<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Request;
use App\region;
use App\renderVPMonth;
use App\salesRep;
use App\pRate;
use App\dataBase;
use App\VPMonth;

class VPMonthController extends Controller {
    
    public function get(){

    	$db = new dataBase();
        $con = $db->openConnection("DLA");
        $r = new region();
        $sr = new salesRep();
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

        $pRate = $pr->getCurrency($con, array($currencyID));

        $year = Request::get("year");
        $value = Request::get("value");

        $vpMonth = new VPMonth();

        $target = $vpMonth->getLinesValue($con, $regionID, $currencyID, $year, $value, $pRate, "Target");
        $forecast = $vpMonth->getLinesValue($con, $regionID, $currencyID, $year, $value, $pRate, "Rolling Fcast ".$year);
        $manualEstimation = $vpMonth->getLinesValue($con, $regionID, $currencyID, $year, $value, $pRate, "Manual Estimation");
        $pForecast = $vpMonth->getLinesValue($con, $regionID, $currencyID, $year, $value, $pRate, "Past Rolling Fcast");
        $bookings = $vpMonth->getLinesValue($con, $regionID, $currencyID, $year, $value, $pRate, "Bookings");
        $pBookings = $vpMonth->getLinesValue($con, $regionID, $currencyID, $year, $value, $pRate, ($year-1));

        $mtx = $vpMonth->assembler($target, $forecast, $pForecast, $manualEstimation, $bookings, $pBookings, $year, $rtr);

        $render = new renderVPMonth();

        return view('pAndR.VPMonthView.post',compact('render','region','currency', 'pRate', 'rtr', 'value', 'mtx'));
    }
}
