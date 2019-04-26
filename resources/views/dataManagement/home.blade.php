@extends('layouts.mirror')

@section('title', '@')

@section('head')

	<style type="text/css">		
		
		.button:focus{    
    		color:white;
		}

	</style>

@endsection


@section('content')

	<div class="container-fluid">
		<div class="row justify-content-center">
			<div class="col-sm-6">
				<div class="card">
					<div class="card-header">
						<span> Data Management </span>	
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

							<div class="row mt-2 justify-content-center">
								<div class="col">
									<button class="btn btn-primary" style="width: 100%;" id="agency"> 
										<a href="#" style="color: white">
											Agency
										</a>
									</button>	
								</div>
								<div class="col">
									<button class="btn btn-primary" style="width: 100%;" id="client">
										<a href="#" style="color: white">
										 	Client 
										</a>
									</button>	
								</div>
							</div>

							<div class="row mt-2 justify-content-center">
								<div class="col">
									<button class="btn btn-primary" style="width: 100%;" id="brand"> 
										<a style="color: white;" href="{{ route('dataManagementOriginGet') }}">
											Origin
										</a>
									</button>	
								</div>
								<div class="col">
									<button class="btn btn-primary" style="width: 100%;" id="brand"> 
										<a style="color: white;" href="{{ route('dataManagementBrandGet') }}">
											Brand 
										</a>
									</button>	
								</div>								
							</div>
							{{--
							<div class="row mt-2 justify-content-start">
								<div class="col">
									<button class="btn btn-primary" style="width: 100%;" id="brand"> 
										<a style="color: white;" href="{{ route('dataManagementTruncateGet') }}">
											Truncate
										</a>
									</button>	
								</div>
							</div>
							--}}
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
