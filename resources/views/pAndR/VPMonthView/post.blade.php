@extends('layouts.mirror')
@section('title', 'Month Adjust')
@section('head')	
    <?php include(resource_path('views/auth.php')); ?>
    <script src="/js/pandr.js"></script>
    <style>
    	.temporario{
    		display:block;
    		float: left;
    		clear: left;
    		width: 100%;
    	}

    	#myInput {
		  background-position: 10px 12px; /* Position the search icon */
		  background-repeat: no-repeat; /* Do not repeat the icon image */
		  width: 100%; /* Full-width */
		  font-size: 16px; /* Increase font-size */
		  border: 1px solid #ddd; /* Add a grey border */
		  margin-bottom: 12px; /* Add some space below the input */
		  text-align: center;
		}
		#loading {
            position: absolute;
            left: 0px;
            top:0px;
            margin:0px;
            width: 100%;
            height: 105%;
            display:block;
            z-index: 99999;
            opacity: 0.9;
            -moz-opacity: 0;
            filter: alpha(opacity = 45);
            background: white;
            background-image: url("/loading.gif");
            background-repeat: no-repeat;
            background-position:50% 50%;
            text-align: center;
            overflow: hidden;
            font-size:30px;
            font-weight: bold;
            color: black;
            padding-top: 20%;
        }
    </style>
@endsection
@section('content')
	

	@if($userLevel == 'SU' || $userLevel == 'L0' || $userLevel == 'L1' )

	<form method="POST" action="{{ route('VPMonthPost') }}" runat="server"  onsubmit="ShowLoading()">
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
						{{$render->regionFiltered($region, $regionID, $special)}}
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
	<div class="container-fluid">
		<div class="row justify-content-end mt-2">
			<div class="col-3" style="color: #0070c0;font-size: 25px;">
				Month Adjust
			</div>
		</div>
	</div>
	<br>

	@if(!$forRender)
		<div class="col" style="width: 100%; padding-right: 2%;">
			<div style="min-height: 100px;" class="alert alert-warning" role="alert">
				<span style="font-size:22px;">
					<center>
					There is no submissions of Forecast from Advertisers Adjust yet!
					</center>
				</span>
			</div>
		</div>
    @else
    	<div class="container-fluid" id="body" style="display: none;">
			<form method="POST" action="{{ route('VPMonthSave') }}" runat="server"  onsubmit="ShowLoading()">
			@csrf
				<div class="row justify-content-end">
					<div class="col"></div>
					<div class="col"></div>
					<div class="col"></div>
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
				<div class="row mt-2 justify-content-end">
					<div class="col" style="width: 100%;">
						<center>
							<input type='hidden' id='user' name='user' value="{{base64_encode(json_encode($userName))}}">
							{{ $render->assemble($forRender, $client, $tfArray, $odd, $even, $rtr) }}
						</center>
					</div>
				</div>
			</form>
		</div>

		<div id="loading">
	        Processing Request...
	        <br>
	    </div>
		<script type="text/javascript">
			var aux = ['Jan','Feb','Mar','Q1','Apr','May','Jun','Q2','Jul','Aug','Sep','Q3','Oct','Nov','Dec','Q4'];

			$(document).ready(function(){				
				
				

				$("input[type=radio][name=options]").change(function(){
					if (this.value == 'save') {
						$("#button").val("Save");
					}else{
						$("#button").val("Submit");
					}
				});

				@for( $m=0;$m<16;$m++)
					$("#me-"+{{$m}}).change(function(){
						if ($(this).val() ==  '') {
							$(this).val(0);
						}
						$(this).val(Comma(handleNumber($(this).val())));
						if ({{$m}} == 0 || {{$m}} == 1 || {{$m}} == 2) {
							var value = Comma(handleNumber($("#me-0").val())+handleNumber($("#me-1").val())+handleNumber($("#me-2").val()));
							$("#me-3").val(value);
						}else if ({{$m}} == 4 || {{$m}} == 5 || {{$m}} == 6 ) {
							var value = Comma(handleNumber($("#me-4").val())+handleNumber($("#me-5").val())+handleNumber($("#me-5").val()));
							$("#me-7").val(value);
						}else if ({{$m}} == 8 || {{$m}} == 9 || {{$m}} == 10 ) {
							var value = Comma(handleNumber($("#me-8").val())+handleNumber($("#me-9").val())+handleNumber($("#me-10").val()));
							$("#me-11").val(value);
						}else if ({{$m}} == 12 || {{$m}} == 13 || {{$m}} == 14 ) {
							var value = Comma(handleNumber($("#me-12").val())+handleNumber($("#me-13").val())+handleNumber($("#me-14").val()));
							$("#me-15").val(value);
						}

						var total = Comma(handleNumber($("#me-3").val()) + handleNumber($("#me-7").val()) + handleNumber($("#me-11").val()) + handleNumber($("#me-15").val()));
						
						$("#total-manualEstimationTotal").val(total);

						for (var i = 0; i <16; i++) {
							$("#RFvsTarget-"+i).val( Comma(handleNumber($("#me-"+i).val()) - handleNumber($("#target-"+i).val())) );

							if (handleNumber($("#target-"+i).val()) != 0) {
								$("#achievement-"+i).val( Comma(((handleNumber($("#me-"+i).val())/handleNumber($("#target-"+i).val()))*100).toFixed(0))+"%");
							}else{
								$("#achievement-"+i).val(0+"%") ;
							}
						}

						$("#TotalRFvsTarget").val(Comma(handleNumber($("#total-manualEstimationTotal").val()) - handleNumber($("#totalTarget").val())) );
						if ( handleNumber($("#totalTarget").val()) != 0) {
							$("#totalAchievement").val(Comma(((handleNumber($("#total-manualEstimationTotal").val()) / handleNumber($("#totalTarget").val()))*100).toFixed(0))+"%");
						}else{
							$("#totalAchievement").val(Comma(0+"%"));
						}

						var mult = handleNumber($("#total-manualEstimationTotal").val()) - handleNumber($("#totalBookingE").val());
							if (mult<0) {
								for (var i = 0; i < 16; i++) {
								$("#me-"+i).val($("#rf-"+i).val());
							}

							$(this).val($("#total-total").val());

							for (var i = 0; i <16; i++) {
								$("#RFvsTarget-"+i).val( Comma(handleNumber($("#me-"+i).val()) - handleNumber($("#target-"+i).val())) );

								if (handleNumber($("#target-"+i).val()) != 0) {
									$("#achievement-"+i).val( Comma(((handleNumber($("#me-"+i).val())/handleNumber($("#target-"+i).val()))*100).toFixed(0))+"%");
								}else{
									$("#achievement-"+i).val(Comma(0)+"%") ;
								}
							}

							$("#TotalRFvsTarget").val(Comma(handleNumber($("#total-manualEstimationTotal").val()) - handleNumber($("#totalTarget").val())) );
							if ( handleNumber($("#totalTarget").val()) != 0) {
								$("#totalAchievement").val(Comma(((handleNumber($("#total-manualEstimationTotal").val()) / handleNumber($("#totalTarget").val()))*100).toFixed(0))+"%");
							}else{
								$("#totalAchievement").val(Comma(0)+"%");
							}
						}

					});
				@endfor

				$("#total-manualEstimationTotal").change(function(){
					if ($(this).val() ==  '') {
						$(this).val(0);
					}
					
					$(this).val(Comma(handleNumber($(this).val())));

					var mult = handleNumber($(this).val()) - handleNumber($("#totalBookingE").val());
					alert(mult);
					if (mult>0) {

						var date = {{(intval(date('n'))-1)}};

				        if (date < 3) {
				        }else if (date < 6) {
				            date ++;
				        }else if (date < 9) {
				            date += 2;
				        }else{
				            date += 3;
				        }

				        var total = parseFloat(0);

				        for (var i =date;i<16 ; i++) {
				        	if (i == 3 || i == 7 || i == 11 || i == 15) {
				        	}else{
				        		total += handleNumber($("#rf-"+i).val());
				        	}
				        }
				        var test = parseFloat(0);
				        for (var i = date ;i<16 ; i++) {
				        	if (i == 3 || i == 7 || i == 11 || i == 15) {
				        	}else{
				        		var prc = (handleNumber($("#rf-"+i).val())/total);
				        		var temp = (mult*prc);
				        		temp += '';
				        		temp = parseFloat(temp.replace('.',','));
				        		$("#me-"+i).val(Comma(temp + handleNumber($("#bookingE-"+i).val())));
				        	}
				        }


				        var value = Comma(handleNumber($("#me-0").val())+handleNumber($("#me-1").val())+handleNumber($("#me-2").val()));
						$("#me-3").val(value);
						var value = Comma(handleNumber($("#me-4").val())+handleNumber($("#me-5").val())+handleNumber($("#me-6").val()));
						$("#me-7").val(value);
						var value = Comma(handleNumber($("#me-8").val())+handleNumber($("#me-9").val())+handleNumber($("#me-10").val()));
						$("#me-11").val(value);
						var value = Comma(handleNumber($("#me-12").val())+handleNumber($("#me-13").val())+handleNumber($("#me-14").val()));
						$("#me-15").val(value);

						for (var i = 0; i <16; i++) {
							$("#RFvsTarget-"+i).val( Comma(handleNumber($("#me-"+i).val()) - handleNumber($("#target-"+i).val())) );

							if (handleNumber($("#target-"+i).val()) != 0) {
								$("#achievement-"+i).val( Comma(((handleNumber($("#me-"+i).val())/handleNumber($("#target-"+i).val()))*100).toFixed(0))+"%");
							}else{
								$("#achievement-"+i).val(0+"%") ;
							}
						}

						$("#TotalRFvsTarget").val(Comma(handleNumber($("#total-manualEstimationTotal").val()) - handleNumber($("#totalTarget").val())) );
						if ( handleNumber($("#totalTarget").val()) != 0) {
							$("#totalAchievement").val(Comma(((handleNumber($("#total-manualEstimationTotal").val()) / handleNumber($("#totalTarget").val()))*100).toFixed(0))+"%");
						}else{
							$("#totalAchievement").val(Comma(0+"%"));
						}

					}else{
						for (var i = 0; i < 16; i++) {
							$("#me-"+i).val($("#rf-"+i).val());
						}

						$(this).val($("#total-total").val());

						for (var i = 0; i <16; i++) {
							$("#RFvsTarget-"+i).val( Comma(handleNumber($("#me-"+i).val()) - handleNumber($("#target-"+i).val())) );

							if (handleNumber($("#target-"+i).val()) != 0) {
								$("#achievement-"+i).val( Comma(((handleNumber($("#me-"+i).val())/handleNumber($("#target-"+i).val()))*100).toFixed(0))+"%");
							}else{
								$("#achievement-"+i).val(Comma(0)+"%") ;
							}
						}

						$("#TotalRFvsTarget").val(Comma(handleNumber($("#total-manualEstimationTotal").val()) - handleNumber($("#totalTarget").val())) );
						if ( handleNumber($("#totalTarget").val()) != 0) {
							$("#totalAchievement").val(Comma(((handleNumber($("#total-manualEstimationTotal").val()) / handleNumber($("#totalTarget").val()))*100).toFixed(0))+"%");
						}else{
							$("#totalAchievement").val(Comma(0)+"%");
						}
					}
				});
            	$("#body").css('display',"");

				$("#linha-1-1").css("height",$("#linha-2-1").css("height"));
				$("#linha-1-2").css("height",$("#linha-2-2").css("height"));
				$("#linha-1-3").css("height",$("#linha-2-3").css("height"));
				$("#linha-1-4").css("height",$("#linha-2-4").css("height"));
				$("#linha-1-5").css("height",$("#linha-2-5").css("height"));
				$("#linha-1-6").css("height",$("#linha-2-6").css("height"));
				$("#linha-1-7").css("height",$("#linha-2-7").css("height"));
				$("#linha-1-8").css("height",$("#linha-2-8").css("height"));
				$("#linha-1-9").css("height",$("#linha-2-9").css("height"));
				$("#linha-1-10").css("height",$("#linha-2-10").css("height"));

				$("#loading").css('display',"none");

			});



		</script>

    @endif
    @endif

@endsection