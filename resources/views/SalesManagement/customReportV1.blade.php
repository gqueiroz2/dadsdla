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
 	$bookings = $temp['bookings'];
	$targetGross = $temp['targetGross'];
	$targetNet = $temp['targetNet'];
?>

@if($userLevel == 'SU')
	<div class="container-fluid">
		<div class="row justify-content-center">
			<div class="col">
				<table class='table'>						
					<tr>
						<td colspan="5"><center> Bookings </center></td>
					</tr>
					<tr>
						<td> Region </td>
						<td> Year </td>
						<td> Month </td>
						<td> AE </td>
						<td> Booking Gross </td>
						<td> Booking Net </td>
					</tr>
					@for($t=0;$t< sizeof($bookings);$t++)
						<tr>
							<td> {{ $bookings[$t]['region'] }} </td>
							<td> {{ $bookings[$t]['year'] }} </td>
							<td> {{ $bookings[$t]['month'] }} </td>
							<td> {{ $bookings[$t]['salesRep'] }} </td>
							<td> {{ number_format( $bookings[$t]['bookingGross'] ) }} </td>
							<td> {{ number_format( $bookings[$t]['bookingNet'] ) }} </td>		
						</tr>
					@endfor
				</table>
			</div>
		</div>

		<div class="row justify-content-center">
			<div class="col">
				<table class='table'>						
					<tr>
						<td colspan="5"><center> Target Gross </center></td>
					</tr>
					<tr>
						<td> Region </td>
						<td> Year </td>
						<td> Month </td>
						<td> AE </td>						
						<td> Type of Value </td>
						<td> Value </td>
					</tr>
					@for($t=0;$t< sizeof($targetGross);$t++)
						<tr>
							<td> {{ $targetGross[$t]['region'] }} </td>
							<td> {{ $targetGross[$t]['year'] }} </td>
							<td> {{ $targetGross[$t]['month'] }} </td>
							<td> {{ $targetGross[$t]['salesRep'] }} </td>
							<td> {{ $targetGross[$t]['typeOfRevenue'] }} </td>
							<td> {{ number_format( $targetGross[$t]['value'] ) }} </td>		
						</tr>
					@endfor
				</table>
			</div>
		</div>

		<div class="row justify-content-center">
			<div class="col">
				<table class='table'>						
					<tr>
						<td colspan="5"><center> Target Net </center></td>
					</tr>
					<tr>
						<td> Region </td>
						<td> Year </td>
						<td> Month </td>
						<td> AE </td>						
						<td> Type of Value </td>
						<td> Value </td>
					</tr>
					@for($t=0;$t< sizeof($targetGross);$t++)
						<tr>
							<td> {{ $targetNet[$t]['region'] }} </td>
							<td> {{ $targetNet[$t]['year'] }} </td>
							<td> {{ $targetNet[$t]['month'] }} </td>
							<td> {{ $targetNet[$t]['salesRep'] }} </td>
							<td> {{ $targetNet[$t]['typeOfRevenue'] }} </td>
							<td> {{ number_format( $targetNet[$t]['value'] ) }} </td>		
						</tr>
					@endfor
				</table>
			</div>
		</div>
	</div>
		
@else
@endif
@endsection
