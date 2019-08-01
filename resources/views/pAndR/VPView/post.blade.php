@extends('layouts.mirror')
@section('title', 'VP Report')
@section('head')	
    <?php include(resource_path('views/auth.php')); ?>
    <script src="/js/pandr.js"></script>
    <style>
    	
    </style>
@endsection
@section('content')
	<div class="container-fluid">
		<div class="row justify-content-end mt-2">
			<div class="col-3" style="color: #0070c0;font-size: 25px;">
				VP Report
			</div>
		</div>
	</div>

	<form method="POST" action="{{ route('VPPost') }}" runat="server"  onsubmit="ShowLoading()">
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
					{{$render->VP1()}}
				</center>
			</div>
		</div>
	</div>

	<script>
		$(document).ready(function(){
			@for($b=0;$b<12;$b++)
				@for($m=0;$m<16;$m++)
					$("#fa-"+{{$b}}+"-"+{{$m}}).change(function(){
						if ($(this).val() == '') {
							$(this).val(0);
						}

						$(this).val(Comma(handleNumber($(this).val())));


						if ({{$m}} == 0 || {{$m}} == 1 || {{$m}} == 2) {
							var value = Comma(handleNumber($("#fa-"+{{$b}}+"-0").val())+handleNumber($("#fa-"+{{$b}}+"-1").val())+handleNumber($("#fa-"+{{$b}}+"-2").val()));
							$("#fa-"+{{$b}}+"-3").val(value);
						}else if({{$m}} == 4 || {{$m}} == 5 || {{$m}} == 6){
							var value = Comma(handleNumber($("#fa-"+{{$b}}+"-4").val())+handleNumber($("#fa-"+{{$b}}+"-5").val())+handleNumber($("#fa-"+{{$b}}+"-6").val()));
							$("#fa-"+{{$b}}+"-7").val(value);
						}else if({{$m}} == 8 || {{$m}} == 9 || {{$m}} == 10){
							var value = Comma(handleNumber($("#fa-"+{{$b}}+"-8").val())+handleNumber($("#fa-"+{{$b}}+"-9").val())+handleNumber($("#fa-"+{{$b}}+"-10").val()));
							$("#fa-"+{{$b}}+"-11").val(value);
						}else if({{$m}} == 12 || {{$m}} == 13 || {{$m}} == 14){
							var value = Comma(handleNumber($("#fa-"+{{$b}}+"-12").val())+handleNumber($("#fa-"+{{$b}}+"-13").val())+handleNumber($("#fa-"+{{$b}}+"-14").val()));
							$("#fa-"+{{$b}}+"-15").val(value);
						}

						var valueTotal = Comma(handleNumber($("#fa-"+{{$b}}+"-3").val()) + handleNumber($("#fa-"+{{$b}}+"-7").val()) + handleNumber($("#fa-"+{{$b}}+"-11").val()) + handleNumber($("#fa-"+{{$b}}+"-15").val()));

						$("#total-"+{{$b}}).val(valueTotal);
					});
				@endfor
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