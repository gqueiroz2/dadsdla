@extends('layouts.mirror')
@section('title', '@')
@section('head')
    <?php include(resource_path('views/auth.php')); ?>
@endsection
@section('content')

@if($userLevel == 'SU')
	<div class="container-fluid mt-5">
		<div class="row justify-content-center">
			<div class="col-sm-4">
				<div class="card">
					<div class="card-header">
						<center><h4> Data Management - <b> Agency / Client </b> </h4></center>
					</div>
					<div class="card-body">
						<div class="container-fluid">
							
							<form action="{{ route('clientAgencyExcelHandler') }}" method="POST" enctype="multipart/form-data">
							@csrf

								<div class="row justify-content-center">          
							 		<div class="col">		
										<div class="form-group">
											<label><b> Type: </b></label> 
											@if($errors->has('type'))
												<label style="color: red;">* Required</label>
											@endif
											{{$rAC->type()}}					
										</div>
									</div>
								</div>

								<div class="row justify-content-center fileContent" id="fileContent" style="display: none;">          
							 		<div class="col">		
										<div class="form-group">
											<label><b><span id="labelTypeGroup"></span></b></label><br>
											<input type="file" name="fileTypeGroup">               
								    	</div>
								    </div>
								</div>

								<div class="row justify-content-center fileContent" id="fileContent" style="display: none;">
								    <div class="col">		
										<div class="form-group">
											<label><b><span id="labelType"></span></b></label><br>
											<input type="file" name="fileType">               
								    	</div>
								    </div>
								</div>

								<div class="row justify-content-center fileContent" id="fileContent" style="display: none;">
								    <div class="col">		
										<div class="form-group">
											<label><b><span id="labelTypeUnit"></span></b></label><br>
											<input type="file" name="fileTypeUnit">               
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
@endif


<script type="text/javascript">
	
	$(document).ready(function(){
		$('#type').change(function(){
			var typeValue = $(this).val();
    		ajaxSetup();
			if (typeValue == "") {  			
				$('.fileContent').css('display','none');
				$('#labelTypeGroup').text('');
				$('#labelType').text('');
				$('#labelTypeUnit').text('');
			}else{
				$('.fileContent').css('display','block');
				var typeCapitalized = typeValue.charAt(0).toUpperCase() + typeValue.slice(1)
				$('#labelTypeGroup').text(typeCapitalized+' Group:');
				$('#labelType').text(typeCapitalized+':');
				$('#labelTypeUnit').text(typeCapitalized+' Unit:');
			}
        });
    });
</script>

@endsection