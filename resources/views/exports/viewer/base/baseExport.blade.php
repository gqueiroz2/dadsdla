<table>
	<tr>
		<th style="background-color: #0047b3;" colspan="14"> {{$data['region']}} - Viewer CMAPS {{$data['year']}} - ({{strtoupper($data['currency'])}})</th>		
	</tr>

	@if($data['source'] == "cmaps")
		<tr>
			<td style="background-color: #e6e6e6;">Map Number</td>
			<td style="background-color: #e6e6e6;">Pi Number</td>
			<td style="background-color: #e6e6e6;">Month</td>
			<td style="background-color: #e6e6e6;">Brand</td>
			<td style="background-color: #e6e6e6;">Sales Rep</td>
			<td style="background-color: #e6e6e6;">Agency</td>
			<td style="background-color: #e6e6e6;">Client</td>
			<td style="background-color: #e6e6e6;">Product</td>
			<td style="background-color: #e6e6e6;">Media Type</td>
			<td style="background-color: #e6e6e6;">Discount</td>
			<td style="background-color: #e6e6e6;">Sector</td>
			<td style="background-color: #e6e6e6;">Category</td>
			<td style="background-color: #e6e6e6;">Net Revenue</td>
			<td style="background-color: #e6e6e6;">Revenue</td>
		</tr>
		<tr>
			@for($t=0;$t < sizeof($data['total']); $t++)
				<td style="background-color: #0f243e;">Total</td>
				<td style="background-color: #0f243e;" colspan="8"></td>
				<td style="background-color: #0f243e;">{{$data['total'][$t]['averageDiscount']/100}}</td>
				<td style="background-color: #0f243e;" colspan="2"></td>
				<td style="background-color: #0f243e;">{{$data['total'][$t]['sumNetRevenue']}}</td>
				<td style="background-color: #0f243e;">{{$data['total'][$t]['sumGrossRevenue']}}</td>
			@endfor
		</tr>
		@for($m=0;$m < sizeof($data['mtx']); $m++)
			<tr>				
				<td>{{$data['mtx'][$m]['mapNumber']}}</td>
				<td>{{$data['mtx'][$m]['piNumber']}}</td>
				<td>{{$data['mtx'][$m]['month']}}</td>
				<td>{{$data['mtx'][$m]['brand']}}</td>
				<td>{{$data['mtx'][$m]['salesRep']}}</td>
				<td>{{$data['mtx'][$m]['agency']}}</td>
				<td>{{$data['mtx'][$m]['client']}}</td>
				<td>{{$data['mtx'][$m]['product']}}</td>
				<td>{{$data['mtx'][$m]['mediaType']}}</td>
				<td>{{$data['mtx'][$m]['discount']/100}}</td>
				<td>{{ucwords(strtolower($data['mtx'][$m]['sector']))}}</td>
				<td>{{ucwords(strtolower($data['mtx'][$m]['category']))}}</td>
				<td>{{$data['mtx'][$m]['netRevenue']}}</td>
				<td>{{$data['mtx'][$m]['grossRevenue']}}</td>
			</tr>
		@endfor
	@endif
</table>