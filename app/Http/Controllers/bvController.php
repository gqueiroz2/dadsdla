<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Request;
use App\Render;
use App\dataBase;
use App\region;
use App\salesRep;
use App\pRate;
use App\brand;
use App\base;
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
      $sql = new sql();
      $default = $db->defaultConnection();
      $con = $db->openConnection($default);
      $a = new agency();
      $region = new region();
      $agencyGroup = Request::get('agencyGroup');
      $salesRep = Request::get('salesRep');
      $agencyGroupName = $a->getAgencyGroupByID($con, $agencyGroup, '1');
      $currency = Request::get('currency');
      $value = Request::get('value');
      
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

      $newClient = $bvModel->getSalesRepByClient($agencyGroup, $salesRep,$con, $sql);

      $bvTest = $bvModel->tableBV($agencyGroup, $year, $con, $value, $salesRep, $currency);
      $total = $bvModel->getBVTotal($bvTest, $year);
      $updateInfo = $bvModel->getRepAndDateofPrev($salesRep, $agencyGroup, $con);
      $list = $bvModel->listOFClients($con, $year);

      $title = "Control Panel - BV";
      $titleExcel = "Control Panel - BV.xlsx";

      
      for ($b=0; $b <sizeof($bvTest) ; $b++) { 
         $color[$b] = 'even';

         if ($newClient) {
            for ($c=0; $c <sizeof($newClient) ; $c++) { 
               if ($bvTest[$b]['clientId'] == $newClient[$c]['client']) {
                  $tmpColor = true;
               }else{
                  $tmpColor = false;
               }

               if ($tmpColor) {
                  $color[$b] = 'oddGrey';
               }else{
                  $color[$b] = 'even';
               }
            }
         }      
      }

      return view("adSales.dashboards.dashboardBVPost", compact('region', 'salesRegion', 'render', 'year', 'bvTest', 'agencyGroupName', 'total', 'salesRep', 'currency', 'value', 'agencyGroup','updateInfo','list','color','title', 'titleExcel'));
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
      //var_dump(Request::all());
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
      $newClient = $bvModel->getSalesRepByClient($agencyGroup, $salesRep,$con, $sql);

      $clientsByAE = $bvModel->getSalesRepByAgencyGroup($agencyGroup, $salesRep, $year, $con, $sql);

      // == Getting and saving any new client inclusion by salesRep == //
      if ($saveButtonGet['client'][0] != null) {
         //var_dump('aki');
         //var_dump($saveButtonGet['client'][0]);
         $test = explode(',', $saveButtonGet['client'][0]);
         //var_dump($test);
         $saveNewClient = $bvModel->newClientInclusion($con,$agencyGroup,$salesRep,$test[0],$test[1]);
      

      }else{
          if ($newClient != null)  {
            $clientsByAE = array_merge($clientsByAE,$newClient);
            $clientsByAE = array_values($clientsByAE);
         }
         
      }      

      // == Using the size of $clientByAE we can do a for to get the correcty match for every registry get by front == //
      for ($i = 0; $i < sizeof($clientsByAE); $i++) {
         $clientID = (int) $saveButtonGet['clientID-' . $i];
         $agencyID = (int) $saveButtonGet['agencyID-' . $i];
         $forecast = str_replace('.', '', $saveButtonGet['forecast-' . $i]);
         $forecastSPT = str_replace('.', '', $saveButtonGet['forecast-spt-' . $i]);
         $status = $saveButtonGet['status-' . $i];

         $bvModel->verifyUpdateAndSaveBV($salesRep, $clientID, $agencyID, $agencyGroup, $currency, $value, $forecast, $forecastSPT, $status, $con, $sql);
      }
      // == Function to get the clients in the same way done in post == //
      if ($newClient != null) {

         $clientsByAE = array_merge($clientsByAE,$newClient);
         $clientsByAE = array_values($clientsByAE);

      }

      $agencyGroupName = $a->getAgencyGroupByID($con, $agencyGroup, '1');
      $bvTest = $bvModel->tableBV($agencyGroup, $year, $con, $value, $salesRep, $currency);
      $total = $bvModel->getBVTotal($bvTest, $year);
      $updateInfo = $bvModel->getRepAndDateOfPrev($salesRep, $agencyGroup, $con);
      $list = $bvModel->listOFClients($con, $year);
      $tmpColor = false;

      $title = "Control Panel - BV";
      $titleExcel = "Control Panel - BV.xlsx";
     
      for ($b=0; $b <sizeof($bvTest) ; $b++) { 
         $color[$b] = 'even';
         
         if ($newClient) {
            for ($c=0; $c <sizeof($newClient) ; $c++) { 
               if ($bvTest[$b]['clientId'] == $newClient[$c]['client']) {
                  $tmpColor[$b] = true;
               }

               if ($tmpColor) {
                  $color[$b] = 'oddGrey';
               }else{
                  $color[$b] = 'even';
               }
            }
         }      
      }

     return view("adSales.dashboards.dashboardBVPost", compact('region', 'salesRegion', 'render', 'year', 'bvTest', 'agencyGroupName', 'total', 'salesRep', 'currency', 'value', 'agencyGroup','updateInfo','list','color', 'title', 'titleExcel'));
   }

   public function resumeBVGet(){
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

      return view("adSales.dashboards.resumeBVGet", compact('region', 'salesRegion', 'render'));
   }

   public function resumeBVPost(){
      $db = new dataBase();
      $sql = new sql();
      $default = $db->defaultConnection();
      $con = $db->openConnection($default);
      $a = new agency();
      $sr = new salesRep();
      $base = new base();
      $region = new region();
      $agencyGroup = Request::get('agencyGroup');
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
      $b = new brand();
      $brand = $b->getBrandFromTable($con,false);
      $tmp = $sr->getSalesRepByRegionBV($con, array($salesRegion[0]['id']),false, $year);
      for ($s=0; $s <sizeof($tmp); $s++) { 
         $tmp2[] = $tmp[$s]['id'];
      }

      $salesRep = $base->arrayToString($tmp2,false,false);
     // var_dump($salesRep);

      $bvTest = $bvModel->tableBV($agencyGroup, $year, $con, $value, $salesRep, $currency);
      
      $total = $bvModel->getBVTotal($bvTest, $year);
      $updateInfo = $bvModel->getRepAndDateofPrev($salesRep, $agencyGroup, $con);
      $list = $bvModel->listOFClients($con, $year);
      $newClient = $bvModel->getSalesRepByClient($agencyGroup, $salesRep,$con, $sql);

      $clientsByAE = $bvModel->getSalesRepByAgencyGroup($agencyGroup, $salesRep, $year, $con, $sql);

      $clientsByPYear = $bvModel->getClientByYear($agencyGroup, $salesRep, $year-1, $con, $sql);
      //var_dump($clientsByPYear);
      $clientsByPpYear = $bvModel->getClientByYear($agencyGroup, $salesRep, $year-2, $con, $sql);
      $clientsByPppYear = $bvModel->getClientByYear($agencyGroup, $salesRep, $year-3, $con, $sql);      
      
      for ($c=0; $c <sizeof($clientsByPYear) ; $c++) { 
         for ($b=0; $b <sizeof($brand) ; $b++) { 
            $tableBrandPyear[$c][$b] = $bvModel->getBrandByClient($sql, $con, $agencyGroup, $brand[$b]['id'], $year-1, $clientsByPYear[$c]['client']);            
         }

         $totalBrandPyear = $bvModel->totalperBrand($tableBrandPyear[$c],$brand);
      }

      var_dump($totalBrandPyear);
      for ($c=0; $c <sizeof($clientsByPpYear) ; $c++) { 
         for ($b=0; $b <sizeof($brand) ; $b++) { 
            $tableBrandPpyear[$c][$b] = $bvModel->getBrandByClient($sql, $con, $agencyGroup, $brand[$b]['id'], $year-2, $clientsByPpYear[$c]['client']);
            
         }
      }

      for ($c=0; $c <sizeof($clientsByPppYear) ; $c++) { 
         for ($b=0; $b <sizeof($brand) ; $b++) {
           $tableBrandPppyear[$c][$b] = $bvModel->getBrandByClient($sql, $con, $agencyGroup, $brand[$b]['id'], $year-3, $clientsByPppYear[$c]['client']);
         }
      }

      $liquid = $bvModel->liquidTable($agencyGroup, $year, $con, $value, $salesRep, $currency,$brand);
      $investTotalBrand = $bvModel->totalperBrandInvest($liquid);
      $totalYearInvest = $bvModel->totalInvestYear($liquid, $investTotalBrand);
      
      $title = "Control Panel - BV";
      $titleExcel = "Control Panel - BV.xlsx";

      
      for ($b=0; $b <sizeof($bvTest) ; $b++) { 
         $color[$b] = 'even';

         if ($newClient) {
            for ($c=0; $c <sizeof($newClient) ; $c++) { 
               if ($bvTest[$b]['clientId'] == $newClient[$c]['client']) {
                  $tmpColor = true;
               }else{
                  $tmpColor = false;
               }

               if ($tmpColor) {
                  $color[$b] = 'oddGrey';
               }else{
                  $color[$b] = 'even';
               }
            }
         }      
      }

      //var_dump($totalBrandPyear);
      return view("adSales.dashboards.resumeBVPost", compact('region', 'salesRegion', 'render', 'year', 'bvTest', 'agencyGroupName', 'total', 'salesRep', 'currency', 'value', 'agencyGroup','updateInfo','list','color','title', 'titleExcel','liquid','brand', 'investTotalBrand','totalYearInvest','clientsByAE','tableBrandPyear','tableBrandPpyear','tableBrandPppyear','clientsByPYear','clientsByPpYear','clientsByPppYear','totalBrandPyear'));
   }
}
