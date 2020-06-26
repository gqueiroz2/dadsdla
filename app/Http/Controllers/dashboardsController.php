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
   
   public function dashboardBVGet(){
      $db = new dataBase();
      $default = $db->defaultConnection();
      $con = $db->openConnection($default);
      $region = new region();
      $salesRegion = array(
         array(
            'id' => '1',
            'name' => 'Brazil',
            'role' => 'Regional Office'
         )
      );

      $currency = new pRate();
      $currencies = $currency->getCurrency($con);

      $b = new brand();
      $brands = $b->getBrand($con);
      $render = new renderDashboards();
        
      return view("adSales.dashboards.dashboardBVGet", compact('region','salesRegion', 'currencies', 'brands', 'render'));
   }

   public function dashboardBVPost(){
      
      $db = new dataBase();
      $region = new region();
      $dash = new dashboards();
      $currency = new pRate();
      $b = new brand();      
      $render = new renderDashboards();
      $p = new pRate();
      $mc = new makeChart();
      $base = new base();
      $default = $db->defaultConnection();
      $con = $db->openConnection($default);
      $salesRegion = array(
         array(
            'id' => '1',
            'name' => 'Brazil',
            'role' => 'Regional Office'
         )
      );

      $currencies = $currency->getCurrency($con);      
      $brands = $b->getBrand($con);      
      $regionID = Request::get("region");

      $temp = json_decode(base64_decode(Request::get("agencyGroup")));

      $agencyGroup = $temp->id;
      $agencyGroupName = $temp->name;

      $currency = Request::get("currency");
      $value = Request::get("value");


      $currencyShow = $p->getCurrency($con, array($currency))[0]['name'];
      $valueShow = strtoupper($value);

      $cYear = intval(date("Y"));
      $pYear = $cYear - 1;
      
      $years = array($cYear);
      $yearsBand = array($cYear,$pYear);
      $type = "agencyGroup";
      
      $mountBV = $dash->mountBV($con,$p,$type,$regionID,$currency,$value,$agencyGroup,$years,"cmaps");

      $forecast = $dash->forecastBV($con,$p,$type,$regionID,$currency,$value,$agencyGroup,$years);

      $bands = $dash->bandsBV($con,$p,$type,$regionID,$currency,$value,$agencyGroup,$yearsBand);

      $bvAnalisis = $dash->bvAnalisis($mountBV['current'],$bands[0]);

      $graph = $dash->excelBV($base,$mc,$mountBV,$cYear);

      $monthsMidName = array("Jan",
                              "Feb",
                              "Mar",
                              "Apr",
                              "May",
                              "Jun",
                              "Jul",
                              "Aug",
                              "Sep",
                              "Oct",
                              "Nov",
                              "Dec"
                             );

      $startMonthFcst = intval(date('m')) - 1;

      return view("adSales.dashboards.dashboardBVNoExcelPost", compact('base','region','salesRegion', 'currencies', 'brands', 'render','graph','yearsBand','cYear','agencyGroupName','bands','bvAnalisis','forecast','monthsMidName','startMonthFcst','currencyShow','valueShow'));
      
   }

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

      $handle = $dash->mount($con,$p,$type,$regionID,$currency,$value,$baseFilter,$secondaryFilter,$years,"ytd");

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
