<table>

	@if($data['source'] == "cmaps")
	<tr>
		<th style="background-color: #0047b3; color: #ffffff font-weight: bold; text-align: center;" colspan="14"> {{$data['regions']}} - Viewer CMAPS {{$data['year']}} - ({{strtoupper($data['currencies'])}})</th>		
	</tr>

		<tr>
			<td style="background-color: #0f243e; color: #ffffff; font-weight: bold;">Total</td>
			<td style="background-color: #0f243e;" colspan="8"></td>
			<td style="background-color: #0f243e; color: #ffffff; font-weight: bold;">{{$data['total']['averageDiscount']/100}}</td>
			<td style="background-color: #0f243e;" colspan="2"></td>				
			<td style="background-color: #0f243e; color: #ffffff; font-weight: bold;">{{$data['total']['sumGrossRevenue']}}</td>
			<td style="background-color: #0f243e; color: #ffffff; font-weight: bold;">{{$data['total']['sumNetRevenue']}}</td>
		</tr>
		
		<tr>
			<td style="background-color: #e6e6e6; font-weight: bold; text-align: center;">Map Number</td>
			<td style="background-color: #e6e6e6; font-weight: bold; text-align: center;">Pi Number</td>
			<td style="background-color: #e6e6e6; font-weight: bold; text-align: center;">Month</td>
			<td style="background-color: #e6e6e6; font-weight: bold; text-align: center;">Brand</td>
			<td style="background-color: #e6e6e6; font-weight: bold; text-align: center;">Sales Rep</td>
			<td style="background-color: #e6e6e6; font-weight: bold; text-align: center;">Agency</td>
			<td style="background-color: #e6e6e6; font-weight: bold; text-align: center;">Client</td>
			<td style="background-color: #e6e6e6; font-weight: bold; text-align: center;">Product</td>
			<td style="background-color: #e6e6e6; font-weight: bold; text-align: center;">Media Type</td>
			<td style="background-color: #e6e6e6; font-weight: bold; text-align: center;">Discount</td>
			<td style="background-color: #e6e6e6; font-weight: bold; text-align: center;">Sector</td>
			<td style="background-color: #e6e6e6; font-weight: bold; text-align: center;">Category</td>			
			<td style="background-color: #e6e6e6; font-weight: bold; text-align: center;">Revenue</td>
			<td style="background-color: #e6e6e6; font-weight: bold; text-align: center;">Net Revenue</td>
		</tr>
		
		@for($m=0;$m < sizeof($data['mtx']); $m++)
			<tr>				
				<td style="background-color: #c3d8ef; font-weight: bold;">{{$data['mtx'][$m]['mapNumber']}}</td>
				<td style="background-color: #c3d8ef; font-weight: bold;">{{$data['mtx'][$m]['piNumber']}}</td>
				<td style="background-color: #c3d8ef; font-weight: bold;">{{$data['mtx'][$m]['month']}}</td>
				<td style="background-color: #c3d8ef; font-weight: bold;">{{$data['mtx'][$m]['brand']}}</td>
				<td style="background-color: #c3d8ef; font-weight: bold;">{{$data['mtx'][$m]['salesRep']}}</td>				
				<td style="background-color: #c3d8ef; font-weight: bold;">{{$data['mtx'][$m]['agency']}}</td>
				<td style="background-color: #c3d8ef; font-weight: bold;">{{$data['mtx'][$m]['client']}}</td>
				<td style="background-color: #c3d8ef; font-weight: bold;">{{$data['mtx'][$m]['product']}}</td>
				<td style="background-color: #c3d8ef; font-weight: bold;">{{$data['mtx'][$m]['mediaType']}}</td>
				<td style="background-color: #c3d8ef; font-weight: bold;">{{$data['mtx'][$m]['discount']/100}}</td>
				<td style="background-color: #c3d8ef; font-weight: bold;">{{ucwords(strtolower($data['mtx'][$m]['sector']))}}</td>
				<td style="background-color: #c3d8ef; font-weight: bold;">{{ucwords(strtolower($data['mtx'][$m]['category']))}}</td>				
				<td style="background-color: #c3d8ef; font-weight: bold;">{{$data['mtx'][$m]['grossRevenue']}}</td>
				<td style="background-color: #c3d8ef; font-weight: bold;">{{$data['mtx'][$m]['netRevenue']}}</td>
			</tr>
		@endfor
	@elseif($data['source'] == 'bts')
	<tr>
		<th style="background-color: #0047b3; color: #ffffff; font-weight: bold; text-align: center;" colspan="12"> {{$data['regions']}} - Viewer BTS {{$data['year']}} - ({{strtoupper($data['currencies'])}})</th>		
	</tr>

		<tr>
			<td style="background-color: #0f243e; color: #ffffff; font-weight: bold;">Total</td>
			<td style="background-color: #0f243e;" colspan="9"></td>
			<td style="background-color: #0f243e; color: #ffffff; font-weight: bold;">{{$data['total']['sumGrossRevenue']}}</td>
			<td style="background-color: #0f243e; color: #ffffff; font-weight: bold;">{{$data['total']['sumNetRevenue']}}</td>
		</tr>

		<tr>
			<td style="background-color: #e6e6e6; font-weight: bold; text-align: center;">Order Reference</td>
			<td style="background-color: #e6e6e6; font-weight: bold; text-align: center;">Campaign Reference</td>
			<td style="background-color: #e6e6e6; font-weight: bold; text-align: center;">Year</td>
			<td style="background-color: #e6e6e6; font-weight: bold; text-align: center;">Month</td>
			<td style="background-color: #e6e6e6; font-weight: bold; text-align: center;">Brand</td>
			<td style="background-color: #e6e6e6; font-weight: bold; text-align: center;">Sales Rep</td>
			<td style="background-color: #e6e6e6; font-weight: bold; text-align: center;">Agency</td>
			<td style="background-color: #e6e6e6; font-weight: bold; text-align: center;">Client</td>
			<td style="background-color: #e6e6e6; font-weight: bold; text-align: center;">Product</td>
			<td style="background-color: #e6e6e6; font-weight: bold; text-align: center;">Num Spot</td>			
			<td style="background-color: #e6e6e6; font-weight: bold; text-align: center;">Revenue</td>
			<td style="background-color: #e6e6e6; font-weight: bold; text-align: center;">Net Revenue</td>
		</tr>
		
		@for($m=0;$m < sizeof($data['mtx']); $m++)
			<tr>
				<td style="background-color: #c3d8ef; font-weight: bold;">{{$data['mtx'][$m]['orderReference']}}</td>
				<td style="background-color: #c3d8ef; font-weight: bold;">{{$data['mtx'][$m]['campaignReference']}}</td>
				<td style="background-color: #c3d8ef; font-weight: bold;">{{$data['mtx'][$m]['year']}}</td>
				<td style="background-color: #c3d8ef; font-weight: bold;">{{$data['mtx'][$m]['month']}}</td>
				<td style="background-color: #c3d8ef; font-weight: bold;">{{$data['mtx'][$m]['brand']}}</td>
				<td style="background-color: #c3d8ef; font-weight: bold;">{{$data['mtx'][$m]['salesRepName']}}</td>
				<td style="background-color: #c3d8ef; font-weight: bold;">{{$data['mtx'][$m]['agency']}}</td>
				<td style="background-color: #c3d8ef; font-weight: bold;">{{$data['mtx'][$m]['client']}}</td>
				<td style="background-color: #c3d8ef; font-weight: bold;">{{$data['mtx'][$m]['clientProduct']}}</td>
				<td style="background-color: #c3d8ef; font-weight: bold;">{{$data['mtx'][$m]['numSpot']}}</td>
				<td style="background-color: #c3d8ef; font-weight: bold;">{{$data['mtx'][$m]['grossRevenue']}}</td>
				<td style="background-color: #c3d8ef; font-weight: bold;">{{$data['mtx'][$m]['netRevenue']}}</td>
			</tr>
		@endfor
	@elseif($data['source'] == 'sf')
	<tr>
		<th style="background-color: #0047b3; color: #ffffff; font-weight: bold; text-align: center;" colspan="13"> {{$data['regions']}} - Viewer SalesForce {{$data['year']}} - ({{strtoupper($data['currencies'])}})</th>		
	</tr>

		<tr>
			<td style="background-color: #0f243e; color: #ffffff; font-weight: bold;">Total</td>
			<td style="background-color: #0f243e;" colspan="10"></td>
			<td style="background-color: #0f243e; color: #ffffff; font-weight: bold;">{{$data['total']['sumGrossRevenue']}}</td>
			<td style="background-color: #0f243e; color: #ffffff; font-weight: bold;">{{$data['total']['sumNetRevenue']}}</td>
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
				@elseif ($data['mtx'][$m]['stage'] == 5)
					<td> 5 - Closed Won </td>
				@endif
				<td>{{$data['mtx'][$m]['agencyCommission']}}</td>
				<td>{{$data['mtx'][$m]['fcstAmountGross']}}</td>
				<td>{{$data['mtx'][$m]['fcstAmountNet']}}</td>
			</tr>
		@endfor
	@elseif ($data['source'] == 'aleph')
		<tr>	
			<th style="background-color: #0047b3;  color: #ffffff; font-weight: bold; text-align: center;" colspan='12'> {{$data['regions']}} - Viewer Aleph {{$data['year']}} - ({{strtoupper($data['currencies'])}}) </th>
		</tr>

		<tr>
			<td style="background-color: #0f243e; color: #ffffff;  font-weight: bold; ">Total</td>
			<td style="background-color: #0f243e; color: #ffffff; font-weight: bold;" colspan='9'></td>
			<td style="background-color: #0f243e; color: #ffffff; font-weight: bold;" >{{$data['total']['sumGrossRevenue']}}</td>
			<td style="background-color: #0f243e; color: #ffffff; font-weight: bold;" >{{$data['total']['sumNetRevenue']}}</td>
		</tr>

		<tr class='center'>
			<td style="background-color: #e6e6e6; font-weight: bold; text-align: center;">Year</td>
			<td style="background-color: #e6e6e6; font-weight: bold; text-align: center;">Month</td>
			<td style="background-color: #e6e6e6; font-weight: bold; text-align: center;">Brand</td>
			<td style="background-color: #e6e6e6; font-weight: bold; text-align: center;">Previous AE</td>
			<td style="background-color: #e6e6e6; font-weight: bold; text-align: center;">Current AE</td>
			<td style="background-color: #e6e6e6; font-weight: bold; text-align: center;">Client</td>
			<td style="background-color: #e6e6e6; font-weight: bold; text-align: center;">Agency</td>
			<td style="background-color: #e6e6e6; font-weight: bold; text-align: center;"> Agency Group</td>
			<td style="background-color: #e6e6e6; font-weight: bold; text-align: center;">Feed Type</td>
			<td style="background-color: #e6e6e6; font-weight: bold; text-align: center;">Feed Code</td>
			<td style="background-color: #e6e6e6; font-weight: bold; text-align: center;">Gross Revenue</td>
			<td style="background-color: #e6e6e6; font-weight: bold; text-align: center;">Net Revenue</td>					
		</tr>		

		@for ($m=0; $m <sizeof($data['mtx']); $m++)
			<tr>
				<td style="background-color: #c3d8ef; font-weight: bold;">{{$data['mtx'][$m]['year']}}</td>
				<td style="background-color: #c3d8ef; font-weight: bold;">{{$data['mtx'][$m]['month']}}</td>
				<td style="background-color: #c3d8ef; font-weight: bold;">{{$data['mtx'][$m]['brand']}}</td>
				<td style="background-color: #c3d8ef; font-weight: bold;">{{$data['mtx'][$m]['oldRep']}}</td>
				<td style="background-color: #c3d8ef; font-weight: bold;">{{$data['mtx'][$m]['salesRep']}}</td>
				<td style="background-color: #c3d8ef; font-weight: bold;">{{$data['mtx'][$m]['client']}}</td>
				<td style="background-color: #c3d8ef; font-weight: bold;">{{$data['mtx'][$m]['agency']}}</td>
				<td style="background-color: #c3d8ef; font-weight: bold;">{{$data['mtx'][$m]['agencyGroup']}}</td>
				<td style="background-color: #c3d8ef; font-weight: bold;">{{$data['mtx'][$m]['feedType']}}</td>
				<td style="background-color: #c3d8ef; font-weight: bold;">{{$data['mtx'][$m]['feedCode']}}</td>
				<td style="background-color: #c3d8ef; font-weight: bold;">{{$data['mtx'][$m]['grossRevenue']}}</td>
				<td style="background-color: #c3d8ef; font-weight: bold;">{{$data['mtx'][$m]['netRevenue']}}</td>
			</tr>
		@endfor

	@elseif ($data['source'] == 'wbd')
		<tr>	
			<th style="background-color: #0047b3; color: #ffffff; font-weight: bold; text-align: center;" colspan='16'> {{$data['regions']}} - Viewer WBD {{$data['year']}} - ({{$data['currencies']}}) </th>
		</tr>

		<tr>
			<td style="background-color: #0f243e; color: #ffffff; font-weight: bold;">Total</td>
			<td style="background-color: #0f243e; " colspan='13'></td>
			<td style="background-color: #0f243e; color: #ffffff; font-weight: bold;" >{{$data['total']['sumGrossRevenue']}}</td>
			<td style="background-color: #0f243e;color: #ffffff; font-weight: bold;" >{{$data['total']['sumNetRevenue']}}</td>
		</tr>

		<tr>
			<td style="background-color: #e6e6e6; font-weight: bold; text-align: center;">Company</td>
			<td style="background-color: #e6e6e6; font-weight: bold; text-align: center;">Year</td>
			<td style="background-color: #e6e6e6; font-weight: bold; text-align: center;">Month</td>
			<td style="background-color: #e6e6e6; font-weight: bold; text-align: center;">Previous AE</td>
			<td style="background-color: #e6e6e6; font-weight: bold; text-align: center;">Client</td>
			<td style="background-color: #e6e6e6; font-weight: bold; text-align: center;">Agency</td>
			<td style="background-color: #e6e6e6; font-weight: bold; text-align: center;">Platform</td>
			<td style="background-color: #e6e6e6; font-weight: bold; text-align: center;">Brand</td>
			<td style="background-color: #e6e6e6; font-weight: bold; text-align: center;">Feed Code</td>
			<td style="background-color: #e6e6e6; font-weight: bold; text-align: center;">Order</td>
			<td style="background-color: #e6e6e6; font-weight: bold; text-align: center;">Pi Number</td>
			<td style="background-color: #e6e6e6; font-weight: bold; text-align: center;">Property</td>
			<td style="background-color: #e6e6e6; font-weight: bold; text-align: center;">Director</td>
			<td style="background-color: #e6e6e6; font-weight: bold; text-align: center;">Current AE</td>
			<td style="background-color: #e6e6e6; font-weight: bold; text-align: center;">Gross Revenue</td>
			<td style="background-color: #e6e6e6; font-weight: bold; text-align: center;">Net Revenue</td>					
		</tr>

		@for ($m=0; $m <sizeof($data['mtx']) ; $m++) 
			<tr> 
				<td >{{$data['mtx'][$m]['company']}}</td>
				<td style="background-color: #c3d8ef; font-weight: bold;">{{$data['mtx'][$m]['year']}}</td>
				<td style="background-color: #c3d8ef; font-weight: bold;">{{$data['mtx'][$m]['month']}}</td>
				<td style="background-color: #c3d8ef; font-weight: bold;">{{$data['mtx'][$m]['oldRep']}}</td>
				<td style="background-color: #c3d8ef; font-weight: bold;">{{$data['mtx'][$m]['client']}}</td>
				<td style="background-color: #c3d8ef; font-weight: bold;">{{$data['mtx'][$m]['agency']}}</td>
				<td style="background-color: #c3d8ef; font-weight: bold;">{{$data['mtx'][$m]['feedType']}}</td>
				<td style="background-color: #c3d8ef; font-weight: bold;">{{$data['mtx'][$m]['brand']}}</td>
				<td style="background-color: #c3d8ef; font-weight: bold;">{{$data['mtx'][$m]['feedCode']}}</td>
				<td style="background-color: #c3d8ef; font-weight: bold;">{{$data['mtx'][$m]['internalCode']}}</td>
				<td style="background-color: #c3d8ef; font-weight: bold;">{{$data['mtx'][$m]['piNumber']}}</td>
				<td style="background-color: #c3d8ef; font-weight: bold;">{{$data['mtx'][$m]['property']}}</td>
				<td style="background-color: #c3d8ef; font-weight: bold;">{{$data['mtx'][$m]['manager']}}</td>
				<td style="background-color: #c3d8ef; font-weight: bold;">{{$data['mtx'][$m]['salesRep']}}</td>
				<td style="background-color: #c3d8ef; font-weight: bold;">{{$data['mtx'][$m]['grossRevenue']}}</td>
				<td style="background-color: #c3d8ef; font-weight: bold;">{{$data['mtx'][$m]['netRevenue']}}</td>		
			</tr>
		@endfor			
	@endif

</table>