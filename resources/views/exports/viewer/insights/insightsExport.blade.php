<table>
	<tr>
		<th colspan="11" style="background-color: #0047b3;"> {{$data['region']}} - Insights - ({{strtoupper($data['currency'])}})</th>
	</tr>

	<tr>
		<td style="background-color: #0047b3;">Brand</td>
		<td style="background-color: #0047b3;">Sales Rep</td>
		<td style="background-color: #0047b3;">Month</td>
		<td style="background-color: #0047b3;">Year</td>
		<td style="background-color: #0047b3;">Client</td>
		<td style="background-color: #0047b3;">Agency</td>
		<td style="background-color: #0047b3;">Product</td>
		<td style="background-color: #0047b3;">Program</td>
		<td style="background-color: #0047b3;">Num Spot</td>
		<td style="background-color: #0047b3;">Revenue</td>
		<td style="background-color: #0047b3;">Net Revenue</td>
	</tr>

	<tr>
		@for($t=0; $t < sizeof($data['total']); $t++)
			<td style="background-color: #0f243e;">Total</td>
			<td colspan="7" style="background-color: #0f243e;"></td>	
			<td style="background-color: #0f243e;">{{$data['total'][$t]['averageNumSpot']}}</td>
			<td style="background-color: #0f243e;">{{$data['total'][$t]['sumGrossRevenue']}}</td>
			<td style="background-color: #0f243e;">{{$data['total'][$t]['sumNetRevenue']}}</td>	
		@endfor
	</tr>

	@for($m=0;$m < sizeof($data['mtx']); $m++)
		<tr>
			<td>{{$data['mtx'][$m]['brand']}}</td>
			<td>{{$data['mtx'][$m]['salesRep']}}</td>
			<td>{{$data['mtx'][$m]['month']}}</td>
			<td>{{$data['mtx'][$m]['year']}}</td>
			<td>{{$data['mtx'][$m]['client']}}</td>
			<td>{{$data['mtx'][$m]['agency']}}</td>			
			<td>{{$data['mtx'][$m]['product']}}</td>
			<td>{{$data['mtx'][$m]['scheduleEvent']}}</td>
			<td>{{$data['mtx'][$m]['numSpot']}}</td>
			<td>{{$data['mtx'][$m]['grossRevenue']}}</td>
			<td>{{$data['mtx'][$m]['netRevenue']}}</td>

		</tr>";
	@endfor
</table>