<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/*
*Author: Bruno Gomes
*Date:12/04/2019
*Razon:Forecastmodeler, which you can pass as parameters: colluns, tables, where and order_by. Be aware to matching the colluns and tables names to be used.
*/
class forecast extends Model
{
    /*
	*Author: Bruno Gomes
	*Date:12/04/2019
	*Razon:Query modeler
	*/
    public function query($con, $colluns, $tabels, $where, $order_by = 1)
    {
        $sql = "SELECT $colluns FROM $tabels WHERE $where $order_by ;";

    	$res = $con->query($sql);

    	return $res;
    }

    /*
	*Author: Bruno Gomes
	*Date:12/04/2019
	*Razon:Colluns modeler
	*/
    public function colluns (
    	$region,
    	$sales_rep,
    	$currency,
    	$date,
    	$type_value,
    	$read,
    	$forecast_month
    )
    {
    	$colluns = "";

    	if ($region) {
    		$colluns .= "region.name AS 'regionName', ";
    	}

    	if ($sales_rep) {
    		$colluns .= "sales_rep.name AS 'salesRepName',";
    	}

    	if ($currency) {
    		$colluns .= "currency.name AS 'currencyName',";
    	}

    	if ($date) {
    		$colluns .= "forecast.date AS 'date', ";
    	}

    	if ($type_value) {
    		$colluns .= "forecast.type_of_value AS 'typeValue', ";
    	}

    	if ($read) {
    		$colluns .= "forecast.read AS 'read', ";
    	}

    	if ($forecast_month) {
    		$colluns .= "forecast.forecast_month AS 'forecastMonth', ";
    	}

    	$colluns .= "forecast.ID AS 'id'";

    	return $colluns;
    }

    /*
	*Author: Bruno Gomes
	*Date:12/04/2019
	*Razon:Table modeler
	*/
    public function table (
    	$region,
    	$sales_rep,
    	$currency
    )
    {
    	$table = "'forecast' AS forecast";

    	if ($region) {
    		$table .= "LEFT JOIN 'region'  ON region.ID = forecast.region_id";
    	}

    	if ($sales_rep) {
    		$table .= "LEFT JOIN 'sales_rep'  ON sales_rep.ID = forecast.sales_rep_id";
    	}

    	if ($currency) {
    		$table .= "LEFT JOIN 'currency' ON currency.ID = forecast.currency_id";
    	}

    	return $table;
    }

    /*
	*Author: Bruno Gomes
	*Date:12/04/2019
	*Razon:Where modeler
	*/
    public function where(
    	$region,
    	$sales_rep

    )
    {
    	$where = "WHERE ";

    	if ($region) {
    		$region_ids = implode(",", $region);
    		$where .= "region.ID IN ('$region_ids')";
    		if ($sales_rep) {
    			$where .= " AND ";
    		}
    	}

    	if ($sales_rep) {
    		$sales_rep_ids = implode(",", $sales_rep);
    		$where .= "sales_rep.ID IN ('$sales_rep_ids')";
    	}

    	return $where;
    }

    /*
	*Author: Bruno Gomes
	*Date:12/04/2019
	*Razon:Order_by modeler
	*/
    public function order_by(
    	$region,
    	$sales_rep,
        $order
    )
    {
    	$order_by = "ORDER BY ";

    	if ($region) {
    		$order_by .= "region.name";
    		if ($sales_rep) {
    			$order_by .= " , ";
    		}
    	}

    	if ($sales_rep) {
    		$order_by .= "sales_rep.name";
    	}

        //this parameters, pass it as true or false, for true the result will be ASC
        if ($order == TRUE) {
            $order_by .= " ASC";
        }
        else{
            $order_by .= " DESC";
        }

        return $order_by;
    }

    /*
	*Author: Bruno Gomes
	*Date:12/04/2019
	*Razon:Forecast unit modeler
	*/
    public function forecast_unit (
    	$forecast_id,
    	$client_id,
    	$brand_id
    )
    {
    	$where = "WHERE ";

    	if ($forecast_id) {
    		$forecast_ids = implode(",", $forecast_id);
    		$where .= "forecast_unit.forecast_id IN ('$forecast_ids')";
    		if ($client_id OR $brand_id) {
    			$where = " AND ";
    		}
    	}

    	if ($client_id) {
    		$client_ids = implode(",", $client_id);
    		$where .= "forecast_unit.client_id IN ('$client_ids')";
    		if ($forecast_id OR $brand_id) {
    			$where .= " AND ";
    		}
    	}

    	if ($brand_id) {
    		$brand_ids = implode(",", $brand_id);
    		$where .= !"forecast_unit.brand_id IN ('$brand_ids')";
    	}

    	$sql = "
    		SELECT
    			forecast_unit.ID AS 'id',
    			client.name AS 'clientName',
    			brand.name AS 'brandName',
    			forecast_unit.month AS 'month',
    			forecast_unit.value AS 'value'

    		FROM 'forecast_unit'  forecast_unit
    			LEFT JOIN 'forecast'  ON forecast.ID = forecast_unit.forecast_id
    			LEFT JOIN 'client'  ON  client.ID = forecast_unit.client_id
    			LEFT JOIN 'brand'  ON brand.ID = forecast_unit.brand_id


    		$where
    	";

    	$res = $con->query($sql);

    	return $res;
    }
}
