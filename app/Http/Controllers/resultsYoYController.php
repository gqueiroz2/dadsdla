<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Request;
use App\dataBase;
use App\region;
use App\brand;
use App\planByBrand;
use App\cmaps;

class resultsYoYController extends Controller{
    
    public function YoYGet(){

        $db = new dataBase();
        $con = $db->openConnection("DLA");

        $region = new region();
        $salesRegion = $region->getRegion($con);

        $currentYear = intval(date('Y'));
        $years = array($currentYear, $currentYear-1);

        $brands = new brand();
        $brandsValue = $brand->getBrand($con);

        $plan = new planByBrand();
        $planValue = $plan->get($con);

        $cmaps = new cmaps();
        $cmapsValue = $cmaps->get($con);

        

        //var_dump($brands);

        return view("adSales.results.YoYGet", compact('salesRegion', 'years', 'brandsValue', 'planValue', 'cmapsValue'));

    }

    public function YoYPost(){
        
    }
}
