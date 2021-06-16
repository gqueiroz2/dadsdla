<table>						
	<tr>
		<td> Target Gross </td>
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
			<td> {{ $data['temp']['targetGross'][$t]['region'] }} </td>
			<td> {{ $data['temp']['targetGross'][$t]['year'] }} </td>
			<td> {{ $data['temp']['targetGross'][$t]['month'] }} </td>
			<td> {{ $data['temp']['targetGross'][$t]['salesRep'] }} </td>
			<td> {{ $data['temp']['targetGross'][$t]['typeOfRevenue'] }} </td>
			<td style="text-align: left;"> {{ number_format( $data['temp']['targetGross'][$t]['value'] ) }} </td>		
		</tr>
	@endfor
</table>