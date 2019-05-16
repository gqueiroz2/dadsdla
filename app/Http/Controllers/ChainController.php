<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Request;
use App\dataBase;
use App\import;
use App\chain;
use App\sql;

class ChainController extends Controller{
    public function ytdGet(){
    	return view('dataManagement.Chain.ytdGet');
    }

    public function ytdPost(){
    	$db = new dataBase();
		$chain = new chain();		
		$i = new import();

		$con = $db->openConnection('firstMatch');	
		$spreadSheet = $i->base();
		unset($spreadSheet[0]);
		unset($spreadSheet[1]);		
		unset($spreadSheet[2]);		
		$spreadSheet = array_values($spreadSheet);
		for ($s=0; $s < sizeof($spreadSheet); $s++) { 
			if($spreadSheet[$s][0] == "Total" && $spreadSheet[$s][1] == '' && $spreadSheet[$s][2] == ''){
				$pivot = $s;
			}
		}
		unset($spreadSheet[$pivot]);
		$spreadSheet = array_values($spreadSheet);
				
		$table = 'ytd';

		$miniHeaderBool = $chain->handler($con,$table,$spreadSheet);
	}

    public function CMAPSGet(){
    	return view('dataManagement.Chain.CMAPSGet');
    }

    public function CMAPSPost(){
    	$db = new dataBase();
		$chain = new chain();		
		$i = new import();
		$con = $db->openConnection('firstMatch');			
		$spreadSheet = $i->base();
		unset($spreadSheet[0]);
		$spreadSheet = array_values($spreadSheet);				
		$table = 'cmaps';		
		$miniHeaderBool = $chain->handler($con,$table,$spreadSheet);		
    }


    public function miniHeaderGet(){
    	return view('dataManagement.Chain.miniHeaderGet');
    }

    public function miniHeaderPost(){
    	$db = new dataBase();
		$chain = new chain();		
		$i = new import();

		$con = $db->openConnection('firstMatch');	
		$spreadSheet = $i->base();
		unset($spreadSheet[0]);
		unset($spreadSheet[1]);		
		unset($spreadSheet[2]);		
		$spreadSheet = array_values($spreadSheet);
		for ($s=0; $s < sizeof($spreadSheet); $s++) { 
			if($spreadSheet[$s][0] == "Total" && $spreadSheet[$s][1] == '' && $spreadSheet[$s][2] == ''){
				$pivot = $s;
			}
		}
		unset($spreadSheet[$pivot]);
		$spreadSheet = array_values($spreadSheet);
				
		$table = 'mini_header';		
		$miniHeaderBool = $chain->handler($con,$table,$spreadSheet);

		
    }

    public function secondChain(){
		$db = new dataBase();
		$chain = new chain();
		$sql = new sql();

		$con = $db->openConnection('DLA');	
		$fCon = $db->openConnection('firstMatch');	
		$sCon = $db->openConnection('secondMatch');	
    	$table = Request::get('table');

    	if($table == 'cmaps'){
    		$year = Request::get('year');
    	}else{
    		$year = false;
    	}

    	$bool = $chain->secondChain($sql,$con,$fCon,$sCon,$table,$year);

    }

    public function thirdChain(){
    	$db = new dataBase();
		$chain = new chain();
		$sql = new sql();

		$con = $db->openConnection('DLA');			
		$sCon = $db->openConnection('secondMatch');	
		$tCon = $db->openConnection('thirdMatch');	
    	$table = Request::get('table');

    	$bool = $chain->thirdChain($sql,$con,$sCon,$tCon,$table);
    }

    public function thirdToDLA(){
    	$db = new dataBase();
		$chain = new chain();
		$sql = new sql();

		$con = $db->openConnection('DLA');			
		$tCon = $db->openConnection('thirdMatch');	
    	$table = Request::get('table');

    	$bool = $chain->thirdToDLA($sql,$con,$tCon,$table);

    }

}
