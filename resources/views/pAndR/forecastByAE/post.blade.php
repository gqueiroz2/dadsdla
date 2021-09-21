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

            $('.clickLoopHeader').hide();

            $(".clickBoolHeader").click(function(e){
                var myBool = $("#clickBoolHeader").val();
                
                if(myBool == 1){e
                    $(".clickLoopHeader").show();
                    myBool = 0;
                   
                }else{
                    $(".clickLoopHeader").hide();
                    myBool = 1;
                }
                 $("#clickBoolHeader").val(myBool);

            });

            $('.clickLoop').hide();

            $(".clickBool").click(function(e){
                var myBool = $("#clickBool").val();
                
                if(myBool == 1){e
                    $(".clickLoop").show();
                    myBool = 0;
                   
                }else{
                    $(".clickLoop").hide();
                    myBool = 1;
                }
                 $("#clickBool").val(myBool);

            });



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

            /**************** INICIO DAS MUDANÇAS NO CAMPO DISCOVERY ****************/
            @for( $m=0;$m<16;$m++)
                @for($c=0;$c< sizeof($forRender['client']);$c++)
                    $("#clientRF-DISC-"+{{$c}}+"-"+{{$m}}).change(function(){
                        if ($(this).val() == '') {
                            $(this).val(0);
                        }
                        /* INICIO DO AJUSTE DA LINHA MODIFICADA */
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
                        var totalClientDisc = Comma(
                    						handleNumber($("#clientRF-DISC-"+{{$c}}+"-3").val()) 
                                            + 
                    						handleNumber($("#clientRF-DISC-"+{{$c}}+"-7").val()) 
                                            + 
                    						handleNumber($("#clientRF-DISC-"+{{$c}}+"-11").val()) 
                                            + 
                    						handleNumber($("#clientRF-DISC-"+{{$c}}+"-15").val())
                    						);
                        $("#totalClient-DISC-"+{{$c}}).val(totalClientDisc);
                        /* FIM DO AJUSTE DA LINHA MODIFICADA */

                        /* INICIO DO AJUSTE DA SOMA SONY + DISCOVERY */
                        var discPlusSonyAdjust = Comma(
                                                        handleNumber($("#clientRF-DISC-"+{{$c}}+"-{{$m}}").val()) 
                                                        + 
                                                        handleNumber($("#clientRF-SONY-"+{{$c}}+"-{{$m}}").val())
                                                        );
                        $("#clientRF-TT-"+{{$c}}+"-{{$m}}").val(discPlusSonyAdjust);
                        if ({{$m}} == 0 || {{$m}} == 1 || {{$m}} == 2 ) {
                            var value = Comma(
                                            handleNumber($("#clientRF-TT-"+{{$c}}+"-0").val())
                                            +
                                            handleNumber($("#clientRF-TT-"+{{$c}}+"-1").val())
                                            + 
                                            handleNumber($("#clientRF-TT-"+{{$c}}+"-2").val())
                                            );
                            $("#clientRF-TT-"+{{$c}}+"-3").val(value);
                        }else if ({{$m}} == 4 || {{$m}} == 5 || {{$m}} == 6 ) {
                            var value = Comma(
                                            handleNumber($("#clientRF-TT-"+{{$c}}+"-4").val())
                                            +
                                            handleNumber($("#clientRF-TT-"+{{$c}}+"-5").val())
                                            +
                                            handleNumber($("#clientRF-TT-"+{{$c}}+"-6").val())
                                            );
                            $("#clientRF-TT-"+{{$c}}+"-7").val(value);
                        }else if ({{$m}} == 8 || {{$m}} == 9 || {{$m}} == 10 ) {
                            var value = Comma(
                                            handleNumber($("#clientRF-TT-"+{{$c}}+"-8").val())+
                                            handleNumber($("#clientRF-TT-"+{{$c}}+"-9").val())+
                                            handleNumber($("#clientRF-TT-"+{{$c}}+"-10").val())
                                            );
                            $("#clientRF-TT-"+{{$c}}+"-11").val(value);
                        }else if ({{$m}} == 12 || {{$m}} == 13 || {{$m}} == 14 ) {
                            var value = Comma(
                                            handleNumber($("#clientRF-TT-"+{{$c}}+"-12").val())
                                            +
                                            handleNumber($("#clientRF-TT-"+{{$c}}+"-13").val())
                                            +
                                            handleNumber($("#clientRF-TT-"+{{$c}}+"-14").val())
                                            );
                            $("#clientRF-TT-"+{{$c}}+"-15").val(value);
                        }          
                        var totalClient = Comma(
                                            handleNumber($("#clientRF-TT-"+{{$c}}+"-3").val()) 
                                            + 
                                            handleNumber($("#clientRF-TT-"+{{$c}}+"-7").val()) 
                                            + 
                                            handleNumber($("#clientRF-TT-"+{{$c}}+"-11").val()) 
                                            + 
                                            handleNumber($("#clientRF-TT-"+{{$c}}+"-15").val())
                                            );
                        $("#totalClient-TT-"+{{$c}}).val(totalClient);
                        /* FIM DO AJUSTE DA SOMA SONY + DISCOVERY */

                        /* INICIO DO AJUSTE DA LINHA E COLUNA NO VALOR DE EXECUTIVO TOTAL */
                        var rf = 0;
                        for(var c2=0;c2<client.length;c2++){
                            if ($("#splitted-"+c2).val() != false) {
                                var mult = 0.5;
                            }else{
                                var mult = 1;
                            }
                            rf += ((
                                        handleNumber($("#clientRF-DISC-"+c2+"-"+{{$m}}).val())
                                        +
                                        handleNumber($("#clientRF-SONY-"+c2+"-"+{{$m}}).val())                                    
                                    )*mult);
                        }    
                        rf += handleNumber($("#bookingE-"+{{$m}}).val());
                        rf = Comma(rf);
                        console.log(rf);
                        $("#rf-"+{{$m}}).val(rf);
                        /* FIM DO AJUSTE DA LINHA E COLUNA NO VALOR DE EXECUTIVO TOTAL */

                        /* INICIO DO AJUSTE DOS QUARTERS E DO TOTAL NO EXECUTIVO TOTAL */
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

                        /* FIM DO AJUSTE DOS QUARTERS E DO TOTAL NO EXECUTIVO TOTAL */


                        /* INICIO DO AJUSTE DO VALOR DE PENDING DO EXECUTIVO */
                        var pendingValue = Comma((
                                        handleNumber($("#rf-"+{{$m}}).val())
                                        -
                                        handleNumber($("#bookingE-"+{{$m}}).val())                                    
                                    ));

                        $("#pending-"+{{$m}}).val(pendingValue);
                            /* INICIO DO AJUSTE DO PENDING NOS QUARTERS E DO TOTAL NO EXECUTIVO TOTAL */

                            if ({{$m}} == 0 || {{$m}} == 1 || {{$m}} == 2 ) {
                                var month =0;
                                month = handleNumber($("#pending-0").val()) + handleNumber($("#pending-1").val()) + handleNumber($("#pending-2").val());
                                month = Comma(month);
                                $("#pending-3").val(month);
                            }else if ({{$m}} == 4 || {{$m}} == 5 || {{$m}} == 6 ) {
                                var month =0;
                                month = handleNumber($("#pending-4").val()) + handleNumber($("#pending-5").val()) + handleNumber($("#pending-6").val());
                                month = Comma(month);
                                $("#pending-7").val(month);
                            }else if ({{$m}} == 8 || {{$m}} == 9 || {{$m}} == 10 ) {
                                var month =0;
                                month = handleNumber($("#pending-8").val()) + handleNumber($("#pending-9").val()) + handleNumber($("#pending-10").val());
                                month = Comma(month);
                                $("#pending-11").val(month);
                            }else if ({{$m}} == 12 || {{$m}} == 13 || {{$m}} == 14 ) {
                                var month =0;
                                month = handleNumber($("#pending-12").val()) + handleNumber($("#pending-13").val()) + handleNumber($("#pending-14").val());
                                month = Comma(month);
                                $("#pending-15").val(month);
                            }
                            var total = Comma(handleNumber($("#pending-3").val()) + handleNumber($("#pending-7").val()) + handleNumber($("#pending-11").val()) + handleNumber($("#pending-15").val()));
                            $("#totalPending").val(total);

                            /* FIM DO AJUSTE DO PENDING NOS QUARTERS E DO TOTAL NO EXECUTIVO TOTAL */                        
                        /* FIM DO AJUSTE DO VALOR DE PENDING DO EXECUTIVO */

                        /* INICIO DO AJUSTE DO VALOR DE FORECAST - TARGET DO EXECUTIVO */
                        var varValue = Comma((
                                        handleNumber($("#rf-"+{{$m}}).val())
                                        -
                                        handleNumber($("#target-"+{{$m}}).val())                                    
                                    ));

                        $("#RFvsTarget-"+{{$m}}).val(varValue);
                            /* INICIO DO AJUSTE DO FORECAST - TARGET NOS QUARTERS E DO TOTAL NO EXECUTIVO TOTAL */

                            if ({{$m}} == 0 || {{$m}} == 1 || {{$m}} == 2 ) {
                                var month =0;
                                month = handleNumber($("#RFvsTarget-0").val()) + handleNumber($("#RFvsTarget-1").val()) + handleNumber($("#RFvsTarget-2").val());
                                month = Comma(month);
                                $("#RFvsTarget-3").val(month);
                            }else if ({{$m}} == 4 || {{$m}} == 5 || {{$m}} == 6 ) {
                                var month =0;
                                month = handleNumber($("#RFvsTarget-4").val()) + handleNumber($("#RFvsTarget-5").val()) + handleNumber($("#RFvsTarget-6").val());
                                month = Comma(month);
                                $("#RFvsTarget-7").val(month);
                            }else if ({{$m}} == 8 || {{$m}} == 9 || {{$m}} == 10 ) {
                                var month =0;
                                month = handleNumber($("#RFvsTarget-8").val()) + handleNumber($("#RFvsTarget-9").val()) + handleNumber($("#RFvsTarget-10").val());
                                month = Comma(month);
                                $("#RFvsTarget-11").val(month);
                            }else if ({{$m}} == 12 || {{$m}} == 13 || {{$m}} == 14 ) {
                                var month =0;
                                month = handleNumber($("#RFvsTarget-12").val()) + handleNumber($("#RFvsTarget-13").val()) + handleNumber($("#RFvsTarget-14").val());
                                month = Comma(month);
                                $("#RFvsTarget-15").val(month);
                            }
                            var total = Comma(handleNumber($("#RFvsTarget-3").val()) + handleNumber($("#RFvsTarget-7").val()) + handleNumber($("#RFvsTarget-11").val()) + handleNumber($("#RFvsTarget-15").val()));
                            $("#totalRFvsTarget").val(total);

                            /* FIM DO AJUSTE DO FORECAST - TARGET NOS QUARTERS E DO TOTAL NO EXECUTIVO TOTAL */ 
                        /* FIM DO AJUSTE DO VALOR DE FORECAST - TARGET DO EXECUTIVO */

                        /* INICIO DO AJUSTE DO VALOR DE TARGET ACHIEVEMENT DO EXECUTIVO */
                        var varValue = Comma(
                                            Math.round(
                                                (
                                                    handleNumber($("#rf-"+{{$m}}).val())
                                                    /
                                                    handleNumber($("#target-"+{{$m}}).val()) 
                                                )*100                                   
                                            )
                                            );

                        $("#achievement-"+{{$m}}).val(varValue+"%");
                            /* INICIO DO AJUSTE DO TARGET ACHIEVEMENT NOS QUARTERS E DO TOTAL NO EXECUTIVO TOTAL */

                            if ({{$m}} == 0 || {{$m}} == 1 || {{$m}} == 2 ) {
                                var variation = Math.round( 
                                                    ( handleNumber($("#rf-3").val()) / handleNumber($("#target-3").val()) )*100
                                                    );
                                variation = Comma(variation);
                                $("#achievement-3").val(variation+"%");
                            }else if ({{$m}} == 4 || {{$m}} == 5 || {{$m}} == 6 ) {
                                var variation = Math.round( 
                                                    ( handleNumber($("#rf-7").val()) / handleNumber($("#target-7").val()) )*100
                                                    );
                                variation = Comma(variation);
                                $("#achievement-7").val(variation+"%");                                
                            }else if ({{$m}} == 8 || {{$m}} == 9 || {{$m}} == 10 ) {
                                var variation = Math.round( 
                                                    ( handleNumber($("#rf-11").val()) / handleNumber($("#target-11").val()) )*100
                                                    );
                                variation = Comma(variation);
                                $("#achievement-11").val(variation+"%"); 
                            }else if ({{$m}} == 12 || {{$m}} == 13 || {{$m}} == 14 ) {
                                var variation = Math.round( 
                                                    ( handleNumber($("#rf-15").val()) / handleNumber($("#target-15").val()) )*100
                                                    );
                                variation = Comma(variation);
                                $("#achievement-15").val(variation+"%"); 
                            }
                            varRFcst = handleNumber($("#total-total").val());
                            varTarget = handleNumber($("#totalTarget").val());
                            var varTotal = Math.round((varRFcst/varTarget)*100);
                            varTotal = Comma(varTotal)

                            $("#totalAchievement").val(varTotal+"%");

                            /* FIM DO AJUSTE DO TARGET ACHIEVEMENT NOS QUARTERS E DO TOTAL NO EXECUTIVO TOTAL */ 
                        /* FIM DO AJUSTE DO VALOR DE TARGET ACHIEVEMENT DO EXECUTIVO */
                    });
                @endfor
            @endfor
            /**************** FIM DAS MUDANÇAS NO CAMPO DISCOVERY ****************/


            /**************** INICIO DAS MUDANÇAS NO CAMPO SONY ****************/
            @for( $m=0;$m<16;$m++)
                @for($c=0;$c< sizeof($forRender['client']);$c++)
                    $("#clientRF-SONY-"+{{$c}}+"-"+{{$m}}).change(function(){
                        if ($(this).val() == '') {
                            $(this).val(0);
                        }
                        /* INICIO DO AJUSTE DA LINHA MODIFICADA */
                        $(this).val(Comma(handleNumber($(this).val())));
                        if ({{$m}} == 0 || {{$m}} == 1 || {{$m}} == 2 ) {
                            var value = Comma(
                                            handleNumber($("#clientRF-SONY-"+{{$c}}+"-0").val())
                                            +
                                            handleNumber($("#clientRF-SONY-"+{{$c}}+"-1").val())
                                            + 
                                            handleNumber($("#clientRF-SONY-"+{{$c}}+"-2").val())
                                            );
                            $("#clientRF-SONY-"+{{$c}}+"-3").val(value);
                        }else if ({{$m}} == 4 || {{$m}} == 5 || {{$m}} == 6 ) {
                            var value = Comma(
                                            handleNumber($("#clientRF-SONY-"+{{$c}}+"-4").val())
                                            +
                                            handleNumber($("#clientRF-SONY-"+{{$c}}+"-5").val())
                                            +
                                            handleNumber($("#clientRF-SONY-"+{{$c}}+"-6").val())
                                            );
                            $("#clientRF-SONY-"+{{$c}}+"-7").val(value);
                        }else if ({{$m}} == 8 || {{$m}} == 9 || {{$m}} == 10 ) {
                            var value = Comma(
                                            handleNumber($("#clientRF-SONY-"+{{$c}}+"-8").val())+
                                            handleNumber($("#clientRF-SONY-"+{{$c}}+"-9").val())+
                                            handleNumber($("#clientRF-SONY-"+{{$c}}+"-10").val())
                                            );
                            $("#clientRF-SONY-"+{{$c}}+"-11").val(value);
                        }else if ({{$m}} == 12 || {{$m}} == 13 || {{$m}} == 14 ) {
                            var value = Comma(
                                            handleNumber($("#clientRF-SONY-"+{{$c}}+"-12").val())
                                            +
                                            handleNumber($("#clientRF-SONY-"+{{$c}}+"-13").val())
                                            +
                                            handleNumber($("#clientRF-SONY-"+{{$c}}+"-14").val())
                                            );
                            $("#clientRF-SONY-"+{{$c}}+"-15").val(value);
                        }          
                        var totalClientSONY = Comma(
                                            handleNumber($("#clientRF-SONY-"+{{$c}}+"-3").val()) 
                                            + 
                                            handleNumber($("#clientRF-SONY-"+{{$c}}+"-7").val()) 
                                            + 
                                            handleNumber($("#clientRF-SONY-"+{{$c}}+"-11").val()) 
                                            + 
                                            handleNumber($("#clientRF-SONY-"+{{$c}}+"-15").val())
                                            );
                        $("#totalClient-SONY-"+{{$c}}).val(totalClientSONY);
                        /* FIM DO AJUSTE DA LINHA MODIFICADA */

                        /* INICIO DO AJUSTE DA SOMA SONY + DISCOVERY */
                        var discPlusSonyAdjust = Comma(
                                                        handleNumber($("#clientRF-DISC-"+{{$c}}+"-{{$m}}").val()) 
                                                        + 
                                                        handleNumber($("#clientRF-SONY-"+{{$c}}+"-{{$m}}").val())
                                                        );
                        $("#clientRF-TT-"+{{$c}}+"-{{$m}}").val(discPlusSonyAdjust);
                        if ({{$m}} == 0 || {{$m}} == 1 || {{$m}} == 2 ) {
                            var value = Comma(
                                            handleNumber($("#clientRF-TT-"+{{$c}}+"-0").val())
                                            +
                                            handleNumber($("#clientRF-TT-"+{{$c}}+"-1").val())
                                            + 
                                            handleNumber($("#clientRF-TT-"+{{$c}}+"-2").val())
                                            );
                            $("#clientRF-TT-"+{{$c}}+"-3").val(value);
                        }else if ({{$m}} == 4 || {{$m}} == 5 || {{$m}} == 6 ) {
                            var value = Comma(
                                            handleNumber($("#clientRF-TT-"+{{$c}}+"-4").val())
                                            +
                                            handleNumber($("#clientRF-TT-"+{{$c}}+"-5").val())
                                            +
                                            handleNumber($("#clientRF-TT-"+{{$c}}+"-6").val())
                                            );
                            $("#clientRF-TT-"+{{$c}}+"-7").val(value);
                        }else if ({{$m}} == 8 || {{$m}} == 9 || {{$m}} == 10 ) {
                            var value = Comma(
                                            handleNumber($("#clientRF-TT-"+{{$c}}+"-8").val())+
                                            handleNumber($("#clientRF-TT-"+{{$c}}+"-9").val())+
                                            handleNumber($("#clientRF-TT-"+{{$c}}+"-10").val())
                                            );
                            $("#clientRF-TT-"+{{$c}}+"-11").val(value);
                        }else if ({{$m}} == 12 || {{$m}} == 13 || {{$m}} == 14 ) {
                            var value = Comma(
                                            handleNumber($("#clientRF-TT-"+{{$c}}+"-12").val())
                                            +
                                            handleNumber($("#clientRF-TT-"+{{$c}}+"-13").val())
                                            +
                                            handleNumber($("#clientRF-TT-"+{{$c}}+"-14").val())
                                            );
                            $("#clientRF-TT-"+{{$c}}+"-15").val(value);
                        }          
                        var totalClient = Comma(
                                            handleNumber($("#clientRF-TT-"+{{$c}}+"-3").val()) 
                                            + 
                                            handleNumber($("#clientRF-TT-"+{{$c}}+"-7").val()) 
                                            + 
                                            handleNumber($("#clientRF-TT-"+{{$c}}+"-11").val()) 
                                            + 
                                            handleNumber($("#clientRF-TT-"+{{$c}}+"-15").val())
                                            );
                        $("#totalClient-TT-"+{{$c}}).val(totalClient);
                        /* FIM DO AJUSTE DA SOMA SONY + DISCOVERY */

                        /* INICIO DO AJUSTE DA LINHA E COLUNA NO VALOR DE EXECUTIVO TOTAL */
                        var rf = 0;
                        for(var c2=0;c2<client.length;c2++){
                            if ($("#splitted-"+c2).val() != false) {
                                var mult = 0.5;
                            }else{
                                var mult = 1;
                            }
                            rf += ((
                                        handleNumber($("#clientRF-DISC-"+c2+"-"+{{$m}}).val())
                                        +
                                        handleNumber($("#clientRF-SONY-"+c2+"-"+{{$m}}).val())                                    
                                    )*mult);
                        }    
                        rf += handleNumber($("#bookingE-"+{{$m}}).val());
                        rf = Comma(rf);
                        console.log(rf);
                        $("#rf-"+{{$m}}).val(rf);
                        /* FIM DO AJUSTE DA LINHA E COLUNA NO VALOR DE EXECUTIVO TOTAL */

                        /* INICIO DO AJUSTE DOS QUARTERS E DO TOTAL NO EXECUTIVO TOTAL */
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

                        /* FIM DO AJUSTE DOS QUARTERS E DO TOTAL NO EXECUTIVO TOTAL */


                        /* INICIO DO AJUSTE DO VALOR DE PENDING DO EXECUTIVO */
                        var pendingValue = Comma((
                                        handleNumber($("#rf-"+{{$m}}).val())
                                        -
                                        handleNumber($("#bookingE-"+{{$m}}).val())                                    
                                    ));

                        $("#pending-"+{{$m}}).val(pendingValue);
                            /* INICIO DO AJUSTE DO PENDING NOS QUARTERS E DO TOTAL NO EXECUTIVO TOTAL */

                            if ({{$m}} == 0 || {{$m}} == 1 || {{$m}} == 2 ) {
                                var month =0;
                                month = handleNumber($("#pending-0").val()) + handleNumber($("#pending-1").val()) + handleNumber($("#pending-2").val());
                                month = Comma(month);
                                $("#pending-3").val(month);
                            }else if ({{$m}} == 4 || {{$m}} == 5 || {{$m}} == 6 ) {
                                var month =0;
                                month = handleNumber($("#pending-4").val()) + handleNumber($("#pending-5").val()) + handleNumber($("#pending-6").val());
                                month = Comma(month);
                                $("#pending-7").val(month);
                            }else if ({{$m}} == 8 || {{$m}} == 9 || {{$m}} == 10 ) {
                                var month =0;
                                month = handleNumber($("#pending-8").val()) + handleNumber($("#pending-9").val()) + handleNumber($("#pending-10").val());
                                month = Comma(month);
                                $("#pending-11").val(month);
                            }else if ({{$m}} == 12 || {{$m}} == 13 || {{$m}} == 14 ) {
                                var month =0;
                                month = handleNumber($("#pending-12").val()) + handleNumber($("#pending-13").val()) + handleNumber($("#pending-14").val());
                                month = Comma(month);
                                $("#pending-15").val(month);
                            }
                            var total = Comma(handleNumber($("#pending-3").val()) + handleNumber($("#pending-7").val()) + handleNumber($("#pending-11").val()) + handleNumber($("#pending-15").val()));
                            $("#totalPending").val(total);

                            /* FIM DO AJUSTE DO PENDING NOS QUARTERS E DO TOTAL NO EXECUTIVO TOTAL */                        
                        /* FIM DO AJUSTE DO VALOR DE PENDING DO EXECUTIVO */

                        /* INICIO DO AJUSTE DO VALOR DE FORECAST - TARGET DO EXECUTIVO */
                        var varValue = Comma((
                                        handleNumber($("#rf-"+{{$m}}).val())
                                        -
                                        handleNumber($("#target-"+{{$m}}).val())                                    
                                    ));

                        $("#RFvsTarget-"+{{$m}}).val(varValue);
                            /* INICIO DO AJUSTE DO FORECAST - TARGET NOS QUARTERS E DO TOTAL NO EXECUTIVO TOTAL */

                            if ({{$m}} == 0 || {{$m}} == 1 || {{$m}} == 2 ) {
                                var month =0;
                                month = handleNumber($("#RFvsTarget-0").val()) + handleNumber($("#RFvsTarget-1").val()) + handleNumber($("#RFvsTarget-2").val());
                                month = Comma(month);
                                $("#RFvsTarget-3").val(month);
                            }else if ({{$m}} == 4 || {{$m}} == 5 || {{$m}} == 6 ) {
                                var month =0;
                                month = handleNumber($("#RFvsTarget-4").val()) + handleNumber($("#RFvsTarget-5").val()) + handleNumber($("#RFvsTarget-6").val());
                                month = Comma(month);
                                $("#RFvsTarget-7").val(month);
                            }else if ({{$m}} == 8 || {{$m}} == 9 || {{$m}} == 10 ) {
                                var month =0;
                                month = handleNumber($("#RFvsTarget-8").val()) + handleNumber($("#RFvsTarget-9").val()) + handleNumber($("#RFvsTarget-10").val());
                                month = Comma(month);
                                $("#RFvsTarget-11").val(month);
                            }else if ({{$m}} == 12 || {{$m}} == 13 || {{$m}} == 14 ) {
                                var month =0;
                                month = handleNumber($("#RFvsTarget-12").val()) + handleNumber($("#RFvsTarget-13").val()) + handleNumber($("#RFvsTarget-14").val());
                                month = Comma(month);
                                $("#RFvsTarget-15").val(month);
                            }
                            var total = Comma(handleNumber($("#RFvsTarget-3").val()) + handleNumber($("#RFvsTarget-7").val()) + handleNumber($("#RFvsTarget-11").val()) + handleNumber($("#RFvsTarget-15").val()));
                            $("#totalRFvsTarget").val(total);

                            /* FIM DO AJUSTE DO FORECAST - TARGET NOS QUARTERS E DO TOTAL NO EXECUTIVO TOTAL */ 
                        /* FIM DO AJUSTE DO VALOR DE FORECAST - TARGET DO EXECUTIVO */

                        /* INICIO DO AJUSTE DO VALOR DE TARGET ACHIEVEMENT DO EXECUTIVO */
                        var varValue = Comma(
                                            Math.round(
                                                (
                                                    handleNumber($("#rf-"+{{$m}}).val())
                                                    /
                                                    handleNumber($("#target-"+{{$m}}).val()) 
                                                )*100                                   
                                            )
                                            );

                        $("#achievement-"+{{$m}}).val(varValue+"%");
                            /* INICIO DO AJUSTE DO TARGET ACHIEVEMENT NOS QUARTERS E DO TOTAL NO EXECUTIVO TOTAL */

                            if ({{$m}} == 0 || {{$m}} == 1 || {{$m}} == 2 ) {
                                var variation = Math.round( 
                                                    ( handleNumber($("#rf-3").val()) / handleNumber($("#target-3").val()) )*100
                                                    );
                                variation = Comma(variation);
                                $("#achievement-3").val(variation+"%");
                            }else if ({{$m}} == 4 || {{$m}} == 5 || {{$m}} == 6 ) {
                                var variation = Math.round( 
                                                    ( handleNumber($("#rf-7").val()) / handleNumber($("#target-7").val()) )*100
                                                    );
                                variation = Comma(variation);
                                $("#achievement-7").val(variation+"%");                                
                            }else if ({{$m}} == 8 || {{$m}} == 9 || {{$m}} == 10 ) {
                                var variation = Math.round( 
                                                    ( handleNumber($("#rf-11").val()) / handleNumber($("#target-11").val()) )*100
                                                    );
                                variation = Comma(variation);
                                $("#achievement-11").val(variation+"%"); 
                            }else if ({{$m}} == 12 || {{$m}} == 13 || {{$m}} == 14 ) {
                                var variation = Math.round( 
                                                    ( handleNumber($("#rf-15").val()) / handleNumber($("#target-15").val()) )*100
                                                    );
                                variation = Comma(variation);
                                $("#achievement-15").val(variation+"%"); 
                            }
                            varRFcst = handleNumber($("#total-total").val());
                            varTarget = handleNumber($("#totalTarget").val());
                            var varTotal = Math.round((varRFcst/varTarget)*100);
                            varTotal = Comma(varTotal)

                            $("#totalAchievement").val(varTotal+"%");

                            /* FIM DO AJUSTE DO TARGET ACHIEVEMENT NOS QUARTERS E DO TOTAL NO EXECUTIVO TOTAL */ 
                        /* FIM DO AJUSTE DO VALOR DE TARGET ACHIEVEMENT DO EXECUTIVO */
                    });
                @endfor
            @endfor
            /**************** FIM DAS MUDANÇAS NO CAMPO SONY ****************/




            $("#body").css('display',"");
            for(var c=0;c<client.length;c++){
                $("#month-"+c+"-0").css("height",$("#client-"+c).css("height"));
            }
            $("#loading").css('display',"none");
        });
    </script>

@endsection

