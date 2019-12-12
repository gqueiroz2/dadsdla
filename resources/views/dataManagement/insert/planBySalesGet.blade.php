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
						<center><h4> Data Management - <b> Insert PLAN BY SALES </b> </h4></center>
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

								<input type="hidden" name="table" value="plan_by_sales">

								<div class="row mt-2">
							 		<div class="col">
							 			<label> Year @if($errors->has('year')) <span style="color: red;">* Required </span> @endif </label>
										<select class="form-control" name="year">
										 	<option value=""> Select </option>
										 	@for($y=0;$y<sizeof($years);$y++)

										 		<option value="{{$years[$y]}}"> {{$years[$y]}} </option>

										 	@endfor									 	
									 	</select>
									</div>
							 	</div>

							 	<div class="row mt-2">
							 		<div class="col">
							 			<label> Region @if($errors->has('region')) <span style="color: red;">* Required </span> @endif </label>

										<select class="selectpicker" multiple="true" name="region[]" data-width="100%" data-selected-text-format='count' multiple='true' data-actions-box='true'>
										 	@for($r=0;$r<sizeof($region);$r++)
										 		<option value="{{ $region[$r]['id'] }}" selected="true"> {{ $region[$r]['name'] }} </option>
										 	@endfor
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

