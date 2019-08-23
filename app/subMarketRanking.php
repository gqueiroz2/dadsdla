<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\rankingBrand;
use App\region;
use App\brand;
use App\agency;
use App\sql;
use App\base;

class subMarketRanking extends rankingMarket {
    
    public function getSubResults($con, $type, $regionID, $value, $months, $brands, $currency, $filter, $filterType){

    	$sql = new sql();

    	$r = new region();                      
                                
        $tmp = $r->getRegion($con,array($regionID));

        $p = new pRate();

        if(is_array($tmp)){
            $region = $tmp[0]['name'];
        }else{
            $region = $tmp['name'];
        }

        $cYear = intval(date('Y'));
		$years = array($cYear, $cYear-1);

    	if ($filterType == "client") {
    		  
    		$brand = array();

	        for ($i=0; $i < sizeof($brands); $i++) { 
	            array_push($brand, $brands[$i][0]);
	        }

    		if ($type == "agency") {
    			
    			$a = new agency();

    			$oldAgency = $a->getAllAgenciesByName($con, $sql, $filter);

    			if (is_array($oldAgency)) {
		            for ($a=0; $a < sizeof($oldAgency); $a++) { 
		                $val[$a] = $oldAgency[$a]['id'];
		            }    
		        }else{
		            $val = $oldAgency;
		        }
    		}else{
    			$val = $filter;
    		}

    		for ($y=0; $y < sizeof($years); $y++) { 
    		
	    		if ($region == "Brazil") {
	    			$table = "cmaps";
	    		}else{
	    			$table = "ytd";
	    		}
	    		
                if ($table != "cmaps") {
                    if ($currency[0]['name'] == "USD") {
                        $pRate = 1.0;
                    }else{
                        $pRate = $p->getPRateByRegionAndYear($con, array($regionID), array($years[0]));
                    }
                }else{
                    if ($currency[0]['name'] == "USD") {
                        $pRate = $p->getPRateByRegionAndYear($con, array($regionID), array($years[0]));
                    }else{
                        $pRate = 1.0;
                    }
                }

	    		$values[$y] = $this->getSubValues($con, $table, $type, $regionID, $value, $years[$y], $months, $currency[0]['id'], $brand, $val, $filterType);
                
                if (is_array($values[$y])) {
                    for ($v=0; $v < sizeof($values[$y]); $v++) { 
                        if ($table != "cmaps") {
                            $values[$y][$v]['total'] *= $pRate;
                        }else{
                            $values[$y][$v]['total'] /= $pRate;
                        }
                    }
                }
	    	}

    	}else{
    		$brand = $this->mountBrands($brands);
    		
    		$order_by = " (
							CASE brandID

							WHEN '1'
							THEN 1

							WHEN '2'
							THEN 2

							WHEN '3'
							THEN 3

							WHEN '4'
							THEN 4

							WHEN '5'
							THEN 5

							WHEN '6'
							THEN 6

							WHEN '7'
							THEN 7

							WHEN '8'
							THEN 8

							WHEN '9'
							THEN 9

							WHEN '10'
							THEN 10

							WHEN '11'
							THEN 11	

							WHEN '12'
							THEN 12

							END
						)";

    		for ($y=0; $y < sizeof($years); $y++) { 
    			for ($b=0; $b < sizeof($brand); $b++) { 
    				
    				if ($b == 1) {
    					$table = "digital";
    				}elseif ($region == "Brazil") {
    					$table = "cmaps";
    				}else{
    					$table = "ytd";
    				}

    				$infoQuery[$b] = $this->getAllValuesUnion($table, "brand", "brand", $brand[$b], $regionID, $value, $months, $currency[0]['id'], $filter);
    			}
    			
    			if (sizeof($brand) > 1) {
					for ($b=0; $b < sizeof($brand); $b++) {
						array_push($infoQuery[$b]['colsValue'], $years[$y]);
						$where[$b] = $sql->where($infoQuery[$b]['columns'], $infoQuery[$b]['colsValue']);
					}

					$values[$y] = $sql->selectWithUnion($con, $where, $infoQuery, $infoQuery[0]['name'], $order_by, "ASC");

					for ($b=0; $b < sizeof($brand); $b++) { 
						array_pop($infoQuery[$b]['colsValue']);
					}
				}else{
					array_push($infoQuery[0]['colsValue'], $years[$y]);
					$where = $sql->where($infoQuery[0]['columns'], $infoQuery[0]['colsValue']);
					$values[$y] = $sql->selectGroupBy($con, $infoQuery[0]['columns'], $infoQuery[0]['table'], $infoQuery[0]['join'], $where, $order_by, $infoQuery[0]['name'], "ASC");
					array_pop($infoQuery[0]['colsValue']);
				}

				$from = $infoQuery[0]['names'];
				$values[$y] = $sql->fetch($values[$y], $from, $from);

                if ($infoQuery[$y]['table'] == "cmaps a") {
                    if ($currency[0]['name'] == "USD") {
                        $pRate = $p->getPRateByRegionAndYear($con, array($region), array($years[0]));
                    }else{
                        $pRate = 1.0;
                    }
                    
                }else{
                    if ($currency[0]['name'] == "USD") {
                        $pRate = 1.0;
                    }else{
                        $pRate = $p->getPRateByRegionAndYear($con, array($region), array($years[0]));
                    }   
                }

                if (is_array($values[$y])) {
                    for ($v=0; $v < sizeof($values[$y]); $v++) { 
                        if ($infoQuery[$y]['table'] == "cmaps a") {
                            $values[$y][$v]['total'] /= $pRate;    
                        }else{
                            $values[$y][$v]['total'] *= $pRate;
                        }
                    }
                }
    		}
    	}

    	return $values;
    }

    public function getSubValues($con, $tableName, $type, $region, $value, $year, $months, $currency, $brands, $filter, $filterType){
    	
    	$sql = new sql();

    	$as = "total";

        $tableAbv = "a";
        $leftAbv = "b";

		if ($tableName == "ytd") {
            $value .= "_revenue_prate";
            $columns = array("sales_representant_office_id", "month", "year", "brand_id", "agency_id");
            $colsValue = array($region, $months, $year, $brands, $filter);
        }else{
            $columns = array("month", "year", "brand_id");
            $colsValue = array($months, $year, $brands, $filter);

            if ($type == "agency") {
            	array_push($columns, "agency_id");
            }elseif($type == "sector"){
            	array_push($columns, "sector");
            }else{
            	array_push($columns, "category");
            }
        }

        $table = "$tableName $tableAbv";

        $tmp = "$leftAbv.ID AS '".$filterType."ID', $leftAbv.name AS '$filterType', SUM($value) AS '$as'";
        $join = "LEFT JOIN $filterType $leftAbv ON $leftAbv.ID = $tableAbv.".$filterType."_ID";

        if ($tableName == "cmaps") {
            
        }

        $name = $filterType."_id";
		$names = array($filterType."ID", $filterType, $as);

        $where = $sql->where($columns, $colsValue);
    
    	$values = $sql->selectGroupBy($con, $tmp, $table, $join, $where, "total", $name, "DESC");

    	$from = $names;

    	$res = $sql->fetch($values, $from, $from);

    	return $res;
    }

    public function searchPos2($name, $values, $type, $s){
        
        if ($values[0] == false) {
            return ($s+1);
        }else{
            for ($s2=0; $s2 < sizeof($values[0]); $s2++) { 
                if ($name == $values[0][$s2][$type]) {
                    return ($s2+1);
                }
            }
        }
        
        return ($s+1);
    }

    public function checkColumn2($mtx, $m, $name, $values, $years, $p, $type, $s, $values2=null){
        
        if ($mtx[$m][0] == "Ranking") {
            $res = $this->searchPos2($name, $values, $type, $s);
        }elseif ($mtx[$m][0] == "Bookings ".$years[0]) {
            $res = $this->searchValueByYear($name, $values, $type, 0);
        }elseif ($mtx[$m][0] == "Bookings ".$years[1]) {
            $res = $this->searchValueByYear($name, $values, $type, 1);
        }elseif ($mtx[$m][0] == "Var (%)") {
            if ($mtx[$m-2][$p] == 0 || $mtx[$m-1][$p] == 0) {
                $res = 0;
            }else{
                $res = ($mtx[$m-2][$p]/$mtx[$m-1][$p])*100;
            }
        }elseif ($mtx[$m][0] == "Var Abs.") {
            $res = $mtx[$m-3][$p] - $mtx[$m-2][$p];
        }else{
            $res = $name;
        }

        return $res;
    }

    public function assemblerMarketBrand($values, $years, $type){
        
        $mtx[0][0] = "Brand";
        $mtx[1][0] = "Closed ".$years[0];
        $mtx[2][0] = $years[1];
        $mtx[3][0] = "Share Closed";
        $mtx[4][0] = "Share ".$years[1];
        $mtx[5][0] = "% YoY";
        $mtx[6][0] = "Dif. YoY";

        $closed = 0;
        $pClosed = 0;
        
        if ($values[0] == false) {
            $size = $values[1];
        }else{
            $size = $values[0];
        }

        for ($b=0; $b < sizeof($size); $b++) { 
            $mtx[0][$b+1] = $size[$b][$type];

            if ($b < sizeof($size)) {
                $val = $size[$b]['total'];
            }else{
                $val = "-";
            }

            $mtx[1][$b+1] = $val;
            
            if ($val != "-") {
                $closed += $val;    
            }

            if ($values[1] != false) {
                
                if ($b < sizeof($values[1])) {
                    $val = $values[1][$b]['total'];
                }else{
                    $val = "-";
                }
            }else{
                $val = "-";
            }
            

            $mtx[2][$b+1] = $val;

            if ($val != "-") {
                $pClosed += $val;   
            }
        }

        $closedP = 0;
        $pClosedP = 0;

        $total = array();

        if (sizeof($size) > 1) {
            array_push($total, "DN");
            array_push($total, $closed);
            array_push($total, $pClosed);

            for ($b=0; $b < sizeof($size); $b++) {
                
                if ($mtx[1][$b+1] != "-") {
                    $val = ($mtx[1][$b+1] / $closed)*100;
                    $closedP += $val;
                }else{
                    $val = "-";
                }

                $mtx[3][$b+1] = $val;

                if ($mtx[2][$b+1] != "-") {
                    $val = ($mtx[2][$b+1] / $pClosed)*100;
                    $pClosedP += $val;
                }else{
                    $val = "-";
                }

                $mtx[4][$b+1] = $val;

                if ($mtx[1][$b+1] == "-" || $mtx[2][$b+1] == "-") {
                    if ($mtx[1][$b+1] == "-") {
                        $val2 = (0 - $mtx[2][$b+1]);   
                    }elseif ($mtx[2][$b+1] == "-") {
                        $val2 = ($mtx[1][$b+1] - 0);   
                    }else{
                        $val2 = "-";
                    }
                    $val = "-";
                }else{
                    $val2 = ($mtx[1][$b+1] - $mtx[2][$b+1]);
                    $val = ($mtx[1][$b+1] / $mtx[2][$b+1])*100;
                }

                $mtx[5][$b+1] = $val;
                $mtx[6][$b+1] = $val2;
            }

            array_push($total, $closedP);
            array_push($total, $pClosedP);

            $size = sizeof($mtx[0]);

            if ($closed == 0 || $pClosed == 0) {
                $val = 0.0;    
            }else{
                $val = ($closed / $pClosed)*100;
            }

            array_push($total, $val);

            $val = ($closed - $pClosed);
            array_push($total, $val);
        }else{

            $total = null;

            for ($b=0; $b < sizeof($size); $b++) {
                
                if ($mtx[1][$b+1] != "-") {
                    $val = ($mtx[1][$b+1] / $closed)*100;
                }else{
                    $val = "-";
                }

                $mtx[3][$b+1] = $val;

                if ($mtx[2][$b+1] != "-") {
                    $val = ($mtx[2][$b+1] / $pClosed)*100;
                }else{
                    $val = "-";
                }

                $mtx[4][$b+1] = $val;

                if ($mtx[1][$b+1] == "-" || $mtx[2][$b+1] == "-") {
                    if ($mtx[1][$b+1] == "-") {
                        $val2 = (0 - $mtx[2][$b+1]);   
                    }elseif ($mtx[2][$b+1] == "-") {
                        $val2 = ($mtx[1][$b+1] - 0);   
                    }else{
                        $val2 = "-";
                    }
                    $val = "-";
                }else{
                    $val2 = ($mtx[1][$b+1] - $mtx[2][$b+1]);
                    $val = ($mtx[1][$b+1] / $mtx[2][$b+1])*100;
                }

                $mtx[5][$b+1] = $val;
                $mtx[6][$b+1] = $val2;
            }
        }
        
        return array($mtx, $total);
    }

    public function subAssemblerMarketTotal($mtx){
        
        $total[0] = "Total";

        $first = 0;
        $second = 0;

        $pos = 2;

        for ($m=1; $m < sizeof($mtx[0]); $m++) { 
            $first += $mtx[$pos][$m];
            $second += $mtx[$pos+1][$m];
        }

        for ($m=1; $m < sizeof($mtx); $m++) { 

            if ($m == $pos || $m == ($pos+1)) {
                
                if ($m == $pos) {
                    $total[$m] = $first;
                }else{
                    $total[$m] = $second;
                }
            }elseif ($mtx[$m][0] == "Var (%)") {
                if ($total[$m-1] != 0 && $total[$m-2] != 0) {
                    $total[$m] = ($total[$pos] / $total[$pos+1])*100;
                }else{
                    $total[$m] = 0;
                }
            }elseif ($mtx[$m][0] == "Var Abs.") {
                $total[$m] = $total[$m-3] - $total[$m-2];
            }else{
                $total[$m] = "-";
            }
        }

        return $total;
    }

    public function subMarketAssembler($values, $valuesTotal, $type, $brands, $typeF){
        
        $cYear = intval(date('Y'));
        $years = array($cYear, $cYear-1);

        if ($type == "client") {
            $mtx = $this->assemblerMarketBrand($values, $years, $typeF);

            return $mtx;
        }else{  
            $mtx[0][0] = "Ranking";
            $mtx[1][0] = "Client";
            $mtx[2][0] = "Bookings ".$years[0];
            $mtx[3][0] = "Bookings ".$years[1];
            $mtx[4][0] = "Var (%)";
            $mtx[5][0] = "Var Abs.";
    
            $types = array();

            for ($r=0; $r < sizeof($values); $r++) { 
                if (is_array($values[$r])) {
                    for ($r2=0; $r2 < sizeof($values[$r]); $r2++) { 
                        if (!in_array($values[$r][$r2][$typeF], $types)) {
                            array_push($types, $values[$r][$r2][$typeF]);  
                        }
                    }
                }
            }

            $size = sizeof($types);

            for ($s=0; $s < $size; $s++) { 
                for ($m=0; $m < sizeof($mtx); $m++) {
                    array_push($mtx[$m], $this->checkColumn2($mtx, $m, $types[$s], $values, $years, sizeof($mtx[$m]), $typeF, $s, $valuesTotal));
                }
            }

            $total = $this->subAssemblerMarketTotal($mtx);

            return array($mtx, $total);

        }


    }

    public function renderSubAssembler($mtx, $total, $type, $years){

        echo "<div class='container-fluid'>";
            echo "<div class='row mt-2 mb-2 justify-content-center'>";
                echo "<div class='col'>";
                    echo "<table style='width: 100%; zoom:100%; font-size: 16px;border: 2px solid black;'>";
                    
                    if (is_string($mtx)) {
                        echo "<tr>";
                            echo "<td class='center' style='color: red;'> ".$mtx." </td>";
                        echo "</tr>";
                    }else{
                        for ($m=0; $m < sizeof($mtx[0]); $m++) { 
                            echo "<tr>";    
                            
                            if ($m == 0) {
                                $color = 'lightBlue';
                            }elseif ($m%2 == 0) {
                                $color = 'medBlue';
                            }else{
                                $color = 'rcBlue';  
                            }

                            for ($n=0; $n < sizeof($mtx); $n++) { 
                                if ($m == 0) {
                                    echo "<td class='$color center'> ".$mtx[$n][$m]." </td>";
                                }else{
                                    if (is_numeric($mtx[$n][$m])) {
                                        if ($mtx[$n][0] == "Var (%)" || $mtx[$n][0] == "Var YTD (%)" || $mtx[$n][0] == "Share Closed" || $mtx[$n][0] == "Share ".$years[1] || $mtx[$n][0] == "% YoY") {
                                            echo "<td class='$color center'> ".number_format($mtx[$n][$m])." %</td>";   
                                        }elseif ($mtx[$n][0] == "Ranking") {
                                            echo "<td class='$color center'> ".number_format($mtx[$n][$m])."º</td>";
                                        }else{
                                            echo "<td class='$color center'> ".number_format($mtx[$n][$m])." </td>";
                                        }
                                    }else{
                                        echo "<td class='$color center'> ".$mtx[$n][$m]." </td>";
                                    }
                                }
                            }

                            echo "</tr>";
                        }

                        if (!is_null($total)) {

                            echo "<tr>";

                            if ($type == "client") {
                                $pos = 3;
                                $pos2 = 4;
                                $pos3 = 5;
                            }else{
                                $pos = 4;
                                $pos2 = 9;
                                $pos3 = -1;
                            }
                            
                            for ($t=0; $t < sizeof($total); $t++) {
                                if ($t == $pos || $t == $pos2 || $t == $pos3) {
                                    echo "<td class='darkBlue center'> ".number_format($total[$t])." %</td>";
                                }
                                elseif (is_numeric($total[$t])) {
                                    echo "<td class='darkBlue center'> ".number_format($total[$t])." </td>";
                                }else{
                                    echo "<td class='darkBlue center'> ".$total[$t]." </td>";
                                }
                                
                            }

                            echo "</tr>";
                        }
                    }

                    echo "</table>";
               echo "</div>";
           echo "</div>";
       echo "</div>";

    }

}
