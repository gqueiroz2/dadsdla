<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/*
*Author: Bruno Gomes
*Date:11/04/2019
*Razon:Sap Digital modeler, which you can pass as parameters: colluns, tables, where and order_by. Be aware to matching the colluns and tables names to be used.
*/
class digital extends Model
{
    /*
	*Author: Bruno Gomes
	*Date:11/04/2019
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
	*Date:11/04/2019
	*Razon:Colluns modeler
	*/

	public function colluns(
		$campaign_sales_office_id,
		$sales_rep_is,
		$agency_id,
		$client_id,
		$currency_id,
		$mouth,
		$year,
		$gross_revenue,
		$net_revenue,
		$agency_comission
	)
	{
		$colluns = "";

		if ($campaign_sales_office_id) {
			$colluns .= "region.name AS 'regionName', ";
		}

		if ($sales_rep_is) {
			$colluns .= "sales_rep.name AS 'salesRepName', ";
		}

		if ($agency_id) {
			$colluns .= "agency.name AS 'agencyName', ";
		}

		if ($client_id) {
			$colluns .= "client.name AS 'clientName', ";
		}

		if ($mouth) {
			$colluns .= "digital.mouth AS 'month', ";
		}

		if ($year) {
			$colluns .= "digital.year AS 'year', ";
		}

		if ($gross_revenue) {
			$colluns .= "digital.gross_revenue AS 'grossRevenue',";
		}

		if ($net_revenue) {
			$colluns .= "digital.net_revenue AS 'netRevenue', ";
		}

		if ($agency_comission) {
			$colluns .= "digital.agency_comission AS 'agencyComission', ";
		}

		$colluns .= "digital.ID AS id";
	}

    /*
	*Author: Bruno Gomes
	*Date:11/04/2019
	*Razon:Table modeler
	*/
	public function table(
		$region,
		$sales_rep,
		$client,
		$agency,
		$currency
	){
		$table = "'digital' AS digital";

		if ($region) {
			$table .="LEFT JOIN 'region' ON region.ID = digital.campaign_sales_office_id";
		}

		if($sales_rep){
			$table .= "LEFT JOIN 'sales_rep' ON sales_rep.ID = digital.sales_rep_id";
		}

		if ($client) {
			$table .= "LEFT JOIN 'client' ON client.ID = digital.client_id";
		}

		if ($agency) {
			$table .= "LEFT JOIN 'agency' ON agency.ID = digital.agency_id";
		}

		if ($currency) {
			$table .= "LEFT JOIN 'currency' ON currency.ID = digital.currency_id";
		}

		return $table;
	}

    /*
	*Author: Bruno Gomes
	*Date:11/04/2019
	*Razon:Where modeler
	*/
	public function where(
		$agency,
		$client,
		$month,
		$year,
		$sales_rep
	)
	{
		$where = "";

		if ($agency) {
			$agency_ids = implode(",", $agency)
			$where .= "agency.ID IN ('$agency_ids')";
			if ($year OR $client) {
    			$where .= " AND ";
    		}
		}

		if ($client) {
			$client_ids = implode(",", $client)
			$where .= "client.ID IN ('$client_ids')";
			if ($year OR $agency OR $month) {
    			$where .= " AND ";
    		}
		}

		if ($month) {			
			$where .= "digital.month = '.$month.'";
			if ($year OR $client OR $agency) {
    			$where .= " AND ";
    		}
		}

		if ($year) {
			$where .= "digital.year = '.$year.'";
			if ($month OR $brand OR $agency) {
    			$where .= " AND ";
    		}
		}

		if ($sales_rep) {
			$sales_rep_ids = implode(",", $sales_rep);
			$where .= "sales_rep.ID in ('$sales_rep_ids')";
		}

		return $where;
	}

    /*
	*Author: Bruno Gomes
	*Date:11/04/2019
	*Razon:Order_by modeler
	*/
	public function order_by(
		$agency,
		$client,
		$order
	)
	{
		$order_by = "ORDER_BY";

		if ($agency) {
			$order_by .= "agency.name";
			if ($client) {
				$order_by .= " , ";
			}
		}

		if ($client) {
			$order_by .= "client.name";
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
}
