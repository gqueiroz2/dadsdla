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
						<center><h4> Data Management - <b> Edit User Type  </b> </h4></center>
					</div>
					<div class="card-body">
						@if(session('response'))
							<div class="alert alert-info">
								{{ session('response') }}
							</div>
						@endif
						<div class="container-fluid">
							@if($userType)
								<div class='row mt-1'>
									<div class="col">
										<form method="POST" action="{{route('dataManagementUserTypeEditPost')}}">
											@csrf
											{{$render->userTypeEdit($userType)}}
											<input type="submit" class="btn btn-primary mt-2" value="Edit/Delete" style="width: 100%;">
										</form>
									</div>
								</div>
							@else
								<div class="alert alert-warning">
  									There is no <strong> User </strong> to manage yet.
								</div>
							@endif
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
@else
@endif
@endsection
