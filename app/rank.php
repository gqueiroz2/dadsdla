<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\agency;

class rank extends Model{
    
    public function getName($value){
        
        $newValue = "";
        $ok = false;
        for ($v=0; $v < strlen($value); $v++) { 
            
            if ($value[$v] != "-" && !$ok) {
                $newValue .= $value[$v];
            }else{
                $ok = true;
            }
        }
        //var_dump(substr($newValue, 0, (strlen($newValue)-1)));
        return substr($newValue, 0, (strlen($newValue)-1));
    }

    public function createPositions($first, $second, $third){
        
        if ($second == 0 && $third == 0) {
            $years = array($first);
        }elseif ($second == 0) {
            $years = array($first, $third);
        }elseif ($third == 0) {
            $years = array($first, $second);
        }else{
            $years = array($first, $second, $third);
        }

        return $years;
    }

    public function verifyQuantity($con, $type, $type2, $region){
        
        if ($type == "agencyGroup") {
        	$a = new agency();
        	$resp = $a->getAgencyGroupByRegion($con, array($region));
        }elseif ($type == "agency") {
            $a = new agency();
            $resp = $a->getAgencyByRegion($con, array($region));
        }else{
            $c = new client();
            $resp = $c->getClientByRegion($con, array($region));
        }

        for ($n=0; $n < sizeof($resp); $n++) { 
            
            $names[$n] = $resp[$n][$type];
        }

        $auxResp = array_unique($names);

        if (sizeof($type2) == sizeof($auxResp)) {
            $all = true;
        }else{
            $all = false;
        }

        return $all;

    }

    public function getSubValues($con, $tableName, $leftName, $type, $brands, $region, $value, $year, $mtx, $months, $currency, $y){
        
        if ($type == "agencyGroup") {
            $filter = "agency_group_id";
        }else{
            $filter = "agency_id";
        }

        for ($b=0; $b < sizeof($brands); $b++) { 
            $brands_id[$b] = $brands[$b][0];
        }

        $sql = new sql();

        $as = "total";

        $tableAbv = "a";
        $leftAbv = "b";

        $a = new agency();

        if ($tableName == "ytd") {
            $value .= "_revenue";
            $columns = array("campaign_sales_office_id", "campaign_currency_id", "brand_id", "month", "year", $filter);
            $colsValue = array($region, $currency[0]['id'], $brands_id, $months, $year, "");
        }else{
            $columns = array("brand_id", "month", "year", $filter);
            $colsValue = array($brands_id, $months, $year, "");
        }

        $table = "$tableName $tableAbv";

        $tmp = $tableAbv.".".$leftName."_id AS '".$leftName."ID', ".$leftAbv.".name AS '".$leftName."', SUM($value) AS $as";

        $join = "LEFT JOIN ".$leftName." ".$leftAbv." ON ".$leftAbv.".ID = ".$tableAbv.".".$leftName."_id";

        $name = $leftName."_id";
        $names = array($leftName."ID", $leftName, $as);
        //var_dump("expression");
        for ($m=1; $m < sizeof($mtx); $m++) { 
            
            if ($type == "agencyGroup") {
                $agency = $a->getAgencyGroupID($con, $sql, $mtx[$m], $region);
            }else{
                $nameMtx = $this->getName($mtx[$m]);
                $agency = $a->getAgencyID($con, $sql, $nameMtx);    
            }
            
            $colsValue[(sizeof($colsValue)-1)] = $agency;
            $where = $sql->where($columns, $colsValue);
            $values[$y] = $sql->selectGroupBy($con, $tmp, $table, $join, $where, "total", $name);

            $from = $names;

            $res[$m-1] = $sql->fetch($values[$y], $from, $from);
        }
    
        
        for ($r=0; $r < sizeof($res); $r++) { 
            if (is_array($res[$r])) {
                for ($r2=0; $r2 < sizeof($res[$r]); $r2++) {                     
                    $p = new pRate();

                    if ($currency[0]['name'] == "USD") {
                        $pRate = 1.0;
                    }else{
                        $pRate = $p->getPRateByRegionAndYear($con, array($region), array($year));
                    }

                    $res[$r][$r2]['total'] /= $pRate;   
                }   
            }else{

            }
        }   
        
        //var_dump($res);
        return $res;
    }

    public function getAllValues($con, $tableName, $leftName, $type, $brands, $region, $value, $years, $months, $currency, $leftName2=null){
        
        for ($b=0; $b < sizeof($brands); $b++) { 
            $brands_id[$b] = $brands[$b][0];
        }

        $sql = new sql();
        
        $as = "total";

        if ($type == "agencyGroup") {
            $tableAbv = "a";
            $leftAbv = "b";
            $leftAbv2 = "c";

            if ($tableName == "ytd") {
                $value .= "_revenue";
                $columns = array("campaign_sales_office_id", "campaign_currency_id", "brand_id", "month", "year");
                $colsValue = array($region, $currency[0]['id'], $brands_id, $months);
            }else{
                $columns = array("brand_id", "month", "year");
                $colsValue = array($brands_id, $months);
            }

            $table = "$tableName $tableAbv";

            $tmp = $leftAbv.".ID AS '".$type."ID', ".
                   $leftAbv.".name AS '".$type."', SUM($value) AS $as";

           $join = "LEFT JOIN ".$leftName2." ".$leftAbv2." ON ".$leftAbv2."."."ID = ".     $tableAbv.".".$leftName2."_id
                    LEFT JOIN ".substr($leftName, 0, 6)."_group ".$leftAbv." ON ".$leftAbv.".ID = ".$leftAbv2.".".substr($leftName, 0, 6)."_group_id";

            $name = substr($type, 0, 6)."_group_id";
            $names = array($type."ID", $type, $as);

            for ($y=0; $y < sizeof($years); $y++) {

                array_push($colsValue, $years[$y]);
                $where = $sql->where($columns, $colsValue);
                $values[$y] = $sql->selectGroupBy($con, $tmp, $table, $join, $where, "total", $name, "ASC");
                array_pop($colsValue);

                $from = $names;

                $res[$y] = $sql->fetch($values[$y], $from, $from);
                
            }

        }else{

            $tableAbv = "a";
            $leftAbv = "b";

            $as = "total";

            if ($tableName == "ytd") {
                $value .= "_revenue";
                $columns = array("campaign_sales_office_id", "campaign_currency_id", "brand_id", "month", "year");
                $colsValue = array($region, $currency[0]['id'], $brands_id, $months);
            }else{
                $columns = array("brand_id", "month", "year");
                $colsValue = array($brands_id, $months);
            }

            $table = "$tableName $tableAbv";

            $tmp = $tableAbv.".".$type."_id AS '".$type."ID', ".
                   $leftAbv."."."name AS '".$type."', SUM($value) AS $as";

            $join = "LEFT JOIN ".$leftName." ".$leftAbv." ON ".$leftAbv."."."ID = ".$tableAbv.".".$type."_id";       

            $name = $type."_id";
            $names = array($type."ID", $type, $as);

            for ($y=0; $y < sizeof($years); $y++) {

                array_push($colsValue, $years[$y]);
                $where = $sql->where($columns, $colsValue);
                $values[$y] = $sql->selectGroupBy($con, $tmp, $table, $join, $where, "total", $name, "DESC");
                array_pop($colsValue);

                $from = $names;

                $res[$y] = $sql->fetch($values[$y], $from, $from);
                
            }

        }

        for ($y=0; $y < sizeof($years); $y++) {
            if (is_array($res[$y])) {
                for ($r=0; $r < sizeof($res[$y]); $r++) { 

                    $p = new pRate();

                    if ($currency[0]['name'] == "USD") {
                        $pRate = 1.0;
                    }else{
                        $pRate = $p->getPRateByRegionAndYear($con, array($region), array($years[$y]));
                    }

                    $res[$y][$r]['total'] /= $pRate;
                }   
            }else{

            }
        }
        
        return $res;

    }

    public function searchValue($name, $values, $type){

        for ($v=0; $v < sizeof($values); $v++) {
            $something = $type."ID";
            /*var_dump("name:".$name->id);
            var_dump("values:".$values[$v][$something]);*/
            if ($name->id == $values[$v][$something]) {
                //var_dump($name->id);
                return 1;
            }
        }

        return 0;

    }

    public function filterValues($values, $type2, $type){
        //var_dump($type2);
        for ($t=0; $t < sizeof($type2); $t++) {
            $res[$type2[$t]->id] = $this->searchValue($type2[$t], $values[0], $type);
        }

        return $res;
        
    }
}
