<?php

namespace App\Http\Controllers;

use Validator;
use Illuminate\Support\Facades\Request;

use App\dataBase;
use App\base;
use App\pRate;

use App\region;
use App\salesRep;
use App\brand;
use App\performanceIndividual;

use App\executivePerformanceRender;
use App\Render;

class performanceController extends Controller{
    
    public function individualGet(){
        $base = new base();
        $db = new dataBase();
        $default = $db->defaultConnection();
        $con = $db->openConnection($default);

        $r = new region();
        $sr = new salesRep();
        $b = new brand();

        $pr = new pRate();
        $render = new executivePerformanceRender();
        
        $region = $r->getRegion($con,null);
        $brand = $b->getBrand($con);
        $salesRepGroup = $sr->getSalesRepGroup($con,null);
        $currency = $pr->getCurrency($con,null);

        return view("adSales.testePerformance.individualGet", compact('region', 'salesRepGroup', 'render', 'brand', 'currency'));
    }

    public function individualPost(){
        $base = new base();
        $b = new brand();
        $db = new dataBase();
        $sr = new salesRep();
        $p = new pRate();
        $r = new region();
        $render = new executivePerformanceRender();
        $pIndividual = new performanceIndividual();
        $default = $db->defaultConnection();
        $con = $db->openConnection($default);

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

        $region = Request::get('region');
        $year = Request::get('year');
        $tier = Request::get('tier');
        $month = Request::get('month');
        $brand = Request::get('brand');
        $salesRepGroup = Request::get('salesRepGroup');
        $salesRep = Request::get('salesRep');
        $currency = Request::get('currecy');
        $value = Request::get('value');

        $currencies = $p->getCurrency($con,$currency)[0]['name'];
        $brand = $b->getBrand($con);
        $srGroup = $sr->getSalesRepGroup($con,null);
        $regions = $r->getRegion($con,array($region))[0]['name'];

        $mtx = $pIndividual->matrix($con,$region,$year,$tier,$salesRep,$salesRepGroup,$currency,$month,$value);

        //return view("adSales.testePerformance.individualPost", compact('mtx','regions','srGroup','currencies','value','currency','salesRep','salesRepGroup','brand','month','tier','year','region','render'));
    }
}
