<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Request;
use App\dataBase;
use App\salesRep;
use App\region;
use App\brand;
use App\quarterPerformance;
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

        return view("adSales.performance.1quarterGet", compact('render', 'salesRegion', 'salesRepGroup', 'salesRep'));
    	
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

        $regionID = Request::get('region');
        $year = Request::get('year');

        $tiers = Request::get('tier');
    	
        $tmp = Request::get("brand");
        $base = new base();
        $brands = $base->handleBrand($tmp);

        $currency = Request::get("currency");
        $p = new pRate();
        $pRate = $p->getCurrency($con, array($currency));

        $value = Request::get("value");

        $salesRepGroupID = Request::get("salesRepGroup");
        $salesRepID = Request::get("salesRep");

        $qp = new quarterPerformance();

        $render = new quarterPerformanceRender();

        $region = new region();
        $salesRegion = $region->getRegion($con);

        $sr = new salesRep();
        $salesRepGroup = $sr->getSalesRepGroup($con, null);
        $salesRep = $sr->getSalesRep($con, null);

        $mtx = $qp->makeQuarter($con, $regionID, $year, $brands, $currency, $value, $base->getMonth(), $salesRepGroupID, $salesRepID, $tiers, 
            $sr->getSalesRepGroup($con, array($regionID)), 
            $sr->getSalesRepByRegion($con, array($regionID),true,$year)
        );

        /*var_dump($mtx);
        var_dump($sales);*/

        return view("adSales.performance.1quarterPost", compact('render', 'salesRegion', 'salesRepGroup', 'salesRep', 'mtx'));
        
    }
}
