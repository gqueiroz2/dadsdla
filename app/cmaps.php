<?php

namespace App;

use Illuminate\Support\Facades\Request;
use App\Management;
use App\sql;

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

    public function sum($con, $value, $columnsName, $columnsValue){
        
        $sql = new sql();

        $table = "cmaps";

        $sum = "$value";

        $as = "sum";

        $where = $sql->where($columnsName, $columnsValue);

        $result = $sql->selectSum($con, $sum, $as, $table, null, $where);

        $res = $sql->fetchSum($result, $as);

        return $res;
    }
 
}
