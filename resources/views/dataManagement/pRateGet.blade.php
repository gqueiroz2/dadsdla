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
				<div class="card" style="margin-bottom:15%;">
					<div class="card-header">
						<center><h4> Data Management - <b> P-Rate / Currency </b> </h4></center>
					</div>
					<div class="card-body">
						<div class="container-fluid">
							<div class="row justify-content-center">
								<div class="col">
									<h5> Edit / Management Currency </h5>
								</div>
							</div>
							@if($region)
								@if($currency)
									<form method="GET" action="{{ route('dataManagementCurrencyEditGet')}}">
										<div class="row mt-1">
											<div class="col">
												<input type="submit" class="btn btn-primary mt-2" value="Edit" style="width: 100%;">
											</div>
										</div>
									</form>
								@else
									<div class="alert alert-warning">
  										There is no <strong> Currency </strong> to manage yet.
									</div>
								@endif
							@else
								<div class="alert alert-warning">
  										There is no <strong> Region </strong> created yet, please first create a Region to relate with a currency.
									</div>
							@endif

							
							<hr>
							<div class="row justify-content-center">
								<div class="col">
									<h5> Add a Currency </h5>
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

							<form method="POST" action="{{ route('dataManagementCurrencyAdd') }}">
							@csrf
								<div class="row justify-content-center">
									<div class="col">
										<label for="region"> Region: </label>
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
										<label for="region"> Name: </label>										
										@if($region)												
											<input type="text" class="form-control" name="currency">
										@else
											<div class="alert alert-warning"> There is no regions created yet. </div>
										@endif										
									</div>								
								</div>

								<div class="row justify-content-end mt-1">
									<div class="col col-sm-3">
										<input type="submit" class="btn btn-primary" value="Add Currency" style="width:100%;">
									</div>
								</div>
							</form>

							<hr><br><hr>

							<div class="row justify-content-center">
								<div class="col">
									<h5> Edit / Management P-Rate </h5>
								</div>
							</div>

							@if($region)
								@if($currency)
									@if($pRate)
										<form method="GET" action="{{route('dataManagementPRateEditGet')}}">
        									<div class="row mt-2">
        										<div class="col">
        											<input type="submit" class="btn btn-primary" value="Edit" style="width: 100%;">
        										</div>
        									</div>   								
										</form>
									@else
										<div class="alert alert-warning">
  											There is no <strong> P-Rate </strong> to manage yet.
										</div>
									@endif
								@else
									<div class="alert alert-warning">
  										There is no <strong> Currency </strong> to manage yet.
									</div>
								@endif
							@else
								<div class="alert alert-warning">
  									There is no <strong> Region </strong> created yet, please first create a Region to relate with a currency.
								</div>
							@endif

							<hr>

							<div class="row justify-content-center">
								<div class="col">
									<h5> Add a P-Rate </h5>
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

							<form method="POST" action="{{ route('dataManagementPRateAdd') }}">
							@csrf
								<div class="row justify-content-center">
									<div class="col">
										<label> Year: </label>
										<input class="form-control" type="number" name="year" value="{{$cYear}}" min="2001" max="2050">
									</div>									
								
									<div class="col">
										<label> Currency: </label>
										<select class="form-control" name="currency">
											<option value=""> Select the Currency </option>
											@if($currency)
												@for($c = 0; $c < sizeof($currency);$c++)
													<option value="{{ $currency[$c]["id"] }}">
														{{ $currency[$c]["name"] }}
													</option>
												@endfor	
											@else
												<option value=""> There is no currency created yet. </option>
											@endif
										</select>
									</div>

									<div class="col">
										<label> Value: </label>
										<input class="form-control" type="number" name="value" min="0" step="0.00001">
									</div>									
								</div>

								<div class="row justify-content-end mt-1">
									<div class="col col-sm-3">
										<input type="submit" class="btn btn-primary" value="Add P-Rate" style="width:100%;">
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
@else
@endif
@endsection
