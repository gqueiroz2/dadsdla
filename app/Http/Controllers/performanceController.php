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

use App\individualRender;

class performanceController extends Controller{
    
    public function individualGet(){

    	$base = new base();
    	$db = new dataBase();
    	$con = $db->openConnection("DLA");
    	$render = new individualRender();

    	$r = new region();
    	$sr = new salesRep();
    	$b = new brand();
    	$p = new pRate();

    	$region = $r->getRegion($con,null);
        $brand = $b->getBrand($con);
        $salesRepGroup = $sr->getSalesRepGroup($con,null);
        $currency = $p->getCurrency($con,null);

        return view("adSales.performance.individualGet", compact('region', 'salesRepGroup', 'render', 'brand', 'currency'));
    }
}
