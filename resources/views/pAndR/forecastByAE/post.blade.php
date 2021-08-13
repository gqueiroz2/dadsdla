@extends('layouts.mirror')
@section('title', 'AE Report')
@section('head')	
    <?php include(resource_path('views/auth.php')); ?>
    <script src="/js/pandr.js"></script>
    <style type="text/css">
        table { 
            border-collapse: collapse; 
        }
    </style>
@endsection
@section('content')
	

	<form method="POST" action="{{ route('forecastByAEPost') }}" runat="server"  onsubmit="ShowLoading()">
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
			<br>
			<div class="row">
				<center style="width: 100%;">
					<div class="col-3">
						@if($typeMsg == "Success")
							<div class="alert alert-info">
								{{$msg}}
							</div>
						@elseif($typeMsg == "Error")
							<div class="alert alert-danger">
								{{$msg}}
							</div>
						@endif
					</div>
				</center>
			</div>
		</div>
	</form>
	<div class="container-fluid">
		<div class="row justify-content-end mt-2">
			<div class="col-2" style="color: #0070c0;font-size: 25px;">
				Forecast by AE
			</div>
    		<div class="col-2">
                <button type="button" id="excel" class="btn btn-primary" style="width: 100%">
                    Generate Excel
                </button>               
            </div>   
        </div>        
	</div>

	<br>
    <div class="container-fluid" id="body">
        <form method="POST" action="{{ route('forecastByAESave') }}" runat="server"  onsubmit="ShowLoading()">
        @csrf
            <div class="row justify-content-end">             
                <div class="col" >
                    <div class="container-fluid">
                        <div class="row justify-content-end">
                            <div class="col-2">
                                <div class="btn-group btn-group-toggle" data-toggle="buttons" style="width: 100%;">
                                    <label class="btn alert-primary active">
                                        <input type="radio" name="options" value='save' id="option1" autocomplete="off" checked> Save
                                    </label>
                                                            
                                    <label class="btn alert-success">
                                        <input type="radio" name="options" value='submit' id="option2" autocomplete="off"> Submit
                                    </label>
                                </div>     
                            </div>
                            <div class="col-2">
                                <input type="submit" id="button" value="Save" class="btn btn-primary" style="width: 100%">      
                            </div>
                        </div>
                    </div>
                </div>  
            </div>
            <div class="row mt-2 justify-content-end">
                <div class="col" style="width: 100%;">
                    <center>
                        {{$render->loadForecast($forRender)}}
                    </center>
                </div>
            </div>

        </form>
    </div>

	<div id="vlau">
		
	</div>

    <script type="text/javascript">
        $(document).ready(function(){

            ajaxSetup();

            $('#excel').click(function(event){
                var yearExcel = "<?php echo $yearExcel; ?>";
                var regionExcel = "<?php echo $regionExcel; ?>";
                var valueExcel = "<?php echo $valueExcel; ?>";
                var currencyExcel = "<?php echo $currencyExcel; ?>";
                var salesRepExcel = "<?php echo $salesRepExcel; ?>";
                var userRegionExcel = "<?php echo $userRegionExcel; ?>";

                var div = document.createElement('div');
                var img = document.createElement('img');
                img.src = '/loading_excel.gif';
                div.innerHTML ="Generating File...</br>";
                div.style.cssText = 'position: absolute; left: 0px; top:0px;  margin:0px;        width: 100%;        height: 100%;        display:block;        z-index: 99999;        opacity: 0.9;        -moz-opacity: 0;        filter: alpha(opacity = 45);        background: white;    background-repeat: no-repeat;        background-position:50% 50%;        text-align: center;        overflow: hidden;   font-size:30px;     font-weight: bold;        color: black;        padding-top: 20%';
                div.appendChild(img);
                document.body.appendChild(div);

                var typeExport = $("#excel").val();

                var title = "<?php echo $titleExcel; ?>";
                var auxTitle = "<?php echo $titleExcel; ?>";
                    
                $.ajax({
                    xhrFields: {
                        responseType: 'blob',
                    },
                    url: "/generate/excel/pandr/forecastAE",
                    type: "POST",
                    data: {title, typeExport, yearExcel,regionExcel,valueExcel,currencyExcel,salesRepExcel,auxTitle, userRegionExcel},
                    /*success: function(output){
                        $("#vlau").html(output);
                    },*/
                    success: function(result,status,xhr){
                        var disposition = xhr.getResponseHeader('content-disposition');
                        var matches = /"([^"]*)"/.exec(disposition);
                        var filename = (matches != null && matches[1] ? matches[1] : title);

                        //download
                        var blob = new Blob([result], {
                            type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
                        });
                        var link = document.createElement('a');
                        link.href = window.URL.createObjectURL(blob);
                        link.download = filename;

                        document.body.appendChild(link);

                        link.click();
                        document.body.removeChild(link);
                        document.body.removeChild(div);
                    },
                    error: function(xhr, ajaxOptions, thrownError){
                        document.body.removeChild(div);
                        alert(xhr.status+" "+thrownError);
                    }
                });                    
            });
        });
    </script>
    
    <script type="text/javascript">
        $(document).ready(function(){
            var client = <?php echo json_encode($forRender['client']); ?>;

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

            
            @for( $m=0;$m<16;$m++)
                @for($c=0;$c< sizeof($forRender['client']);$c++)
                    $("#clientRF-DISC-"+{{$c}}+"-"+{{$m}}).change(function(){
                        
                        if ($(this).val() == '') {
                            $(this).val(0);
                        }
                        $(this).val(Comma(handleNumber($(this).val())));
                        if ({{$m}} == 0 || {{$m}} == 1 || {{$m}} == 2 ) {
                            var value = Comma(
                                            handleNumber($("#clientRF-DISC-"+{{$c}}+"-0").val())
                                            +
                                            handleNumber($("#clientRF-DISC-"+{{$c}}+"-1").val())
                                            + 
                                            handleNumber($("#clientRF-DISC-"+{{$c}}+"-2").val())
                                            );
                            $("#clientRF-DISC-"+{{$c}}+"-3").val(value);
                        }else if ({{$m}} == 4 || {{$m}} == 5 || {{$m}} == 6 ) {
                            var value = Comma(
                                            handleNumber($("#clientRF-DISC-"+{{$c}}+"-4").val())
                                            +
                                            handleNumber($("#clientRF-DISC-"+{{$c}}+"-5").val())
                                            +
                                            handleNumber($("#clientRF-DISC-"+{{$c}}+"-6").val())
                                            );
                            $("#clientRF-DISC-"+{{$c}}+"-7").val(value);
                        }else if ({{$m}} == 8 || {{$m}} == 9 || {{$m}} == 10 ) {
                            var value = Comma(
                                            handleNumber($("#clientRF-DISC-"+{{$c}}+"-8").val())+
                                            handleNumber($("#clientRF-DISC-"+{{$c}}+"-9").val())+
                                            handleNumber($("#clientRF-DISC-"+{{$c}}+"-10").val())
                                            );
                            $("#clientRF-DISC-"+{{$c}}+"-11").val(value);
                        }else if ({{$m}} == 12 || {{$m}} == 13 || {{$m}} == 14 ) {
                            var value = Comma(
                                            handleNumber($("#clientRF-DISC-"+{{$c}}+"-12").val())
                                            +
                                            handleNumber($("#clientRF-DISC-"+{{$c}}+"-13").val())
                                            +
                                            handleNumber($("#clientRF-DISC-"+{{$c}}+"-14").val())
                                            );
                            $("#clientRF-DISC-"+{{$c}}+"-15").val(value);
                        }          

                        var totalClient = Comma(
                    						handleNumber($("#clientRF-DISC-"+{{$c}}+"-3").val()) 
                                            + 
                    						handleNumber($("#clientRF-DISC-"+{{$c}}+"-7").val()) 
                                            + 
                    						handleNumber($("#clientRF-DISC-"+{{$c}}+"-11").val()) 
                                            + 
                    						handleNumber($("#clientRF-DISC-"+{{$c}}+"-15").val())
                    						);
                        console.log(totalClient);
                        console.log(handleNumber(totalClient));
                        console.log(Comma(handleNumber(totalClient)));
                        $("#totalClient-DISC-"+{{$c}}).val(totalClient);
                        Temp3 = handleNumber(totalClient);   

                        var rf = 0;
                        for(var c2=0;c2<client.length;c2++){
                            if ($("#splitted-"+c2).val() != false) {
                                var mult = 0.5;
                            }else{
                                var mult = 1;
                            }
                            rf += (handleNumber($("#clientRF-DISC-"+c2+"-"+{{$m}}).val())*mult);
                        }    

                        rf += handleNumber($("#bookingE-"+{{$m}}).val());
                        rf = Comma(rf);
                        $("#rf-"+{{$m}}).val(rf);
                        if ({{$m}} == 0 || {{$m}} == 1 || {{$m}} == 2 ) {
                            var month =0;
                            month = handleNumber($("#rf-0").val()) + handleNumber($("#rf-1").val()) + handleNumber($("#rf-2").val());
                            month = Comma(month);
                            $("#rf-3").val(month);
                        }else if ({{$m}} == 4 || {{$m}} == 5 || {{$m}} == 6 ) {
                            var month =0;
                            month = handleNumber($("#rf-4").val()) + handleNumber($("#rf-5").val()) + handleNumber($("#rf-6").val());
                            month = Comma(month);
                            $("#rf-7").val(month);
                        }else if ({{$m}} == 8 || {{$m}} == 9 || {{$m}} == 10 ) {
                            var month =0;
                            month = handleNumber($("#rf-8").val()) + handleNumber($("#rf-9").val()) + handleNumber($("#rf-10").val());
                            month = Comma(month);
                            $("#rf-11").val(month);
                        }else if ({{$m}} == 12 || {{$m}} == 13 || {{$m}} == 14 ) {
                            var month =0;
                            month = handleNumber($("#rf-12").val()) + handleNumber($("#rf-13").val()) + handleNumber($("#rf-14").val());
                            month = Comma(month);
                            $("#rf-15").val(month);
                        }
                        var total = Comma(handleNumber($("#rf-3").val()) + handleNumber($("#rf-7").val()) + handleNumber($("#rf-11").val()) + handleNumber($("#rf-15").val()));
                        $("#total-total").val(total);
                        for(var c2=0;c2<client.length;c2++){
                            var temp = handleNumber($("#totalClient-DISC-"+c2).val())/handleNumber($("#total-total").val());
                            temp = Comma(temp*100);
                            $("#totalPP2-"+c2).val(temp);
                            $("#totalPP3-"+c2).val(temp);
                        }              
                        var value = handleNumber($(this).val());
                        var PY = handleNumber($("#PY-"+{{$c}}+"-"+{{$m}}).val());
                        var tmp = value - PY;
                        tmp = Comma(tmp);
                        $("#RFvsPY-DISC-"+{{$c}}+"-"+{{$m}}).val(tmp);
                        if ({{$m}} == 0 || {{$m}} == 1 || {{$m}} == 2 ) {
                            var value = Comma(
                                            handleNumber($("#RFvsPY-DISC-"+{{$c}}+"-0").val())
                                            +
                                            handleNumber($("#RFvsPY-DISC-"+{{$c}}+"-1").val())
                                            +
                                            handleNumber($("#RFvsPY-DISC-"+{{$c}}+"-2").val())
                                            );
                            $("#RFvsPY-DISC-"+{{$c}}+"-3").val(value);
                        }else if ({{$m}} == 4 || {{$m}} == 5 || {{$m}} == 6 ) {
                            var value = Comma(
                                            handleNumber($("#RFvsPY-DISC-"+{{$c}}+"-4").val())
                                            +
                                            handleNumber($("#RFvsPY-DISC-"+{{$c}}+"-5").val())
                                            +
                                            handleNumber($("#RFvsPY-DISC-"+{{$c}}+"-6").val())
                                            );
                            $("#RFvsPY-DISC-"+{{$c}}+"-7").val(value);
                        }else if ({{$m}} == 8 || {{$m}} == 9 || {{$m}} == 10 ) {
                            var value = Comma(
                                            handleNumber($("#RFvsPY-DISC-"+{{$c}}+"-8").val())
                                            +
                                            handleNumber($("#RFvsPY-DISC-"+{{$c}}+"-9").val())
                                            +
                                            handleNumber($("#RFvsPY-DISC-"+{{$c}}+"-10").val())
                                            );
                            $("#RFvsPY-DISC-"+{{$c}}+"-11").val(value);
                        }else if ({{$m}} == 12 || {{$m}} == 13 || {{$m}} == 14 ) {
                            var value = Comma(
                                            handleNumber($("#RFvsPY-DISC-"+{{$c}}+"-12").val())
                                            +
                                            handleNumber($("#RFvsPY-DISC-"+{{$c}}+"-13").val())
                                            +
                                            handleNumber($("#RFvsPY-DISC-"+{{$c}}+"-14").val())
                                            );
                            $("#RFvsPY-DISC-"+{{$c}}+"-15").val(value);
                        }
                        var Temp = Comma(
                                            handleNumber($("#RFvsPY-DISC-"+{{$c}}+"-3").val()) 
                                            + 
                                            handleNumber($("#RFvsPY-DISC-"+{{$c}}+"-7").val()) 
                                            + 
                                            handleNumber($("#RFvsPY-DISC-"+{{$c}}+"-11").val()) 
                                            + 
                                            handleNumber($("#RFvsPY-DISC-"+{{$c}}+"-15").val())
                                            );
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
                                month += handleNumber($("#clientRF-DISC-"+c2+"-3").val());
                            }
                            var target = handleNumber($("#target-0").val())
                                         +
                                         handleNumber($("#target-1").val())
                                         +
                                         handleNumber($("#target-2").val());
                            var booking = handleNumber($("#bookingE-0").val())
                                          +
                                          handleNumber($("#bookingE-1").val())
                                          +
                                          handleNumber($("#bookingE-2").val());
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
                                month += handleNumber($("#clientRF-DISC-"+c2+"-7").val());
                            }
                            var target = handleNumber($("#target-4").val())
                                         +
                                         handleNumber($("#target-5").val())
                                         +
                                         handleNumber($("#target-6").val());
                            var booking = handleNumber($("#bookingE-4").val())
                                          +
                                          handleNumber($("#bookingE-5").val())
                                          +
                                          handleNumber($("#bookingE-6").val());
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
                                month += handleNumber($("#clientRF-DISC-"+c2+"-11").val());
                            }
                            var target = handleNumber($("#target-8").val())
                                         +
                                         handleNumber($("#target-9").val())
                                         +
                                         handleNumber($("#target-10").val());
                            var booking = handleNumber($("#bookingE-8").val())
                                          +
                                          handleNumber($("#bookingE-9").val())
                                          +
                                          handleNumber($("#bookingE-10").val());
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
                                month += handleNumber($("#clientRF-DISC-"+c2+"-15").val());
                            }
                            var target = handleNumber($("#target-12").val())
                                         +
                                         handleNumber($("#target-13").val())
                                         +
                                         handleNumber($("#target-14").val());
                            var booking = handleNumber($("#bookingE-12").val())
                                          +
                                          handleNumber($("#bookingE-13").val())
                                          +
                                          handleNumber($("#bookingE-14").val());
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
                        var RF = handleNumber($("#rf-3").val()) 
                                 + 
                                 handleNumber($("#rf-7").val()) 
                                 + 
                                 handleNumber($("#rf-11").val()) 
                                 + 
                                 handleNumber($("#rf-15").val());
                        var target = handleNumber($("#target-3").val()) 
                                     + 
                                     handleNumber($("#target-7").val()) 
                                     + 
                                     handleNumber($("#target-11").val()) 
                                     + 
                                     handleNumber($("#target-15").val());
                        var booking = handleNumber($("#bookingE-3").val()) 
                                      + 
                                      handleNumber($("#bookingE-7").val()) 
                                      + 
                                      handleNumber($("#bookingE-11").val()) 
                                      + 
                                      handleNumber($("#bookingE-15").val());
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

            @for( $m=0;$m<16;$m++)
                @for($c=0;$c< sizeof($forRender['client']);$c++)
                    $("#clientRF-SONY-"+{{$c}}+"-"+{{$m}}).change(function(){
                        
                        if ($(this).val() == '') {
                            $(this).val(0);
                        }
                        $(this).val(Comma(handleNumber($(this).val())));
                        if ({{$m}} == 0 || {{$m}} == 1 || {{$m}} == 2 ) {
                            var value = Comma(handleNumber($("#clientRF-SONY-"+{{$c}}+"-0").val())+handleNumber($("#clientRF-SONY-"+{{$c}}+"-1").val())+handleNumber($("#clientRF-SONY-"+{{$c}}+"-2").val()));
                            $("#clientRF-SONY-"+{{$c}}+"-3").val(value);
                        }else if ({{$m}} == 4 || {{$m}} == 5 || {{$m}} == 6 ) {
                            var value = Comma(handleNumber($("#clientRF-SONY-"+{{$c}}+"-4").val())+handleNumber($("#clientRF-SONY-"+{{$c}}+"-5").val())+handleNumber($("#clientRF-SONY-"+{{$c}}+"-6").val()));
                            $("#clientRF-SONY-"+{{$c}}+"-7").val(value);
                        }else if ({{$m}} == 8 || {{$m}} == 9 || {{$m}} == 10 ) {
                            var value = Comma(handleNumber($("#clientRF-SONY-"+{{$c}}+"-8").val())+handleNumber($("#clientRF-SONY-"+{{$c}}+"-9").val())+handleNumber($("#clientRF-SONY-"+{{$c}}+"-10").val()));
                            $("#clientRF-SONY-"+{{$c}}+"-11").val(value);
                        }else if ({{$m}} == 12 || {{$m}} == 13 || {{$m}} == 14 ) {
                            var value = Comma(handleNumber($("#clientRF-SONY-"+{{$c}}+"-12").val())+handleNumber($("#clientRF-SONY-"+{{$c}}+"-13").val())+handleNumber($("#clientRF-SONY-"+{{$c}}+"-14").val()));
                            $("#clientRF-SONY-"+{{$c}}+"-15").val(value);
                        }          

                        var totalClient = Comma(
                                                handleNumber($("#clientRF-SONY-"+{{$c}}+"-3").val()) + 
                                                handleNumber($("#clientRF-SONY-"+{{$c}}+"-7").val()) + 
                                                handleNumber($("#clientRF-SONY-"+{{$c}}+"-11").val()) + 
                                                handleNumber($("#clientRF-SONY-"+{{$c}}+"-15").val())
                                                );
                        console.log(totalClient);
                        $("#totalClient-SONY-"+{{$c}}).val(totalClient);
                        Temp3 = handleNumber(totalClient);   

                        var rf = 0;
                        for(var c2=0;c2<client.length;c2++){
                            if ($("#splitted-"+c2).val() != false) {
                                var mult = 0.5;
                            }else{
                                var mult = 1;
                            }
                            rf += (handleNumber($("#clientRF-SONY-"+c2+"-"+{{$m}}).val())*mult);
                        }    

                        rf += handleNumber($("#bookingE-"+{{$m}}).val());
                        rf = Comma(rf);
                        $("#rf-"+{{$m}}).val(rf);
                        if ({{$m}} == 0 || {{$m}} == 1 || {{$m}} == 2 ) {
                            var month =0;
                            month = handleNumber($("#rf-0").val()) + handleNumber($("#rf-1").val()) + handleNumber($("#rf-2").val());
                            month = Comma(month);
                            $("#rf-3").val(month);
                        }else if ({{$m}} == 4 || {{$m}} == 5 || {{$m}} == 6 ) {
                            var month =0;
                            month = handleNumber($("#rf-4").val()) + handleNumber($("#rf-5").val()) + handleNumber($("#rf-6").val());
                            month = Comma(month);
                            $("#rf-7").val(month);
                        }else if ({{$m}} == 8 || {{$m}} == 9 || {{$m}} == 10 ) {
                            var month =0;
                            month = handleNumber($("#rf-8").val()) + handleNumber($("#rf-9").val()) + handleNumber($("#rf-10").val());
                            month = Comma(month);
                            $("#rf-11").val(month);
                        }else if ({{$m}} == 12 || {{$m}} == 13 || {{$m}} == 14 ) {
                            var month =0;
                            month = handleNumber($("#rf-12").val()) + handleNumber($("#rf-13").val()) + handleNumber($("#rf-14").val());
                            month = Comma(month);
                            $("#rf-15").val(month);
                        }
                        var total = Comma(handleNumber($("#rf-3").val()) + handleNumber($("#rf-7").val()) + handleNumber($("#rf-11").val()) + handleNumber($("#rf-15").val()));
                        $("#total-total").val(total);
                        for(var c2=0;c2<client.length;c2++){
                            var temp = handleNumber($("#totalClient-SONY-"+c2).val())/handleNumber($("#total-total").val());
                            temp = Comma(temp*100);
                            $("#totalPP2-"+c2).val(temp);
                            $("#totalPP3-"+c2).val(temp);
                        }              
                        var value = handleNumber($(this).val());
                        var PY = handleNumber($("#PY-"+{{$c}}+"-"+{{$m}}).val());
                        var tmp = value - PY;
                        tmp = Comma(tmp);
                        $("#RFvsPY-SONY-"+{{$c}}+"-"+{{$m}}).val(tmp);
                        if ({{$m}} == 0 || {{$m}} == 1 || {{$m}} == 2 ) {
                            var value = Comma(handleNumber($("#RFvsPY-SONY-"+{{$c}}+"-0").val())+handleNumber($("#RFvsPY-SONY-"+{{$c}}+"-1").val())+handleNumber($("#RFvsPY-SONY-"+{{$c}}+"-2").val()));
                            $("#RFvsPY-SONY-"+{{$c}}+"-3").val(value);
                        }else if ({{$m}} == 4 || {{$m}} == 5 || {{$m}} == 6 ) {
                            var value = Comma(handleNumber($("#RFvsPY-SONY-"+{{$c}}+"-4").val())+handleNumber($("#RFvsPY-SONY-"+{{$c}}+"-5").val())+handleNumber($("#RFvsPY-SONY-"+{{$c}}+"-6").val()));
                            $("#RFvsPY-SONY-"+{{$c}}+"-7").val(value);
                        }else if ({{$m}} == 8 || {{$m}} == 9 || {{$m}} == 10 ) {
                            var value = Comma(handleNumber($("#RFvsPY-SONY-"+{{$c}}+"-8").val())+handleNumber($("#RFvsPY-SONY-"+{{$c}}+"-9").val())+handleNumber($("#RFvsPY-SONY-"+{{$c}}+"-10").val()));
                            $("#RFvsPY-SONY-"+{{$c}}+"-11").val(value);
                        }else if ({{$m}} == 12 || {{$m}} == 13 || {{$m}} == 14 ) {
                            var value = Comma(handleNumber($("#RFvsPY-SONY-"+{{$c}}+"-12").val())+handleNumber($("#RFvsPY-SONY-"+{{$c}}+"-13").val())+handleNumber($("#RFvsPY-SONY-"+{{$c}}+"-14").val()));
                            $("#RFvsPY-SONY-"+{{$c}}+"-15").val(value);
                        }
                        var Temp = Comma(handleNumber($("#RFvsPY-SONY-"+{{$c}}+"-3").val()) + handleNumber($("#RFvsPY-SONY-"+{{$c}}+"-7").val()) + handleNumber($("#RFvsPY-SONY-"+{{$c}}+"-11").val()) + handleNumber($("#RFvsPY-SONY-"+{{$c}}+"-15").val()));
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
                                month += handleNumber($("#clientRF-SONY-"+c2+"-3").val());
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
                                month += handleNumber($("#clientRF-SONY-"+c2+"-7").val());
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
                                month += handleNumber($("#clientRF-SONY-"+c2+"-11").val());
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
                                month += handleNumber($("#clientRF-SONY-"+c2+"-15").val());
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


            $("#body").css('display',"");
            for(var c=0;c<client.length;c++){
                $("#month-"+c+"-0").css("height",$("#client-"+c).css("height"));
            }
            $("#loading").css('display',"none");
        });
    </script>

@endsection

