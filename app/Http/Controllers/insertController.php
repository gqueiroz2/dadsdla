<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Request;
use App\insertPlan;
use App\base;
use App\region;
use App\dataBase;
use Validator;

class insertController extends Controller{
    
	public function planByBrandGet(){

		$cYear = intval(date('Y'));
		$pYear = $cYear - 1;
		$ppYear = $pYear - 1;

		$years = array($cYear,$pYear,$ppYear);

		return view('dataManagement.insert.planByBrandGet',compact('years'));
	}

	public function planByBrandPost(){
		$validator = Validator::make(Request::all(),[
        	'year' => 'required',
        	'source' => 'required'     	
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

		$in = new insertPlan();
		$rtr = $in->baseBrand();
		if($rtr){
			return back()->with('insertSuccess',"There insetions was successfully made :( ");	
		}
	}

	public function planBySalesGet(){
		$cYear = intval(date('Y'));
		$pYear = $cYear - 1;
		$ppYear = $pYear - 1;

		$years = array($cYear,$pYear,$ppYear);

		$db = new dataBase();
		$con = $db->openConnection("DLA");

		$r = new region();

		$region = $r->getRegion($con);
		return view('dataManagement.insert.planBySalesGet',compact('region','years'));
	}

	public function planBySalesPost(){

		$validator = Validator::make(Request::all(),[
        	'year' => 'required',
        	'region' => 'required'     	
        ]);

		if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
		
		$in = new insertPlan();
		
		$rtr = $in->baseSales();

		var_dump($rtr);

	}

	

}
