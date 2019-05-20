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
				<div class="card" style="margin-bottom:15%;">
					<div class="card-header">
						<center><h4> Data Management - <b> Sales Rep. </b> </h4></center>
					</div>
					<div class="card-body">
						<div class="container-fluid">
							<div class="row justify-content-center">
								<div class="col align-self-center">
									<h5> Edit / Management Sales Rep. </h5>
								</div>
							</div>
							
							<div class="row justify-content-center">
								<div class="col">
									@if($region)
										<form method="POST" action="{{route('dataManagementSalesRepEditFilter')}}">
											@csrf
											{{$render->filters($region)}}
										</form>
										<br>
									@endif
									@if($salesRep)

										<form method="POST" action="{{route('dataManagementSalesRepEditFilter')}}">
											@csrf
											{{$render->salesRepEdit($salesRep,$region,$salesGroup)}}										
											<div class="row justify-content-end mt-2">
												<div class="col">
													<input type="submit" class="btn btn-primary" value="Edit" style="width: 100%;">
												</div>
											</div>
										</form>
									@else
										<div class="alert alert-warning">
  											There is no <strong> Sales Rep. </strong> to manage yet.
										</div>
									@endif		
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
