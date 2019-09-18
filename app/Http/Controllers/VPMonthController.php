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

        $year = Request::get("year");
        $value = Request::get("value");

        $vpMonth = new VPMonth();
        
        $values = $vpMonth->base($con, $rtr, $regionID, $currencyID, $year, $value);

        if (!$values) {
            return back()->with("Error","Don't have a Forecast Saved");
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
