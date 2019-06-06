<?php

namespace App\Http\Controllers;

use Validator;
use Illuminate\Support\Facades\Request;
use App\dataBase;
use App\base;
use App\monthly;
use App\region;
use App\salesRep;
use App\share;
use App\executivePerformanceRender;
use App\brand;
use App\pRate;
use App\performanceExecutive;

class executivePerformanceController extends Controller
{
    public function get(){
    	$base = new base();
        $db = new dataBase();
        $con = $db->openConnection("DLA");
        $r = new region();
        $sr = new salesRep();
        $render = new executivePerformanceRender();
        $b = new brand();
        $pr = new pRate();

        $region = $r->getRegion($con,null);
        $brand = $b->getBrand($con);
        $salesRepGroup = $sr->getSalesRepGroup($con,null);
        $currency = $pr->getCurrency($con,null);
        
        return view("adSales.performance.2executiveGet",compact('region','salesRepGroup','render','brand','currency'));
    }

    public function post(){
        $base = new base();
        $db = new dataBase();
        $con = $db->openConnection("DLA");
        $r = new region();
        $sr = new salesRep();
        $render = new executivePerformanceRender();
        $b = new brand();
        $pr = new pRate();
        $p = new performanceExecutive();

        $validator = Validator::make(Request::all(),[
            'region' => 'required',
            'year' => 'required',
            'tier' => 'required',
            'brand' => 'required',
            'salesRepGroup' => 'required',
            'salesRep' => 'required',
            'currency' => 'required',
            'value' => 'required',
            'month' => 'required',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $mtx = $p->makeMatrix($con);

        $region = $r->getRegion($con,null);
        $brand = $b->getBrand($con);
        $salesRepGroup = $sr->getSalesRepGroup($con,null);
        $currency = $pr->getCurrency($con,null);
        
        return view("adSales.performance.2executivePost",compact('region','salesRepGroup','render','brand','currency','mtx'));
    }
}
