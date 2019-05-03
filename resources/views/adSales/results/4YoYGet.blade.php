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

	<div id="vlau"></div>

	<script type="text/javascript">

		$(document).ready(function(){

			$('#region').change(function(){

				var region = $(this).val();

				if (region != "") {
					
					ajaxSetup();

					$.ajax({
            			url:"/ajax/adsales/currencyByRegion",
            			method:"POST",
            			data:{region:regionID},
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