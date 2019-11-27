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
		<th style="background-color: #0047b3;" colspan="14"> {{$data['region']}} - Viewer {{$data['source']}} {{$data['year']}} - ({{strtoupper($data['currency'])}}/{{strtoupper($data['value'])}})</th>		
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

		<tr>
			@for($t=0;$t <sizeof($data['total']);$t++)
				<td style="background-color: #0f243e;">Total</td>
				<td style="background-color: #0f243e;" colspan="9"></td>
				<td style="background-color: #0f243e;">{{$data['total'][$t]['averageDiscount']}}%</td>
				<td style="background-color: #0f243e;" colspan="2"></td>
				@if($data['value'] == 'gross')
					<td style="background-color: #0f243e;">{{$data['total'][$t]['sumGrossRevenue']}}</td>
				@else
					<td style="background-color: #0f243e;">{{$data['total'][$t]['sumNetRevenue']}}</td>
				@endif
			@endfor
		</tr>

		<tr>
			@for($m=0;$m <sizeof($data['mtx']))
				@if($m == sizeof($data['mtx'])-1)
					<tr>
						<td style="background-color: #f9fbfd;">{{$data['mtx'][$m]['mapNumber']}}</td>
						<td style="background-color: #f9fbfd;">{{$data['mtx'][$m]['piNumber']}}</td>
						<td style="background-color: #f9fbfd;">{{$data['mtx'][$m]['month']}}</td>
						<td style="background-color: #f9fbfd;">{{$data['mtx'][$m]['brand']}}</td>
						<td style='background-color: #f9fbfd;'>{{$data['mtx'][$m]['salesRep']}}</td>
						<td style="background-color: #f9fbfd;">{{$data['mtx'][$m]['agency']}}</td>
						<td style="background-color: #f9fbfd;">{{$data['mtx'][$m]['client']}}</td>
						<td style="background-color: #f9fbfd;">{{$data['mtx'][$m]['product']}}</td>
						<td style="background-color: #f9fbfd;">{{$data['mtx'][$m]['segment']}}</td>
						<td style="background-color: #f9fbfd;">{{$data['mtx'][$m]['mediaType']}}</td>
						<td style="background-color: #f9fbfd;">{{$data['mtx'][$m]['discount']}}</td>
						<td style="background-color: #f9fbfd;">{{ucwords(strtolower($data['mtx'][$m]['sector']))}}</td>
						<td style="background-color: #f9fbfd;">{{ucwords(strtolower($data['mtx'][$m]['category']))}}</td>
						@if($data['value'] == 'gross')
							<td style="background-color: #f9fbfd;">{{$data['mtx'][$m]['grossRevenue']}}</td>
						@else
							<td style="background-color: #f9fbfd;">{{$data['mtx'][$m]['netRevenue']}}</td>
						@endif
					</tr>	
				@else
					<tr>
						<td style="background-color: #c3d8ef;">{{$data['mtx'][$m]['mapNumber']}}</td>
						<td style="background-color: #c3d8ef;">{{$data['mtx'][$m]['piNumber']}}</td>
						<td style="background-color: #c3d8ef;">{{$data['mtx'][$m]['month']}}</td>
						<td style="background-color: #c3d8ef;">{{$data['mtx'][$m]['brand']}}</td>
						<td style='background-color: #c3d8ef;'>{{$data['mtx'][$m]['salesRep']}}</td>
						<td style="background-color: #c3d8ef;">{{$data['mtx'][$m]['agency']}}</td>
						<td style="background-color: #c3d8ef;">{{$data['mtx'][$m]['client']}}</td>
						<td style="background-color: #c3d8ef;">{{$data['mtx'][$m]['product']}}</td>
						<td style="background-color: #c3d8ef;">{{$data['mtx'][$m]['segment']}}</td>
						<td style="background-color: #c3d8ef;">{{$data['mtx'][$m]['mediaType']}}</td>
						<td style="background-color: #c3d8ef;">{{$data['mtx'][$m]['discount']}}</td>
						<td style="background-color: #c3d8ef;">{{ucwords(strtolower($data['mtx'][$m]['sector']))}}</td>
						<td style="background-color: #c3d8ef;">{{ucwords(strtolower($data['mtx'][$m]['category']))}}</td>
						@if($data['value'] == 'gross')
							<td style="background-color: #c3d8ef;">{{$data['mtx'][$m]['grossRevenue']}}</td>
						@else
							<td style="background-color: #c3d8ef;">{{$data['mtx'][$m]['netRevenue']}}</td>
						@endif
					</tr>
				@endif
			@endfor
		</tr>
	@endif
</table>