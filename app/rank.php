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
            $years = array($fisrt, $second);
        }else{
            $years = array($first, $second, $third);
        }

        return $years;
    }

    public function verifyQuantity($con, $type, $type2, $region){
        
        if (strlen($type) > 6) {
        	$a = new agency();
        	$resp = $a->getAgencyGroupByRegion($con, array($region));
        	$var = "agencyGroup";
        }elseif ($type == "agency") {
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

    public function createSearch($tableName, $leftName, $type, $value){

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

        $tmp = $tableAbv.$type."_id AS '".$type."ID', "
               $leftAbv."name AS '".$type."', SUM($value) AS $as";

        $join = "LEFT JOIN ".$leftName." ".$leftAbv." ON ".$leftAbv."ID = ".$tableAbv.$type."_id";       

        $name = $type."_id";
        $names = array($type."ID", $type, $as);

        for ($y=0; $y < sizeof($years); $y++) {

        	array_push($colsValue, $years[$y]);
        	$where = $sql->where($cols, $colsValue);
        	$values[$y] = $sql->selectWithGroup($con, $columns, $table, $join, $where, "total", $name, "DESC");
        	array_pop($colsValue);
        	
        }

    }
}
