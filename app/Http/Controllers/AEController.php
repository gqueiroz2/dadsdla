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
        $brandsPerClient = json_decode( base64_decode( Request::get('brandsPerClient') ));
        $splitted = json_decode( base64_decode( Request::get('splitted') ));
        $submit = Request::get('options');

        $sourceSave = Request::get('sourceSave');

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

            //kind,region,year,salesRep,currency,value,week,month
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

        $typeMsg = false;
        $msg = "";

		return view('pAndR.AEView.get',compact('con','render','region','currency','permission','user','msg','typeMsg'));
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
        $permission = Request::session()->get('userLevel');
        $user = Request::session()->get('userName');

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
        
        $tmp = $ae->baseLoad($con,$r,$pr,$cYear,$pYear);

        if (!$tmp) {
            $msg = "Don't have a Forecast Saved";
            $typeMsg = "Error";
            return view('pAndR.AEView.get',compact('con','render','region','currency','permission','user','msg','typeMsg'));
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

        //lines of clients table
        $rollingClients = $forRender['lastRollingFCST'];
        $manual = $forRender['rollingFCST'];

        return view('pAndR.AEView.post',compact('render','region','currency','forRender','client',"tfArray","odd","even","error","sourceSave"));
    }

    /*
    var editedValue = $(this).val();

                        $.ajax({
                            url:"/ajaxPAndR/changeVal",
                            method:"POST",
                            data:{editedValue},
                            success: function(output){
                                $("#clientRF-"+{{$c}}+"-"+{{$m}}).val(output);

                                if ({{$m}} == 0 || {{$m}} == 1 || {{$m}} == 2 ) {
                                    var firstValue = $("#clientRF-"+{{$c}}+"-0").val();
                                    var secondValue = $("#clientRF-"+{{$c}}+"-1").val();
                                    var thirdValue = $("#clientRF-"+{{$c}}+"-2").val();
                                    var p = 3;
                                }else if ({{$m}} == 4 || {{$m}} == 5 || {{$m}} == 6 ) {
                                    var firstValue = $("#clientRF-"+{{$c}}+"-4").val();
                                    var secondValue = $("#clientRF-"+{{$c}}+"-5").val();
                                    var thirdValue = $("#clientRF-"+{{$c}}+"-6").val();
                                    var p = 7;
                                }else if ({{$m}} == 8 || {{$m}} == 9 || {{$m}} == 10 ) {
                                    var firstValue = $("#clientRF-"+{{$c}}+"-8").val();
                                    var secondValue = $("#clientRF-"+{{$c}}+"-9").val();
                                    var thirdValue = $("#clientRF-"+{{$c}}+"-10").val();
                                    var p = 11;
                                }else if ({{$m}} == 12 || {{$m}} == 13 || {{$m}} == 14 ) {
                                    var firstValue = $("#clientRF-"+{{$c}}+"-12").val();
                                    var secondValue = $("#clientRF-"+{{$c}}+"-13").val();
                                    var thirdValue = $("#clientRF-"+{{$c}}+"-14").val();
                                    var p = 15;
                                }

                                $.ajax({
                                    url:"/ajaxPAndR/reCalculateQuarterValues",
                                    method:"POST",
                                    data:{firstValue, secondValue, thirdValue},
                                    success: function(output){
                                        $("#clientRF-"+"{{$c}}"+"-"+p).val(output);

                                        var Q1 = $("#clientRF-"+{{$c}}+"-3").val();
                                        var Q2 = $("#clientRF-"+{{$c}}+"-7").val();
                                        var Q3 = $("#clientRF-"+{{$c}}+"-11").val();
                                        var Q4 = $("#clientRF-"+{{$c}}+"-15").val();

                                        $.ajax({
                                            url:"/ajaxPAndR/reCalculateTotalVal",
                                            method:"POST",
                                            data:{Q1, Q2, Q3, Q4},
                                            success: function(output) {
                                                $("#totalClient-"+{{$c}}).val(output);

                                                var totalClient = $("#totalClient-"+{{$c}}).val();
                                                var total = $("#passTotal-"+{{$c}}).val();

                                                $.ajax({
                                                    url:"/ajaxPAndR/verifyVal",
                                                    method:"POST",
                                                    data:{totalClient, total},
                                                    success: function(output){
                                                        Temp3 = output;

                                                        if (Temp3 == 1) {
                                                            $("#client-"+{{$c}}).css("background-color","red");
                                                        }else{
                                                            $("#client-"+{{$c}}).css("background-color","");
                                                        }

                                                        var rf = 0;

                                                        @for($c2=0;$c2<sizeof($client);$c2++)

                                                            var splitted = $("#splitted-"+{{$c2}}).val();
                                                            var client = $("#clientRF-"+{{$c2}}+"-"+{{$m}}).val();

                                                            $.ajax({
                                                                url:"/ajaxPAndR/splittedClients",
                                                                method:"POST",
                                                                data:{client, splitted},
                                                                success: function(output){
                                                                    rf += output;
                                                                },
                                                                error: function(xhr, ajaxOptions,thrownError){
                                                                    alert(xhr.status+" "+thrownError);
                                                                }
                                                            });
                                                        @endfor

                                                        var transform = "Comma";
                                                        
                                                        $.ajax({
                                                            url:"/ajaxPAndR/transformVal",
                                                            method:"POST",
                                                            data:{rf,transform},
                                                            success: function(output){
                                                                rf = output;

                                                                $("#rf-"+{{$m}}).val(rf);
                                                                
                                                                if ({{$m}} == 0 || {{$m}} == 1 || {{$m}} == 2 ) {
                                                                    var month = 0;
                                                                    @for($c2=0;$c2<sizeof($client);$c2++)

                                                                        var splitted = $("#splitted-"+{{$c2}}).val();
                                                                        var client = $("#clientRF-"+{{$c2}}+"-3").val();

                                                                        $.ajax({
                                                                            url:"/ajaxPAndR/splittedClients",
                                                                            method:"POST",
                                                                            data:{client, splitted},
                                                                            success: function(output){
                                                                                month += output;
                                                                            },
                                                                            error: function(xhr, ajaxOptions,thrownError){
                                                                                alert(xhr.status+" "+thrownError);
                                                                            }
                                                                        });
                                                                    @endfor

                                                                    $.ajax({
                                                                        url:"/ajaxPAndR/transformVal",
                                                                        method:"POST",
                                                                        data:{month,transform},
                                                                        success: function(output){
                                                                            month = output;
                                                                            $("#rf-3").val(month);
                                                                        },
                                                                        error: function(xhr, ajaxOptions,thrownError){
                                                                            alert(xhr.status+" "+thrownError);
                                                                        }
                                                                    });
                                                                }else if ({{$m}} == 4 || {{$m}} == 5 || {{$m}} == 6 ) {
                                                                    var month = 0;
                                                                    @for($c2=0;$c2<sizeof($client);$c2++)

                                                                        var splitted = $("#splitted-"+{{$c2}}).val();
                                                                        var client = $("#clientRF-"+{{$c2}}+"-7").val();

                                                                        $.ajax({
                                                                            url:"/ajaxPAndR/splittedClients",
                                                                            method:"POST",
                                                                            data:{client, splitted},
                                                                            success: function(output){
                                                                                month += output;
                                                                            },
                                                                            error: function(xhr, ajaxOptions,thrownError){
                                                                                alert(xhr.status+" "+thrownError);
                                                                            }
                                                                        });
                                                                    @endfor

                                                                    $.ajax({
                                                                        url:"/ajaxPAndR/transformVal",
                                                                        method:"POST",
                                                                        data:{month,transform},
                                                                        success: function(output){
                                                                            month = output;
                                                                            $("#rf-7").val(month);
                                                                        },
                                                                        error: function(xhr, ajaxOptions,thrownError){
                                                                            alert(xhr.status+" "+thrownError);
                                                                        }
                                                                    });
                                                                }else if ({{$m}} == 8 || {{$m}} == 9 || {{$m}} == 10 ) {
                                                                    var month = 0;
                                                                    @for($c2=0;$c2<sizeof($client);$c2++)

                                                                        var splitted = $("#splitted-"+{{$c2}}).val();
                                                                        var client = $("#clientRF-"+{{$c2}}+"-11").val();

                                                                        $.ajax({
                                                                            url:"/ajaxPAndR/splittedClients",
                                                                            method:"POST",
                                                                            data:{client, splitted},
                                                                            success: function(output){
                                                                                month += output;
                                                                            },
                                                                            error: function(xhr, ajaxOptions,thrownError){
                                                                                alert(xhr.status+" "+thrownError);
                                                                            }
                                                                        });
                                                                    @endfor

                                                                    $.ajax({
                                                                        url:"/ajaxPAndR/transformVal",
                                                                        method:"POST",
                                                                        data:{month,transform},
                                                                        success: function(output){
                                                                            month = output;
                                                                            $("#rf-11").val(month);
                                                                        },
                                                                        error: function(xhr, ajaxOptions,thrownError){
                                                                            alert(xhr.status+" "+thrownError);
                                                                        }
                                                                    });
                                                                }else if ({{$m}} == 12 || {{$m}} == 13 || {{$m}} == 14 ) {
                                                                    var month = 0;
                                                                    @for($c2=0;$c2<sizeof($client);$c2++)

                                                                        var splitted = $("#splitted-"+{{$c2}}).val();
                                                                        var client = $("#clientRF-"+{{$c2}}+"-15").val();

                                                                        $.ajax({
                                                                            url:"/ajaxPAndR/splittedClients",
                                                                            method:"POST",
                                                                            data:{client, splitted},
                                                                            success: function(output){
                                                                                month += output;
                                                                            },
                                                                            error: function(xhr, ajaxOptions,thrownError){
                                                                                alert(xhr.status+" "+thrownError);
                                                                            }
                                                                        });
                                                                    @endfor

                                                                    $.ajax({
                                                                        url:"/ajaxPAndR/transformVal",
                                                                        method:"POST",
                                                                        data:{month,transform},
                                                                        success: function(output){
                                                                            month = output;
                                                                            $("#rf-15").val(month);
                                                                        },
                                                                        error: function(xhr, ajaxOptions,thrownError){
                                                                            alert(xhr.status+" "+thrownError);
                                                                        }
                                                                    });
                                                                }

                                                                var Q1 = $("#rf-3").val();
                                                                var Q2 = $("#rf-7").val();
                                                                var Q3 = $("#rf-11").val();
                                                                var Q4 = $("#rf-15").val();

                                                                $.ajax({
                                                                    url:"/ajaxPAndR/reCalculateTotalVal",
                                                                    method:"POST",
                                                                    data:{Q1, Q2, Q3, Q4},
                                                                    success: function(output){
                                                                        var total = output;
                                                                        $("#total-total").val(total);

                                                                        @for($c2=0;$c2<sizeof($client);$c2++)
                                                                            
                                                                            var firstValue = $("#totalClient-"+{{$c2}}).val();
                                                                            var secondValue = total;
                                                                            var op = "/";
                                                                        
                                                                            $.ajax({
                                                                                url:"/ajaxPAndR/number",
                                                                                method:"POST",
                                                                                data:{firstValue, secondValue, op},
                                                                                success: function (output) {
                                                                                    var temp = output;
                                                                                    temp *= 100;
                                                                                    $.ajax({
                                                                                        url:"/ajaxPAndR/transformVal",
                                                                                        method:"POST",
                                                                                        data:{temp,transform},
                                                                                        success: function(output){
                                                                                            temp = output;

                                                                                            $("#totalPP2-"+{{$c2}}).val(temp);
                                                                                            $("#totalPP3-"+{{$c2}}).val(temp);
                                                                                        },
                                                                                        error: function(xhr, ajaxOptions,thrownError){
                                                                                            alert(xhr.status+" "+thrownError);
                                                                                        }
                                                                                    });
                                                                                },
                                                                                error: function(xhr, ajaxOptions,thrownError){
                                                                                    alert(xhr.status+" "+thrownError);
                                                                                }
                                                                            });
                                                                            
                                                                        @endfor

                                                                        var value = $("#clientRF-"+{{$c}}+"-"+{{$m}}).val();
                                                                        transform = "handleNumber";

                                                                        $.ajax({
                                                                            url:"/ajaxPAndR/transformVal",
                                                                            method:"POST",
                                                                            data:{value,transform},
                                                                            success: function(output){
                                                                                value = output;

                                                                                var PY = $("#PY-"+{{$c}}+"-"+{{$m}}).val();

                                                                                $.ajax({
                                                                                    url:"/ajaxPAndR/transformVal",
                                                                                    method:"POST",
                                                                                    data:{PY,transform},
                                                                                    success: function(output){
                                                                                        PY = output;

                                                                                        var tmp = value - PY;

                                                                                        transform = "Comma";

                                                                                        $.ajax({
                                                                                            url:"/ajaxPAndR/transformVal",
                                                                                            method:"POST",
                                                                                            data:{tmp,transform},
                                                                                            success: function(output){
                                                                                                tmp = output;

                                                                                                $("#RFvsPY-"+{{$c}}+"-"+{{$m}}).val(tmp);

                                                                                                if ({{$m}} == 0 || {{$m}} == 1 || {{$m}} == 2 ) {

                                                                                                    var firstValue = $("#RFvsPY-"+{{$c}}+"-0").val();
                                                                                                    var secondValue = $("#RFvsPY-"+{{$c}}+"-1").val();
                                                                                                    var thirdValue = $("#RFvsPY-"+{{$c}}+"-0").val();

                                                                                                    $.ajax({
                                                                                                        url:"/ajaxPAndR/reCalculateQuarterValues",
                                                                                                        method:"POST",
                                                                                                        data:{firstValue,secondValue,thirdValue},
                                                                                                        success: function(output){
                                                                                                            $("#RFvsPY-"+{{$c}}+"-3").val(output);
                                                                                                        },
                                                                                                        error: function(xhr, ajaxOptions,thrownError){
                                                                                                            alert(xhr.status+" "+thrownError);
                                                                                                        }
                                                                                                    });
                                                                                                }else if ({{$m}} == 4 || {{$m}} == 5 || {{$m}} == 6 ) {

                                                                                                    var firstValue = $("#RFvsPY-"+{{$c}}+"-4").val();
                                                                                                    var secondValue = $("#RFvsPY-"+{{$c}}+"-5").val();
                                                                                                    var thirdValue = $("#RFvsPY-"+{{$c}}+"-6").val();

                                                                                                    $.ajax({
                                                                                                        url:"/ajaxPAndR/reCalculateQuarterValues",
                                                                                                        method:"POST",
                                                                                                        data:{firstValue,secondValue,thirdValue},
                                                                                                        success: function(output){
                                                                                                            $("#RFvsPY-"+{{$c}}+"-7").val(output);
                                                                                                        },
                                                                                                        error: function(xhr, ajaxOptions,thrownError){
                                                                                                            alert(xhr.status+" "+thrownError);
                                                                                                        }
                                                                                                    });
                                                                                                }else if ({{$m}} == 8 || {{$m}} == 9 || {{$m}} == 10 ) {

                                                                                                    var firstValue = $("#RFvsPY-"+{{$c}}+"-8").val();
                                                                                                    var secondValue = $("#RFvsPY-"+{{$c}}+"-9").val();
                                                                                                    var thirdValue = $("#RFvsPY-"+{{$c}}+"-10").val();

                                                                                                    $.ajax({
                                                                                                        url:"/ajaxPAndR/reCalculateQuarterValues",
                                                                                                        method:"POST",
                                                                                                        data:{firstValue,secondValue,thirdValue},
                                                                                                        success: function(output){
                                                                                                            $("#RFvsPY-"+{{$c}}+"-11").val(output);
                                                                                                        },
                                                                                                        error: function(xhr, ajaxOptions,thrownError){
                                                                                                            alert(xhr.status+" "+thrownError);
                                                                                                        }
                                                                                                    });
                                                                                                }else if ({{$m}} == 12 || {{$m}} == 13 || {{$m}} == 14 ) {

                                                                                                    var firstValue = $("#RFvsPY-"+{{$c}}+"-12").val();
                                                                                                    var secondValue = $("#RFvsPY-"+{{$c}}+"-13").val();
                                                                                                    var thirdValue = $("#RFvsPY-"+{{$c}}+"-14").val();

                                                                                                    $.ajax({
                                                                                                        url:"/ajaxPAndR/reCalculateQuarterValues",
                                                                                                        method:"POST",
                                                                                                        data:{firstValue,secondValue,thirdValue},
                                                                                                        success: function(output){
                                                                                                            $("#RFvsPY-"+{{$c}}+"-15").val(output);
                                                                                                        },
                                                                                                        error: function(xhr, ajaxOptions,thrownError){
                                                                                                            alert(xhr.status+" "+thrownError);
                                                                                                        }
                                                                                                    });
                                                                                                }

                                                                                                var Q1 = $("#RFvsPY-"+{{$c}}+"-3").val();
                                                                                                var Q2 = $("#RFvsPY-"+{{$c}}+"-7").val();
                                                                                                var Q3 = $("#RFvsPY-"+{{$c}}+"-11").val();
                                                                                                var Q4 = $("#RFvsPY-"+{{$c}}+"-15").val();

                                                                                                $.ajax({
                                                                                                    url:"/ajaxPAndR/reCalculateTotalVal",
                                                                                                    method:"POST",
                                                                                                    data:{Q1,Q2,Q3,Q4},
                                                                                                    success: function(output){
                                                                                                        $("#totalRFvsPY-"+{{$c}}).val(output);
                                                                                                    },
                                                                                                    error: function(xhr, ajaxOptions,thrownError){
                                                                                                        alert(xhr.status+" "+thrownError);
                                                                                                    }
                                                                                                });

                                                                                                transform = "handleNumber";
                                                                                                var booking = $("#bookingE-"+{{$m}}).val();

                                                                                                $.ajax({
                                                                                                    url:"/ajaxPAndR/transformVal",
                                                                                                    method:"POST",
                                                                                                    data:{booking,transform},
                                                                                                    success: function(output){
                                                                                                        booking = output;
                                                                                                    },
                                                                                                    error: function(xhr, ajaxOptions,thrownError){
                                                                                                        alert(xhr.status+" "+thrownError);
                                                                                                    }
                                                                                                });

                                                                                                var Temp2 = $("#target-"+{{$m}}).val();

                                                                                                $.ajax({
                                                                                                    url:"/ajaxPAndR/transformVal",
                                                                                                    method:"POST",
                                                                                                    data:{Temp2,transform},
                                                                                                    success: function(output){
                                                                                                        Temp2 = output;
                                                                                                    },
                                                                                                    error: function(xhr, ajaxOptions,thrownError){
                                                                                                        alert(xhr.status+" "+thrownError);
                                                                                                    }
                                                                                                });

                                                                                                transform = "handleNumber";

                                                                                                $.ajax({
                                                                                                    url:"/ajaxPAndR/transformVal",
                                                                                                    method:"POST",
                                                                                                    data:{rf,transform},
                                                                                                    success: function(output){
                                                                                                        rf = output;
                                                                                                    },
                                                                                                    error: function(xhr, ajaxOptions,thrownError){
                                                                                                        alert(xhr.status+" "+thrownError);
                                                                                                    }
                                                                                                });

                                                                                                transform = "Comma";
                                                                                                var RFvsTarget = (rf-Temp2);

                                                                                                $.ajax({
                                                                                                    url:"/ajaxPAndR/transformVal",
                                                                                                    method:"POST",
                                                                                                    data:{RFvsTarget,transform},
                                                                                                    success: function(output){
                                                                                                        $("#RFvsTarget-"+{{$m}}).val(output);
                                                                                                    },
                                                                                                    error: function(xhr, ajaxOptions,thrownError){
                                                                                                        alert(xhr.status+" "+thrownError);
                                                                                                    }
                                                                                                });

                                                                                                var pending = (rf-booking);

                                                                                                $.ajax({
                                                                                                    url:"/ajaxPAndR/transformVal",
                                                                                                    method:"POST",
                                                                                                    data:{pending,transform},
                                                                                                    success: function(output){
                                                                                                        $("#pending-"+{{$m}}).val(output);
                                                                                                    },
                                                                                                    error: function(xhr, ajaxOptions,thrownError){
                                                                                                        alert(xhr.status+" "+thrownError);
                                                                                                    }
                                                                                                });

                                                                                                if (Temp2 == 0) {
                                                                                                    var Temp3 = 0+"%";
                                                                                                }else{
                                                                                                    var Temp3 = ((rf/Temp2)*100).toFixed(2);
                                                                                                    $.ajax({
                                                                                                        url:"/ajaxPAndR/transformVal",
                                                                                                        method:"POST",
                                                                                                        data:{Temp3,transform},
                                                                                                        success: function(output){
                                                                                                            Temp3 = output;
                                                                                                            Temp3 = Temp3 + "%";
                                                                                                        },
                                                                                                        error: function(xhr, ajaxOptions,thrownError){
                                                                                                            alert(xhr.status+" "+thrownError);
                                                                                                        }
                                                                                                    });
                                                                                                }

                                                                                                $("#achievement-"+{{$m}}).val(Temp3);

                                                                                                if ({{$m}} == 0 || {{$m}} == 1 || {{$m}} == 2 ) {

                                                                                                    var month = 0;
                                                                                                    transform = "handleNumber";
                                                                                                    @for($c2=0;$c2<sizeof($client);$c2++)
                                                                                                        var aux = $("#clientRF-"+{{$c2}}+"-3").val();
                                                                                                        $.ajax({
                                                                                                            url:"/ajaxPAndR/transformVal",
                                                                                                            method:"POST",
                                                                                                            data:{aux,transform},
                                                                                                            success: function(output){
                                                                                                                month += output;
                                                                                                            },
                                                                                                            error: function(xhr, ajaxOptions,thrownError){
                                                                                                                alert(xhr.status+" "+thrownError);
                                                                                                            }
                                                                                                        });
                                                                                                    @endfor

                                                                                                    var firstValue = $("#target-0").val();
                                                                                                    var secondValue = $("#target-1").val();
                                                                                                    var thirdValue = $("#target-2").val();

                                                                                                    $.ajax({
                                                                                                        url:"/ajaxPAndR/reCalculateQuarterValues",
                                                                                                        method:"POST",
                                                                                                        data:{firstValue,secondValue, thirdValue},
                                                                                                        success: function(output){
                                                                                                            var target = output;
                                                                                                        },
                                                                                                        error: function(xhr, ajaxOptions,thrownError){
                                                                                                            alert(xhr.status+" "+thrownError);
                                                                                                        }
                                                                                                    });

                                                                                                    var firstValue = $("#bookingE-0").val();
                                                                                                    var secondValue = $("#bookingE-1").val();
                                                                                                    var thirdValue = $("#bookingE-2").val();

                                                                                                    $.ajax({
                                                                                                        url:"/ajaxPAndR/reCalculateQuarterValues",
                                                                                                        method:"POST",
                                                                                                        data:{firstValue,secondValue, thirdValue},
                                                                                                        success: function(output){
                                                                                                            var booking = output;
                                                                                                        },
                                                                                                        error: function(xhr, ajaxOptions,thrownError){
                                                                                                            alert(xhr.status+" "+thrownError);
                                                                                                        }
                                                                                                    });

                                                                                                    var aux = (month-booking);
                                                                                                    transform = "Comma";

                                                                                                    $.ajax({
                                                                                                        url:"/ajaxPAndR/transformVal",
                                                                                                        method:"POST",
                                                                                                        data:{aux,transform},
                                                                                                        success: function(output){
                                                                                                            $("#pending-3").val(output);
                                                                                                        },
                                                                                                        error: function(xhr, ajaxOptions,thrownError){
                                                                                                            alert(xhr.status+" "+thrownError);
                                                                                                        }
                                                                                                    });

                                                                                                    var RFvsTargetQ = (month-target);              

                                                                                                    $.ajax({
                                                                                                        url:"/ajaxPAndR/transformVal",
                                                                                                        method:"POST",
                                                                                                        data:{RFvsTargetQ,transform},
                                                                                                        success: function(output){
                                                                                                            $("#RFvsTarget-3").val(output);
                                                                                                        },
                                                                                                        error: function(xhr, ajaxOptions,thrownError){
                                                                                                            alert(xhr.status+" "+thrownError);
                                                                                                        }
                                                                                                    });

                                                                                                    if (target == 0) {
                                                                                                        var Temp3 = 0+"%";
                                                                                                    }else{
                                                                                                        var Temp3 = ((month/target)*100).toFixed(2);

                                                                                                        $.ajax({
                                                                                                            url:"/ajaxPAndR/transformVal",
                                                                                                            method:"POST",
                                                                                                            data:{Temp3,transform},
                                                                                                            success: function(output){
                                                                                                                Temp3 = output;
                                                                                                            },
                                                                                                            error: function(xhr, ajaxOptions,thrownError){
                                                                                                                alert(xhr.status+" "+thrownError);
                                                                                                            }
                                                                                                        });

                                                                                                        Temp3 = Temp3 + "%";
                                                                                                    }

                                                                                                    $("#achievement-3").val(Temp3);
                                                                                                }else if ({{$m}} == 4 || {{$m}} == 5 || {{$m}} == 6 ) {

                                                                                                    var month = 0;
                                                                                                    transform = "handleNumber";

                                                                                                    @for($c2=0;$c2<sizeof($client);$c2++)
                                                                                                        var aux = $("#clientRF-"+{{$c2}}+"-7").val();
                                                                                                        $.ajax({
                                                                                                            url:"/ajaxPAndR/transformVal",
                                                                                                            method:"POST",
                                                                                                            data:{aux,transform},
                                                                                                            success: function(output){
                                                                                                                month += output;
                                                                                                            },
                                                                                                            error: function(xhr, ajaxOptions,thrownError){
                                                                                                                alert(xhr.status+" "+thrownError);
                                                                                                            }
                                                                                                        });
                                                                                                    @endfor

                                                                                                    var firstValue = $("#target-4").val();
                                                                                                    var secondValue = $("#target-5").val();
                                                                                                    var thirdValue = $("#target-6").val();

                                                                                                    $.ajax({
                                                                                                        url:"/ajaxPAndR/reCalculateQuarterValues",
                                                                                                        method:"POST",
                                                                                                        data:{firstValue,secondValue, thirdValue},
                                                                                                        success: function(output){
                                                                                                            var target = output;
                                                                                                        },
                                                                                                        error: function(xhr, ajaxOptions,thrownError){
                                                                                                            alert(xhr.status+" "+thrownError);
                                                                                                        }
                                                                                                    });

                                                                                                    var firstValue = $("#bookingE-4").val();
                                                                                                    var secondValue = $("#bookingE-5").val();
                                                                                                    var thirdValue = $("#bookingE-6").val();

                                                                                                    $.ajax({
                                                                                                        url:"/ajaxPAndR/reCalculateQuarterValues",
                                                                                                        method:"POST",
                                                                                                        data:{firstValue,secondValue, thirdValue},
                                                                                                        success: function(output){
                                                                                                            var booking = output;
                                                                                                        },
                                                                                                        error: function(xhr, ajaxOptions,thrownError){
                                                                                                            alert(xhr.status+" "+thrownError);
                                                                                                        }
                                                                                                    });

                                                                                                    var aux = (month-booking);
                                                                                                    transform = "Comma";

                                                                                                    $.ajax({
                                                                                                        url:"/ajaxPAndR/transformVal",
                                                                                                        method:"POST",
                                                                                                        data:{aux,transform},
                                                                                                        success: function(output){
                                                                                                            $("#pending-7").val(output);
                                                                                                        },
                                                                                                        error: function(xhr, ajaxOptions,thrownError){
                                                                                                            alert(xhr.status+" "+thrownError);
                                                                                                        }
                                                                                                    });

                                                                                                    var RFvsTargetQ = (month-target);              

                                                                                                    $.ajax({
                                                                                                        url:"/ajaxPAndR/transformVal",
                                                                                                        method:"POST",
                                                                                                        data:{RFvsTargetQ,transform},
                                                                                                        success: function(output){
                                                                                                            $("#RFvsTarget-7").val(output);
                                                                                                        },
                                                                                                        error: function(xhr, ajaxOptions,thrownError){
                                                                                                            alert(xhr.status+" "+thrownError);
                                                                                                        }
                                                                                                    });

                                                                                                    if (target == 0) {
                                                                                                        var Temp3 = 0+"%";
                                                                                                    }else{
                                                                                                        var Temp3 = ((month/target)*100).toFixed(2);

                                                                                                        $.ajax({
                                                                                                            url:"/ajaxPAndR/transformVal",
                                                                                                            method:"POST",
                                                                                                            data:{Temp3,transform},
                                                                                                            success: function(output){
                                                                                                                Temp3 = output;
                                                                                                            },
                                                                                                            error: function(xhr, ajaxOptions,thrownError){
                                                                                                                alert(xhr.status+" "+thrownError);
                                                                                                            }
                                                                                                        });

                                                                                                        Temp3 = Temp3 + "%";
                                                                                                    }

                                                                                                    $("#achievement-7").val(Temp3);

                                                                                                }else if ({{$m}} == 8 || {{$m}} == 9 || {{$m}} == 10 ) {

                                                                                                    var month = 0;
                                                                                                    transform = "handleNumber";

                                                                                                    @for($c2=0;$c2<sizeof($client);$c2++)
                                                                                                        var aux = $("#clientRF-"+{{$c2}}+"-11").val();
                                                                                                        $.ajax({
                                                                                                            url:"/ajaxPAndR/transformVal",
                                                                                                            method:"POST",
                                                                                                            data:{aux,transform},
                                                                                                            success: function(output){
                                                                                                                month += output;
                                                                                                            },
                                                                                                            error: function(xhr, ajaxOptions,thrownError){
                                                                                                                alert(xhr.status+" "+thrownError);
                                                                                                            }
                                                                                                        });
                                                                                                    @endfor

                                                                                                    var firstValue = $("#target-8").val();
                                                                                                    var secondValue = $("#target-9").val();
                                                                                                    var thirdValue = $("#target-10").val();

                                                                                                    $.ajax({
                                                                                                        url:"/ajaxPAndR/reCalculateQuarterValues",
                                                                                                        method:"POST",
                                                                                                        data:{firstValue,secondValue, thirdValue},
                                                                                                        success: function(output){
                                                                                                            var target = output;
                                                                                                        },
                                                                                                        error: function(xhr, ajaxOptions,thrownError){
                                                                                                            alert(xhr.status+" "+thrownError);
                                                                                                        }
                                                                                                    });

                                                                                                    var firstValue = $("#bookingE-8").val();
                                                                                                    var secondValue = $("#bookingE-9").val();
                                                                                                    var thirdValue = $("#bookingE-10").val();

                                                                                                    $.ajax({
                                                                                                        url:"/ajaxPAndR/reCalculateQuarterValues",
                                                                                                        method:"POST",
                                                                                                        data:{firstValue,secondValue, thirdValue},
                                                                                                        success: function(output){
                                                                                                            var booking = output;
                                                                                                        },
                                                                                                        error: function(xhr, ajaxOptions,thrownError){
                                                                                                            alert(xhr.status+" "+thrownError);
                                                                                                        }
                                                                                                    });

                                                                                                    var aux = (month-booking);
                                                                                                    transform = "Comma";

                                                                                                    $.ajax({
                                                                                                        url:"/ajaxPAndR/transformVal",
                                                                                                        method:"POST",
                                                                                                        data:{aux,transform},
                                                                                                        success: function(output){
                                                                                                            $("#pending-11").val(output);
                                                                                                        },
                                                                                                        error: function(xhr, ajaxOptions,thrownError){
                                                                                                            alert(xhr.status+" "+thrownError);
                                                                                                        }
                                                                                                    });

                                                                                                    var RFvsTargetQ = (month-target);              

                                                                                                    $.ajax({
                                                                                                        url:"/ajaxPAndR/transformVal",
                                                                                                        method:"POST",
                                                                                                        data:{RFvsTargetQ,transform},
                                                                                                        success: function(output){
                                                                                                            $("#RFvsTarget-11").val(output);
                                                                                                        },
                                                                                                        error: function(xhr, ajaxOptions,thrownError){
                                                                                                            alert(xhr.status+" "+thrownError);
                                                                                                        }
                                                                                                    });

                                                                                                    if (target == 0) {
                                                                                                        var Temp3 = 0+"%";
                                                                                                    }else{
                                                                                                        var Temp3 = ((month/target)*100).toFixed(2);

                                                                                                        $.ajax({
                                                                                                            url:"/ajaxPAndR/transformVal",
                                                                                                            method:"POST",
                                                                                                            data:{Temp3,transform},
                                                                                                            success: function(output){
                                                                                                                Temp3 = output;
                                                                                                            },
                                                                                                            error: function(xhr, ajaxOptions,thrownError){
                                                                                                                alert(xhr.status+" "+thrownError);
                                                                                                            }
                                                                                                        });

                                                                                                        Temp3 = Temp3 + "%";
                                                                                                    }

                                                                                                    $("#achievement-11").val(Temp3);
                                                                                                }else if ({{$m}} == 12 || {{$m}} == 13 || {{$m}} == 14 ) {

                                                                                                    var month = 0;
                                                                                                    transform = "handleNumber";

                                                                                                    @for($c2=0;$c2<sizeof($client);$c2++)
                                                                                                        var aux = $("#clientRF-"+{{$c2}}+"-15").val();
                                                                                                        $.ajax({
                                                                                                            url:"/ajaxPAndR/transformVal",
                                                                                                            method:"POST",
                                                                                                            data:{aux,transform},
                                                                                                            success: function(output){
                                                                                                                month += output;
                                                                                                            },
                                                                                                            error: function(xhr, ajaxOptions,thrownError){
                                                                                                                alert(xhr.status+" "+thrownError);
                                                                                                            }
                                                                                                        });
                                                                                                    @endfor

                                                                                                    var firstValue = $("#target-12").val();
                                                                                                    var secondValue = $("#target-13").val();
                                                                                                    var thirdValue = $("#target-14").val();

                                                                                                    $.ajax({
                                                                                                        url:"/ajaxPAndR/reCalculateQuarterValues",
                                                                                                        method:"POST",
                                                                                                        data:{firstValue,secondValue, thirdValue},
                                                                                                        success: function(output){
                                                                                                            var target = output;
                                                                                                        },
                                                                                                        error: function(xhr, ajaxOptions,thrownError){
                                                                                                            alert(xhr.status+" "+thrownError);
                                                                                                        }
                                                                                                    });

                                                                                                    var firstValue = $("#bookingE-12").val();
                                                                                                    var secondValue = $("#bookingE-13").val();
                                                                                                    var thirdValue = $("#bookingE-14").val();

                                                                                                    $.ajax({
                                                                                                        url:"/ajaxPAndR/reCalculateQuarterValues",
                                                                                                        method:"POST",
                                                                                                        data:{firstValue,secondValue, thirdValue},
                                                                                                        success: function(output){
                                                                                                            var booking = output;
                                                                                                        },
                                                                                                        error: function(xhr, ajaxOptions,thrownError){
                                                                                                            alert(xhr.status+" "+thrownError);
                                                                                                        }
                                                                                                    });

                                                                                                    var aux = (month-booking);
                                                                                                    transform = "Comma";

                                                                                                    $.ajax({
                                                                                                        url:"/ajaxPAndR/transformVal",
                                                                                                        method:"POST",
                                                                                                        data:{aux,transform},
                                                                                                        success: function(output){
                                                                                                            $("#pending-15").val(output);
                                                                                                        },
                                                                                                        error: function(xhr, ajaxOptions,thrownError){
                                                                                                            alert(xhr.status+" "+thrownError);
                                                                                                        }
                                                                                                    });

                                                                                                    var RFvsTargetQ = (month-target);              

                                                                                                    $.ajax({
                                                                                                        url:"/ajaxPAndR/transformVal",
                                                                                                        method:"POST",
                                                                                                        data:{RFvsTargetQ,transform},
                                                                                                        success: function(output){
                                                                                                            $("#RFvsTarget-15").val(output);
                                                                                                        },
                                                                                                        error: function(xhr, ajaxOptions,thrownError){
                                                                                                            alert(xhr.status+" "+thrownError);
                                                                                                        }
                                                                                                    });

                                                                                                    if (target == 0) {
                                                                                                        var Temp3 = 0+"%";
                                                                                                    }else{
                                                                                                        var Temp3 = ((month/target)*100).toFixed(2);

                                                                                                        $.ajax({
                                                                                                            url:"/ajaxPAndR/transformVal",
                                                                                                            method:"POST",
                                                                                                            data:{Temp3,transform},
                                                                                                            success: function(output){
                                                                                                                Temp3 = output;
                                                                                                            },
                                                                                                            error: function(xhr, ajaxOptions,thrownError){
                                                                                                                alert(xhr.status+" "+thrownError);
                                                                                                            }
                                                                                                        });

                                                                                                        Temp3 = Temp3 + "%";
                                                                                                    }

                                                                                                    $("#achievement-15").val(Temp3);
                                                                                                }
                                                                                            },
                                                                                            error: function(xhr, ajaxOptions,thrownError){
                                                                                                alert(xhr.status+" "+thrownError);
                                                                                            }
                                                                                        });
                                                                                    },
                                                                                    error: function(xhr, ajaxOptions,thrownError){
                                                                                        alert(xhr.status+" "+thrownError);
                                                                                    }
                                                                                });
                                                                            },
                                                                            error: function(xhr, ajaxOptions,thrownError){
                                                                                alert(xhr.status+" "+thrownError);
                                                                            }
                                                                        });

                                                                        var Q1 = $("#rf-3").val();
                                                                        var Q2 = $("#rf-7").val();
                                                                        var Q3 = $("#rf-11").val();
                                                                        var Q4 = $("#rf-15").val();

                                                                        $.ajax({
                                                                            url:"/ajaxPAndR/reCalculateTotalVal",
                                                                            method:"POST",
                                                                            data:{Q1,Q2,Q3,Q4},
                                                                            success: function(output){
                                                                                var RF = output;
                                                                            },
                                                                            error: function(xhr, ajaxOptions,thrownError){
                                                                                alert(xhr.status+" "+thrownError);
                                                                            }
                                                                        });

                                                                        var Q1 = $("#target-3").val();
                                                                        var Q2 = $("#target-7").val();
                                                                        var Q3 = $("#target-11").val();
                                                                        var Q4 = $("#target-15").val();

                                                                        $.ajax({
                                                                            url:"/ajaxPAndR/reCalculateTotalVal",
                                                                            method:"POST",
                                                                            data:{Q1,Q2,Q3,Q4},
                                                                            success: function(output){
                                                                                var target = output;
                                                                            },
                                                                            error: function(xhr, ajaxOptions,thrownError){
                                                                                alert(xhr.status+" "+thrownError);
                                                                            }
                                                                        });

                                                                        var Q1 = $("#bookingE-3").val();
                                                                        var Q2 = $("#bookingE-7").val();
                                                                        var Q3 = $("#bookingE-11").val();
                                                                        var Q4 = $("#bookingE-15").val();

                                                                        $.ajax({
                                                                            url:"/ajaxPAndR/reCalculateTotalVal",
                                                                            method:"POST",
                                                                            data:{Q1,Q2,Q3,Q4},
                                                                            success: function(output){
                                                                                var booking = output;
                                                                            },
                                                                            error: function(xhr, ajaxOptions,thrownError){
                                                                                alert(xhr.status+" "+thrownError);
                                                                            }
                                                                        });

                                                                        var totalPending = (RF-booking);
                                                                        transform = "Comma";

                                                                        $.ajax({
                                                                            url:"/ajaxPAndR/transformVal",
                                                                            method:"POST",
                                                                            data:{totalPending,transform},
                                                                            success: function(output){
                                                                                $("#totalPending").val(output);
                                                                            },
                                                                            error: function(xhr, ajaxOptions,thrownError){
                                                                                alert(xhr.status+" "+thrownError);
                                                                            }
                                                                        });

                                                                        var TotalRFvsTarget = (RF-target);

                                                                        $.ajax({
                                                                            url:"/ajaxPAndR/transformVal",
                                                                            method:"POST",
                                                                            data:{TotalRFvsTarget,transform},
                                                                            success: function(output){
                                                                                $("#TotalRFvsTarget").val(output);
                                                                            },
                                                                            error: function(xhr, ajaxOptions,thrownError){
                                                                                alert(xhr.status+" "+thrownError);
                                                                            }
                                                                        });

                                                                        if (target == 0) {
                                                                            var Temp3 = 0+"%";
                                                                        }else{

                                                                            var Temp3 = ((RF/target)*100).toFixed(2);

                                                                            $.ajax({
                                                                                url:"/ajaxPAndR/transformVal",
                                                                                method:"POST",
                                                                                data:{Temp3,transform},
                                                                                success: function(output){
                                                                                    $("#TotalRFvsTarget").val(output);
                                                                                },
                                                                                error: function(xhr, ajaxOptions,thrownError){
                                                                                    alert(xhr.status+" "+thrownError);
                                                                                }
                                                                            });

                                                                            Temp3 = Temp3 + "%";
                                                                            $("#totalAchievement").val(Temp3);
                                                                        }
                                                                    },
                                                                    error: function(xhr, ajaxOptions,thrownError){
                                                                        alert(xhr.status+" "+thrownError);
                                                                    }
                                                                });

                                                            },
                                                            error: function(xhr, ajaxOptions,thrownError){
                                                                alert(xhr.status+" "+thrownError);
                                                            }
                                                        });

                                                    },
                                                    error: function(xhr, ajaxOptions,thrownError){
                                                        alert(xhr.status+" "+thrownError);
                                                    }
                                                });
                                            },
                                            error: function(xhr, ajaxOptions,thrownError){
                                                alert(xhr.status+" "+thrownError);
                                            }
                                        });
                                    },
                                    error: function(xhr, ajaxOptions,thrownError){
                                        alert(xhr.status+" "+thrownError);
                                    }
                                });
                            },
                            error: function(xhr, ajaxOptions,thrownError){
                                alert(xhr.status+" "+thrownError);
                            }
                        });
            
    */

}