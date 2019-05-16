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

        $tmp = Request::get("brand");
        $base = new base();
        $brands = $base->handleBrand($tmp);

        $b = new brand();
        $brand = $b->getBrand($con);

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

        //pegando valores das linhas das tabelas
        //pegando do banco as informações (nenhuma conta)
        $lines = $yoy->lines($con, $pRate[0]['name'], $base->getMonth(), $form, $brands, $year, $region, $value, $source);
        
        //criando matriz que será renderizada        
    	$matrix = $yoy->assemblers($brands, $lines, $base->getMonth(), $year);
        //var_dump($matrix);

    	$render = new Render();
    	$renderYoY = new renderYoY();

        $region = $r->getRegion($con, array($region));

        if (sizeof($brands) > 1) {
            array_push($brands, array('12', 'DN'));
        }

        $form = $yoy->TruncateName($form);

        //var_dump($brands);
        //var_dump($matrix);

	   	  return view("adSales.results.4YoYPost", compact('render', 'renderYoY', 'salesRegion', 'brand', 'form', 'year', 'value', 'pRate', 'matrix','brands', 'region'));
    }
}
