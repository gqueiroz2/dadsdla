@extends('layouts.mirror')

@section('title', '@')
@section('head')
    <?php include(resource_path('views/auth.php')); ?>
@endsection
@section('content')
	<div class="container-fluid">
		<div class="row justify-content-center">
			<div class="col-sm-6">
				<div class="card">
					<div class="card-header">
						<center><h4> Data Management - <b> Insert PLAN BY BRAND </b> </h4></center>
					</div>
					<div class="card-body">
						<div class="container-fluid">
							
							<div class="row justify-content-center">
								<div class="col">
									<h6> Add a Excel File </h6>
								</div>
							</div>

							
							<form action="" method="POST" enctype="multipart/form-data">
							@csrf
							 	<div class="row justify-content-center">          
							 		<div class="col">		
										<div class="form-group">
											<input type="file" name="file">                
								    	</div>
								    </div>
								</div>

								<div class="row justify-content-center">
									<div class="col">
										@if(session('insertError'))
											<div class="alert alert-danger">
	  											{{ session('insertError') }}
											</div>
										@endif

										@if(session('insertSuccess'))
											<div class="alert alert-info">
	  											{{ session('insertSuccess') }}
											</div>
										@endif
									</div>
								</div>

								<input type="hidden" name="table" value="plan_by_brand">

							 	<div class="row mt-2">
							 		<div class="col">
							 			<label> Source </label>
										<select class="form-control" name="source">
										 	<option value=""> Select </option>
										 	<option value="ACTUAL"> ACTUAL </option>
										 	<option value="CORPORATE"> CORPORATE </option>
										 	<option value="TARGET"> TARGET </option>
									 	</select>
									</div>
							 	</div>

							 	<div class="row mt-2">
							 		<div class="col">
							 			<label> Year </label>
										<select class="form-control" name="year">
										 	<option value=""> Select </option>
										 	<option value="2019"> 2019 </option>
										 	<option value="2018"> 2018 </option>										 	
									 	</select>
									</div>
							 	</div>

								<div class="row justify-content-end mt-2">          
							 		<div class="col">		
								    	<button type="submit" class="btn btn-primary" style="width: 100%;">Submit</button>
									</div>
								</div>
							</form>
							<br><hr>								
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection