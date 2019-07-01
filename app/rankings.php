<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\agency;
use App\client;
use App\sql;
use App\pRate;

class rankings extends Model {

    public function verifyQuantity($con, $type, $type2, $region){
        
        if ($type == "agency") {
            $a = new agency();
            $resp = $a->getAgencyByRegion($con, array($region));
            $var = "agency";
        }else{
            $c = new client();
            $resp = $c->getClientByRegion($con, array($region));
            $var = "client";
        }

        for ($n=0; $n < sizeof($resp); $n++) { 
            
            $names[$n] = $resp[$n][$var];
        }

        $auxResp = array_unique($names);

        if (sizeof($type2) == sizeof($auxResp)) {
            $all = true;
        }else{
            $all = false;
        }

        return $all;

    }

    public function getResultAll($con, $brands, $type, $type2, $region, $value, $pRate, $months, $first, $second, $third){

        $sql = new sql();
        
        $table = "ytd y";

        $value .= "_revenue";
        $as = "sum";

        for ($b=0; $b < sizeof($brands); $b++) { 
            $brands_id[$b] = $brands[$b][0];
        }

        if ($type == "agency") {
            $columns = "y.agency_id AS 'agencyID', a.name AS 'agency'";
            $join = "LEFT JOIN agency a ON a.ID = y.agency_id";
            $name = "agency_id";
        }else{
            $columns = "y.client_id AS 'clientID', c.name AS 'client'";
            $join = "LEFT JOIN client c ON c.ID = y.client_id";
            $name = "client_id";
        }

        $columns .= ", SUM($value) AS '$as'";
        $cols = array("campaign_sales_office_id", "year", "brand_id", "month");

        for ($y=0; $y < 3; $y++) { 

            if ($y == 0) {
                $year = $first;
            }elseif ($y == 1) {
                $year = $second;
            }else{
                $year = $third;
            }
 
            $colsValue = array($region, $year, $brands_id, $months);
            $where = $sql->where($cols, $colsValue);
            $values[$y] = $sql->selectWithGroup($con, $columns, $table, $join, $where, "sum", $name);

            $from = array("agencyID, agency, sum");
            $res[$y] = $sql->fetch($values[$y], $from, $from);
        }

        //var_dump($res);
        return $res;
    }

    public function getVars($con, $type, $type2, $region){
    	
    	$sql = new sql();

    	if (strlen($type) > 6) {
    		
    		$a = new agency();

    		for ($g=0; $g < sizeof($type2); $g++) { 
    			$id[$g] = $a->getAgencyGroupID($con, $sql, addslashes($type2[$g]), $region);
    			$resp[$g] = $a->getAgencyByGroup($con, array($id[$g]));
    		}

    	}else{

    		if ($type == "agency") {
		    			
    			$a = new agency();

    			for ($g=0; $g < sizeof($type2); $g++) { 
    				$id[$g] = $a->getAgencyID($con, $sql, addslashes($type2[$g]));
    				$resp[$g] = $a->getAgency($con, array($id[$g]));
    			}

    		}else{

    			$c = new client();

    			for ($g=0; $g < sizeof($type2); $g++) { 
    				
    				$id[$g] = $c->getClientID($con, $sql, addslashes($type2[$g]));
    				$resp[$g] = $c->getClient($con, array($id[$g]));
    			}

    		}
    	}

    	return $resp;
    }

    public function getValues($con, $brands, $type, $vars, $region, $value, $currency, $months, $first, $second, $third){

		for ($i=0; $i < sizeof($vars); $i++) { 
			$values[0][$i][1] = 0;
			$values[1][$i][1] = 0;
			$values[2][$i][1] = 0;
		}

    	for ($i=0; $i < sizeof($vars); $i++) { 
    		for ($j=0; $j < sizeof($vars[$i]); $j++) { 
    			$values[0][$i][0] = $vars[$i][$j];
                $values[0][$i][1] += $this->calculateValue($con, $brands, $type, $vars[$i][$j], $region, $value, $currency, $months, $first);

    			$values[1][$i][0] = $vars[$i][$j];
                $values[1][$i][1] += $this->calculateValue($con, $brands, $type, $vars[$i][$j], $region, $value, $currency, $months, $second);

                $values[2][$i][0] = $vars[$i][$j];
                $values[2][$i][1] += $this->calculateValue($con, $brands, $type, $vars[$i][$j], $region, $value, $currency, $months, $third);
    		}
    	}

        $value .= "_revenue";
        for ($y=0; $y < 3; $y++) { 
            for ($v=0; $v < sizeof($values[$y]); $v++) { 
                $resp[$y][$v]["agency"] = $values[$y][$v][0]['agency'];
                $resp[$y][$v][$value] = $values[$y][$v][1];
            }
        }
        
        //var_dump($values);
        return $resp;
    }

    public function calculateValue($con, $brands, $type, $var, $region, $value, $currency, $months, $year){
    	//var_dump($var);
        for ($b=0; $b < sizeof($brands); $b++) { 
            $brands_id[$b] = $brands[$b][0];
        }

        if (strlen($type) > 6 || $type == "agency") {
            $name = "agency_id";
        }else{
            $name = "client_id";
        }

    	$sql = new sql();

    	$as = "sum";

    	$colsName = array("campaign_sales_office_id", "brand_id", "year", "month", $name);
        $colsValue = array($region, $brands_id, $year, $months, $var["id"]);
    	$value .= "_revenue";

    	$p = new pRate();

        if ($currency[0]['name'] == "USD") {
            $pRate = $p->getPRateByRegionAndYear($con, array($region),$year);
        }else{
            $pRate = 1.0;
        }    

        $where = $sql->where($colsName, $colsValue);

        $selectSum = $sql->selectSum($con, $value, $as, "ytd", null, $where);

        $rtr = $sql->fetchSum($selectSum, $as)["sum"]/$pRate;

        return $rtr;    	

    }

    public function assemble($vars, $values, $first, $second, $third){
        

    }

}
