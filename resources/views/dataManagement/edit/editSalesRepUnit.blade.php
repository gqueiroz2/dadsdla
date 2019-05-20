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
						<center><h4> Data Management - <b> Edit Sales Rep. Unit</b> </h4></center>
					</div>
					<div class="card-body">
						<div class="container-fluid">
							<div class="row justify-content-center">
								<div class="col">
									@if($salesRep)
										<form method="POST" action="{{route('salesRepUnitEditFilter')}}">
											@csrf
											{{$render->filterBySalesRep($salesRep)}}
										</form>
										<br>
										<br>
									@endif
									@if($salesRepUnit)
										<form method="POST" action="{{route('salesRepUnitEditFilter')}}">
											@csrf
											{{$render->salesRepUnitEdit($salesRepUnit,$salesRep,$origin)}}
											<div class="row justify-content-end mt-2">
												<div class="col">
													<input type="submit" class="btn btn-primary" value="Edit" style="width: 100%;">
												</div>
											</div>
										</form>
									@else
										<div class="alert alert-warning">
  											There is no <strong> Sales Rep. Unit </strong> to manage yet.
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
