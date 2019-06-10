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
		$bool = $this->insert($con,$table,$client);
		var_dump($bool); 
	}

	public function insert($con,$table,$client){

		var_dump($table);

		$check = 0;

		for ($c=0; $c < sizeof($client); $c++) { 	

			$insert[$c] = "INSERT INTO $table (client_id,origin_id,name) VALUES( \"".$client[$c]["base"]->ID."\" ,\"1\", \"".$client[$c]['unit']."\")";	
			var_dump($insert[$c]);
			
			if ($con->query($insert[$c]) === TRUE) {
				$check ++;
			}else{
            	var_dump($con->error);
			}
		}

		if($check == sizeof($client)){
			$rtr = true;
		}else{
			$rtr = false;
		}


	}


}
