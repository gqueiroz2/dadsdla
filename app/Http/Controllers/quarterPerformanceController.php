<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Request;
use App\dataBase;
use App\salesRep;
use App\region;
use App\brand;
use App\quarterPerformanceRender;
use App\base;
use App\pRate;
use Validator;

class quarterPerformanceController extends Controller {
    
    public function get(){

    	$db = new dataBase();
        $con = $db->openConnection("DLA");

        $render = new quarterPerformanceRender();

        $region = new region();
        $salesRegion = $region->getRegion($con);

        $sr = new salesRep();
        $salesRepGroup = $sr->getSalesRepGroup($con, null);
        $salesRep = $sr->getSalesRep($con, null);

        return view("adSales.performance.2quarterGet", compact('render', 'salesRegion', 'salesRepGroup', 'salesRep'));
    	
    }

    public function post(){

        $db = new dataBase();
        $con = $db->openConnection("DLA");

        $validator = Validator::make(Request::all(),[
            'region' => 'required',
            'year' => 'required',
            'tier' => 'required',            
            'brand' => 'required',
            'currency' => 'required',
            'value' => 'required',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $region = Request::get('region');
        $year = Request::get('year');
    	
        $tmp = Request::get("brand");
        $base = new base();
        $brands = $base->handleBrand($tmp);

        $currency = Request::get("currency");
        $p = new pRate();
        $pRate = $p->getCurrency($con, array($currency));

        $value = Request::get("value");

        var_dump($brands);
    }
}
