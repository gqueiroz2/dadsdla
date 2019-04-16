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
			$where .= "WHERE r.ID in ('.$region.')";
		}

		$sql = "
			SELECT 
				sr.ID AS 'id',
				sr.name AS 'salesRep'
				
			FROM sales_rep_group srg
				LEFT JOIN region r ON r.ID = srg.region_id
				LEFT JOIN sales_rep sr ON sr.ID = srg.sales_rep_id
			$where";

		$res = $con->query($sql);

    	return $res;
	}

    public function getSalesRep($con,$sales_rep_group_id){
		
		$where = "";

		if($sales_rep_group_id){
			$sales_rep_ids = implode(",", $sales_rep_group_id)
			$where .= "WHERE srg.ID in ('.$sales_rep_ids.')";
		}

		$sql = "
			SELECT 
				sr.ID AS 'id',
				sr.name AS 'salesRep'
				
			FROM sales_rep sr
				LEFT JOIN sales_rep_group srg ON srg.sales_rep_id = sr.ID
			$where
				

		";

		$res = $con->query($sql);

    	return $res;
	}



	public function getSalesRepUnit($con,$sales_rep_id){

		$sql = "
			SELECT 
				sr.ID AS 'id',
				sr.name AS 'salesRep'
				
			FROM sales_rep_unit sru
				LEFT JOIN sales_rep sr ON sr.ID = sru.ID
				LEFT JOIN origin o ON o.ID = sru.origin_id
			WHERE
				 sr.ID in ('.$sales_rep_id.')

		";

		$res = $con->query($sql);

    	return $res;
	}
}
