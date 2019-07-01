@extends('layouts.mirror')
@section('title', 'AE Report')
@section('head')	
    <?php include(resource_path('views/auth.php')); ?>
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
				

					@for($c=0;$c<10;$c++)
						var temp = $(this).val()*(parseFloat($("#oldCY-"+{{$c}}+"-"+{{$m}}).val())/parseFloat($("#oldY-"+{{$m}}).val()));

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
		});
	</script>

@endsection