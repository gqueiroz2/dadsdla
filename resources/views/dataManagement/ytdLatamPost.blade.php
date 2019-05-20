@extends('layouts.mirror')

@section('title', '@')

@section('head')
    <?php include(resource_path('views/auth.php')); ?>
@endsection
@section('content')

@if($userLevel == 'SU')

	<div class="container-fluid">
		<div class="row justify-content-center">
			<div class="col-sm-8">
				<div class="card">
					<div class="card-header">
						<center><h4> Data Management - <b> Check For Miss Matches </b> </h4></center>
					</div>
					<div class="card-body">
						<div class="container-fluid">
							<div class="row">
								<div class="col">
									<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#agencyGroupModal" style="width: 100%;">
										Create Agency Group
									</button>
								</div>

								<div class="col">
									<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#agencyModal" style="width: 100%;">
										Create Agency 
									</button>
								</div>
							</div>
							@if($agencyMissMatches)
								<div class="row justify-content-center mt-1">
									<div class="col">
										<div class="container-fluid">
											<div class="row justify-content-center">
												<div class="col">
													<span style="font-size: 16px;"><b> Relantionship Between Agencies </b></span>
												</div>
											</div>
											<div class="row">
												<div class="col"><b> Agency </b></div>
												<div class="col"><b> Agency Unit </b></div>
											</div>
											@for($a=0;$a < sizeof($agencyMissMatches);$a++)
												<div class="row">
													<div class="col">
														<select name="agency">
															<option value=""> Select </option>
															@for($aa=0;$aa<sizeof($agency);$aa++)
																<option value="{{ $agency[$aa]['agency'] }}"> {{ $agency[$aa]['agency'] }}</option>
															@endfor															
														</select>
													</div>
													<div class="col"> {{ $agencyMissMatches[$a] }} </div>
												</div>
											@endfor
										</div>
									</div>
								</div>	
							@endif

							<br><hr><br>

							@if($clientMissMatches)
								
								<div class="row">
									<div class="col">
										<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#clientGroupModal" style="width: 100%;">
											Create Client Group
										</button>
									</div>

									<div class="col">
										<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#clientModal" style="width: 100%;">
											Create Client 
										</button>
									</div>
								</div>

								<div class="row justify-content-center mt-1">
									<div class="col">
										<div class="container-fluid">
											<div class="row justify-content-center">
												<div class="col">
													<span style="font-size: 16px;"><b> Relantionship Between Clients </b></span>
												</div>
											</div>
											<div class="row">
												<div class="col"><b> Client </b></div>
												<div class="col"><b> Client Unit </b></div>
											</div>
											@for($c=0;$c < sizeof($clientMissMatches);$c++)
												<div class="row">
													<div class="col">
														<select name="client" class="form-control">
															<option value=""> Select </option>
															@for($cc=0;$cc<sizeof($client);$cc++)
																<option value="{{ $client[$cc]['client'] }}"> {{ $client[$cc]['client'] }}</option>
															@endfor															
														</select>
													</div>
													<div class="col"> {{ $clientMissMatches[$c] }} </div>
												</div>
											@endfor
										</div>
									</div>
								</div>	
							@endif

						</div>
					</div>
				</div>
			</div>
		</div>
	</div>


	<div class="modal fade" id="agencyGroupModal" tabindex="-1" role="dialog" aria-labelledby="agencyGroupModalLabel" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title"> Create Agency Group </h5>
					<button type="button" class="close" data-dismiss="modal" arial-label="Close">
						<span aria-hidden="true"> &times; </span>
					</button>
				</div>				
				<div class="modal-body">
					<div class="container-fluid">
						<div class="row justify-content-center">
							<div class="col">
								<span style="font-weight: bold;"> Region </span>
							</div>

							<div class="col">
								<span style="font-weight: bold;"> Agency Group </span>
							</div>
						</div>
			<form method="POST" id="fileUploadAgencyGroupAdd" action="{{ route('fileUploadAgencyGroupAdd') }}">
			@csrf
						<div class="row justify-content-center">
							<div class="col">
								<select name="region" class="form-control">
									<option value=""> Select </option>
									@for($i=0; $i< sizeof($region);$i++)
										<option value="{{$region[$i]['id']}}"> {{$region[$i]['name']}} </option>
									@endfor
								</select>
							</div>
							<div class="col">
								<input type="text" class="form-control" name="createAgencyGroup">
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        			<button type="submit" class="btn btn-primary"> Create Agency Group </button>					
				</div>
			</form>
			</div>
		</div>
	</div>

	<div class="modal fade" id="agencyModal" tabindex="-1" role="dialog" aria-labelledby="agencyModalLabel" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title"> Create Agency </h5>
					<button type="button" class="close" data-dismiss="modal" arial-label="Close">
						<span aria-hidden="true"> &times; </span>
					</button>
				</div>				
				<div class="modal-body">
					<div class="container-fluid">
						<div class="row justify-content-center">
							<div class="col">
								<span style="font-weight: bold;"> Agency Group </span>
							</div>

							<div class="col">
								<span style="font-weight: bold;"> Agency </span>
							</div>
						</div>
			<form method="POST" id="fileUploadAgencyAdd" action="{{ route('fileUploadAgencyAdd') }}">
			@csrf
							<div class="row justify-content-center">
								<div class="col">
									<select name="agencyGroup" class="form-control">
										<option value=""> Select </option>
										@for($i=0; $i< sizeof($agencyGroup);$i++)
											<option value="{{$agencyGroup[$i]['id']}}"> {{$agencyGroup[$i]['agencyGroup']}} </option>
										@endfor
									</select>
								</div>
								<div class="col">
									<input type="text" class="form-control" name="createAgency">
								</div>
							</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        			<button type="submit" class="btn btn-primary" onclick="fileUploadAgencyAddGroup()"> Create Agency Group </button>					
				</div>
			</form>
			</div>
		</div>
	</div>

	<script type="text/javascript">
			
		function fileUploadAgencyAdd(){
			document.getElementById('fileUploadAgencyAdd').submit();
		}


	</script>
@else
@endif
@endsection
