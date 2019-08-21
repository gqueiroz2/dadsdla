<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\rank;
use App\region;
use App\pRate;

class rankingChurn extends rank {

    public function fixArray($values, $type){

    	$finalValues = array();

    	for ($v=0; $v < sizeof($values[1]); $v++) {
    		$bool = -1; 
    		for ($i=0; $i < sizeof($values[0]); $i++) {
    			if ($values[1][$v][$type] == $values[0][$i][$type]) {
					if ($values[0][$i]['total'] == 0 && $values[1][$v]['total'] > 0) {
						$bool = 0;
						array_push($finalValues, $values[1][$v]);
					}else{
						$bool = 1;
					}
				}
    		}

    		if($bool == -1){
				array_push($finalValues, $values[1][$v]);
    		}
    	}

    	return $finalValues;
    }

    public function getAllResults($con, $brands, $type, $regionID, $region, $value, $currency, $months, $years){
    	
		if ($region == "Brazil") {
    		$res = $this->getAllValues($con, "cmaps", $type, $type, $brands, $regionID, $value, $years, $months, $currency);
    	}else{
			$res = $this->getAllValues($con, "ytd", $type, $type, $brands, $regionID, $value, $years, $months, $currency);    	
		}

    	return $res;
		
    }

    public function checkYTD($name, $type, $valuesYTD){
    	
		for ($i=0; $i < sizeof($valuesYTD); $i++) { 
			
			if ($name == $valuesYTD[$i][$type]) {
				return $valuesYTD[$i]['total'];
			}
		}
	
		return "-";
    	
    }

    public function checkColumn($mtx, $m, $name, $values, $years, $p, $type, $v, $values2=null){
    	
    	if ($mtx[$m][0] == "Ranking") {
    		$res = ($v+1);
    	}elseif ($mtx[$m][0] == "Agency group") {
    		$res = $values[$v]['agencyGroup'];
    	}elseif ($mtx[$m][0] == $years[0]) {
    		$res = 0;
    	}elseif ($mtx[$m][0] == $years[1]) {
    		$res = $values[$v]['total'];
    	}elseif ($mtx[$m][0] == "Var (%)") {
    		if ($mtx[$m-2][$p] == 0 || $mtx[$m-1][$p] == 0) {
    			$res = 0;
    		}else{
    			$res = ($mtx[$m-2][$p]/$mtx[$m-1][$p])*100;
    		}
    	}elseif ($mtx[$m][0] == "Var Abs.") {
    		$res = $mtx[$m-3][$p] - $mtx[$m-2][$p];
    	}elseif ($mtx[$m][0] == "Class") {
			$res = "Churn";
    	}elseif ($mtx[$m][0] == "YTD ".$years[0]) {
    		$res = 0;
    	}elseif ($mtx[$m][0] == "YTD ".$years[1]) {
    		$res = $this->checkYTD($name, $type, $values2);
    	}/*elseif ($mtx[$m][0] == "Var YTD (%)") {
    		if (($mtx[$m-2][$p] == 0 || $mtx[$m-1][$p] == 0) || ($mtx[$m-2][$p] == "-" || $mtx[$m-1][$p] == "-")) {
    			$res = 0;
    		}else{
    			$res = ($mtx[$m-2][$p]/$mtx[$m-1][$p])*100;
    		}
    	}*/elseif ($mtx[$m][0] == "Var Abs. YTD") {
    		$pos = 4;
    		if ($mtx[$m-$pos][$p] == "-" && $mtx[$m-$pos-1][$p] != "-") {
    			$val = 0;
    			$val2 = $mtx[$m-$pos-1][$p];
    		}elseif ($mtx[$m-$pos-1][$p] == "-" && $mtx[$m-$pos][$p] != "-") {
    			$val = $mtx[$m-$pos][$p];
    			$val2 = 0;
    		}elseif ($mtx[$m-$pos-1][$p] == "-" && $mtx[$m-$pos][$p] == "-") {
    			$val = 0;
    			$val2 = 0;
    		}else{
    			$val = $mtx[$m-$pos][$p];
    			$val2 = $mtx[$m-$pos-1][$p];
    		}
    		$res = $val - $val2;
    	}else{
    		$res = $name;
    	}

    	return $res;
    }

    public function assemblerChurnTotal($mtx, $type, $years){
    	
    	$total[0] = "Total";

        $first = 0;
        $second = 0;

        $firstYtd = 0;
        $secondYtd = 0;

        if ($type == "agency") {
    		$pos = 3;
    		$pos2 = 8;
    	}else{
    		$pos = 2;
    		$pos2 = 7;
    	}

        for ($m=1; $m < sizeof($mtx[0]); $m++) { 
            $first += $mtx[$pos][$m];
            $second += $mtx[$pos+1][$m];

            if ($mtx[$pos2+1][$m] != "-") {
            	$secondYtd += $mtx[$pos2+1][$m];
            }
            
        }

        for ($m=1; $m < sizeof($mtx); $m++) { 

            if ($m == $pos || $m == ($pos+1)) {
                
                if ($m == $pos) {
                    $total[$m] = $first;
                }else{
                    $total[$m] = $second;
                }
            }elseif ($m == $pos2 || $m == ($pos2+1)) {
            	if ($m == $pos2) {
                    $total[$m] = $firstYtd;
                }else{
                    $total[$m] = $secondYtd;
                }
            }elseif ($mtx[$m][0] == "Var (%)") {
                if ($total[$m-1] != 0 && $total[$m-2] != 0) {
                    $total[$m] = ($total[$pos] / $total[$pos+1])*100;
                }else{
                    $total[$m] = 0;
                }
            }elseif ($mtx[$m][0] == "Var Abs.") {
                $total[$m] = $total[$m-3] - $total[$m-2];
            }/*elseif ($mtx[$m][0] == "Var YTD (%)") {
            	if ($total[$m-1] != 0 && $total[$m-2] != 0) {
                    $total[$m] = ($total[$pos2] / $total[$pos2+1])*100;
                }else{
                    $total[$m] = 0;
                }
            }*/elseif ($mtx[$m][0] == "Var Abs. YTD") {
            	$p = 4;
            	if ($total[$m-$p] == "-" && $total[$m-$p-1] != "-") {
	    			$val = 0;
	    			$val2 = $total[$m-$p-1];
	    		}elseif ($total[$m-$p-1] == "-" && $total[$m-$p] != "-") {
	    			$val = $total[$m-$p];
	    			$val2 = 0;
	    		}elseif ($total[$m-$p-1] == "-" && $total[$m-$p] == "-") {
	    			$val = 0;
	    			$val2 = 0;
	    		}else{
	    			$val = $total[$m-$p];
	    			$val2 = $total[$m-$p-1];
	    		}
	    		$total[$m] = $val - $val2;
            	
            }else{
                $total[$m] = "-";
            }
        }

        return $total;
    }

    public function assembler($values, $valuesYTD, $years, $type){
    	
    	$mtx[0][0] = "Ranking";
    	$pos = 1;
    	
    	if ($type == "agency") {
    		$mtx[$pos][0] = "Agency group";	
    		$pos++;
    	}
    	
    	$mtx[$pos][0] = ucfirst($type);$pos++;
    	$mtx[$pos][0] = $years[0];$pos++;
    	$mtx[$pos][0] = $years[1];$pos++;
    	$mtx[$pos][0] = "Var (%)";$pos++;
    	$mtx[$pos][0] = "Var Abs.";$pos++;
    	$mtx[$pos][0] = "Class";$pos++;
    	$mtx[$pos][0] = "YTD ".$years[0];$pos++;
		$mtx[$pos][0] = "YTD ".$years[1];$pos++;
		//$mtx[$pos][0] = "Var YTD (%)";$pos++;
		$mtx[$pos][0] = "Var Abs. YTD";$pos++;

        for ($v=0; $v < sizeof($values); $v++) { 
        	for ($m=0; $m < sizeof($mtx); $m++) { 
				array_push($mtx[$m], $this->checkColumn($mtx, $m, $values[$v][$type], $values, $years, sizeof($mtx[$m]), $type, $v, $valuesYTD));
        	}
        }
		
        $total = $this->assemblerChurnTotal($mtx, $type, $years);
    	//var_dump($total);
    	return array($mtx, $total);
    }
}
