<?php

namespace App;

use Illuminate\Support\Facades\Request;
use App\Management;
use App\sql;
use App\pRate;

class planByBrand extends Management{
    
    public function get($con, $where = null, $order_by = 1){
        
        $sql = new sql();

        $table = 'plan_by_brand pbb';

        $columns = "pbb.ID AS 'id',
                    r.name AS 'region',
                    c.name AS 'currency',
                    b.name AS 'brand',
                    pbb.source AS 'source',
                    pbb.year AS 'year',
                    pbb.type_of_revenue AS 'typeOfRevenue',
                    pbb.month AS 'month',
                    pbb.revenue AS 'revenue'
                    ";

        $join = "LEFT JOIN region r ON r.ID = pbb.sales_office_id
                 LEFT JOIN currency c ON c.ID = pbb.currency_id
                 LEFT JOIN brand b ON b.id = pbb.brand_id";

        

        if (is_null($where)) {
            $where = "";
        }

        $result = $sql->select($con, $columns, $table, $join, $where, $order_by);

        $from = array('id', 'region', 'currency', 'brand', 'source', 'year', 'typeOfRevenue', 'month', 'revenue');
        $to = $from;

        $planByBrand = $sql->fetch($result,$from,$to);

        return $planByBrand;
    }

    public function getWithFilter($con, $where, $currency, $region, $order_by = 1){
        
        $sql = new sql();

        $table = 'plan_by_brand pbb';

        $columns = "pbb.ID AS 'id',
                    r.name AS 'region',
                    c.name AS 'currency',
                    b.name AS 'brand',
                    pbb.source AS 'source',
                    pbb.year AS 'year',
                    pbb.type_of_revenue AS 'typeOfRevenue',
                    pbb.month AS 'month',
                    pbb.revenue AS 'revenue'
                    ";

        $join = "LEFT JOIN region r ON r.ID = pbb.sales_office_id
                 LEFT JOIN currency c ON c.ID = pbb.currency_id
                 LEFT JOIN brand b ON b.id = pbb.brand_id";

        $result = $sql->select($con, $columns, $table, $join, $where, $order_by);

        $from = array('id', 'region', 'currency', 'brand', 'source', 'year', 'typeOfRevenue', 'month', 'revenue');
        $to = $from;

        $planByBrand = $sql->fetch($result,$from,$to);

        $p = new pRate();

        if ($currency[0]['name'] == 'USD') {
            $pRate = 1.0;
        }else{
            $pRate = $p->getPRateByRegionAndYear($con,array($region),array(intval(date('Y'))));
        }
        
        for ($p=0; $p < sizeof($planByBrand); $p++) { 
            $planByBrand[$p]['revenue'] *= $pRate;
        }

        return $planByBrand;
    }

}
