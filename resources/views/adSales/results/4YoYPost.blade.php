@extends('layouts.mirror')

@section('title', 'YoY Results')

@section('head')	

@endsection

@section('content')

	<div class="container-fluid">
		<div class="row">
			<div class="col">
				<form class="form-inline" role="form" method="POST" action="{{ route('YoYResultsPost') }}">
				@csrf
					<!-- Region Area -->
					<div class="col-12 col-lg">
						<div class="form-inline">
							<label>Sales Region</label>
							{{ $render->region($salesRegion) }}
						</div>
					</div>

					<!-- Year Area -->
					<div class="col-12 col-lg">
						<div class="form-inline">
							<label>Year</label>
							{{ $render->year() }}
						</div>
					</div>

					<!-- Brand Area -->
					<div class="col-12 col-lg">
						<div class="form-inline">
							<label>Brand</label>
							{{ $render->brand($brandsValue) }}
						</div>
					</div>	

					<!-- 1st Pos Area -->
					<div class="col-12 col-lg">
						<div class="form-inline">
							<label> 1st Pos </label>
							<select id="firstPos" name="firstPos" style="width: 100%;">
								<option id="option1" value=''> Select </option>
							</select>
						</div>
					</div>	

					<!-- 2st Pos Area -->
					<div class="col-12 col-lg">
						<div class="form-inline">
							<label> 2st Pos </label>
							<select id="secondPos" name="secondPos" style="width: 100%;">
								<option value=""> Select </option>
							</select> 
						</div>
					</div>	

					<!-- 3st Pos Area -->
					<div class="col-12 col-lg">
						<div class="form-inline">
							<label> 3st Pos </label>
							<select id="thirdPos" name="thirdPos" style="width: 100%;">
								<option value=''> All Selected </option>
							</select>
						</div>
					</div>	

					<!-- Currency Area -->
					<div class="col-12 col-lg">
						<div class="form-inline">
							<label> Currency </label>
							<select id="currency" name="currency" style="width: 100%;">
								<option value=""> Select </option>
							</select>
						</div>
					</div>	

					<!-- Value Area -->
					<div class="col-12 col-lg">
						<div class="form-inline">
							<label> Value </label>
							{{ $render->value() }}
						</div>
					</div>

					<div class="col-12 col-lg">
						<div class="form-inline">
							<label> &nbsp; </label>
							<input type="submit" value="Generate" class="btn btn-primary" style="width: 100%">		
						</div>
					</div>

				</form>
			</div>
		</div>
	</div>

	<div class="container-fluid" style="margin-right: 0.5%; margin-left: 0.5%; font-size: 12px">
		<div class="row">
			<div class="col">
				<table class="table table-bordered" style="width: 100%">
					<tr>
						<td class="darkBlue center" colspan="15">
							<span style="font-size:18px;"> 
								Year Over Year :({{$form}}) {{$year}} ({{$value}}/{{strtoupper($currency)}})
							</span>
						</td>
					</tr>
				</table>
				<br>
				@for($i = 0; $i < sizeof($brandsValue); $i++)
					<table class="table table-bordered table-striped table-sm" style="width: 100%;">
						<tr>
							<td class="darkBlue" rowspan="7">
								<span style="font-size: 18px">
									{{ $brandsValueArray[$i] }}
								</span>
							</td>
						</tr>
						<tr>{{$renderYoY->renderDataHead($matrix[0]['month'])}}</tr>
						<tr>{{$renderYoY->renderDataBody($matrix[0]['valuePastYear'])}}</tr>
						<tr>{{$renderYoY->renderDataBody($matrix[0]['target'])}}</tr>
						<tr>{{$renderYoY->renderDataBody($matrix[0]['valueCurrentYear'])}}</tr>
						<tr>{{$renderYoY->renderDataBody($matrix[0]['difExpected'])}}</tr>
						<tr>{{$renderYoY->renderDataBody($matrix[0]['difYoY'])}}</tr>
					</table>
				@endfor
			</div>
		</div>
	</div>

	<div id="vlau"></div>

	<script type="text/javascript">

		$(document).ready(function(){

			$('#region').change(function(){

				var regionID = $(this).val();

				if (regionID != "") {
					
					ajaxSetup();

					$.ajax({
            			url:"/ajax/adsales/currencyByRegion",
            			method:"POST",
            			data:{regionID},
	              		success: function(output){
	                		$('#currency').html(output);
	              		},
	              		error: function(xhr, ajaxOptions,thrownError){
	                		alert(xhr.status+" "+thrownError);
	          			}
	          		});

				}

			});

			$('#year').click(function(){
				
				var year = $(this).val();

				if (year != "") {
					var regionID = $('#region').val();

					if (regionID != "") {

						ajaxSetup();

						$.ajax({
	            			url:"/ajax/adsales/thirdPosByRegion",
	            			method:"POST",
	            			data:{regionID, year},
		              		success: function(output){
		                		$('#thirdPos').html(output);
		                		$('#option1').html("");
		              		},
		              		error: function(xhr, ajaxOptions,thrownError){
		                		alert(xhr.status+" "+thrownError);
		          			}
		          		});

		          		$.ajax({
	            			url:"/ajax/adsales/secondPosByRegion",
	            			method:"POST",
	            			data:{year},
		              		success: function(output){
		                		$('#secondPos').html(output);
		              		},
		              		error: function(xhr, ajaxOptions,thrownError){
		                		alert(xhr.status+" "+thrownError);
		          			}
		          		});
					}
				}
			});

			$('#thirdPos').click(function(){

				var year = $('#year').val();
				var form = $(this).val();

				if (year != "" || form != "") {

					ajaxSetup();

					$.ajax({
						url:"/ajax/adsales/firstPosByRegion",
						method:"POST",
						data:{form, year},
						success: function(output){
	                		$('#firstPos').html(output);
	              		},
	              		error: function(xhr, ajaxOptions,thrownError){
	                		alert(xhr.status+" "+thrownError);
	          			}
					});
				}

			});
		});
	</script>

@endsection