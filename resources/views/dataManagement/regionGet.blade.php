@extends('layouts.mirror')

@section('title', '@')

@section('content')

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
								{{ $render->editRegion($region) }}
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
																  
							<form method="POST" action="{{ route('dataManagementAddRegion') }}">
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
