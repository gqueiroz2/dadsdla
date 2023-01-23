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
use App\makeChart;

class dashboardsController extends Controller{

	public function overviewGet(){

		$db = new dataBase();
         
      $default = $db->defaultConnection();
      $con = $db->openConnection($default);

      $region = new region();
      $salesRegion = $region->getRegion($con);

      $currency = new pRate();
      $currencies = $currency->getCurrency($con);

      $b = new brand();
      $brands = $b->getBrand($con);
      $render = new renderDashboards();
        
		return view("adSales.dashboards.overviewGet", compact('region','salesRegion', 'currencies', 'brands', 'render'));
	}

	public function overviewPost(){

   	$db = new dataBase();
      $region = new region();
      $dash = new dashboards();
      $currency = new pRate();
      $b = new brand();      
      $render = new renderDashboards();
      $p = new pRate();
      $mc = new makeChart();
   
      $default = $db->defaultConnection(); 
      $con = $db->openConnection($default);

      $salesRegion = $region->getRegion($con);      
      $currencies = $currency->getCurrency($con);      
      $brands = $b->getBrand($con);      

      $regionID = Request::get("region");
      $type = Request::get("type");
      $baseFilter = json_decode( base64_decode( Request::get("baseFilter") ));
      $secondaryFilter = Request::get("secondaryFilter");
      $currency = Request::get("currency");
      $value = Request::get("value");

      $validator = Validator::make(Request::all(),[
         'region' => 'required',
         'type' => 'required',
         'baseFilter' => 'required',
         'secondaryFilter' => 'required',
         'currency' => 'required',
         'value' => 'required',
     ]);

     if ($validator->fails()) {
         return back()->withErrors($validator)->withInput();
     }

      $cYear = intval(date("Y"));
      $pYear = $cYear - 1;
      $ppYear = $pYear - 1;
      $years = array($cYear,$pYear,$ppYear);
      
      $handle = $dash->mount($con,$p,$type,$regionID,$currency,$value,$baseFilter,$secondaryFilter,$years);

      $last3YearsChild = $handle['last3YearsChild'];
      $last3YearsByMonth = $handle['last3YearsByMonth'];
      $last3YearsByBrand = $handle['last3YearsByBrand'];

      for ($y=0; $y < sizeof($years); $y++) { 
        $brandChart[$y] = $mc->overviewBrand($con,$last3YearsByBrand[$y]);
      }

      $temp = $mc->overviewBrandColumn($con,$last3YearsByBrand,$years);

      $brandChartColumn = $temp['string'];
      $maxChartColumn = $temp['max'];

      $childChart = $mc->overviewChild($con,$type,$last3YearsChild,$years);

      $monthChart = $mc->overviewMonth($con,$type,$last3YearsByMonth,$years);

      if ($value == "gross") {
         $valueView = "Gross";
      }elseif ($value == "net") {
         $valueView = "Net";
      }

      $currency = array($currency);

      $currencyView = $p->getCurrency($con,$currency)[0]['name'];

      return view("adSales.dashboards.overviewPost", compact('con' , 'salesRegion', 'currencies', 'brands', 'render' , 'handle' , 'type' , 'baseFilter' , 'secondaryFilter' , 'brandChart' , 'childChart' , 'monthChart' ,  'brandChartColumn' , 'maxChartColumn' , 'years', 'valueView', 'currencyView'));
	}

}
