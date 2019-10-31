<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\rankingBrand;
use App\region;
use App\brand;

class subBrandRanking extends rankingBrand {
    
	public function getSubResults($con, $type, $regionID, $value, $months, $currency, $filter, $brands){
		
		$cYear = intval(date('Y'));
		$years = array($cYear, $cYear-1, $cYear-2);

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
            $onl = false;
            $vix = false;

            $brand = array();
            $brand[0]['id'] = array();

            for ($i=0; $i < sizeof($brands); $i++) {
                if ($brands[$i][1] == 'ONL') {
                    $onl = true;
                }

                if ($brands[$i][1] == 'VIX') {
                    $vix = true;
                }
                array_push($brand[0]['id'], $brands[$i][0]);
            }
        }
        
        $r = new region();

        $tmp = $r->getRegion($con,array($regionID));

        if(is_array($tmp)){
            $region = $tmp[0]['name'];
        }else{
            $region = $tmp['name'];
        }
        
        if ($filter != "DN") {
            for ($y=0; $y < sizeof($years); $y++) {
            
                if ($filter == "VIX" || $filter == "ONL") {
                    $table = "fw_digital";
                }elseif ($region == "Brazil") {
                    $table = "cmaps";
                }else{
                    $table = "ytd";
                }

                $res[$y] = $this->getSubValues($con, $table, $type, $regionID, $value, $years[$y], $months, $currency, $brand);
                
                if (is_array($res[$y])) {
                    for ($r=0; $r < sizeof($res[$y]); $r++) {
                        if ($table != "cmaps") {
                            if ($currency[0]['name'] == "USD") {
                                $pRate = 1.0;
                            }else{
                                $pRate = $p->getPRateByRegionAndYear($con, array($regionID), array($years[0]));
                            }

                            $res[$y][$r]['total'] *= $pRate;
                        }else{
                            if ($currency[0]['name'] == "USD") {
                                $pRate = $p->getPRateByRegionAndYear($con, array($regionID), array($years[0]));
                            }else{
                                $pRate = 1.0;
                            } 

                            $res[$y][$r]['total'] /= $pRate;                       
                        }
                        
                    }
                }

            }
        }else{

            for ($y=0; $y < sizeof($years); $y++) {
                if ($region == "Brazil") {
                    $table = "cmaps";
                }else{
                    $table = "ytd";
                }

                $res[$y] = $this->getSubValues($con, $table, $type, $regionID, $value, $years[$y], $months, $currency, $brand);

                if (is_array($res[$y])) {
                    for ($r=0; $r < sizeof($res[$y]); $r++) {
                        if ($table != "cmaps") {
                            if ($currency[0]['name'] == "USD") {
                                $pRate = 1.0;
                            }else{
                                $pRate = $p->getPRateByRegionAndYear($con, array($regionID), array($years[0]));
                            }

                            $res[$y][$r]['total'] *= $pRate;
                        }else{
                            if ($currency[0]['name'] == "USD") {
                                $pRate = $p->getPRateByRegionAndYear($con, array($regionID), array($years[0]));
                            }else{
                                $pRate = 1.0;
                            } 

                            $res[$y][$r]['total'] /= $pRate;                       
                        }
                        
                    }
                }
            }

            if ($onl || $vix) {
                for ($y=0; $y < sizeof($years); $y++) {
                    
                    $table = "fw_digital";

                    $resDV = false;
                    $resDO = false;

                    if ($vix) {
                        $tmp = array();
                        $tmp[0] = array('id' => '9');

                        $resDV = array();
                        $resDV[$y] = $this->getSubValues($con, $table, $type, $regionID, $value, $years[$y], $months, $currency, $tmp);   
                    }

                    if ($onl) {
                        $tmp = array();
                        $tmp[0] = array('id' => '10');

                        $resDO = array();
                        $resDO[$y] = $this->getSubValues($con, $table, $type, $regionID, $value, $years[$y], $months, $currency, $tmp);
                    }

                    if ($resDV[$y] && $resDO[$y]) {
                        
                        $size1 = sizeof($resDO[$y]);
                        $size2 = sizeof($resDV[$y]);

                        for ($r=0; $r < $size1; $r++) { 
                            for ($r2=0; $r2 < $size2; $r2++) {
                                if ($resDO[$y][$r][$type."ID"] == $resDV[$y][$r2][$type."ID"]) {
                                    $resDV[$y][$r2]['total'] += $resDO[$y][$r]['total'];

                                    unset($resDO[$y][$r]);
                                    break;
                                }
                            }
                        }

                        $resDO[$y] = array_values($resDO[$y]);

                        if (is_array($resDO)) {
                            for ($r=0; $r < sizeof($resDO[$y]); $r++) { 
                                array_push($resDV[$y], $resDO[$y][$r]);
                            }   
                        }

                        usort($resDV[$y], array($this,'compare'));
                        
                        if (is_array($resDV[$y])) {
                            for ($r=0; $r < sizeof($resDV[$y]); $r++) {
                                if ($currency[0]['name'] == "USD") {
                                    $pRate = 1.0;
                                }else{
                                    $pRate = $p->getPRateByRegionAndYear($con, array($regionID), array($years[0]));
                                } 

                                $resDV[$y][$r]['total'] *= $pRate;
                            }
                        }
                    }

                    if ($resDV[$y] && $res[$y]) {
                        $size1 = sizeof($resDV[$y]);
                        $size2 = sizeof($res[$y]);

                        for ($r=0; $r < $size1; $r++) { 
                            for ($r2=0; $r2 < $size2; $r2++) {
                                if ($resDV[$y][$r][$type."ID"] == $res[$y][$r2][$type."ID"]) {
                                    $res[$y][$r2]['total'] += $resDV[$y][$r]['total'];

                                    unset($resDV[$y][$r]);
                                    break;
                                }
                            }
                        }

                        $resDV[$y] = array_values($resDV[$y]);

                        for ($r=0; $r < sizeof($resDV[$y]); $r++) { 
                            array_push($res[$y], $resDV[$y][$r]);
                        }

                        usort($res[$y], array($this,'compare'));
                    }elseif($resDV[$y] && !$res[$y]){
                        $res[$y] = $resDV[$y];
                    }
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
            $colsValue = array($region, $months, $year, $filter[0]['id']);
            $where = $sql->where($columns, $colsValue);
        }elseif ($tableName == "fw_digital") {
            $value .= "_revenue";
            
            if ($filter[0]['id'] == '9') {
                
                $where = "WHERE (a.region_id = \"$region\") AND (year = \"$year\") AND (brand_id != \"10\") AND (month IN (";
                    for ($m=0; $m < sizeof($months); $m++) { 
                        if ($m == sizeof($months)-1) {
                            $where .= "'".$months[$m]."'";
                        }else{
                            $where .= "'".$months[$m]."',";   
                        }
                    }
                $where .= "))";
            }else{
                $columns = array("a.region_id", "month", "year", "brand_id");
                $colsValue = array($region, $months, $year, $filter[0]['id']);
                $where = $sql->where($columns, $colsValue);
            }
        }elseif ($tableName == "plan_by_brand") {
            $columns = array("sales_office_id","type_of_revenue", "month", "year", "brand_id");
            $colsValue = array($region, $value, $months, $year, $filter[0]['id']);
            $value = "revenue";
            $where = $sql->where($columns, $colsValue);
        }else{
            $columns = array("month", "year", "brand_id");
            $colsValue = array($months, $year, $filter[0]['id']);
            $where = $sql->where($columns, $colsValue);
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
        
        $values = $sql->selectGroupBy($con, $tmp, $table, $join, $where, "total", $name, "DESC");

        $from = $names;

        $res = $sql->fetch($values, $from, $from);

        return $res;
    }

    public function searchGroupValue($name, $sub){
    	
    	for ($s=0; $s < sizeof($sub); $s++) {
            if (is_array($sub[$s])) {
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
    }

	public function searchNameValue($name, $sub, $type){
    	
        $bool = -1;
        $bool2 = -1;
        $bool3 = -1;

        if (is_array($sub[0])) {
            for ($v=0; $v < sizeof($sub[0]); $v++) { 
                if ($sub[0][$v][$type] == $name) {
                    $bool = 0;
                    if ($sub[0][$v]['total'] == 0) {
                        $bool = 1;
                    }else{
                        $bool = 2;
                    }
                }
            }   
        }

        if (is_array($sub[1])) {
            for ($v=0; $v < sizeof($sub[1]); $v++) { 
                if ($sub[1][$v][$type] == $name) {
                    $bool2 = 0;
                    if ($sub[1][$v]['total'] == 0) {
                        $bool2 = 1;
                    }else{
                        $bool2 = 2;
                    }
                }
            }
        }

        if (is_array($sub[2])) {
            for ($v=0; $v < sizeof($sub[2]); $v++) { 
                if ($sub[2][$v][$type] == $name) {
                    $bool2 = 0;
                    if ($sub[2][$v]['total'] == 0) {
                        $bool3 = 1;
                    }else{
                        $bool3 = 2;
                    }
                }
            }
        }

        if ($bool == -1 || $bool == 1) {
            if ($bool2 == -1 || $bool2 == 1) {
            	if ($bool3 == -1 || $bool3 == 1) {
            		$res = -1;	
            	}
            }else{
                $res = $name;
            }
        }else{
            $res = $name;
        }

        return $res;
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

    public function checkClass($valCyear, $valPyear, $valPPyear){
    
    	if ($valPPyear > 0) { //ANO RETRASADO > 0
            if ($valPyear == 0) { // ANO PASSADO = 0
                if ($valCyear == 0) { //ANO CORRENTE = 0
                    $res = "Churn";
                }else{ //ANO CORRENTE > 0
                    $res = "Recovered";
                }
            }else{ //ANO PASSADO > 0
                if ($valCyear == 0) { //ANO CORRENTE = 0
                    $res = "Churn";
                }else{ //ANO CORRENTE > 0
                    $res = "Renewed";
                }
            }
        }else{ //ANO RETRASADO = 0
            if ($valPyear > 0) { //ANO PASSADO > 0
                if ($valCyear > 0) { //ANO CORRENTE > 0
                    $res = "Renewed";
                }else{ //ANO CORRENTE = 0
                    $res = "Churn";
                }
            }else{ //ANO PASSADO = 0
                if ($valCyear > 0) { //ANO CORRENTE > 0
                    $res = "New";
                }else{
                    $res = "Churn";
                }
            }
        }

        return $res;

    }

    public function checkColumn($mtx, $m, $name, $sub, $years, $p, $type){

    	if ($type == "agency" && $m == 1) {
    		$res = $this->searchGroupValue($name, $sub);
    	}elseif ($mtx[$m][0] == "Bookings ".$years[0]) {
    		$res = $this->searchYearValue($name, $sub, $type, 0);
    	}elseif ($mtx[$m][0] == "Bookings ".$years[1]) {
    		$res = $this->searchYearValue($name, $sub, $type, 1);
    	}elseif ($mtx[$m][0] == "Bookings ".$years[2]) {
            $res = $this->searchYearValue($name, $sub, $type, 2);
        }elseif ($mtx[$m][0] == "Var(%)") {
    		if (($mtx[$m-2][$p] == 0) || ($mtx[$m-3][$p] == 0)) {
    			$res = 0;
    		}else{
    			$res = ($mtx[$m-3][$p] / $mtx[$m-2][$p])*100;
    		}
    	}elseif ($mtx[$m][0] == "Class") {
    		$res = $this->checkClass($mtx[$m-4][$p], $mtx[$m-3][$p], $mtx[$m-2][$p]);
    	}elseif($mtx[$m][0] == "Move"){
    		if ($mtx[$m-5][$p]-$mtx[$m-4][$p] > 0) {
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
        $third = 0;

    	if ($type == "agency") {
    		$pos1 = 2;
    		$pos2 = 3;
            $pos3 = 4;
    	}else{
    		$pos1 = 1;
    		$pos2 = 2;
            $pos3 = 3;
    	}

    	for ($m=1; $m < sizeof($mtx[0]); $m++) { 
    		$first += $mtx[$pos1][$m];
    		$second += $mtx[$pos2][$m];
            $third += $mtx[$pos3][$m];
    	}

    	for ($t=0; $t < sizeof($mtx); $t++) { 

    		$total[$t] = 0;

    		if ($t == 0) {
    			$val = "Total";
    		}elseif ($t == $pos1 || $t == $pos2 || $t == $pos3) {
    			if ($t == $pos1) {
    				$val = $first;
    			}elseif ($t == $pos2) {
                    $val = $second;
                }else{
    				$val = $third;
    			}
    		}elseif ($mtx[$t][0] == "Var(%)") {
    			if ($total[$pos1] != 0 && $total[$pos2] != 0) {
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
		$years = array($cYear, $cYear-1, $cYear-2);

		$pos = 0;

		if ($type == "agency") {
			$mtx[$pos][0] = "Agency";$pos++;
            $mtx[$pos][0] = "Agency Group";$pos++;
		}else{
            $mtx[$pos][0] = ucfirst($type);$pos++;
        }

		$mtx[$pos][0] = "Bookings ".$years[0];$pos++;
		$mtx[$pos][0] = "Bookings ".$years[1];$pos++;
        $mtx[$pos][0] = "Bookings ".$years[2];$pos++;
		$mtx[$pos][0] = "Var(%)";$pos++;
		$mtx[$pos][0] = "Class";$pos++;
		$mtx[$pos][0] = "Move";
        
		for ($n=0; $n < sizeof($names); $n++) { 
			for ($m=0; $m < sizeof($mtx); $m++) {

                $res = $this->checkColumn($mtx, $m, $names[$n], $values, $years, sizeof($mtx[$m]), $type);

                if ($res == -1) {
                    break;
                }else{
                    array_push($mtx[$m], $res);
                }
			}
		}

        if ($type == "agency") {
            $aux = $mtx[0];
            $auxGroup = $mtx[1];

            $mtx[0] = $auxGroup;
            $mtx[1] = $aux;
        }

	    $total = $this->subAssemblerTotal($mtx, $type);
		
		return array($mtx, $total);
    }

    public function renderSubAssembler($mtx, $total, $type, $brand, $brands){
    	
    	if ($type == "agency") {
			$pos = 5;
		}else{
			$pos = 4;
		}

    	echo "<div class='container-fluid'>";
            echo "<div class='row mt-2 mb-2 justify-content-center'>";
                echo "<div class='col'>";
                if ($brand == "DN") {
                    echo "<table style='width: 100%; zoom:100%; font-size: 16px;border: 1px solid white; color: black; !important'>";
                }elseif (sizeof($brands) == 1) {
                    echo "<table style='width: 100%; zoom:100%; font-size: 16px;border: 1px solid white; color: black; !important'>";
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
		        						echo "<td class='$color center'> ".number_format($mtx[$n][$m],0,',','.')." %</td>";	
		        					}else{
                                        if ($mtx[$n][$m] == 0) {
                                            echo "<td class='$color center'> - </td>";
                                        }else{
                                            echo "<td class='$color center'> ".number_format($mtx[$n][$m],0,',','.')." </td>";
                                        }
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
		            		echo "<td class='darkBlue center'> ".number_format($total[$t],0,',','.')." %</td>";
		            	}
		            	elseif (is_numeric($total[$t])) {
		            		echo "<td class='darkBlue center'> ".number_format($total[$t],0,',','.')." </td>";
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
