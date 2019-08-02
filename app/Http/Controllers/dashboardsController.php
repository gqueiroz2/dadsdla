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
use App\dashboards;

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
   		$db = new dataBase();
      $region = new region();
      $dash = new dashboards();
      $currency = new pRate();
      $b = new brand();      
      $render = new renderDashboards();
      $p = new pRate();

      $con = $db->openConnection("DLA");      
      $salesRegion = $region->getRegion($con);      
      $currencies = $currency->getCurrency($con);      
      $brands = $b->getBrand($con);      

      $regionID = Request::get("region");
      $type = Request::get("type");
      $baseFilter = json_decode( base64_decode( Request::get("baseFilter") ));
      $secondaryFilter = Request::get("secondaryFilter");
      $currency = Request::get("currency");
      $value = Request::get("value");      
/*
      var_dump($regionID);
      var_dump($type);
      var_dump($baseFilter);
      var_dump($secondaryFilter);
      var_dump($currency);
      var_dump($value);
*/

      $handle = $dash->mount($con,$p,$type,$regionID,$currency,$value,$baseFilter,$secondaryFilter);

      //$last3 = $dash->last3Years($type,$regionID,$currency,$value,$baseFilter,$secondaryFilter);


      return view("adSales.dashboards.overviewPost", compact('salesRegion', 'currencies', 'brands', 'render'));
   	}

    public function brandGet(){
      
      $db = new dataBase();
      $con = $db->openConnection("DLA");

      $region = new region();
      $salesRegion = $region->getRegion($con);

      $currency = new pRate();
      $currencies = $currency->getCurrency($con);

      $b = new brand();
      $brands = $b->getBrand($con);

      $render = new renderDashboards();

      return view("adSales.dashboards.brandGet", compact('salesRegion', 'currencies', 'brands', 'render')); 
    }

}
