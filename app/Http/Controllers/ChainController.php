<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Request;
use App\dataBase;
use App\import;
use App\chain;
use App\sql;

class ChainController extends Controller{
    

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

		if($miniHeaderBool){
			$msg = "Data insert successfully !!! ";
		}else{
			$msg = "Error, try it again !!!";
		}
		var_dump($msg);
		//return back()->with('firstChainResponse',$msg);
    }

    public function secondChain(){
		$db = new dataBase();
		$chain = new chain();
		$sql = new sql();

		$con = $db->openConnection('DLA');	
		$fCon = $db->openConnection('firstMatch');	
		$sCon = $db->openConnection('secondMatch');	
    	$table = Request::get('table');

    	$bool = $chain->secondChain($sql,$con,$fCon,$sCon,$table);

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