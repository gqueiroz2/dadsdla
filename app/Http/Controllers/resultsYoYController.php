<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Request;
use App\dataBase;
use App\region;
use App\brand;
use App\Render;
use App\resultsYoY;
use App\base;
use App\pRate;
use App\renderYoY;

class resultsYoYController extends Controller{

    public function get(){

        $db = new dataBase();
        $con = $db->openConnection("DLA");

        $render = new Render();

        $region = new region();
        $salesRegion = $region->getRegion($con);

        $brands = new brand();
        $brandsValue = $brands->getBrand($con);

        return view("adSales.results.4YoYGet", compact('render', 'salesRegion', 'brandsValue'));

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
    	$value = Request::get("value");

    	$form = Request::get("firstPos");
    	$source = strtoupper(Request::get("secondPos"));
        $yoy = new resultsYoY();
        
        //pegando valores das linhas das tabelas
        $lines = $yoy->lines($con, $b, $region, $year, $value, $form, $source);
        
        //criando matriz que será renderizada
    	$matrix = $yoy->assemblers($b, $lines, $base->getMonth(), $year);

    	$render = new Render();
    	$renderYoY = new renderYoY();

    	$brandsValueArray = array();
    	for ($i=0; $i < sizeof($b); $i++) { 
    		$index = intval($b[$i]);
    		$index -= 1;
    		$brandsValueArray[$i] = $brandsValueAux[$index];
    	}

    	//var_dump($matrix);

	   	return view("adSales.results.4YoYPost", compact('render', 'renderYoY', 'salesRegion', 'brandsValue', 'brandsValueArray', 'form', 'year', 'value', 'currency', 'matrix'));
    }
}
