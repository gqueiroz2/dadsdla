<table>						
	<tr>
		<td> Region </td>						
		<td> Date </td>
		<td> Currency </td>
		<td> Brand </td>
		<td> AE </td>
		<td> SF ID </td>
		<td> Target </td>
		<td> Booking Current Year </td>
		<td> Booking Previous Year </td>
	</tr>

	@for($m=0;$m< sizeof($data['temp']);$m++)
		@if($data['temp'][$m])
			@for($n=0;$n< sizeof($data['temp'][$m]);$n++)
				<tr>
					<td> {{ $data['temp'][$m][$n]['region'] }} </td>									
					<td> {{ date("m-d-Y H:i:s") }} </td>
					<td> USD </td>
					<td> {{ $data['temp'][$m][$n]['brand'] }} </td>
					<td> {{ $data['temp'][$m][$n]['salesRep'] }} </td>
					<td> {{ $data['temp'][$m][$n]['salesRepSfID'] }} </td>
					<td> {{ $data['temp'][$m][$n]['targetValue'] }} </td>
					<td style=" text-align: left;"> {{ number_format( $data['temp'][$m][$n]['bookingsNetCurrentYear'] ) }} </td>
					<td style=" text-align: left;"> {{ number_format( $data['temp'][$m][$n]['bookingsNetPreviousYear'] ) }} </td>
				</tr>
			@endfor
		@endif
	@endfor
</table>