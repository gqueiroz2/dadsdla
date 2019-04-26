<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Request;
use App\dataBase;
use App\region;
use App\brand;
use App\planByBrand;

class resultsYoYController extends Controller{
    
    public function YoYGet(){

        $db = new dataBase();
        $con = $db->openConnection("DLA");

        $region = new region();
        $salesRegion = $region->getRegion($con);

        $currentYear = intval(date('Y'));
        $years = array($currentYear, $currentYear-1);

        $brand = new brand();
        $brands = $brand->getBrand($con);

        $plan = new planByBrand();
        //var_dump($brands);

        return view("adSales.results.YoYGet", compact('salesRegion', 'years', 'brands'));

    }

    public function YoYPost(){
        
    }
}
