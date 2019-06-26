<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\agency;
use App\client;
use App\sql;
use App\pRate;

class rankings extends Model {

    public function getVars($con, $type, $type2, $region){
    	
    	$sql = new sql();

    	if (strlen($type) > 6) {
    		
    		$a = new agency();

    		for ($g=0; $g < sizeof($type2); $g++) { 
    			$ag[$g] = $a->getAgencyGroupID($con, $sql, addslashes($type2[$g]), $region);
    			$resp[$g] = $a->getAgencyByGroup($con, array($ag[$g]));
    		}

    	}else{

    		if ($type == "agency") {
		    			
    			$a = new agency();

    			for ($g=0; $g < sizeof($type2); $g++) { 
    				$agID[$g] = $a->getAgencyID($con, $sql, addslashes($type2[$g]));
    				$resp[$g] = $a->getAgency($con, array($agID[$g]));
    			}

    		}else{

    			$c = new client();

    			for ($g=0; $g < sizeof($type2); $g++) { 
    				
    				$cID[$g] = $c->getClientID($con, $sql, addslashes($type2[$g]));
    				$resp[$g] = $c->getClient($con, array($cID[$g]));
    			}

    		}
    	}

    	/*for ($i=0; $i < sizeof($type2); $i++) { 
    		var_dump($i, $type2[$i]);
    	}*/

    	/*for ($i=0; $i < sizeof($resp); $i++) { 
    		var_dump($i, $resp[$i]);
    	}*/

    	return $resp;
    }

    public function getValues($con, $brands, $type, $vars, $region, $value, $currency, $months, $first, $second, $third){

		for ($i=0; $i < sizeof($vars); $i++) { 
			$values[0][$i] = 0;
			$values[1][$i] = 0;
			$values[2][$i] = 0;
		}

    	for ($i=0; $i < sizeof($vars); $i++) { 
    		for ($j=0; $j < sizeof($vars[$i]); $j++) { 
    			$values[0][$i] += $this->calculateValue($con, $brands, $type, $vars[$i][$j], $region, $value, $currency, $months, $first);
    			/*$values[1][$i] += $this->calculateValue($con, $brands, $type, $vars[$i][$j], $region, $value, $currency, $months, $second);
    			$values[2][$i] += $this->calculateValue($con, $brands, $type, $vars[$i][$j], $region, $value, $currency, $months, $third);*/
    		}
    	}

    	var_dump($values);

    }

    public function calculateValue($con, $brands, $type, $var, $region, $value, $currency, $months, $year){
    	
    	$sql = new sql();

    	$as = "sum";

    	$colsName = array("campaign_sales_office_id", "brand_id", "year", "month");
    	$value .= "_revenue";

    	$p = new pRate();

        if ($currency[0]['name'] == "USD") {
            $pRate = $p->getPRateByRegionAndYear($con, array($region),$year);
        }else{
            $pRate = 1.0;
        }    

    	for ($m=0; $m < sizeof($months); $m++) { 
    		for ($b=0; $b < sizeof($brands); $b++) { 
    			
    			$colsValue = array($region, $brands[$b][0], $year, $months[$m]);
    			$where = $sql->where($colsName, $colsValue);

    			$selectSum = $sql->selectSum($con, $value, $as, "ytd", null, $where);

    			$rtr[$m][$b] = $sql->fetchSum($selectSum, $as)["sum"]/$pRate;
    		}
    	}

    }

}
