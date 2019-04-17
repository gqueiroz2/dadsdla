<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/*
*Author: Bruno Gomes
*Date:12/04/2019
*Razon:Header modeler ,which you can pass as parameters: colluns, tables, where and order_by. Be aware to matching the colluns and tables names to be used.
*/
class header extends Model
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
    public function colluns(
    	$campaign_sales_office_id,
    	$sales_rep_sales_office_id,
    	$brand_id,
    	$sales_rep_id,
    	$client_id,
    	$agency_id,
    	$campaign_currency_id,
    	$year,
    	$month,
    	$brand_feed,
    	$sales_rep_role,
    	$client_product,
    	$order_reference,
    	$campaign_reference,
    	$spot_duration,
    	$campaign_status_id,
    	$campaign_option_desc,
    	$campaign_class_id,
    	$campaign_option_start_date,
    	$campaign_option_target_spot,
    	$campaign_option_spend,
    	$impression_duration,
    	$num_spot_impressions,
    	$gross_revenue,
    	$net_revenue,
    	$net_net_revenue,
    	$gross_revenue_prate,
    	$net_revenue_prate,
    	$net_net_revenue_prate

    )
    {
        $colluns = "";

        if ($campaign_sales_office_id) {
            $colluns .= "region.name AS 'campaignSalesOffice', ";
        }

        if ($sales_rep_sales_office_id) {
            $colluns .= "region.name AS 'sales_repSalesOffice', ";
        }

        if ($brand_id) {
            $colluns .= "brand.name AS 'brand', ";
        }

        if ($sales_rep_id) {
            $colluns .= "sales_rep.name AS 'salesRep',";
        }

        if ($client_id) {
            $colluns .= "client.name AS 'client' ,";
        }

        if ($agency_id) {
            $colluns .= "agency.name AS 'agency', ";
        }

        if ($campaign_currency_id) {
            $colluns .= "currency.name AS 'currency', ";
        }

        if ($year) {
            $colluns .= "header.year AS 'year', "
        }

        if ($month) {
            $colluns .= "header.month AS 'month', ";
        }

        if ($brand_feed) {
            $colluns .= "header.brand_feed AS 'brandFeed', ";
        }

        if ($sales_rep_role) {
            $colluns .= "header.sales_rep_role AS 'salesRepRole', ";
        }

        if ($client_product) {
            $colluns .= "header.client_product AS 'clientProduct', ";
        }

        if ($order_reference) {
            $colluns .= "header.order_reference AS 'orderReference', ";
        }

        if ($campaign_reference) {
            $colluns .= "header.campaign_reference AS 'campaignReference', ";
        }

        if ($spot_duration) {
            $colluns .= "header.spot_duration AS 'spotDuration', ";
        }

        if ($campaign_status_id) {
            $colluns .= "header.campaign_status_id AS 'campaignStatusId', ";
        }

        if ($campaign_option_desc) {
            $colluns .= "header.campaign_option_desc AS 'campaignOptionDesc', ";
        }

        if ($campaign_class_id) {
            $colluns .= "header.campaign_class_id AS 'campaignClassId', ";
        }

        if ($campaign_option_start_date) {
            $colluns .= "header.campaign_option_start_date AS 'campaignOptionStartDate', ";
        }

        if ($campaign_option_target_spot) {
            $colluns .= "header.campaign_option_target_spot AS 'campaignOptionTargetSpot', ";
        }

        if ($campaign_option_spend) {
            $colluns .= "header.campaign_option_spend AS 'campaignOptionSpend', ";
        }

        if ($impression_duration) {
            $colluns .= "header.impression_duration AS 'impressionDuration', ";
        }

        if ($num_spot_impressions) {
            $colluns .= "header.num_spot_impressions AS 'numSpotImpressions', ";
        }

        if ($gross_revenue) {
            $colluns .= "header.gross_revenue AS 'grossRevenue', ";
        }

        if ($net_revenue) {
            $colluns .= "header.net_revenue AS 'netRevenue', ";
        }

        if ($net_net_revenue) {
            $colluns .= "header.net_net_revenue AS 'netNetRevenue', ";
        }

        if ($gross_revenue_prate) {
            $colluns .= "header.gross_revenue_prate AS 'grossRevenuePrate', ";
        }

        if ($net_revenue_prate) {
            $colluns .= "header.net_revenue_prate AS 'netRevenuePrate', ";
        }

        if ($net_net_revenue_prate) {
            $colluns .= "header.net_net_revenue_prate AS 'netNetRevenuePrate', ";
        }

        $colluns .= "header.ID AS 'id'";

        return $colluns;
    }

    /*
	*Author: Bruno Gomes
	*Date:12/04/2019
	*Razon:Table modeler
	*/
    public function table (
    	$client,
    	$sales_rep,
    	$brand,
    	$agency,
    	$currency,
    	$region
    )
    {
        $table = "'header' AS header";

        if ($client) {
            $table .= "LEFT JOIN 'client'  ON client.ID = header.client_id";
        }

        if ($sales_rep) {
            $table .= "LEFT JOIN 'sales_rep'  ON sales_rep.ID = header.sales_rep_id";
        }

        if ($brand) {
            $table .= "LEFT JOIN 'brand' ON brand.ID = header.brand_id";
        }

        if ($agency) {
            $table .= "LEFT JOIN 'agency'  ON agency.ID = header.agency_id";
        }

        if ($currency) {
            $table .= "LEFT JOIN 'currency'  ON currency.ID = header.campaign_currency_id";
        }

        if ($region) {
            $table .= "LEFT JOIN 'region'  ON region.ID = header.campaign_sales_office_id";
        }

        return $table;
    }

    /*
	*Author: Bruno Gomes
	*Date:12/04/2019
	*Razon:Where modeler
	*/
    public function where (
    	$month,
    	$year,
    	$brand,
    	$sales_rep,
    	$client,
    	$agency
    )
    {
        $where = "WHERE ";

        if ($month) {
            $where .= "header.month = $month";
            if ($year OR $brand OR $client OR $agency) {
                $where .= " AND ";
            }
        }

        if ($year) {
            $where .= "header.year = $year";
            if ($month OR $brand OR $client OR $agency) {
                $where .= " AND ";
            }
        }

        if ($brand) {
            $where .= "header.brand = $brand";
            if ($year OR $month OR $client OR $agency) {
                $where .= " AND ";
            }
        }

        if ($sales_rep) {
            $sales_rep_ids = implode(",", $sales_rep);
            $where .= "sales_rep.ID IN ('.$sales_rep_ids.')";
            if ($year OR $brand OR $client OR $agency) {
                $where .= " AND ";
            }
        }

        if ($client) {
            $client_ids = implode("", $client);
            $where .= "client.ID IN ('.$client_ids.')";
            if ($year OR $brand OR $month OR $agency) {
                $where .= " AND ";
            }
        }

        if ($agency) {
            $agency_ids = implode(",", $agency);
            $where .= "agency.ID IN ('.$agency_ids.')";
        }

        return $where;
    }

    /*
	*Author: Bruno Gomes
	*Date:12/04/2019
	*Razon:Order_by modeler
	*/
    public function order_by (
    	$sales_rep,
        $brand,
        $client,
        $agency,
        $month,
        $year,
        $order
    )
    {
        $order_by = "ORDER BY ";

        if ($month) {
            $order_by .= "header.month";
            if($year OR $sales_rep OR $brand OR $client OR $agency){
                $order_by .= " , ";
            }
        }

        if ($year) {
            $order_by .= "header.year";
            if($month OR $sales_rep OR $brand OR $client OR $agency){
                $order_by .= " , ";
            }   
        }

        if ($sales_rep) {
            $order_by .= "sales_rep.name";
            if($year OR $month OR $brand OR $client OR $agency){
                $order_by .= " , ";
            }
        }

        if ($brand) {
            $order_by .= "brand.name";
            if($year OR $sales_rep OR $month OR $client OR $agency){
                $order_by .= " , ";
            }
        }

        if ($client) {
            $order_by .= "client.name";
            if($year OR $sales_rep OR $brand OR $month OR $agency){
                $order_by .= " , ";
            }
        }

        if ($agency) {
            $order_by .= "agency.name";
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
