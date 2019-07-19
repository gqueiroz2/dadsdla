@extends('layouts.mirror')

@section('title', '@')

@section('head')

	<style type="text/css">		
		
		.button:focus{    
    		color:white;
		}

	</style>
    <?php 
    	include(resource_path('views/auth.php'));
    ?>

@endsection


@section('content')
@if($userLevel == 'SU')
	<div class="container-fluid">
		<div class="row justify-content-center">
			<div class="col-sm">
				<div class="card">

					<div class="card-header">
						<center>
							<span style="font-size:22px;"><b> RelationShip Agency - Select a Region </b></span>	
						</center>
							<form method="POST" action="{{route('relationshipAgencyPost')}}" runat="server"  onsubmit="ShowLoading()">
							@csrf
								<div class="row">
									<div class="col-sm">
										<label class="labelLeft"><span class="bold"> Region: </span></label>
										<select name="region" class="form-control" style="width: 100%;">
											<option value=""> Select </option>
											@for($r=0;$r <sizeof($region);$r++)
												<option value="{{$region[$r]['id']}}"> {{$region[$r]['name']}} </option>
											@endfor
										</select>
									</div>
								</div>
								<div class="row justify-content-center mt-2">
									<?php
										$alphabet = array("%","A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T","U","V","X","W","Y","Z");
									?>
									@for($a = 0 ; $a < sizeof($alphabet);$a++)

										<button class="btn btn-default" name="alphabetLetter" value="{{ $alphabet[$a] }}"> {{ $alphabet[$a] }} </button>

									@endfor
								</div>
							</form>	
					</div>

					<div class="card-body">
						<div class="container-fluid">
								

							<form method="POST" action="{{route('relationshipUpdateAgency')}}" runat="server"  onsubmit="ShowLoading()">
							@csrf
								{{$render->base($agencies,$agency)}}
							</form>
							
						</div>							
					</div>

				</div>
			</div>
		</div>
	</div>

	<div id="vlau"></div>

	@for( $a=0; $a<sizeof($agencies); $a++)
		<script type="text/javascript">
	
		$(document).ready(function(){
			$("#newAgency-{{$a}}").change(function(){
			    var newAgency = $('#newAgency-{{$a}}').val();
			    
			    ajaxSetup();

			    $.ajax({
			      	url:"/ajax/relationship/agencyGroupByNewAgency",
			      	method:"POST",
			      	data:{newAgency},
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
	@endfor

@endif
@endsection
