<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Request;
use Validator;

use App\dataBase;
use App\import;
use App\chain;
use App\sql;
use App\RenderAgencyClient;
use App\ClientAgency;
use App\base;

class ClientAgencyController extends Controller{

    public function rootExcel(){
    	$rAC = new RenderAgencyClient();
    	return view('dataManagement.AgencyClient.get',compact('rAC'));
    }

    public function excelHandler(){
    	
    	$db = new dataBase();
		$chain = new chain();		
		$i = new import();
		$cA = new ClientAgency();
		$base = new base();

		$con = $db->openConnection('DLA');	
		
		$year = Request::get('year');

		$type = Request::get('type');

		$fileNames = array('fileTypeGroup','fileType','fileTypeUnit');
		$table = $cA->handler($type,false);		

		for ($f=0; $f < sizeof($fileNames); $f++) { 
			$spreadSheet[$f] = $i->spread($fileNames[$f]);
			unset($spreadSheet[$f][0]);			
			$spreadSheet[$f] = array_values($spreadSheet[$f]);
		}

		$complete = $cA->toDataBase($con,$table,$spreadSheet,$base);

		if($complete){
            var_dump("FOI");
        }else{
            var_dump("NAO FOI");
        }



    }
}
