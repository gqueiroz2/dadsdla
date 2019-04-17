@extends('layouts.mirror')

@section('title', '@')

@section('content')

	<div class="container-fluid">
		<div class="row justify-content-center">
			<div class="col-sm-6">
				<div class="card">
					<div class="card-header">
						<center><h4> Data Management - <b> User </b> </h4></center>
					</div>
					<div class="card-body">
						<div class="container-fluid">
							<div class="row justify-content-center">
								<div class="col">
									<h5> Edit / Management User </h5>
								</div>
							</div>
							
							@if($user)

							@else
								<div class="alert alert-warning">
  									There is no <strong> User </strong> to manage yet.
								</div>
							@endif
							<hr>
							<div class="row justify-content-center">
								<div class="col">
									<h5> Add a User </h5>
								</div>
							</div>

							<form method="POST" action="{{ route('dataManagementAddUser') }}">
							@csrf

								
								<div class="row justify-content-center">
									<div class="col">
										<label> Name: </label>
										<input type="text" name="name" class="form-control">
									</div>								

									<div class="col">
										<label> E-mail: </label>
										<input type="text" name="email" class="form-control">
									</div>								
								</div>

								<div class="row justify-content-center">
									<div class="col">
										<label> Region: </label>
										<select class="form-control" name="region">
											@if($region)
												<option value=""> Select a Region </option>
												@for($r = 0; $r < sizeof($region);$r++)
													<option value="{{ $region[$r]["name"] }}"> 
														{{ $region[$r]["name"] }} 
													</option>
												@endfor												
											@else
												<option value=""> There is no regions created yet. </option>
											@endif
										</select>
									</div>

									<div class="col">
										<label> Level: </label>
										<select class="form-control" name="level">
											
										</select>
									</div>

									<div class="col">
										<label> Sub-Level: </label>
										<select class="form-control" name="level">
											
										</select>
									</div>

									<div class="col">
										<label> Status: </label>
										<select class="form-control" name="level">
											
										</select>
									</div>
								</div>
								<div class="row justify-content-end mt-1">
									<div class="col col-sm-3">
										<input type="submit" class="btn btn-primary" value="Add Region" style="width:100%;">
									</div>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!--
	<script type="text/javascript">
		
		jQuery(document).ready(function($){
			$('#region').click(function(e){

				location.href =''

			});
		});

	</script>
	-->
@endsection
