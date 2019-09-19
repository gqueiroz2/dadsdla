<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\region;
use App\pacingRender;
use App\salesRep;
use App\pRate;
use App\dataBase;
use App\brand;

class pacingReportController extends Controller{
    
	public function get(){
		
		$db = new dataBase();
        $con = $db->openConnection("DLA");
        $r = new region();
        $sr = new salesRep();
        $render = new pacingRender();
        $pr = new pRate();

        $region = $r->getRegion($con,null);
        $currency = $pr->getCurrency($con,null);
		
		return view('pAndR.pacingReport.get',compact('render','region','currency'));
	}

	public function post(){
		$db = new dataBase();
        $con = $db->openConnection("DLA");
        $r = new region();
        $sr = new salesRep();
        $render = new pacingRender();
        $pr = new pRate();
        $b = new brand();

        $brands = $b->getBrand($con);

        $region = $r->getRegion($con,null);
        $currency = $pr->getCurrency($con,null);

		return view('pAndR.pacingReport.post',compact('render','region','currency','brands'));
	}

}
