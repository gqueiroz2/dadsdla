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
use App\corePerformanceRender;
use App\brand;
use App\pRate;
use App\performanceCore;


class corePerformanceController extends Controller{

	public function get(){
	
        $base = new base();
        $db = new dataBase();
        $default = $db->defaultConnection();
        $con = $db->openConnection($default);
        $r = new region();
        $sr = new salesRep();
        $render = new corePerformanceRender();
        $b = new brand();
        $pr = new pRate();

        $region = $r->getRegion($con,null);
        $brand = $b->getBrand($con);
        $salesRepGroup = $sr->getSalesRepGroup($con,null);
        $currency = $pr->getCurrency($con,null);

        return view("adSales.performance.0coreGet",compact('region','salesRepGroup','render','brand','currency'));
	}

    
    public function post(){
        $base = new base();
        $db = new dataBase();
        $default = $db->defaultConnection();
        $con = $db->openConnection($default);
        $r = new region();
        $sr = new salesRep();
        $render = new corePerformanceRender();
        $b = new brand();
        $pr = new pRate();
        $p = new performanceCore();

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
        $brand = $base->handleBrand(Request::get('brand'));
        $salesRepGroup = Request::get('salesRepGroup');
        $salesRep = Request::get('salesRep');
        $currency = Request::get('currency');
        $month = Request::get('month');
        $value = Request::get('value');
        $tier = Request::get('tier');

        $mtx = $p->makeCore($con, $region, $year, $brand, $salesRepGroup, $salesRep, $currency, $month, $value, $tier);

        $region = $r->getRegion($con,null);
        $brand = $b->getBrand($con);
        $salesRepGroup = $sr->getSalesRepGroup($con,null);
        $currency = $pr->getCurrency($con,null);
        $cYear = $mtx["year"];

        $regionExcel = Request::get("region");
        
        $regionExcel = Request::get('region');
        $yearExcel = Request::get('year');
        $brandExcel = $base->handleBrand(Request::get('brand'));
        $salesRepGroupExcel = Request::get('salesRepGroup');
        $salesRepExcel = Request::get('salesRep');
        $currencyExcel = Request::get('currency');
        $monthExcel = Request::get('month');
        $valueExcel = Request::get('value');
        $tierExcel = Request::get('tier');

        $title = $mtx['region']." - Performance Core";
        $titleExcel = $mtx['region']." - Performance Core.xlsx";
        $titlePdf = $mtx['region']." - Performance Core.pdf";
        
        return view("adSales.performance.0corePost",compact('region','salesRepGroup','render','brand','currency','mtx','cYear', 'regionExcel', 'yearExcel', 'brandExcel', 'salesRepGroupExcel', 'salesRepExcel', 'currencyExcel', 'monthExcel', 'valueExcel', 'tierExcel', 'title', 'titleExcel', 'titlePdf'));
    }
}
