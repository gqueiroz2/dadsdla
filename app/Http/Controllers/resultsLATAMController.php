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

        $regionID = Request::get('region');
        $currencyID = Request::get('currency');
        $value = Request::get('value');
        $log = Request::get('log');
        //var_dump(Request::all());
        $dr = new DailyResults();

        // == Gera o valor do pRate com base na moeda(currency) e o ano atual == //
        $pRate = $pr->getPrateByCurrencyAndYear($con, $currencyID, $year = date('Y'));
        //var_dump($pRate);

        // == Objeto que constroi a matriz para população da tabela == //
        $table = $dr->tableDailyResults($con, $regionID, $value, $log, $pRate);

        $month = date('m', strtotime($log));
        
        //$month = $base->intToMonth2($month);

        $day = date('d', strtotime($log));

        $cYear = date('Y', strtotime($log));

        $pYear = $cYear - 1;

        $ppYear = $pYear - 1;

        $currencyName = $pr->getCurrency($con,array($currencyID))[0]['name'];
        /*for ($i=0; $i <sizeof($table) ; $i++) { 
            //for ($l=0; $l <sizeof($table[$l]) ; $l++) { 
                var_dump($table[$i][0]);
            //}
            # code...
        }*/
        
    	
    	return view('adSales.results.6LATAMPost',compact('render','region', 'currency','month','log', 'day','currencyName', 'value','table', 'cYear', 'pYear', 'ppYear'));

    }
}
