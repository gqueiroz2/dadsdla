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
      //var_dump($agencyGroup);
      $newClient = $bvModel->getSalesRepByClient($agencyGroup, $salesRep,$con, $sql);
      //var_dump($newClient);
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
     // var_dump($saveButtonGet);
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
     // var_dump($clientsByAE);
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
      $pRate = new pRate();
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

      $currencyName = $pRate->getCurrency($con,array($currency))[0]['name'];
      $brand = $b->getBrandFromTable($con,false);
      $tmp = $sr->getSalesRepByRegionBV($con, array($salesRegion[0]['id']),false, $year);
      for ($s=0; $s <sizeof($tmp); $s++) { 
         $tmp2[] = $tmp[$s]['id'];
      }

      $salesRep = $base->arrayToString($tmp2,false,false);
     // var_dump($salesRep);


      if($currency == '1'){
         $pRateWM = 1; // Temporary value for WM pRate, will need change after 2023
      }else {
         if ($year <= 2022 ) {
             $pRateWM = 4.99;
         }else{
             $pRateWM = $pRate->getPRateByRegionAndYear($con, array('1'), array($year));
         }
      }

      $bvTest = $bvModel->tableResume($agencyGroup, $year, $con, $value, $salesRep, $currency);
      //var_dump($bvTest);
      $total = $bvModel->getBVTotal($bvTest, $year);
      $updateInfo = $bvModel->getRepAndDateOfPrevResume($salesRep, $agencyGroup, $con);
      //var_dump($updateInfo);
      $list = $bvModel->listOFClients($con, $year);
      $newClient = $bvModel->getSalesRepByClient($agencyGroup, $salesRep,$con, $sql);

      $clientsByAE = $bvModel->getSalesRepByAgencyGroup($agencyGroup, $salesRep, $year, $con, $sql);

      //THIS PART IS TO BRING BV INFO OF WM COMPANY
      $bvPaytvPYear = $bvModel->bvTable($year-1,$agencyGroup,$con,'Pay TV',3);
      $bvDigitalPYear = $bvModel->bvTable($year-1,$agencyGroup,$con,'Digital',3);
      
      $temp = array('wmDigital' => $bvDigitalPYear, 'wmPaytv' => $bvPaytvPYear);
      
      $bvPaytvPpYear = $bvModel->bvTable($year-2,$agencyGroup,$con,'Pay TV',3);
      $bvDigitalPpYear = $bvModel->bvTable($year-2,$agencyGroup,$con,'Digital',3);
      
      $temp1 = array('wmDigital' => $bvDigitalPpYear, 'wmPaytv' => $bvPaytvPpYear);

      $bvWMPyear = $temp;
      $bvWMPpyear = $temp1;
      // END OF WM PART

      //THIS PART IS TO BRING BV INFO OF DSC COMPANY
      $bvPaytvPYear = $bvModel->bvTable($year-1,$agencyGroup,$con,'Pay TV',1);
      $bvDigitalPYear = $bvModel->bvTable($year-1,$agencyGroup,$con,'Digital',1);
      
      $temp = array('dscDigital' => $bvDigitalPYear, 'dscPaytv' => $bvPaytvPYear);
      
      $bvPaytvPpYear = $bvModel->bvTable($year-2,$agencyGroup,$con,'Pay TV',1);
      $bvDigitalPpYear = $bvModel->bvTable($year-2,$agencyGroup,$con,'Digital',1);
      
      $temp1 = array('dscDigital' => $bvDigitalPpYear, 'dscPaytv' => $bvPaytvPpYear);

      $bvDSCPyear = $temp;
      $bvDSCPpyear = $temp1;
      // END OF DSC PART
      
      //PAY TV TABLE
      $payTv = $bvModel->getPayTv($con);
      
      //DSC TARGET FOR PREVIOUS YEAR
      $monthTargetDSC = $bvModel->getMonthTarget($con,$agencyGroup,$year-1,'DSC');

      $bvTargetDSC = $bvModel->getBvTarget($con,$agencyGroup,$year-1,'DSC');
      //END OF DSC TARGET

      //WM TARGET FOR PREVIOUS YEAR
      $monthTargetWM = $bvModel->getMonthTarget($con,$agencyGroup,$year-1,'WM');

      $bvTargetWM = $bvModel->getBvTarget($con,$agencyGroup,$year-1,'WM');
      //END OF WM TARGET

      // GETTING REAL VALUE OF WICH COMPANY FOR PREVIOUS YEAR
      $realDSCPyear = $bvModel->getReal($con,$agencyGroup,$year-1,1);

      $realSPTPyear = $bvModel->getReal($con,$agencyGroup,$year-1,2);

      $realWMPyear = $bvModel->getReal($con,$agencyGroup,$year-1,3);
      
      //this part is to the historical tables
      $historyPyear = $bvModel->historyTable($con, $agencyGroup, $year-1);

      $totalClusterPyear = $bvModel->totalByCluster($historyPyear);

      $totalByClientPyear = $bvModel->totalByClientHistory($historyPyear);

      $historyPpyear = $bvModel->historyTable($con, $agencyGroup, $year-2);

      $totalClusterPpyear = $bvModel->totalByCluster($historyPpyear);

      $totalByClientPpyear = $bvModel->totalByClientHistory($historyPpyear);

      $title = "RESUME - BV -".$agencyGroupName;
      $titleExcel = "$agencyGroupName - Resume - BV.xlsx";

      
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
       return view("adSales.dashboards.resumeBVPost", compact('region', 'salesRegion', 'render', 'year', 'bvTest', 'agencyGroupName', 'total', 'salesRep', 'currency', 'value', 'agencyGroup','updateInfo','list','color','title', 'titleExcel','brand', 'clientsByAE','bvWMPyear', 'bvWMPpyear','bvDSCPyear', 'bvDSCPpyear','payTv','monthTargetDSC','bvTargetDSC','monthTargetWM','bvTargetWM','realWMPyear','realDSCPyear','realSPTPyear','pRateWM','historyPyear','historyPpyear', 'totalClusterPyear','totalByClientPyear', 'totalClusterPpyear','totalByClientPpyear','currencyName'));
      
   }
}
