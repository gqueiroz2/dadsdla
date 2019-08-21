<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Request;
use App\insertPlan;
use App\base;
use App\region;
use App\dataBase;

class insertController extends Controller{
    
	public function planByBrandGet(){
		return view('dataManagement.insert.planByBrandGet');
	}

	public function planByBrandPost(){
		$in = new insertPlan();
		$rtr = $in->baseBrand();
		if($rtr){
			return back()->with('insertSuccess',"There insetions was successfully made :( ");	
		}
	}

	public function planBySalesGet(){
		$db = new dataBase();
		$con = $db->openConnection("DLA");

		$r = new region();

		$region = $r->getRegion($con);
		return view('dataManagement.insert.planBySalesGet',compact('region'));
	}

	public function planBySalesPost(){
		$in = new insertPlan();
		
		$rtr = $in->baseSales();

		var_dump($rtr);

	}

	

}
