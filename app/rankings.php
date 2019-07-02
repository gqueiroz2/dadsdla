<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\agency;
use App\client;
use App\sql;
use App\pRate;
use App\rank;

class rankings extends rank{

    public function cmpTotal($element1, $element2){
        
        $v1 = $element1['total'];
        $v2 = $element2['total'];

        if ($v1 == $v2) {
            return 0;
        }

        return ($v1 < $v2) ? 1 : -1;
    }

    

    public function getResultAll($con, $brands, $type, $type2, $region, $value, $currency, $months, $years){

        for ($b=0; $b < sizeof($brands); $b++) { 
            $brands_id[$b] = $brands[$b][0];
        }

        $sql = new sql();
        
        if (strlen($type) > 6) {
            $a = new agency();

            $group = $a->getAgencyGroupByRegion($con, array($region));

            for ($g=0; $g < sizeof($group); $g++) { 
                
                $resp[$g] = $a->getAgencyByGroup($con, array($group[$g]['id']));
            }

            for ($i=0; $i < sizeof($resp); $i++) { 
                for ($y=0; $y < sizeof($years); $y++) { 
                    $res[$y][$i]['agencyID'] = $resp[$i][0]['agencyGroupID'];
                    $res[$y][$i]['agency'] = $resp[$i][0]['agencyGroup'];
                    $res[$y][$i]['total'] = 0;
                    for ($j=0; $j < sizeof($resp[$i]); $j++) { 
                        $res[$y][$i]['total'] += $this->calculateValue($con, $brands_id, $type, $res[$y][$i]['agencyID'], $region, $value, $currency, $months, $years[$y]);
                    }

                    usort($res[$y], array($this, "cmpTotal"));
                }
            }

        }else{

            if ($type == "agency") {
                $columns = "y.agency_id AS 'agencyID', a.name AS 'agency'";
                $join = "LEFT JOIN agency a ON a.ID = y.agency_id";
                $name = "agency_id";
                $names = array("agencyID", "agency", "total");
            }else{
                $columns = "y.client_id AS 'clientID', c.name AS 'client'";
                $join = "LEFT JOIN client c ON c.ID = y.client_id";
                $name = "client_id";
                $names = array("clientID", "client", "total");
            }

            $columns .= ", SUM($value) AS $as";
            $cols = array("campaign_sales_office_id", "year", "brand_id", "month");

            for ($y=0; $y < sizeof($years); $y++) { 
     
                $colsValue = array($region, $years[$y], $brands_id, $months);
                $where = $sql->where($cols, $colsValue);
                $values[$y] = $sql->selectWithGroup($con, $columns, $table, $join, $where, "total", $name, "DESC");

                $from = $names;

                $res[$y] = $sql->fetch($values[$y], $from, $from);

            }

            for ($y=0; $y < sizeof($years); $y++) {
                if (is_array($res[$y])) {
                    for ($r=0; $r < sizeof($res[$y]); $r++) { 

                        $p = new pRate();

                        if ($currency[0]['name'] == "USD") {
                            $pRate = $p->getPRateByRegionAndYear($con, array($region), $years[$y]);
                        }else{
                            $pRate = 1.0;
                        }

                        $res[$y][$r]['total'] /= $pRate;
                    }   
                }else{

                }
            }
        }

        //var_dump($res);
        return $res;
    }

    public function calculateValue($con, $brands_id, $type, $var, $region, $value, $currency, $months, $year){
    	//var_dump($var);

        if (strlen($type) > 6 || $type == "agency") {
            $name = "agency_id";
        }else{
            $name = "client_id";
        }

    	$sql = new sql();

    	$as = "sum";

    	$colsName = array("campaign_sales_office_id", "brand_id", "year", "month", $name);
        $colsValue = array($region, $brands_id, $year, $months, $var);
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

    public function checkOtherYearsPosition($name, $values, $year, $type){
        
        if ($year == "2019") {
            $y = 0;
        }elseif ($year == "2018") {
            $y = 1;
        }else{
            $y = 2;
        }

        $ok = 0;

        if (is_array($values[$y])) {
            for ($v=0; $v < sizeof($values[$y]); $v++) { 
                if ($values[$y][$v][$type] == $name) {
                    $pos = $v+1;
                    $ok = 1;
                }
            }   
        }else{
            $pos = false;
        }

        if ($ok == 0) {
            $pos = 0;
        }

        return $pos;

    }

    public function getValueByYear($name, $values, $year, $type){
        
        if ($year == "2019") {
            $y = 0;
        }elseif ($year == "2018") {
            $y = 1;
        }else{
            $y = 2;
        }

        $ok = 0;

        if (is_array($values[$y])) {
            for ($v=0; $v < sizeof($values[$y]); $v++) { 
            
                if ($values[$y][$v][$type] == $name) {
                    $rtr = $values[$y][$v]['total'];
                    $ok = 1;
                }
            }
        }else{
            $rtr = false;
        }

        if ($ok == 0) {
            $rtr = 0;
        }

        return $rtr;
    }

    public function checkColumn($mtx, $m, $v, $values, $name, $val, $type){
        
        if (substr($mtx[$m][0], 0, 3) == "Pos") {
            $var = substr($mtx[$m][0], 5);

            $res = $this->checkOtherYearsPosition($name, $values, $var, $type);
        }elseif (substr($mtx[$m][0], 0, 3) == "Rev") {
            $var = substr($mtx[$m][0], 5);

            $res = $this->getValueByYear($name, $values, $var, $type);
        }elseif ($mtx[$m][0] == "VAR ABS.") {
            $res = $mtx[1][$v] - $mtx[2][$v];
        }elseif ($mtx[$m][0] == "VAR %") {
            if ($mtx[2][$v] == 0) {
                $res = 0.0;
            }else{
                $res = ($mtx[1][$v] / $mtx[2][$v])*100;
            }
        }else{
            $res = $name;
        }

        return $res;

    }

    public function assembler($values, $years, $type){
        //var_dump($values);

        if (strlen($type) > 6) {
            $var = "agency groups";
            $aux = "agency";
        }else{
            $var = $type;
            $var .= "s";
            $aux = $type;
        }

        for ($y=0; $y < sizeof($years); $y++) { 
            $mtx[$y][0] = "Pos. ".$years[$y];
        }

        $last = $y;
        
        $mtx[$last][0] = ucfirst($var);
        

        for ($l=0; $l < sizeof($years); $l++) { 
            
            $mtx[(sizeof($years)+$l+1)][0] = "Rev. ".$years[$l];
        }

        if (sizeof($years) >= 2) {
            $last = $l+sizeof($years)+1;

            $mtx[$last][0] = "VAR ABS.";
            $mtx[$last+1][0] = "VAR %";    
        }

        for ($y=0; $y < sizeof($years); $y++) {
            if (is_array($values[$y])) {
                for ($v=0; $v < sizeof($values[$y]); $v++) { 
                    for ($m=0; $m < sizeof($mtx); $m++) {
                        $mtx[$m][$v+1] = $this->checkColumn($mtx, $m, ($v+1), $values, $values[$y][$v][$aux], $values[$y][$v]['total'], $aux);
                    }
                }    
            }else{
                $mtx[$m][$v+1] = false;
            }
            
        }

        var_dump($mtx);

    }

}
