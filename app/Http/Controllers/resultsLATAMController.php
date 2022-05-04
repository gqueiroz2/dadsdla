<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Request;
use App\dataBase;
use App\base;
use App\region;
use App\pRate;
use App\Render;
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

        //var_dump(Request::all());

        // == Gera o valor do pRate com base na moeda(currency) e o ano atual == //
        $pRate = $pr->getPrateByCurrencyAndYear($con, $currencyID, $year = date('Y'));
        //var_dump($pRate);

        // == Objetos que constroem a matriz para população da tabela == //
        // -- Real Date -- //
        $realDate = $dr->getLog($con, $log, $regionID);
        //var_dump($realDate);
        $total = $dr->tableDailyResults($con, $regionID, $value, $log, $pRate, "total");
        //var_dump($total);
        $disc = $dr->tableDailyResults($con, $regionID, $value, $log, $pRate, "discovery");
        //var_dump($disc);
        $sony = $dr->tableDailyResults($con, $regionID, $value, $log, $pRate, "sony");
        //var_dump($total);
        //var_dump($sony);

        $month = date('m', strtotime($log));
        $day = date('d', strtotime($log));
        $cYear = date('Y', strtotime($log));
        $pYear = $cYear - 1;
        $ppYear = $pYear - 1;

        $currencyName = $pr->getCurrency($con,array($currencyID))[0]['name'];
        
    	return view('adSales.results.6LATAMPost',compact('render','region', 'currency','month','log', 'day','currencyName', 'value', 'cYear', 'pYear', 'ppYear', 'total', 'disc', 'sony', 'realDate'));

    }
}
