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
use App\renderPlanning;
use App\base;

use App\brand;


class rollOutExcelController extends Controller{
    
	public function excelG(){
		$rC = new RenderChain();
		$rP = new renderPlanning();

		$base = new base();
        $db = new dataBase();
        $default = $db->defaultConnection();
        $conDLA = $db->openConnection($default);

        $b = new brand();

        $brand = $b->getBrand($conDLA);

		return view('planning.base.excel.get',compact('rC','rP','brand'));
	}

	public function excelP(){
		$mini = array('','A','B');
		$alphabet = range('A','Z');
 		$column = array();
		for ($i=0; $i < sizeof($mini); $i++) { 
			for ($j=0; $j < sizeof($alphabet); $j++) { 
				array_push($column,$mini[$i].$alphabet[$j]);
			}
		}
		$rC = new RenderChain();
		$rP = new renderPlanning();
		$base = new base();
        $db = new dataBase();
		$chain = new chain();		
		$i = new import();
		$ro = new rollOut();
		$default = $db->defaultConnection();
        $conDLA = $db->openConnection($default);
        
        $brandTmp = Request::get('brand');
        $brand = $base->handleBrandSS($brandTmp);

		$pattern = Request::get('pattern');

		$spreadSheet = $i->spread();

		$mergedCells = $ro->getMergedCells($spreadSheet);
		$cellsToFix = $ro->workOnMergedCells($mergedCells,$column);
		$newSpreadSheet = $ro->spreadSheetToMatrix($spreadSheet,$column);
		$newSpreadSheet = $ro->handleExcel($pattern,$newSpreadSheet,$column,$cellsToFix);

		$structure = $ro->structureSpreadsheet($newSpreadSheet,$column,$brand);

		$structureHead = array("program","brand","date","dayOfTheWeek","startHour","endHour");
		$structureHeadTable = array("Program","Brand","Date","Day Of The Week","Start Hour","End Hour");

		return view('planning.base.excel.post',compact('rC','rP','spreadSheet','column','newSpreadSheet','structure','structureHead','structureHeadTable'));

	}

}
