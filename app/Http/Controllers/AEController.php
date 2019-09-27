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
        $sourceSave = $forRender['sourceSave'];
        $client = $tmp['client'];
        $tfArray = array();
        $odd = array();
        $even = array();

        $error = false;

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

/*
if ($(this).val() == '') {
                            $(this).val(0);
                        }
                        $(this).val(Comma(handleNumber($(this).val())));
                        if ({{$m}} == 0 || {{$m}} == 1 || {{$m}} == 2 ) {
                            var value = Comma(handleNumber($("#clientRF-"+{{$c}}+"-0").val())+handleNumber($("#clientRF-"+{{$c}}+"-1").val())+handleNumber($("#clientRF-"+{{$c}}+"-2").val()));
                            $("#clientRF-"+{{$c}}+"-3").val(value);
                        }else if ({{$m}} == 4 || {{$m}} == 5 || {{$m}} == 6 ) {
                            var value = Comma(handleNumber($("#clientRF-"+{{$c}}+"-4").val())+handleNumber($("#clientRF-"+{{$c}}+"-5").val())+handleNumber($("#clientRF-"+{{$c}}+"-6").val()));
                            $("#clientRF-"+{{$c}}+"-7").val(value);
                        }else if ({{$m}} == 8 || {{$m}} == 9 || {{$m}} == 10 ) {
                            var value = Comma(handleNumber($("#clientRF-"+{{$c}}+"-8").val())+handleNumber($("#clientRF-"+{{$c}}+"-9").val())+handleNumber($("#clientRF-"+{{$c}}+"-10").val()));
                            $("#clientRF-"+{{$c}}+"-11").val(value);
                        }else if ({{$m}} == 12 || {{$m}} == 13 || {{$m}} == 14 ) {
                            var value = Comma(handleNumber($("#clientRF-"+{{$c}}+"-12").val())+handleNumber($("#clientRF-"+{{$c}}+"-13").val())+handleNumber($("#clientRF-"+{{$c}}+"-14").val()));
                            $("#clientRF-"+{{$c}}+"-15").val(value);
                        }
                        var totalClient = Comma(handleNumber($("#clientRF-"+{{$c}}+"-3").val()) + handleNumber($("#clientRF-"+{{$c}}+"-7").val()) + handleNumber($("#clientRF-"+{{$c}}+"-11").val()) + handleNumber($("#clientRF-"+{{$c}}+"-15").val()));
                        $("#totalClient-"+{{$c}}).val(totalClient);
                        Temp3 = handleNumber(totalClient);
                        if (Temp3.toFixed(0) != handleNumber($("#passTotal-"+{{$c}}).val()).toFixed(0) /*|| ((tmp2 != '100.00') && (tmp2 != '0.00') ) ) {
                            $("#client-"+{{$c}}).css("background-color","red");
                        }else{
                            $("#client-"+{{$c}}).css("background-color","");
                        }
                        var rf = 0;
                        @for($c2=0;$c2<sizeof($client);$c2++)
                            if ($("#splitted-"+{{$c2}}).val() != false) {
                                var mult = 0.5;
                            }else{
                                var mult = 1;
                            }
                            rf += (handleNumber($("#clientRF-"+{{$c2}}+"-"+{{$m}}).val())*mult);
                        @endfor
                        rf = Comma(rf);
                        $("#rf-"+{{$m}}).val(rf);
                        if ({{$m}} == 0 || {{$m}} == 1 || {{$m}} == 2 ) {
                            var month =0;
                            @for($c2=0;$c2<sizeof($client);$c2++)
                                if ($("#splitted-"+{{$c2}}).val() != false) {
                                    var mult = 0.5;
                                }else{
                                    var mult = 1;
                                }
                                month += (handleNumber($("#clientRF-"+{{$c2}}+"-3").val())*mult);
                            @endfor
                            month = Comma(month);
                            $("#rf-3").val(month);
                        }else if ({{$m}} == 4 || {{$m}} == 5 || {{$m}} == 6 ) {
                            var month =0;
                            @for($c2=0;$c2<sizeof($client);$c2++)
                                if ($("#splitted-"+{{$c2}}).val() != false) {
                                    var mult = 0.5;
                                }else{
                                    var mult = 1;
                                }
                                month += (handleNumber($("#clientRF-"+{{$c2}}+"-7").val())*mult);
                            @endfor
                            month = Comma(month);
                            $("#rf-7").val(month);
                        }else if ({{$m}} == 8 || {{$m}} == 9 || {{$m}} == 10 ) {
                            var month =0;
                            @for($c2=0;$c2<sizeof($client);$c2++)
                                if ($("#splitted-"+{{$c2}}).val() != false) {
                                    var mult = 0.5;
                                }else{
                                    var mult = 1;
                                }
                                month += (handleNumber($("#clientRF-"+{{$c2}}+"-11").val())*mult);
                            @endfor
                            month = Comma(month);
                            $("#rf-11").val(month);
                        }else if ({{$m}} == 12 || {{$m}} == 13 || {{$m}} == 14 ) {
                            var month =0;
                            @for($c2=0;$c2<sizeof($client);$c2++)
                                if ($("#splitted-"+{{$c2}}).val() != false) {
                                    var mult = 0.5;
                                }else{
                                    var mult = 1;
                                }
                                month += (handleNumber($("#clientRF-"+{{$c2}}+"-15").val())*mult);
                            @endfor
                            month = Comma(month);
                            $("#rf-15").val(month);
                        }
                        var total = Comma(handleNumber($("#rf-3").val()) + handleNumber($("#rf-7").val()) + handleNumber($("#rf-11").val()) + handleNumber($("#rf-15").val()));
                        $("#total-total").val(total);
                        @for($c2=0;$c2<sizeof($client);$c2++)
                            var temp = handleNumber($("#totalClient-"+{{$c2}}).val())/handleNumber($("#total-total").val());
                            temp = Comma(temp*100);
                            $("#totalPP2-"+{{$c2}}).val(temp);
                            $("#totalPP3-"+{{$c2}}).val(temp);
                        @endfor
                        var value = handleNumber($(this).val());
                        var PY = handleNumber($("#PY-"+{{$c}}+"-"+{{$m}}).val());
                        var tmp = value - PY;
                        tmp = Comma(tmp);
                        $("#RFvsPY-"+{{$c}}+"-"+{{$m}}).val(tmp);
                        if ({{$m}} == 0 || {{$m}} == 1 || {{$m}} == 2 ) {
                            var value = Comma(handleNumber($("#RFvsPY-"+{{$c}}+"-0").val())+handleNumber($("#RFvsPY-"+{{$c}}+"-1").val())+handleNumber($("#RFvsPY-"+{{$c}}+"-2").val()));
                            $("#RFvsPY-"+{{$c}}+"-3").val(value);
                        }else if ({{$m}} == 4 || {{$m}} == 5 || {{$m}} == 6 ) {
                            var value = Comma(handleNumber($("#RFvsPY-"+{{$c}}+"-4").val())+handleNumber($("#RFvsPY-"+{{$c}}+"-5").val())+handleNumber($("#RFvsPY-"+{{$c}}+"-6").val()));
                            $("#RFvsPY-"+{{$c}}+"-7").val(value);
                        }else if ({{$m}} == 8 || {{$m}} == 9 || {{$m}} == 10 ) {
                            var value = Comma(handleNumber($("#RFvsPY-"+{{$c}}+"-8").val())+handleNumber($("#RFvsPY-"+{{$c}}+"-9").val())+handleNumber($("#RFvsPY-"+{{$c}}+"-10").val()));
                            $("#RFvsPY-"+{{$c}}+"-11").val(value);
                        }else if ({{$m}} == 12 || {{$m}} == 13 || {{$m}} == 14 ) {
                            var value = Comma(handleNumber($("#RFvsPY-"+{{$c}}+"-12").val())+handleNumber($("#RFvsPY-"+{{$c}}+"-13").val())+handleNumber($("#RFvsPY-"+{{$c}}+"-14").val()));
                            $("#RFvsPY-"+{{$c}}+"-15").val(value);
                        }
                        var Temp = Comma(handleNumber($("#RFvsPY-"+{{$c}}+"-3").val()) + handleNumber($("#RFvsPY-"+{{$c}}+"-7").val()) + handleNumber($("#RFvsPY-"+{{$c}}+"-11").val()) + handleNumber($("#RFvsPY-"+{{$c}}+"-15").val()));
                        $("#totalRFvsPY-"+{{$c}}).val(Temp);
                        var booking = handleNumber($("#bookingE-"+{{$m}}).val());
                        var Temp2 = handleNumber($("#target-"+{{$m}}).val());
                        rf = handleNumber(rf);
                        var RFvsTarget = Comma(rf-Temp2);
                        var pending = Comma(rf-booking);
                        $("#pending-"+{{$m}}).val(pending);
                        $("#RFvsTarget-"+{{$m}}).val(RFvsTarget);
                        if (Temp2 == 0) {
                            var Temp3 = 0+"%";
                        }else{
                            var Temp3 = Comma(((rf/Temp2)*100).toFixed(2))+"%";
                        }
                        $("#achievement-"+{{$m}}).val(Temp3);
                        if ({{$m}} == 0 || {{$m}} == 1 || {{$m}} == 2 ) {
                            var month =0;
                            @for($c2=0;$c2<sizeof($client);$c2++)
                                month += handleNumber($("#clientRF-"+{{$c2}}+"-3").val());
                            @endfor
                            var target = handleNumber($("#target-0").val())+handleNumber($("#target-1").val())+handleNumber($("#target-2").val());
                            var booking = handleNumber($("#bookingE-0").val())+handleNumber($("#bookingE-1").val())+handleNumber($("#bookingE-2").val());
                            $("#pending-3").val(Comma(month-booking));
                            var RFvsTargetQ = Comma(month-target);
                            $("#RFvsTarget-3").val(RFvsTargetQ);
                            if (target == 0) {
                                var Temp3 = 0+"%";
                            }else{
                                var Temp3 = Comma(((month/target)*100).toFixed(2))+"%";
                            }
                            $("#achievement-3").val(Temp3);
                        }else if ({{$m}} == 4 || {{$m}} == 5 || {{$m}} == 6 ) {
                            var month =0;
                            @for($c2=0;$c2<sizeof($client);$c2++)
                                month += handleNumber($("#clientRF-"+{{$c2}}+"-7").val());
                            @endfor
                            var target = handleNumber($("#target-4").val())+handleNumber($("#target-5").val())+handleNumber($("#target-6").val());
                            var booking = handleNumber($("#bookingE-4").val())+handleNumber($("#bookingE-5").val())+handleNumber($("#bookingE-6").val());
                            $("#pending-7").val(Comma(month-booking));
                            var RFvsTargetQ = Comma(month-target);
                            $("#RFvsTarget-7").val(RFvsTargetQ);
                            if (target == 0) {
                                var Temp3 = 0+"%";
                            }else{
                                var Temp3 = Comma(((month/target)*100).toFixed(2))+"%";
                            }
                            $("#achievement-7").val(Temp3);
                        }else if ({{$m}} == 8 || {{$m}} == 9 || {{$m}} == 10 ) {
                            var month =0;
                            @for($c2=0;$c2<sizeof($client);$c2++)
                                month += handleNumber($("#clientRF-"+{{$c2}}+"-11").val());
                            @endfor
                            var target = handleNumber($("#target-8").val())+handleNumber($("#target-9").val())+handleNumber($("#target-10").val());
                            var booking = handleNumber($("#bookingE-8").val())+handleNumber($("#bookingE-9").val())+handleNumber($("#bookingE-10").val());
                            $("#pending-11").val(Comma(month-booking));
                            var RFvsTargetQ = Comma(month-target);
                            $("#RFvsTarget-11").val(RFvsTargetQ);
                            if (target == 0) {
                                var Temp3 = 0+"%";
                            }else{
                                var Temp3 = Comma(((month/target)*100).toFixed(2))+"%";
                            }
                            $("#achievement-11").val(Temp3);
                        }else if ({{$m}} == 12 || {{$m}} == 13 || {{$m}} == 14 ) {
                            var month =0;
                            @for($c2=0;$c2<sizeof($client);$c2++)
                                month += handleNumber($("#clientRF-"+{{$c2}}+"-15").val());
                            @endfor
                            var target = handleNumber($("#target-12").val())+handleNumber($("#target-13").val())+handleNumber($("#target-14").val());
                            var booking = handleNumber($("#bookingE-12").val())+handleNumber($("#bookingE-13").val())+handleNumber($("#bookingE-14").val());
                            $("#pending-15").val(Comma(month-booking));
                            var RFvsTargetQ = Comma(month-target);
                            $("#RFvsTarget-15").val(RFvsTargetQ);
                            if (target == 0) {
                                var Temp3 = 0+"%";
                            }else{
                                var Temp3 = Comma(((month/target)*100).toFixed(2))+"%";
                            }
                            $("#achievement-15").val(Temp3);
                        }
                        var RF = handleNumber($("#rf-3").val()) + handleNumber($("#rf-7").val()) + handleNumber($("#rf-11").val()) + handleNumber($("#rf-15").val());
                        var target = handleNumber($("#target-3").val()) + handleNumber($("#target-7").val()) + handleNumber($("#target-11").val()) + handleNumber($("#target-15").val());
                        var booking = handleNumber($("#bookingE-3").val()) + handleNumber($("#bookingE-7").val()) + handleNumber($("#bookingE-11").val()) + handleNumber($("#bookingE-15").val());
                        $("#totalPending").val(Comma(RF-booking));
                        $("#TotalRFvsTarget").val(Comma(RF-target));
                        if (target == 0) {
                            var Temp3 = 0+"%";
                        }else{
                            var Temp3 = Comma(((RF/target)*100).toFixed(2))+"%";
                        }
                        $("#totalAchievement").val(Temp3);
*/