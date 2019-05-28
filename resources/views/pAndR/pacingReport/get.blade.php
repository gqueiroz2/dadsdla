@extends('layouts.mirror')
@section('title', 'P&R Pacing Report')
@section('head')	
    <?php include(resource_path('views/auth.php')); ?>
@endsection
@section('content')
	<div class="container-fluid">
		<div class="row">
			<div class="col">
				<form method="POST" action="{{ route('pacingReportPost') }}">
					@csrf
					<div class="row">
						
					</div>
				</form>
			</div>
		</div>

		<div class="row justify-content-end mt-2">
			<div class="col-3" style="color: #0070c0;font-size: 25px;">
				Pacing Report
			</div>
		</div>
	</div>


@endsection