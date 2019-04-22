<?php

namespace App;

use Illuminate\Support\Facades\Request;
use App\Management;
use App\sql;

class origin extends Management{
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
