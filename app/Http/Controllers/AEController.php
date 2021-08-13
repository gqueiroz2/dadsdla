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
    
    public function get(){
        $db = new dataBase();
        $b = new base();
        $default = $db->defaultConnection();
        $con = $db->openConnection($default);
        $r = new region();
        $sr = new salesRep();
        $render = new PAndRRender();
        $pr = new pRate();

        $user = Request::session()->get('userName');
        $permission = Request::session()->get('userLevel');

        $region = $r->getRegion($con,null);
        $currency = $pr->getCurrency($con,null);

        $typeMsg = false;
        $msg = "";

        return view('pAndR.AEView.get',compact('con','render','region','currency','permission','user','msg','typeMsg'));
    }

    public function post(){

        $db = new dataBase();
        $b = new base();
        $render = new PAndRRender();
        $r = new region();
        $pr = new pRate();
        $ae = new AE();        
        $default = $db->defaultConnection();
        $con = $db->openConnection($default);

        $cYear = intval( Request::get('year') );
        $pYear = $cYear - 1;
        $region = $r->getRegion($con,false);
        $currency = $pr->getCurrency($con,false);
        $permission = Request::session()->get('userLevel');
        $user = Request::session()->get('userName');

        $regionID = Request::get('region');
        $salesRepID = array( Request::get('salesRep') );
        $currencyID = Request::get('currency');
        $value = Request::get('value');

        $regionName = Request::session()->get('userRegion');


        $validator = Validator::make(Request::all(),[
            'region' => 'required',
            'year' => 'required',
            'currency' => 'required',
            'value' => 'required',
            'salesRep' => 'required'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        
        $tmp = $ae->baseLoad($con,$r,$pr,$cYear,$pYear,$regionID,$salesRepID,$currencyID,$value);

        if (!$tmp) {
            /* $msg = "Don't have a Forecast Saved"; $typeMsg = "Error"; return view ('pAndR.AEView.get',compact('con','render','region','currency','permission','user','msg','typeMsg'));*/
        }

        $forRender = $tmp;

        $sourceSave = $forRender['sourceSave'];
        $client = $tmp['client'];
        $tfArray = array();
        $odd = array();
        $even = array();

        $error = false;

        //lines of sales rep table
        $rollingSalesRep = $forRender['executiveRevenueCYear'];
        $pending = $forRender['pending'];
        $RFvsTarget = $forRender['RFvsTarget'];

        $yearExcel = $cYear;
        $clientExcel = $client;
        $currencyExcel = $currencyID;
        $regionExcel = $regionID;
        $valueExcel = $value;
        $salesRepExcel = Request::get("salesRep");
        $userRegionExcel = $regionName;

        $titleExcel = "PandR - AE.xlsx";

        return view('pAndR.AEView.post',compact('render','region','currency','forRender','client',"tfArray","odd","even","error","sourceSave", "titleExcel", "yearExcel",'tmp', "clientExcel","currencyExcel","regionExcel","valueExcel","salesRepExcel", 'userRegionExcel'));
    }
    
    public function save(){
        $db = new dataBase();
        $sql = new sql();
        $pr = new pRate();
        $r = new region();
        $ae = new AE();
        $base = new base();
        $render = new PAndRRender();
        $excel = new excel();

        $default = $db->defaultConnection();
        $con = $db->openConnection($default);

        $user = Request::session()->get('userName');
        $permission = Request::session()->get('userLevel');

        $region = $r->getRegion($con,null);
        $currency = $pr->getCurrency($con,null);

        $regionID = json_decode( base64_decode( Request::get('region') ));
        $salesRep = json_decode( base64_decode( Request::get('salesRep') ));
        $currencyID = json_decode( base64_decode( Request::get('currency') ));
        $value = json_decode( base64_decode( Request::get('value') ));
        $user = json_decode( base64_decode( Request::get('user') ));
        $year = json_decode( base64_decode( Request::get('year') ));
        $brandsPerClient = json_decode( base64_decode (Request::get ('brandsPerClient') ));
        $splitted = json_decode( base64_decode( Request::get('splitted') ));
        $submit = Request::get('options');

        $sourceSave = Request::get('sourceSave');

        $salesRepID = $salesRep->id;

        $currentMonth = intval(date('m')) -1;

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
            $manualEstimantionBySalesRep[$m] = $excel->fixExcelNumberWithComma(Request::get("fcstSalesRep-$m"));
        }

        unset($manualEstimantionBySalesRep[3]);
        unset($manualEstimantionBySalesRep[7]);
        unset($manualEstimantionBySalesRep[11]);
        unset($manualEstimantionBySalesRep[15]);

        $manualEstimantionBySalesRep = array_values($manualEstimantionBySalesRep);

        for ($c=0; $c < sizeof($client); $c++) { 
            for ($m=0; $m < sizeof($monthWQ); $m++) { 
                $manualEstimantionByClient[$c][$m] = $excel->fixExcelNumberWithComma(str_replace(".", "", Request::get("fcstClient-$c-$m")));
            }
        }

        $holder = $manualEstimantionByClient;

        for ($c=0; $c < sizeof($client); $c++) { 
            
            unset($holder[$c][3]);
            unset($holder[$c][7]);
            unset($holder[$c][11]);
            unset($holder[$c][15]);

            $holder[$c] = array_values($holder[$c]);
        }

        $somaSeTemFcst = 0.0;

        for ($h=0; $h < sizeof($holder); $h++) { 
            for ($i=0; $i < sizeof($holder[$h]); $i++) { 
                if($i >= $currentMonth){
                    $somaSeTemFcst += $holder[$h][$i];
                }
            }
        }
        
        if($somaSeTemFcst <= 0 ){
            $msg = "No FCST Value to Submit";

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
            $error = "Cannot Submit, There is no forecast on CRM Discovery (Sales Force)";
        
            return view('pAndR.AEView.post',compact('render','region','currency','forRender','client',"tfArray","odd","even", "error","sourceSave"));
        }

        for ($c=0; $c < sizeof($client); $c++) { 
            $passTotal[$c] = $excel->fixExcelNumberWithComma(Request::get("passTotal-$c"));
            $totalClient[$c] = $excel->fixExcelNumberWithComma(Request::get("totalClient-$c"));
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
        
                return view('pAndR.AEView.post',compact('render','region','currency','forRender','client',"tfArray","odd","even", "error","sourceSave"));

            }
        }

        for ($c=0; $c < sizeof($client); $c++) { 
            
            unset($manualEstimantionByClient[$c][3]);
            unset($manualEstimantionByClient[$c][7]);
            unset($manualEstimantionByClient[$c][11]);
            unset($manualEstimantionByClient[$c][15]);

            $manualEstimantionByClient[$c] = array_values($manualEstimantionByClient[$c]);
        }

        $today = $date;

        if ($submit == "submit") {
            $type = "salve";
        }else{
            $type = "save";
        }

        $read = $ae->weekOfMonth($today);
        $read = "0".$read;

        $ID = $ae->generateID($con,$sql,$pr,$type,$regionID,$year,$salesRep,$currencyID,$value,$read,$fcstMonth);
        
        $currency = $pr->getCurrencybyName($con,$currencyID);
        
        $bool = $ae->insertUpdate($con,$ID,$regionID,$salesRep,$currency,$value,$user,$year,$read,$date,$time,$fcstMonth,$manualEstimantionBySalesRep,$manualEstimantionByClient,$client,$splitted,$submit,$brandPerClient);
        
        if ($bool == "Updated") {
            $msg = "Forecast Updated";
            $typeMsg = "Success";
            return view('pAndR.AEView.get',compact('con','render','region','currency','permission','user','msg','typeMsg'));
        }elseif($bool == "Created"){
            $msg = "Forecast Created";
            $typeMsg = "Success";
            return view('pAndR.AEView.get',compact('con','render','region','currency','permission','user','msg','typeMsg'));
        }elseif ($bool == "Already Submitted") {
            $msg = "You already have submitted the Forecast";
            $typeMsg = "Error";
            return view('pAndR.AEView.get',compact('con','render','region','currency','permission','user','msg','typeMsg'));
        }else{
            $msg = "Error";
            $typeMsg = "Error";
            return view('pAndR.AEView.get',compact('con','render','region','currency','permission','user','msg','typeMsg'));
        }
        
        
    }

    

    
}