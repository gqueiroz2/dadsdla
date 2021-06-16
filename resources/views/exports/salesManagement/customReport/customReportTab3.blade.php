<table>						
	<tr>
		<td> Target Net </td>
	</tr>
	<tr>
		<td> Region </td>
		<td> Year </td>
		<td> Month </td>
		<td> AE </td>						
		<td> Type of Value </td>
		<td> Value </td>
	</tr>
	@for($t=0;$t< sizeof($data['temp']['targetGross']);$t++)
		<tr>
			<td> {{ $data['temp']['targetNet'][$t]['region'] }} </td>
			<td> {{ $data['temp']['targetNet'][$t]['year'] }} </td>
			<td> {{ $data['temp']['targetNet'][$t]['month'] }} </td>
			<td> {{ $data['temp']['targetNet'][$t]['salesRep'] }} </td>
			<td> {{ $data['temp']['targetNet'][$t]['typeOfRevenue'] }} </td>
			<td style="text-align: left;"> {{ number_format( $data['temp']['targetNet'][$t]['value'] ) }} </td>		
		</tr>
	@endfor
</table>