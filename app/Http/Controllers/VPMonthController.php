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
        $con = $db->openConnection("DLA");

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
        $value = json_decode(base64_decode(Request::get('value')));
        $user = json_decode(base64_decode(Request::get('user')));
        $year = json_decode(base64_decode(Request::get('year')));
        $brandsPerClient = json_decode(base64_decode(Request::get('brandsPerClient')));
        $submit = Request::get('options');

        $date = date('Y-m-d');
        $time = date('H:i');
        $fcstMonth = date('m');

        $month = $base->month;
        $monthWQ = $base->monthWQ;

        $client = json_decode(base64_decode(Request::get('client')));

        for ($m=0; $m < sizeof($monthWQ); $m++) {
            $target[$m] = $excel->fixExcelNumber(Request::get("target-$m"));
            $bookings[$m] = $excel->fixExcelNumber(Request::get("bookingE-$m"));
            $manualEstimation[$m] = $excel->fixExcelNumber(Request::get("manualEstimation-$m"));

        }

        unset($manualEstimation[3]);
        unset($manualEstimation[7]);
        unset($manualEstimation[11]);
        unset($manualEstimation[15]);

        for ($c=0; $c < sizeof($client); $c++) { 
            for ($m=0; $m < sizeof($monthWQ); $m++) { 
                $manualEstimantionByClient[$c][$m] = $excel->fixExcelNumber(Request::get("fcstClient-$c-$m"));
            }

        }

        $manualEstimation = array_values($manualEstimation);

        $vpMonth = new VPMonth();
        $values = $vpMonth->baseSave($con, $rtr, $regionID, $currencyID, $year, $value, $target, $bookings, $manualEstimation);

        $today = $date;
        $read = $vpMonth->weekOfMonth($today);
        $read = "0".$read;

        $ID = $vpMonth->generateID($con,$sql,$pr,"save",$regionID,$year,$currencyID,$value,$read,$fcstMonth);

        var_dump($ID);
    }

    public function get(){

    	$db = new dataBase();
        $con = $db->openConnection("DLA");
        $r = new region();        
        $render = new renderVPMonth();
        $pr = new pRate();

        $region = $r->getRegion($con,null);
        $currency = $pr->getCurrency($con,null);

		return view('pAndR.VPMonthView.get',compact('render','region','currency'));
    }

    public function post(){
    	
    	$db = new dataBase();
        $con = $db->openConnection("DLA");
        
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

        if (!$values) {
            return back()->with("error","Don't have a Forecast Saved");
        }

        $forRender = $values;
        $client = $values['client'];

        $tfArray = array();
        $odd = array();
        $even = array();
        
        $render = new renderVPMonth();

        return view('pAndR.VPMonthView.post',compact('render','region','currency', 'rtr', 'value', 'forRender', 'client', 'tfArray', 'odd', 'even'));
    }
}
