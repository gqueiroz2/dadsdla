<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Request;
use Validator;

use App\dataBase;
use App\import;
use App\chain;
use App\sql;
use App\RenderChain;
use App\digital;

class ChainController extends Controller{
    public function chainGet(){
    	$rC = new RenderChain();
    	return view('dataManagement.Chain.get',compact('rC'));
    }    

    public function truncateChain(){
    	$validator = Validator::make(Request::all(),[
            'tableTruncate' => 'required',
        ]);

        if ($validator->fails()) {
        	return back()->withErrors($validator)->withInput();
        }

    	$table = Request::get('tableTruncate'); 

        if($table == 'bts' || $table == 'ytdFN'){
            $table = 'ytd';
        }
        
    	$db = new dataBase();
		
		$connections = array('firstmatch','secondmatch','thirdmatch');

		$truncateStatement = "TRUNCATE TABLE $table";

		$check = 0;

		for ($c=0; $c < sizeof($connections); $c++) { 
			$con[$c] = $db->openConnection($connections[$c]);
			if($con[$c]->query($truncateStatement) === TRUE){
                $something[$c] = "Tabela $table na dataBase ".$connections[$c]." foi Truncada ";

				$check++;
			}
		}

		if($check == sizeof($connections)){
			return back()->with('truncateChainComplete',"The table $table was succesfully truncated on all data bases :)");
		}else{
			return back()->with('truncateChainError',"There was and error on truncating the $table table on all data bases :( ");
		}

    }

    public function firstChain(){

    	$validator = Validator::make(Request::all(),[
    		'file' => 'required',
            'tableFirstChain' => 'required',
        ]);

        if ($validator->fails()) {
        	return back()->withErrors($validator)->withInput();
        } 

    	$db = new dataBase();
		$chain = new chain();		
		$i = new import();
		$con = $db->openConnection('firstmatch');	
		$table = Request::get('tableFirstChain');
		$year = Request::get('year');

		$spreadSheet = $i->base();

		switch ($table) {
            case 'data_hub':
                unset($spreadSheet[0]);
                unset($spreadSheet[1]);
                unset($spreadSheet[2]);
                unset($spreadSheet[3]);
                unset($spreadSheet[4]);

                $tar = sizeof($spreadSheet);
                unset($spreadSheet[$tar]);

                $spreadSheet = array_values($spreadSheet);

                break;

            case 'bts':
                unset($spreadSheet[0]);
                //unset($spreadSheet[1]);
                //unset($spreadSheet[2]);
                $spreadSheet = array_values($spreadSheet);
                for ($s=0; $s < sizeof($spreadSheet); $s++) {
                    if( $spreadSheet[$s][0] == "Grand Total"){
                        $pivot = $s;
                    }
                }
                if(isset($pivot) && $pivot){
                    unset($spreadSheet[$pivot]);
                    $spreadSheet = array_values($spreadSheet);
                }
                break;

			case 'ytd':
				unset($spreadSheet[0]);
				unset($spreadSheet[1]);
				unset($spreadSheet[2]);
				$spreadSheet = array_values($spreadSheet);
				for ($s=0; $s < sizeof($spreadSheet); $s++) {
					if( ($spreadSheet[$s][0] == "Total" && $spreadSheet[$s][1] == '' && $spreadSheet[$s][2] == '') || $spreadSheet[$s][0] == "Grand Total"){
						$pivot = $s;
					}
				}
				unset($spreadSheet[$pivot]);
				$spreadSheet = array_values($spreadSheet);
				break;
            case 'ytdFN':
                unset($spreadSheet[0]);
                $spreadSheet = array_values($spreadSheet);
                //$table = "ytd";
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
            case 'fw_digital':
                var_dump($spreadSheet[0]);
                unset($spreadSheet[0]);
                $spreadSheet = array_values($spreadSheet);
                break;
            case 'sf_pr':
                unset($spreadSheet[0]);                
                if($spreadSheet){
                    $spreadSheet = array_values($spreadSheet);
                }
                break;
            case 'sf_pr_brand':
                unset($spreadSheet[0]);
                if($spreadSheet){
                    $spreadSheet = array_values($spreadSheet);
                }
                break;
            case 'digital':
                $dg = new digital();
                $spreadSheet = $dg->excelToBase($spreadSheet);
                break;	
            case 'insights':
                unset($spreadSheet[0]);
                unset($spreadSheet[1]);
                unset($spreadSheet[2]);
                $spreadSheet = array_values($spreadSheet);
                break;
            case 'aleph':
                unset($spreadSheet[0]);
                unset($spreadSheet[1]);
                $spreadSheet = array_values($spreadSheet);
                break;
            case 'wbd':
                unset($spreadSheet[0]);
                unset($spreadSheet[1]);
                unset($spreadSheet[2]);
                $spreadSheet = array_values($spreadSheet);
                break;
            case 'wbd_bv':
                unset($spreadSheet[0]);
                $spreadSheet = array_values($spreadSheet);
                break;
		}
        
		$complete = $chain->handler($con,$table,$spreadSheet,$year);
        //var_dump($spreadSheet);

		if($complete){
            return back()->with('firstChainComplete',"The Excel Data Was Succesfully Inserted :)");
        }
    }

    public function secondChain(){
		$validator = Validator::make(Request::all(),[
            'tableSecondChain' => 'required',
        ]);

        if ($validator->fails()) {
        	return back()->withErrors($validator)->withInput();
        }

		$db = new dataBase();
		$chain = new chain();
		$sql = new sql();

		$default = $db->defaultConnection();
        $con = $db->openConnection($default);
		$fCon = $db->openConnection('firstmatch');	
		$sCon = $db->openConnection('secondmatch');	
    	$table = Request::get('tableSecondChain');

    	$year = Request::get('year');

        $complete = $chain->secondChain($sql,$con,$fCon,$sCon,$table,$year);
        //var_dump($complete);
    	if($complete){
            return back()->with('secondChainComplete',"The Excel Data Was Succesfully Inserted :)");
        }
    }

    public function thirdChain(){
    	$validator = Validator::make(Request::all(),[
            'tableThirdChain' => 'required',
        ]);

        if ($validator->fails()) {
        	return back()->withErrors($validator)->withInput();
        }

    	$db = new dataBase();
		$chain = new chain();
		$sql = new sql();
        
		$default = $db->defaultConnection();
        $con = $db->openConnection($default);	

		$sCon = $db->openConnection('secondmatch');	
		$tCon = $db->openConnection('thirdmatch');	
    	$table = Request::get('tableThirdChain');
    	$year = Request::get('year');

        if($table == "ytdFN"){
            $table = "ytd";
        }

        $complete  = $chain->thirdChain($sql,$con,$sCon,$tCon,$table,$year);
        
    	if($complete){
            return back()->with('thirdChainComplete',"The Excel Data Was Succesfully Inserted :)");
        }
        
    }

    public function thirdToDLA(){
    	$validator = Validator::make(Request::all(),[
            'tableToDLAChain' => 'required',
        ]);

        if ($validator->fails()) {
        	return back()->withErrors($validator)->withInput();
        }

    	$db = new dataBase();
		$chain = new chain();
		$sql = new sql();

		$default = $db->defaultConnection();
        $con = $db->openConnection($default);
        		
		$tCon = $db->openConnection('thirdmatch');	
    	$table = Request::get('tableToDLAChain');
    	$year = Request::get('year');
		$truncate = (bool)intval(Request::get('truncate'));

    	$complete = $chain->thirdToDLA($sql,$con,$tCon,$table,$year,$truncate);
        
    	if($complete){
            return back()->with('lastChainComplete',"The Excel Data Was Succesfully Inserted :)");
        }
    }

}
