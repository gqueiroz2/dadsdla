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
			<div class="col-sm" style="width: 100%;">
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

						$(this).val(handleNumber($(this).val()));

						if ({{$m}} == 0 || {{$m}} == 1 || {{$m}} == 2) {
							var value = parseFloat($("#fa-"+{{$b}}+"-0").val())+parseFloat($("#fa-"+{{$b}}+"-1").val())+parseFloat($("#fa-"+{{$b}}+"-2").val());
							$("#fa-"+{{$b}}+"-3").val(value);
						}else if({{$m}} == 4 || {{$m}} == 5 || {{$m}} == 6){
							var value = parseFloat($("#fa-"+{{$b}}+"-4").val())+parseFloat($("#fa-"+{{$b}}+"-5").val())+parseFloat($("#fa-"+{{$b}}+"-6").val());
							$("#fa-"+{{$b}}+"-7").val(value);
						}else if({{$m}} == 8 || {{$m}} == 9 || {{$m}} == 10){
							var value = parseFloat($("#fa-"+{{$b}}+"-8").val())+parseFloat($("#fa-"+{{$b}}+"-9").val())+parseFloat($("#fa-"+{{$b}}+"-10").val());
							$("#fa-"+{{$b}}+"-11").val(value);
						}else if({{$m}} == 12 || {{$m}} == 13 || {{$m}} == 14){
							var value = parseFloat($("#fa-"+{{$b}}+"-12").val())+parseFloat($("#fa-"+{{$b}}+"-13").val())+parseFloat($("#fa-"+{{$b}}+"-14").val());
							$("#fa-"+{{$b}}+"-15").val(value);
						}

						var valueTotal = parseFloat($("#fa-"+{{$b}}+"-3").val()) + parseFloat($("#fa-"+{{$b}}+"-7").val()) + parseFloat($("#fa-"+{{$b}}+"-11").val()) + parseFloat($("#fa-"+{{$b}}+"-15").val());

						$("#total-"+{{$b}}).val(valueTotal);
					});
				@endfor
			@endfor
		});

		function handleNumber(number){

			number = number.replace(",","");
			
			return number;
		}

		function viewNumber(number){

		}
	</script>


@endsection