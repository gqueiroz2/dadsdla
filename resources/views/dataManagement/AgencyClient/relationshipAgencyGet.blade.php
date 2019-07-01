@extends('layouts.mirror')

@section('title', '@')

@section('head')

	<style type="text/css">		
		
		.button:focus{    
    		color:white;
		}

	</style>
    <?php 
    	include(resource_path('views/auth.php')); 
    ?>

@endsection


@section('content')
@if($userLevel == 'SU')
	<div class="container-fluid">
		<div class="row justify-content-center">
			<div class="col-sm-10">
				<div class="card">

					<div class="card-header">
						<center>
							<span><b> RelationShip Agency </b></span>	
						</center>
					</div>

					<div class="card-body">
						<div class="container-fluid">
							<div class="row justify-content-center">
								<div class="col"><b> # </b></div>
								<div class="col"><b> Region </b></div>
								<div class="col"><b> Agency Group </b></div>
								<div class="col"><b> Agency </b></div>
								<div class="col"><b> Agency Unit </b></div>
							</div>
							@for( $a=0; $a<sizeof($agencies); $a++)
								<div class="row justify-content-center">
									<div class="col">
										<input type="text" class="form-control" style="width:100% !important;" value="{{$a+1}}">
									</div>
									<div class="col">
										<input type="text" class="form-control" style="width:100% !important;"  name="region" value="{{$agencies[$a]['region']}}">
									</div>
									<div class="col">
										<input type="text" class="form-control" style="width:100% !important;"  name="agencyGroup" value="{{$agencies[$a]['agencyGroup']}}">
									</div>
										<input type="hidden" name="agencyGroupID" value="{{$agencies[$a]['agencyGroupID']}}">
									<div class="col">
										<input type="text" class="form-control" style="width:100% !important;"  name="agency" value="{{$agencies[$a]['agency']}}">
									</div>
										<input type="hidden" name="agencyID" value="{{$agencies[$a]['id']}}">
									<div class="col">
										<input type="text" class="form-control" style="width:100% !important;"  name="agencyUnit" value="{{$agencies[$a]['agencyUnit']}}">
									</div>
										<input type="hidden" name="agencyUnitID" value="{{$agencies[$a]['agencyUnitID']}}">
								</div>
							@endfor
						</div>
					</div>

				</div>
			</div>
		</div>
	</div>
@endif
@endsection
