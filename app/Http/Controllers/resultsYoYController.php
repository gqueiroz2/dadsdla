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

class resultsYoYController extends Controller{

    public function get(){

        $db = new dataBase();
        $con = $db->openConnection("DLA");

        $render = new Render();

        $region = new region();
        $salesRegion = $region->getRegion($con);

        $brands = new brand();
        $brandsValue = $brands->getBrand($con);

        return view("adSales.results.YoYGet", compact('render', 'salesRegion', 'brandsValue'));

    }

    public function post(){

    	$base = new base();

    	$db = new dataBase();
        $con = $db->openConnection("DLA");

        //seleciona as brands que foram escolhidas
        $brand = Request::get("brand");
        $brands = new brand();
        $b = $base->handleBrand($con,$brands,$brand);

    	$region = Request::get("region");
    	$year = Request::get("year");
    	
    	$currency = Request::get("currency");
    	$value = Request::get("value");

    	$form = Request::get("firstPos");

        $yoy = new resultsYoY();

    	/*$columns = array("campaign_sales_office_id", "brand_id", "year", "month");
    	$values = array(1, 2, 2019, 1);

    	$mini = new mini_header();
    	$res = $mini->sum($con, 'gross_revenue', $columns, $values, $region, $year);*/
    	
        $yoy = new resultsYoY();

        $p = new pRate();
        $pRate = $p->getCurrency($con, array($currency));
		var_dump($currency);//var_dump($pRate);

        //pegando valores das linhas das tabelas
        /*$lines = $yoy->lines($con, $b, $region, $year, $value, $form);
        
    	$matrix = $yoy->assemblers($b, $lines, $base->getMonth(), $year);*/
    }
}
