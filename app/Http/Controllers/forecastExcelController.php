<?php

namespace App\Http\Controllers;

use App\region;
use App\salesRep;
use App\forecast;
use App\base;
use App\PAndRRender;
use App\forecastRender;

use App\pRate;
use App\sql;
use App\dataBase;

//use App\excel;

use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Request;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Pdf\Dompdf;

use App\Exports\forecastExport;


class forecastExcelController extends Controller{


	public function forecastAE(){
         $db = new dataBase();
        $b = new base();
        $r = new region();
        $pr = new pRate();
        $fcst = new forecast();
        $sr = new salesRep();
        $render = new PAndRRender();   
        $default = $db->defaultConnection();
        $con = $db->openConnection($default);

        $intMonth = Request::get('intMonth');
        $year = date('Y');
        $pYear = $year - 1;
        $region = $r->getRegion($con,false);
        $currency = $pr->getCurrency($con,false)[0]['name'];
        $permission = Request::session()->get('userLevel');
        $user = Request::session()->get('userName');

        $regionID = Request::get('regionID');
        $salesRepID = Request::get('salesRepID');
        $currencyID = '1'; 
        $value = 'gross';
        $typeExport = Request::get('typeExport');
        $title = Request::get('title');
        $auxTitle = Request::get('auxTitle');
        $regionName = Request::session()->get('userRegion');
        $salesRepName = $sr->getSalesRepById($con,array($salesRepID));

        $months = array(intval(date('n')),intval(date('n')) + 1,intval(date('n')) + 2);   
        $monthName = $b->intToMonth2(array($intMonth)); 
        //var_dump($salesRepID);

        $company = array('1','2','3');

        for ($c=0; $c < sizeof($company); $c++) { 
            if ($company[$c] == '1') {
                $color[$c] = '#0070c0';
                $companyView[$c] = 'DSC';
            }elseif ($company[$c] == '2') {
                $color[$c] = '#000000';
                $companyView[$c] = 'SPT';
            }elseif ($company[$c]) {
                $color[$c] = '#0f243e;';
                $companyView[$c] = 'WM';
            }
        }

        $listOfClients = $fcst->listOFClients($con);

        $listOfAgencies = $fcst->listOFAgencies($con); 

        $clientsTable = $fcst->makeClientsTable($con,$salesRepID,$pr,$year,$pYear,$regionID,$currencyID,$value,$salesRepName[0]['salesRep'],$intMonth);
        
        $newClientsTable = $fcst->makeNewClientsTable($con,$salesRepID,$pr,$year,$pYear,$regionID,$currencyID,$value,$salesRepName[0]['salesRep'],$intMonth); 
            //var_dump($newClientsTable);
            
               //var_dump('aki');
        $aeTable = $fcst->makeRepTable($con,$salesRepID,$pr,$year,$pYear,$regionID,$currencyID,$value,$salesRepName[0]['salesRep'],$intMonth,$newClientsTable,$clientsTable);
        //var_dump($company);

        $data = array('regionName' => $regionName,'regionID' => $regionID, 'salesRepID' => $salesRepID, 'currencyID' => $currencyID, 'value' => $value, 'regionName' => $regionName, 'aeTable' => $aeTable, 'newClientsTable' => $newClientsTable, 'clientsTable' => $clientsTable, 'company' => $company, 'companyView' => $companyView,'monthName' => $monthName,'year' => $year,'pYear' => $pYear,'salesRepName' => $salesRepName,'color' => $color);

        $label = "exports.PandR.ForecastAE.forecastExport";

        return Excel::download(new forecastExport($data, $label, $typeExport, $auxTitle), $title);
	}
    
}
