<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Request;
use App\dataBase;
use App\base;
use App\region;
use App\pRate;
use App\Render;

class resultsLATAMController extends Controller{
    public function get(){
    	$base = new base();
        $db = new dataBase();
        $default = $db->defaultConnection();
        $con = $db->openConnection($default);
        $r = new region();
        $pr = new pRate();
        $render = new Render();
        $region = $r->getRegion($con,false);
        $currency = $pr->getCurrency($con,false);

    	return view('adSales.results.6LATAMGet',compact('render','region','currency'));
    }

    public function post(){
    	$base = new base();
        $db = new dataBase();
        $default = $db->defaultConnection();
        $con = $db->openConnection($default);
        $r = new region();
        $pr = new pRate();
        $render = new Render();
        $region = $r->getRegion($con,false);
        $currency = $pr->getCurrency($con,false);
    	
    	var_dump(Request::all());
    	
    	return view('adSales.results.6LATAMPost',compact('render','region', 'currency'));

    }
}
