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
use App\sql;


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
      
        $render = new Render();
          
        return view("adSales.dashboards.dashboardBVGet", compact('region', 'salesRegion', 'render'));
     }
    
    public function bvPost(){
         $db = new dataBase();
         $default = $db->defaultConnection();
         $con = $db->openConnection($default);
         $a = new agency();
         $region = new region();
         $agencyGroup = Request::get('agencyGroup');
         $salesRep = Request::get('salesRep');
         $agencyGroupName = $a->getAgencyGroupByID($con,$agencyGroup,'1');
         $currency = Request::get('currency');
         $value = Request::get('value');
         //var_dump(Request::all());
         $year = (int)date("Y");
         $salesRegion = array(
            array(
               'id' => '1',
               'name' => 'Brazil',
               'role' => 'Regional Office'
            )
         );

         $render = new Render();
         $bvModel = new bvModel();
         $bvTest = $bvModel->tableBV(Request::get('agencyGroup'), $year, $con, $value,$salesRep, $currency);
         $total = $bvModel->getBVTotal($bvTest, $year);

        return view("adSales.dashboards.dashboardBVPost", compact('region','salesRegion', 'render','year','bvTest','agencyGroupName', 'total','salesRep','currency','value', 'agencyGroup'));
    }

    public function bvSaveForecast(){
      $db = new dataBase();
      $bvModel = new bvModel();
      $sql = new sql();

      $default = $db->defaultConnection();
      $con = $db->openConnection($default);

      $year = (int)date("Y");
      $value = Request::get('value');
      $currency = (int) Request::get('currency');
      $salesRep = (int) Request::get('salesRep');
      $saveButtonGet = Request::all();
      $agencyGroupId = Request::get('agencyGroup');

      // == Function to get the clients in the same way done in post == //
      $clientsByAE = $bvModel->getSalesRepByAgencyGroup($agencyGroupId, $salesRep, $year, $con, $sql);

      // == Using the size of $clientByAE we can do a for to get the correcty match for every registry get by front == //
      for ($i = 0; $i < sizeof($clientsByAE); $i++){
         $clientID = (int) $saveButtonGet['clientID-'.$i];
         $agencyID = (int) $saveButtonGet['agencyID-'.$i]; 
         $forecast = (float) $saveButtonGet['forecast-'.$i];
         $forecastSPT = (float) $saveButtonGet['forecast-spt-'.$i];
         $status = $saveButtonGet['status-'.$i];

         var_dump($salesRep, $clientID, $agencyID, $currency, $value, $forecast, $forecastSPT, $status);

         $bvModel->verifyUpdateAndSaveBV($salesRep, $clientID, $agencyID, $currency, $value, $forecast, $forecastSPT, $status, $con, $sql);
      }

      //var_dump($saveButtonGet);
    }
}
