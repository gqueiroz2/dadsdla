<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Request;
use Validator;

use App\dataBase;
use App\import;
use App\chainCmaps;
use App\sql;
use App\RenderChain;
use App\digital;
use App\base;

class chainCmapsController extends Controller{

    public function chainGet(){
    	$rC = new RenderChain();
    	return view('dataManagement.Chain.CMAPSGet',compact('rC'));
    }    

    public function truncateChain(){
    	$validator = Validator::make(Request::all(),[
            'tableTruncate' => 'required',
        ]);

        if ($validator->fails()) {
        	return back()->withErrors($validator)->withInput();
        }

    	$table = Request::get('tableTruncate'); 
        
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

     public function dailyResultsChain(){
        $validator = Validator::make(Request::all(),[
            'file' => 'required',
            'daily_results' => 'required'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }


        $db = new dataBase();
        $chain = new chainCmaps();       
        $i = new import();
        $base = new base();

        $default = $db->defaultConnection();
        $con = $db->openConnection($default);

        $spreadSheet = $i->base();

        $table = Request::get('daily_results');

        $truncateStatement = "TRUNCATE TABLE $table";
        
        $truncateStatement = $con->query($truncateStatement);


        switch ($table) {
            case 'daily':
                unset($spreadSheet[0]);
                unset($spreadSheet[1]);
                unset($spreadSheet[2]);
                $spreadSheet = array_values($spreadSheet);
                break;          
        }
        /*for ($s=0; $s < sizeof($spreadSheet); $s++) { 
            $spreadSheet[$s][3] = $base->monthToIntCMAPS(trim($spreadSheet[$s][3]));
        }*/
       // var_dump($spreadSheet);
        $complete = $chain->dailyChain($con,$table,$spreadSheet);
        
        if($complete){
            return back()->with('dailyComplete',"The Excel Data Was Succesfully Inserted :)");
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
        $chain = new chainCmaps();       
        $i = new import();
        $con = $db->openConnection('firstmatch');   
        $table = Request::get('tableFirstChain');
        $year = Request::get('year');

        $spreadSheet = $i->base();

        switch ($table) {
            case 'cmaps':
                unset($spreadSheet[0]);
                $spreadSheet = array_values($spreadSheet);
                break;   
            case 'pipeline':
                unset($spreadSheet[0]);
                $spreadSheet = array_values($spreadSheet);
                break;        
        }
        
        $complete = $chain->handler($con,$table,$spreadSheet,$year);

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
		$chain = new chainCmaps();
		$sql = new sql();

		$default = $db->defaultConnection();
        $con = $db->openConnection($default);
		$fCon = $db->openConnection('firstmatch');	
		$sCon = $db->openConnection('secondmatch');	
    	$table = Request::get('tableSecondChain');

    	$year = Request::get('year');

        $complete = $chain->secondChain($sql,$con,$fCon,$sCon,$table,$year);
        
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
		$chain = new chainCmaps();
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
		$chain = new chainCmaps();
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
