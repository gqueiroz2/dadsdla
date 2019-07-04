<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class rank extends Model{
    
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

    public function getAllValues($con, $tableName, $leftName, $type, $brands, $region,  $value, $years, $months, $currency, $leftName2=null){
        
        for ($b=0; $b < sizeof($brands); $b++) { 
            $brands_id[$b] = $brands[$b][0];
        }

        $sql = new sql();
        
        $as = "total";

        if ($type == "agencyGroup") {
            $tableAbv = "a";
            $leftAbv = "b";
            $leftAbv2 = "c";

            if ($tableName == "ytd" || $tableName == "mini_header") {
                $value .= "_revenue";
                $columns = array("campaign_sales_office_id", "brand_id", "month", "year");
                $colsValue = array($region, $brands_id, $months);
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
                $values[$y] = $sql->selectGroupBy($con, $tmp, $table, $join, $where, "total", $name, "DESC");
                array_pop($colsValue);

                $from = $names;

                $res[$y] = $sql->fetch($values[$y], $from, $from);
                
            }

        }else{

            $tableAbv = "a";
            $leftAbv = "b";

            $as = "total";

            if ($tableName == "ytd" || $tableName == "mini_header") {
                $value .= "_revenue";
                $columns = array("campaign_sales_office_id", "brand_id", "month", "year");
                $colsValue = array($region, $brands_id, $months);
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
                        $pRate = $p->getPRateByRegionAndYear($con, array($region), $years[$y]);
                    }else{
                        $pRate = 1.0;
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
