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
						<td> Date </td>
						<td> Currency </td>
						<td> Brand </td>
						<td> AE </td>
						<td> SF ID </td>
						<td> Client </td>
						<td> Client ID </td>
						<td> Agency </td>
						<td> Agency ID</td>
						<td> Booking Current Year </td>
						<td> Booking Previous Year </td>
					</tr>

					@for($m=0;$m< sizeof($temp);$m++)
						@if($temp[$m])
							@for($n=0;$n< sizeof($temp[$m]);$n++)
								<tr>
									<td> {{ $temp[$m][$n]['region'] }} </td>									
									<td> {{ $temp[$m][$n]['date'] }} </td>
									<td> {{ $temp[$m][$n]['currency'] }} </td>
									<td> {{ $temp[$m][$n]['brand'] }} </td>
									<td> {{ $temp[$m][$n]['salesRep'] }} </td>
									<td> {{ $temp[$m][$n]['salesRepSfID'] }} </td>
									
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
