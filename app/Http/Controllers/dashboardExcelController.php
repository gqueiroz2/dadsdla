<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Request;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Pdf\Dompdf;

use App\Render;
use App\dataBase;
use App\region;
use App\pRate;
use App\brand;
use App\agency;
use App\salesRep;
use App\bvModel;
use App\base;
use App\sql;

use App\Exports\bvExport;
use App\Exports\resumeExport;

class dashboardExcelController extends Controller{

      public function dashBV(){

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
         $title = Request::get('title');
         $typeExport = Request::get('typeExport');
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
         $bvTest = $bvModel->tableBV($agencyGroup, $year, $con, $value, $salesRep, $currency);
         $total = $bvModel->getBVTotal($bvTest, $year);
         $updateInfo = $bvModel->getRepAndDateofPrev($salesRep, $agencyGroup, $con);
         $list = $bvModel->listOFClients($con, $year);
         $newClient = $bvModel->getSalesRepByClient($agencyGroup, $salesRep,$con, $sql);
         $tmpColor = false;

         
         for ($b=0; $b <sizeof($bvTest) ; $b++) { 
            $color[$b] = '#f9fbfd;';

            if ($newClient) {
               for ($c=0; $c <sizeof($newClient) ; $c++) { 
                  if ($bvTest[$b]['clientId'] == $newClient[$c]['client']) {
                     $tmpColor[$b] = true;
                  }
                  if ($tmpColor) {
                     $color[$b] = '#e6e6e6;';
                  }else{
                     $color[$b] = '#f9fbfd;';
                  }
               }
            }      
         }

         $data = array('bvTest' => $bvTest, 'total' => $total, 'updateInfo' => $updateInfo, 'year' => $year, 'agencyGroupName' => $agencyGroupName, 'color' => $color);

         $label = 'exports.dashboards.bvExport';

         $auxTitle = $title;

         return Excel::download(new bvExport($data, $label, $typeExport, $auxTitle), $title);
      
      }


      public function dashResume(){
         
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
         $title = Request::get('title');
         $typeExport = Request::get('typeExport');
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
         
         $total = $bvModel->getBVTotal($bvTest, $year);
         $updateInfo = $bvModel->getRepAndDateofPrev($salesRep, $agencyGroup, $con);
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

         $tmpColor = false;

          for ($b=0; $b <sizeof($bvTest) ; $b++) { 
            $color[$b] = '#f9fbfd;';

            if ($newClient) {
               for ($c=0; $c <sizeof($newClient) ; $c++) { 
                  if ($bvTest[$b]['clientId'] == $newClient[$c]['client']) {
                     $tmpColor[$b] = true;
                  }
                  if ($tmpColor) {
                     $color[$b] = '#e6e6e6;';
                  }else{
                     $color[$b] = '#f9fbfd;';
                  }
               }
            }      
         }

         $data = array('totalByClientPyear' => $totalByClientPyear,'totalClusterPyear' => $totalClusterPyear, 'totalByClientPpyear' => $totalByClientPpyear, 'totalClusterPpyear' => $totalClusterPpyear, 'historyPpyear' => $historyPpyear, 'historyPyear' => $historyPyear, 'realWMPyear' => $realWMPyear, 'realDSCPyear' => $realDSCPyear, 'realSPTPyear' => $realSPTPyear, 'bvTargetWM' => $bvTargetWM, 'monthTargetWM' => $monthTargetWM, 'bvTargetDSC' => $bvTargetDSC, 'monthTargetDSC' => $monthTargetDSC, 'payTv' => $payTv, 'bvDSCPyear' => $bvDSCPyear, 'bvDSCPpyear' => $bvDSCPpyear, 'bvWMPyear' => $bvWMPyear, 'bvWMPpyear' => $bvWMPpyear, 'bvTest' => $bvTest, 'total'=> $total, 'updateInfo' => $updateInfo,'newClient' => $newClient, 'year' => $year, 'pRateWM' => $pRateWM, 'color' => $color,'agencyGroupName' => $agencyGroupName);

         $label = array('exports.dashboards.resumeExport','exports.dashboards.payTvExport', 'exports.dashboards.bvTablesExport');

         $auxTitle = $title;
         //var_dump($data);
         return Excel::download(new resumeExport($data, $label, $typeExport, $auxTitle), $title);
      }
}
