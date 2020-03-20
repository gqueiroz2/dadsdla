<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\rank;
use App\region;
use App\pRate;

class rankingChurn extends rank {

    public function getAllResults($con, $brands, $type, $regionID, $region, $value, $currency, $months, $years){
    	
        $null = null;

		if ($region == "Brazil") {
    		$res = $this->getAllValues($con, "cmaps", $type, $type, $brands, $regionID, $value, $years, $months, $currency, $null);
    	}else{
			$res = $this->getAllValues($con, "ytd", $type, $type, $brands, $regionID, $value, $years, $months, $currency, $null);    	
		}
        
    	return $res;
		
    }

    public function getValue($values, $type, $name, $year, $id){
        
        if ($id == true) {
            $id = "ID";
        }else{
            $id = "";
        }

        if (is_array($values[$year])) {
            for ($v=0; $v < sizeof($values[$year]); $v++) { 
                if ($values[$year][$v][$type.$id] == $name[$type.$id]) {
                    return $values[$year][$v]['total'];
                }
            }
        }

        return 0;
    }

    public function getAgencyGroup($values, $name){
        
        for ($v=0; $v < sizeof($values); $v++) {
            if (is_array($values[$v])) {
                for ($v2=0; $v2 < sizeof($values[$v]); $v2++) { 
                    if ($values[$v][$v2]['agencyID'] == $name['agencyID']) {
                        if ($values[$v][$v2]['agencyGroup'] == "Others") {
                            return "-";
                        }else{
                            return $values[$v][$v2]['agencyGroup'];
                        }
                    }
                }   
            }
        }
    }

    public function checkRank($cont, $values, $name, $type, $years, $id){
        
        $bool = -1;
        $bool2 = -1;
        $bool3 = -1;

        if ($id == true) {
            $id = "ID";
        }else{
            $id = "";
        }

        if (is_array($values[0])) {
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

        if (is_array($values[1])) {
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
        }

        if (is_array($values[2])) {
            for ($v=0; $v < sizeof($values[2]); $v++) { 
                if ($values[2][$v][$type.$id] == $name[$type.$id]) {
                    $bool3 = 0;
                    if ($values[2][$v]['total'] == 0) {
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
                    return -1;
                }else{
                    return $cont;
                }
            }else{
                return $cont;
            }
        }else{
            return -1;
        }
    }

    public function checkColumn($mtx, $m, $name, $values, $years, $p, $type, $cont, $valuesTotal, $id){

    	if ($mtx[$m][0] == "Ranking") {
    		$res = $this->checkRank($cont, $values, $name, $type, $years, $id);
    	}elseif ($mtx[$m][0] == "Agency group") {
    		$res = $this->getAgencyGroup($values, $name);
    	}elseif ($mtx[$m][0] == "Bookings ".$years[0]) {
    		$res = $this->getValue($values, $type, $name, 0, $id);
    	}elseif ($mtx[$m][0] == "Bookings ".$years[1]) {
            $res = $this->getValue($values, $type, $name, 1, $id);
        }elseif ($mtx[$m][0] == "Bookings ".$years[2]) {
            $res = $this->getValue($values, $type, $name, 2, $id);
        }elseif ($mtx[$m][0] == "Var (%)") {
    		if ($mtx[$m-3][$p] == 0 || $mtx[$m-2][$p] == 0) {
    			$res = 0;
    		}else{
    			$res = ($mtx[$m-3][$p]/$mtx[$m-2][$p])*100;
    		}
    	}elseif ($mtx[$m][0] == "Var Abs.") {
            $res = $mtx[$m-4][$p] - $mtx[$m-3][$p];
    	}elseif ($mtx[$m][0] == "Total ".$years[0]) {
            $res = $this->getValue($valuesTotal, $type, $name, 0, $id);
        }elseif ($mtx[$m][0] == "Total ".$years[1]) {
            $res = $this->getValue($valuesTotal, $type, $name, 1, $id);
        }elseif ($mtx[$m][0] == "Total ".$years[2]) {
            $res = $this->getValue($valuesTotal, $type, $name, 2, $id);
        }elseif ($mtx[$m][0] == "Class") {
            $res = "Churn";
    	}else{
            if ($type == "sector" || $type == "category") {
                $res = $name[$type];
            }else{
                $res = $name[$type];
            }
    	}

    	return $res;
    }

    public function assemblerChurnTotal($mtx, $type, $years){
    	
    	$total[0] = "Total";

        $first = 0;
        $second = 0;
        $third = 0;

        $firstTotal = 0;
        $secondTotal = 0;
        $thirdTotal = 0;

        if ($type == "agency") {
    		$pos = 3;
    		$pos2 = 8;
    	}else{
    		$pos = 2;
    		$pos2 = 7;
    	}

        for ($m=1; $m < sizeof($mtx[0]); $m++) { 
            $second += $mtx[$pos+1][$m];
            $third += $mtx[$pos+2][$m];

            $secondTotal += $mtx[$pos2+1][$m];
            $thirdTotal += $mtx[$pos2+2][$m];
        }

        for ($m=1; $m < sizeof($mtx); $m++) { 

            if ($m == $pos || $m == ($pos+1) || $m == ($pos+2)) {
                if ($m == $pos) {
                    $total[$m] = $first;
                }elseif ($m == ($pos+1)) {
                    $total[$m] = $second;
                }else{
                    $total[$m] = $third;
                }
            }elseif ($m == $pos2 || $m == ($pos2+1) || $m == ($pos2+2)) {
            	if ($m == $pos2) {
                    $total[$m] = $firstTotal;
                }elseif ($m == ($pos2+1)) {
                    $total[$m] = $secondTotal;
                }else{
                    $total[$m] = $thirdTotal;
                }
            }elseif ($mtx[$m][0] == "Var (%)") {
                $total[$m] = 0;
            }elseif ($mtx[$m][0] == "Var Abs.") {
                $total[$m] = $total[$m-4] - $total[$m-3];
            }else{
                $total[$m] = "-";
            }
        }

        return $total;
    }

    public function assembler($values, $nameValues, $valuesTotal, $years, $type){
        
    	$mtx[0][0] = "Ranking";
    	$pos = 1;
    	
    	if ($type == "agency") {
    		$mtx[$pos][0] = "Agency group";	
    		$pos++;
    	}
    	
    	$mtx[$pos][0] = ucfirst($type);$pos++;
    	$mtx[$pos][0] = "Bookings ".$years[0];$pos++;
    	$mtx[$pos][0] = "Bookings ".$years[1];$pos++;
        $mtx[$pos][0] = "Bookings ".$years[2];$pos++;
    	$mtx[$pos][0] = "Var (%)";$pos++;
    	$mtx[$pos][0] = "Var Abs.";$pos++;
        $mtx[$pos][0] = "Total ".$years[0];$pos++;
        $mtx[$pos][0] = "Total ".$years[1];$pos++;
        $mtx[$pos][0] = "Total ".$years[2];$pos++;
    	$mtx[$pos][0] = "Class";$pos++;
		
        $cont = 1;
        
        //array_push(array, var)

        if ($type != "agency" && $type != "client") {
            for ($t=0; $t < sizeof($nameValues); $t++) { 
                for ($m=0; $m < sizeof($mtx); $m++) { 
                    $res = $this->checkColumn($mtx, $m, $nameValues[$t], $values, $years, sizeof($mtx[$m]), $type, $cont, $valuesTotal, false);

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
            for ($t=0; $t < sizeof($nameValues); $t++) { 
                for ($m=0; $m < sizeof($mtx); $m++) { 
                        
                    $res = $this->checkColumn($mtx, $m, $nameValues[$t], $values, $years, sizeof($mtx[$m]), $type, $cont, $valuesTotal, true);
                    
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

        $total = $this->assemblerChurnTotal($mtx, $type, $years);
    	
    	return array($mtx, $total);
    }
}
