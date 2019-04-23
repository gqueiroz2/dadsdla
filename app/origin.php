<?php

namespace App;

use Illuminate\Support\Facades\Request;
use App\Management;
use App\sql;

class origin extends Management{
    public function getOriginID($con,$origin){
    	$sql = new sql();
		$table ='origin o';
		$columns = 'ID';
		$where = "";
    	if($origin) {
    		$origins = implode(",", $origin);
    		$where .= "WHERE o.name IN ('$origins')";
    	}
    	$res = $sql->select($con,$columns,$table,null,$where);
    	$row = $res->fetch_assoc();
    	$originID = array($row['ID'],$origin);
		return $originID;    	
    }

    public function getOrigin($con,$ID){
		$sql = new sql();
		
		$table ='origin';
		$columns = 'id,name';

		$where = "";
    	if ($ID) {
    		$ids = implode(",", $ID);
    		$where .= "WHERE r.ID IN ('$ids')";
    	}

    	$res = $sql->select($con,$columns,$table);

  		$from = array('id','name');

  		$origin = $sql->fetch($res,$from,$from);

		return $origin;
	}

	public function addOrigin($con){
    	$sql = new sql();

    	$origin = Request::get('origin');

    	$table = "origin";
		$columns = 'name';
		$values = '"'.$origin.'"';

		$bool = $sql->insert($con,$table,$columns,$values);

		return $bool;
	}
}
