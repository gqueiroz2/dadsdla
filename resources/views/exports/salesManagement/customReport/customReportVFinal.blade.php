<table>						
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

	@for($m=0;$m< sizeof($data['temp']);$m++)
		@if($data['temp'][$m])
			@for($n=0;$n< sizeof($data['temp'][$m]);$n++)
				<tr>
					<td> {{ $data['temp'][$m][$n]['region'] }} </td>
					<td> 2021 </td>
					<td> {{ $data['temp'][$m][$n]['month'] }} </td>
					<td> {{ $data['temp'][$m][$n]['brand'] }} </td>
					<td> {{ $data['temp'][$m][$n]['salesRep'] }} </td>
					<td style=" text-align: left;"> {{ number_format( $data['temp'][$m][$n]['targetValue'] ) }} </td>
					<td style=" text-align: left;"> {{ number_format( $data['temp'][$m][$n]['bookingsNetCurrentYear'] ) }} </td>
					<td style=" text-align: left;"> {{ number_format( $data['temp'][$m][$n]['bookingsNetPreviousYear'] ) }} </td>
				</tr>
			@endfor
		@endif
	@endfor
</table>