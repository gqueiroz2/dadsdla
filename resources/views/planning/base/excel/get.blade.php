@extends('layouts.planningMirror')
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
						<center><h4> Roll Out </h4></center>
					</div>
					<div class="card-body">
						<div class="container-fluid">
							
							<div class="row">
								<div class="col">
									<center><span style="font-size: 18px;"> &nbsp; </span></center>
								</div>
							</div>

							<div class="row justify-content-center">
								<div class="col">
									<span style="font-size: 16px;">
										<b> Add a Excel File </b>
									</span>
									@if($errors->has('file'))
										<span style="color: red;">* Required</span>
									@endif									
								</div>
							</div>

							
							<form action="{{ route('rollOutExcelP') }}" runat="server"  onsubmit="ShowLoading()" method="POST" enctype="multipart/form-data">
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
											<label class="labelLeft"><span class="bold"> Brand: </span></label>
											@if($errors->has('brand'))
												<label style="color: red;">* Required</label>
											@endif
											{{$rP->brandSS($brand)}}
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

								<div class="row justify-content-end">          
							 		<div class="col">		
								    	<button type="submit" class="btn btn-primary" style="width: 100%;">Submit</button>
									</div>
								</div>
							</form>
							<br><hr>

							
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
@else
@endif


<script type="text/javascript">
	$(document).ready(function(){
		$('#PedingStuffByRegions').click( function() {

		    var tableToCheck = $('#tableToCheck').val();

		    ajaxSetup();
		    $.ajax({
                url:"/checkElements/PedingStuffByRegions",
                method:"POST",
                data:{tableToCheck},
                  success: function(output){
                    $('#vlau').html(output);
                  },
                  error: function(xhr, ajaxOptions,thrownError){
                    alert(xhr.status+" "+thrownError);
                }
            });
		});
	});
</script>

@endsection
