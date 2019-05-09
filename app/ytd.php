<?php

namespace App;

use Illuminate\Support\Facades\Request;
use App\Management;
use App\sql;

class ytd extends Management{
    /*
        TABLE THAT REFERENCES
        @ytd_{{CURRENT YEAR}}
        example ytd_2019
    */

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

        $order_by = 10;

        $result = $sql->select($con, $columns, $table, $join, $where, $order_by);

        $from = array('id', 'campaignRegion', 'Salesregion', 'brand', 'salesRep', 'client', 'agency', 'campaignCurrency',
                      'year', 'month', 'brandFeed', 'clientProduct', 'orderReference', 'campaignReference', 'spotDuration',
                      'impressionDuration', 'numSpotImpressions', 'grossRevenue', 'netRevenue', 'netNetRevenue', 
                      'grossRevenuePrate', 'netRevenuePrate', 'netNetRevenuePrate');


        $to = $from;

        $ytd = $sql->fetch($result, $from, $to);

        return $ytd;
    }

    public function sum($con, $value, $columnsName, $columnsValue){
        
        $sql = new sql();
        $table = "ytd";
        $sum = "$value";
        //var_dump($sum);

        $as = "sum";
        $where = $sql->where($columnsName, $columnsValue);
        //var_dump($where);
        $result = $sql->selectSum($con, $sum, $as, $table, null, $where);
        $res = $sql->fetchSum($result, $as);
        return $res;
    }
    
}
