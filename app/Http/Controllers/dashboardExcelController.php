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

class dashboardExcelController extends Controller{
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

      $region = Request::get("regionExcel");

      $temp = json_decode(base64_decode(Request::get("agencyExcel")));

      $currency

      $value
}
