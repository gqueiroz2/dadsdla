@extends('layouts.mirror')

@section('title', 'Monthly Results')

@section('head')	

@endsection

@section('content')
	<form class="form-inline" role="form" method="POST" action="{{ route('monthlyResultsPost') }}">
	@csrf
		<div class="container-fluid">
			<div class="row">
				<div class="col-12 col-lg">
					<div class="form-inline">
						<label> Sales Region </label>
						<select name="salesRegion" id="salesRegion" class="form-control" style="width: 100%;">
							<option value=""> Select </option>
							@for($s = 0;$s < sizeof($salesRegion);$s++)
								<option value="{{$salesRegion[$s]}}"> {{$salesRegion[$s]}} </option>
							@endfor
						</select>
					</div>
				</div>
				<div class="col-12 col-lg">
					<div class="form-inline">
						<label> Year </label>
						<select name="year" id="year" class="form-control" style="width: 100%;">
							<option value=""> Select </option>
							@for($y = 0;$y < sizeof($years);$y++)
								<option value="{{$years[$y]}}"> {{$years[$y]}} </option>
							@endfor
						</select>
					</div>
				</div><div id="vlau"></div>
				<div class="col-12 col-lg">
					<div class="form-inline">
						<label> Brand </label>
						<select name="brand[]" id="brand" class="form-control" multiple="true" style="width: 100%;">
							<?php
								for ($f=0; $f < sizeof($brand); $f++) { 
									echo "<option value='".$brand[$f]."'>".$brand[$f]."</option>";
								}
							?>
						</select>
					</div>
				</div>
				<div class="col-12 col-lg">
					<div class="form-inline">
						<label> 1st Pos </label>
						<select name="firstPos" id="firstPos" class="form-control" style="width: 100%;">
							
						</select>
					</div>
				</div>
				<div class="col-12 col-lg">
					<div class="form-inline">
						<label> 2nd Pos </label>
						<select name="secondPos" id="secondPos" class="form-control" style="width: 100%;">
							
						</select>
					</div>
				</div>
				<div class="col-12 col-lg">
					<div class="form-inline">
						<label> Currency </label>
						<select name="currency" id="currency" class="form-control" style="width: 100%;">
							
						</select>
					</div>
				</div>
				<div class="col-12 col-lg">
					<div class="form-inline">
						<label> Value </label>
						<select name="value" id="value" class="form-control" style="width: 100%;">							
							<option value="gross"> Gross </option>
							<option value="net"> Net </option>
						</select>
					</div>
				</div>
				<div class="col-12 col-lg">
					<div class="form-inline">
						<label> &nbsp; </label>
						<input type="submit" value="Generate" class="btn btn-primary" style="width: 100%;">
					</div>
				</div>
			</div>
		</div>		
	</form>

	<script>
    	$(document).ready(function(){      
      		$('#year').click(function(){
        		var year = $(this).val();                		
        		console.log(year);
        		if(year != ""){        			
        			/*
						SETUP THE AJAX FOR ALL CALLS
        			*/
        			$.ajaxSetup({
            			headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            			type:"POST"
          			});
        			/*
        				GET THE TARGET/FCST'S BY YEAR
					*/
          			$.ajax({
            			url:"/ajax/adsales/firstPosMonthly",
            			method:"POST",
            			data:{year},
	              		success: function(output){
	                		$('#firstPos').html(output);                		
	              		},
	              		error: function(xhr, ajaxOptions,thrownError){
	                		alert(xhr.status+""+thrownError);
	          			}
	          		});
          			/*
        				GET THE REVENUE BY YEAR
					*/
          			$.ajax({
            			url:"/ajax/adsales/secondPosMonthly",
            			method:"POST",
            			data:{year},
	              		success: function(output){
	                		$('#secondPos').html(output);                		
	              		},
	              		error: function(xhr, ajaxOptions,thrownError){
	                		alert(xhr.status+""+thrownError);
          				}
          			});          			
        		}
      		});
      		$('#salesRegion').click(function(){
        		var salesRegion = $(this).val();                		
        		console.log(salesRegion);
        		if(salesRegion != ""){        			
        			/*
						SETUP THE AJAX FOR ALL CALLS
        			*/
        			$.ajaxSetup({
            			headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            			type:"POST"
          			});
      				/*
        				GET THE CURRENCY BY REGION
					*/
					$.ajax({
            			url:"/ajax/adsales/currencyByRegion",
            			method:"POST",
            			data:{salesRegion},
	              		success: function(output){
	                		$('#currency').html(output);                		
	              		},
	              		error: function(xhr, ajaxOptions,thrownError){
	                		alert(xhr.status+""+thrownError);
	          			}
          			});
          		}
    		});
    	});
  </script>

@endsection