<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/*
*Author: Bruno Gomes
*Date:10/04/2019
*Razon:RollingForecast modeler
*/
class rollingForecast extends Model
{
    /*
	*Author: Bruno Gomes
	*Date:10/04/2019
	*Razon:Query modeler
	*/
    public function query($con, $colluns, $tabels, $where, $order_by)
    {
    	$sql = "SELECT $colluns FROM $tabels WHERE $where";

    	if (isset($order_by)) {
    		$sql = "SELECT $colluns FROM $tabels WHERE $where $order_by ";
    	}

    	$res = $con->query($sql);

    	return $res;
    }

    /*
	*Author: Bruno Gomes
	*Date:10/04/2019
	*Razon:Colluns modeler
	*/
    public function colluns(
    	$region_id,
    	$currency_id,
    	$broad_id,
    	$client_id,
    	$sales_rep_id,
    	$opportunity_name,
    	$phase,
    	$cathegory,
    	$prediction_date,
    	$closing_date,
    	$campaign_start,
    	$campaign_end,
    	$expected_amount
    )
    {
    	$colluns = "";

    	if ($region_id) {    		
    		$colluns .= "region.name AS 'region_name', ";
    	}

    	if ($currency_id) {
    		$colluns .= "currency.name AS 'currency_name', ";
    	}

    	if ($brand_id) {
    		$colluns .= "brand.name AS 'brand_name', ";
    	}

    	if ($client_id) {
    		$colluns .= "client.name AS 'client_name', ";
    	}

    	if ($sales_rep_id) {
    		$colluns .= "sales_rep.name AS 'sales_rep_name', ";
    	}

    	if ($opportunity_name) {
    		$colluns .= "rf.opportunity_name AS 'opportunity_name', ";
    	}

    	if ($phase) {
    		$colluns .= "rf.phase AS 'phase', ";
    	}

    	if ($cathegory) {
    		$colluns .= "rf.cathegory AS 'cathegory', ";
    	}

    	if ($prediction_date) {
    		$colluns .= "rf.prediction_date AS 'prediction_date', ";
    	}

    	if ($closing_date) {
    		$colluns .= "rf.closing_date AS 'closing_date', ";
    	}

    	if ($campaign_start) {
    		$colluns .= "rf.campaign_start AS 'campaign_start', ";
    	}

    	if ($campaign_end) {
    		$colluns .= "rf.campaign_end AS 'campaign_end', ";
    	}

    	if ($expected_amount) {
    		$colluns .= "rf.expected_amount AS 'expected_amount', ";
    	}

    	$colluns .= "rf.ID AS 'ID'";

    	return $colluns;
    }

    /*
	*Author: Bruno Gomes
	*Date:10/04/2019
	*Razon:Table modeler
	*/
    public function table(
    	$region,
    	$currency,
    	$brand,
    	$client,
    	$sales_rep
    )
    {
    	$table = "'DLA'.'rolling_forecast' AS rf";

    	if ($region) {
    		$table .= "LEFT JOIN 'DLA'.'region' AS region ON region.ID = rf.region_id";
    	}

    	if ($currency) {
    		$table .= "LEFT JOIN 'DLA'.'currency' AS currency ON currency.ID = rf.currency_id";
    	}

    	if ($brand) {
    		$table .= "LEFT JOIN 'DLA'.'brand' AS brand ON brand.ID AS rf.brand_id";
    	}

    	if ($client) {
    		$table .= "LEFT JOIN 'DLA'.'client' AS client ON client.ID = rf.client_id";
    	}

    	if ($sales_rep) {
    		$table .= "LEFT JOIN 'DLA'.'sales_rep' AS sales_rep ON sales_rep.ID = rf.sales_rep_id";
    	}

    	return $table;
    }

    /*
	*Author: Bruno Gomes
	*Date:10/04/2019
	*Razon:Where modeler
	*/
    public function where(
    	$region,
    	$phase,
    	$client,
    	$brand,
    	$sales_rep_id
    )
    {
    	$where = "";

    	if ($region) {
    		$region_ids = implode(",", $region) 
    		$where .= "region.ID IN ('.$region_ids.')";
    		if ($phase OR $client OR $brand) {
    			$where .= " AND ";
    		}
    	}

    	if ($phase) {
    		$where .= "rf.phase = $phase";
    		if ($sales_rep_id OR $client OR $brand) {
    			$where .= " AND ";
    		}
    	}

    	if ($client) {
    		$client_ids = implode(",", $client);
    		$where .= "rf.client_id IN ('.$client_ids.')";

    		if ($sales_rep_id OR $phase OR $brand) {
    			$where .= " AND ";
    		}
    	}

    	if ($brand) {
    		$brand_ids = implode(",", $brand);
    		$where .= "rf.brand_id IN ('.$brand_ids.')";

    		if ($sales_rep_id OR $client OR $phase) {
    			$where .= " AND ";
    		}
    	}

    	if ($sales_rep_id) {
    		$sales_rep_ids = implode(",", $sales_rep_id);
    		$where .= "rf.sales_rep_id IN ('.$sales_rep_ids.')";	
    	}



    	return $where;
    }

    /*
	*Author: Bruno Gomes
	*Date:10/04/2019
	*Razon:Order_by modeler
	*/
    public function order_by(
    	$client,
    	$phase

    )
    {
    	$order_by = "ORDER BY ";

    	if ($client) {
    		$order_by .= "client.name";

    		if ($phase) {
    			$order_by .= " , ";
    		}
    	}

    	if ($phase) {
    		$order_by .= "rf.phase";
    	}

    	$order_by .= " ASC";

    	return $order_by;
    }
}
