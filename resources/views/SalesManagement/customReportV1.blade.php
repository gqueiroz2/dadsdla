@extends('layouts.mirror')

@section('title', '@')

@section('head')
	<style type="text/css">		
		.button:focus{    
    		color:white;
		}
	</style>
    <?php include(resource_path('views/auth.php')); ?>
@endsection
@section('content')

<?php
 	//var_dump($temp);
?>
@if($userLevel == 'SU')
	
	<div class="container-fluid">
		<div class="row justify-content-center">
			<div class="col">
				<table class="table">						
					<tr>
						<td> Bookings </td>
					</tr>
					<tr>
						<td> Region </td>
						<td> Year </td>
						<td> Month </td>
						<td> Brand </td>
						<td> AE </td>
						<td> Target Value </td>
						<td> Booking Current Year </td>
						<td> Booking Previous Year </td>
					</tr>

					@for($m=0;$m< sizeof($temp);$m++)
						@if($temp[$m])
							@for($n=0;$n< sizeof($temp[$m]);$n++)
								<tr>
									<td> {{ $temp[$m][$n]['region'] }} </td>
									<td> 2021 </td>
									<td> {{ $temp[$m][$n]['month'] }} </td>
									<td> {{ $temp[$m][$n]['brand'] }} </td>
									<td> {{ $temp[$m][$n]['salesRep'] }} </td>
									<td style=" text-align: left;"> {{ number_format( $temp[$m][$n]['targetValue'] ) }} </td>
									<td style=" text-align: left;"> {{ number_format( $temp[$m][$n]['bookingsNetCurrentYear'] ) }} </td>
									<td style=" text-align: left;"> {{ number_format( $temp[$m][$n]['bookingsNetPreviousYear'] ) }} </td>
								</tr>
							@endfor
						@endif
					@endfor
				</table>

			</div>
		</div>
	</div>
		
@else
@endif

@endsection
