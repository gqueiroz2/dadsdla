<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\rank;
use App\region;
use App\pRate;
use App\base;

class rankingMarket extends rank {
    
    public function getAllResults($con, $brands, $type, $regionID, $region, $value, $currency, $months, $years, $sector=false){
        
        $null = null;

		if ($region == "Brazil") {
			$res = $this->getAllValues($con, "cmaps", $type, $type, $brands, $regionID, $value, $years, $months, $currency, $null, "DESC");
		}else{
			$res = $this->getAllValues($con, "ytd", $type, $type, $brands, $regionID, $value, $years, $months, $currency, $null, "DESC");    	
		}
    	
        return $res;
        
    }

    public function searchPos($name, $values, $type, $cont, $id){

        $bool = -1;
        $bool2 = -1;

        if ($id == true) {
            $id = "ID";
        }else{
            $id = "";
        }

       
        if($values[0]){
            for ($v=0; $v < sizeof($values[0]); $v++) { 
                if ($values[0][$v][$type.$id] == $name[$type.$id]) {
                    $bool = 0;
                    if ($values[0][$v]['total'] == 0) {
                        $bool = 1;
                    }else{
                        $bool = 2;
                    }
                }
            }
        }

        for ($v=0; $v < sizeof($values[1]); $v++) { 
            if ($values[1][$v][$type.$id] == $name[$type.$id]) {
                $bool2 = 0;
                if ($values[1][$v]['total'] == 0) {
                    $bool2 = 1;
                }else{
                    $bool2 = 2;
                }
            }
        }

        if ($bool == -1 || $bool == 1) {
            if ($bool2 == -1 || $bool2 == 1) {
                return -1;
            }else{
                return $cont;
            }
        }else{
            return $cont;
        }

    }

    public function searchValueByYear($name, $values, $type, $year, $id){
    	
        if ($id == true) {
            $id = "ID";
        }else{
            $id = "";
        }

        if ($values[$year] == false) {
            return 0;
        }else{
            for ($s2=0; $s2 < sizeof($values[$year]); $s2++) { 
                if ($name[$type.$id] == $values[$year][$s2][$type.$id]) {
                    return $values[$year][$s2]['total'];
                }
            }
        }

    	return 0;
    }

    public function searchGroupValue($name, $values, $id){
    	
        if ($id == true) {
            $id = "ID";
        }else{
            $id = "";
        }
        
        for ($s=0; $s < sizeof($values); $s++) { 
            if($values[$s]){
                for ($s2=0; $s2 < sizeof($values[$s]); $s2++) {                 
                    if ($name['agency'.$id] == $values[$s][$s2]['agency'.$id]) {
                        if ($values[$s][$s2]['agencyGroup'] == "Others") {
                            return "-";
                        }else{
                            return $values[$s][$s2]['agencyGroup'];
                        }
                    }
                }
            }
        }
    }

    public function checkColumn($mtx, $m, $name, $values, $years, $p, $type, $cont, $id){
        
        if ($mtx[$m][0] == "Ranking") {
            $res = $this->searchPos($name, $values, $type, $cont, $id);
        }elseif ($mtx[$m][0] == "Agency group") {
            $res = $this->searchGroupValue($name, $values, $id);
        }elseif ($mtx[$m][0] == "Booking ".$years[0]) {
            $res = $this->searchValueByYear($name, $values, $type, 0, $id);
        }elseif ($mtx[$m][0] == "Booking ".$years[1]) {
            $res = $this->searchValueByYear($name, $values, $type, 1, $id);
        }elseif ($mtx[$m][0] == "Var (%)") {
            if ($mtx[$m-2][$p] == 0 || $mtx[$m-1][$p] == 0) {
                $res = 0;
            }else{
                $res = ($mtx[$m-2][$p]/$mtx[$m-1][$p])*100;
            }
        }elseif ($mtx[$m][0] == "Var Abs.") {
            $res = $mtx[$m-3][$p] - $mtx[$m-2][$p];
        }elseif ($mtx[$m][0] == "Move") {
            if ($type == "client") {
                $pos = 4;
            }else{
                $pos = 3;
            }

            if ($mtx[$m-$pos][$p] < $mtx[$m-$pos-1][$p]) {
                $res = "Increased";
            }else{
                $res = "Decreased";
            }
        }else{
            if ($type == "sector" || $type == "category") {
                $res = $name[$type];
            }else{
                $res = $name[$type];
            }
        }

        return $res;
    }

    public function assemblerMarketTotal($mtx, $type, $years){
    	
    	$total[0] = "Total";

    	$first = 0;
    	$second = 0;

    	if ($type == "agency") {
    		$pos = 3;
    	}else{
    		$pos = 2;
    	}

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
    			$total[$m] = " ";
    		}
    	}

    	return $total;
    }

    public function assembler($values, $years, $type){
    	
    	$mtx[0][0] = "Ranking";
    	$pos = 1;
    	
    	if ($type == "agency") {
    		$mtx[$pos][0] = "Agency group";	
    		$pos++;
    	}
    	
    	$mtx[$pos][0] = ucfirst($type);$pos++;
    	$mtx[$pos][0] = "Booking ".$years[0];$pos++;
    	$mtx[$pos][0] = "Booking ".$years[1];$pos++;
    	$mtx[$pos][0] = "Var (%)";$pos++;
    	$mtx[$pos][0] = "Var Abs.";$pos++;

    	$mtx[$pos][0] = "Move";$pos++;

		$types = array();

        if ($type != "agency" && $type != "client") {
            for ($r=0; $r < sizeof($values); $r++) {
                if (is_array($values[$r])) {
                    for ($r2=0; $r2 < sizeof($values[$r]); $r2++) {
                        if ($this->existInArray($types, $values[$r][$r2][$type], $type)) {
                            array_push($types, $values[$r][$r2]);
                        }
                    }
                }
            }
        }else{
            for ($r=0; $r < sizeof($values); $r++) {
                if (is_array($values[$r])) {
                    for ($r2=0; $r2 < sizeof($values[$r]); $r2++) {
                        if ($this->existInArray($types, $values[$r][$r2][$type."ID"], $type, true)) {
                            array_push($types, $values[$r][$r2]);  
                        }
                    }
                }
            }
        }
        
        $cont = 1;

        if ($type != "agency" && $type != "client") {
            for ($t=0; $t < sizeof($types); $t++) { 
                for ($m=0; $m < sizeof($mtx); $m++) { 
                    $res = $this->checkColumn($mtx, $m, $types[$t], $values, $years, sizeof($mtx[$m]), $type, $cont, false);

                    if ($res == -1) {
                        break;
                    }else{
                        
                        if ($m == 0) {
                            $cont++;    
                        }

                        array_push($mtx[$m], $res);
                    }
                }
            }
        }else{
            for ($t=0; $t < sizeof($types); $t++) { 
                for ($m=0; $m < sizeof($mtx); $m++) { 
                        
                    $res = $this->checkColumn($mtx, $m, $types[$t], $values, $years, sizeof($mtx[$m]), $type, $cont, true);

                    if ($res == -1) {
                        break;
                    }else{
                        
                        if ($m == 0) {
                            $cont++;    
                        }

                        array_push($mtx[$m], $res);
                    }
                }
            }
        }
        
        $total = $this->assemblerMarketTotal($mtx, $type, $years);
    	
    	return array($mtx, $total);
    }

}
