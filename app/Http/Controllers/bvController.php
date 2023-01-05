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


class bvController extends Controller{

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
      $agencyGroupName = $a->getAgencyGroupByID($con, $agencyGroup, '1');
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
      $bvTest = $bvModel->tableBV(Request::get('agencyGroup'), $year, $con, $value, $salesRep, $currency);
      $total = $bvModel->getBVTotal($bvTest, $year);
      $updateInfo = $bvModel->getRepAndDateofPrev($salesRep, $agencyGroup, $con);
      $list = $bvModel->listOFClients($con, $year);

      return view("adSales.dashboards.dashboardBVPost", compact('region', 'salesRegion', 'render', 'year', 'bvTest', 'agencyGroupName', 'total', 'salesRep', 'currency', 'value', 'agencyGroup','updateInfo','list'));
   }

   public function bvSaveForecast(){
      $db = new dataBase();
      $bvModel = new bvModel();
      $sql = new sql();
      $a = new agency();
      $region = new region();
      $render = new Render();
      $bvModel = new bvModel();

      $agencyGroup = Request::get('agencyGroup');
      $value = Request::get('value');
      $currency = (int) Request::get('currency');
      $salesRep = (int) Request::get('salesRep');
      $saveButtonGet = Request::all();

      $default = $db->defaultConnection();
      $con = $db->openConnection($default);
      $year = (int)date("Y");
      $salesRegion = array(
         array(
            'id' => '1',
            'name' => 'Brazil',
            'role' => 'Regional Office'
         )
      );


      // == Function to get the clients in the same way done in post == //
      $clientsByAE = $bvModel->getSalesRepByAgencyGroup($agencyGroup, $salesRep, $year, $con, $sql);

      // == Using the size of $clientByAE we can do a for to get the correcty match for every registry get by front == //
      for ($i = 0; $i < sizeof($clientsByAE); $i++) {
         $clientID = (int) $saveButtonGet['clientID-' . $i];
         $agencyID = (int) $saveButtonGet['agencyID-' . $i];
         $forecast = str_replace('.', '', $saveButtonGet['forecast-' . $i]);
         $forecastSPT = str_replace('.', '', $saveButtonGet['forecast-spt-' . $i]);
         $status = $saveButtonGet['status-' . $i];

         //var_dump($salesRep, $clientID, $agencyID, $currency, $value, $forecast, $forecastSPT, $status);

         $bvModel->verifyUpdateAndSaveBV($salesRep, $clientID, $agencyID, $agencyGroup, $currency, $value, $forecast, $forecastSPT, $status, $con, $sql);
      }

      $agencyGroupName = $a->getAgencyGroupByID($con, $agencyGroup, '1');
      $bvTest = $bvModel->tableBV(Request::get('agencyGroup'), $year, $con, $value, $salesRep, $currency);
      $total = $bvModel->getBVTotal($bvTest, $year);
      $updateInfo = $bvModel->getRepAndDateOfPrev($salesRep, $agencyGroup, $con);
      $list = $bvModel->listOFClients($con, $year);

      return view("adSales.dashboards.dashboardBVPost", compact('region', 'salesRegion', 'render', 'year', 'bvTest', 'agencyGroupName', 'total', 'salesRep', 'currency', 'value', 'agencyGroup','updateInfo','list'));
   }
}
