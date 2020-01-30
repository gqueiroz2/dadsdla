<table>
	<tr>
		<th colspan="21" style="background-color: #0047b3;"> {{$data['region']}} - Insights - ({{strtoupper($data['currency'])}}/{{strtoupper($data['value'])}})</th>
	</tr>

	<tr style="background-color: #0047b3;">
		<td>Brand</td>";
		<td>Brand Feed</td>;
		<td>Sales Rep</td>;
		<td>Agency</td>;
		<td>Client</td>;
		<td>Month</td>;
		<td>Charge Type</td>;
		<td>Product</td>;
		<td>Campaign</td>;
		<td>Order Reference</td>;
		<td>Schedule Event</td>;
		<td>Spot Status</td>;
		<td>Date Event</td>;
		<td>Unit Start Time</td>;
		<td>Duration Spot</td>;
		<td>Copy Key</td>;
		<td>Media Item</td>;
		<td>Spot Type</td>;
		<td>Duration Impression</td>;
		<td>Num Spot</td>;
		<td>Revenue</td>;
	</tr>

	<tr style="background-color: #0f243e;">
		@for($t=0; $t < sizeof($data['total']); $t++)
			<td>Total</td>
			<td colspan="18"></td>	
			<td>{{$data['total'][$t]['averageNumSpot']}}</td>
			<td>{{$data['total'][$t]['sum'.($data['value']).'Revenue']}}</td>	
		@endfor
	</tr>

	@for($m=0;$m < sizeof($data['mtx']); $m++)
		<tr>
			<td>{{$data['mtx'][$m]['brand']}}</td>
			<td>{{$data['mtx'][$m]['brandFeed']}}</td>
			<td>{{$data['mtx'][$m]['salesRep']}}</td>
			<td>{{$data['mtx'][$m]['agency']}}</td>
			<td>{{$data['mtx'][$m]['client']}}</td>
			<td>{{$data['mtx'][$m]['month']}}</td>
			<td>{{$data['mtx'][$m]['chargeType']}}</td>
			<td>{{$data['mtx'][$m]['product']}}</td>
			<td>{{$data['mtx'][$m]['campaign']}}</td>
			<td>{{$data['mtx'][$m]['orderReference']}}</td>
			<td>{{$data['mtx'][$m]['scheduleEvent']}}</td>
			<td>{{$data['mtx'][$m]['spotStatus']}}</td>
			<td>{{$data['mtx'][$m]['dateEvent']}}</td>
			<td>{{$data['mtx'][$m]['unitStartTime']}}</td>
			<td>{{$data['mtx'][$m]['durationSpot']}}</td>
			<td>{{$data['mtx'][$m]['copyKey']}}</td>
			<td>{{$data['mtx'][$m]['mediaItem']}}</td>
			<td>{{$data['mtx'][$m]['spotType']}}</td>
			<td>{{$data['mtx'][$m]['durationImpression']}}</td>
			<td>{{$data['mtx'][$m]['numSpot']}}</td>
			<td>{{$data['mtx'][$m][$data['value'].'Revenue']}}</td>
		</tr>";
	@endfor
</table>