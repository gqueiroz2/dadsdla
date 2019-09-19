<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Request;
use App\region;
use App\PAndRRender;
use App\VPPAndRRender;
use App\salesRep;
use App\pRate;
use App\dataBase;
use App\VP;
use App\sql;
use App\base;

class VPController extends Controller
{
    public function get(){

    	$db = new dataBase();
        $con = $db->openConnection("DLA");
        $r = new region();
        $sr = new salesRep();
        $render = new PAndRRender();
        $pr = new pRate();

        $region = $r->getRegion($con,null);
        $currency = $pr->getCurrency($con,null);

		return view('pAndR.VPView.get',compact('render','region','currency'));
    }

    public function post(){
        $db = new dataBase();
        $con = $db->openConnection("DLA");
        $r = new region();
        $sr = new salesRep();
        $render = new VPPAndRRender();
        $pr = new pRate();
        $vp = new vp();
        $sql = new sql();
        $base = new base();

        $cYear = intval( Request::get('year') );
        $pYear = $cYear - 1;

        $regionID = Request::get("region");

        $region = $r->getRegion($con,null);
        $currency = $pr->getCurrency($con,null);

        $fcstInfo = $vp->getForecast($con,$sql,$regionID);

        $forRender = $vp->base($con,$r,$pr,$cYear,$pYear);

        $salesRepListOfSubmit = $forRender["salesRepListOfSubmit"];
        

        if($forRender){
            $client = $forRender['client'];
        }else{
            $client = false;
        }

        return view('pAndR.VPView.post',compact('base','render','region','currency','forRender','client','salesRepListOfSubmit'));
    }
}
