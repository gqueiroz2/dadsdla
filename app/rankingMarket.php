<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\rank;
use App\region;
use App\pRate;

class rankingMarket extends rank {
    
    public function getAllResults($con, $brands, $type, $regionID, $region, $value, $currency, $months, $years, $sector=false){

    	if ($sector) {

    		$cMonth = intval(date('m'));
    		$months2 = array();
    		for ($m=1; $m <= $cMonth; $m++) { 
    			array_push($months2, $m);
    		}
    		$res = $this->getAllValues($con, "cmaps", $type, $type, $brands, $regionID, $value, $years, $months2, $currency, "DESC");
    	}else{
    		if ($region == "Brazil") {
				$res = $this->getAllValues($con, "cmaps", $type, $type, $brands, $regionID, $value, $years, $months, $currency, "DESC");
			}else{
				$res = $this->getAllValues($con, "ytd", $type, $type, $brands, $regionID, $value, $years, $months, $currency, "DESC");    	
			}
    	}
        
        return $res;
    }

    public function searchPos($name, $values, $type){
    	
		for ($s2=0; $s2 < sizeof($values[0]); $s2++) { 
			if ($name == $values[0][$s2][$type]) {
				return ($s2+1);
			}
		}
    	

    	return "-";
    }

    public function searchValueByYear($name, $values, $type, $year){
    	
		for ($s2=0; $s2 < sizeof($values[$year]); $s2++) { 
			if ($name == $values[$year][$s2][$type]) {
				return $values[$year][$s2]['total'];
			}
		}
    	

    	return 0;
    }

    public function searchGroupValue($name, $values){
    	
		for ($s2=0; $s2 < sizeof($values[0]); $s2++) { 
			if ($name == $values[0][$s2]['agency']) {
				if ($values[0][$s2]['agencyGroup'] == "Others") {
					return "-";
				}else{
					return $values[0][$s2]['agencyGroup'];
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

    public function checkColumn($mtx, $m, $name, $values, $years, $p, $type, $values2=null){
    	
    	if ($mtx[$m][0] == "Ranking") {
    		$res = $this->searchPos($name, $values, $type);
    	}elseif ($mtx[$m][0] == "Agency group") {
    		$res = $this->searchGroupValue($name, $values);
    	}elseif ($mtx[$m][0] == $years[0]) {
    		$res = $this->searchValueByYear($name, $values, $type, 0);
    	}elseif ($mtx[$m][0] == $years[1]) {
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

    		if ($mtx[$m-$pos][$p] - $mtx[$m-$pos-1][$p] > 0) {
    			$res = "Incresed";
    		}else{
    			$res = "Decreased";
    		}
    	}elseif ($mtx[$m][0] == "Class") {
    		if ($mtx[$m-3][$p] == 0 && $mtx[$m-4][$p] > 0) {
    			if ($this->existInYear($name, $values, $type, 1)) {
    				$res = "Recovered";
    			}else{
    				$res = "New";
    			}
    		}elseif ($mtx[$m-3][$p] > 0 && $mtx[$m-4][$p] > 0) {
    			$res = "Renovated";
    		}else{
    			$res = "Churn";
    		}
    	}elseif ($mtx[$m][0] == "YTD ".$years[0]) {
    		$res = $this->searchValueByYear($name, $values2, $type, 0);
    	}elseif ($mtx[$m][0] == "YTD ".$years[1]) {
    		$res = $this->searchValueByYear($name, $values2, $type, 1);
    	}elseif ($mtx[$m][0] == "Var YTD (%)") {
    		if ($mtx[$m-2][$p] == 0 || $mtx[$m-1][$p] == 0) {
    			$res = 0;
    		}else{
    			$res = ($mtx[$m-2][$p]/$mtx[$m-1][$p])*100;
    		}
    	}elseif ($mtx[$m][0] == "Var Abs. YTD") {
    		$res = $mtx[$m-3][$p] - $mtx[$m-2][$p];
    	}elseif ($mtx[$m][0] == "Move YTD") {
    		if ($mtx[$m-3][$p] - $mtx[$m-4][$p] > 0) {
    			$res = "Incresed";
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

    	$firstYtd = 0;
    	$secondYtd = 0;

    	if ($type == "agency") {
    		$pos = 3;
    	}else{
    		$pos = 2;
    	}

    	for ($m=1; $m < sizeof($mtx[0]); $m++) { 
    		$first += $mtx[$pos][$m];
    		$second += $mtx[$pos+1][$m];

    		if ($type == "sector") {
    			$firstYtd += $mtx[7][$m];
    			$secondYtd += $mtx[8][$m];
    		}
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

    	if ($type == "sector") {
			$total[7] = $firstYtd;
			$total[8] = $secondYtd;
			$total[9] = ($total[7]/$total[8])*100;
			$total[10] = $total[7] - $total[8];
		}

    	return $total;
    }

    public function assembler($values, $years, $type, $values2=null){
    	
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

    	if ($type == "client") {
			$mtx[$pos][0] = "Class";$pos++;    		
    	}

    	$mtx[$pos][0] = "Move";$pos++;

    	if ($type == "sector") {
    		$mtx[$pos][0] = "YTD ".$years[0];$pos++;
			$mtx[$pos][0] = "YTD ".$years[1];$pos++;
			$mtx[$pos][0] = "Var YTD (%)";$pos++;
			$mtx[$pos][0] = "Var Abs. YTD";$pos++;
			$mtx[$pos][0] = "Move YTD";$pos++;	
    	}

		/*$types = array();

        for ($r=0; $r < sizeof($values); $r++) { 
            if (is_array($values[$r])) {
                for ($r2=0; $r2 < sizeof($values[$r]); $r2++) { 
                    if (!in_array($values[$r][$r2][$type], $types)) {
                        array_push($types, $values[$r][$r2][$type]);  
                    }
                }   
            }
        }*/

        for ($t=0; $t < sizeof($values[0]); $t++) { 
        	for ($m=0; $m < sizeof($mtx); $m++) { 
        		if ($type == "sector") {
        			array_push($mtx[$m], $this->checkColumn($mtx, $m, $values[0][$t][$type], $values, $years, sizeof($mtx[$m]), $type, $values2));
        		}else{
        			array_push($mtx[$m], $this->checkColumn($mtx, $m, $values[0][$t][$type], $values, $years, sizeof($mtx[$m]), $type));
        		}
        	}
        }

        $total = $this->assemblerMarketTotal($mtx, $type, $years);
    	
    	return array($mtx, $total);
    }

    public function createNames($type, $months, $region, $brands){
        
        $res['name'] = ucfirst($type);

        if ($region == "Brazil") {
            $res['source'] = "CMAPS";
        }else{
            $res['source'] = "IBMS";
        }

        $res['brands'] = "";

        for ($b=0; $b < sizeof($brands); $b++) { 
            $res['brands'] .= $brands[$b][1];

            if ($b != (sizeof($brands)-1)) {
                $res['brands'] .= " - ";                
            }
        }

        $b = new base();

        if (sizeof($months) == 12) {
            $res['months'] = "All Year";            
        }else{
            $month = $b->intToMonth2($months);

            $res['months'] = "";

            for ($m=0; $m < sizeof($month); $m++) { 
                
                $res['months'] .= $month[$m];

                if ($m == sizeof($month)-2) {
                    $res['months'] .= " and ";
                }elseif (($m != sizeof($month)-2) && ($m != sizeof($month)-1)) {
                    $res['months'] .= ", ";
                }
                
            }
        }

        return $res;
    }
}
