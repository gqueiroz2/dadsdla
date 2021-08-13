<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Request;
use App\region;
use App\pacingReport;
use App\pacingRender;
use App\salesRep;
use App\pRate;
use App\dataBase;
use App\brand;

class pacingReportController extends Controller{
    
	public function get(){
		
        $db = new dataBase();
        $default = $db->defaultConnection();
        $con = $db->openConnection($default);
        $r = new region();
        $sr = new salesRep();
        $render = new pacingRender();
        $pr = new pRate();

        $region = $r->getRegion($con,null);
        $currency = $pr->getCurrency($con,null);
		
		return view('pAndR.pacingReport.get',compact('render','region','currency'));
	}

	public function post(){
        var_dump("AKI");

		$db = new dataBase();
        $default = $db->defaultConnection();
        $con = $db->openConnection($default);
        $r = new region();
        $sr = new salesRep();
        $render = new pacingRender();
        $pr = new pRate();
        $b = new brand();
        $pc = new pacingReport();

        $brands = $b->getBrand($con);

        $region = Request::get('region');
        $year = Request::get('year');
        $currency = Request::get('currency');
        $value = Request::get('value');

        $forRender = $pc->base($con,$region,$year,$currency,$value,$brands,$pr);

        $region = $r->getRegion($con,null);
        $currency = $pr->getCurrency($con,null);

        return view('pAndR.pacingReport.post',compact('render','region','currency','brands','forRender'));
	}

}
