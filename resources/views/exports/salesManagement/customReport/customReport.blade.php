<table>						
	<tr>
		<td> Bookings </td>
	</tr>
	<tr>
		<td> Region </td>
		<td> Year </td>
		<td> Month </td>
		<td> AE </td>
		<td> Booking Gross </td>
		<td> Booking Net </td>
	</tr>
	@for($t=0;$t< sizeof($data['temp']['bookings']);$t++)
		<tr>
			<td> {{ $data['temp']['bookings'][$t]['region'] }} </td>
			<td> {{ $data['temp']['bookings'][$t]['year'] }} </td>
			<td> {{ $data['temp']['bookings'][$t]['month'] }} </td>
			<td> {{ $data['temp']['bookings'][$t]['salesRep'] }} </td>
			<td style=" text-align: left;"> {{ number_format( $data['temp']['bookings'][$t]['bookingGross'] ) }} </td>
			<td style=" text-align: left;"> {{ number_format( $data['temp']['bookings'][$t]['bookingNet'] ) }} </td>		
		</tr>
	@endfor
</table>