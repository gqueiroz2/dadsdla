@extends('layouts.mirror')

@section('title', '@')

@section('content')

	<div class="container-fluid">
		<div class="row justify-content-center">
			<div class="col-sm-8">
				<div class="card" style="margin-bottom:15%;">
					<div class="card-header">
						<center><h4> Data Management - <b> Edit Sales Representative Group </b> </h4></center>
					</div>
					<div class="card-body">
						<div class="container-fluid">
							<div class="row justify-content-center">
								<div class="col">
									<div class="row justify-content-center mt-1">
										<div class="col">
											<form method='Post' action="{{route('dataManagementSalesRepGroupEditFilter')}}">
												@csrf
												{{$render->filters($region)}}												
											</form>
										</div>
									</div>
									<br>
									@if($salesRepresentativeGroup)
										{{-- $render->salesRepGroupEdit($salesRepresentativeGroup,$region) --}}
										<form method="Post" action="{{ route('dataManagementSalesRepGroupEditFilter') }}">
											@csrf
											<div class="row justify-content-end mt-1">
												<div class="col col-sm-3">
													<input type="submit" class="btn btn-primary" value="Edit" style="width: 100%;">
												</div>
											</div>
										</form>
									@else
										<div class="alert alert-warning">
  											There is no <strong> Sales Representative Group </strong> to manage yet.
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
	
	<div id="vlau"></div>

@endsection
