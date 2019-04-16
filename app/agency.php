<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/*
*Author: Bruno Gomes
*Date:09/04/2019
*Razon:Agency modeler
*/
class agency extends Model
{
	/*
	*Author: Bruno Gomes
	*Date:09/04/2019
	*Razon:getAgencyGroup modeler
	*/
    public function getAgencyGroup(
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
				agency_group.ID AS 'ID',
				agency_group.name AS 'agency_group_name'


			FROM 'DLA'.'agency_group' AS agency_group
				LEFT JOIN 'DLA'.'agency' AS agency ON agency.agency_group_id = agency_group.ID
				LEFT JOIN 'DLA'.'region' AS region ON region.ID = agency.region_id
			$where
		";

		$res = $con->query($sql);

    	return $res;
    }

	/*
	*Author: Bruno Gomes
	*Date:09/04/2019
	*Razon:getAgency modeler
	*/
    public function getAgency(
    	$con
    )
    {
    	$sql = "
			SELECT 
				agency.ID AS 'ID',
				agency.name AS 'agency_name',
				agency_group.name AS 'agency_group_name'


			FROM 'DLA'.'agency' AS agency
				LEFT JOIN 'DLA'.'agency_group' AS agency_group ON agency.agency_group_id = agency_group.ID
		";

    	$res = $con->query($sql);

    	return $res;
    }

	/*
	*Author: Bruno Gomes
	*Date:09/04/2019
	*Razon:getAgencyUnit modeler
	*/
    public function getAgencyUnit(
    	$con,
    	$agency_id

    )
    {
    	$where = "";

		if($agency_id){
			$where .= "WHERE agency.ID in ('.$agency_id.')";
		}

		$sql = "
			SELECT
				agency_unit.ID AS 'ID',
				agency_unit.name AS 'agency_name',
				origin.name AS 'origin_name'

			FROM 'DLA'.'agency_unit' AS agency_unit
				LEFT JOIN 'DLA'.'agency' AS agency ON agency.ID = agency_unit.agency_id
				LEFT JOIN 'DLA'.'origin' AS origin ON origin.ID = agency_unit.origin_id
			$where

		";

		$res = $con->query($sql);

    	return $res;
    }
}
