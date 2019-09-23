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
        $r = new region();
        $ae = new AE();
        $base = new base();
        $render = new PAndRRender();
        $excel = new excel();

        $con = $db->openConnection("DLA");  

        $regionID = json_decode( base64_decode( Request::get('region') ));
        $salesRep = json_decode( base64_decode( Request::get('salesRep') ));
        $currencyID = json_decode( base64_decode( Request::get('currency') ));
        $value = json_decode( base64_decode( Request::get('value') ));
        $user = json_decode( base64_decode( Request::get('user') ));
        $year = json_decode( base64_decode( Request::get('year') ));
        $brandsPerClient = json_decode( base64_decode( Request::get('brandsPerClient') ));
        $splitted = json_decode( base64_decode( Request::get('splitted') ));
        $submit = Request::get('options');

        $salesRepID = $salesRep->id;

        for ($c=0; $c < sizeof($brandsPerClient); $c++) {
            $saida[$c] = array();
            $brandPerClient[$c] = "";
            if($brandsPerClient[$c]){
                for ($p=0; $p < sizeof($brandsPerClient[$c]); $p++) {
                    $brandsPerClient[$c][$p] = explode(";", $brandsPerClient[$c][$p]->brand);
                }
                for($p=0; $p <sizeof($brandsPerClient[$c]) ; $p++){
                    for ($b=0; $b <sizeof($brandsPerClient[$c][$p]) ; $b++) { 
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
        }

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

        }
        for ($c=0; $c < sizeof($client); $c++) { 

            $passTotal[$c] = $excel->fixExcelNumber(Request::get("passTotal-$c"));
            $totalClient[$c] = $excel->fixExcelNumber(Request::get("totalClient-$c"));

            if ($passTotal[$c] != $totalClient[$c] && $submit == "submit" && ($splitted == false || ($splitted == true && $splitted[$c]->splitted == true && $splitted[$c]->owner == true) || $splitted[$c]->splitted == false) ) {
                $msg = "Incorrect value submited";

                if ($value == "Gross") {
                    $value = "gross";
                }else{
                    $value = "net";
                }

                $forRender = $ae->baseSaved($con,$r,$pr,$year,$regionID,$salesRep->id,$currencyID,$value,$manualEstimantionByClient);

                $region = $r->getRegion($con,false);
                $currency = $pr->getCurrency($con,false);

                $client = $forRender['client'];
                $tfArray = array();
                $odd = array();
                $even = array();

                $error = "Cannot Submit, Manual Estimation does not match with Rolling FCST";
        
                return view('pAndR.AEView.post',compact('render','region','currency','forRender','client',"tfArray","odd","even", "error"));

            }
        }

        for ($c=0; $c < sizeof($client); $c++) { 
            
            unset($manualEstimantionByClient[$c][3]);
            unset($manualEstimantionByClient[$c][7]);
            unset($manualEstimantionByClient[$c][11]);
            unset($manualEstimantionByClient[$c][15]);

            $manualEstimantionByClient[$c] = array_values($manualEstimantionByClient[$c]);
        }

        /*
            kind,region,year,salesRep,currency,value,week,month
        */
        $today = $date;

        if ($submit == "submit") {
            $read = "salve";
        }else{
            $read = "save";
        }

        $read = $ae->weekOfMonth($today);
        $read = "0".$read;

        $ID = $ae->generateID($con,$sql,$pr,$read,$regionID,$year,$salesRep,$currencyID,$value,$read,$fcstMonth);
        
        $currency = $pr->getCurrencybyName($con,$currencyID);

        $bool = $ae->insertUpdate($con,$ID,$regionID,$salesRep,$currency,$value,$user,$year,$read,$date,$time,$fcstMonth,$manualEstimantionBySalesRep,$manualEstimantionByClient,$client,$splitted,$submit,$brandPerClient);


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
            //return back()->with("Error",$msg);
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
        
        $tmp = $ae->baseLoad($con,$r,$pr,$cYear,$pYear);

        if (!$tmp) {
            return back()->with("Error","Don't have a Forecast Saved");
        }

        $forRender = $tmp;
        $client = $tmp['client'];
        $tfArray = array();
        $odd = array();
        $even = array();

        $error = false;

        return view('pAndR.AEView.post',compact('render','region','currency','forRender','client',"tfArray","odd","even","error"));
    }

}
