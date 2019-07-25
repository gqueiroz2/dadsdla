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

        $r = new region();
        $salesRegion = $r->getRegion($con);

        $sr = new salesRep();
        $salesRepGroup = $sr->getSalesRepGroup($con, null);
        $salesRep = $sr->getSalesRep($con, null);

        $matrix = $qp->makeQuarter($con, $regionID, $year, $brands, $currency, $value, $base->getMonth(), $tiers, $salesRepID);
        $mtx = $matrix[0];
        $auxTiers = $matrix[1];

        $sales = $qp->createLabels($con, $salesRepGroupID, $salesRepID, $regionID, $year);

        $region = $r->getRegion($con, array($regionID))[0]['name'];
        $rName = $qp->TRuncateRegion($region);
        //var_dump($mtx[0]);
        //var_dump($sales);

        $tiersFinal = array();

        for ($i=0; $i < sizeof($auxTiers); $i++) { 
            
            if (empty(!$auxTiers[$i])) {
                array_push($tiersFinal, $tiers[$i]);
            }
        }

        if (sizeof($brands) > 1) {
            array_push($tiersFinal, "TT");
        }

        $tiers = $tiersFinal;

        return view("adSales.performance.1quarterPost", compact('render', 'salesRegion', 'salesRepGroup', 'salesRep', 'mtx', 'rName', 'region', 'pRate', 'value', 'year', 'sales', 'tiers'));
        
    }
}
