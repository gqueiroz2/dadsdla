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
						<center><h4> Data Management - <b> Origin </b> </h4></center>
					</div>
					<div class="card-body">
						<div class="container-fluid">
							<div class="row justify-content-center">
								<div class="col">
									<h5> Edit / Management Origin </h5>
								</div>
							</div>
							
							@if($origin)
								{{-- $render->originEdit($origin) --}}
								<div class='row mt-1'>
									<div class="col">
										<form method="GET" action="">
											<input type="submit" class="btn btn-primary mt-2" value="Edit/Delete" style="width: 100%;">
										</form>
									</div>
								</div>
							@else
								<div class="alert alert-warning">
  									There is no <strong> Origins </strong> to manage yet.
								</div>
							@endif
							<hr>
							<div class="row justify-content-center">
								<div class="col">
									<h5> Add a Origin </h5>
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
							<form method="POST" action="{{ route('dataManagementOriginAdd') }}">
							@csrf
								<div class="row justify-content-center">
									<div class="col">
										<label> Name: </label>
										<input type="text" name="origin" class="form-control">
									</div>								
								</div>

								<div class="row justify-content-end mt-1">
									<div class="col col-sm-3">
										<input type="submit" class="btn btn-primary" value="Add Origin" style="width:100%;">
									</div>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
@else
@endif
@endsection
