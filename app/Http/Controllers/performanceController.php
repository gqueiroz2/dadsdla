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

use App\executivePerformanceRender;

class performanceController extends Controller{
    
    public function individualGet(){
        $base = new base();
        $db = new dataBase();
        $con = $db->openConnection("DLA");

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
        $db = new dataBase();
        $con = $db->openConnection("DLA");

        var_dump(Request::all());

        $region = Request::get('region');
        $year = Request::get('year');
        $tier = Request::get('brand');
        $month = Request::get('month');

    }
}
