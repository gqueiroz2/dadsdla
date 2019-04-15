@extends('layouts.mirror')

@section('title', '@')

@section('content')

	<div class="container-fluid">
		<div class="row justify-content-center">
			<div class="col-sm-6">
				<div class="card">
					<div class="card-header">
						<center><h4> Data Management - <b> Brand </b> </h4></center>
					</div>
					<div class="card-body">
						<div class="container-fluid">
							<div class="row justify-content-center">
								<div class="col">
									<h5> Edit / Management Brand </h5>
								</div>
							</div>
							
							@if($brand)

							@else
								<div class="alert alert-warning">
  									There is no <strong> Brands </strong> to manage yet.
								</div>
							@endif
							<hr>
							<div class="row justify-content-center">
								<div class="col">
									<h5> Add a Brand </h5>
								</div>
							</div>

							<form method="POST" action="{{ route('dataManagementAddBrand') }}">
							@csrf
								<div class="row justify-content-center">
									<div class="col">
										<label for="region"> Name: </label>
										<input type="text" name="brand" class="form-control">
									</div>								
								</div>

								<div class="row justify-content-end mt-1">
									<div class="col col-sm-3">
										<input type="submit" class="btn btn-primary" value="Add Brand" style="width:100%;">
									</div>
								</div>
							</form>

							<hr><br><hr>

							<div class="row justify-content-center">
								<div class="col">
									<h5> Edit / Management Brand Unit </h5>
								</div>
							</div>
							
							@if($brandUnit)

							@else
								<div class="alert alert-warning">
  									There is no <strong> Brand Unit </strong> to manage yet.
								</div>
							@endif
							<hr>
							<div class="row justify-content-center">
								<div class="col">
									<h5> Add a Brand Unit </h5>
								</div>
							</div>

							<form method="POST" action="{{ route('dataManagementAddBrand') }}">
							@csrf
								<div class="row justify-content-center">
									<div class="col">
										@if($brand)										
											<label for="region"> Brand </label>
											<input type="text" name="brand" class="form-control">										
										@else
											<div class="alert alert-warning">
	  											There is no <strong> Brand </strong> created yet, please create an Brand to relate with Brand Unit.
											</div>
										@endif
									</div>							
									
									@if($origin)
										<div class="col">
											<label for="region"> Origin </label>
											<input type="text" name="brand" class="form-control">
										</div>	
									@else
										<div class="col">
											<div class="alert alert-warning">
	  											There is no <strong> Origins </strong> created yet, please create an Origin to relate with Brand Unit.
											</div>
										</div>
									@endif																

									<div class="col">
										<label for="region"> Brand Unit </label>
										<input type="text" name="brand" class="form-control" {{$state}}>
									</div>								
								</div>

								<div class="row justify-content-end mt-1">
									<div class="col col-sm-3">
										<input type="submit" class="btn btn-primary" value="Add Brand Unit" style="width:100%;">
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
