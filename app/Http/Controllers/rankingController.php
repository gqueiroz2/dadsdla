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
use App\subRankings;
use Validator;

class rankingController extends Controller {

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

        $render = new renderRanking();

        return view('adSales.ranking.3rankingGet', compact('salesRegion', 'currencies', 'brands', 'render'));
    }

    public function post(){

    	$base = new base();

    	$db = new dataBase();
        $default = $db->defaultConnection();
        $con = $db->openConnection($default);

        $validator = Validator::make(Request::all(),[
            'region' => 'required',
            'type' => 'required',
            'type2' => 'required',
            'nPos' => 'required',
            'month' => 'required',
            'brand' => 'required',
            'firstPos' => 'required',
            'secondPos' => 'required',
            'thirdPos' => 'required',
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
    	$temp = Request::get("type2");

        for ($t=0; $t < sizeof($temp); $t++) { 
            $type2[$t] = json_decode(base64_decode($temp[$t]));
        }

    	$nPos = Request::get("nPos");

    	$months = Request::get("month");

    	$tmp = Request::get("brand");
        $base = new base();
        $brands = $base->handleBrand($tmp);

        $b = new brand();
        $brand = $b->getBrand($con);

        $firstForm = Request::get("firstPos");
        $secondForm = Request::get("secondPos");
        $thirdForm = Request::get("thirdPos");

    	$currency = Request::get("currency");
        $p = new pRate();
        $pRate = $p->getCurrency($con, array($currency));
        $currencies = $p->getCurrency($con);

    	$value = Request::get("value");

    	$r = new rankings();

        $years = $r->createPositions($firstForm, $secondForm, $thirdForm);

        $values = $r->getAllResults($con, $brands, $type, $region, $value, $pRate, $months, $years, $type2);

        $filterValues = $r->filterValues($values, $type2, $type);
        
        $mtx = $r->assembler($values, $type2, $years, $type, $filterValues);

        if ($nPos == 'All') {
            $size = sizeof($mtx[0]);
        }else{
            $size = ($nPos+1);
        }
        
        $total = $r->assemblerTotal($mtx, $years, $size);
        
        $names = $r->createNames2($type, $months, $years);

        $render = new renderRanking();

        $rName = $r->TruncateRegion($rtr);

        $subR = new subRankings();
        
        $regionExcel = $region;
        $typeExcel = $type;
        $type2Excel = base64_encode(json_encode($temp));
        $brandsExcel = $brands;
        $monthsExcel = $months;
        $firstFormExcel = $firstForm;
        $secondFormExcel = $secondForm;
        $thirdFormExcel = $thirdForm;
        $currencyExcel = $pRate;
        $nPosExcel = $nPos;
        $valueExcel = $value;

        $title = "ranking (".$rtr.")";
        $titleExcel = "ranking (".$rtr.").xlsx";
        $titlePdf = "ranking (".$rtr.").pdf";

        return view('adSales.ranking.3rankingPost', compact('con','subR','salesRegion', 'currencies', 'brand', 'render', 'mtx', 'names', 'pRate', 'value', 'total', 'size', 'type', 'months', 'brands', 'years', 'pRate', 'region', 'rName', 'regionExcel', 'typeExcel', 'type2Excel', 'brandsExcel', 'firstFormExcel', 'secondFormExcel', 'thirdFormExcel', 'currencyExcel', 'monthsExcel', 'nPosExcel', 'valueExcel', 'title', 'titleExcel', 'titlePdf'));

    }
}