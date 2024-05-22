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
        $cMonth = date('M');
        $cDate = date('d/m/Y');
        
        $lastMonday = date('d/m/Y',strtotime("last Monday of $cMonth $year"));
        if ($cDate >= $lastMonday) {
            $months = array(intval(date('n'))+1,intval(date('n')) + 2,intval(date('n')) + 3);   
            $currentMonthName = $b->intToMonth2(array(intval(date('n')+1))); 
            $nextMonthName = $b->intToMonth2(array(intval(date('n')+2)));  
            $nextNMonthName = $b->intToMonth2(array(intval(date('n')+3)));  
        //var_dump($salesRepID);

            $currentMonth = intval(date('n'))+1; 
            $nextMonth = intval(date('n')+2);  
            $nextNMonth = intval(date('n')+3);  
        }else{
            $months = array(intval(date('n')),intval(date('n')) + 1,intval(date('n')) + 2);   
            $currentMonthName = $b->intToMonth2(array(intval(date('n')))); 
            $nextMonthName = $b->intToMonth2(array(intval(date('n')+1)));  
            $nextNMonthName = $b->intToMonth2(array(intval(date('n')+2)));  
        //var_dump($salesRepID);

            $currentMonth = intval(date('n')); 
            $nextMonth = intval(date('n')+1);  
            $nextNMonth = intval(date('n')+2);  
        }    
         
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
        //var_dump($currentMonth);
        $clientsTableCMonth = $fcst->makeClientsTable($con,$salesRepID,$pr,$year,$pYear,$regionID,$currencyID,$value,$salesRepName[0]['salesRep'],$currentMonth);
        
        $newClientsTableCMonth = $fcst->makeNewClientsTable($con,$salesRepID,$pr,$year,$pYear,$regionID,$currencyID,$value,$salesRepName[0]['salesRep'],$currentMonth); 

        $clientsTableNMonth = $fcst->makeClientsTable($con,$salesRepID,$pr,$year,$pYear,$regionID,$currencyID,$value,$salesRepName[0]['salesRep'],$nextMonth);
        
        $newClientsTableNMonth = $fcst->makeNewClientsTable($con,$salesRepID,$pr,$year,$pYear,$regionID,$currencyID,$value,$salesRepName[0]['salesRep'],$nextMonth); 

        $clientsTableNNMonth = $fcst->makeClientsTable($con,$salesRepID,$pr,$year,$pYear,$regionID,$currencyID,$value,$salesRepName[0]['salesRep'],$nextNMonth);
        
        $newClientsTableNNMonth = $fcst->makeNewClientsTable($con,$salesRepID,$pr,$year,$pYear,$regionID,$currencyID,$value,$salesRepName[0]['salesRep'],$nextNMonth); 
            //var_dump($newClientsTable);
        //var_dump($company);

        $data = array('regionName' => $regionName,'regionID' => $regionID, 'salesRepID' => $salesRepID, 'currencyID' => $currencyID, 'value' => $value, 'regionName' => $regionName, 'newClientsTableCMonth' => $newClientsTableCMonth, 'clientsTableCMonth' => $clientsTableCMonth,'newClientsTableNMonth' => $newClientsTableNMonth, 'clientsTableNMonth' => $clientsTableNMonth,'newClientsTableNNMonth' => $newClientsTableNNMonth, 'clientsTableNNMonth' => $clientsTableNNMonth, 'company' => $company, 'companyView' => $companyView,'currentMonthName' => $currentMonthName,'nextMonthName' => $nextMonthName,'nextNMonthName' => $nextNMonthName,'year' => $year,'pYear' => $pYear,'salesRepName' => $salesRepName,'color' => $color);

        $label = "exports.PandR.ForecastAE.forecastExport";

        return Excel::download(new forecastExport($data, $label, $typeExport, $auxTitle), $title);
	}
    
}
