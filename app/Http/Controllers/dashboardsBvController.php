<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Request;

use App\dataBase;

use App\region;
use App\pRate;
use App\brand;


class dashboardsBvController extends Controller{
    
    public function bvGet{
   	  $db = new dataBase();
         
      $default = $db->defaultConnection();
      $con = $db->openConnection($default);

      $region = new region();
      $salesRegion = $region->getRegion($con);

      $currency = new pRate();
      $currencies = $currency->getCurrency($con);

      $b = new brand();
      $brands = $b->getBrand($con);
        
	
	return view("adSales.dashboards.overviewGet", compact('region','salesRegion', 'currencies', 'brands', 'render'));
    }
}
