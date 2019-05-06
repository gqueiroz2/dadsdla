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
		
		<br>
		
		<div class="row no-gutters">
			<div class="col-9"></div>
			<div class="col-3" style="color: #0070c0;font-size: 25px">
				Year Over Year ({{$form}}) {{$year}}
			</div>
		</div>

		<br>

		<div class="row no-gutters">
			<div class="col-9"></div>
			<div class="col-3" style="color: #0070c0;font-size: 25px">
				<form class="form-inline" method="POST" action="#">
					@csrf
					 <button class="btn btn-primary" style="width: 100%">Generate Excel</button>
				</form>
			</div>
		</div>

	</div>

	<div class="container-fluid" style="margin-right: 0.5%; margin-left: 0.5%; font-size: 12px">
		<div class="row">
			<div class="col">
				<br>
				<table style="width: 100%">
					<tbody>
						<tr>
							<td class="lightBlue center" colspan="30">
								<span style="font-size:18px;"> 
									Year Over Year :({{$form}}) {{$year}} ({{($value == "gross") ? "Gross" : "Net"}}
									/{{strtoupper($currency)}})
								</span>
							</td>
						</tr>
						<tr><td>&nbsp;</td></tr>

						<?php $size = sizeof($brandsValueArray) ?>

						@for($i = 0; $i < $size; $i++)
							
							<tr>{{$renderYoY->brandTable($brandsValueArray[$i], $brandsValueArray[$i])}}</tr>
                            <tr>
                                {{$renderYoY->renderData($matrix[$i][0],1,"grey","darkBlue")}}
                            </tr>
                            <tr>
                                {{$renderYoY->renderData($matrix[$i][1],2,
                                "lightb","othersc","smBlue")}}
                            </tr>
                            <tr>
                                {{$renderYoY->renderData($matrix[$i][2],3,
                                "rcBlue","othersc","smBlue")}}
                            </tr>
                            <tr>
                                {{$renderYoY->renderData($matrix[$i][3],4,"rcBlue","smBlue")}}
                            </tr>
                            <tr>
                                {{$renderYoY->renderData($matrix[$i][4],5,"medBlue","smBlue")}}
                            </tr>
                            <tr>
                                {{$renderYoY->renderData($matrix[$i][5],6,"medBlue","darkBlue")}}
                            </tr>

                            @if($i != $size-1)
                                <tr><td>&nbsp;</td></tr>
                            @endif

						@endfor
					</tbody>
				</table>
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