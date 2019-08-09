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

    	$db = new dataBase();
		
		$connections = array('firstMatch','secondMatch','thirdMatch');
		
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
		$con = $db->openConnection('firstMatch');	
		$table = Request::get('tableFirstChain');
		$year = Request::get('year');

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
            case 'fw_digital':
                unset($spreadSheet[0]);
                unset($spreadSheet[1]);
                $spreadSheet = array_values($spreadSheet);
                break;

            case 'digital':
                
                $dg = new digital();

                $spreadSheet = $dg->excelToBase($spreadSheet);

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
		$chain = new chain();
		$sql = new sql();

		$con = $db->openConnection('DLA');	
		$fCon = $db->openConnection('firstMatch');	
		$sCon = $db->openConnection('secondMatch');	
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
		$chain = new chain();
		$sql = new sql();

		$con = $db->openConnection('DLA');			
		$sCon = $db->openConnection('secondMatch');	
		$tCon = $db->openConnection('thirdMatch');	
    	$table = Request::get('tableThirdChain');
    	$year = Request::get('year');
    	$complete  = $chain->thirdChain($sql,$con,$sCon,$tCon,$table,$year);

    	if($complete){
            return back()->with('thirdChainComplete',"The Excel Data Was Succesfully Inserted :)");
        }/*else{
            return back()->with('thirdChainError',"There was and error on the insertion of the Excel Data :( ");
        }
*/
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

		$con = $db->openConnection('DLA');			
		$tCon = $db->openConnection('thirdMatch');	
    	$table = Request::get('tableToDLAChain');
    	$year = Request::get('year');
		$truncate = (bool)intval(Request::get('truncate'));

    	$complete = $chain->thirdToDLA($sql,$con,$tCon,$table,$year,$truncate);

    	if($complete){
            return back()->with('lastChainComplete',"The Excel Data Was Succesfully Inserted :)");
        }/*else{
            return back()->with('lastChainError',"There was and error on the insertion of the Excel Data :( ");
        }*/

    }

}
