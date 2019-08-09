<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\rankingBrand;
use App\region;
use App\brand;

class subBrandRanking extends rankingBrand {
    
	public function getSubResults($con, $type, $regionID, $value, $months, $currency, $filter){
		
		$cYear = intval(date('Y'));
		$years = array($cYear, $cYear-1);

		$p = new pRate();

        if ($currency[0]['name'] == "USD") {
            $pRate = 1.0;
        }else{
            $pRate = $p->getPRateByRegionAndYear($con, array($regionID), array($years[0]));
        }

        $b = new brand();

        if ($filter != 'DN') {
            $brand = $b->getBrandID($con, $filter);
        }else{
            $brands = $b->getBrand($con);
            //var_dump($brand);
            $brand = array();
            $brand[0]['id'] = array();

            for ($i=0; $i < sizeof($brands); $i++) { 
                array_push($brand[0]['id'], $brands[$i]['id']);
            }
        }
        

        $r = new region();

        $tmp = $r->getRegion($con,array($regionID));

        if(is_array($tmp)){
            $region = $tmp[0]['name'];
        }else{
            $region = $tmp['name'];
        }

        for ($y=0; $y < sizeof($years); $y++) {
        	
        	if ($filter == "VIX" || $filter == "ONL") {
        		$table = "digital";
        	}elseif ($region == "Brazil" && ($years[$y] == $cYear)) {
				$table = "cmaps";
			}else{
				$table = "ytd";
			}

			$res[$y] = $this->getSubValues($con, $table, $type, $regionID, $value, $years[$y], $months, $currency, $brand[0]['id']);

			
			if (is_array($res[$y])) {
            	for ($r=0; $r < sizeof($res[$y]); $r++) {
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
                    $res[$y][$r]['total'] *= $pRate;
            	}
        	}	
			
        }

        return $res;

	}

    public function getSubValues($con, $tableName, $type, $region, $value, $year, $months, $currency, $filter){

    	$sql = new sql();

    	$as = "total";

        $tableAbv = "a";
        $leftAbv = "b";

        if ($tableName == "ytd") {
            $value .= "_revenue_prate";
            $columns = array("sales_representant_office_id", "month", "year", "brand_id");
            $colsValue = array($region, $months, $year, $filter);
        }elseif ($tableName == "digital") {
            $value .= "_revenue";
            $columns = array("campaign_sales_office_id", "month", "year", "brand_id");
            $colsValue = array($region, $months, $year, $filter);
        }elseif ($tableName == "plan_by_brand") {
            $columns = array("sales_office_id","type_of_revenue", "month", "year", "brand_id");
            $colsValue = array($region, $value, $months, $year, $filter);
            $value = "revenue";
        }else{
            $columns = array("month", "year", "brand_id");
            $colsValue = array($months, $year, $filter);
        }

        $table = "$tableName $tableAbv";

        $leftName = $type;

        $name = $type."_id";

        if ($type == "agency") {
        	
        	$leftName2 = "agency_group";
        	$leftAbv2 = "c";

        	$tmp = $leftAbv.".ID AS '".$type."ID', ".$leftAbv.".name AS '".$type."', ".$leftAbv2.".name AS 'agencyGroup', SUM($value) AS $as";

            $join = "LEFT JOIN ".$leftName." ".$leftAbv." ON ".$leftAbv.".ID = ".$tableAbv.".".$type."_id
                    LEFT JOIN ".$leftName2." ".$leftAbv2." ON ".$leftAbv2.".ID = ".$leftAbv.".".$leftName2."_id";

            $names = array($type."ID", $type, "agencyGroup", $as);

        }elseif ($type == "sector" || $type == "category") {
			$tmp = $tableAbv.".".$type." AS '".$type."', SUM($value) AS $as";
			$join = null;
			$name = $type;
			$names = array($type, $as);
        }else{
        	$join = "LEFT JOIN ".$leftName." ".$leftAbv." ON ".$leftAbv."."."ID = ".$tableAbv.".".$type."_id";

        	$tmp = $tableAbv.".".$type."_id AS '".$type."ID', ".$leftAbv."."."name AS '".$type."', SUM($value) AS $as";

        	$names = array($type."ID", $type, $as);
        }

        $where = $sql->where($columns, $colsValue);

        $values = $sql->selectGroupBy($con, $tmp, $table, $join, $where, "total", $name, "DESC");

        $from = $names;

        $res = $sql->fetch($values, $from, $from);

        //var_dump($res);
        return $res;
    }

    public function searchGroupValue($name, $sub){
    	
    	for ($s=0; $s < sizeof($sub); $s++) { 
    		for ($s2=0; $s2 < sizeof($sub[$s]); $s2++) { 
    			if ($name == $sub[$s][$s2]['agency']) {
    				if ($sub[$s][$s2]['agencyGroup'] == "Others") {
    					return "-";
    				}else{
    					return $sub[$s][$s2]['agencyGroup'];
    				}
    			}
    		}
    	}
    }

	public function searchNameValue($name, $sub, $type){
    	
    	for ($s=0; $s < sizeof($sub); $s++) { 
    		for ($s2=0; $s2 < sizeof($sub[$s]); $s2++) { 
    			if ($name == $sub[$s][$s2][$type]) {
					return $sub[$s][$s2][$type];
    			}
    		}
    	}
    }

    public function searchYearValue($name, $sub, $type, $y){
    	if (is_array($sub[$y])) {
    		for ($s=0; $s < sizeof($sub[$y]); $s++) { 
				if ($name == $sub[$y][$s][$type]) {
					return $sub[$y][$s]['total'];
				}
    		}	
    	}
    	

    	return 0;
    }

    public function existInYear($name, $sub, $type, $y){
    
    	if (is_array($sub[$y])) {
    		for ($s=0; $s < sizeof($sub[$y]); $s++) { 
				if ($name == $sub[$y][$s][$type]) {
					return true;
				}
    		}
    	}

    	return false;

    }

    public function checkColumn($mtx, $m, $name, $sub, $years, $p, $type){

    	if ($type == "agency" && $m == 0) {
    		$res = $this->searchGroupValue($name, $sub);
    	}elseif ($mtx[$m][0] == $years[0]) {
    		$res = $this->searchYearValue($name, $sub, $type, 0);
    	}elseif ($mtx[$m][0] == $years[1]) {
    		$res = $this->searchYearValue($name, $sub, $type, 1);
    	}elseif ($mtx[$m][0] == "Var(%)") {
    		if (($mtx[$m-1][$p] == 0) || ($mtx[$m-2][$p] == 0)) {
    			$res = 0;
    		}else{
    			$res = ($mtx[$m-2][$p] / $mtx[$m-1][$p])*100;
    		}
    	}elseif ($mtx[$m][0] == "Type") {
    		if ($mtx[$m-3][$p] == 0 && $mtx[$m-2][$p] > 0) {
    			$res = "Churn";
    		}elseif ($mtx[$m-3][$p] > 0 && $mtx[$m-2][$p] == 0) {
    			if ($this->existInYear($name, $sub, $type, 1)) {
    				$res = "Recovered";
    			}else{
    				$res = "New";
    			}
    		}else{
    			$res = "Renovated";
    		}
    	}elseif($mtx[$m][0] == "Move"){
    		if ($mtx[$m-4][$p]-$mtx[$m-3][$p] > 0) {
    			$res = "Increased";
    		} else {
    			$res = "Decreased";
    		}
    	}else{
    		$res = $this->searchNameValue($name, $sub, $type);
    	}

    	return $res;
    	
    }

    public function subAssemblerTotal($mtx, $type){

    	$first = 0;
    	$second = 0;

    	if ($type == "agency") {
    		$pos1 = 2;
    		$pos2 = 3;
    	}else{
    		$pos1 = 1;
    		$pos2 = 2;
    	}

    	for ($m=1; $m < sizeof($mtx[0]); $m++) { 
    		$first += $mtx[$pos1][$m];
    		$second += $mtx[$pos2][$m];
    	}

    	for ($t=0; $t < sizeof($mtx); $t++) { 

    		$total[$t] = 0;

    		if ($t == 0) {
    			$val = "Total";
    		}elseif ($t == $pos1 || $t == $pos2) {
    			if ($t == $pos1) {
    				$val = $first;
    			}else{
    				$val = $second;
    			}
    		}elseif ($mtx[$t][0] == "Var(%)") {
    			if ($total[$t-1] != 0 && $total[$t-2] != 0) {
    				$val = ($total[$pos1] / $total[$pos2])*100;
    			}else{
    				$val = 0;
    			}
    		}else{
    			$val = " ";
    		}

    		$total[$t] = $val;

    	}


    	return $total;
    }

    public function assemble($names, $values, $type){
    	
    	$cYear = intval(date('Y'));
		$years = array($cYear, $cYear-1);

		$pos = 0;

		if ($type == "agency") {
			$mtx[$pos][0] = "Agency Group";
			$pos++;
		}

		$mtx[$pos][0] = ucfirst($type);$pos++;

		$mtx[$pos][0] = $years[0];$pos++;
		$mtx[$pos][0] = $years[1];$pos++;
		$mtx[$pos][0] = "Var(%)";$pos++;
		$mtx[$pos][0] = "Type";$pos++;
		$mtx[$pos][0] = "Move";

		for ($n=0; $n < sizeof($names); $n++) { 
			for ($m=0; $m < sizeof($mtx); $m++) { 
				array_push($mtx[$m], $this->checkColumn($mtx, $m, $names[$n], $values, $years, sizeof($mtx[$m]), $type));
			}
		}
		
	    $total = $this->subAssemblerTotal($mtx, $type);
		
		return array($mtx, $total);
    }

    public function renderSubAssembler($mtx, $total, $type, $brand){
    	
    	if ($type == "agency") {
			$pos = 4;
		}else{
			$pos = 3;
		}

    	echo "<div class='container-fluid'>";
            echo "<div class='row mt-2 mb-2 justify-content-center'>";
                echo "<div class='col'>";
                if ($brand == "DN") {
                    echo "<table style='width: 100%; zoom:100%; font-size: 16px;border: 2px solid black; color: black; !important'>";
                }else{
                    echo "<table style='width: 100%; zoom:100%; font-size: 16px;border: 2px solid black;'>";
                }

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
		        					if ($n == $pos) {
		        						echo "<td class='$color center'> ".number_format($mtx[$n][$m])." %</td>";	
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

		            echo "<tr>";

		            for ($t=0; $t < sizeof($total); $t++) {
		            	if ($t == $pos) {
		            		echo "<td class='darkBlue center'> ".number_format($total[$t])." %</td>";
		            	}
		            	elseif (is_numeric($total[$t])) {
		            		echo "<td class='darkBlue center'> ".number_format($total[$t])." </td>";
		            	}else{
		            		echo "<td class='darkBlue center'> ".$total[$t]." </td>";
		            	}
		            	
		            }

		            echo "</tr>";

                    echo "</table>";
               echo "</div>";
           echo "</div>";
       echo "</div>";

    }
}
