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
						<center><h4> Data Management - <b> YTD Latam </b> </h4></center>
					</div>
					<div class="card-body">
						<div class="container-fluid">
							<div class="row justify-content-center">
								<div class="col">
									<h5> Add a Excel File </h5>
								</div>
							</div>

							<div class="row justify-content-center">
								<div class="col">
									@if(session('error'))
										<div class="alert alert-danger">
  											{{ session('error') }}
										</div>
									@endif

									@if(session('response'))
										<div class="alert alert-info">
  											{{ session('response') }}
										</div>
									@endif
								</div>
							</div>
							<form action="{{ route('fileUploadYtdLatam') }}" method="POST" enctype="multipart/form-data">
							@csrf
							 	<div class="row justify-content-center">          
							 		<div class="col">		
										<div class="form-group">
								        	<label for="exampleInputFile">File Upload</label>
								        	<div class="custom-file">                
												<input type="file" class="form-control" id="file" name="file">                
												<!--<label class="custom-file-label" for="file">Choose file</label>-->
											</div>  
								    	</div>
								    </div>
								</div>
								<div class="row justify-content-end">          
							 		<div class="col col-sm-6">		
								    	<button type="submit" class="btn btn-primary" style="width: 100%;">Submit</button>
									</form>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>{{--
	<div class="container-fluid">
		<div class="row">
			<div class="col">
				<table>
				@for($y=0;$y < sizeof($ytd);$y++)
					<tr>
						<td> {{$ytd[$y]['ID']}} </td>
						<td> {{$ytd[$y]['name']}} </td>
					</tr>
				@endfor
				</table>
				<table class="table" style="width: 100%;">
					<tr>
						<th> Region</th>
						<th> Year </th>
						<th> Month </th>
						<th> Brand </th>
						<th> Brand Feed </th>
						<th> Sales Rep </th>
						<th> Client </th>
						<th> Agency </th>
						<th> Client Product </th>
						<th> Order Reference </th>
						<th> Campaign Reference </th>
						<th> Spot Duration </th>
						<th> Impression Duration </th>
						<th> Num Spot </th>							
						<th> Currency </th>							
						<th> Gross Revenue </th>
						<th> Net Revenue </th>
						<th> Net Net Revenue </th>
						<th> Gross Revenue P-Rate </th>
						<th> Net P-Rate </th>
						<th> Net Net P-Rate </th>
					</tr>
					@for($y=0;$y < sizeof($ytd);$y++)
						<tr>
							<td> {{$ytd[$y]['region']}} </td>
							<td> {{$ytd[$y]['year']}} </td>
							<td> {{$ytd[$y]['month']}} </td>
							<td> {{$ytd[$y]['brand']}} </td>
							<td> {{$ytd[$y]['brandFeed']}} </td>
							<td> {{$ytd[$y]['salesRep']}} </td>
							<td> {{$ytd[$y]['client']}} </td>
							<td> {{$ytd[$y]['agency']}} </td>
							<td> {{$ytd[$y]['clientProduct']}} </td>
							<td> {{$ytd[$y]['orderReference']}} </td>
							<td> {{$ytd[$y]['campaignReference']}} </td>
							<td> {{$ytd[$y]['spotDuration']}} </td>
							<td> {{$ytd[$y]['impressionDuration']}} </td>
							<td> {{$ytd[$y]['numSpot']}} </td>							
							<td> {{$ytd[$y]['currency']}} </td>							
							<td> {{$ytd[$y]['grossRevenue']}} </td>
							<td> {{$ytd[$y]['netRevenue']}} </td>
							<td> {{$ytd[$y]['netNetRevenue']}} </td>
							<td> {{$ytd[$y]['grossRevenuePrate']}} </td>
							<td> {{$ytd[$y]['netPrate']}} </td>
							<td> {{$ytd[$y]['netNetPrate']}} </td>

						</tr>
					@endfor
				</table>
			</div>
		</div>
	</div>--}}
@else
@endif
@endsection
