<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class brand extends Model{

    public function select($con, $columns, $table, $join, $where, $order_by = 1){    	

        $sql = "SELECT $columns FROM $table $join $where ORDER BY $order_by";    	
    	$res = $con->query($sql);
    	return $res;
    }
/*
    public function columns ( $brand, $brand_unit ){
    	
        $columns = "";

    	if($brand) {
    		$columns .= "brand.name AS 'brand_name'";
    	}

    	if ($brand_unit) {
    		$columns .= "brand_unit.name AS 'brand_unit.name'";
    	}

    	return $columns;
    }

    public function table($brand,$brand_unit){
    	if ($brand) {
    		$table = "brand brand ";
    		$table .= "LEFT JOIN brand_unit ON brand_unit.brand_id = brand.ID";
    	}

    	if ($brand_unit) {
    		$table = "brand_unit brand_unit";
    		$table .= "LEFT JOIN brand ON brand.ID = brand_unit.brand_id";
    	}

    	return $table;
    }

    public function where($brand, $brand_unit ){
    	$where = "";

    	if ($brand) {
    		$brand_ids = implode(",", $brand);
    		$where = "brand.ID IN ('$brand_ids')";
    	}

    	if ($brand_unit) {
    		$brand_unit_ids = implode(",", $brand_unit);
    		$where .= "brand_unit.ID IN ('$brand_unit_ids')";
    	}

    	return $where;
    }

    public function order_by($brand, $brand_unit, $order){
    	$order_by = "ORDER BY ";

    	if ($brand) {
    		$order_by .= "brand.name";
    		if ($brand_unit) {
    			$order_by .= " , ";
    		}
    	}

    	if ($brand_unit) {
    		$order_by .= "brand_unit.name";
    	}

    	//this parameters, pass it as true or false, for true the result will be ASC
    	if ($order == TRUE) {
    		$order_by .= " ASC";
    	}
    	else{
    		$order_by .= " DESC";
    	}

    	return $order_by;
    }
	
    public function getBrand($con){
    	$sql = "SELECT
    			    brand.ID AS 'ID',
    			    brand.name AS 'brand_name'
    		    FROM 'brand' AS brand";

    	$res = $con->query($sql);

    	return $res;
    }
	
	public function getBrandUnit($con,$brand_id){            
        $where = ""; // WHERE brand_unit.brand_id in ('.$brand_id.')

		$sql = "SELECT
				    brand_unit.ID AS id,
				    brand_unit.name AS 'brandUnitName',
				    origin.name AS 'originName'
				
			    FROM brand_unit AS brand_unit
				    LEFT JOIN 'brand' AS brand ON brand.ID = brand_unit.brand_id
				    LEFT JOIN 'origin' AS origin ON origin.ID = brand_unit.origin_id
                $where";

		$res = $con->query($sql);

    	return $res;
	}

*/
}
