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
						<center><h4> Data Management - <b> Insert AVB </b> </h4></center>
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

							<form method="POST" action="{{ route('insertAgencyGroupBV') }}" runat="server" onsubmit="ShowLoading()">
							@csrf
							

								<div class="row justify-content-center mt-2">
									<div class="container">
										<div class="row justify-content-center mt-2">
											<div class="col">
												<span id="checkAgencyGroup"></span>	
											</div>
											
										</div>
									</div>
								</div>	
								<div class="row justify-content-center mt-2" id="submitCheckAgencyGroup" style="display:none;">
									<div class="col">
										<label class="labelLeft"><span class="bold"> &nbsp; </span> </label>
										<input type="submit" value="Generate" class="btn btn-primary" style="width: 100%;">
									</div>						
								</div>
							</form>

							<form method="POST" action="{{ route('insertBvBandAfterCheck') }}" runat="server" onsubmit="ShowLoading()">
								<div class="row justify-content-center mt-2" id="insertBvBandAfterCheckLabel" style="display:none;">
									<div class="col">
										<center><h6> Data Management - <b> Continue Insert AVB </b> </h6></center>
									</div>
								</div>
								<div class="row justify-content-center mt-2" id="insertBvBandAfterCheck" style="display:none;">
									<div class="col">
										<input type="hidden" value="{{base64_encode(json_encode($ins))}}" name="insert">
										<label class="labelLeft"><span class="bold"> &nbsp; </span> </label>
										<input type="submit" value="Generate" class="btn btn-primary" style="width: 100%;">
									</div>						
								</div>
            				@csrf

            				</form>
							
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
			          $('#checkAgencyGroup').html(output);
			          	var seeIfShow = $("#forward").val();

			          	if (seeIfShow == 0) 
				    		$("#submitCheckAgencyGroup").css("display", "block");
				    	else{
				    		$("#insertBvBandAfterCheck").css("display", "block");
				    		$("#insertBvBandAfterCheckLabel").css("display", "block");
				    	}

			        },
			        error: function(xhr, ajaxOptions,thrownError){
			          alert(xhr.status+" "+thrownError);
			    }
		    });
		    
		    
				    

		}); 
  	});

</script>

@endsection

