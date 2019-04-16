<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/*
*Author: Bruno Gomes
*Date:11/04/2019
*Razon:Pacing modeler, which you can pass as parameters: colluns, tables, where and order_by. Be aware to matching the colluns and tables names to be used.
*/
class pacing extends Model
{
    /*
	*Author: Bruno Gomes
	*Date:11/04/2019
	*Razon:Query modeler
	*/
    public function query($con, $colluns, $tabels, $where, $order_by)
    {
    	$sql = "SELECT $colluns FROM $tabels WHERE $where ;";

    	if (isset($order_by)) {
    		$sql = "SELECT $colluns FROM $tabels WHERE $where $order_by ;";
    	}

    	$res = $con->query($sql);

    	return $res;
    }

    /*
	*Author: Bruno Gomes
	*Date:11/04/2019
	*Razon:Colluns modeler
	*/
    public function colluns(
    	$region,
    	$user,
    	$currency,
    	$date,
    	$type_of_value,
    	$pacing_month
    )
    {
    	$colluns = "";

    	if ($region) {
    		$colluns .= "region.name AS 'region_name', ";
    	}

    	if ($user) {
    		$colluns .= "user.name AS 'user_name', ";
    	}

    	if ($currency) {
    		$colluns .= "currency.name AS 'currency_name', ";
    	}

    	if ($date) {
    		$colluns .= "pacing.date AS 'date'";
    	}

    	if ($type_of_value) {
    		$colluns .= "pacing.type_of_value AS 'type_value', ";
    	}

    	if ($pacing_month) {
    		$colluns .= "pacing.pacing_month AS 'pacing_month', ";
    	}

    	$colluns .= "pacing.ID AS ID";

    	return $colluns;
    }

    /*
	*Author: Bruno Gomes
	*Date:11/04/2019
	*Razon:Table modeler
	*/
    public function table(
    	$region,
    	$currency,
    	$user
    )
    {
    	$table = "'DLA'.'pacing_report' AS pacing";

    	if ($region) {
    		$table .= "LEFT JOIN 'DLA'.'region' AS region ON region.ID = pacing.region_id";
    	}

    	if ($currency) {
    		$table .= "LEFT JOIN 'DLA'.'currency' AS currency ON currency.ID = pacing.currency_id";
    	}

    	if ($user) {
    		$table .= "LEFT JOIN 'DLA'.'user' AS user ON user.ID = pacing.user_id";
    	}

    	return $table;
    }

    /*
	*Author: Bruno Gomes
	*Date:11/04/2019
	*Razon:Where modeler
	*/
    public function where(	
    	$region,
    	$user
    ){
    	$where = "";

    	if ($region) {
    		$region_ids = implode(",", $region);
    		$where .= "region.ID IN ('.$region_ids.')";
    		if ($user) {
    			$where .= " AND ";
    		}
    	}

    	if ($user) {
    		$user_ids = implode(",", $user);
    		$where .= "user.ID IN ('.$user_ids.')";
    	}

    	return $where;
    }

    /*
	*Author: Bruno Gomes
	*Date:11/04/2019
	*Razon:Order_by modeler
	*/
    public function order_by(
    	$region,
    	$user
    )
    {
    	$order_by = "ORDER_BY";

    	if ($region) {
    		$order_by .= "region.name";
    		if ($user) {
    			$order_by .= " , ";
    		}
    	}

    	if ($user) {
    		$order_by .= "user.name";    		
    	}

    	$order_by .= " ASC";

    	return $order_by;
    }

    /*
	*Author: Bruno Gomes
	*Date:12/04/2019
	*Razon:Pacing_report_unit modeler
	*/
    public function pacing_report_unit(
    	$pacing_report_id,
    	$brand,
    	$client

    )
    {
    	$where = "WHERE ";

    	if ($pacing_report_id) {
    		$pacing_report_ids = implode(",", $pacing_report_id);
    		$where .= "pacing_report_unit.pacing_report_id IN ('.$pacing_report_ids.')";
    		if ($brand OR $client) {
    			$where .= " AND ";
    		}
    	}

    	if ($brand) {
    		$brand_ids = implode(",", $brand);
    		$where .= "pacing_report_unit.brand_id IN ('.$brand_ids.')";
    		if ($pacing_report_id OR $client) {
    			$where .= " AND ";
    		}
    	}

    	if ($client) {
    		$client_ids = implode(",", $client);
    		$where .= "pacing_report_unit.client_id IN ('.$client_ids.')";
    	}

    	$sql = "
    		SELECT
    			pacing_report_unit.ID AS 'ID',
    			brand.name AS 'brand_name',
    			client.name AS 'client_name',
    			pacing_report_unit.month AS 'pacing_unit_month',
    			pacing_report_unit.currency_value AS 'currency_value',
    			pacing_report_unit.full_year_value AS 'full_year_value'  

    		FROM 'DLA'.'pacing_report' AS pacing
    			LEFT JOIN 'DLA'.'pacing_report_unit' AS pacing_report_unit ON pacing_report_unit.pacing_report_id = pacing.ID 
    			LEFT JOIN 'DLA'.'brand' AS brand ON brand.ID = pacing_report_unit.brand_id
    			LEFT JOIN 'DLA'.'client' AS client ON client.ID = pacing_report_unit.client_id

    		$where

    	";

    	$res = $con->query($sql);

    	return $res;
    }
}
