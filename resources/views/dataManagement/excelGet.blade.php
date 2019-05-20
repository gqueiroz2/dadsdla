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
				<div class="card">
					<div class="card-header">
						<center><h4> Data Management - <b> Excel </b> </h4></center>
					</div>
					<div class="card-body">
						<div class="container-fluid">
							<div class="row justify-content-center">
								<div class="col">
									<h5> Add a Excel File </h5>
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
							<form action="{{ route('fileUploadExcelPost') }}" method="POST" enctype="multipart/form-data">
							@csrf
								<div class="row justify-content-center">          
							 		<div class="col">		
										<div class="form-group">
								        	<label for="exampleInputFile"> Select the report you want to upload </label>
								        	<select class="form-control" name="table">
								        		<option value=""> Select </option>
								        		@for($r = 0 ; $r < sizeof($report) ; $r++)
								        			<option value="{{ $report[$r]['table'] }}"> {{ $report[$r]['show'] }} </option>
								        		@endfor
								        	</select>
								    	</div>
								    </div>
								</div>
							 	<div class="row justify-content-center">          
							 		<div class="col">		
										<div class="form-group">
											<input type="file" name="file">                
								    	</div>
								    </div>
								</div>
								<div class="row justify-content-end">          
							 		<div class="col col-sm-6">		
								    	<button type="submit" class="btn btn-primary" style="width: 100%;">Submit</button>
									</form>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
@else
@endif
@endsection
