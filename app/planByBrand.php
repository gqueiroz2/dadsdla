<?php

namespace App;

use Illuminate\Support\Facades\Request;
use App\Management;
use App\sql;

class planByBrand extends Management{
    
    public function get($con, $colNames = null, $values = null, $order_by = 1){
        
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

        $where = "";

        if ($values) {
            $where = $sql->where($colNames, $values);
        }

        $result = $sql->select($con, $columns, $table, $join, $where, $order_by);

        $from = array('id', 'region', 'currency', 'brand', 'source', 'year', 'typeOfRevenue', 'month', 'revenue');
        $to = $from;

        $planByBrand = $sql->fetch($result,$from,$to);

        return $planByBrand;
    }

    public function sum($con, $value, $columnsName, $columnsValue){
        
        $sql = new sql();

        $table = "plan_by_brand";

        $sum = "$value";

        $as = "sum";

        $where = $sql->where($columnsName, $columnsValue);
        $result = $sql->selectSum($con, $sum, $as, $table, null, $where);

        $res = $sql->fetchSum($result, $as);

        return $res;
    }
}
