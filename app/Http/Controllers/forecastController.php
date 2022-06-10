<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Request;
use App\dataBase;
use App\region;
use App\salesRep;
use App\forecastRender;
use App\forecast;
use App\pRate;
use App\sql;
use App\base;
use App\PAndRRender;
use App\excel;
use Validator;

class forecastController extends Controller{
    
    public function byAEGet(){

    	$db = new dataBase();
        $default = $db->defaultConnection();
        $con = $db->openConnection($default);
        $r = new region();
        $sr = new salesRep();
        $render = new forecastRender();
        $pr = new pRate();

        $user = Request::session()->get('userName');
        $permission = Request::session()->get('userLevel');

        $region = $r->getRegion($con,null);
        $currency = $pr->getCurrency($con,null);

        $typeMsg = false;
        $msg = "";

        return view('pAndR.forecastByAE.get',compact('con','render','region','currency','permission','user','msg','typeMsg'));

    }

    public function byAEPost(){
    	$db = new dataBase();
        $render = new forecastRender();
        $base = new base();
        $r = new region();
        $pr = new pRate();
        $fcst = new forecast();        
        $default = $db->defaultConnection();
        $con = $db->openConnection($default);

        $cYear = intval( Request::get('year') );
        $pYear = $cYear - 1;
        $region = $r->getRegion($con,false);
        $currency = $pr->getCurrency($con,false);

        $typeMsg = false;
        $msg = "";

        $permission = Request::session()->get('userLevel');
        $regionName = Request::session()->get('userRegion');
        $user = Request::session()->get('userName');

        $regionID = Request::get('region');
        $salesRepID = array( Request::get('salesRep') );
        $currencyID = Request::get('currency');
        $value = Request::get('value');

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

        $tmp = $fcst->baseLoad($con,$r,$pr,$cYear,$pYear,$regionID,$salesRepID,$currencyID,$value);
        $forRender = $tmp;

        $regionExcel = $regionID;
        $salesRepExcel = Request::get('salesRep');
        $valueExcel = $value;
        $currencyExcel = $currencyID;
        $yearExcel = $cYear;
        $userRegionExcel = $regionName;

        $titleExcel = "PandR - AE.xlsx";
       
        return view('pAndR.forecastByAE.post',compact('render','region','currency','forRender','msg','typeMsg', 'regionExcel', 'salesRepExcel','valueExcel', 'currencyExcel','yearExcel','userRegionExcel', 'titleExcel'));

    }

    public function byAESave(){
        $db = new dataBase();
        $sql = new sql();
        $pr = new pRate();
        $r = new region();
        $fcst = new forecast();
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
        $brandPerClient = json_decode( base64_decode (Request::get ('brandsPerClient') ));
        $splitted = json_decode( base64_decode( Request::get('splitted') ));
        $submit = Request::get('options');

        $sourceSave = Request::get('sourceSave');

        $salesRepID = $salesRep->id;

        $currentMonth = intval(date('m')) -1;


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
                //var_dump(Request::get("fcstClient-DISC-$c-$m"));
                if (Request::get("fcstClient-DISC-$c-$m") == null) {
                    $manualEstimantionByClientDISC[$c][$m] = '0';    
                }else{
                    $manualEstimantionByClientDISC[$c][$m] = str_replace(',', '',Request::get("fcstClient-DISC-$c-$m"));     
                }                
            }
        }

        for ($c=0; $c < sizeof($client); $c++) { 
            for ($m=0; $m < sizeof($monthWQ); $m++) { 
                if (Request::get("fcstClient-SONY-$c-$m") == null) {
                    $manualEstimantionByClientSONY[$c][$m] = '0';    
                }else{
                    $manualEstimantionByClientSONY[$c][$m] = str_replace(',', '',Request::get("fcstClient-SONY-$c-$m"));    
                }
            }
        }

        $holderDISC = $manualEstimantionByClientDISC;
        $holderSONY = $manualEstimantionByClientSONY;

        for ($c=0; $c < sizeof($client); $c++) { 
            
            unset($holderDISC[$c][3]);
            unset($holderDISC[$c][7]);
            unset($holderDISC[$c][11]);
            unset($holderDISC[$c][15]);

            $holderDISC[$c] = array_values($holderDISC[$c]);

            unset($holderSONY[$c][3]);
            unset($holderSONY[$c][7]);
            unset($holderSONY[$c][11]);
            unset($holderSONY[$c][15]);

            $holderSONY[$c] = array_values($holderSONY[$c]);
        }

        $somaSeTemFcst = 0.0;

        

        for ($h=0; $h < sizeof($holderDISC); $h++) { 
            for ($i=0; $i < sizeof($holderDISC[$h]); $i++) { 
                if($i >= $currentMonth){
                    //var_dump($holderDISC[$h][$i]);
                    $somaSeTemFcst += $holderDISC[$h][$i];
                }
            }
        }

        for ($h=0; $h < sizeof($holderSONY); $h++) { 
            for ($i=0; $i < sizeof($holderSONY[$h]); $i++) { 
                if($i >= $currentMonth){
                    $somaSeTemFcst += $holderSONY[$h][$i];
                }
            }
        }

        
        if($somaSeTemFcst <= 0 ){
            
            $msg = "No FCST Value to Submit";

            if($value == "Gross"){$value = "gross";}else{$value = "net";}

            $forRender = $fcst->baseSaved($con,$r,$pr,$year,$regionID,$salesRep->id,$currencyID,$value,$manualEstimantionByClient);

            $region = $r->getRegion($con,false);
            $currency = $pr->getCurrency($con,false);

            $client = $forRender['client'];
            $tfArray = array();
            $odd = array();
            $even = array();
            $error = "Cannot Submit, There is no forecast on CRM Discovery (Sales Force)";
        
            return view('pAndR.AEView.post',compact('render','region','currency','forRender','client',"tfArray","odd","even", "error","sourceSave"));
            
        }

        
            //FUNÇÃO RESPONSÁVEL POR VALIDAR DE O MANUAL ESTIMATION CORRESPONDE AO ROLLINGFCST, REVALIDAR PARA FUTURO AO SER IMPLEMENTADO

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

                $forRender = $fcst->baseSaved($con,$r,$pr,$year,$regionID,$salesRep->id,$currencyID,$value,$manualEstimantionByClient);

                $region = $r->getRegion($con,false);
                $currency = $pr->getCurrency($con,false);

                $client = $forRender['client'];
                $tfArray = array();
                $odd = array();
                $even = array();

                $error = "Cannot Submit, Manual Estimation does not match with Rolling FCST";
        
                //return view('pAndR.AEView.post',compact('render','region','currency','forRender','client',"tfArray","odd","even", "error","sourceSave"));

            }
        }

        

        for ($c=0; $c < sizeof($client); $c++) { 
            
            unset($manualEstimantionByClientDISC[$c][3]);
            unset($manualEstimantionByClientDISC[$c][7]);
            unset($manualEstimantionByClientDISC[$c][11]);
            unset($manualEstimantionByClientDISC[$c][15]);

            $manualEstimantionByClientDISC[$c] = array_values($manualEstimantionByClientDISC[$c]);

            unset($manualEstimantionByClientSONY[$c][3]);
            unset($manualEstimantionByClientSONY[$c][7]);
            unset($manualEstimantionByClientSONY[$c][11]);
            unset($manualEstimantionByClientSONY[$c][15]);

            $manualEstimantionByClientSONY[$c] = array_values($manualEstimantionByClientSONY[$c]);
        }

        $today = $date;

        if($submit == "submit"){ $type = "salve";}else{$type = "save";}

        $read = $fcst->weekOfMonth($today);
        $read = "0".$read;
        $ID = $fcst->generateID($con,$sql,$pr,$type,$regionID,$year,$salesRep,$currencyID,$value,$read,$fcstMonth);        
        $currency = $pr->getCurrencybyName($con,$currencyID);
        
        $bool = $fcst->insertUpdate($con,$ID,$regionID,$salesRep,$currency,$value,$user,$year,$read,$date,$time,$fcstMonth,$manualEstimantionBySalesRep,$manualEstimantionByClientDISC,$manualEstimantionByClientSONY,$client,$splitted,$submit,$brandPerClient);

        
        if ($bool == "Updated") {
            $msg = "Forecast Updated";
            $typeMsg = "Success";
            return view('pAndR.forecastByAE.get',compact('con','render','region','currency','permission','user','msg','typeMsg'));
        }elseif($bool == "Created"){
            $msg = "Forecast Created";
            $typeMsg = "Success";
            return view('pAndR.forecastByAE.get',compact('con','render','region','currency','permission','user','msg','typeMsg'));
        }elseif ($bool == "Already Submitted") {
            $msg = "You already have submitted the Forecast";
            $typeMsg = "Error";
            return view('pAndR.forecastByAE.get',compact('con','render','region','currency','permission','user','msg','typeMsg'));
        }else{
            $msg = "Error";
            $typeMsg = "Error";            
            return view('pAndR.forecastByAE.get',compact('con','render','region','currency','permission','user','msg','typeMsg'));
        }
        
    }
}
