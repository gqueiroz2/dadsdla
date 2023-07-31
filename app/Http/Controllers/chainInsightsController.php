<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Request;
use Validator;

use App\dataBase;
use App\import;
use App\InsightsChain;
use App\sql;
use App\digital;
use App\RenderChain;
use App\base;
use App\chain;

class chainInsightsController extends Controller{

	public function INSIGHTSGet(){
		$rC = new RenderChain();
    	return view('dataManagement.Chain.INSIGHTSGet',compact('rC'));
	}

	 public function truncate(){
    	$validator = Validator::make(Request::all(),[
            'tableTruncate' => 'required',
        ]);

        if ($validator->fails()) {
        	return back()->withErrors($validator)->withInput();
        }

    	$table = Request::get('tableTruncate'); 
        
    	$db = new dataBase();
		
		$connections = array('firstmatch','secondmatch','thirdmatch','dla');

		$truncateStatement = "TRUNCATE TABLE $table";
		//var_dump($table);
		$check = 0;

		for ($c=0; $c < sizeof($connections); $c++) { 
			$con[$c] = $db->openConnection($connections[$c]);
			if($con[$c]->query($truncateStatement) === TRUE){
                $something[$c] = "Tabela $table na dataBase ".$connections[$c]." foi Truncada ";

				$check++;
			}else{
                //
            }

		}

		if($check == sizeof($connections)){
			return back()->with('truncateChainComplete',"The table $table was succesfully truncated on all data bases :)");
		}else{
			return back()->with('truncateChainError',"There was and error on truncating the $table table on all data bases :( ");
		}

    }

    public function firstC(){

    	$validator = Validator::make(Request::all(),[
    		'file' => 'required',
            'tableFirstChain' => 'required',
        ]);

        if ($validator->fails()) {
        	return back()->withErrors($validator)->withInput();
        } 

    	$db = new dataBase();
		$chain = new chain();
        $iChain = new insightsChain();		
		$i = new import();
		$con = $db->openConnection('firstmatch');	
		$table = Request::get('tableFirstChain');
		$year = Request::get('year');

		$spreadSheet = $i->base();

		switch ($table) {
			case 'insights':
                unset($spreadSheet[0]);
                unset($spreadSheet[1]);
                unset($spreadSheet[2]);
                $spreadSheet = array_values($spreadSheet);
                //var_dump($spreadSheet);
                break;

            case 'forecast':
                unset($spreadSheet[0]);
                $spreadSheet = array_values($spreadSheet);
                //var_dump($spreadSheet);
                break;
			
		}



		$complete = $iChain->handler($con,$table,$spreadSheet,$year);

        
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
        $iChain = new insightsChain();
		$sql = new sql();

		$default = $db->defaultConnection();
        $con = $db->openConnection($default);
		$fCon = $db->openConnection('firstmatch');	
		$sCon = $db->openConnection('secondmatch');	
    	$table = Request::get('tableSecondChain');

    	$year = Request::get('year');

    	$complete = $iChain->secondC($sql,$con,$fCon,$sCon,$table,$year);
    	
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
        $iChain = new insightsChain();
		$sql = new sql();

		$default = $db->defaultConnection();
        $con = $db->openConnection($default);	

		$sCon = $db->openConnection('secondmatch');	
		$tCon = $db->openConnection('thirdmatch');	
    	$table = Request::get('tableThirdChain');
    	$year = Request::get('year');

        $complete  = $iChain->thirdC($sql,$con,$sCon,$tCon,$table,$year);

    	if($complete){
            return back()->with('thirdChainComplete',"The Excel Data Was Succesfully Inserted :)");
        }
		
    }

    public function toDLA(){
    	$validator = Validator::make(Request::all(),[
            'tableToDLAChain' => 'required',
        ]);

        if ($validator->fails()) {
        	return back()->withErrors($validator)->withInput();
        }

    	$db = new dataBase();
		$chain = new chain();
        $iChain = new insightsChain();
		$sql = new sql();

		$default = $db->defaultConnection();
        $con = $db->openConnection($default);
        		
		$tCon = $db->openConnection('thirdmatch');	
    	$table = Request::get('tableToDLAChain');
    	$year = Request::get('year');
		$truncate = (bool)intval(Request::get('truncate'));

    	$complete = $iChain->toDLA($sql,$con,$tCon,$table,$year,$truncate);

    	if($complete){
            return back()->with('lastChainComplete',"The Excel Data Was Succesfully Inserted :)");
        }

    }

}