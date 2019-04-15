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
    		FROM 'DLA'.'brand' AS brand
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
				brand_unit.ID AS ID,
				brand_unit.name AS 'brand_unit_name',
				origin.name AS 'origin_name'
				
			FROM 'DLA'.brand_unit AS brand_unit
				LEFT JOIN 'DLA'.'brand' AS brand ON brand.ID = brand_unit.brand_id
				LEFT JOIN 'DLA'.'origin' AS origin ON origin.ID = brand_unit.origin_id
			WHERE brand_unit.brand_id in ('.$brand_id.')
		";

		$res = $con->query($sql);

    	return $res;
	}


}
