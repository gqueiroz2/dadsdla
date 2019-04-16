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
    	$agency,
    	$agency_unit,
    	$agency_group
    )
    {
    	$colluns = "";

    	if ($agency) {
    		$colluns .= "agency.ID AS 'id', agency.name AS 'agencyName' ";
    		if ($agency_group OR $agency_unit) {
    			$colluns .= " , ";
    		}
    	}

    	if ($agency_group) {
    		$colluns .= "agency_group.ID AS 'id', agency_group.name AS 'agencyGroupName'";
    		if ($agency OR $agency_unit) {
    			$colluns .= " , ";
    		}
    	}

    	if ($agency_unit) {
    		$colluns .= "agency_unit.ID AS 'id', agency_unit.name AS 'agencyUnitName'";
    	}

    	return $colluns;
    }

    /*
	*Author: Bruno Gomes
	*Date:15/04/2019
	*Razon:Table modeler
	*/
    public function table (
    	$agency,
    	$agency_unit,
    	$agency_group
    )
    {
    	if ($agency) {
    		$table = "'agency' agency ";
    		$table .= "LEFT JOIN 'agency_group' AS agency_group ON agency.agency_group_id = agency_group.ID ";
    	}

    	if ($agency_unit) {
    		$table = "'agency_unit' agency_unit ";
    		$table .= "LEFT JOIN 'agency' AS agency ON agency.ID = agency_unit.agency_id ";
    		$table .= "LEFT JOIN 'origin' AS origin ON origin.ID = agency_unit.origin_id ";
    	}

    	if ($agency_group) {
    		$table = "'agency_group' agency_group ";
    		$table .= "LEFT JOIN 'agency' AS agency ON agency.agency_group_id = agency_group.ID ";
    		$table .= "LEFT JOIN 'region' AS region ON region.ID = agency.region_id ";
    	}

    	return $table;

    }

    /*
	*Author: Bruno Gomes
	*Date:15/04/2019
	*Razon:Where modeler
	*Details: If you pass agency and agency_group, the return will be the group which that agency belong. If you pass the region, the search will use this argument to better return results
	*/
    public function where (
    	$agency_group,
    	$agency,
    	$agency_unit,
    	$region
    )
    {
    	$where = "";

    	if ($agency) {
    		if ($agency_group) {
    			$agency_group_ids = implode(",", $agency_group);
    			$where .= "agency.agency_group_id IN ('.$agency_group_ids.') ";
    		}
    		$where .= "client.ID IN ('.$agency.')";
    	}

    	if ($agency_group) {
    		if ($region) {
    			$region_ids = implode(",", $region);
    			$where .= "region.ID in ('.$region_ids.') ";
    		}
    		$where .= "client_group.ID IN ('.$client_group.')";
    	}

    	if ($agency_unit) {
    		if ($agency) {
    			$agency_ids = implode(",", $agency);
    			$where .= "agency.ID in ('.$agency_ids.') ";
    		}
    		$where .= "agency_unit.ID IN ('.$agency_unit.')";
    	}

    	return $where;
    }

    /*
	*Author: Bruno Gomes
	*Date:15/04/2019
	*Razon:Order_by modeler
	*/
    public function order_by (
    	$agency,
    	$agency_group,
    	$agency_unit,
    	$order
    )
    {
    	$order_by = "ORDER BY ";

    	if ($agency) {
    		$order_by .= "agency.name"; 
    		if ($agency_unit OR $agency_group ) {
    			$order_by .= " , ";
    		}
    	}

    	if ($agency_unit) {
    		$order_by .= "agency_unit.name";
    		if ($agency OR $agency_group ) {
    			$order_by .= " , ";
    		}
    	}

    	if ($agency_group) {
    		$order_by .= "agency_group.name";
    	}

		//this parameters, pass it as true or false, for true the result will be ASC
    	if ($order == TRUE) {
    		$order_by .= " ASC";
    	}
    	else{
    		$order_by .= " DESC";
    	}

    	return $order_by;

    	return $order_by;
    }

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


			FROM 'agency_group' AS agency_group
				LEFT JOIN 'agency' AS agency ON agency.agency_group_id = agency_group.ID
				LEFT JOIN 'region' AS region ON region.ID = agency.region_id
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


			FROM 'agency' AS agency
				LEFT JOIN 'agency_group' AS agency_group ON agency.agency_group_id = agency_group.ID
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

			FROM 'agency_unit' AS agency_unit
				LEFT JOIN 'agency' AS agency ON agency.ID = agency_unit.agency_id
				LEFT JOIN 'origin' AS origin ON origin.ID = agency_unit.origin_id
			$where

		";

		$res = $con->query($sql);

    	return $res;
    }
}
