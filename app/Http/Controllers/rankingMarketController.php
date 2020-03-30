<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Request;
use App\base;
use App\dataBase;
use App\region;
use App\pRate;
use App\brand;
use App\renderMarketRanking;
use App\rankings;
use App\rankingMarket;
use Validator;

class rankingMarketController extends Controller {

	public function get(){
		
		$db = new dataBase();
  	$default = $db->defaultConnection();
    $con = $db->openConnection($default);

  	$region = new region();
  	$salesRegion = $region->getRegion($con);

  	$currency = new pRate();
  	$currencies = $currency->getCurrency($con);

  	$b = new brand();
  	$brands = $b->getBrand($con);

  	$render = new renderMarketRanking();
  
  	return view("adSales.ranking.1marketGet", compact('salesRegion', 'currencies', 'brands', 'render')); 
	}

	public function post(){
		
		$db = new dataBase();
  	$default = $db->defaultConnection();
    $con = $db->openConnection($default);

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
  	$tmp = $r->getRegion($con,array($region));

      if(is_array($tmp)){
          $rtr = $tmp[0]['name'];
      }else{
          $rtr = $tmp['name'];
      }

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
  	
  	$rm = new rankingMarket();

  	$cYear = intval(date('Y'));
    $pYear = $cYear - 1;

  	$years = array($cYear, $pYear);

  	$values = $rm->getAllResults($con, $brands, $type, $region, $rtr, $value, $pRate, $months, $years);
  	
		$matrix = $rm->assembler($values, $years, $type);

  	$mtx = $matrix[0];
  	$total = $matrix[1];
    
  	$rName = $rm->TruncateRegion($rtr);

  	$render = new renderMarketRanking();
  	$names = $rm->createNames($type, $months, $rtr, $brands);

    $regionExcel = $region;
    $regionNameFilter = $rtr;
    $typeExcel = $type;
    $brandsExcel = $brands;
    $monthsExcel = $months;
    $currencyExcel = $pRate;
    $valueExcel = $value;
    $yearsExcel = $years;
    
    if ($type == "sector") {

      $namesExcel = array();

      for ($v=0; $v < sizeof($values); $v++) { 
        for ($v2=0; $v2 < sizeof($values[$v]); $v2++) { 
          array_push($namesExcel, $values[$v][$v2][$type]);
        }
      }

      $namesExcel = array_values(array_unique($namesExcel));
      
    }else{
      $namesExcel = null;
    }

    $title = "ranking market (".$rtr.")";
    $titleExcel = "ranking market (".$rtr.").xlsx";
    $titlePdf = "ranking market (".$rtr.").pdf";

  	return view("adSales.ranking.1marketPost", compact('salesRegion', 'currencies', 'brand', 'type', 'brands', 'months', 'value', 'pRate', 'region', 'render', 'rName', 'mtx', 'total', 'pRate', 'names', 'rtr', 'regionExcel', 'regionNameFilter', 'typeExcel', 'brandsExcel', 'monthsExcel', 'currencyExcel', 'valueExcel', 'yearsExcel', 'namesExcel', 'title', 'titleExcel', 'titlePdf'));

  
	}

}