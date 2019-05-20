@extends('layouts.mirror')

@section('title', '@')

@section('head')
    <?php include(resource_path('views/auth.php')); ?>
@endsection
@section('content')

@if($userLevel == 'SU')
	<div class="container-fluid">
		<div class="row justify-content-center">
			<div class="col-sm-6">
				<div class="card" style="margin-bottom:15%;">
					<div class="card-header">
						<center><h4> Data Management - <b> Sales Rep. </b> </h4></center>
					</div>
					<div class="card-body">
						<div class="container-fluid">
							<div class="row justify-content-center">
								<div class="col align-self-center">
									<h5> Edit / Management Sales Rep. Group </h5>
								</div>
							</div>
							
							<div class="row justify-content-center">
								<div class="col">
									@if($salesRepGroup)
										<form method="POST" action="{{ route('dataManagementSalesRepGroupEditFilter') }}">
											@csrf
											<div class="row justify-content-end mt-1">
												<div class="col">
													<input type="submit" class="btn btn-primary" value="Edit" style="width: 100%;">
												</div>
											</div>
										</form>
									@else
										<div class="alert alert-warning">
  											There is no <strong> Sales Rep. Group </strong> to manage yet.
										</div>
									@endif		
								</div>
							</div>

							<hr>
							<div class="row justify-content-center">
								<div class="col">
									<h5> Add a Sales Rep. Group </h5>
								</div>
							</div>

							<div class="row justify-content-center">
								<div class="col">
									@if(session('error'))
										<div class="alert alert-danger">
  											{{ session('error') }}
										</div>
									@endif

									@if(session('response'))
										<div class="alert alert-info">
  											{{ session('response') }}
										</div>
									@endif
								</div>
							</div>

							<form method="POST" action="{{ route('dataManagementSalesRepGroupAdd') }}">
							@csrf
								<div class="row justify-content-center">
									<div class="col">
										<label for="region"> Region: </label>
										<select class="form-control" name="region">
											@if($region)
												<option value=""> Select a Region </option>
												@for($r = 0; $r < sizeof($region);$r++)
													<option value="{{ $region[$r]["id"] }}"> 
														{{ $region[$r]["name"] }} 
													</option>
												@endfor	
											@else
												<div class="alert alert-warning">
		  											There is no <strong> Region </strong> created yet, please first create a Region to relate with a currency.
												</div>
											@endif
										</select>
									</div>

									<div class="col">
										<label> Sales Rep. Group Name: </label>
										<input class="form-control" type="text" name="salesRepGroup">
									</div>

															
								</div>

								<div class="row justify-content-end mt-1">
									<div class="col col-sm-4">
										<input type="submit" class="btn btn-primary" value="Add Sales Rep. Group" style="width:100%;">
									</div>
								</div>
							</form>

							<hr><br><hr>
							
							<div class="row justify-content-center">
								<div class="col align-self-center">
									<h5> Edit / Management Sales Rep. </h5>
								</div>
							</div>
							
							<div class="row justify-content-center">
								<div class="col">
									@if($salesRep)
										<form method="POST" action="{{route('dataManagementSalesRepEditFilter')}}">
											@csrf
											<input type="hidden" name="jorge">
											<div class="row justify-content-end mt-1">
												<div class="col">
													<input type="submit" class="btn btn-primary" value="Edit" style="width: 100%;">
												</div>
											</div>
										</form>
									@else
										<div class="alert alert-warning">
  											There is no <strong> Sales Rep. </strong> to manage yet.
										</div>
									@endif		
								</div>
							</div>

							<hr>
							<div class="row justify-content-center">
								<div class="col">
									<h5> Add a Sales Rep. </h5>
								</div>
							</div>

							<form method="POST" action="{{ route('dataManagementSalesRepAdd') }}">
							@csrf
								<div class="row justify-content-center">
									<div class="col">
										<label> Region: </label>
										@if($region)
											<select class="form-control" style="width: 100%;" name="region" id="salesRep_Region">
												<option value=""> Select a Region </option>
												@for($r = 0; $r < sizeof($region);$r++)
													<option value="{{ $region[$r]["id"] }}"> {{ $region[$r]["name"] }}</option>
												@endfor	
											</select>
										@else
											<div class="alert alert-warning">
	  											There is no <strong> Region </strong> created yet, please first create a Region to relate with a Sales Group Rep. and Sales Group.
											</div>
										@endif
									</div>

									<div class="col">
										<label> Sales Rep. Group: </label>
										@if($salesRepGroup)
											<select class="form-control" style="width: 100%;" name="salesRepGroup" id="salesRep_SalesRepGroup">
												<option value=""> Select a Region </option>
											</select>
										@else
											<div class="alert alert-warning">
	  											There is no <strong> Sales Rep. Group </strong> created yet, please first create a Sales Rep. Group to relate with a Sales Rep..
											</div>
										@endif
									</div>

									<div class="col">
										<label> Sales Rep. Name: </label>
										<input class="form-control" type="text" name="salesRep">
									</div>

															
								</div>

								<div class="row justify-content-end mt-1">
									<div class="col col-sm-4">
										<input type="submit" class="btn btn-primary" value="Add Sales. Rep" style="width:100%;">
									</div>
								</div>
							</form>

							<hr><br><hr>
							
							<div class="row justify-content-center">
								<div class="col align-self-center">
									<h5> Edit / Management Sales Rep. Unit </h5>
								</div>
							</div>
							
							<div class="row justify-content-center">
								<div class="col">
									@if($salesRepUnit)
										<form method="POST" action="{{route("salesRepUnitEditFilter")}}">
											@csrf
											<input type="hidden" name="jorge">
											<div class="row justify-content-end mt-1">
												<div class="col">
													<input type="submit" class="btn btn-primary" value="Edit" style="width: 100%;">
												</div>
											</div>
										</form>
									@else
										<div class="alert alert-warning">
  											There is no <strong> Sales Rep. Unit </strong> to manage yet.
										</div>
									@endif		
								</div>
							</div>

							<hr>
							<div class="row justify-content-center">
								<div class="col">
									<h5> Add a Sales Rep. Unit </h5>
								</div>
							</div>

							<div class="row justify-content-center">
								<div class="col">
									@if(session('error'))
										<div class="alert alert-danger">
  											{{ session('error') }}
										</div>
									@endif

									@if(session('response'))
										<div class="alert alert-info">
  											{{ session('response') }}
										</div>
									@endif
								</div>
							</div>

							<form method="POST" action="{{ route('dataManagementSalesRepUnitAdd') }}">
							@csrf
								<div class="row justify-content-center">
									<div class="col">
										<label> Region: </label>
										@if($region)
											<select class="form-control" style="width: 100%;" name="region" id="salesRepUnit_Region">
												<option value=""> Select a Region </option>
												@for($r = 0; $r < sizeof($region);$r++)
													<option value="{{ $region[$r]["id"] }}"> {{ $region[$r]["name"] }}</option>
												@endfor	
											</select>
										@else
											<div class="alert alert-warning">
	  											There is no <strong> Region </strong> created yet, please first create a Region to relate with a Sales Group Rep. and Sales Group.
											</div>
										@endif
									</div>

									<div class="col">
										<label> Sales Rep. Group: </label>
										@if($salesRepGroup)
											<select class="form-control" style="width: 100%;" name="salesRepGroup" id="salesRepUnit_SalesRepGroup">
												<option value=""> Select a Region </option>
											</select>
										@else
											<div class="alert alert-warning">
	  											There is no <strong> Sales Rep. Group </strong> created yet, please first create a Sales Rep. Group to relate with a Sales Rep..
											</div>
										@endif
									</div>
								
									<div class="col">
										<label for="region"> Sales Rep.: </label>
										@if($salesRep)
											<select class="form-control" style="width: 100%;" name="salesRep" id="salesRepUnit_SalesRep">
												<option value=""> Select a Region </option>
											</select>
										@else
											<div class="alert alert-warning">
	  											There is no <strong> Sales Rep. </strong> created yet, please first create a Sales Rep. to relate with a Sales Rep. Unit.
											</div>
										@endif
									</div>
								</div>

								<div class="row justify-content-center">
									<div class="col">
										<label> Origin: </label>
										@if($origin)
											<select name="origin" class="form-control">
												<option value=""> Select </option>
												@for($o = 0; $o < sizeof($origin);$o++)
													<option value="{{$origin[$o]['id']}}">
														{{ $origin[$o]['name'] }}
													</option>
												@endfor
											</select>
										@else
											<div class="alert alert-warning">
	  											There is no <strong> Origin </strong> created yet, please first create a Origin to relate with a Sales Rep. Unit.
											</div>
										@endif
									</div>									

									<div class="col">
										<label> Sales Rep. Unit Name: </label>
										<input class="form-control" type="text" name="salesRepUnit">
									</div>		
								</div>

								<div class="row justify-content-end mt-1">
									<div class="col col-sm-4">
										<input type="submit" class="btn btn-primary" value="Add Sales Rep. Unit" style="width:100%;">
									</div>
								</div>
							</form>

						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	
	<div id="vlau"></div>

	<script type="text/javascript">
		
		$(document).ready(function(){      
      		$('#salesRep_Region').click(function(){
        		var regionID = $(this).val();                		
        		console.log(regionID);
        		if(regionID != ""){        			
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
            			url:"/dataManagement/ajax/salesRepGroupByRegion",
            			method:"POST",
            			data:{regionID},
	              		success: function(output){
	                		$('#salesRep_SalesRepGroup').html(output);                		
	              		},
	              		error: function(xhr, ajaxOptions,thrownError){
	                		alert(xhr.status+""+thrownError);
	          			}
	          		});
	          	}
	        });

	        $('#salesRepUnit_Region').click(function(){
        		var regionID = $(this).val();                		
        		console.log(regionID);
        		if(regionID != ""){        			
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
            			url:"/dataManagement/ajax/salesRepGroupByRegion",
            			method:"POST",
            			data:{regionID},
	              		success: function(output){
	                		$('#salesRepUnit_SalesRepGroup').html(output);                		
	                		$('#salesRepUnit_SalesRep').html("<option value=''> Select the Sales Rep Group </option>");                		
	              		},
	              		error: function(xhr, ajaxOptions,thrownError){
	                		alert(xhr.status+""+thrownError);
	          			}
	          		});
	          	}
	        });

	        $('#salesRepUnit_SalesRepGroup').click(function(){
        		var regionID = $("#salesRepUnit_Region").val();                		
        		var salesRepGroupID = $(this).val();                		
        		console.log(regionID);
        		console.log(salesRepGroupID);
        		if(regionID != ""){        			
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
            			url:"/dataManagement/ajax/salesRepBySalesRepGroup",
            			method:"POST",
            			data:{regionID,salesRepGroupID},
	              		success: function(output){
	                		///$('#vlau').html(output);                
	                		$('#salesRepUnit_SalesRep').html(output);                		
	              		},
	              		error: function(xhr, ajaxOptions,thrownError){
	                		alert(xhr.status+""+thrownError);
	          			}
	          		});
	          	}
	        });
	    });

	</script>
@else
@endif
@endsection
