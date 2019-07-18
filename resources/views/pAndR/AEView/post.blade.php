@extends('layouts.mirror')
@section('title', 'AE Report')
@section('head')	
    <?php include(resource_path('views/auth.php')); 
    $month = array('Jan','Feb','Mar','Q1','Apr','May','Jun','Q2','Jul','Aug','Sep','Q3','Oct','Nov','Dec','Q4');?>

    <script src="/js/pandr.js"></script>
    
@endsection
@section('content')
	<div class="container-fluid">
		<div class="row justify-content-end mt-2">
			<div class="col-3" style="color: #0070c0;font-size: 25px;">
				Account Executive Report
			</div>
		</div>
	</div>

	<form method="POST" action="{{ route('AEPost') }}" runat="server"  onsubmit="ShowLoading()">
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
						{{$render->regionFiltered($region, $regionID )}}
					@endif
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
					<label class='labelLeft'> &nbsp; </label>
					<input style="width: 100%;" type="submit" value="Generate" class="btn btn-primary">		
				</div>			
			</div>
		</div>
	</form>
	<br>
	<div class="container-fluid">
		<div class="row">
			<div class="col" style="width: 100%;">
				<center>
					{{$render->AE1($total2018,$totaltotal2018,$totalClient2018,$client2018)}}
				</center>
			</div>
		</div>
	</div>

	<div id="vlau"></div>

	<script>
		$(document).ready(function(){
			@for($m=0;$m<16;$m++)
				$("#rf-"+{{$m}}).change(function(){

					if ($(this).val() == '') {
						$(this).val(parseFloat(0));
					}

					if ({{$m}} == 0 || {{$m}} == 1 || {{$m}} == 2 ) {
						var value = parseFloat($("#rf-0").val())+parseFloat($("#rf-1").val())+parseFloat($("#rf-2").val());
						$("#rf-3").val(value);
					}else if ({{$m}} == 4 || {{$m}} == 5 || {{$m}} == 6 ) {
						var value = parseFloat($("#rf-4").val())+parseFloat($("#rf-5").val())+parseFloat($("#rf-6").val());
						$("#rf-7").val(value);
					}else if ({{$m}} == 8 || {{$m}} == 9 || {{$m}} == 10 ) {
						var value = parseFloat($("#rf-8").val())+parseFloat($("#rf-9").val())+parseFloat($("#rf-10").val());
						$("#rf-11").val(value);
					}else if ({{$m}} == 12 || {{$m}} == 13 || {{$m}} == 14 ) {
						var value = parseFloat($("#rf-12").val())+parseFloat($("#rf-13").val())+parseFloat($("#rf-14").val());
						$("#rf-15").val(value);
					}
				
					var Temp = parseFloat($("#rf-3").val()) + parseFloat($("#rf-7").val()) + parseFloat($("#rf-11").val()) + parseFloat($("#rf-15").val());

					$("#total-total").val(Temp);


					@for($c=0;$c<10;$c++)
						var temp = $(this).val()*parseFloat($("#inputNumber-"+{{$c}}+"-"+{{$m}}).val()/100);

						$("#clientRF-"+{{$c}}+"-"+{{$m}}).val(temp);
						
						if ({{$m}} == 0 || {{$m}} == 1 || {{$m}} == 2 ) {
							var value = parseFloat($("#clientRF-"+{{$c}}+"-0").val())+parseFloat($("#clientRF-"+{{$c}}+"-1").val())+parseFloat($("#clientRF-"+{{$c}}+"-2").val());
							$("#clientRF-"+{{$c}}+"-3").val(value);
						}else if ({{$m}} == 4 || {{$m}} == 5 || {{$m}} == 6 ) {
							var value = parseFloat($("#clientRF-"+{{$c}}+"-4").val())+parseFloat($("#clientRF-"+{{$c}}+"-5").val())+parseFloat($("#clientRF-"+{{$c}}+"-6").val());
							$("#clientRF-"+{{$c}}+"-7").val(value);
						}else if ({{$m}} == 8 || {{$m}} == 9 || {{$m}} == 10 ) {
							var value = parseFloat($("#clientRF-"+{{$c}}+"-8").val())+parseFloat($("#clientRF-"+{{$c}}+"-9").val())+parseFloat($("#clientRF-"+{{$c}}+"-10").val());
							$("#clientRF-"+{{$c}}+"-11").val(value);
						}else if ({{$m}} == 12 || {{$m}} == 13 || {{$m}} == 14 ) {
							var value = parseFloat($("#clientRF-"+{{$c}}+"-12").val())+parseFloat($("#clientRF-"+{{$c}}+"-13").val())+parseFloat($("#clientRF-"+{{$c}}+"-14").val());
							$("#clientRF-"+{{$c}}+"-15").val(value);
						}

						var Temp = parseFloat($("#clientRF-"+{{$c}}+"-3").val()) + parseFloat($("#clientRF-"+{{$c}}+"-7").val()) + parseFloat($("#clientRF-"+{{$c}}+"-11").val()) + parseFloat($("#clientRF-"+{{$c}}+"-15").val());

						$("#totalClient-"+{{$c}}).val(Temp);

					@endfor
									
				});


				@for($c=0;$c<10;$c++)
					$("#clientRF-"+{{$c}}+"-"+{{$m}}).change(function(){

						if ($(this).val() == '') {
							$(this).val(parseFloat(0));
						}

						if ({{$m}} == 0 || {{$m}} == 1 || {{$m}} == 2 ) {
							var value = parseFloat($("#clientRF-"+{{$c}}+"-0").val())+parseFloat($("#clientRF-"+{{$c}}+"-1").val())+parseFloat($("#clientRF-"+{{$c}}+"-2").val());
							$("#clientRF-"+{{$c}}+"-3").val(value);
						}else if ({{$m}} == 4 || {{$m}} == 5 || {{$m}} == 6 ) {
							var value = parseFloat($("#clientRF-"+{{$c}}+"-4").val())+parseFloat($("#clientRF-"+{{$c}}+"-5").val())+parseFloat($("#clientRF-"+{{$c}}+"-6").val());
							$("#clientRF-"+{{$c}}+"-7").val(value);
						}else if ({{$m}} == 8 || {{$m}} == 9 || {{$m}} == 10 ) {
							var value = parseFloat($("#clientRF-"+{{$c}}+"-8").val())+parseFloat($("#clientRF-"+{{$c}}+"-9").val())+parseFloat($("#clientRF-"+{{$c}}+"-10").val());
							$("#clientRF-"+{{$c}}+"-11").val(value);
						}else if ({{$m}} == 12 || {{$m}} == 13 || {{$m}} == 14 ) {
							var value = parseFloat($("#clientRF-"+{{$c}}+"-12").val())+parseFloat($("#clientRF-"+{{$c}}+"-13").val())+parseFloat($("#clientRF-"+{{$c}}+"-14").val());
							$("#clientRF-"+{{$c}}+"-15").val(value);
						}

						var Temp = parseFloat($("#clientRF-"+{{$c}}+"-3").val()) + parseFloat($("#clientRF-"+{{$c}}+"-7").val()) + parseFloat($("#clientRF-"+{{$c}}+"-11").val()) + parseFloat($("#clientRF-"+{{$c}}+"-15").val());

						$("#totalClient-"+{{$c}}).val(Temp);

						var month = parseFloat(0);

						@for($c2=0;$c2<10;$c2++)
							month += parseFloat($("#clientRF-"+{{$c2}}+"-"+{{$m}}).val());
						@endfor

						$("#rf-"+{{$m}}).val(month);

						if ({{$m}} == 0 || {{$m}} == 1 || {{$m}} == 2 ) {
							var month = parseFloat(0);
							@for($c2=0;$c2<10;$c2++)
								month += parseFloat($("#clientRF-"+{{$c2}}+"-3").val());
							@endfor
							$("#rf-3").val(month);
						
						}else if ({{$m}} == 4 || {{$m}} == 5 || {{$m}} == 6 ) {
							var month = parseFloat(0);
							@for($c2=0;$c2<10;$c2++)
								month += parseFloat($("#clientRF-"+{{$c2}}+"-7").val());
							@endfor
							$("#rf-7").val(month);
						
						}else if ({{$m}} == 8 || {{$m}} == 9 || {{$m}} == 10 ) {
							var month = parseFloat(0);
							@for($c2=0;$c2<10;$c2++)
								month += parseFloat($("#clientRF-"+{{$c2}}+"-11").val());
							@endfor
							$("#rf-11").val(month);
						
						}else if ({{$m}} == 12 || {{$m}} == 13 || {{$m}} == 14 ) {
							var month = parseFloat(0);
							@for($c2=0;$c2<10;$c2++)
								month += parseFloat($("#clientRF-"+{{$c2}}+"-15").val());
							@endfor
							$("#rf-15").val(month);
						}

						var total = parseFloat($("#rf-3").val()) + parseFloat($("#rf-7").val()) + parseFloat($("#rf-11").val()) + parseFloat($("#rf-15").val());

						$("#total-total").val(total);

					});
				@endfor
			@endfor

			@for($c=0;$c<10;$c++)
				$("#client-"+{{$c}}).click(function(){
					if ($("#input-"+{{$c}}+"-0").css("display")=='none') {
						var display = 'block';
						var size = '3500px';
						var width = '2.5%';
						var width2 = '6.5%';
					}else{
						var display = 'none';
						var size = '';
						var width = '4.5%';
						var width2 = '12%';
					}

					$("#quarter-"+{{$c}}+"-3").css("width",width);
					$("#quarter-"+{{$c}}+"-7").css("width",width);
					$("#quarter-"+{{$c}}+"-11").css("width",width);
					$("#quarter-"+{{$c}}+"-15").css("width",width);
					$("#totalC-"+{{$c}}).css("width",width);
					$("#client-"+{{$c}}).css("width",width2);

					$("#table-"+{{$c}}).css("min-width",size);

					@for($m=0;$m<16;$m++)
						$("#input-"+{{$c}}+"-"+{{$m}}).css("display",display);
					@endfor

					if ($("#inputT-0").css("display")=='none') {
						var displayT = 'block';
					}else{
						var displayT = 'none';
						for(var c2=0;c2<10;c2++){
							if ($("#input-"+c2+"-0").css("display") == 'block') {
								displayT = 'block';
								break;
							}
						}
					}

					@for($m=0;$m<16;$m++)
						$("#inputT-"+{{$m}}).css("display",displayT);
					@endfor

					if ($("#newCol-"+{{$c}}+"-0").css("display") == 'none') {
						var displayC = "";
						var number = 2;
						var border = "1px 1px 0px 1px";
						var width3 = '6.5%';
					}else{
						var displayC = 'none';
						var number = 1;
						var border = "1px 0px 0px 0px";
						var width3 = '4.5%';
					}

					$("#newLine-"+{{$c}}).css("display",displayC);
					$("#client-"+{{$c}}).attr("rowspan",number);
					$("#totalC-"+{{$c}}).attr("rowspan",number);
					$("#quarter-"+{{$c}}+"-3").attr("rowspan",number);
					$("#quarter-"+{{$c}}+"-7").attr("rowspan",number);
					$("#quarter-"+{{$c}}+"-11").attr("rowspan",number);
					$("#quarter-"+{{$c}}+"-15").attr("rowspan",number);
					@for($m=0;$m<16;$m++)
						$("#newCol-"+{{$c}}+"-"+{{$m}}).css("display",displayC);
						$("#month-"+{{$c}}+"-"+{{$m}}).attr("colspan",number);
						$("#month-"+{{$c}}+"-"+{{$m}}).css("border-width",border);
						$("#month-"+{{$c}}+"-"+{{$m}}).css("width",width3);
					@endfor
				});
				@for($m=0;$m<16;$m++)
					$("#inputNumber-"+{{$c}}+"-"+{{$m}}).change(function(){
						var temp = 0;

						@for($c2=0;$c2<10;$c2++)
							temp += parseFloat($("#inputNumber-"+{{$c2}}+"-"+{{$m}}).val());
						@endfor

						temp = temp.toFixed(2);

						if(temp != '100.00'){
							alert("The sum of {{$month[$m]}} is "+temp);
						}
						
						$("#inputTNumber-"+{{$m}}).val(temp);

						var temp2 = $("#rf-"+{{$m}}).val()*parseFloat($(this).val()/100);

						$("#clientRF-"+{{$c}}+"-"+{{$m}}).val(temp2);

						if ({{$m}} == 0 || {{$m}} == 1 || {{$m}} == 2 ) {
							var value = parseFloat($("#clientRF-"+{{$c}}+"-0").val())+parseFloat($("#clientRF-"+{{$c}}+"-1").val())+parseFloat($("#clientRF-"+{{$c}}+"-2").val());
							$("#clientRF-"+{{$c}}+"-3").val(value);
						}else if ({{$m}} == 4 || {{$m}} == 5 || {{$m}} == 6 ) {
							var value = parseFloat($("#clientRF-"+{{$c}}+"-4").val())+parseFloat($("#clientRF-"+{{$c}}+"-5").val())+parseFloat($("#clientRF-"+{{$c}}+"-6").val());
							$("#clientRF-"+{{$c}}+"-7").val(value);
						}else if ({{$m}} == 8 || {{$m}} == 9 || {{$m}} == 10 ) {
							var value = parseFloat($("#clientRF-"+{{$c}}+"-8").val())+parseFloat($("#clientRF-"+{{$c}}+"-9").val())+parseFloat($("#clientRF-"+{{$c}}+"-10").val());
							$("#clientRF-"+{{$c}}+"-11").val(value);
						}else if ({{$m}} == 12 || {{$m}} == 13 || {{$m}} == 14 ) {
							var value = parseFloat($("#clientRF-"+{{$c}}+"-12").val())+parseFloat($("#clientRF-"+{{$c}}+"-13").val())+parseFloat($("#clientRF-"+{{$c}}+"-14").val());
							$("#clientRF-"+{{$c}}+"-15").val(value);
						}

						var Temp = parseFloat($("#clientRF-"+{{$c}}+"-3").val()) + parseFloat($("#clientRF-"+{{$c}}+"-7").val()) + parseFloat($("#clientRF-"+{{$c}}+"-11").val()) + parseFloat($("#clientRF-"+{{$c}}+"-15").val());

						$("#totalClient-"+{{$c}}).val(Temp);

					});
				@endfor
			@endfor
		});
	</script>

@endsection