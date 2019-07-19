@extends('layouts.mirror')
@section('title', '@')

@section('head')
    <?php include(resource_path('views/auth.php')); ?>
@endsection

@section('content')

	@if($userLevel == 'SU')
		<div class="container-fluid">
			<div class="row">
				<div class="col">
					@if(!empty($inserted))
		  				<div class="alert alert-success"> {{ $inserted }}</div>
					@endif	
				</div>
			</div>		

			{{ $rS->base($con,$base,$table,$newValues,$dependencies,$region) }}

		</div>
	@endif

	<div id='vlau'></div>
	
	@if($newValues['clients'])
		@for($nv = 0; $nv < sizeof($newValues['clients']);$nv++)
			<script type="text/javascript">
				
				$(document).ready(function(){      
		  			$('#clients-{{$nv}}').change(function(){
			  			var clients = $(this).val();
					  	if(clients != ""){        			
		  					ajaxSetup();
						        $.ajax({
						        	url:"/ajax/checkElements/clientGroupByClient",
						        	method:"POST",
						        	data:{clients},
						        	success: function(output){
						          		//$('#clients-group').html(output);
						          		$('#clients-group-{{$nv}}').val(output);//.selectpicker('refresh');
						        	},
						        	error: function(xhr, ajaxOptions,thrownError){
						          		alert(xhr.status+" "+thrownError);
						        	}
						      	});
						}else{
					      	
						}
		  			});
		  		});
			</script>
		@endfor
	@endif


	@if($newValues['agencies'])
		@for($nv = 0; $nv < sizeof($newValues['agencies']);$nv++)
			<script type="text/javascript">
				
				$(document).ready(function(){      
		  			$('#agencies-{{$nv}}').change(function(){
			  			var agencies = $(this).val();
					  	if(agencies != ""){        			
		  					ajaxSetup();
						        $.ajax({
						        	url:"/ajax/checkElements/agencyGroupByAgency",
						        	method:"POST",
						        	data:{agencies},
						        	success: function(output){
						          		//$('#agencies-group').html(output);
						          		$('#agencies-group-{{$nv}}').val(output);//.selectpicker('refresh');

						        	},
						        	error: function(xhr, ajaxOptions,thrownError){
						          		alert(xhr.status+" "+thrownError);
						        	}
						      	});
						}else{
					      	
						}
		  			});
		  		});
			</script>
		@endfor
	@endif
@endsection

