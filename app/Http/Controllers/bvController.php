<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Request;
use App\Render;
use App\dataBase;
use App\region;
use App\pRate;
use App\brand;
use App\agency;
use App\bvModel;

class bvController extends Controller {

    public function bvGet(){
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
        $render = new Render();
          
        return view("adSales.dashboards.dashboardBVGet", compact('region','salesRegion', 'currencies', 'brands', 'render'));
     }
    
    public function bvPost(){
         $db = new dataBase();
         $default = $db->defaultConnection();
         $con = $db->openConnection($default);
         $a = new agency();
         $region = new region();
         $agencyGroup = Request::get('agencyGroup');
         $agencyGroupName = $a->getAgencyGroupByID($con,$agencyGroup,'1');
         
         $year = (int)date("Y");
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
         $render = new Render();
         $bvModel = new bvModel();
         $bvTest = $bvModel->tableBV(Request::get('agencyGroup'), $year, $con, Request::get('value'));

        return view("adSales.dashboards.dashboardBVPost", compact('region','salesRegion', 'currencies', 'brands', 'render','year','bvTest','agencyGroupName'));
    }
}
