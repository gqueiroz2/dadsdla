<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Request;
use App\region;
use App\PAndRRender;
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
        $render = new PAndRRender();
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
        $currencyID = Request::get("currency");

        $year = intval(date('Y'));

        $vpMonth = new VPMonth();

        $target = $vpMonth->getLinesValue($con, $regionID, $currencyID, $year, "Target");
        $forecast = $vpMonth->getLinesValue($con, $regionID, $currencyID, $year, "Roling Fcast ".$year);
        $bookings = $vpMonth->getLinesValue($con, $regionID, $currencyID, $year, "Bookings");
        $pBookings = $vpMonth->getLinesValue($con, $regionID, $currencyID, $year, ($year-1));

        $mtx = $vpMonth->assembler($target, $forecast, $bookings, $pBookings, $year);

        var_dump($mtx);
    }
}
