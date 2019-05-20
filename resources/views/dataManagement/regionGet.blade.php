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
						<center><h4> Data Management - <b> Region </b> </h4></center>
					</div>
					<div class="card-body">
						<div class="container-fluid">
							<div class="row justify-content-center">
								<div class="col">
									<h5> Edit / Management Regions </h5>
								</div>
							</div>
							
							@if($region)			
								{{-- $render->regionEdit($region) --}}
    							<div class='row mt-1'>
									<div class="col">
										<form method="GET" action=" {{ route('dataManagementRegionEditGet') }} ">
											<input type="submit" class="btn btn-primary mt-2" value="Edit/Delete" style="width: 100%;">
										</form>
									</div>
								</div>
							@else
								<div class="alert alert-warning">
  									There is no <strong> Regions </strong> to manage yet.
								</div>
							@endif
							<hr>
							<div class="row justify-content-center">
								<div class="col">
									<h5> Add a Region </h5>
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
																  
							<form method="POST" action="{{ route('dataManagementRegionAdd') }}">
							@csrf
								<div class="row justify-content-center">
									<div class="col">
										<label for="region"> Name: </label>
										<input type="text" name="region" class="form-control">
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
@else
@endif
@endsection
