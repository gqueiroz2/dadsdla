<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Request;
use App\dataBase;
use App\import;
use App\chain;
use App\sql;
use App\CheckElements;
use App\RenderStuff;

class CheckElementsController extends Controller{
    
	public function base(){
		$rS = new RenderStuff();
		$db = new dataBase();
		$cE = new CheckElements();

		$conDLA = $db->openConnection('DLA');	
		$con = $db->openConnection('firstMatch');	

		$table = Request::get('table');
		$newValues = $cE->newValues($conDLA,$con,$table);

		$dependencies = array('regions','brands','salesReps','clients','agencies','currencies');

		

		return view('dataManagement.Chain.pendingStuff',compact('rS','newValues','dependencies','table'));

	}
}
