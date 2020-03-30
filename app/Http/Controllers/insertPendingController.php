<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Request;

use App\dataBase;
use App\RenderStuff;
use App\CheckElements;
use App\base;

class insertPendingController extends Controller{
    
	public function insertClientUnit(){
		
		$db = new dataBase;		
		$rS = new RenderStuff();
		$cE = new CheckElements();
		$base = new base();

		$default = $db->defaultConnection();
        $con = $db->openConnection($default);

		$fM = $db->matchesConnection("first");
		$conFM = $db->openConnection($fM);

		$region = Request::get('region'); 
		$table = Request::get('table');
		

		$tableCli = 'client_unit';
		$sizeC = Request::get('size');
		for ($c=0; $c < $sizeC; $c++) { 
			
			if( !is_null( Request::get("clients-group-$c") ) || !is_null( json_decode(base64_decode(Request::get("clients-$c"))) ) ){
				$client[$c]['group'] = Request::get("clients-group-$c");
				$client[$c]['base'] = json_decode(base64_decode(Request::get("clients-$c")));
				$client[$c]['unit'] = Request::get("clients-unit-$c");
			}
		}
		$type = "client";
		
		$bool = $this->insert($con,$tableCli,$type,$client);

		$newValues = $cE->newValues($con,$conFM,$region,$table);
		$dependencies = array('regions','brands','salesReps','clients','agencies','currencies');

		return view('dataManagement.Chain.pendingStuff',compact('base','rS','con','newValues','dependencies','table','region'))->with('inserted',"DONE !!!");
		
	}


	public function insertAgencyUnit(){
		
		$db = new dataBase;		
		$rS = new RenderStuff();
		$cE = new CheckElements();
		$base = new base();

		$default = $db->defaultConnection();
        $con = $db->openConnection($default);

		$fM = $db->matchesConnection("first");
		$conFM = $db->openConnection($fM);

		$region = Request::get('region'); 
		$table = Request::get('table');		

		$tableAg = 'agency_unit';
		$sizeC = Request::get('size');
		
		for ($c=0; $c < $sizeC; $c++) { 
			
			if( !is_null( Request::get("agencies-group-$c") ) || !is_null( json_decode(base64_decode(Request::get("agencies-$c"))) ) ){
				$agencies[$c]['group'] = Request::get("agencies-group-$c");
				$agencies[$c]['base'] = json_decode(base64_decode(Request::get("agencies-$c")));
				$agencies[$c]['unit'] = Request::get("agencies-unit-$c");
			}
		}

		$type = "agency";
		$bool = $this->insert($con,$tableAg,$type,$agencies);


		$newValues = $cE->newValues($con,$conFM,$region,$table);
		$dependencies = array('regions','brands','salesReps','clients','agencies','currencies');

		return view('dataManagement.Chain.pendingStuff',compact('base','rS','con','newValues','dependencies','table','region'))->with('inserted',"DONE !!!");
	}

	public function insert($con,$table,$type,$array){
		
			

		$keys = array_keys($array);
		$check = 0;

		for ($c=0; $c < sizeof($array); $c++) { 	
			$insert[$c] = "INSERT INTO $table (".$type."_id,origin_id,name) 
				VALUES( \"".$array[$keys[$c]]["base"]->ID."\" ,
				         \"1\", 
				         \"".addslashes( $array[$keys[$c]]['unit'] )."\"
				      )";	

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
