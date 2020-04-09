<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Request;
use Validator;
use App\dataBase;
use App\import;
use App\chain;
use App\sql;
use App\RenderChain;
use App\rollOut;

class rollOutExcelController extends Controller{
    
	public function excelG(){
		$rC = new RenderChain();

		return view('planning.base.excel.get',compact('rC'));
	}

	public function excelP(){
		var_dump("Excel Post");

		$db = new dataBase();
		$chain = new chain();		
		$i = new import();
		$ro = new rollOut();

		$pattern = Request::get('pattern');

		$spreadSheet = $i->base();

		$sheet = $ro->handleExcel($pattern,$spreadSheet);

		var_dump($spreadSheet);

	}

}
