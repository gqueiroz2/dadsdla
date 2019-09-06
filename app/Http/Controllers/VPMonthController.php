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
}
