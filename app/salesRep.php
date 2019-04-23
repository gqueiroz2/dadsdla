<?php

namespace App;

use Illuminate\Support\Facades\Request;
use App\Management;
use App\sql;

class salesRep extends Management{
	/*
		Abreviations

		srg = sales_rep_group
		sr = sales_rep
		sru = sales_rep_unit
		o = origin
	*/

	public function getSalesRepGroup($con,$region){
		$where = "";

		if($region){
			$regions = implode(",", $region);
			$where .= "WHERE r.ID IN ('$regions')";
		}

		$sql = "SELECT 
				srg.ID AS 'id',
				srg.name AS 'name',
				r.name AS 'region'
			FROM sales_rep_group srg
				LEFT JOIN region r ON srg.region_id = r.ID
			$where";

		$res = $con->query($sql);


    	return $res;
	}

	public function addSalesRepGroup($con){
		$sql = new sql();

		$region = Request::get('region');
		$salesRepGroup = Request::get('salesRepGroup');

		$table = 'sales_rep_group';
		$columns = 'region_id,name';
		$values = " '$region','$salesRepGroup' ";

		$bool = $sql->insert($con,$table,$columns,$values);

		return $bool;
	}

    public function getSalesRep($con,$sales_rep_group_id){
		
		$where = "";

		if($sales_rep_group_id){
			$sales_rep_ids = implode(",", $sales_rep_group_id);
			$where .= "WHERE srg.ID in ('$sales_rep_ids')";
		}

		$sql = "
			SELECT 
				sr.ID AS 'id',
				sr.name AS 'salesRep',	
				srg.name AS 'salesRepGroup',
				r.name AS 'region'			
			FROM sales_rep sr
				LEFT JOIN sales_rep_group srg ON srg.ID = sr.sales_group_id
				LEFT JOIN region r ON r.ID = srg.region_id
			$where
		";

		$res = $con->query($sql);

    	return $res;
	}

	public function addSalesRep($con){
		$sql = new sql();

		$regionID = Request::get('region');
		$salesRepGroupID = Request::get('salesRepGroup');
		$salesRep = Request::get('salesRep');

		$table = 'sales_rep';
		$columns = 'sales_group_id,name';
		$values = " '$salesRepGroupID','$salesRep' ";

		$bool = $sql->insert($con,$table,$columns,$values);

		return $bool;

	}

	public function getSalesRepUnit($con,$sales_rep_id){

		$where = "";

		if($sales_rep_id){
			$sales_rep_ids = implode(",", $sales_rep_id);
			$where .= "WHERE sr.ID in ('$sales_rep_ids')";
		}

		$sql = "
			SELECT 
				sru.ID AS 'id',
				sru.name AS 'salesRepUnit',
				sr.name AS 'salesRep',
				o.name AS 'origin'				
			FROM sales_rep_unit sru
				LEFT JOIN sales_rep sr ON sr.ID = sru.sales_rep_id
				LEFT JOIN origin o ON o.ID = sru.origin_id
			$where
		";

		$res = $con->query($sql);

    	return $res;
	}

	public function addSalesRepUnit($con){
		$sql = new sql();

		$regionID = Request::get('region');
		$salesRepGroupID = Request::get('salesRepGroup');
		$salesRepID = Request::get('salesRep');
		$salesRepUnit = Request::get('salesRepUnit');
		$origin = Request::get('origin');

		$table = 'sales_rep_unit';
		$columns = 'sales_rep_id,origin_id,name';
		$values = " '$salesRepID','$origin','$salesRepUnit' ";

		$bool = $sql->insert($con,$table,$columns,$values);

		return $bool;

	}

}
