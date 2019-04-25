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
		$sql = new sql();

		$table = "sales_rep_group srg";
		$columns = "srg.ID AS 'id',
				    srg.name AS 'name',
				    r.name AS 'region'";
		$where = "";

		if($region){
			$regions = implode(",", $region);
			$where .= "WHERE r.ID IN ('$regions')";
		}

		$join = "LEFT JOIN region r ON srg.region_id = r.ID";

		$res = $sql->select($con,$columns,$table,$join,$where);

		$from = array('id','name','region');

		$salesRepGroup = $sql->fetch($res,$from,$from);

    	return $salesRepGroup;
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

	public function editSalesRepGroup($con){
		
		$sql = new sql();

		$size = Request::get("size");

		$table = "sales_rep_group";

		$columns = array("region_id","name");

		for ($i=0; $i <$size ; $i++) {
			
			$oldRegion[$i] = Request::get("oldRegion-$i");
			$oldName[$i] = Request::get("oldName-$i");

			$newRegion[$i] = Request::get("newRegion-$i");
			$newName[$i] = Request::get("newName-$i");

			$arrayWhere[$i] = array($oldRegion[$i], $oldName[$i]);
			$arraySet[$i] = array($newRegion[$i], $newName[$i]);

			$where[$i] = $sql->where($columns,$arrayWhere[$i]);
			$set[$i] = $sql->setUpdate($columns,$arraySet[$i]);
		}

		for ($i=0; $i <$size ; $i++) { 
			if ($oldRegion[$i] != $newRegion[$i] || $oldName[$i] != $newName[$i]) {
				$bool = $sql->updateValues($con,$table,$set[$i],$where[$i]);
				if ($bool == false) {
					break;
				}
			}
		}


		return $bool;
	}

    public function getSalesRep($con,$salesRepGroupID){
		$sql = new sql();

		$table = "sales_rep sr";
		$columns = "sr.ID AS 'id',
				sr.name AS 'salesRep',	
				srg.name AS 'salesRepGroup',
				r.name AS 'region'";

		$where = "";

		if($salesRepGroupID){
			$salesRepGroupIDS = implode(",", $salesRepGroupID);
			$where .= "WHERE srg.ID IN ('$salesRepGroupIDS')";
		}

		$join = "LEFT JOIN sales_rep_group srg ON srg.ID = sr.sales_group_id
				 LEFT JOIN region r ON r.ID = srg.region_id";

		$res = $sql->select($con,$columns,$table,$join,$where);

		$from = array('id','salesRep','salesRepGroup','region');

		$salesRep = $sql->fetch($res,$from,$from);

    	return $salesRep;
	}

	public function getSalesRepByRegion($con,$region){
		$sql = new sql();

		$table = "sales_rep sr";
		$columns = "sr.ID AS 'id',
				sr.name AS 'salesRep',	
				srg.name AS 'salesRepGroup',
				r.name AS 'region'";

		$where = "";

		if($region){
			$ids = implode(",", $region);
			$where .= "WHERE r.ID IN ('$ids')";
		}

		$join = "LEFT JOIN sales_rep_group srg ON srg.ID = sr.sales_group_id
				LEFT JOIN region r ON r.ID = srg.region_id";

		$res = $sql->select($con,$columns,$table,$join,$where);

		$from = array('id','salesRep','salesRepGroup','region');

		$salesRep = $sql->fetch($res,$from,$from);

    	return $salesRep;
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

	public function editSalesRep($con){
		$sql = new sql();

		$size = Request::get("size");

		$table = "sales_rep sr";

		var_dump(Request::all());

		$columns = array('sales_group_id','name');

		for ($i=0; $i <$size; $i++) { 
			$oldSalesGroup[$i] = Request::get("oldSalesGroup-$i");
			$newSalesGroup[$i] = Request::get("newSalesGroup-$i");
		
			$oldSalesRep[$i] = Request::get("oldSalesRep-$i");
			$newSalesRep[$i] = Request::get("newSalesRep-$i");

			$arrayWhere[$i] = array($oldSalesGroup[$i],$oldSalesRep[$i]);
			$arraySet[$i] = array($newSalesGroup[$i],$newSalesRep[$i]);
		}

		var_dump($arrayWhere);
		var_dump($arraySet);
	}

	public function getSalesRepUnit($con,$salesRepID){
		$sql = new sql();

		$table = "sales_rep_unit sru";
		$columns = "sru.ID AS 'id',
				    sru.name AS 'salesRepUnit',
				    sr.name AS 'salesRep',
				    sr.ID AS 'salesRepID',
				    o.name AS 'origin'";
		$where = "";

		if($salesRepID){
			$salesRepIDS = implode(",", $salesRepID);
			$where .= "WHERE sr.ID IN ('$salesRepIDS')";
		}

		$join = "lEFT JOIN sales_rep sr ON sr.ID = sru.sales_rep_id
				LEFT JOIN origin o ON o.ID = sru.origin_id";

		$res = $sql->select($con,$columns,$table,$join,$where);

		$from = array('id','salesRepUnit','salesRep','salesRepID','origin');

		$salesRepUnit = $sql->fetch($res,$from,$from);

    	return $salesRepUnit;
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
