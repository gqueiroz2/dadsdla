<?php

namespace App;

use Illuminate\Support\Facades\Request;
use App\Management;
use App\sql;

class planByBrand extends Management{
    
    public function get($con, $region = false){
        
        $sql = new sql();

        $table = 'plan_by_brand pb';
        $columns = "pb.ID AS 'id',
                    r.name AS 'region',
                    c.name AS 'currency',
                    b.name AS 'brand',
                    pb.source AS 'source',
                    pb.year AS 'year',
                    pb.type_of_revenue AS 'typeOfRevenue',
                    pb.month AS 'month',
                    pb.revenue AS 'revenue'
                    ";
        $join = "LEFT JOIN region r ON r.ID = pb.sales_office_id
                 LEFT JOIN currency c ON c.ID = pb.currency_id
                 LEFT JOIN brand b ON B.id = pb.brand_id";

        $where = "";

        if ($region) {
            $ids = implode(",", $region);
            $where .= "WHERE region_id IN ('$ids')";
        }

        $result = $sql->select($con, $columns, $table, $join, $where);

        $from = array('id', 'region', 'currency', 'brand', 'source', 'year', 'typeOfRevenue', 'month', 'revenue');
        $to = $from;

        $planByBrand = $sql->fetch($result,$from,$to);

        return $planByBrand;
    }
}
