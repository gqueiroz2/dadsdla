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
use Validator;

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
        $yoy = new resultsYoY();
        
        //var_dump(Request::all());

        //pegando valores das linhas das tabelas
        //pegando do banco as informações (nenhuma conta)
        $lines = $yoy->lines($con, $b, $region, $year,$currency, $value, $form, $source);
        
        //criando matriz que será renderizada
    	$matrix = $yoy->assemblers($brandsValue, $b, $lines, $base->getMonth(), $year);

    	$render = new Render();
    	$renderYoY = new renderYoY();

        if (sizeof($b) > 1) {
            array_push($brandsValueAux, "DN");
        }


        for ($i=0; $i < sizeof($b); $i++) { 
            $index = intval($b[$i]);
            $index -= 1;
            $brandsValueArray[$i] = $brandsValueAux[$index];
        }

        $size = sizeof($brandsValueArray);

        $region = $r->getRegion($con, array($region));

	   	return view("adSales.results.4YoYPost", compact('render', 'renderYoY', 'salesRegion', 'brandsValue', 'form', 'year', 'value', 'pRate', 'matrix','size','brandsValueArray', 'region'));
    }
}
