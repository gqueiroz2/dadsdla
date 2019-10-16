@extends('layouts.mirror')
@section('title', 'VP Month Report')
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
    </style>
@endsection
@section('content')
	<div class="container-fluid">
		<div class="row justify-content-end mt-2">
			<div class="col-3" style="color: #0070c0;font-size: 25px;">
				VP Month Report
			</div>
		</div>
	</div>

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
	<br>

	@if(!$forRender)
		<div class="col" style="width: 100%; padding-right: 2%;">
			<div style="min-height: 100px;" class="alert alert-warning" role="alert">
				<span style="font-size:22px;">
					<center>
					There is no submissions of Forecast from AE yet!
					</center>
				</span>
			</div>
		</div>
    @else
    	<div class="container-fluid">
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

		<script type="text/javascript">
			var aux = <?php echo json_encode($aux); ?>;
			$(document).ready(function(){

				<?php 
					$aux = array('Jan','Feb','Mar','Q1','Apr','May','Jun','Q2','Jul','Aug','Sep','Q3','Oct','Nov','Dec','Q4');
				?>

				$("input[type=radio][name=options]").change(function(){
					if (this.value == 'save') {
						$("#button").val("Save");
					}else{
						$("#button").val("Submit");
					}
				});

				for(var m=0; m < aux.length; m++){
					$("#me-"+m).change(function(){
						if ($(this).val() ==  '') {
							$this.val(0);
						}

						$(this).val(Comma(handleNumber($(this).val())));
						if (m == 0 || m == 1 || m == 2) {
							var value = Comma(handleNumber($("#me-0").val())+handleNumber($("#me-1").val())+handleNumber($("#me-2").val()));
							$("#me-3").val(value);
						}else if (m == 4 || m == 5 || m == 6 ) {
							var value = Comma(handleNumber($("#me-4").val())+handleNumber($("#me-5").val())+handleNumber($("#me-5").val()));
							$("#me-7").val(value);
						}else if (m == 8 || m == 9 || m == 10 ) {
							var value = Comma(handleNumber($("#me-8").val())+handleNumber($("#me-9").val())+handleNumber($("#me-10").val()));
							$("#me-11").val(value);
						}else if (m == 12 || m == 13 || m == 14 ) {
							var value = Comma(handleNumber($("#me-12").val())+handleNumber($("#me-13").val())+handleNumber($("#me-14").val()));
							$("#me-15").val(value);
						}

						var total = Comma(handleNumber($("#me-3").val()) + handleNumber($("#me-7").val()) + handleNumber($("#me-11").val()) + handleNumber($("#me-15").val()));
						
						$("#total-manualEstimationTotal").val(total);
					});
				}
			});

			//function to add commas to textboxes
			function Comma(Num) {
	        Num += '';
	        Num = Num.replace(',', ''); Num = Num.replace(',', ''); Num = Num.replace(',', '');
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

	    function handleNumber(number){
			for (var i = 0; i < number.length; i++) {
				number = number.replace(",","");
			}
			number = parseFloat(number);
			return number;
		}

		</script>

    @endif

@endsection