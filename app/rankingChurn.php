<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\rank;
use App\region;
use App\pRate;

class rankingChurn extends rank {

    public function getAllResults($con, $brands, $type, $regionID, $region, $value, $currency, $months, $years){
    	
		if ($region == "Brazil") {
    		$res = $this->getAllValues($con, "cmaps", $type, $type, $brands, $regionID, $value, $years, $months, $currency);
    	}else{
			$res = $this->getAllValues($con, "ytd", $type, $type, $brands, $regionID, $value, $years, $months, $currency);    	
		}

    	return $res;
		
    }

    public function getValue($values, $type, $name, $year){
        
        for ($v=0; $v < sizeof($values[$year]); $v++) { 
            if ($values[$year][$v][$type] == $name) {
                return $values[$year][$v]['total'];
            }
        }

        return 0;
    }

    public function getAgencyGroup($values, $name){
        
        for ($v=0; $v < sizeof($values); $v++) { 
            for ($v2=0; $v2 < sizeof($values[$v]); $v2++) { 
                if ($values[$v][$v2]['agency'] == $name) {
                    if ($values[$v][$v2]['agencyGroup'] == "Others") {
                        return "-";
                    }else{
                        return $values[$v][$v2]['agencyGroup'];
                    }
                }
            }
        }
    }

    public function checkRank($cont, $values, $name, $type, $years){
        
        $bool = -1;

        for ($v=0; $v < sizeof($values[0]); $v++) { 
            if ($values[0][$v][$type] == $name) {
                $bool = 0;
                if ($values[0][$v]['total'] == 0) {
                    $bool = 1;
                    return $cont;
                }
            }
        } 

        if ($bool == -1) {
            return $cont;
        }

        return -1;
    }

    public function checkColumn($mtx, $m, $name, $values, $years, $p, $type, $v, $cont, $valuesTotal){
    	
    	if ($mtx[$m][0] == "Ranking") {
    		$res = $this->checkRank($cont, $values, $name, $type, $years);
    	}elseif ($mtx[$m][0] == "Agency group") {
    		$res = $this->getAgencyGroup($values, $name);
    	}elseif ($mtx[$m][0] == "Bookings ".$years[0]) {
    		$res = $this->getValue($values, $type, $name, 0);
    	}elseif ($mtx[$m][0] == "Bookings ".$years[1]) {
            $res = $this->getValue($values, $type, $name, 1);
        }elseif ($mtx[$m][0] == "Bookings ".$years[2]) {
            $res = $this->getValue($values, $type, $name, 2);
        }elseif ($mtx[$m][0] == "Var (%)") {
    		if ($mtx[$m-3][$p] == 0 || $mtx[$m-2][$p] == 0) {
    			$res = 0;
    		}else{
    			$res = ($mtx[$m-3][$p]/$mtx[$m-2][$p])*100;
    		}
    	}elseif ($mtx[$m][0] == "Var Abs.") {
            $res = $mtx[$m-4][$p] - $mtx[$m-3][$p];
    	}elseif ($mtx[$m][0] == "Total ".$years[0]) {
            $res = $this->getValue($valuesTotal, $type, $name, 0);
        }elseif ($mtx[$m][0] == "Total ".$years[1]) {
            $res = $this->getValue($valuesTotal, $type, $name, 1);
        }elseif ($mtx[$m][0] == "Total ".$years[2]) {
            $res = $this->getValue($valuesTotal, $type, $name, 2);
        }elseif ($mtx[$m][0] == "Class") {
            $res = "Churn";
    	}else{
            $res = $name;
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
        
        for ($v=0; $v < sizeof($nameValues); $v++) { 
        	for ($m=0; $m < sizeof($mtx); $m++) { 
                
                $res = $this->checkColumn($mtx, $m, $nameValues[$v], $values, $years, sizeof($mtx[$m]), $type, $v, $cont, $valuesTotal);

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

        $c = 0;
        $fun = "array_multisort(";

        for ($m=0; $m < sizeof($mtx); $m++) {
            if (substr($mtx[$m][0], 0, 8) == "Bookings") {
                $fun .= "\$mtx[".$m."], SORT_ASC";
                $c++;
                if ($c != sizeof($years)) {
                    $fun .= ", ";
                }
            }
        }

        $fun .= ");";var_dump($fun);
        eval($fun);

        $total = $this->assemblerChurnTotal($mtx, $type, $years);
    	
    	return array($mtx, $total);
    }
}
