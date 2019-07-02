<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Request;
use App\base;
use App\dataBase;
use App\region;
use App\pRate;
use App\brand;
use App\renderDashboards;
use Validator;

class dashboardsController extends Controller{
   
   	public function overviewGet(){
   		$db = new dataBase();
        $con = $db->openConnection("DLA");

        $region = new region();
        $salesRegion = $region->getRegion($con);

        $currency = new pRate();
        $currencies = $currency->getCurrency($con);

        $b = new brand();
        $brands = $b->getBrand($con);
        $render = new renderDashboards();
        
   		return view("adSales.dashboards.overviewGet", compact('salesRegion', 'currencies', 'brands', 'render'));
   	}

   	public function overviewPost(){
   		return view("adSales.dashboards.overviewPost");
   	}

}
