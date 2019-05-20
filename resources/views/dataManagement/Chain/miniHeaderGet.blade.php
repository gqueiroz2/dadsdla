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
						<center><h4> Data Management - <b> Chain Mini-Header </b> </h4></center>
					</div>
					<div class="card-body">
						<div class="container-fluid">
							
							<div class="row">
								<div class="col">
									<center><span style="font-size: 18px;"> FIRST CHAIN </span></center>
								</div>
							</div>

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
							<form action="{{ route('fileUploadMiniHeaderPost') }}" method="POST" enctype="multipart/form-data">
							@csrf
							 	<div class="row justify-content-center">          
							 		<div class="col">		
										<div class="form-group">
											<input type="file" name="file">                
								    	</div>
								    </div>
								</div>

								<div class="row justify-content-center">          
							 		<div class="col">		
										<div class="form-group">

											@if(session('firstChainResponse'))
												<div class="alert alert-danger">
		  											{{ session('firstChainResponse') }}
												</div>
											@endif

										</div>
									</div>
								</div>

								<div class="row justify-content-end">          
							 		<div class="col">		
								    	<button type="submit" class="btn btn-primary" style="width: 100%;">Submit</button>
									</div>
								</div>
							</form>
							<br><hr>
							<div class="row">
								<div class="col">
									<center><span style="font-size: 18px;"> SECOND CHAIN </span></center>
								</div>
							</div>
							<form action="{{ route('miniHeaderSecondChain') }}" method="POST">
							@csrf
								<div class="row justify-content-center">          
							 		<div class="col">		
										<div class="form-group">
											<input type="hidden" name="table" value="mini_header">                
								    	</div>
								    </div>
								</div>

								<div class="row justify-content-center">          
							 		<div class="col">		
										<div class="form-group">

											@if(session('secondChainResponse'))
												<div class="alert alert-danger">
		  											{{ session('secondChainResponse') }}
												</div>
											@endif

										</div>
									</div>
								</div>

								<div class="row justify-content-end">          
							 		<div class="col">		
								    	<button type="submit" class="btn btn-primary" style="width: 100%;">Submit</button>
									</div>
								</div>
							</form>

							<br><hr>
							<div class="row">
								<div class="col">
									<center><span style="font-size: 18px;"> THIRD CHAIN </span></center>
								</div>
							</div>
							<form action="{{ route('miniHeaderThirdChain') }}" method="POST">
							@csrf
								<div class="row justify-content-center">          
							 		<div class="col">		
										<div class="form-group">
											<input type="hidden" name="table" value="mini_header">                
								    	</div>
								    </div>
								</div>

								<div class="row justify-content-center">          
							 		<div class="col">		
										<div class="form-group">

											@if(session('secondChainResponse'))
												<div class="alert alert-danger">
		  											{{ session('secondChainResponse') }}
												</div>
											@endif

										</div>
									</div>
								</div>

								<div class="row justify-content-end">          
							 		<div class="col">		
								    	<button type="submit" class="btn btn-primary" style="width: 100%;">Submit</button>
									</div>
								</div>
							</form>
							<br><hr>
							<div class="row">
								<div class="col">
									<center><span style="font-size: 18px;"> THIRD TO DLA </span></center>
								</div>
							</div>
							<form action="{{ route('miniHeaderThirdToDLA') }}" method="POST">
							@csrf
								<div class="row justify-content-center">          
							 		<div class="col">		
										<div class="form-group">
											<input type="hidden" name="table" value="mini_header">                
								    	</div>
								    </div>
								</div>
								

								<div class="row justify-content-end">          
							 		<div class="col">		
								    	<button type="submit" class="btn btn-primary" style="width: 100%;">Submit</button>
									</div>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
@else
@endif
@endsection
