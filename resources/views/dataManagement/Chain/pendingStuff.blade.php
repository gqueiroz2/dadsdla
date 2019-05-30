@extends('layouts.mirror')
@section('title', '@')
@section('head')
    <?php include(resource_path('views/auth.php')); ?>
@endsection
@section('content')
	<?php

		var_dump($newValues);

	?>
	@if($userLevel == 'SU')
		<div class="container-fluid">
			
			{{ $rS->base($conDLA,$base,$table,$newValues,$dependencies) }}


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

@endsection

