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
        $log = Request::get('log');

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
        var_dump("==============================");
        $disc = $dr->tableDailyResults($con, $regionID, $value, $log, $pRate, $brlPRate, "discovery", $currencyID);
        var_dump("==============================");
        $sony = $dr->tableDailyResults($con, $regionID, $value, $log, $pRate, $brlPRate, "sony", $currencyID);
        var_dump("==============================");
        //var_dump($sony);

        $month = $dr->getActiveMonth();
        $day = date('d', strtotime($log));
        $cYear = date('Y', strtotime($log));
        $pYear = $cYear - 1;
        $ppYear = $pYear - 1;        

        $currencyName = $pr->getCurrency($con,array($currencyID))[0]['name'];

        $title = "Daily Results";
        $titleExcel = "Daily Results.xlsx";

        $regionExcel = $regionID;
        $currencyExcel = $currencyID;
        $valueExcel = $value;
        $logExcel = $log; 
            
        //var_dump($regionID);
    	return view('adSales.results.6LATAMPost',compact('render','region', 'currency','month','log', 'day','currencyName', 'value', 'cYear', 'pYear', 'ppYear', 'total', 'disc', 'sony', 'realDate','base','title', 'titleExcel', 'regionExcel', 'currencyExcel', 'valueExcel', 'logExcel', 'regionID'));

    }
}
