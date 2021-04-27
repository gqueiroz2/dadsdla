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
						<center><h4> Data Management - <b> Chain X </b> </h4></center>
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
									<h6> Add a Excel File </h6>
								</div>
							</div>

							
							<form action="{{ route('ytdFirstChain') }}" method="POST" enctype="multipart/form-data">
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
										@if(session('firstChainError'))
											<div class="alert alert-danger">
	  											{{ session('firstChainError') }}
											</div>
										@endif

										@if(session('firstChainComplete'))
											<div class="alert alert-info">
	  											{{ session('firstChainComplete') }}
											</div>
										@endif
									</div>
								</div>
								
								<div class="row justify-content-center">          
							 		<div class="col">		
										<div class="form-group">
											{{$rC->report()}}					
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
									<center><span style="font-size: 18px;"> CHECK NEW ELEMENTS </span></center>
								</div>
							</div>
							<form action="{{ route('ytdCheckElementsPost') }}" method="POST">
							@csrf
								<div class="row justify-content-center">          
							 		<div class="col">		
										<div class="form-group">
											<input type="hidden" name="table" value="ytd">                
								    	</div>
								    </div>
								</div>

								<div class="row justify-content-center">
									<div class="col">
										@if(session('checkElementsError'))
											<div class="alert alert-danger">
													{{ session('checkElementsError') }}
											</div>
										@endif

										@if(session('checkElementsComplete'))
											<div class="alert alert-info">
													{{ session('checkElementsComplete') }}
											</div>
										@endif
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
							<form action="{{ route('ytdSecondChain') }}" method="POST">
							@csrf
								<div class="row justify-content-center">          
							 		<div class="col">		
										<div class="form-group">
											<input type="hidden" name="table" value="ytd">
								    	</div>
								    </div>
								</div>

								<div class="row justify-content-center">
									<div class="col">
										@if(session('secondChainError'))
											<div class="alert alert-danger">
													{{ session('secondChainError') }}
											</div>
										@endif

										@if(session('secondChainComplete'))
											<div class="alert alert-info">
													{{ session('secondChainComplete') }}
											</div>
										@endif
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
							<form action="{{ route('ytdThirdChain') }}" method="POST">
							@csrf

								<div class="row justify-content-center">
									<div class="col">
										@if(session('thirdChainError'))
											<div class="alert alert-danger">
	  											{{ session('thirdChainError') }}
											</div>
										@endif

										@if(session('thirdChainComplete'))
											<div class="alert alert-info">
	  											{{ session('thirdChainComplete') }}
											</div>
										@endif
									</div>
								</div>

								<div class="row justify-content-center">          
							 		<div class="col">		
										<div class="form-group">
											<input type="hidden" name="table" value="ytd">                
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
							<form action="{{ route('ytdThirdToDLA') }}" method="POST">
							@csrf
								<div class="row justify-content-center">
									<div class="col">
										@if(session('lastChainError'))
											<div class="alert alert-danger">
	  											{{ session('lastChainError') }}
											</div>
										@endif

										@if(session('lastChainComplete'))
											<div class="alert alert-info">
	  											{{ session('lastChainComplete') }}
											</div>
										@endif
									</div>
								</div>

								<div class="row justify-content-center">          
							 		<div class="col">		
										<div class="form-group">
											{{$rC->year()}}					
										</div>
									</div>
								</div>

								<div class="row justify-content-end">
									<div class="col col-sm-6">
										<div class="form-inline" style="float:right;width:100%;">
											<div class="container">
												<div class="row">
													<div class="col">
														<input type="radio" name="truncate" value="1"> Yes <br>
													</div>
													<div class="col">
														<input type="radio" name="truncate" value="0" checked> No 
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>

								<div class="row justify-content-center">          
							 		<div class="col">		
										<div class="form-group">
											<input type="hidden" name="table" value="ytd">                
								    	</div>
								    </div>
								</div>								

								<div class="row justify-content-end">          
							 		<div class="col">		
								    	<button type="submit" class="btn btn-primary" style="width: 100%;">Submit</button>
									</div>
								</div>
							</form>


							<div class="row">
								<div class="col">
									<center><span style="font-size: 18px;"> FIX SALES FORCE </span></center>
								</div>
							</div>
							<form action="{{ route('fixCRM') }}" method="POST">
							@csrf
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
