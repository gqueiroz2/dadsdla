<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\rank;
use App\region;
use App\pRate;
use App\base;

class rankingMarket extends rank {
    
    public function getAllResults($con, $brands, $type, $regionID, $region, $value, $currency, $months, $years, $sector=false){
        
		if ($region == "Brazil") {
			$res = $this->getAllValues($con, "cmaps", $type, $type, $brands, $regionID, $value, $years, $months, $currency, "DESC");
		}else{
			$res = $this->getAllValues($con, "ytd", $type, $type, $brands, $regionID, $value, $years, $months, $currency, "DESC");    	
		}
    	
        return $res;
    }

    public function searchPos($name, $values, $type, $cont){

        $bool = -1;
        $bool2 = -1;

        for ($v=0; $v < sizeof($values[0]); $v++) { 
            if ($values[0][$v][$type] == $name) {
                $bool = 0;
                if ($values[0][$v]['total'] == 0) {
                    $bool = 1;
                }else{
                    $bool = 2;
                }
            }
        }

        for ($v=0; $v < sizeof($values[1]); $v++) { 
            if ($values[1][$v][$type] == $name) {
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

    public function searchValueByYear($name, $values, $type, $year){
    	
        if ($values[$year] == false) {
            return 0;
        }else{
            for ($s2=0; $s2 < sizeof($values[$year]); $s2++) { 
                if ($name == $values[$year][$s2][$type]) {
                    return $values[$year][$s2]['total'];
                }
            }
        }

    	return 0;
    }

    public function searchGroupValue($name, $values){
    	
        for ($s=0; $s < sizeof($values); $s++) { 
            for ($s2=0; $s2 < sizeof($values[$s]); $s2++) { 
                if ($name == $values[$s][$s2]['agency']) {
                    if ($values[$s][$s2]['agencyGroup'] == "Others") {
                        return "-";
                    }else{
                        return $values[$s][$s2]['agencyGroup'];
                    }
                }
            }
        }
    	
    }

    public function existInYear($name, $values, $type, $y){
    
    	if (is_array($values[$y])) {
    		for ($s=0; $s < sizeof($values[$y]); $s++) { 
				if ($name == $values[$y][$s][$type]) {
					return true;
				}
    		}
    	}

    	return false;

    }

    public function checkColumn($mtx, $m, $name, $values, $years, $p, $type, $cont, $values2=null){
    	
    	if ($mtx[$m][0] == "Ranking") {
    		$res = $this->searchPos($name, $values, $type, $cont);
    	}elseif ($mtx[$m][0] == "Agency group") {
    		$res = $this->searchGroupValue($name, $values);
    	}elseif ($mtx[$m][0] == "Booking ".$years[0]) {
    		$res = $this->searchValueByYear($name, $values, $type, 0);
    	}elseif ($mtx[$m][0] == "Booking ".$years[1]) {
    		$res = $this->searchValueByYear($name, $values, $type, 1);
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
    		$res = $name;
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

    	if ($type == "client") {
			//$mtx[$pos][0] = "Class";$pos++;    		
    	}

    	$mtx[$pos][0] = "Move";$pos++;

		$types = array();

        for ($r=0; $r < sizeof($values); $r++) {
            if (is_array($values[$r])) {
                for ($r2=0; $r2 < sizeof($values[$r]); $r2++) { 
                    if (!in_array($values[$r][$r2][$type], $types)) {
                        array_push($types, $values[$r][$r2][$type]);  
                    }
                }
            }
        }

        $cont = 1;

        for ($t=0; $t < sizeof($types); $t++) { 
        	for ($m=0; $m < sizeof($mtx); $m++) { 
                    
                $res = $this->checkColumn($mtx, $m, $types[$t], $values, $years, sizeof($mtx[$m]), $type, $cont);

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

        $total = $this->assemblerMarketTotal($mtx, $type, $years);
    	
    	return array($mtx, $total);
    }

}
