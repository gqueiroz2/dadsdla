@extends('layouts.mirror')

@section('title', '@')

@section('head')
	<style type="text/css">		
		.button:focus{    
    		color:white;
		}
	</style>
    <?php include(resource_path('views/auth.php')); ?>
@endsection
@section('content')
@if($userLevel == 'SU')
	<div class="container-fluid">
		<div class="row justify-content-center">
			<div class="col-sm-6">
				<div class="card mt-5">
					<div class="card-header">
						<center>
							<span style="font-size: 16px; font-weight: bold;"> Insert New Info  </span>	
						</center>
					</div>
					<div class="card-body">
						<div class="container-fluid">
							<div class="row mt-2 justify-content-center">
								<div class="col">
									<button class="btn btn-primary" style="width: 100%;" id="agency"> 
										<a href="{{ route('dataCurrentThroughtG') }}" style="color: white">
											Data Current Throught
										</a>
									</button>
								</div>
							</div>

							<div class="row mt-2 justify-content-center">
								<div class="col">
									<button class="btn btn-primary" style="width: 100%;" id="agency"> 
										<a href="{{ route('chain') }}" style="color: white">
											Insert (BTS / FW / SF / ALEPH / WBD)
										</a>
									</button>
								</div>
							</div>

							<div class="row mt-2 justify-content-center">
								<div class="col">
									<button class="btn btn-primary" style="width: 100%;" id="agency"> 
										<a href="{{ route('chainCmaps') }}" style="color: white">
											Insert (CMAPS)
										</a>
									</button>
								</div>
							</div>

							<div class="row mt-2 justify-content-center">
								<div class="col">
									<button class="btn btn-primary" style="width: 100%;" id="agency"> 
										<a href="{{ route('insightsChain') }}" style="color: white">
											Insert (FORECAST)
										</a>
									</button>
								</div>
							</div>

							<div class="row mt-2 justify-content-center">
								<div class="col">
									<button class="btn btn-primary" style="width: 100%;" id="agency"> 
										<a href="{{ route('insertPlanByBrandGet') }}" style="color: white">
											Insert (Corporate/Actual/Target)
										</a>
									</button>	
								</div>
							</div>

							<div class="row justify-content-center mt-2">
								<div class="col">
									<button class="btn btn-primary" style="width: 100%;" id="agency"> 
										<a href="{{ route('insertPlanBySalesGet') }}" style="color: white">
											Insert Target by Sales Rep
										</a>
									</button>	
								</div>
							</div>

							<div class="row justify-content-center mt-2">
								<div class="col">
									<button class="btn btn-primary" style="width: 100%;" id="agency"> 
										<a href="{{ route('insertBvBandG') }}" style="color: white">
											Insert AVB
										</a>
									</button>	
								</div>
							</div>

							<div class="row justify-content-center mt-2">
								<div class="col">
									<button class="btn btn-primary" style="width: 100%;" id="agency"> 
										<a href="{{ route('saveCMAPSReadGet') }}" style="color: white">
											Save CMAPS Read
										</a>
									</button>	
								</div>
							</div>

						</div>
					</div>
				</div>


				<div class="card mt-5">
					<div class="card-header">
						<center>
							<span style="font-size: 16px; font-weight: bold;"> Data Management </span>	
						</center>
					</div>
					<div class="card-body">
						<div class="container-fluid">
							<div class="row justify-content-center">
								<div class="col">
									<button class="btn btn-primary" style="width: 100%;" id="region">
										<a style="color: white;" href="{{ route('dataManagementRegionGet') }}"> 	
											Region 
										</a>
									</button>	
								</div>
							
								<div class="col">
									<button class="btn btn-primary" style="width: 100%;" id="user">
										<a style="color: white;" href="{{ route('dataManagementUserGet') }}">
										 	User
										</a> 
									</button>	
								</div>
							</div>

							<div class="row mt-2 justify-content-center">
								<div class="col">
									<button class="btn btn-primary" style="width: 100%;" id="prate"> 
										<a style="color: white;" href="{{ route('dataManagementPRateGet') }}">
											P-Rate / Currency 
										</a>
									</button>	
								</div>
							
								<div class="col">
									<button class="btn btn-primary" style="width: 100%;" id="salesrepresentative"> 
										<a style="color: white;" href="{{ route('dataManagementSalesRepGet') }}">
											Sales Representative 
										</a>
									</button>	
								</div>
							</div>				

							<div class="row justify-content-center mt-2">
								<div class="col">
									<button class="btn btn-primary" style="width: 100%;" id="brand"> 
										<a style="color: white;" href="{{ route('dataManagementBrandGet') }}">
											Brand 
										</a>
									</button>	
								</div>								
							</div>

							<div class="row mt-2 justify-content-center">
								<div class="col">
									<button class="btn btn-primary" style="width: 100%;" id="brand"> 
										<a style="color: white;" href="{{ route('dataManagementAgencyGet') }}">
											Agency Group / Agency
										</a>
									</button>	
								</div>	
							
								<div class="col">
									<button class="btn btn-primary" style="width: 100%;" id="brand"> 
										<a style="color: white;" href="{{ route('dataManagementClientGet') }}">
											Client Group / Client
										</a>
									</button>	
								</div>															
							</div>
						</div>
					</div>
				</div>
				<div class="mt-5">
				</div>
			</div>
		</div>
	</div>
		
@else
@endif
@endsection
