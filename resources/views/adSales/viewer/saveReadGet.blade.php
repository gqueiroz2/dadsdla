@extends('layouts.mirror')
@section('title', '@')
@section('head')
    <?php 
    	include(resource_path('views/auth.php')); 
    	$today = date("Y-m-d");
    ?>
@endsection
@section('content')

@if($userLevel == 'SU')
	<div class="container-fluid">
		<div class="row justify-content-center mt-3"> 
			<div class="col-sm-6">
				<div class="card">
					<div class="card-header">
						<center><h4> Save CMAPS Read </h4></center>
					</div>
					<div class="card-body">
						<div class="container-fluid">
													
							<form action="{{ route('saveCMAPSReadPost') }}" runat="server"  onsubmit="ShowLoading()" method="POST" enctype="multipart/form-data">
							@csrf
							 	<div class="row justify-content-center">          
							 		<div class="col">		
										<div class="form-group">
											<input class="form-control" type="date" name="readDate" value="{{$today}}">
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
