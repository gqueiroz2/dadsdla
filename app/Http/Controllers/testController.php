<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Request;
use Validator;
use App\dataBase;
use App\import;
use App\chain;
use App\sql;
use App\RenderChain;

class testController extends Controller{
    
	public function controleG(){

		$rC = new RenderChain();

		return view('test.get',compact('rC'));
	}

	public function controleP(){
		
		$db = new dataBase();
		$chain = new chain();		
		$i = new import();

		$spreadSheet = $i->base();

		var_dump($spreadSheet);


	}

}
