<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Request;
use App\base;
use App\dataBase;
use App\region;
use App\pRate;
use App\brand;
use App\renderBrandRanking;
use App\rankings;
use App\rankingBrand;
use Validator;

class rankingBrandController extends Controller {
    
    public function brandGet(){
      
        $db = new dataBase();
        $default = $db->defaultConnection();
        $con = $db->openConnection($default);
        
        $region = new region();
        $salesRegion = $region->getRegion($con);

        $currency = new pRate();
        $currencies = $currency->getCurrency($con);

        $b = new brand();
        $brands = $b->getBrand($con);

        $render = new renderBrandRanking();
      
      return view("adSales.ranking.0brandGet", compact('salesRegion', 'currencies', 'brands', 'render')); 
    }

    public function brandPost(){

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
    	
    	$rb = new rankingBrand();

    	$cYear = intval(date('Y'));
        $pYear = $cYear - 1;

		$years = array($cYear, $pYear);

    	$brandsFinal = $rb->mountBrands($brands);
        
    	$values = $rb->getAllResults($con, $rtr, $region, $brandsFinal, $value, $months, $pRate, $years);
    	
        $mtx = $rb->assembler($values, $years, $brands);
    	
    	$rName = $rb->TruncateRegion($rtr);

    	$render = new renderBrandRanking();

    	$aux['id'] = '13';
    	$aux['name'] = "DN";
        
    	if (sizeof($brand) > 1) {
    		array_push($brand, $aux);
    	}
        
        $names = $rb->createNames($type, $months, $rtr, $brands);

        $regionExcel = $region;
        $typeExcel = $type;
        $brandsExcel = $brands;
        $monthsExcel = $months;
        $currencyExcel = $pRate;
        $valueExcel = $value;

        $title = "ranking brands (".$rtr.")";
        $titleExcel = "ranking brands (".$rtr.").xlsx";
        $titlePdf = "ranking brands (".$rtr.").pdf";
        
    	return view("adSales.ranking.0brandPost", compact('salesRegion', 'currencies', 'brand', 'type', 'brands', 'months', 'value', 'pRate', 'region', 'render', 'mtx', 'rName', 'rtr', 'names', 'regionExcel', 'typeExcel', 'brandsExcel', 'monthsExcel', 'currencyExcel', 'valueExcel', 'title', 'titleExcel', 'titlePdf'));
    }
}
