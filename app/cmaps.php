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

        $result = $sql->select($con, $columns, $table, $join, $where, $order_by);

        $from = array('id', 'salesRepGroup', 'salesRep', 'client', 'agency', 'brand', 'decode', 'year', 'month', 'mapNumber',
         'package', 'product', 'segment', 'piNumber', 'gross', 'net', 'market', 'discount', 'clientCNPJ', 'agencyCNPJ', 'mediaType',
          'log', 'adSalesSupport', 'obs', 'sector', 'category');

        $to = $from;

        $cmaps = $sql->fetch($result, $from, $to);

        return $cmaps;
    }

    public function getWithFilter($con, $value, $region, $currency, $where, $order_by = 1){
        
        $sql = new sql();

        $table = "cmaps cm";
        $columns = "cm.ID AS 'id',
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
                    cm.".$value." AS '".$value."',
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

        $join = "LEFT JOIN sales_rep sr ON sr.ID = cm.sales_rep_id
                 LEFT JOIN client cl ON cl.ID = cm.client_id
                 LEFT JOIN agency agc ON agc.ID = cm.agency_id
                 LEFT JOIN brand b ON b.ID = cm.brand_id
                 ";

        if (is_null($where)) {
             $where = "";
        }

        $result = $sql->select($con, $columns, $table, $join, $where, $order_by);

        $from = array('salesRep', 'client', 'agency', 'brand', 'decode', 'year', 'month', 'mapNumber',
         'package', 'product', 'segment', 'piNumber', $value, 'market', 'discount', 'clientCNPJ', 'agencyCNPJ', 'mediaType',
          'log', 'adSalesSupport', 'obs', 'sector', 'category');

        $to = $from;

        $cmaps = $sql->fetch($result, $from, $to);
        
        $p = new pRate();

        if ($currency[0]['name'] == 'USD') {
            $pRate = 1.0;
        }else{
            $pRate = $p->getPRateByRegionAndYear($con,array($region),array(intval(date('Y'))));
        }

        for ($c=0; $c < sizeof($cmaps); $c++) { 
            $cmaps[$c][$value] /= $pRate;
        }

        return $cmaps;

    }
 
}
