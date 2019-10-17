@extends('layouts.mirror')
@section('title', 'AE Report')
@section('head')    
    <?php include(resource_path('views/auth.php')); 
    $month = array('Jan','Feb','Mar','Q1','Apr','May','Jun','Q2','Jul','Aug','Sep','Q3','Oct','Nov','Dec','Q4');?>
    <script src="/js/pandr.js"></script>
    <style type="text/css">
        ::-webkit-scrollbar{
            height: 15px;
        }
        ::-webkit-scrollbar-track {
            background: #d9d9d9; 
        }
        ::-webkit-scrollbar-thumb {
            background: #666666;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #4d4d4d; 
        }
    </style>
@endsection
@section('content')
    <div class="container-fluid">
        <div class="row justify-content-end mt-2">
            <div class="col-3" style="color: #0070c0;font-size: 25px;">
                Account Executive Report
            </div>
        </div>
    </div>

    <form method="POST" action="{{ route('AEPost') }}" runat="server"  onsubmit="ShowLoading()" onkeydown="return event.key != 'Enter';">
        @csrf
        <div class="container-fluid">       
            <div class="row">
                <div class="col">
                    <label class='labelLeft'><span class="bold">Region:</span></label>
                    @if($errors->has('region'))
                        <label style="color: red;">* Required</label>
                    @endif
                    @if($userLevel == 'L0' || $userLevel == 'SU')
                        {{$render->region($region)}}
                    @else
                        {{$render->regionFiltered($region, $regionID, $special )}}
                    @endif
                </div>
                <div class="col">
                    <label class='labelLeft'><span class="bold">Year:</span></label>
                    @if($errors->has('year'))
                        <label style="color: red;">* Required</label>
                    @endif
                    {{$render->year()}}
                </div>
                <div class="col">
                    <label class='labelLeft'><span class="bold">Sales Rep:</span></label>
                    @if($errors->has('salesRep'))
                        <label style="color: red;">* Required</label>
                    @endif
                    {{$render->salesRep2()}}
                </div>
                <div class="col">
                    <label class='labelLeft'><span class="bold">Currency:</span></label>
                    @if($errors->has('currency'))
                        <label style="color: red;">* Required</label>
                    @endif
                    {{$render->currency($currency)}}
                </div>  
                <div class="col">
                    <label class="labelLeft"><span class="bold"> Value: </span></label>
                        @if($errors->has('value'))
                            <label style="color: red;">* Required</label>
                        @endif
                        {{$render->value2()}}                   
                </div>
                <div class="col">
                    <label class='labelLeft'> &nbsp; </label>
                    <input style="width: 100%;" type="submit" value="Generate" class="btn btn-primary">     
                </div>          
            </div>
        </div>
    </form>
    <br>
    <div class="container-fluid">
        <form method="POST" action="{{ route('AESave') }}" runat="server"  onsubmit="ShowLoading()">
        @csrf
            <div class="row justify-content-end">
                <!--<div class="col-3">
                    <label> &nbsp;</label>
                    <input type="button" class="btn btn-primary" value="{{$sourceSave}}" style="width: 100%;">
                    <input type="text" name="sourceSave" value="{{$sourceSave}}" style="display: none;">
                </div>-->               
                <div class="col-4" >
                    <div class="container-fluid">
                        <div class="row justify-content-end">
                            <div class="col">
                                <label> &nbsp;</label>
                                <div class="btn-group btn-group-toggle" data-toggle="buttons" style="width: 100%;">
                                    <label class="btn alert-primary active">
                                        <input type="radio" name="options" value='save' id="option1" autocomplete="off" checked> Save
                                    </label>
                                                            
                                    <label class="btn alert-success">
                                        <input type="radio" name="options" value='submit' id="option2" autocomplete="off"> Submit
                                    </label>
                                </div>
                            </div>
                            <div class="col">
                                <label> &nbsp; </label>
                                <input type="submit" id="button" value="Save" class="btn btn-primary" style="width: 100%">      
                                </div>
                            </div>
                        </div>
                    </div>
                </div>  
            </div>

            <div class="row mt-2 justify-content-end">
                <div class="col" style="width: 100%;">
                    <center>
                        {{$render->AE1($forRender,$client,$tfArray,$odd,$even,$userName,$error)}}
                    </center>
                </div>
            </div>

        </form>
    </div>

    <div id="vlau"></div>

    <script>

        var client = <?php echo json_encode($client); ?>;
        console.log(client);
        console.log(client.length);

        $('.linked').scroll(function(){
            $('.linked').scrollLeft($(this).scrollLeft());
        });
        $(window).keydown(function(event){
            if(event.keyCode == 13) {
                event.preventDefault();
                return false;
            }
        });
        $("input[type=radio][name=options]").change(function(){
            if (this.value == 'save') {
                $("#button").val("Save");
            }else{
                $("#button").val("Submit");
            }
        });
        for(var c=0;c<client.length;c++){
            $("#month-"+c+"-0").css("height",$("#client-"+c).css("height"));
        }
    </script>

    <script type="text/javascript">
        $(document).ready(function(){
            @for( $m=0;$m<16;$m++)
                @for($c=0;$c< sizeof($client);$c++)
                    $("#clientRF-"+{{$c}}+"-"+{{$m}}).change(function(){
                        if ($(this).val() == '') {
                            $(this).val(0);
                        }
                        alert(m);
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
                        if (Temp3.toFixed(0) != handleNumber($("#passTotal-"+{{$c}}).val()).toFixed(0) /*|| ((tmp2 != '100.00') && (tmp2 != '0.00') )*/ ) {
                            $("#client-"+{{$c}}).css("background-color","red");
                        }else{
                            $("#client-"+{{$c}}).css("background-color","");
                        }
                        var rf = 0;
                        for(var c2=0;c2<client.length;c2++){
                            if ($("#splitted-"+c2).val() != false) {
                                var mult = 0.5;
                            }else{
                                var mult = 1;
                            }
                            rf += (handleNumber($("#clientRF-"+c2+"-"+{{$m}}).val())*mult);
                        }
                        rf = Comma(rf);
                        $("#rf-"+{{$m}}).val(rf);
                        if ({{$m}} == 0 || {{$m}} == 1 || {{$m}} == 2 ) {
                            var month =0;
                            for(var c2=0;c2<client.length;c2++){
                                if ($("#splitted-"+c2).val() != false) {
                                    var mult = 0.5;
                                }else{
                                    var mult = 1;
                                }
                                month += (handleNumber($("#clientRF-"+c2+"-3").val())*mult);
                            }
                            month = Comma(month);
                            $("#rf-3").val(month);
                        }else if ({{$m}} == 4 || {{$m}} == 5 || {{$m}} == 6 ) {
                            var month =0;
                            for(var c2=0;c2<client.length;c2++){
                                if ($("#splitted-"+c2).val() != false) {
                                    var mult = 0.5;
                                }else{
                                    var mult = 1;
                                }
                                month += (handleNumber($("#clientRF-"+c2+"-7").val())*mult);
                            }
                            month = Comma(month);
                            $("#rf-7").val(month);
                        }else if ({{$m}} == 8 || {{$m}} == 9 || {{$m}} == 10 ) {
                            var month =0;
                            for(var c2=0;c2<client.length;c2++){
                                if ($("#splitted-"+c2).val() != false) {
                                    var mult = 0.5;
                                }else{
                                    var mult = 1;
                                }
                                month += (handleNumber($("#clientRF-"+c2+"-11").val())*mult);
                            }
                            month = Comma(month);
                            $("#rf-11").val(month);
                        }else if ({{$m}} == 12 || {{$m}} == 13 || {{$m}} == 14 ) {
                            var month =0;
                            for(var c2=0;c2<client.length;c2++){
                                if ($("#splitted-"+c2).val() != false) {
                                    var mult = 0.5;
                                }else{
                                    var mult = 1;
                                }
                                month += (handleNumber($("#clientRF-"+c2+"-15").val())*mult);
                            }
                            month = Comma(month);
                            $("#rf-15").val(month);
                        }
                        var total = Comma(handleNumber($("#rf-3").val()) + handleNumber($("#rf-7").val()) + handleNumber($("#rf-11").val()) + handleNumber($("#rf-15").val()));
                        $("#total-total").val(total);
                        for(var c2=0;c2<client.length;c2++){
                            var temp = handleNumber($("#totalClient-"+c2).val())/handleNumber($("#total-total").val());
                            temp = Comma(temp*100);
                            $("#totalPP2-"+c2).val(temp);
                            $("#totalPP3-"+c2).val(temp);
                        }
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
                            for(var c2=0;c2<client.length;c2++){
                                month += handleNumber($("#clientRF-"+c2+"-3").val());
                            }
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
                            for(var c2=0;c2<client.length;c2++){
                                month += handleNumber($("#clientRF-"+c2+"-7").val());
                            }
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
                            for(var c2=0;c2<client.length;c2++){
                                month += handleNumber($("#clientRF-"+c2+"-11").val());
                            }
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
                            for(var c2=0;c2<client.length;c2++){
                                month += handleNumber($("#clientRF-"+c2+"-15").val());
                            }
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
                    });
                @endfor
            @endfor
        });
    </script>

@endsection

