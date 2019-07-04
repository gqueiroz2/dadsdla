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
use Validator;

class rankingController extends Controller {
    
    public function get(){
    	
    	$db = new dataBase();
        $con = $db->openConnection("DLA");

        $region = new region();
        $salesRegion = $region->getRegion($con);

        $currency = new pRate();
        $currencies = $currency->getCurrency($con);

        $b = new brand();
        $brands = $b->getBrand($con);

        $render = new renderRanking();

        return view('adSales.ranking.0rankingGet', compact('salesRegion', 'currencies', 'brands', 'render'));
    }

    public function post(){
    	
        //var_dump(Request::all());

    	$base = new base();

    	$db = new dataBase();
        $con = $db->openConnection("DLA");


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

    	$type = Request::get("type");
    	$temp = Request::get("type2");

        for ($t=0; $t < sizeof($temp); $t++) { 
            $type2[$t] = json_decode(base64_decode($temp[$t]));
        }

        /*
    	for ($t=0; $t < sizeof($type2); $t++) { 
    		$type2[$t] = base64_decode($type2[$t]);
    	}
    	*/

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

    	$value = Request::get("value");

    	$r = new rankings();

        $years = $r->createPositions($firstForm, $secondForm, $thirdForm);

        $values = $r->getAllResults($con, $brands, $type, $region, $value, $pRate, $months, $years);
       
        $filterValues = $r->filterValues($values, $type2, $type);

        /*$somao = 0;
        for ($f=0; $f < sizeof($filterValues); $f++) { 
            if($filterValues[$f] == 1){
                $somao++;
            }
        }

        var_dump($somao);*/
        //var_dump($type);
        //$all = $r->verifyQuantity($con, $type, $type2, $region);
        
        if ($nPos == 'All') {
            $size = sizeof($type2);
        }else{
            $size = $nPos;
        }

        $mtx = $r->assembler($values, $type2, $years, $type, $filterValues, $size);
        var_dump($mtx);

    }
}

