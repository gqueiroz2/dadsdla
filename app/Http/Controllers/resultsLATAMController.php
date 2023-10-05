<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Request;
use App\dataBase;
use App\base;
use App\region;
use App\pRate;
use App\Render;
use App\excel;
use App\DailyResults;

class resultsLATAMController extends Controller{

    public function get(){
    	$base = new base();
        $db = new dataBase();
        $default = $db->defaultConnection();
        $con = $db->openConnection($default);
        $r = new region();
        $pr = new pRate();
        $render = new Render();
        $region = $r->getRegion($con,false);
        $currency = $pr->getCurrency($con,false);

    	return view('adSales.results.6LATAMGet',compact('render','region','currency'));
    }

    public function post(){
    	$base = new base();
        $db = new dataBase();
        $default = $db->defaultConnection();
        $con = $db->openConnection($default);
        $r = new region();
        $pr = new pRate();
        $render = new Render();
        $region = $r->getRegion($con,false);
        $currency = $pr->getCurrency($con,false);
        $dr = new DailyResults();

        $regionID = Request::get('region');
        $currencyID = Request::get('currency');
        $value = Request::get('value');

        //this function get always the date of the last update of wbd base
        $date = $dr->getCurrentDate($con);
        $log = $date;
        
        //var_dump($log);
        // == Gera o valor do pRate com base na moeda(currency) e o ano atual == //
        if ($currencyID == '4') {
            $pRate = 1.0;
        }else{
            $pRate = $pr->getPrateByCurrencyAndYear($con, $currencyID, $year = date('Y'));    
        }

        $brlPRate = $pr->getPrateByCurrencyAndYear($con, 1, $year = date('Y'));
        
        // == Objetos que constroem a matriz para população da tabela == //
        // -- Real Date -- //
        $realDate = $dr->getLog($con, $log, $regionID);
        //var_dump($realDate);
        $total = $dr->tableDailyResults($con, $regionID, $value, $log, $pRate, $brlPRate,"total", $currencyID);
        $disc = $dr->tableDailyResults($con, $regionID, $value, $log, $pRate, $brlPRate, "discovery", $currencyID);
        $sony = $dr->tableDailyResults($con, $regionID, $value, $log, $pRate, $brlPRate, "sony", $currencyID);
        $wm = $dr->tableDailyResults($con, $regionID, $value, $log, $pRate, $brlPRate, "wm", $currencyID);
       // $total = $disc + $wm + $sony;
        
        for ($m=0; $m < 3; $m++) { 
            $total[$m][0]['currentYTD'] = ($disc[$m][0]['currentYTD']+$wm[$m][0]['currentYTD']+$sony[$m][0]['currentYTD']);
            $total[$m][0]['currentPlanPercent'] = $dr->percentageCalculator($total[$m][0]['currentYTD'],$total[$m][0]['currentPlan']);
            $total[$m][0]['currentFcstPercent'] = $dr->percentageCalculator($total[$m][0]['currentYTD'],$total[$m][0]['currentFcst']);
            $total[$m][0]['ssPercent'] = $dr->percentageCalculator($total[$m][0]['currentYTD'],$total[$m][0]['previousSS']);
            $total[$m][0]['pSapPercent'] = $dr->percentageCalculator($total[$m][0]['currentYTD'],$total[$m][0]['previousSap']);
            $total[$m][0]['ppSapPercent'] = $dr->percentageCalculator($total[$m][0]['currentYTD'],$total[$m][0]['pPSap']);

            $total[$m][1]['currentYTD'] = ($disc[$m][1]['currentYTD']+$wm[$m][1]['currentYTD']+$sony[$m][1]['currentYTD']);
            $total[$m][1]['currentPlanPercent'] = $dr->percentageCalculator($total[$m][1]['currentYTD'],$total[$m][1]['currentPlan']);
            $total[$m][1]['currentFcstPercent'] = $dr->percentageCalculator($total[$m][1]['currentYTD'],$total[$m][1]['currentFcst']);
            $total[$m][1]['ssPercent'] = $dr->percentageCalculator($total[$m][1]['currentYTD'],$total[$m][1]['previousSS']);
            $total[$m][1]['pSapPercent'] = $dr->percentageCalculator($total[$m][1]['currentYTD'],$total[$m][1]['previousSap']);
            $total[$m][1]['ppSapPercent'] = $dr->percentageCalculator($total[$m][1]['currentYTD'],$total[$m][1]['pPSap']);

            $total[$m][2]['currentYTD'] = ($disc[$m][2]['currentYTD']+$wm[$m][2]['currentYTD']+$sony[$m][2]['currentYTD']);
            $total[$m][2]['currentPlanPercent'] =  $dr->percentageCalculator($total[$m][2]['currentYTD'],$total[$m][2]['currentPlan']);
            $total[$m][2]['currentFcstPercent'] =  $dr->percentageCalculator($total[$m][2]['currentYTD'],$total[$m][2]['currentFcst']);
            $total[$m][2]['ssPercent'] =  $dr->percentageCalculator($total[$m][2]['currentYTD'],$total[$m][2]['previousSS']);
            $total[$m][2]['pSapPercent'] =  $dr->percentageCalculator($total[$m][2]['currentYTD'],$total[$m][2]['previousSap']);
            $total[$m][2]['ppSapPercent'] =  $dr->percentageCalculator($total[$m][2]['currentYTD'],$total[$m][2]['pPSap']);
        }

        $total[3][0]['currentYTD'] = ($disc[3][0]['currentYTD']+$wm[3][0]['currentYTD']+$sony[3][0]['currentYTD']);
        $total[3][0]['currentPlanPercent'] = $dr->percentageCalculator($total[3][0]['currentYTD'],$total[3][0]['currentPlan']);
        $total[3][0]['currentFcstPercent'] = $dr->percentageCalculator($total[3][0]['currentYTD'],$total[3][0]['currentFcst']);
        $total[3][0]['ssPercent'] = $dr->percentageCalculator($total[3][0]['currentYTD'],$total[3][0]['previousSS']);
        $total[3][0]['pSapPercent'] = $dr->percentageCalculator($total[3][0]['currentYTD'],$total[3][0]['previousSap']);
        $total[3][0]['ppSapPercent'] = $dr->percentageCalculator($total[3][0]['currentYTD'],$total[3][0]['pPSap']);

        $total[3][1]['currentYTD'] = ($disc[3][1]['currentYTD']+$wm[3][1]['currentYTD']+$sony[3][1]['currentYTD']);
        $total[3][1]['currentPlanPercent'] = $dr->percentageCalculator($total[3][1]['currentYTD'],$total[3][1]['currentPlan']);
        $total[3][1]['currentFcstPercent'] = $dr->percentageCalculator($total[3][1]['currentYTD'],$total[3][1]['currentFcst']);
        $total[3][1]['ssPercent'] = $dr->percentageCalculator($total[3][1]['currentYTD'],$total[3][1]['previousSS']);
        $total[3][1]['pSapPercent'] = $dr->percentageCalculator($total[3][1]['currentYTD'],$total[3][1]['previousSap']);
        $total[3][1]['ppSapPercent'] = $dr->percentageCalculator($total[3][1]['currentYTD'],$total[3][1]['pPSap']);

        $total[3][2]['currentYTD'] = ($disc[3][2]['currentYTD']+$wm[3][2]['currentYTD']+$sony[3][2]['currentYTD']);
        $total[3][2]['currentPlanPercent'] = $dr->percentageCalculator($total[3][2]['currentYTD'],$total[3][2]['currentPlan']);
        $total[3][2]['currentFcstPercent'] = $dr->percentageCalculator($total[3][2]['currentYTD'],$total[3][2]['currentFcst']);
        $total[3][2]['ssPercent'] = $dr->percentageCalculator($total[3][2]['currentYTD'],$total[3][2]['previousSS']);
        $total[3][2]['pSapPercent'] = $dr->percentageCalculator($total[3][2]['currentYTD'],$total[3][2]['previousSap']);
        $total[3][2]['ppSapPercent'] = $dr->percentageCalculator($total[3][2]['currentYTD'],$total[3][2]['pPSap']);
        
        //$total = $dr->makeTotal($disc,$sony,$wm);
        $month = $dr->getActiveMonth();
        $day = date('d', strtotime($log));
        $cYear = date('Y', strtotime($log));
        $pYear = $cYear - 1;
        $ppYear = $pYear - 1;  
        
        $actualMonth = $monthForm = $base->intToMonth(array($month))[0];
        
        if($month == 12 || $month == 11){
            $month = 10;
        }

        $currencyName = $pr->getCurrency($con,array($currencyID))[0]['name'];

        $title = "Daily Results";
        $titleExcel = "Daily Results.xlsx";
        

        $regionExcel = $regionID;
        $currencyExcel = $currencyID;
        $valueExcel = $value;
        $logExcel = $log; 
            
        //var_dump($regionID);
    	return view('adSales.results.6LATAMPost',compact('render','region', 'currency','month','log', 'day','currencyName', 'value', 'cYear', 'pYear', 'ppYear', 'total', 'disc', 'sony', 'realDate','base','title', 'titleExcel', 'regionExcel', 'currencyExcel', 'valueExcel', 'logExcel', 'regionID', 'wm', 'actualMonth'));

    }
}
