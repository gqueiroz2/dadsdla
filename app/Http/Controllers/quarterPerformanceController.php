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

        $b = new brand();
        $brands = $b->getBrand($con);

        $sr = new salesRep();
        $salesRepGroup = $sr->getSalesRepGroup($con, null);
        $salesRep = $sr->getSalesRep($con, null);

        //var_dump($salesRepGroup);
        return view("adSales.performance.2quarterGet", compact('render', 'salesRegion', 'brands', 'salesRepGroup', 'salesRep'));
    	
    }

    public function post(){
    	
    	var_dump("expression");
    }
}
