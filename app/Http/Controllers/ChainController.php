<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Request;
use App\dataBase;
use App\import;
use App\chain;
use App\sql;
use App\RenderChain;

class ChainController extends Controller{
    public function ytdGet(){
    	$rC = new RenderChain();
    	return view('dataManagement.Chain.ytdGet',compact('rC'));
    }

    public function CMAPSGet(){
    	$rC = new RenderChain();
    	return view('dataManagement.Chain.CMAPSGet',compact('rC'));
    }

    public function miniHeaderGet(){
    	$rC = new RenderChain();
    	return view('dataManagement.Chain.miniHeaderGet',compact('rC'));
    }

    public function firstChain(){
    	$db = new dataBase();
		$chain = new chain();		
		$i = new import();
		$con = $db->openConnection('firstMatch');	
		$table = Request::get('table');
		$year = Request::get('year');
		$truncate = (bool)intval(Request::get('truncate'));
		var_dump($truncate);

		$spreadSheet = $i->base();

		switch ($table) {
			case 'ytd':
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
				break;
			case 'cmaps':
				unset($spreadSheet[0]);
				$spreadSheet = array_values($spreadSheet);
				break;
			case 'mini_header':
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
				break;			
		}

		$complete = $chain->handler($con,$table,$spreadSheet,$year,$truncate);
		

		if($complete){
            return back()->with('firstChainComplete',"The Excel Data Was Succesfully Inserted :)");
        }else{
            return back()->with('firstChainError',"There was and error on the insertion of the Excel Data :( ");
        }

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

    	$complete = $chain->secondChain($sql,$con,$fCon,$sCon,$table,$year);

    	if($complete){
            return back()->with('secondChainComplete',"The Excel Data Was Succesfully Inserted :)");
        }else{
            return back()->with('secondChainError',"There was and error on the insertion of the Excel Data :( ");
        }
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
