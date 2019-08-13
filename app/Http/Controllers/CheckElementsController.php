<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Request;
use App\dataBase;
use App\import;
use App\chain;
use App\sql;
use App\CheckElements;
use App\RenderStuff;
use App\base;

class CheckElementsController extends Controller{
    
	public function base(){
		$rS = new RenderStuff();
		$db = new dataBase();
		$cE = new CheckElements();
		$base = new base();
		$con = $db->openConnection('DLA');	
		$conFM = $db->openConnection('firstMatch');	
		$table = Request::get('table');
		$region = Request::get('region');

		$newValues = $cE->newValues($con,$conFM,$region,$table);

		$dependencies = array('regions','brands','salesReps','clients','agencies','currencies');

		return view('dataManagement.Chain.pendingStuff',compact('base','rS','con','newValues','dependencies','table','region'));

	}


	public function check(){
		$rS = new RenderStuff();
		$db = new dataBase();
		$cE = new CheckElements();
		$base = new base();
		$con = $db->openConnection('DLA');	
		$conFM = $db->openConnection('firstMatch');	
		$table = Request::get('tableToCheck');
		
		$newValues = $cE->newValuesNoRegion($con,$conFM,$table);

		var_dump($newValues);

		$dependencies = array('regions','brands','salesReps','clients','agencies','currencies');

	}
}
