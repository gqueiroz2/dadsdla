<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\dataBase;
use App\base;
use App\monthly;
use App\region;
use App\salesRep;
use App\share;
use App\shareRender;
use App\brand;
use App\pRate;

class shareController extends Controller{

    public function shareGet(){
        $base = new base();
        $db = new dataBase();
        $con = $db->openConnection("DLA");
        $r = new region();
        $sr = new salesRep();
        $render = new shareRender();
        $b = new brand();
        $pr = new pRate();

        $region = $r->getRegion($con,null);
        $brand = $b->getBrand($con);
        $salesRepGroup = $sr->getSalesRepGroup($con,null);
        $salesRep = $sr->getSalesRep($con,null);
        $currency = $pr->getCurrency($con);

        return view("adSales.results.3shareGet",compact('region','salesRep','salesRepGroup','render','brand','currency'));
    }

    public function sharePost(){
    	$base = new base();
        $db = new dataBase();
        $con = $db->openConnection("DLA");
        $r = new region();
        $sr = new salesRep();
        $render = new shareRender();
        $b = new brand();
        $pr = new pRate();

        $region = $r->getRegion($con,null);
        $brand = $b->getBrand($con);
        $salesRepGroup = $sr->getSalesRepGroup($con,null);
        $salesRep = $sr->getSalesRep($con,null);
        $currency = $pr->getCurrency($con);

        return view("adSales.results.3sharePost",compact('region','salesRep','salesRepGroup','render','brand','currency'));
    }
}
