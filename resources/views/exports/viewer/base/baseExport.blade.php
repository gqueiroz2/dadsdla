@if($data['source'] == 'SF')
	$data['source'] = 'SalesForce';
@elseif($data['source'] == 'FW')
	$data['source'] == 'FreeWheel';
@elseif($data['source'] == 'CMAPS')
	$data['source'] == 'Cmaps';
@elseif($data['source'] == 'IBMS/BTS')
	$data['source'] == 'BTS';
@endif

<table>
	<tr>
		<th style="background-color: #0047b3;" colspan="14"> {{$data['region']}} - Viewer {{$data['source']}} {{$data['year']}} - ({{$data['currency']}}/{{$data['value']}})</th>		
	</tr>

	@if($data['source'] == 'Cmaps')
		<tr>
			<td style="background-color: #e6e6e6;">Map Number</td>
			<td style="background-color: #e6e6e6;">Pi Number</td>
			<td style="background-color: #e6e6e6;">Month</td>
			<td style="background-color: #e6e6e6;">Brand</td>
			<td style="background-color: #e6e6e6;">Sales Rep</td>
			<td style="background-color: #e6e6e6;">Agency</td>
			<td style="background-color: #e6e6e6;">Client</td>
			<td style="background-color: #e6e6e6;">Product</td>
			<td style="background-color: #e6e6e6;">Segment</td>
			<td style="background-color: #e6e6e6;">Media Type</td>
			<td style="background-color: #e6e6e6;">Discount</td>
			<td style="background-color: #e6e6e6;">Sector</td>
			<td style="background-color: #e6e6e6;">Category</td>
			<td style="background-color: #e6e6e6;">Revenue</td>
		</tr>
	@endif
</table>