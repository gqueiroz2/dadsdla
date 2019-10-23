<?php

namespace App;

use Illuminate\Support\Facades\Request;
use App\Management;
use App\sql;
use App\pRate;

class cmaps extends Management{

    public function get($con, $colNames = null, $values = null, $order_by = 1){
        
        $sql = new sql();

        $table = "cmaps cm";
        
        $columns = "cm.ID AS 'id',
                    srg.name AS 'salesRepGroup',
                    sr.name AS 'salesRep',
                    cl.name AS 'client',
                    agc.name AS 'agency',
                    b.name AS 'brand',
                    cm.decode AS 'decode',
                    cm.year AS 'year',
                    cm.month AS 'month',
                    cm.map_number AS 'mapNumber',
                    cm.package AS 'package',
                    cm.product AS 'product',
                    cm.segment AS 'segment',
                    cm.pi_number AS 'piNumber',
                    cm.gross AS 'gross',
                    cm.net AS 'net',
                    cm.market AS 'market',
                    cm.discount AS 'discount',
                    cm.client_cnpj AS 'clientCNPJ',
                    cm.agency_cnpj AS 'agencyCNPJ',
                    cm.media_type AS 'mediaType',
                    cm.log AS 'log',
                    cm.ad_sales_support AS 'adSalesSupport',
                    cm.obs AS 'obs',
                    cm.sector AS 'sector',
                    cm.category AS 'category'
                    ";

        $join = "LEFT JOIN sales_rep_group srg ON srg.ID = cm.sales_group_id
                 LEFT JOIN sales_rep sr ON sr.ID = cm.sales_rep_id
                 LEFT JOIN client cl ON cl.ID = cm.client_id
                 LEFT JOIN agency agc ON agc.ID = cm.agency_id
                 LEFT JOIN brand b ON b.ID = cm.brand_id
                 ";

        $where = "";
        if ($values) {
            $where = $sql->where($colNames, $values);
        }

        $order_by = "year DESC";

        $result = $sql->select($con, $columns, $table, $join, $where, $order_by);

        $from = array('id', 'salesRepGroup', 'salesRep', 'client', 'agency', 'brand', 'decode', 'year', 'month', 'mapNumber',
         'package', 'product', 'segment', 'piNumber', 'gross', 'net', 'market', 'discount', 'clientCNPJ', 'agencyCNPJ', 'mediaType',
          'log', 'adSalesSupport', 'obs', 'sector', 'category');

        $to = $from;

        $cmaps = $sql->fetch($result, $from, $to);

        return $cmaps;
    }

    public function getWithFilter($con, $value, $region, $currency, $where, $months, $order_by = 1){
        
        $sql = new sql();

        $table = "cmaps cm";
        $columns = "cm.ID AS 'id',
                    sr.name AS 'sales_rep',
                    cl.name AS 'client',
                    agc.name AS 'agency',
                    b.name AS 'brand',
                    cm.decode AS 'decode',
                    cm.year AS 'year',
                    cm.month AS 'month',
                    cm.map_number AS 'map_number',
                    cm.package AS 'package',
                    cm.product AS 'product',
                    cm.segment AS 'segment',
                    cm.pi_number AS 'pi_number',
                    cm.".$value." AS 'revenue',
                    cm.market AS 'market',
                    cm.discount AS 'discount',
                    cm.client_cnpj AS 'client_CNPJ',
                    cm.agency_cnpj AS 'agency_CNPJ',
                    cm.media_type AS 'media_type',
                    cm.log AS 'log',
                    cm.ad_sales_support AS 'ad_Sales_Support',
                    cm.obs AS 'obs',
                    cm.sector AS 'sector',
                    cm.category AS 'category'
                    ";

        $join = "LEFT JOIN sales_rep sr ON sr.ID = cm.sales_rep_id
                 LEFT JOIN client cl ON cl.ID = cm.client_id
                 LEFT JOIN agency agc ON agc.ID = cm.agency_id
                 LEFT JOIN brand b ON b.ID = cm.brand_id
                 ";

        if (is_null($where)) {
             $where = "";
        }

        $order_by = "year DESC";

        $result = $sql->select($con, $columns, $table, $join, $where, $order_by);

        $from = array('decode', 'year', 'month', 'map_number', 'sales_rep', 'package', 'client', 'product', 'segment', 'agency', 'brand', 'pi_number', 'revenue', 'market', 'discount', 'client_CNPJ', 'agency_CNPJ', 'media_type', 'log', 'ad_Sales_Support', 'obs', 'sector', 'category');

        $to = $from;

        $cmaps = $sql->fetch($result, $from, $to);
        
        if (is_array($cmaps)) {
            $p = new pRate();

            if ($currency[0]['name'] == 'USD') {
                $pRate = $p->getPRateByRegionAndYear($con,array($region),array(date('Y')));
            }else{
                $pRate = 1.0;
            }

            for ($c=0; $c < sizeof($cmaps); $c++) { 
                $cmaps[$c]['month'] = $months[$cmaps[$c]['month']-1][2];
                $cmaps[$c]['discount'] /= 100;
                $cmaps[$c]['revenue'] /= $pRate;
            }   
        }

        return $cmaps;

    }

}
