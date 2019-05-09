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
		$con = $db->openConnection("DLA");

		$render = new Render();
		$renderYoY = new renderYoY();

		$region = new region();
		$salesRegion = $region->getRegion($con);

        $brands = new brand();
        $brandsValue = $brands->getBrand($con);

        return view("adSales.results.5monthlyYoYGet", compact('render', 'renderYoY', 'salesRegion', 'brandsValue'));
	}

	public function post(){
		
		$base = new base();

    	$db = new dataBase();
        $con = $db->openConnection("DLA");

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
        $brand = Request::get("brand");
        $brands = new brand();
        $brandsValue = $brands->getBrand($con);
        $brandsValueAux = $base->getBrands();
        $b = $base->handleBrand($con,$brands,$brand);

        $region = Request::get("region");
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

    	//pegando valores das colunas das tabelas
    	//$cols = $monthlyYoY->cols($con, $b, $region, $year,$currency, $value, $form, $source);
        $lines = $monthlyYoY->lines($con, $b, $region, $year,$currency, $value, $form, $source);
        //var_dump($lines);
    	//$matrix = $monthlyYoY->assemblers($base->getMonth(), $year, $b, $cols);
        $matrix = $monthlyYoY->assemblers($brandsValue, $b, $lines, $base->getMonth(), $year);
        //var_dump($matrix);

    	if (sizeof($b) > 1) {
            array_push($brandsValueAux, "DN");
        }

        for ($i=0; $i < sizeof($b); $i++) { 
            $index = intval($b[$i]);
            $index -= 1;
            $brandsValueArray[$i] = $brandsValueAux[$index];
        }

        $size = sizeof($brandsValueArray);

        $render = new Render();
        $renderYoY = new renderYoY();
        $renderMonthlyYoY = new renderMonthlyYoY();

    	return view("adSales.results.5monthlyYoYPost", compact('matrix', 'render', 'renderYoY', 'renderMonthlyYoY', 'salesRegion', 'brandsValue', 'year', 'size','brandsValueArray', 'base', 'form', 'pRate', 'value'));
	}

}

/*
<tr>{{ $renderMonthlyYoY->renderModalHeader("dc", "darkBlue") }}</tr>
                        <tr>{{ $renderMonthlyYoY->renderModalHeader2($year, "dc", "darkBlue")}}</tr>

@for($i = 0; $i < sizeof($brandsValueArray); $i++)
                            <tr>
                                {{
                                    $renderMonthlyYoY->renderDataModal($brandsValueArray[$i], $matrix[1], $i, "dc", "rcBlue", "white", "medBlue") 
                                }}
                            </tr>
                        @endfor*/
