<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class salesRep extends Model{
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
			$where .= "WHERE r.ID IN ('$region')";
		}

		$sql = "
			SELECT 
				srg.ID AS 'id',
				srg.name AS 'name',
				r.name AS 'region'
			FROM sales_rep_group srg
				LEFT JOIN region r ON srg.region_id = r.ID
			$where";

		$res = $con->query($sql);

    	return $res;
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
}
