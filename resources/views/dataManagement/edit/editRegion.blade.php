@extends('layouts.mirror')

@section('title', '@')

@section('head')
    <?php include(resource_path('views/auth.php')); ?>
@endsection
@section('content')

@if($userLevel == 'SU')

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
									<h5> Edit Regions </h5>
								</div>
							</div>
							@if(session('response'))
								<div class="alert alert-info">
									{{ session('response') }}
								</div>
							@endif
							
							@if($region)			
								<form method="POST" action=" {{ route('dataManagementRegionEditPost') }} ">
									@csrf
									{{ $render->regionEdit($region) }}
    								<div class='row justify-content-end mt-1'>
										<div class="col col-sm-3">
											<input type="submit" class="btn btn-primary mt-2" value="Edit" style="width: 100%;">
										</div>
									</div>
								</form>
							@else
								<div class="alert alert-warning">
  									There is no <strong> Regions </strong> to manage yet.
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
