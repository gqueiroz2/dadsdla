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
		<div class="row">
			<div class="col" style="width: 100%;">
				<center>
					{{$render->AE1($forRender,$client,$total2018,$totaltotal2018,$totalClient2018,$client2018,$tfArray)}}
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
					
					$(this).val(Comma(handleNumber($(this).val())));

					if ({{$m}} == 0 || {{$m}} == 1 || {{$m}} == 2 ) {
						var value = Comma(handleNumber($("#rf-0").val())+handleNumber($("#rf-1").val())+handleNumber($("#rf-2").val()));
						$("#rf-3").val(value);
					}else if ({{$m}} == 4 || {{$m}} == 5 || {{$m}} == 6 ) {
						var value = Comma(handleNumber($("#rf-4").val())+handleNumber($("#rf-5").val())+handleNumber($("#rf-6").val()));
						$("#rf-7").val(value);
					}else if ({{$m}} == 8 || {{$m}} == 9 || {{$m}} == 10 ) {
						var value = Comma(handleNumber($("#rf-8").val())+handleNumber($("#rf-9").val())+handleNumber($("#rf-10").val()));
						$("#rf-11").val(value);
					}else if ({{$m}} == 12 || {{$m}} == 13 || {{$m}} == 14 ) {
						var value = Comma(handleNumber($("#rf-12").val())+handleNumber($("#rf-13").val())+handleNumber($("#rf-14").val()));
						$("#rf-15").val(value);
					}
				
					var Temp = Comma(handleNumber($("#rf-3").val()) + handleNumber($("#rf-7").val()) + handleNumber($("#rf-11").val()) + handleNumber($("#rf-15").val()));

					$("#total-total").val(Temp);


					@for($c=0;$c<10;$c++)
						var temp = Comma(handleNumber($(this).val())*parseFloat($("#totalPP2-"+{{$c}}).val()/100));


						$("#clientRF-"+{{$c}}+"-"+{{$m}}).val(temp);
						
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
						
						var Temp = Comma(handleNumber($("#clientRF-"+{{$c}}+"-3").val()) + handleNumber($("#clientRF-"+{{$c}}+"-7").val()) + handleNumber($("#clientRF-"+{{$c}}+"-11").val()) + handleNumber($("#clientRF-"+{{$c}}+"-15").val()));

						$("#totalClient-"+{{$c}}).val(Temp);

						@for($m2=0;$m2<16;$m2++)
							var temp2 = handleNumber($("#clientRF-"+{{$c}}+"-"+{{$m2}}).val())/handleNumber($("#totalClient-"+{{$c}}).val());
							temp2 = temp2*100;
							$("#inputNumber-"+{{$c}}+"-"+{{$m2}}).val(temp2);
						@endfor

					@endfor
					
							
				});


				@for($c=0;$c<10;$c++)
					$("#clientRF-"+{{$c}}+"-"+{{$m}}).change(function(){

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

						var Temp = Comma(handleNumber($("#clientRF-"+{{$c}}+"-3").val()) + handleNumber($("#clientRF-"+{{$c}}+"-7").val()) + handleNumber($("#clientRF-"+{{$c}}+"-11").val()) + handleNumber($("#clientRF-"+{{$c}}+"-15").val()));

						$("#totalClient-"+{{$c}}).val(Temp);

						Temp3 = handleNumber(Temp);

						var tmp2 = 0;


						@for($m2=0;$m2<16;$m2++)
							var temp2 = handleNumber($("#clientRF-"+{{$c}}+"-"+{{$m2}}).val())/handleNumber($("#totalClient-"+{{$c}}).val());
							temp2 = Comma(temp2*100);
							$("#inputNumber-"+{{$c}}+"-"+{{$m2}}).val(temp2);
							
							if({{$m2}} != 3 && {{$m2}} != 7 && {{$m2}} != 11 && {{$m2}} != 15){
								tmp2 += handleNumber($("#inputNumber-"+{{$c}}+"-"+{{$m2}}).val());
							}

						@endfor

						tmp2 = tmp2.toFixed(2);

						if (Temp3 != handleNumber($("#passTotal-"+{{$c}}).val()) || tmp2 != '100.00') {
							$("#client-"+{{$c}}).css("background-color","red");
						}else{
							$("#client-"+{{$c}}).css("background-color","");
						}

						var month = 0;

						@for($c2=0;$c2<10;$c2++)
							month += handleNumber($("#clientRF-"+{{$c2}}+"-"+{{$m}}).val());
						@endfor

						month = Comma(month);

						$("#rf-"+{{$m}}).val(month);

						if ({{$m}} == 0 || {{$m}} == 1 || {{$m}} == 2 ) {
							var month = handleNumber(0);
							@for($c2=0;$c2<10;$c2++)
								month += handleNumber($("#clientRF-"+{{$c2}}+"-3").val());
							@endfor
							month = Comma(month);
							$("#rf-3").val(month);
						
						}else if ({{$m}} == 4 || {{$m}} == 5 || {{$m}} == 6 ) {
							var month = handleNumber(0);
							@for($c2=0;$c2<10;$c2++)
								month += handleNumber($("#clientRF-"+{{$c2}}+"-7").val());
							@endfor
							month = Comma(month);
							$("#rf-7").val(month);
						
						}else if ({{$m}} == 8 || {{$m}} == 9 || {{$m}} == 10 ) {
							var month = handleNumber(0);
							@for($c2=0;$c2<10;$c2++)
								month += handleNumber($("#clientRF-"+{{$c2}}+"-11").val());
							@endfor
							month = Comma(month);
							$("#rf-11").val(month);
						
						}else if ({{$m}} == 12 || {{$m}} == 13 || {{$m}} == 14 ) {
							var month = handleNumber(0);
							@for($c2=0;$c2<10;$c2++)
								month += handleNumber($("#clientRF-"+{{$c2}}+"-15").val());
							@endfor
							month = Comma(month);
							$("#rf-15").val(month);
						}

						var total = Comma(handleNumber($("#rf-3").val()) + handleNumber($("#rf-7").val()) + handleNumber($("#rf-11").val()) + handleNumber($("#rf-15").val()));

						$("#total-total").val(total);

						@for($c2=0;$c2<10;$c2++)
							var temp = handleNumber($("#totalClient-"+{{$c2}}).val())/handleNumber($("#total-total").val());
							temp = Comma(temp*100);
							$("#totalPP2-"+{{$c2}}).val(temp);
							$("#totalPP3-"+{{$c2}}).val(temp);
						@endfor

					});
				@endfor
			@endfor
			@for($c=0;$c<10;$c++)
				$("#totalPP2-"+{{$c}}).change(function(){
					var top = 0;
					@for($c2=0;$c2<10;$c2++)
						top += handleNumber($("#totalPP2-"+{{$c2}}).val());
					@endfor

					top = Comma(top);

					$("#totalClients").val(top);

					@for($m=0;$m<16;$m++)
						if($("#totalPP3-"+{{$c}}).val() != 0){
							var value = Comma(handleNumber($("#clientRF-"+{{$c}}+"-"+{{$m}}).val())*handleNumber($(this).val())/handleNumber($("#totalPP3-"+{{$c}}).val()));
						}else{
							var value = Comma(handleNumber($("#rf-"+{{$m}}).val())*parseFloat($(this).val()/100));
						}
						$("#clientRF-"+{{$c}}+"-"+{{$m}}).val(value);
					@endfor

					$("#totalPP3-"+{{$c}}).val($(this).val());

					var Temp = Comma(handleNumber($("#clientRF-"+{{$c}}+"-3").val()) + handleNumber($("#clientRF-"+{{$c}}+"-7").val()) + handleNumber($("#clientRF-"+{{$c}}+"-11").val()) + handleNumber($("#clientRF-"+{{$c}}+"-15").val()));

					$("#totalClient-"+{{$c}}).val(Temp);

					
				});

				$("#client-"+{{$c}}).click(function(){
					if ($("#input-"+{{$c}}+"-0").css("display")=='none') {
						var display = 'block';
						var size = '4000px';
						var width = '3.5%';
						var width2 = '6%';
						var displayC = "";
						var number = 2;
						var border = "1px 1px 0px 1px";
						var width3 = '5%';
						var division = 8;
						var width4 = '3%';
					}else{
						var display = 'none';
						var size = '2600px';
						var width = '3.5%';
						var width2 = '8%';
						var displayC = 'none';
						var number = 1;
						var border = "1px 0px 0px 0px";
						var width3 = '3.5%';
						var division = 7;
						var width4 = '3.5%';
					}

					$("#division-"+{{$c}}).attr("rowspan",division);
					$("#sideTable-"+{{$c}}+"-0").attr("rowspan",number);
					$("#sideTable-"+{{$c}}+"-1").attr("rowspan",number);
					$("#sideTable-"+{{$c}}+"-2").attr("rowspan",number);
					$("#sideTable-"+{{$c}}+"-3").attr("rowspan",number);
					$("#sideTable-"+{{$c}}+"-4").attr("rowspan",number);
					$("#sideTable-"+{{$c}}+"-0").css("width",width4);
					$("#sideTable-"+{{$c}}+"-1").css("width",width4);
					$("#sideTable-"+{{$c}}+"-2").css("width",width4);
					$("#sideTable-"+{{$c}}+"-3").css("width",width4);
					$("#sideTable-"+{{$c}}+"-4").css("width",width4);
					$("#quarter-"+{{$c}}+"-3").css("width",width);
					$("#quarter-"+{{$c}}+"-7").css("width",width);
					$("#quarter-"+{{$c}}+"-11").css("width",width);
					$("#quarter-"+{{$c}}+"-15").css("width",width);
					$("#TotalTitle-"+{{$c}}).css("width",width);
					$("#client-"+{{$c}}).css("width",width2);
					$("#table-"+{{$c}}).css("min-width",size);

					@for($m=0;$m<16;$m++)
						$("#input-"+{{$c}}+"-"+{{$m}}).css("display",display);
					@endfor


					if ($("#inputT-0").css("display")=='none') {
						var displayT = '';
					}else{
						var displayT = 'none';
						for(var c2=0;c2<10;c2++){
							if ($("#input-"+c2+"-0").css("display") != 'none') {
								displayT = '';
								break;
							}
						}
					}
					$("#totalTotalPP").css("display",displayT);

					$("#totalPP-"+{{$c}}).css("display",displayC);
					$("#newLine-"+{{$c}}).css("display",displayC);
					$("#client-"+{{$c}}).attr("rowspan",number);
					$("#TotalTitle-"+{{$c}}).attr("rowspan",number);
					$("#quarter-"+{{$c}}+"-3").attr("rowspan",number);
					$("#quarter-"+{{$c}}+"-7").attr("rowspan",number);
					$("#quarter-"+{{$c}}+"-11").attr("rowspan",number);
					$("#quarter-"+{{$c}}+"-15").attr("rowspan",number);
					@for($m=0;$m<16;$m++)
						$("#newCol-"+{{$c}}+"-"+{{$m}}).css("display",displayC);
						$("#month-"+{{$c}}+"-"+{{$m}}).css("border-width",border);
						$("#month-"+{{$c}}+"-"+{{$m}}).css("width",width3);
					@endfor
				});

				@for($m=0;$m<16;$m++)
					$("#inputNumber-"+{{$c}}+"-"+{{$m}}).change(function(){
						var temp = 0;

						var antigo = $(this).val();

						@for($m2=0;$m2<16;$m2++)
							if({{$m2}} != 3 && {{$m2}} != 7 && {{$m2}} != 11 && {{$m2}} != 15){
								temp += handleNumber($("#inputNumber-"+{{$c}}+"-"+{{$m2}}).val());
							}
						@endfor

						temp = temp.toFixed(2);

						var tmp2 = handleNumber($("#clientRF-"+{{$c}}+"-3").val()) + handleNumber($("#clientRF-"+{{$c}}+"-7").val()) + handleNumber($("#clientRF-"+{{$c}}+"-11").val()) + handleNumber($("#clientRF-"+{{$c}}+"-15").val());

						if(temp != '100.00'){
							alert("The sum of client is "+temp);
							$("#client-"+{{$c}}).css("background-color","red");
						}else if(tmp2 != handleNumber($("#passTotal-"+{{$c}}).val())){
							$("#client-"+{{$c}}).css("background-color","red");
						}else{
							$("#client-"+{{$c}}).css("background-color","");
						}
						
					});
				@endfor
				$("#TotalTitle-"+{{$c}}).click(function(){
					@for($m=0;$m<16;$m++)
						if({{$m}} != 3 && {{$m}} != 7 && {{$m}} != 11 && {{$m}} != 15){
							var vlau = Comma(parseFloat($("#inputNumber-"+{{$c}}+"-"+{{$m}}).val())*parseFloat($("#totalTClient-"+{{$c}}).val()/100));
							$("#clientRF-"+{{$c}}+"-"+{{$m}}).val(vlau);
						}

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

						var Temp = Comma(handleNumber($("#clientRF-"+{{$c}}+"-3").val()) + handleNumber($("#clientRF-"+{{$c}}+"-7").val()) + handleNumber($("#clientRF-"+{{$c}}+"-11").val()) + handleNumber($("#clientRF-"+{{$c}}+"-15").val()));

						$("#totalClient-"+{{$c}}).val(Temp);

						var month = handleNumber(0);

						@for($c2=0;$c2<10;$c2++)
							month += handleNumber($("#clientRF-"+{{$c2}}+"-"+{{$m}}).val());
						@endfor

						month = Comma(month);

						$("#rf-"+{{$m}}).val(month);

						if ({{$m}} == 0 || {{$m}} == 1 || {{$m}} == 2 ) {
							var month = handleNumber(0);
							@for($c2=0;$c2<10;$c2++)
								month += handleNumber($("#clientRF-"+{{$c2}}+"-3").val());
							@endfor

							month = Comma(month);

							$("#rf-3").val(month);
						
						}else if ({{$m}} == 4 || {{$m}} == 5 || {{$m}} == 6 ) {
							var month = handleNumber(0);
							@for($c2=0;$c2<10;$c2++)
								month += handleNumber($("#clientRF-"+{{$c2}}+"-7").val());
							@endfor
							
							month = Comma(month);
							
							$("#rf-7").val(month);
						
						}else if ({{$m}} == 8 || {{$m}} == 9 || {{$m}} == 10 ) {
							var month = handleNumber(0);
							@for($c2=0;$c2<10;$c2++)
								month += handleNumber($("#clientRF-"+{{$c2}}+"-11").val());
							@endfor
							
							month = Comma(month);
							
							$("#rf-11").val(month);
						
						}else if ({{$m}} == 12 || {{$m}} == 13 || {{$m}} == 14 ) {
							var month = handleNumber(0);
							@for($c2=0;$c2<10;$c2++)
								month += handleNumber($("#clientRF-"+{{$c2}}+"-15").val());
							@endfor

							month = Comma(month);
							
							$("#rf-15").val(month);
						}

						var total = Comma(handleNumber($("#rf-3").val()) + handleNumber($("#rf-7").val()) + handleNumber($("#rf-11").val()) + handleNumber($("#rf-15").val()));

						$("#total-total").val(total);

						@for($c2=0;$c2<10;$c2++)
							var temp = handleNumber($("#totalClient-"+{{$c2}}).val())/handleNumber($("#total-total").val());
							temp = temp*100;
							$("#totalPP2-"+{{$c2}}).val(temp);
							$("#totalPP3-"+{{$c2}}).val(temp);
						@endfor

					@endfor
				});

			@endfor
		});
		
		function handleNumber(number){

			number = number.replace(",","");

			number = parseFloat(number);
			
			return number;
		}

	  	function Comma(Num) { //function to add commas to textboxes
	        Num += '';
	        Num = Num.replace(',', ''); Num = Num.replace(',', ''); Num = Num.replace(',', '');
	        Num = Num.replace(',', ''); Num = Num.replace(',', ''); Num = Num.replace(',', '');
	        x = Num.split('.');
	        x1 = x[0];
	        x2 = x.length > 1 ? '.' + x[1] : '';
	        var rgx = /(\d+)(\d{3})/;
	        while (rgx.test(x1))
	            x1 = x1.replace(rgx, '$1' + ',' + '$2');
	        return x1 + x2;
	    }

	</script>

@endsection