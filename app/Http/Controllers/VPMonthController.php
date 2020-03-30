<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Request;
use App\region;
use App\sql;
use App\base;
use App\renderVPMonth;
use App\pRate;
use App\dataBase;
use App\VPMonth;
use App\excel;
use Validator;

class VPMonthController extends Controller {
    
    public function save(){
        
        $db = new dataBase();
        $default = $db->defaultConnection();
        $con = $db->openConnection($default);

        $sql = new sql();
        $base = new base();
        $excel = new excel();

        $r = new region();
        $regionID = json_decode(base64_decode(Request::get('region')));

        $tmp = $r->getRegion($con,array($regionID));

        if(is_array($tmp)){
            $rtr = $tmp[0]['name'];
        }else{
            $rtr = $tmp['name'];
        }

        $currencyID = json_decode(base64_decode(Request::get('currency')));
        $client = json_decode(base64_decode(Request::get('client')));
        $value = json_decode(base64_decode(Request::get('value')));
        $user = json_decode(base64_decode(Request::get('user')));
        $year = json_decode(base64_decode(Request::get('year')));
        $percentage = json_decode(base64_decode(Request::get('percentage')));
        $brandsPerClient = json_decode(base64_decode(Request::get('brandsPerClient')));

        /*for ($c=0; $c < sizeof($brandsPerClient); $c++) {
            $saida[$c] = array();
            $brandPerClient[$c] = "";
            if($brandsPerClient[$c]){
                for ($p=0; $p < sizeof($brandsPerClient[$c]); $p++) {
                    $brandsPerClient[$c][$p] = explode(";", $brandsPerClient[$c][$p]->brand);
                }
                for($p=0; $p < sizeof($brandsPerClient[$c]); $p++){
                    for ($b=0; $b < sizeof($brandsPerClient[$c][$p]); $b++) { 
                        array_push($saida[$c], $brandsPerClient[$c][$p][$b]);
                    }
                }
                $saida[$c] = array_unique($saida[$c]);
                $saida[$c] = array_values($saida[$c]);
                for ($s=0; $s <sizeof($saida[$c]); $s++) { 
                    if ($s == (sizeof($saida[$c])-1)) {
                        $brandPerClient[$c] .= $saida[$c][$s];
                    }else{
                        $brandPerClient[$c] .= $saida[$c][$s].";";
                    }
                }
            }else{
                $saida[$c] = false;
                $brandPerClient[$c] = false;
            }
        }*/

        $submit = Request::get('options');

        $date = date('Y-m-d');
        $time = date('H:i');
        $fcstMonth = date('m');

        $month = $base->month;
        $monthWQ = $base->monthWQ;

        $client = json_decode(base64_decode(Request::get('client')));

        for ($m=0; $m < sizeof($monthWQ); $m++) {
            $manualEstimation[$m] = $excel->fixExcelNumber( str_replace(".", "", Request::get("manualEstimation-$m")));
            $booking[$m] = $excel->fixExcelNumber(str_replace(".", "", Request::get("bookingE-$m")));
        }


        unset($manualEstimation[3]);
        unset($manualEstimation[7]);
        unset($manualEstimation[11]);
        unset($manualEstimation[15]);

        unset($booking[3]);
        unset($booking[7]);
        unset($booking[11]);
        unset($booking[15]);

        $manualEstimation = array_values($manualEstimation);
        $booking = array_values($booking);


        for ($m=0; $m <sizeof($booking) ; $m++) { 
            $manualEstimation[$m] -= $booking[$m];
        }


        for ($c=0; $c < sizeof($client); $c++) { 
            for ($m=0; $m < sizeof($monthWQ); $m++) { 
                $manualEstimantionByClient[$c][$m] = $excel->fixExcelNumber(Request::get("fcstClient-$c-$m"));
            }
        }

        for ($c=0; $c < sizeof($client); $c++) { 
            $passTotal[$c] = $excel->fixExcelNumber(Request::get("passTotal-$c"));
            $totalClient[$c] = $excel->fixExcelNumber(Request::get("totalClient-$c"));
        }

        for ($c=0; $c < sizeof($client); $c++) { 
            
            unset($manualEstimantionByClient[$c][3]);
            unset($manualEstimantionByClient[$c][7]);
            unset($manualEstimantionByClient[$c][11]);
            unset($manualEstimantionByClient[$c][15]);

            $manualEstimantionByClient[$c] = array_values($manualEstimantionByClient[$c]);
        }

        $pr = new pRate();

        $vpMonth = new VPMonth();

        $today = $date;

        if ($submit == "submit") {
            $type = "submit";
        }else{
            $type = "save";
        }

        $read = $vpMonth->weekOfMonth($today);
        $read = "0".$read;
        
        $ID = $vpMonth->generateID($con,$type,$rtr,$year,$currencyID,$value,$read,$fcstMonth,$user);
        
        $currency = $pr->getCurrencybyName($con,$currencyID);

        $bool = $vpMonth->insertUpdate($con,$ID,$regionID,$currency,$value,$user,$year,$read,$date,$time,$fcstMonth,$manualEstimation,$manualEstimantionByClient,$client,$submit,$brandsPerClient, $totalClient, $percentage);

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
        $default = $db->defaultConnection();
        $con = $db->openConnection($default);
        $r = new region();        
        $render = new renderVPMonth();
        $pr = new pRate();

        $region = $r->getRegion($con,null);
        $currency = $pr->getCurrency($con,null);

		return view('pAndR.VPMonthView.get',compact('render','region','currency'));
    }

    public function post(){
    	
    	$db = new dataBase();
        $default = $db->defaultConnection();
        $con = $db->openConnection($default);
        
        $r = new region();
        $regionID = Request::get("region");
        $region = $r->getRegion($con);

        $tmp = $r->getRegion($con,array($regionID));

        if(is_array($tmp)){
            $rtr = $tmp[0]['name'];
        }else{
            $rtr = $tmp['name'];
        }

        $pr = new pRate();
        $currencyID = Request::get("currency");
        $currency = $pr->getCurrency($con);

        $year = Request::get("year");
        $value = Request::get("value");

        $vpMonth = new VPMonth();
        
        $values = $vpMonth->base($con, $rtr, $regionID, $currencyID, $year, $value);

        $forRender = $values;
        $client = $values['client'];

        $tfArray = array();
        $odd = array();
        $even = array();
        
        $render = new renderVPMonth();

        

        return view('pAndR.VPMonthView.post',compact('render','region','currency', 'rtr', 'value', 'forRender', 'client', 'tfArray', 'odd', 'even'));
    }
}
