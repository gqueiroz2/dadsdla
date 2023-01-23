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
use App\bvModel;
use App\sql;

use App\Exports\bvExport;

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
}
