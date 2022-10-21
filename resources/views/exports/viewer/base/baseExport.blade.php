<table>

	@if($data['source'] == "cmaps")
	<tr>
		<th style="background-color: #0047b3;" colspan="14"> {{$data['regions']}} - Viewer CMAPS {{$data['year']}} - ({{strtoupper($data['currencies'])}})</th>		
	</tr>
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
			<td style="background-color: #e6e6e6;">Revenue</td>
			<td style="background-color: #e6e6e6;">Net Revenue</td>
		</tr>
		<tr>
			<td style="background-color: #0f243e;">Total</td>
			<td style="background-color: #0f243e;" colspan="8"></td>
			<td style="background-color: #0f243e;">{{$data['total']['averageDiscount']/100}}</td>
			<td style="background-color: #0f243e;" colspan="2"></td>				
			<td style="background-color: #0f243e;">{{$data['total']['sumGrossRevenue']}}</td>
			<td style="background-color: #0f243e;">{{$data['total']['sumNetRevenue']}}</td>
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
				<td>{{$data['mtx'][$m]['grossRevenue']}}</td>
				<td>{{$data['mtx'][$m]['netRevenue']}}</td>
			</tr>
		@endfor
	@elseif($data['source'] == 'bts')
	<tr>
		<th style="background-color: #0047b3;" colspan="12"> {{$data['regions']}} - Viewer BTS {{$data['year']}} - ({{strtoupper($data['currencies'])}})</th>		
	</tr>
		<tr>
			<td style="background-color: #e6e6e6;">Order Reference</td>
			<td style="background-color: #e6e6e6;">Campaign Reference</td>
			<td style="background-color: #e6e6e6;">Year</td>
			<td style="background-color: #e6e6e6;">Month</td>
			<td style="background-color: #e6e6e6;">Brand</td>
			<td style="background-color: #e6e6e6;">Sales Rep</td>
			<td style="background-color: #e6e6e6;">Agency</td>
			<td style="background-color: #e6e6e6;">Client</td>
			<td style="background-color: #e6e6e6;">Product</td>
			<td style="background-color: #e6e6e6;">Num Spot</td>			
			<td style="background-color: #e6e6e6;">Revenue</td>
			<td style="background-color: #e6e6e6;">Net Revenue</td>
		</tr>
		<tr>
			<td style="background-color: #0f243e;">Total</td>
			<td style="background-color: #0f243e;" colspan="9"></td>
			<td style="background-color: #0f243e;">{{$data['total']['sumGrossRevenue']}}</td>
			<td style="background-color: #0f243e;">{{$data['total']['sumNetRevenue']}}</td>
		</tr>
		@for($m=0;$m < sizeof($data['mtx']); $m++)
			<tr>
				<td >{{$data['mtx'][$m]['orderReference']}}</td>
				<td>{{$data['mtx'][$m]['campaignReference']}}</td>
				<td>{{$data['mtx'][$m]['year']}}</td>
				<td>{{$data['mtx'][$m]['month']}}</td>
				<td>{{$data['mtx'][$m]['brand']}}</td>
				<td>{{$data['mtx'][$m]['salesRepName']}}</td>
				<td>{{$data['mtx'][$m]['agency']}}</td>
				<td>{{$data['mtx'][$m]['client']}}</td>
				<td>{{$data['mtx'][$m]['clientProduct']}}</td>
				<td>{{$data['mtx'][$m]['numSpot']}}</td>
				<td>{{$data['mtx'][$m]['grossRevenue']}}</td>
				<td>{{$data['mtx'][$m]['netRevenue']}}</td>
			</tr>
		@endfor
	@elseif($data['source'] == 'sf')
	<tr>
		<th style="background-color: #0047b3;" colspan="13"> {{$data['regions']}} - Viewer SalesForce {{$data['year']}} - ({{strtoupper($data['currencies'])}})</th>		
	</tr>
		<tr>
			<td style="background-color: #e6e6e6;">Oppid</td>
			<td style="background-color: #e6e6e6;">Oportunity Name</td>
			<td style="background-color: #e6e6e6;">Brand</td>
			<td style="background-color: #e6e6e6;">Agency</td>
			<td style="background-color: #e6e6e6;">Client</td>
			<td style="background-color: #e6e6e6;">Sales Rep Owner</td>
			<td style="background-color: #e6e6e6;">Sales Rep Splitted</td>
			<td style="background-color: #e6e6e6;">From Date</td>
			<td style="background-color: #e6e6e6;">To Date</td>
			<td style="background-color: #e6e6e6;">Stage</td>
			<td style="background-color: #e6e6e6;">Agency Commission</td>			
			<td style="background-color: #e6e6e6;">Amount Gross</td>
			<td style="background-color: #e6e6e6;">Amount Net</td>
		</tr>
		<tr>
			<td style="background-color: #0f243e;">Total</td>
			<td style="background-color: #0f243e;" colspan="10"></td>
			<td style="background-color: #0f243e;">{{$data['total']['sumGrossRevenue']}}</td>
			<td style="background-color: #0f243e;">{{$data['total']['sumNetRevenue']}}</td>
		</tr>
		@for($m=0;$m < sizeof($data['mtx']); $m++)
			<tr>
				<td >{{$data['mtx'][$m]['oppid']}}</td>
				<td>{{$data['mtx'][$m]['opportunityName']}}</td>
				<td>{{$data['mtx'][$m]['brand']}}</td>
				<td>{{$data['mtx'][$m]['agency']}}</td>
				<td>{{$data['mtx'][$m]['client']}}</td>
				<td>{{$data['mtx'][$m]['salesRepOwner']}}</td>
				<td>{{$data['mtx'][$m]['salesRepSplitter']}}</td>
				<td>{{$data['mtx'][$m]['fromDate']}}</td>
				<td>{{$data['mtx'][$m]['toDate']}}</td>
				@if ($data['mtx'][$m]['stage'] == 1) 
					<td> 1 - Qualification </td>
				@elseif ($data['mtx'][$m]['stage'] == 2) 
					<td> 2 - Proposal </td>
				@elseif ($data['mtx'][$m]['stage'] == 3)
					<td> 3 - Negotiation </td>
				@elseif ($data['mtx'][$m]['stage'] == 4)
					<td> 4 - Verbal </td>
				@endif
				<td>{{$data['mtx'][$m]['agencyCommission']}}</td>
				<td>{{$data['mtx'][$m]['fcstAmountGross']}}</td>
				<td>{{$data['mtx'][$m]['fcstAmountNet']}}</td>
			</tr>
		@endfor
	@elseif ($data['source'] == 'aleph')
		<tr>	
			<th style="background-color: #0047b3;" colspan='12'> {{$data['regions']}} - Viewer Aleph {{$data['year']}} - ({{strtoupper($data['currencies'])}}) </th>
		</tr>

		<tr class='center'>
			<td style="background-color: #e6e6e6;">Year</td>
			<td style="background-color: #e6e6e6;">Month</td>
			<td style="background-color: #e6e6e6;">Brand</td>
			<td style="background-color: #e6e6e6;">P. Sales Rep</td>
			<td style="background-color: #e6e6e6;">Current Sales Rep</td>
			<td style="background-color: #e6e6e6;">Client</td>
			<td style="background-color: #e6e6e6;">Agency</td>
			<td style="background-color: #e6e6e6;"> Agency Group</td>
			<td style="background-color: #e6e6e6;">Feed Type</td>
			<td style="background-color: #e6e6e6;">Feed Code</td>
			<td style="background-color: #e6e6e6;">Gross Revenue</td>
			<td style="background-color: #e6e6e6;">Net Revenue</td>					
		</tr>

		<tr>
			<td style="background-color: #0f243e;">Total</td>
			<td style="background-color: #0f243e;" colspan='9'></td>
			<td style="background-color: #0f243e;" >{{$data['total']['sumGrossRevenue']}}</td>
			<td style="background-color: #0f243e;" >{{$data['total']['sumNetRevenue']}}</td>
		</tr>

		@for ($m=0; $m <sizeof($data['mtx']); $m++)
			<tr>
				<td>{{$data['mtx'][$m]['year']}}</td>
				<td>{{$data['mtx'][$m]['month']}}</td>
				<td>{{$data['mtx'][$m]['brand']}}</td>
				<td>{{$data['mtx'][$m]['oldRep']}}</td>
				<td>{{$data['mtx'][$m]['salesRep']}}</td>
				<td>{{$data['mtx'][$m]['client']}}</td>
				<td>{{$data['mtx'][$m]['agency']}}</td>
				<td>{{$data['mtx'][$m]['agencyGroup']}}</td>
				<td>{{$data['mtx'][$m]['feedType']}}</td>
				<td>{{$data['mtx'][$m]['feedCode']}}</td>
				<td>{{$data['mtx'][$m]['grossRevenue']}}</td>
				<td>"{{$data['mtx'][$m]['netRevenue']}}</td>
			</tr>
		@endfor

	@elseif ($data['source'] == 'wbd')
		<tr>	
			<th style="background-color: #0047b3;" colspan='11'> {{$data['regions']}} - Viewer WBD {{$data['year']}} - ({{$data['currencies']}}) </th>
		</tr>

		<tr>
			<td style="background-color: #e6e6e6;">Company</td>
			<td style="background-color: #e6e6e6;">Year</td>
			<td style="background-color: #e6e6e6;">Month</td>
			<td style="background-color: #e6e6e6;">Brand</td>
			<td style="background-color: #e6e6e6;">P. Sales Rep</td>
			<td style="background-color: #e6e6e6;">Current Sales Rep</td>
			<td style="background-color: #e6e6e6;">Client</td>
			<td style="background-color: #e6e6e6;">Agency</td>
			<td style="background-color: #e6e6e6;">Manager</td>
			<td style="background-color: #e6e6e6;">Gross Revenue</td>
			<td style="background-color: #e6e6e6;">Net Revenue</td>					
		</tr>

		<tr>
			<td style="background-color: #0f243e;">Total</td>
			<td style="background-color: #0f243e;" colspan='8'></td>
			<td style="background-color: #0f243e;" >{{$data['total']['sumGrossRevenue']}}</td>
			<td style="background-color: #0f243e;" >{{$data['total']['sumNetRevenue']}}</td>
		</tr>

		@for ($m=0; $m <sizeof($data['mtx']) ; $m++) 
			<tr>
				<td>{{$data['mtx'][$m]['company']}}</td>
				<td>{{$data['mtx'][$m]['year']}}</td>
				<td>{{$data['mtx'][$m]['month']}}</td>
				<td>{{$data['mtx'][$m]['brand']}}</td>
				<td>{{$data['mtx'][$m]['oldRep']}}</td>
				<td>{{$data['mtx'][$m]['salesRep']}}</td>
				<td>{{$data['mtx'][$m]['client']}}</td>
				<td>{{$data['mtx'][$m]['agency']}}</td>
				<td>{{$data['mtx'][$m]['manager']}}</td>
				<td>{{$data['mtx'][$m]['grossRevenue']}}</td>
				<td>{{$data['mtx'][$m]['netRevenue']}}</td>		
			</tr>
		@endfor			
	@endif

</table>