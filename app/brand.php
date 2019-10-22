<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Request;
use App\Management;
use App\sql;


class brand extends Management{

    public function getBrand($con , $ID = false){
		
		$sql = new sql();
		$table = "brand";
		$columns = "id,name,type";
		$from = array('id','name','type');	
		$where = "WHERE sub_brand = '0'";//false;//"WHERE name != 'OTH'";

		if($ID){
			$IDS = implode(",", $ID);
			$where.= " WHERE brand.id IN ($IDS)";
		}

		$result = $sql->select($con,$columns,$table,null,$where);
		$brand = $sql->fetch($result,$from,$from);
		return $brand;
	}

	public function getBrandBinary($con , $ID = false){
		
		$sql = new sql();
		$table = "brand";
		$columns = "id,name";
		$from = array('id','name');	
		$to = array(0,1);	
		$where = "";//false;//"WHERE name != 'OTH'";

		if($ID){
			$IDS = implode(",", $ID);
			$where.= " WHERE brand.id IN ($IDS)";
		}

		$result = $sql->select($con,$columns,$table,null,$where);
		$brand = $sql->fetch($result,$from,$to);
		return $brand;
	}

	public function getBrandUnit($con){
		$sql = new sql();
		$table = "brand_unit brdu";
		$columns = "brdu.ID AS 'id',
					brdu.name AS 'brandUnit',
					brd.name AS 'brand',
					brd.ID AS 'brandID',
					o.name AS 'origin'
					";
		$join = "LEFT JOIN brand brd ON brd.ID = brdu.brand_id
				 LEFT JOIN origin o ON o.ID = brdu.origin_id
				";
		$from = array('id','brandUnit','brand','brandID','origin');
		$result = $sql->select($con,$columns,$table,$join,false);
		$brandUnit = $sql->fetch($result,$from,$from);
		return $brandUnit;
	}

	public function addBrand($con){
		$sql = new sql();
		$brand = Request::get('brand');
		$table = 'brand';
		$columns = 'name';
		$values = "'$brand'";
		$bool = $sql->insert($con,$table,$columns,$values);
		return $bool;
	}

	public function addBrandUnit($con){
		$sql = new sql();
		$brandID = Request::get('brand');
		$originID = Request::get('origin');
		$brandUnit = Request::get('brandUnit');
		$table = 'brand_unit';
		$columns = 'brand_id,origin_id,name';
		$values = "'$brandID','$originID','$brandUnit'";
		$bool = $sql->insert($con,$table,$columns,$values);
		return $bool;
	}

	public function getBrandID($con, $name){
		
		$sql = new sql();
		$table = "brand";
		$columns = "id,name";
		$from = array('id','name');	
		$to = array(0,1);	
		$where = "WHERE name = '$name'";

		$result = $sql->select($con,$columns,$table,null,$where);
		$brand = $sql->fetch($result,$from,$from);
		return $brand;
	}

}
