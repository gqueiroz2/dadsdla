<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Request;
use App\region;
use App\PAndRRender;
use App\salesRep;
use App\pRate;
use App\dataBase;
use App\brand;
use App\base;
use App\AE;
use App\sql;
use App\excel;

class AEController extends Controller{
    
    public function save(){
        $db = new dataBase(); 
        $sql = new sql();
        $pr = new pRate();
        $ae = new AE();
        $base = new base();
        $excel = new excel();

        $con = $db->openConnection("DLA");  

        $regionID = json_decode( base64_decode( Request::get('region') ));
        $salesRep = json_decode( base64_decode( Request::get('salesRep') ));
        $currencyID = json_decode( base64_decode( Request::get('currency') ));
        $value = json_decode( base64_decode( Request::get('value') ));
        $user = json_decode( base64_decode( Request::get('user') ));
        $year = json_decode( base64_decode( Request::get('year') ));

        $salesRepID = $salesRep->id;

        var_dump($regionID);
        var_dump($salesRepID);        
        var_dump($currencyID);
        var_dump($value);
        var_dump($user);
        var_dump($year);

        $date = date('Y-d-m');
        $time = date('H:i');
        $fcstMonth = date('m');

        var_dump($date);
        var_dump($time);

        $month = $base->month;
        $monthWQ = $base->monthWQ;        

        $client = json_decode( base64_decode( Request::get('client') ) );

        for ($m=0; $m < sizeof($monthWQ); $m++) { 
            $manualEstimantionBySalesRep[$m] = $excel->fixExcelNumber(Request::get("fcstSalesRep-$m"));
        }

        unset($manualEstimantionBySalesRep[3]);
        unset($manualEstimantionBySalesRep[7]);
        unset($manualEstimantionBySalesRep[11]);
        unset($manualEstimantionBySalesRep[15]);

        $manualEstimantionBySalesRep = array_values($manualEstimantionBySalesRep);

        for ($c=0; $c < sizeof($client); $c++) { 
            for ($m=0; $m < sizeof($monthWQ); $m++) { 
                $manualEstimantionByClient[$c][$m] = $excel->fixExcelNumber(Request::get("fcstClient-$c-$m"));
            }
        }       

        for ($c=0; $c < sizeof($client); $c++) { 
            
            unset($manualEstimantionByClient[$c][3]);
            unset($manualEstimantionByClient[$c][7]);
            unset($manualEstimantionByClient[$c][11]);
            unset($manualEstimantionByClient[$c][15]);

            $manualEstimantionByClient[$c] = array_values($manualEstimantionByClient[$c]);
        }

        //var_dump($manualEstimantionBySalesRep);
        //var_dump($manualEstimantionByClient);

        /*
            kind,region,year,salesRep,currency,value,week,month
        */
        $ID = $ae->generateID($con,$sql,$pr,"save",$regionID,$year,$salesRep,$currencyID,$value,"week",$fcstMonth);

        $weeki = $ae->weekOfMonth("2019-08-28");
        var_dump($weeki);

        //$bool = $ae->saveUpdate($regionID,$salesRep,$currencyID,$value,$user,$year,$date,$time,$fcstMonth,$manualEstimantionBySalesRep,$manualEstimantionByClient);
        var_dump($ID);
    }

    public function get(){
    	$db = new dataBase();
        $con = $db->openConnection("DLA");
        $r = new region();
        $sr = new salesRep();
        $render = new PAndRRender();
        $pr = new pRate();

        $region = $r->getRegion($con,null);
        $currency = $pr->getCurrency($con,null);

		return view('pAndR.AEView.get',compact('render','region','currency'));
    }

    public function post(){
        $db = new dataBase(); 
        $render = new PAndRRender();
        $r = new region();
        $pr = new pRate();
        $ae = new AE();        
        $con = $db->openConnection("DLA");        
        $cYear = intval( Request::get('year') );
        $pYear = $cYear - 1;
        $region = $r->getRegion($con,false);
        $currency = $pr->getCurrency($con,false);
        $tmp = $ae->base($con,$r,$pr,$cYear,$pYear);
        $forRender = $tmp;
        $client = $tmp['client'];
        $tfArray = array();
        $odd = array();
        $even = array();

        return view('pAndR.AEView.post',compact('render','region','currency','forRender','client',"tfArray","odd","even"));
    }

}
