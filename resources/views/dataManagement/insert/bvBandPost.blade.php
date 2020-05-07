@extends('layouts.mirror')

@section('title', '@')
@section('head')
    <?php include(resource_path('views/auth.php')); ?>
@endsection
@section('content')
	<div class="container-fluid">
		<div class="row justify-content-center">
			<div class="col-sm-6">
				<div class="card">
					<div class="card-header">
						<center><h4> Data Management - <b> Insert BV Band </b> </h4></center>
					</div>
					<div class="card-body">
						<div class="container-fluid">
							
							<div class="row justify-content-center">
								<div class="col">
									<center>
										<h5> Check for Adds </h5>
									</center>
								</div>
							</div>

							<div class="row justify-content-center">
								<div class="col">
									<button class="btn btn-primary" id="agencyGroupsCheck" style="width: 100%;"> Check Agency Groups </button>
								</div>
							</div>

							
							<div class="row justify-content-center mt-2">
								<div class="container">
									<div class="row justify-content-center mt-2">
										<div class="col">
											<span id="vlau"></span>	
										</div>
									</div>
								</div>
							</div>							
							
							
							
							
							<br><hr>								
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

<script>
	$(document).ready(function(){
		$('#agencyGroupsCheck').click(function(){	    	
		    $.ajax({
			    url:"/dataManagement/agencyGroupCheck",
			    method:"POST",			    
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

