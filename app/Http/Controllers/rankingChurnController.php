<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Request;
use App\base;
use App\dataBase;
use App\region;
use App\pRate;
use App\brand;
use App\renderChurnRanking;
use App\rankings;
use App\rankingChurn;
use Validator;

class rankingChurnController extends Controller {
    
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

      	$render = new renderChurnRanking();

      	return view("adSales.ranking.2churnGet", compact('salesRegion', 'currencies', 'brands', 'render')); 
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

	  	$year = Request::get('year');

	  	$cYear = $year; //intval(date('Y'));
    	$pYear = $cYear - 1;
	  	$years = array($cYear, $pYear, $pYear-1);

	  	$rc = new rankingChurn();

	  	$values = $rc->getAllResults($con, $brands, $type, $region, $rtr, $value, $pRate, $months, $years);

	  	$finalValues = array();

	  	if ($type != "agency" && $type != "client") {
            for ($r=0; $r < sizeof($values); $r++) {
                if (is_array($values[$r])) {
                    for ($r2=0; $r2 < sizeof($values[$r]); $r2++) {
                        if ($rc->existInArray($finalValues, $values[$r][$r2][$type], $type)) {
                            array_push($finalValues, $values[$r][$r2]);
                        }
                    }
                }
            }
        }else{
            for ($r=0; $r < sizeof($values); $r++) {
                if (is_array($values[$r])) {
                    for ($r2=0; $r2 < sizeof($values[$r]); $r2++) {
                        if ($rc->existInArray($finalValues, $values[$r][$r2][$type."ID"], $type, true)) {
                            array_push($finalValues, $values[$r][$r2]);  
                        }
                    }
                }
            }
        }
	  	
        $months2 = array();
        for ($m=1; $m <= sizeof($base->getMonth()); $m++) { 
            array_push($months2, $m);
        }

		$valuesTotal = $rc->getAllResults($con, $brands, $type, $region, $rtr, $value, $pRate, $months2, $years);

		$matrix = $rc->assembler($values, $finalValues, $valuesTotal, $years, $type);

		$mtx = $matrix[0];

		$total = $matrix[1];

		$rName = $rc->TruncateRegion($rtr);

		$render = new renderChurnRanking();
  		$names = $rc->createNames($type, $months, $rtr, $brands);

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

	      for ($m=1; $m < sizeof($mtx[1]); $m++) { 
	        array_push($namesExcel, $mtx[1][$m]);
	      }

	    }else{
	      $namesExcel = null;
	    }
	    
	    $title = "ranking churn (".$rtr.")";
	    $titleExcel = "ranking churn (".$rtr.").xlsx";
	    $titlePdf = "ranking churn (".$rtr.").pdf";

  		return view("adSales.ranking.2churnPost", compact('salesRegion', 'currencies', 'brand', 'type', 'brands', 'months', 'value', 'pRate', 'region', 'render', 'rName', 'mtx', 'total', 'pRate', 'names', 'rtr', 'regionExcel', 'regionNameFilter', 'typeExcel', 'brandsExcel', 'monthsExcel', 'currencyExcel', 'valueExcel', 'yearsExcel', 'title', 'titleExcel', 'titlePdf', 'namesExcel','year'));
	}
}
