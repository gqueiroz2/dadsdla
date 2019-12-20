<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\rankingBrand;
use App\subBrandRanking;
use App\region;
use App\brand;
use App\agency;
use App\sql;
use App\base;

class subMarketRanking extends rankingMarket {
    
    public function sortDigitalBrands($brands){
        
        $ids = array();

        for ($i=0; $i < sizeof($brands); $i++) { 
            $ids[$i] = $brands[$i]['id'];
        }

        $ids = array_unique($ids);

        sort($ids);

        $rtr = array();

        $c = 0;

        while ($c < sizeof($brands)) {
            for ($i=0; $i < sizeof($brands); $i++) { 
                if ($brands[$i]['id'] == $ids[$c]) {
                    array_push($rtr, $brands[$i]['brand']);
                    $c++;
                    break;
                }
            }   
        }

        return $rtr;
    }

    public function getSubResults($con, $type, $regionID, $value, $months, $brands, $currency, $filter, $filterType, $auxName){

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

    		if ($type == "agency") {
    			
    			$a = new agency();

    			$oldAgency = $a->getAllAgenciesByName($con, $sql, $filter, $auxName);

    			if (is_array($oldAgency)) {
		            for ($a = 0; $a < sizeof($oldAgency); $a++) { 
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

	    		$values[$y] = $this->getSubValues($con, $table, $type, $regionID, $value, $years[$y], $months, $currency, $brands, $val, $filterType);
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
    					$table = "fw_digital";
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
                
                if (is_array($values[$y])) {
                    $size = sizeof($values[$y]);
                    $sum = 0;
                    $check = false;

                    for ($r=0; $r < $size; $r++) { 

                        if ($values[$y][$r]['brand'] == 'ONL-SM') {
                            $check = true;
                            $sum += $values[$y][$r]['total'];
                            unset($values[$y][$r]);
                        }elseif ($values[$y][$r]['brand'] == 'ONL') {
                            $check = true;
                            $sum += $values[$y][$r]['total'];
                            unset($values[$y][$r]);
                        }elseif ($values[$y][$r]['brand'] == 'ONL-DSS') {
                            $check = true;
                            $sum += $values[$y][$r]['total'];
                            unset($values[$y][$r]);
                        }elseif ($values[$y][$r]['brand'] == 'VOD') {
                            $check = true;
                            $sum += $values[$y][$r]['total'];
                            unset($values[$y][$r]);
                        }
                    }

                    if ($check) {
                        $values[$y] = array_values($values[$y]);
                        $aux = array("brandID" => "9" ,"brand" => "ONL", "total" => $sum);
                        array_push($values[$y], $aux);
                    }   
                }

                for ($b=0; $b < sizeof($infoQuery); $b++) {

                    if ($infoQuery[$b]['table'] == "cmaps a") {
                        if ($currency[0]['name'] == "USD") {
                            $pRate = $p->getPRateByRegionAndYear($con, array($regionID), array($years[0]));
                        }else{
                            $pRate = 1.0;
                        }
                    }else{
                        if ($currency[0]['name'] == "USD") {
                            $pRate = 1.0;
                        }else{
                            $pRate = $p->getPRateByRegionAndYear($con, array($regionID), array($years[0]));
                        }
                    }
                    
                    if (is_array($values[$y])) {
                        for ($i=0; $i < sizeof($values[$y]); $i++) {
                            if ($infoQuery[$b]['table'] == "cmaps a") {

                                if ($values[$y][$i]['brand'] != 'ONL' && $values[$y][$i]['brand'] != 'VIX') {
                                    $values[$y][$i]['total'] /= $pRate;
                                }else{
                                    $values[$y][$i]['total'] /= 1.0;
                                }

                            }elseif ($infoQuery[$b]['table'] == "ytd a") {
                                
                                if ($values[$y][$i]['brand'] != 'ONL' && $values[$y][$i]['brand'] != 'VIX') {
                                    $values[$y][$i]['total'] *= $pRate;
                                }else{
                                    $values[$y][$i]['total'] *= 1.0;
                                }

                            }elseif ($infoQuery[$b]['table'] == "fw_digital a") {
                                
                                if ($values[$y][$i]['brand'] != 'ONL' && $values[$y][$i]['brand'] != 'VIX') {
                                    $values[$y][$i]['total'] *= 1.0;
                                }else{
                                    $values[$y][$i]['total'] *= $pRate;
                                }
                                
                            }else{
                                $values[$y][$i]['total'] *= $pRate;
                            }
                        }
                    }
                }
    		}
    	}

    	return $values;
    }

    public function getSubValues($con, $tableName, $type, $region, $value, $year, $months, $currency, $brands, $filter, $filterType){
    	
        $brand = array();

        for ($i=0; $i < sizeof($brands); $i++) { 
            array_push($brand, $brands[$i][0]);
        }

        $check = false;

        for ($b=0; $b < sizeof($brands) ; $b++) {
            if ($brands[$b][1] == 'ONL') {
                $check = true;
            }
        }

        if ($check) {
            array_push($brand, '13');
            array_push($brand, '14');
            array_push($brand, '15');
            array_push($brand, '16');
        }

    	$sql = new sql();

        $p = new pRate();

        if ($tableName != "cmaps") {
            if ($currency[0]['name'] == "USD") {
                $pRate = 1.0;
            }else{
                $pRate = $p->getPRateByRegionAndYear($con, array($region), array(intval(date('Y'))));
            }
        }else{
            if ($currency[0]['name'] == "USD") {
                $pRate = $p->getPRateByRegionAndYear($con, array($region), array(intval(date('Y'))));
            }else{
                $pRate = 1.0;
            }
        }

        if ($currency[0]['name'] == "USD") {
            $pRateDigital = 1.0;
        }else{
            $pRateDigital = $p->getPRateByRegionAndYear($con, array($region), array(intval(date('Y'))));
        }

        $valueDigital = $value."_revenue";
        $columnsDigital = array("f.region_id","brand_id", "month", "year", "agency_id");
        $colsValueDigital = array($region, $brand, $months, $year, $filter);

    	$as = "total";

        $tableAbv = "a";
        $leftAbv = "b";

		if ($tableName == "ytd") {
            $value .= "_revenue_prate";
            $columns = array("sales_representant_office_id", "month", "year", "brand_id", "agency_id");
            $colsValue = array($region, $months, $year, $brand, $filter);
        }else{
            $columns = array("month", "year", "brand_id");
            $colsValue = array($months, $year, $brand, $filter);

            if ($type == "agency") {
            	array_push($columns, "agency_id");
            }elseif($type == "sector"){
            	array_push($columns, "sector");
            }else{
            	array_push($columns, "category");
            }
        }

        $table = "$tableName $tableAbv";
        $tableDigital = "fw_digital f";

        $tmp = "$leftAbv.ID AS '".$filterType."ID', $leftAbv.name AS '$filterType', SUM($value) AS '$as'";
        $tmpD = $leftAbv.".ID AS '".$filterType."ID', ".$leftAbv.".name AS '".$filterType."', SUM($valueDigital) AS $as";

        $join = "LEFT JOIN $filterType $leftAbv ON $leftAbv.ID = $tableAbv.".$filterType."_ID";
        $joinD = "LEFT JOIN $filterType $leftAbv ON $leftAbv.ID = f.".$filterType."_ID";

        $name = $filterType."_id";
		$names = array($filterType."ID", $filterType, $as);

        $where = $sql->where($columns, $colsValue);
        $whereD = $sql->where($columnsDigital, $colsValueDigital);
    
    	$values = $sql->selectGroupBy($con, $tmp, $table, $join, $where, "total", $name, "DESC");
        $valuesD = $sql->selectGroupBy($con, $tmpD, $tableDigital, $joinD, $whereD, "total", $name, "DESC");

    	$from = $names;

    	$res = $sql->fetch($values, $from, $from);
        $resD = $sql->fetch($valuesD, $from, $from);
        
        if (is_array($res)) {
            for ($v=0; $v < sizeof($res); $v++) { 
                if ($tableName != "cmaps") {
                    $res[$v]['total'] *= $pRate;
                }else{
                    $res[$v]['total'] /= $pRate;
                }
            }
        }

        if($tmpD && is_array($resD)){
            for ($r=0; $r < sizeof($resD); $r++) { 
                $resD[$r]['total'] *= $pRateDigital;
            }

            if ($res) {
                $size1 = sizeof($resD);
                $size2 = sizeof($res);

                for ($r=0; $r < $size1; $r++) { 
                    for ($r2=0; $r2 < $size2; $r2++) {
                        if ($resD[$r][$filterType."ID"] == $res[$r2][$filterType."ID"]) {
                            $res[$r2]['total'] += $resD[$r]['total'];

                            unset($resD[$r]);
                            break;
                        }
                    }
                }

                $resD = array_values($resD);
                for ($r=0; $r < sizeof($resD); $r++) { 
                    array_push($res, $resD[$r]);
                }

                usort($res, array($this,'compare'));
            }else{
                $res = $resD;
            }

        }elseif ($resD) {
            for ($r=0; $r < sizeof($resD); $r++) { 
                $resD[$r]['total'] *= $pRateDigital;
            }
            $res = $resD;

        }
        
    	return $res;
    }

    public function getValueColumn($values, $brand, $year){
        
        if (is_array($values[$year])) {
            for ($b=0; $b < sizeof($values[$year]); $b++) { 
                if ($brand == $values[$year][$b]['brand']) {
                    return $values[$year][$b]['total'];
                }
            }
        }

        return "-";
    }

    public function checkBrandColumn($brand, $mtx, $m, $years, $values){

        if ($mtx[$m][0] == "Bookings ".$years[0] || $mtx[$m][0] == "Share Bookings ".$years[0]) {
            $res = $this->getValueColumn($values, $brand, 0);
        }elseif ($mtx[$m][0] == "Bookings ".$years[1] || $mtx[$m][0] == "Share Bookings ".$years[1]) {
            $res = $this->getValueColumn($values, $brand, 1);
        }elseif ($mtx[$m][0] == "% YoY") {
            $val1 = $this->getValueColumn($values, $brand, 0);
            $val2 = $this->getValueColumn($values, $brand, 1);
            
            if ($val1 != "-" && $val2 != "-") {
                $res = ($val1 / $val2)*100;
            }else{
                $res = "-";
            }
        }elseif ($mtx[$m][0] == "Dif. YoY") {
            $val1 = $this->getValueColumn($values, $brand, 0);
            $val2 = $this->getValueColumn($values, $brand, 1);

            if ($val1 != "-" && $val2 != "-") {
                $res = $val1 - $val2;
            }else{
                $res = "-";
            }
        }else{
            $res = $brand;
        }

        return $res;
    }

    public function checkArray($array, $value){
        
        if (sizeof($array) == 0) {
            $rtr = 1;
        }else{

            $equal = false;

            for ($i=0; $i < sizeof($array); $i++) { 
            
                if ($value['id'] == $array[$i]['id']) {
                    $equal = true;
                }
            }

            if (!$equal) {
                $rtr = 1;  
            }else{
                $rtr = 0;
            }
        }

        return $rtr;

    }

    public function assemblerMarketBrand($values, $years, $brands){
        
        $mtx[0][0] = "Brand";
        $mtx[1][0] = "Bookings ".$years[0];
        $mtx[2][0] = "Bookings ".$years[1];
        $mtx[3][0] = "Share Bookings ".$years[0];
        $mtx[4][0] = "Share Bookings ".$years[1];
        $mtx[5][0] = "% YoY";
        $mtx[6][0] = "Dif. YoY";

        $closed = 0;
        $pClosed = 0;

        $closedP = 0;
        $pClosedP = 0;
        
        $brand = array();

        for ($r=0; $r < sizeof($values); $r++) { 
            if (is_array($values[$r])) {
                for ($r2=0; $r2 < sizeof($values[$r]); $r2++) {
                    $aux = array("id" => $values[$r][$r2]['brandID'], "brand" => $values[$r][$r2]['brand']);
                    if ($this->checkArray($brand, $aux)) {
                        array_push($brand, $aux);
                    }
                }
            }
        }

        $brand = $this->sortDigitalBrands($brand);

        for ($b=0; $b < sizeof($brand); $b++) {
            for ($m=0; $m < 3; $m++) {

                $res = $this->checkBrandColumn($brand[$b], $mtx, $m, $years, $values);

                if ($mtx[$m][0] == "Bookings ".$years[0]) {
                    if ($res != "-") {
                        $closed += $res;    
                    }
                }elseif ($mtx[$m][0] == "Bookings ".$years[1]) {
                    if ($res != "-") {
                        $pClosed += $res;
                    }
                }

                array_push($mtx[$m], $res);
            }
        }

        for ($b=0; $b < sizeof($brand); $b++) {
            for ($m=3; $m < 5; $m++) { 
                $val = $this->checkBrandColumn($brand[$b], $mtx, $m, $years, $values);

                if ($mtx[$m][0] == "Share Bookings ".$years[0]) {
                    if ($val != "-") {
                        $res = ($val / $closed)*100;
                        $closedP += $res;
                    }else{
                        $res = $val;
                    }
                }elseif ($mtx[$m][0] == "Share Bookings ".$years[1]) {
                    if ($val != "-") {
                        $res = ($val / $pClosed)*100;
                        $pClosedP += $res;
                    }else{
                        $res = $val;
                    }
                }
                
                array_push($mtx[$m], $res);
            }
        }

        for ($b=0; $b < sizeof($brand); $b++) { 
            for ($m=5; $m < sizeof($mtx); $m++) {
                $res = $this->checkBrandColumn($brand[$b], $mtx, $m, $years, $values);

                array_push($mtx[$m], $res);
            }
        }

        $total = array();

        if (sizeof($brands) > 1) {
            array_push($total, "DN");
            array_push($total, $closed);
            array_push($total, $pClosed);
            array_push($total, $closedP);
            array_push($total, $pClosedP);

            if ($closed == 0 || $pClosed == 0) {
                $val = 0;
            }else{
                $val = ($closed / $pClosed)*100;
            }

            array_push($total, $val);

            $val = ($closed - $pClosed);
            array_push($total, $val);
        }
        
        return array($mtx, $total);
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

    public function checkColumn2($mtx, $m, $name, $values, $years, $p, $type, $s, $values2, $id){
        
        if ($mtx[$m][0] == "Ranking") {
            $res = $this->searchPos2($name, $values, $type, $s, $id);
        }elseif ($mtx[$m][0] == "Bookings ".$years[0]) {
            $res = $this->searchValueByYear($name, $values, $type, 0, $id);
        }elseif ($mtx[$m][0] == "Bookings ".$years[1]) {
            $res = $this->searchValueByYear($name, $values, $type, 1, $id);
        }elseif ($mtx[$m][0] == "Var (%)") {
            if ($mtx[$m-2][$p] == 0 || $mtx[$m-1][$p] == 0) {
                $res = 0;
            }else{
                $res = ($mtx[$m-2][$p]/$mtx[$m-1][$p])*100;
            }
        }elseif ($mtx[$m][0] == "Var Abs.") {
            $res = $mtx[$m-3][$p] - $mtx[$m-2][$p];
        }elseif ($mtx[$m][0] == "Total ".$years[0]) {
            $res = $this->searchValueByYear($name, $values2, $type, 0, $id);
        }elseif ($mtx[$m][0] == "Total ".$years[1]) {
            $res = $this->searchValueByYear($name, $values2, $type, 1, $id);
        }else{
            $res = $name[$type];
        }

        return $res;
    }

    public function subAssemblerMarketTotal($mtx){
        
        $total[0] = "Total";

        $first = 0;
        $second = 0;
        $totalFirst = 0;
        $totalSecond = 0;

        $pos = 2;

        for ($m=1; $m < sizeof($mtx[0]); $m++) { 
            $first += $mtx[$pos][$m];
            $second += $mtx[$pos+1][$m];
            $totalFirst += $mtx[sizeof($mtx)-2][$m];
            $totalSecond += $mtx[sizeof($mtx)-1][$m];
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
            }elseif ($m == sizeof($mtx)-2) {
                $total[$m] = $totalFirst;
            }elseif ($m == sizeof($mtx)-1) {
                $total[$m] = $totalSecond;
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
            $mtx = $this->assemblerMarketBrand($values, $years, $brands);

            return $mtx;
        }else{  

            $mtx[0][0] = "Ranking";
            $mtx[1][0] = "Client";
            $mtx[2][0] = "Bookings ".$years[0];
            $mtx[3][0] = "Bookings ".$years[1];
            $mtx[4][0] = "Var (%)";
            $mtx[5][0] = "Var Abs.";
            $mtx[6][0] = "Total ".$years[0];
            $mtx[7][0] = "Total ".$years[1];
    
            $types = array();

            for ($r=0; $r < sizeof($values); $r++) { 
                if (is_array($values[$r])) {
                    for ($r2=0; $r2 < sizeof($values[$r]); $r2++) { 
                        if ($this->existInArray($types, $values[$r][$r2][$typeF."ID"], $typeF, true)) {
                            array_push($types, $values[$r][$r2]); 
                        }
                    }
                }
            }

            $size = sizeof($types);

            for ($s=0; $s < $size; $s++) { 
                for ($m=0; $m < sizeof($mtx); $m++) {
                    array_push($mtx[$m], $this->checkColumn2($mtx, $m, $types[$s], $values, $years, sizeof($mtx[$m]), $typeF, $s, $valuesTotal, true));
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
                                        if ($mtx[$n][0] == "Var (%)" || $mtx[$n][0] == "Share Bookings ".$years[0] || $mtx[$n][0] == "Share Bookings ".$years[1] || $mtx[$n][0] == "% YoY") {
                                            echo "<td class='$color center'> ".number_format($mtx[$n][$m],0,',','.')." %</td>";   
                                        }elseif ($mtx[$n][0] == "Ranking") {
                                            echo "<td class='$color center'> ".number_format($mtx[$n][$m],0,',','.')."º</td>";
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
                                    echo "<td class='darkBlue center'> ".number_format($total[$t],0,',','.')." %</td>";
                                }
                                elseif (is_numeric($total[$t])) {
                                    echo "<td class='darkBlue center'> ".number_format($total[$t],0,',','.')." </td>";
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
