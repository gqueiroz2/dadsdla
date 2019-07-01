<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Request;

use App\dataBase;

class insertPendingController extends Controller{
    
	public function insertClientUnit(){
		$db = new dataBase;
		$con = $db->openConnection('DLA');
		$table = 'client_unit';
		$sizeC = Request::get('size');
		for ($c=0; $c < $sizeC; $c++) { 
			
			if( !is_null( Request::get("clients-group-$c") ) || !is_null( json_decode(base64_decode(Request::get("clients-$c"))) ) ){
				$client[$c]['group'] = Request::get("clients-group-$c");
				$client[$c]['base'] = json_decode(base64_decode(Request::get("clients-$c")));
				$client[$c]['unit'] = Request::get("clients-unit-$c");
			}
		}
		$type = "client";
		$bool = $this->insert($con,$table,$type,$client);
		return back()->with('inserted',"DONE !!!");
	}


	public function insertAgencyUnit(){
		$db = new dataBase;
		$con = $db->openConnection('DLA');
		$table = 'agency_unit';
		$sizeC = Request::get('size');
		var_dump(Request::all());
		
		for ($c=0; $c < $sizeC; $c++) { 
			
			if( !is_null( Request::get("agencies-group-$c") ) || !is_null( json_decode(base64_decode(Request::get("agencies-$c"))) ) ){
				$agencies[$c]['group'] = Request::get("agencies-group-$c");
				$agencies[$c]['base'] = json_decode(base64_decode(Request::get("agencies-$c")));
				$agencies[$c]['unit'] = Request::get("agencies-unit-$c");
			}
		}
		$type = "agency";
		$bool = $this->insert($con,$table,$type,$agencies);
		return back()->with('inserted',"DONE !!!");
	}

	public function insert($con,$table,$type,$array){
		
			

		$keys = array_keys($array);
		$check = 0;

		for ($c=0; $c < sizeof($array); $c++) { 	
			$insert[$c] = "INSERT INTO $table (".$type."_id,origin_id,name) VALUES( \"".$array[$keys[$c]]["base"]->ID."\" ,\"1\", \"".$array[$keys[$c]]['unit']."\")";	
			var_dump($insert[$c]);

			if ($con->query($insert[$c]) === TRUE) {
				$check ++;
			}else{
            	var_dump($con->error);
			}
		}

		if($check == sizeof($array)){
			$rtr = true;
		}else{
			$rtr = false;
		}


	}


}
