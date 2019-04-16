<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/*
*Author: Bruno Gomes
*Date:10/04/2019
*Razon:Brand modeler
*/
class brand extends Model
{

	/*
	*Author: Bruno Gomes
	*Date:15/04/2019
	*Razon:Query modeler
	*/
    public function query($con, $colluns, $tabels, $where, $order_by = 1)
    {    	
    	$sql = "SELECT $colluns FROM $tabels WHERE $where $order_by ;";    	

    	$res = $con->query($sql);

    	return $res;
    }

    /*
	*Author: Bruno Gomes
	*Date:15/04/2019
	*Razon:Colluns modeler
	*/
    public function colluns (
    	$brand,
    	$brand_unit
    )
    {
    	$colluns = "";

    	if ($brand) {
    		$colluns .= "brand.name AS 'brand_name',";
    	}

    	if ($brand_unit) {
    		$colluns .= "brand_unit.name AS 'brand_unit.name',";
    	}

    	return $colluns;
    }

    /*
	*Author: Bruno Gomes
	*Date:15/04/2019
	*Razon:Table modeler
	*/
    public function table (
    	$brand,
    	$brand_unit
    )
    {
    	if ($brand) {
    		$table = "'brand' brand ";
    		$table .= "LEFT JOIN brand_unit AS brand_unit ON brand_unit.brand_id = brand.ID";
    	}

    	if ($brand_unit) {
    		$table = "'brand_unit' brand_unit";
    		$table .= "LEFT JOIN brand AS brand ON brand.ID = brand_unit.brand_id";
    	}

    	return $table;
    }

    /*
	*Author: Bruno Gomes
	*Date:15/04/2019
	*Razon:Where modeler
	*/
    public function where (
    	$brand,
    	$brand_unit
    )
    {
    	$where = "";

    	if ($brand) {
    		$brand_ids = implode(",", $brand);
    		$where = "brand.ID IN ('.$brand_ids.')";
    	}

    	if ($brand_unit) {
    		$brand_unit_ids = implode(",", $brand_unit);
    		$where .= "brand_unit.ID IN ('.$brand_unit_ids.'')";
    	}

    	return $where;
    }

    /*
	*Author: Bruno Gomes
	*Date:15/04/2019
	*Razon:Order_by modeler
	*/
    public function order_by (
    	$brand,
    	$brand_unit,
    	$order
    )
    {
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

	/*
	*Author: Bruno Gomes
	*Date:10/04/2019
	*Razon:Brand modeler
	*/
    public function getBrand(
    	$con
    )
    {
    	$sql = "
    		SELECT
    			brand.ID AS 'ID',
    			brand.name AS 'brand_name'
    		FROM 'brand' AS brand
    		ORDER BY brand.ID ASC
    	";

    	$res = $con->query($sql);

    	return $res;
    }

	/*
	*Author: Bruno Gomes
	*Date:10/04/2019
	*Razon:Brand modeler
	*/
	public function getBrandUnit(
		$con,
		$brand_id
	)
	{
		$sql = "
			SELECT
				brand_unit.ID AS id,
				brand_unit.name AS 'brandUnitName',
				origin.name AS 'originName'
				
			FROM brand_unit AS brand_unit
				LEFT JOIN 'brand' AS brand ON brand.ID = brand_unit.brand_id
				LEFT JOIN 'origin' AS origin ON origin.ID = brand_unit.origin_id
			WHERE brand_unit.brand_id in ('.$brand_id.')
		";

		$res = $con->query($sql);

    	return $res;
	}


}
