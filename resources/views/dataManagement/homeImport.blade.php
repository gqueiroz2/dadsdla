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
						<div class="container-fluid">
							<div class="row mt-2 justify-content-center">
								<form action="{{ route('postTest') }}" method="POST" enctype="multipart/form-data">
									@csrf
									<div class="form-group">
							        	<label for="exampleInputFile">File Upload</label>
							        	<input type="file" name="file" class="form-control" id="exampleInputFile">
							    	</div>
							    	<button type="submit" class="btn btn-primary">Submit</button>
								</form>
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
