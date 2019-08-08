<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\rankingBrand;
use App\region;
use App\brand;

class subBrandRanking extends rankingBrand {
    
	public function getSubResults($con, $type, $regionID, $value, $months, $currency, $filter){
		
		$cYear = intval(date('Y'));
		$years = array($cYear, $cYear-1);

		$p = new pRate();

        if ($currency[0]['name'] == "USD") {
            $pRate = 1.0;
        }else{
            $pRate = $p->getPRateByRegionAndYear($con, array($regionID), array($years[0]));
        }

        $b = new brand();

        $brand = $b->getBrandID($con, $filter);

        $r = new region();

        $tmp = $r->getRegion($con,array($regionID));

        if(is_array($tmp)){
            $region = $tmp[0]['name'];
        }else{
            $region = $tmp['name'];
        }

        for ($y=0; $y < sizeof($years); $y++) { 
        	
        	if ($filter == "VIX" || $filter == "ONL") {
        		$table = "digital";
        	}elseif ($region == "Brazil" && ($years[$y] == $cYear)) {
				$table = "cmaps";
			}else{
				$table = "ytd";
			}

			$res[$y] = $this->getSubValues($con, $table, $type, $regionID, $value, $years[$y], $months, $currency, $brand[0]['id']);

			if (is_array($res[$y])) {
                for ($r=0; $r < sizeof($res[$y]); $r++) { 
                    $res[$y][$r]['total'] *= $pRate;
                }
            }
        }

        return $res;

	}

    public function getSubValues($con, $tableName, $type, $region, $value, $year, $months, $currency, $filter){

    	$sql = new sql();

    	$as = "total";

        $tableAbv = "a";
        $leftAbv = "b";

        if ($tableName == "ytd") {
            $value .= "_revenue_prate";
            $columns = array("sales_representant_office_id", "month", "year", "brand_id");
            $colsValue = array($region, $months, $year, $filter);
        }elseif ($tableName == "digital") {
            $value .= "_revenue";
            $columns = array("campaign_sales_office_id", "month", "year", "brand_id");
            $colsValue = array($region, $months, $year, $filter);
        }elseif ($tableName == "plan_by_brand") {
            $columns = array("sales_office_id","type_of_revenue", "month", "year", "brand_id");
            $colsValue = array($region, $value, $months, $year, $filter);
            $value = "revenue";
        }else{
            $columns = array("month", "year", "brand_id");
            $colsValue = array($months, $year, $filter);
        }

        $table = "$tableName $tableAbv";

        $leftName = $type;

        $tmp = $tableAbv.".".$type."_id AS '".$type."ID', ".$leftAbv."."."name AS '".$type."', SUM($value) AS $as";

            $join = "LEFT JOIN ".$leftName." ".$leftAbv." ON ".$leftAbv."."."ID = ".$tableAbv.".".$type."_id";

        $name = $type."_id";
        $names = array($type."ID", $type, $as);

        $where = $sql->where($columns, $colsValue);

        $values = $sql->selectGroupBy($con, $tmp, $table, $join, $where, "total", $name, "DESC");

        $from = $names;

        $res = $sql->fetch($values, $from, $from);

        //var_dump($res);
        return $res;
    }

    public function assemble($names, $values, $type){
    	
    	$cYear = intval(date('Y'));
		$years = array($cYear, $cYear-1);

    }
}
