@extends('layouts.mirror')

@section('title', '@')

@section('head')
	<script src="/js/views/dataManagement_userGet.js"></script>
    <?php include(resource_path('views/auth.php')); ?>
@endsection


@section('content')

@if($userLevel == 'SU')

	<div class="container-fluid">
		<div class="row justify-content-center">
			<div class="col-sm-6">
				<div class="card" style="margin-bottom: 5%;">
					<div class="card-header">
						<center><h4> Data Management - <b> User </b> </h4></center>
					</div>
					<div class="card-body">
						<div class="container-fluid">
							<div class="row justify-content-center">
								<div class="col">
									<h5> Edit / Management User </h5>
								</div>
							</div>
							<div class='row mt-1'>
								<div class="col">

									<form method="POST" action="{{route('dataManagementUserEditFilter')}}">
										@csrf
										{{ $render->filters($region)}}
									</form>
									<form method="POST" action="{{route('dataManagementUserEditFilter')}}">
										@csrf
										@if($user)
											{{ $render->userEdit($user,$region,$userType,$salesGroup)}}
											<input type="submit" class="btn btn-primary mt-2" value="Edit/Delete" style="width: 100%;">
										@else
											<br>
											<div class="alert alert-warning">
			  									There is no <strong> User </strong> to manage yet.
											</div>
										@endif			
									</form>
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
