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
						<center><h4> Data Management - <b> Client </b> </h4></center>
					</div>
					<div class="card-body">
						<div class="container-fluid">
							<form action="{{ route('insertClientGroup') }}" method="POST">
							@csrf
								<div class="row">
									<div class="col">
										<span style="font-size: 18px;"> Create Client Group </span>
									</div>
								</div>

								<div class="row justify-content-center mt-1">
									<div class="col">
										@if(session('failedGroup'))
											<div class="alert alert-danger">
	  											{{ session('failedGroup') }}
											</div>
										@endif

										@if(session('insertedGroup'))
											<div class="alert alert-info">
	  											{{ session('insertedGroup') }}
											</div>
										@endif
									</div>
								</div>

								<div class="row mt-1">
									<div class="col">
										<label> Region: </label>
										<select name="region" class="form-control">
											<option value=""> Select a Region </option>
											@for($r=0;$r<sizeof($region);$r++)
												<option value="{{ $region[$r]['id'] }}"> {{ $region[$r]['name'] }}  </option>
											@endfor
										</select>
									</div>
								</div>

								<div class="row mt-1">
									<div class="col">
										<label> Name: </label>
										<input type="text" name="groupName" class="form-control">				
										<input type="hidden" name="type" value="client">					
									</div>
								</div>

								<div class="row mt-1">
									<div class="col">
										<button type="submit" class="btn btn-primary" style="width: 100%;">Submit</button>										
									</div>
								</div>
							</form>

							<hr><hr><hr>

							<form action="{{ route('insertOneClient') }}" method="POST">
							@csrf							
								<div class="row">
									<div class="col">
										<span style="font-size: 18px;"> Create Client </span>
									</div>
								</div>

								<div class="row justify-content-center mt-1">
									<div class="col">
										@if(session('failedTable'))
											<div class="alert alert-danger">
	  											{{ session('failedTable') }}
											</div>
										@endif

										@if(session('insertedTable'))
											<div class="alert alert-info">
	  											{{ session('insertedTable') }}
											</div>
										@endif
									</div>
								</div>

								<div class="row mt-1">
									<div class="col">
										<label> Client Group: </label>
										<select name="groupName" class="form-control">
											<option value=""> Select the respective Client Group </option>
											@for($r=0;$r<sizeof($clientGroup);$r++)
												<option value="{{ $clientGroup[$r]['id'] }}"> {{ $clientGroup[$r]['clientGroup'] }}  </option>
											@endfor
										</select>
									</div>
								</div>

								<div class="row mt-1">
									<div class="col">
										<label> Name: </label>
										<input type="text" name="name" class="form-control">				
										<input type="hidden" name="type" value="client">					
									</div>
								</div>

								<div class="row mt-1">
									<div class="col">
										<button type="submit" class="btn btn-primary" style="width: 100%;">Submit</button>										
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
