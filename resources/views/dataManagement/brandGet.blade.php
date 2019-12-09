@extends('layouts.mirror')

@section('title', '@')
@section('head')
    <?php include(resource_path('views/auth.php')); ?>
@endsection
@section('content')

@if($userLevel == 'SU')
	<div class="container-fluid" style="margin-bottom: 5%;">
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
								{{-- $render->brandEdit($brand) --}}
								<div class='row mt-1'>
									<div class="col">
										<form method="GET" action="">
											<input type="submit" class="btn btn-primary mt-2" value="Edit/Delete" style="width: 100%;">
										</form>
									</div>
								</div>
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

							<form method="POST" action="{{ route('dataManagementBrandAdd') }}">
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
								{{-- $render->brandUnitEdit($brandUnit) --}}
								<div class='row mt-1'>
									<div class="col">
										<form method="GET" action="">
											<input type="submit" class="btn btn-primary mt-2" value="Edit/Delete" style="width: 100%;">
										</form>
									</div>
								</div>
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
							<form method="POST" action="{{ route('dataManagementBrandUnitAdd') }}">
							@csrf
								<div class="row justify-content-center">
									<div class="col">
										@if($brand)										
											<label> Brand </label>
											<select class="form-control" name="brand">
												
												<option value=""> Select</option>
												@for($b=0;$b<sizeof($brand);$b++)
													<option value="{{$brand[$b]['id']}}">
														{{ $brand[$b]['name'] }}
													</option>
												@endfor
											</select>							
										@else
											<div class="alert alert-warning">
	  											There is no <strong> Brand </strong> created yet, please create an Brand to relate with Brand Unit.
											</div>
										@endif
									</div>							
									
									@if($origin)
										<div class="col">
											<label for="region"> Origin </label>
											<select class="form-control" name="origin">
													<option value="1">
														Default
													</option>												
											</select>
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
										<input type="text" name="brandUnit" class="form-control" {{$state}}>
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
@else
@endif
@endsection
