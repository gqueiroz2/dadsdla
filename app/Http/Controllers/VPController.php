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
use App\brand;
use App\excel;
use Validator;

class VPController extends Controller
{
    public function save(){

        $db = new dataBase(); 
        $sql = new sql();
        $pr = new pRate();
        $r = new region();
        $vp = new VP();
        $base = new base();
        $render = new PAndRRender();
        $excel = new excel();

        $con = $db->openConnection("DLA");  

        $excel = new excel();

        $client = json_decode(base64_decode(Request::get('client')));
        $percentage = json_decode(base64_decode(Request::get('percentage')));
        $region = json_decode(base64_decode(Request::get('region')));
        $currency = json_decode(base64_decode(Request::get('currency')));
        $value = json_decode(base64_decode(Request::get('value')));
        $cYear = json_decode(base64_decode(Request::get('cYear')));
        $submit = Request::get('options');

        for ($p=0; $p <sizeof($percentage); $p++) { 
            $totalFCST[$p] = $excel->fixExcelNumber(Request::get("clientRF-Fy-$p"));
        }

        $date = date('Y-m-d');
        $time = date('H:i');
        $fcstMonth = date('m');

        $month = $base->month;
        $monthWQ = $base->monthWQ;

        if ($value == "Gross") {
            $value = "gross";
        }else{
            $value = "net";
        }

        $currency = $pr->getCurrency($con,array($currency))[0];

        $region = $r->getRegion($con,array($region))[0];

        $bool = $vp->saveValues($con,$date,$cYear,$value,$submit,$currency,$percentage,$totalFCST,$region);


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
