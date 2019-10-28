<?php

namespace App;

use Illuminate\Support\Facades\Request;
use App\Management;
use App\sql;
use App\pRate;

class planBySales extends Management{
    
    public function getWithFilter($con, $where, $currency, $region, $months, $order_by = 1){
        
        $sql = new sql();

        $table = 'plan_by_sales pbs';

        $columns = "pbs.ID AS 'id',
                    r.name AS 'region',
                    sr.name AS 'sales_rep',
                    c.name AS 'currency',
                    b.name AS 'brand',
                    pbs.year AS 'year',
                    pbs.type_of_revenue AS 'type_of_Revenue',
                    pbs.month AS 'month',
                    pbs.value AS 'revenue'
                    ";

        $join = "LEFT JOIN region r ON r.ID = pbs.region_id
                 LEFT JOIN currency c ON c.ID = pbs.currency_id
                 LEFT JOIN brand b ON b.id = pbs.brand_id
                 LEFT JOIN sales_rep sr ON sr.id = pbs.sales_rep_id";

        $order_by = "year DESC";

        $result = $sql->select($con, $columns, $table, $join, $where, $order_by);

        $from = array('region', 'year', 'month', 'brand', 'sales_rep', 'revenue');

        $to = $from;

        $planBySales = $sql->fetch($result,$from,$to);

        if (is_array($planBySales)) {
            $p = new pRate();

            if ($currency[0]['name'] == 'USD') {
                $pRate = 1.0;
            }else{
                $pRate = $p->getPRateByRegionAndYear($con,array($region),array(date('Y')));
            }
            
            for ($p=0; $p < sizeof($planBySales); $p++) {
                $planBySales[$p]['month'] = $months[$planBySales[$p]['month']-1][2];
                $planBySales[$p]['revenue'] *= $pRate;
            }   
        }

        return $planBySales;
    }
}
