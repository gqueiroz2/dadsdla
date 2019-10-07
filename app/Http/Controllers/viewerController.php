<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Render;
use App\dataBase;
use App\region;
use App\brand;
use App\pRate;
use App\viewer;

class viewerController extends Controller{
    
	public function baseGet(){
	
        $db = new dataBase();
        $con = $db->openConnection("DLA");

        $years = array( $cYear = intval(date('Y')) , $cYear - 1 );     
        $render = new Render();

        $r = new region();
        $region = $r->getRegion($con, NULL);


        $currency = new pRate();
        $currencies = $currency->getCurrency($con); 

        $b = new brand();
        $brand = $b->getBrand($con);

        $v = new viewer();


        return view("adSales.viewer.baseGet",compact("render","years","region","currency","currencies","brand"));
	}


	public function basePost(){
	
        $db = new dataBase();
        $con = $db->openConnection("DLA");

        $years = array($cYear = intval(date('Y')), $cYear - 1);
        $render = new Render();
        
        $sr = new salesRep();
        $salesRep = $sr->getSalesRep($con);

        $r = new region();
        $region = $r->getRegion($con, NULL);

        $currency = new pRate();
        $currencies = $currency->getCurrency($con);

        $b = new brand();
        $brand = $b->getBrand($con);

        $grossRevenue = 'gross_revenue_prate';
        $netRevenue = 'net_revenue_prate';
        $v = new viewer();



        return view("adSales.viewer.basePost", compact("years","render","region","currency","currencies","brand"));

	}

}
