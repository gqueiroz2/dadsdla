<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Request;
use App\dataBase;
use App\region;
use App\brand;
use App\planByBrand;
use App\cmaps;
use App\header;
use App\ytd;
use App\Render;
use App\pRate;

class resultsYoYController extends Controller{

    public function YoYGet(){

        $db = new dataBase();
        $con = $db->openConnection("DLA");

        $render = new Render();

        $region = new region();
        $salesRegion = $region->getRegion($con);

        $brands = new brand();
        $brandsValue = $brands->getBrand($con);

        $plan = new planByBrand();
        $planValue = $plan->get($con);

        $cmaps = new cmaps();
        $cmapsValue = $cmaps->get($con);

        $header = new header();
        $headerValue = $header->get($con);

        $ytd = new ytd();
        $ytdValue = $ytd->get($con);

        //var_dump($planValue);

        return view("adSales.results.YoYGet", compact('render', 'salesRegion', 'brandsValue', 'planValue', 'cmapsValue',
         'headerValue', 'ytdValue'));

    }

    public function YoYPost(){
        
        $all = Request::all();
        var_dump($all);

    }
}
