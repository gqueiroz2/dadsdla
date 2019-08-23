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

    public function checkValue(){
        
    }

    public function checkColumn($mtx, $m, $name, $values, $years, $p, $type, $v, $cont, $values2){
    	
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
                /*$res = $this->checkColumn($mtx, $m, $nameValues[$v], $values, $years, sizeof($mtx[$m]), $type, $v, $cont, $valuesTotal);

                if (!is_null($res)) {
                    $cont++;
                }*/

				//array_push($mtx[$m], $this->checkColumn($mtx, $m, $values[$v][$type], $values, $years, sizeof($mtx[$m]), $type, $v, $valuesYTD));
        	}
        }
		
        /*$total = $this->assemblerChurnTotal($mtx, $type, $years);
    	//var_dump($total);
    	return array($mtx, $total);*/
    }
}
