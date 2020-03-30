<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Request;
use App\dataBase;
use App\region;
use App\brand;
use App\Render;
use App\renderYoY;
use App\renderMonthlyYoY;
use App\base;
use App\pRate;
use App\resultsMonthlyYoY;
use Validator;

class resultsMonthlyYoYController extends Controller{
    
	public function get(){
		
		$db = new dataBase();
		$default = $db->defaultConnection();
        $con = $db->openConnection($default);

		$render = new Render();

		$region = new region();
		$salesRegion = $region->getRegion($con);

        $brands = new brand();
        $brandsValue = $brands->getBrand($con);

        return view("adSales.results.5monthlyYoYGet", compact('render', 'salesRegion', 'brandsValue'));
	}

	public function post(){
		
		$base = new base();

    	$db = new dataBase();
        $default = $db->defaultConnection();
        $con = $db->openConnection($default);

        $validator = Validator::make(Request::all(),[
            'region' => 'required',
            'brand' => 'required',
            'year' => 'required',
            'currency' => 'required',
            'value' => 'required',
            'firstPos' => 'required',
            'secondPos' => 'required',
            'thirdPos' => 'required',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        //seleciona as brands que foram escolhidas
        $tmp = Request::get("brand");
        $base = new base();
        $brands = $base->handleBrand($tmp);

        $b = new brand();
        $brand = $b->getBrand($con);
        
        $regionID = Request::get("region");
    	$r = new region();
    	$salesRegion = $r->getRegion($con);

    	$year = Request::get("year");
    	
    	$currency = Request::get("currency");
        $p = new pRate();
        $pRate = $p->getCurrency($con, array($currency));

        $value = Request::get("value");

    	$form = Request::get("firstPos");
    	$source = strtoupper(Request::get("secondPos"));

    	$monthlyYoY = new resultsMonthlyYoY();

        $form2 = $form;

    	//pegando valores das colunas das tabelas
        $lines = $monthlyYoY->lines($con, $pRate, $base->getMonth(), $form, $brands, $year, $regionID, $value, $source);
        
        $matrix = $monthlyYoY->assemblers($brands, $lines, $base->getMonth(), $year, $source);
        
        //var_dump($matrix[0]);

        $render = new renderMonthlyYoY();

    	if (sizeof($brands) > 1) {
            array_push($brands, array('12', 'DN'));
        }

        $form = $monthlyYoY->TruncateName($form);
        
        $region = $r->getRegion($con, array($regionID))[0]['name'];
        $rName = $monthlyYoY->TRuncateRegion($region);

        $regionExcel = $regionID;
        $yearExcel = $year;
        $firstPosExcel = $form2;
        $secondPosExcel = $source;
        $thirdPosExcel = $form2;
        $currencyExcel = $pRate;
        $valueExcel = $value;
        $brandsExcel = $brands;

        $title = $form." - YoY Month";
        $titleExcel = $form." - YoY Month.xlsx";
        $titlePdf = $form." - YoY Month.pdf";

    	return view("adSales.results.5monthlyYoYPost", compact('matrix', 'render', 'salesRegion', 'brand', 'year', 'brands', 'base', 'form', 'pRate', 'value', 'source', 'region', 'rName', 'regionExcel', 'yearExcel', 'firstPosExcel', 'secondPosExcel', 'thirdPosExcel', 'currencyExcel', 'valueExcel', 'brandsExcel', 'title', 'titleExcel', 'titlePdf'));
	}

}
