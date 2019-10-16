<?php

namespace App;

use Illuminate\Support\Facades\Request;
use App\Management;
use App\sql;
use App\pRate;

class ytd extends Management{

    public function get($con, $colNames = null, $values = null){

        $sql = new sql();

        $table = "ytd ytd";

        $columns = "ytd.ID AS 'id',
                    r.name AS 'campaignRegion',
                    r.name AS 'Salesregion',
                    b.name AS 'brand',
                    sr.name AS 'salesRep',
                    cl.name AS 'client',
                    agc.name AS 'agency',
                    c.name AS 'campaignCurrency',
                    ytd.year AS 'year',
                    ytd.month AS 'month',
                    ytd.brand_feed AS 'brandFeed',
                    ytd.client_product AS 'clientProduct',
                    ytd.order_reference AS 'orderReference',
                    ytd.campaign_reference AS 'campaignReference',
                    ytd.spot_duration AS 'spotDuration',
                    ytd.impression_duration AS 'impressionDuration',
                    ytd.num_spot_impressions AS 'numSpotImpressions',
                    ytd.gross_revenue AS 'grossRevenue',
                    ytd.net_revenue AS 'netRevenue',
                    ytd.net_net_revenue AS 'netNetRevenue',
                    ytd.gross_revenue_prate AS 'grossRevenuePrate',
                    ytd.net_revenue_prate AS 'netRevenuePrate',
                    ytd.net_net_revenue_prate AS 'netNetRevenuePrate'";

        $join = "LEFT JOIN region r ON r.ID = ytd.campaign_sales_office_id
                 LEFT JOIN region r ON r.ID = ytd.sales_rep_sales_office_id
                 LEFT JOIN brand b ON b.ID = ytd.brand_id
                 LEFT JOIN sales_rep sr ON sr.ID = ytd.sales_rep_id
                 LEFT JOIN client cl ON cl.ID = ytd.client_id
                 LEFT JOIN agency agc ON agc.ID = ytd.agency_id
                 LEFT JOIN currency c ON c.ID = ytd.campaign_currency";

        $where = "";
        if ($values) {
            $where = $sql->where($colNames, $values);
        }

        $order_by = "year DESC";

        $result = $sql->select($con, $columns, $table, $join, $where, $order_by);

        $from = array('id', 'campaignRegion', 'Salesregion', 'brand', 'salesRep', 'client', 'agency', 'campaignCurrency',
                      'year', 'month', 'brandFeed', 'clientProduct', 'orderReference', 'campaignReference', 'spotDuration',
                      'impressionDuration', 'numSpotImpressions', 'grossRevenue', 'netRevenue', 'netNetRevenue', 
                      'grossRevenuePrate', 'netRevenuePrate', 'netNetRevenuePrate');

        $to = $from;

        $ytd = $sql->fetch($result, $from, $to);

        return $ytd;
    }

    public function getWithFilter($con, $value, $currency, $region, $where, $months, $order_by = 1){
        
        $sql = new sql();

        $table = "ytd ytd";

        $columns = "ytd.ID AS 'id',
                    r.name AS 'campaign_sales_office',
                    r2.name AS 'sales_representant_office',
                    b.name AS 'brand',
                    sr.name AS 'sales_rep',
                    cl.name AS 'client',
                    agc.name AS 'agency',
                    ytd.year AS 'year',
                    ytd.month AS 'month',
                    ytd.brand_feed AS 'brand_feed',
                    ytd.client_product AS 'client_product',
                    ytd.order_reference AS 'order_reference',
                    ytd.campaign_reference AS 'campaign_reference',
                    ytd.spot_duration AS 'spot_duration',
                    ytd.impression_duration AS 'impression_duration',
                    ytd.num_spot AS 'num_spot',
                    ytd.".$value."_revenue_prate AS 'revenue'";

        $join = "LEFT JOIN region r ON r.ID = ytd.campaign_sales_office_id
                 LEFT JOIN region r2 ON r2.ID = ytd.sales_representant_office_id
                 LEFT JOIN brand b ON b.ID = ytd.brand_id
                 LEFT JOIN sales_rep sr ON sr.ID = ytd.sales_rep_id
                 LEFT JOIN client cl ON cl.ID = ytd.client_id
                 LEFT JOIN agency agc ON agc.ID = ytd.agency_id";

        if (is_null($where)) {
            $where = "";
        }

        $order_by = "year DESC";

        $result = $sql->select($con, $columns, $table, $join, $where, $order_by);

        $from = array('campaign_sales_office', 'sales_representant_office', 'year', 'month', 'brand', 'brand_feed', 'sales_rep', 'client', 'client_product', 'agency', 'order_reference', 'campaign_reference', 'spot_duration', 'impression_duration', 'num_spot', 'revenue');

        $to = $from;

        $ytd = $sql->fetch($result, $from, $to);

        if (is_array($ytd)) {
            $p = new pRate();

            if ($currency[0]['name'] == 'USD') {
                $pRate = 1.0;
            }else{
                $pRate = $p->getPRateByRegionAndYear($con,array($region),array(intval(date('Y'))));
            }

            for ($y=0; $y < sizeof($ytd); $y++) {
                $ytd[$y]['month'] = $months[$ytd[$y]['month']-1][2];
                $ytd[$y]['revenue'] *= $pRate;
            }
        }
        
        return $ytd;

    }
    
}
