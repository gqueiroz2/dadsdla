<?php

namespace App;

use Illuminate\Support\Facades\Request;
use App\Management;
use App\sql;

class header extends Management
{
    public function get($con, $colNames = null, $values = null, $order_by = 1){       
    
        $sql = new sql();

        $table = 'header h';

        $columns = "h.ID AS 'id',
                    r.name AS 'campaignRegion',
                    r.name AS 'Salesregion',
                    b.name AS 'brand',
                    sr.name AS 'salesRep',
                    cl.name AS 'client',
                    agc.name AS 'agency',
                    c.name AS 'campaignCurrency',
                    h.year AS 'year',
                    h.month AS 'month',
                    h.brand_feed AS 'brandFeed',
                    h.sales_rep_role AS 'salesRepRole',
                    h.client_product AS 'clientProduct',
                    h.order_reference AS 'orderReference',
                    h.campaign_reference AS 'campaignReference',
                    h.spot_duration AS 'spotDuration',
                    h.campaign_status_id AS 'campaignStatus',
                    h.campaign_option_desc AS 'campaignOptionDesc',
                    h.campaign_class_id AS 'camapignClass',
                    h.campaign_option_start_date AS 'campaignOptionStartDate',
                    h.campaign_option_target_spot AS 'campaignOptionTargetSpot',
                    h.campaign_option_spend AS 'campaignOptionSpend',
                    h.impression_duration AS 'impressionDuration',
                    h.num_spot_impressions AS 'numSpotImpressions',
                    h.gross_revenue AS 'grossRevenue',
                    h.net_revenue AS 'netRevenue',
                    h.net_net_revenue AS 'netNetRevenue',
                    h.gross_revenue_prate AS 'grossRevenuePrate',
                    h.net_revenue_prate AS 'netRevenuePrate',
                    h.net_net_revenue_prate AS 'netNetRevenuePrate'";

        $join = "LEFT JOIN region r ON r.ID = h.campaign_sales_office_id
                 LEFT JOIN region r ON r.ID = h.sales_rep_sales_office_id
                 LEFT JOIN brand b ON b.ID = h.brand_id
                 LEFT JOIN sales_rep sr ON sr.ID = h.sales_rep_id
                 LEFT JOIN client cl ON cl.ID = h.client_id
                 LEFT JOIN agency agc ON agc.ID = h.agency_id
                 LEFT JOIN currency c ON c.ID = h.campaign_currency
                 ";

        $where = "";
        if ($values) {
            $where = $sql->where($colNames, $values);
        }

        $result = $sql->select($con, $columns, $table, $join, $where, $order_by);

        $from = array('id', 'campaignRegion', 'Salesregion', 'brand', 'salesRep', 'client', 'agency', 'campaignCurrency',
                      'year', 'month', 'brandFeed', 'salesRepRole', 'clientProduct', 'orderReference', 'campaignReference',
                      'spotDuration', 'campaignStatus', 'campaignOptionDesc', 'camapignClass', 'campaignOptionStartDate',
                      'campaignOptionTargetSpot', 'campaignOptionSpend', 'impressionDuration', 'numSpotImpressions',
                      'grossRevenue', 'netRevenue', 'netNetRevenue', 'grossRevenuePrate', 'netRevenuePrate', 
                      'netNetRevenuePrate');

        $to = $from;

        $header = $sql->fetch($result, $from, $to);

        return $header;
    }

    public function sum($con, $value, $columnsName, $columnsValue, $region, $year){
        
        $sql = new sql();

        $table = "header";

        $sum = "$value";

        $as = "sum";

        $where = $sql->where($columnsName, $columnsValue);

        $result = $sql->selectSum($con, $sum, $as, $table, null, $where);

        $res = $sql->fetchSum($result, $sum);
    }
}
