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
							<span style="font-size: 16px; font-weight: bold;"> Sales Management  </span>	
						</center>
					</div>
					<div class="card-body">
						<div class="container-fluid">
							<div class="row mt-2 justify-content-center">
								<div class="col">
									<button class="btn btn-primary" style="width: 100%;" id="agency"> 
										<a href="{{ route('salesManagementCustomReportV1') }}" style="color: white">
											Custom Report V1
										</a>
									</button>
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
