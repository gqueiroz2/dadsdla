<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/*
*Author: Bruno Gomes
*Date:08/04/2019
*Razon:Sales rep modeler
*/
class salesRep extends Model
{

	/*
	*Author: Bruno Gomes
	*Date:08/04/2019
	*Razon:getSalesRepGroup modeler
	*/
	public function getSalesRepGroup(
		$con,
		$region
	)
	{
		$where = "";

		if($region){
			$where .= "WHERE region.ID in ('.$region.')";
		}

		$sql = "
			SELECT 
				sales_rep.ID AS 'ID',
				sales_rep.name AS 'sales_rep'
				
			FROM 'DLA'.'sales_rep_group' AS sales_rep_group
				LEFT JOIN 'DLA'.'region' AS region ON region.ID = sales_rep_group.region_id
				LEFT JOIN 'DLA'.'sales_rep' AS sales_rep ON sales_rep.ID = sales_rep_group.sales_rep_id
			$where

		";

		$res = $con->query($sql);

    	return $res;
	}

	/*
	*Author: Bruno Gomes
	*Date:08/04/2019
	*Razon:getSalesRep modeler
	*/
    public function getSalesRep(
    	$con,
    	$sales_rep_group_id
    )
	{
		$where = "";

		if($sales_rep_group_id){
			$sales_rep_ids = implode(",", $sales_rep_group_id)
			$where .= "WHERE sales_rep_group.ID in ('.$sales_rep_ids.')";
		}

		$sql = "
			SELECT 
				sales_rep.ID AS 'ID',
				sales_rep.name AS 'sales_rep'
				
			FROM 'DLA'.'sales_rep' AS sales_rep
				LEFT JOIN 'DLA'.'sales_rep_group' AS sales_rep_group ON sales_rep_group.sales_rep_id = sales_rep.ID
			$where
				

		";

		$res = $con->query($sql);

    	return $res;
	}

	 /*
	*Author: Bruno Gomes
	*Date:08/04/2019
	*Razon:getSalesRepUnit modeler
	*/
	public function getSalesRepUnit(
		$con,
		$sales_rep_id
	)
	{
		$sql = "
			SELECT 
				sales_rep.ID AS 'ID',
				sales_rep.name AS 'sales_rep'
				
			FROM 'DLA'.'sales_rep_unit' AS sales_rep_unit
				LEFT JOIN 'DLA'.'sales_rep' AS sales_rep ON sales_rep.ID = sales_rep_unit.ID
				LEFT JOIN 'DLA'.'origin' AS origin ON origin.ID = sales_rep_unit.origin_id
			WHERE
				 sales_rep.ID in ('.$sales_rep_id.')

		";

		$res = $con->query($sql);

    	return $res;
	}
}
