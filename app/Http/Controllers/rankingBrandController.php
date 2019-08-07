<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Request;
use App\base;
use App\dataBase;
use App\region;
use App\pRate;
use App\brand;
use App\renderRanking;
use App\rankings;
use App\rankingBrand;
use Validator;

class rankingBrandController extends Controller {
    
    public function brandGet(){
      
      $db = new dataBase();
      $con = $db->openConnection("DLA");

      $region = new region();
      $salesRegion = $region->getRegion($con);

      $currency = new pRate();
      $currencies = $currency->getCurrency($con);

      $b = new brand();
      $brands = $b->getBrand($con);

      $render = new renderRanking();

      return view("adSales.ranking.0brandGet", compact('salesRegion', 'currencies', 'brands', 'render')); 
    }

    public function brandPost(){

		$db = new dataBase();
      	$con = $db->openConnection("DLA");

      	$validator = Validator::make(Request::all(),[
            'region' => 'required',
            'type' => 'required',
            'month' => 'required',
            'brand' => 'required',
            'currency' => 'required',
            'value' => 'required',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $region = Request::get("region");
    	$r = new region();
    	$salesRegion = $r->getRegion($con);

    	$type = Request::get("type");

    	$tmp = Request::get("brand");
        $base = new base();
        $brands = $base->handleBrand($tmp);

        $b = new brand();
        $brand = $b->getBrand($con);

    	$months = Request::get("month");

    	$currency = Request::get("currency");
        $p = new pRate();
        $pRate = $p->getCurrency($con, array($currency));
        $currencies = $p->getCurrency($con);

    	$value = Request::get("value");
    	
    	$rb = new rankingBrand();

    	$brandsFinal = $rb->mountBrands($brands);
    	$info = $rb->mountValues($con, $r, $region);
    	$rb->getAllResults($con, $info, $region, $brandsFinal, $value, $months, $pRate);
    	//var_dump($info);
    	//return view("adSales.ranking.0brandPost");
    }
}
