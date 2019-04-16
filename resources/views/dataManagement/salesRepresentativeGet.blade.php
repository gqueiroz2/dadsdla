@extends('layouts.mirror')

@section('title', '@')

@section('content')

	<div class="container-fluid">
		<div class="row justify-content-center">
			<div class="col-sm-8">
				<div class="card" style="margin-bottom:15%;">
					<div class="card-header">
						<center><h4> Data Management - <b> Sales Representative Group and Sales Representative</b> </h4></center>
					</div>
					<div class="card-body">
						<div class="container-fluid">
							<div class="row justify-content-center">
								<div class="col align-self-center">
									<h5> Edit / Management Sales Representative Group </h5>
								</div>
							</div>
							
							<div class="row justify-content-center">
								<div class="col">
									@if($salesRepresentativeGroup)
										{{ $render->editSalesRepGroup($salesRepresentativeGroup) }}
									@else
										<div class="alert alert-warning">
  											There is no <strong> Sales Representative Group </strong> to manage yet.
										</div>
									@endif		
								</div>
							</div>

							<hr>
							<div class="row justify-content-center">
								<div class="col">
									<h5> Add a Sales Representative Group </h5>
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

							<form method="POST" action="{{ route('dataManagementAddSalesRepresentativeGroup') }}">
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
										<label> Sales Representative Group Name: </label>
										<input class="form-control" type="text" name="salesRepGroup">
									</div>

															
								</div>

								<div class="row justify-content-end mt-1">
									<div class="col col-sm-3">
										<input type="submit" class="btn btn-primary" value="Add Sales Rep. Group" style="width:100%;">
									</div>
								</div>
							</form>

							<hr><br><hr>
							
							<div class="row justify-content-center">
								<div class="col align-self-center">
									<h5> Edit / Management Sales Representative </h5>
								</div>
							</div>
							
							<div class="row justify-content-center">
								<div class="col">
									@if($salesRepresentative)
										
									@else
										<div class="alert alert-warning">
  											There is no <strong> Sales Representative </strong> to manage yet.
										</div>
									@endif		
								</div>
							</div>

							<hr>
							<div class="row justify-content-center">
								<div class="col">
									<h5> Add a Sales Representative </h5>
								</div>
							</div>

							<form method="POST" action="{{ route('dataManagementAddSalesRepresentative') }}">
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
	  											There is no <strong> Region </strong> created yet, please first create a Region to relate with a Sales Group Representative and Sales Group.
											</div>
										@endif
									</div>

									<div class="col">
										<label> Sales Representative Group: </label>
										@if($salesRepresentativeGroup)
											<select class="form-control" style="width: 100%;" name="saleRepGr" id="salesRep_SalesRepGroup">
												<option value=""> Select a Region </option>
											</select>
										@else
											<div class="alert alert-warning">
	  											There is no <strong> Sales Representative Group </strong> created yet, please first create a Sales Representative Group to relate with a Sales Representative.
											</div>
										@endif
									</div>

									<div class="col">
										<label> Sales Representative Name: </label>
										<input class="form-control" type="text" name="salesRep">
									</div>

															
								</div>

								<div class="row justify-content-end mt-1">
									<div class="col col-sm-3">
										<input type="submit" class="btn btn-primary" value="Add Sales. Rep" style="width:100%;">
									</div>
								</div>
							</form>

							<hr><br><hr>
							
							<div class="row justify-content-center">
								<div class="col align-self-center">
									<h5> Edit / Management Sales Representative Unit </h5>
								</div>
							</div>
							
							<div class="row justify-content-center">
								<div class="col">
									@if($salesRepresentativeUnit)
										
									@else
										<div class="alert alert-warning">
  											There is no <strong> Sales Representative Unit </strong> to manage yet.
										</div>
									@endif		
								</div>
							</div>

							<hr>
							<div class="row justify-content-center">
								<div class="col">
									<h5> Add a Sales Representative Unit </h5>
								</div>
							</div>

							<form method="POST" action="{{ route('dataManagementAddSalesRepresentativeUnit') }}">
							@csrf
								<div class="row justify-content-center">
									<div class="col">
										<label for="region"> Sales Representative: </label>
										@if($salesRepresentative)
											@for($s = 0; $s < sizeof($salesRepresentative);$s++)
											@endfor	
										@else
											<div class="alert alert-warning">
	  											There is no <strong> Sales Representative </strong> created yet, please first create a Sales Representative to relate with a Sales Representative Unit.
											</div>
										@endif
									</div>

									<div class="col">
										<label> Sales Representative Unit Name: </label>
										<input class="form-control" type="text" name="salesRep">
									</div>

															
								</div>

								<div class="row justify-content-end mt-1">
									<div class="col col-sm-3">
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
        		var region = $(this).val();                		
        		console.log(region);
        		if(region != ""){        			
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
            			data:{region},
	              		success: function(output){
	                		$('#vlau').html(output);                		
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
