<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Request;
use App\Render;
use App\dataBase;
use App\base;
use App\monthly;
use App\region;
use App\salesRep;
use App\share;
use App\shareRender;
use App\brand;
use App\pRate;


class resultsResumeController extends Controller{
    
	public function get(){
		$base = new base();
        $db = new dataBase();
        $con = $db->openConnection("DLA");
        $r = new region();
        $sr = new salesRep();
        $b = new brand();
        $pr = new pRate();
		$render = new Render();

		$region = $r->getRegion($con,null);
        $brand = $b->getBrand($con);
        $salesRepGroup = $sr->getSalesRepGroup($con,null);
        $salesRep = $sr->getSalesRep($con,null);
        $currency = $pr->getCurrency($con);

		return view('adSales.results.0resumeGet',compact('render','region','brand','salesRepGroup','salesRep','currency'));

	}

	public function post(){
		var_dump(Request::all());
	}

}
