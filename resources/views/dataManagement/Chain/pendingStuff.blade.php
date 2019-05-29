@extends('layouts.mirror')
@section('title', '@')
@section('head')
    <?php include(resource_path('views/auth.php')); ?>
@endsection
@section('content')
	@if($userLevel == 'SU')
		<div class="container">
			
			{{ $rS->base($table,$newValues,$dependencies) }}


		</div>
	@endif
@endsection

