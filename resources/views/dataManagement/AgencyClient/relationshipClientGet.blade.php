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
				<div class="card">
					<div class="card-header">
						<span> Data Management </span>	
					</div>
					<div class="card-body">
						
					</div>
				</div>
			</div>
		</div>
	</div>
@endif
@endsection
