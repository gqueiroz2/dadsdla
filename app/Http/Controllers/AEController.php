<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Redirect;
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
use Validator;

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
        $splitted = json_decode( base64_decode( Request::get('splitted') ));
        $submit = Request::get('options');

        $salesRepID = $salesRep->id;

/*
        var_dump($regionID);
        var_dump($salesRepID);        
        var_dump($currencyID);
        var_dump($value);
        var_dump($user);
        var_dump($year);
*/
        $date = date('Y-m-d');
        $time = date('H:i');
        $fcstMonth = date('m');

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
            $passTotal[$c] = $excel->fixExcelNumber(Request::get("passTotal-$c"));
            $totalClient[$c] = $excel->fixExcelNumber(Request::get("totalClient-$c"));

            if ($passTotal[$c] != $totalClient[$c]) {
                $msg = "Incorrect value submited";
                return back()->with("Error",$msg);
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
        $today = $date;

        var_dump($today);
        $read = $ae->weekOfMonth($today);
        $read = "0".$read;

        $ID = $ae->generateID($con,$sql,$pr,"save",$regionID,$year,$salesRep,$currencyID,$value,$read,$fcstMonth);
        
        $currency = $pr->getCurrencybyName($con,$currencyID);

        $bool = $ae->insertUpdate($con,$ID,$regionID,$salesRep,$currency,$value,$user,$year,$read,$date,$time,$fcstMonth,$manualEstimantionBySalesRep,$manualEstimantionByClient,$client,$splitted,$submit);


        if ($bool == "Updated") {
            $msg = "Forecast Updated";
            return back()->with("Success",$msg);
        }elseif($bool == "Created"){
            $msg = "Forecast Created";
            return back()->with("Success",$msg);
        }elseif ($bool == "Already Submitted") {
            $msg = "You already have submitted the Forecast";
            return back()->with("Error",$msg);
        }else{
            $msg = "Error";
            return back()->with("Error",$msg);
        }

    }

    public function get(){
    	$db = new dataBase();
        $con = $db->openConnection("DLA");
        $r = new region();
        $sr = new salesRep();
        $render = new PAndRRender();
        $pr = new pRate();

        $user = Request::session()->get('userName');
        $permission = Request::session()->get('userLevel');

        //$checkForForecasts = $ae->checkForForecasts();

        $region = $r->getRegion($con,null);
        $currency = $pr->getCurrency($con,null);

		return view('pAndR.AEView.get',compact('con','render','region','currency','permission','user'));
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

        $validator = Validator::make(Request::all(),[
            'region' => 'required',
            'year' => 'required',
            'currency' => 'required',
            'value' => 'required',
            'salesRep' => 'required',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        
        $tmp = $ae->base($con,$r,$pr,$cYear,$pYear);

        if (!$tmp) {
            return back()->with("Error","Don't have a Forecast Saved");
        }

        $forRender = $tmp;
        $client = $tmp['client'];
        $tfArray = array();
        $odd = array();
        $even = array();

        return view('pAndR.AEView.post',compact('render','region','currency','forRender','client',"tfArray","odd","even"));
    }

}
