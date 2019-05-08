<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Request;
use App\dataBase;
use App\region;
use App\brand;
use App\Render;
use App\renderYoY;
use App\base;
use App\pRate;
use App\resultsMonthlyYoY;

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
    	$cols = $monthlyYoY->cols($con, $b, $region, $year,$currency, $value, $form, $source);

    	$matrix = $monthlyYoY->assemblers($base->getMonth(), $year, $b, $cols);

    	/*for ($i=0; $i < sizeof($b); $i++) { 
            $index = intval($b[$i]);
            $index -= 1;
            $brandsValueArray[$i] = $brandsValueAux[$index];
        }
        
        if (sizeof($brandsValueArray) > 1) {
            array_push($brandsValueArray, "DN");
        }

        $size = sizeof($brandsValueArray);*/

    	//return view("adSales.results.5monthlyYoYPost", compact('matrix', 'size','$brandsValueArray', 'base'));
	}

}
