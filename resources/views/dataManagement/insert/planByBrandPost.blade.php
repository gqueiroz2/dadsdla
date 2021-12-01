@extends('layouts.mirror')

@section('title', '@')
@section('head')
    <?php include(resource_path('views/auth.php')); ?>
@endsection
@section('content')
	<div class=" container-fluid">
		<div class="col-6" style="float: center;">
			<label  style="color: #1E90FF; font-size: 25px; margin-left: 60%;">
			 	insetions was successfully made!
			</label>
	        <a class="btn btn-primary" style="margin-left: 50%; width: 100%;"  href="{{ route("dataManagementHomeGet")}}" >
	        	Data Management
	        </a>    
	    </div>
 	</div>

@endsection