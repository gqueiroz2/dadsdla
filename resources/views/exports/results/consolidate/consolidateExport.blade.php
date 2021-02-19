@if($data['type'] == 'brand')

<td style="background-color: #0070c0; color: #FFFFFF; font-weight: bold;" colspan="18">{{ $data['salesRegion'] }} - Consolidate : ({{$data['currencyS']}}/{{strtoupper($data['value'])}})
</td>

<table>
	<tr>
		<td></td>
	</tr>
</table>

<table>
	<tr>
		<td style="background-color: #0f243e; color: #FFFFFF; font-weight: bold;"> DN </td>
		@for($m=0; $m < sizeof($data['month']); $m++)
			<td style="background-color: #a6a6a6; font-weight: bold;"> {{ $data['month'][$m] }} </td>
		@endfor
			<td style="background-color: #0f243e; color: #FFFFFF; font-weight: bold;"> Total </td>
			<td style="background-color: #a6a6a6; font-weight: bold;"> YTD </td>
		@for($q=0; $q < sizeof($data['quarter']); $q++)
			<td style="background-color: #a6a6a6; font-weight: bold;"> {{ $data['quarter'][$q] }} </td>
		@endfor
	</tr>

	<tr>
		<td style="background-color: #143052; color: #FFFFFF; font-weight: bold;"> {{ $data['years'][1] }} Ad Sales </td>
		@for($d=0; $d < sizeof($data['mtxDN']['previousAdSales']); $d++)
			@if($d == 12)
				<td style=" color: #FFFFFF; font-weight: bold; background-color: #143052;"> {{ number_format($data['mtxDN']['previousAdSales'][$d]) }} </td>
			@else 
				<td style="background-color: #c3d8ef; font-weight: bold;"> {{ number_format($data['mtxDN']['previousAdSales'][$d]) }} </td>
			@endif			
		@endfor
	</tr>

	<tr>
		<td style="background-color: #143052; color: #FFFFFF; font-weight: bold;"> {{ $data['years'][1] }} SAP </td>
		@for($d=0; $d < sizeof($data['mtxDN']['previousAdSales']); $d++)
			@if($d == 12)
				<td style=" color: #FFFFFF; font-weight: bold; background-color: #143052;"> {{ number_format($data['mtxDN']['previousSAP'][$d]) }} </td>
			@else
				<td style="background-color: #dce6f1; font-weight: bold;"> {{ number_format($data['mtxDN']['previousSAP'][$d]) }} </td>
			@endif
			
		@endfor
	</tr>

	<tr>
		<td style="background-color: #143052; color: #FFFFFF; font-weight: bold;"> {{ $data['years'][0] }} Target </td>
		@for($d=0; $d < sizeof($data['mtxDN']['previousAdSales']); $d++)
			@if($d == 12)
				<td style="color: #FFFFFF; font-weight: bold; background-color: #143052; color: #FFFFFF; font-weight: bold;"> {{ number_format($data['mtxDN']['currentTarget'][$d]) }} </td>
			@else
				<td style="background-color: #c3d8ef; font-weight: bold;">{{ number_format($data['mtxDN']['currentTarget'][$d]) }} </td>
			@endif
		@endfor
	</tr>

	<tr>
		<td style="background-color: #143052; color: #FFFFFF; font-weight: bold;"> {{ $data['years'][0] }} Corporate </td>
		@for($d=0; $d < sizeof($data['mtxDN']['previousAdSales']); $d++)
			@if($d == 12)
				<td style="color: #FFFFFF; font-weight: bold; background-color: #143052;"> {{ number_format($data['mtxDN']['currentCorporate'][$d]) }} </td>
			@else
				<td style="background-color: #dce6f1; font-weight: bold;"> {{ number_format($data['mtxDN']['currentCorporate'][$d]) }} </td>
			@endif
		@endfor
	</tr>

	<tr>
		<td style="background-color: #143052; color: #FFFFFF; font-weight: bold;"> {{ $data['years'][0] }} Ad Sales </td>
		@for($d=0; $d < sizeof($data['mtxDN']['previousAdSales']); $d++)
			@if($d == 12)
				<td style="color: #FFFFFF; font-weight: bold; background-color: #143052;"> {{ number_format($data['mtxDN']['currentAdSales'][$d]) }} </td>
			@else
				<td style="background-color: #c3d8ef; font-weight: bold;"> {{ number_format($data['mtxDN']['currentAdSales'][$d]) }} </td>
			@endif
		@endfor
	</tr>

	<tr>
		<td style="background-color: #143052; color: #FFFFFF; font-weight: bold;"> {{ $data['years'][0] }} SAP </td>
		@for($d=0; $d < sizeof($data['mtxDN']['previousAdSales']); $d++)
			@if($d == 12)
				<td style="color: #FFFFFF; font-weight: bold; background-color: #143052;"> {{ number_format($data['mtxDN']['currentSAP'][$d]) }} </td>
			@else
				<td style="background-color: #dce6f1; font-weight: bold;"> {{ number_format($data['mtxDN']['currentSAP'][$d]) }} </td>
			@endif
		@endfor
	</tr>

	<tr>
		<td style="background-color: #143052; color: #FFFFFF; font-weight: bold;"> %({{ $data['years'][0] }}F - 2019) </td>
		@for($d=0; $d < sizeof($data['mtxDN']['previousAdSales']); $d++)
			@if($d == 12)
				@if($data['mtxDN']['previousAdSales'][$d] > 0)
					<td style="color: #FFFFFF; font-weight: bold; background-color: #143052;">{{($data['mtxDN']['currentCorporate'][$d]/$data['mtxDN']['previousAdSales'][$d])*100}}</td>
				@else
					<td style=" color: #FFFFFF; font-weight: bold; background-color: #143052;">0.0</td>
				@endif
			@else
				@if($data['mtxDN']['previousAdSales'][$d] > 0)
					<td style=" background-color: #c3d8ef; font-weight: bold;">{{($data['mtxDN']['currentCorporate'][$d]/$data['mtxDN']['previousAdSales'][$d])*100}}</td>
				@else
					<td style="background-color: #c3d8ef; font-weight: bold;">0.0</td>
				@endif
			@endif
		@endfor
	</tr>

	<tr>
		<td style="background-color: #143052; color: #FFFFFF; font-weight: bold;"> %({{ $data['years'][0] }}F - Target) </td>
		@for($d=0; $d < sizeof($data['mtxDN']['previousAdSales']); $d++)
			@if($d == 12)
				@if($data['mtxDN']['previousAdSales'][$d] > 0)
					<td style=" color: #FFFFFF; font-weight: bold; background-color: #143052;">{{($data['mtxDN']['currentCorporate'][$d]/$data['mtxDN']['currentTarget'][$d])*100}}%</td>
				@else
					<td style="color: #FFFFFF; font-weight: bold; background-color: #143052;">0.0</td>
				@endif 
			@else
				@if($data['mtxDN']['previousAdSales'][$d] > 0)
					<td style="background-color: #dce6f1; font-weight: bold;">{{($data['mtxDN']['currentCorporate'][$d]/$data['mtxDN']['currentTarget'][$d])*100}}%</td>
				@else
					<td style="background-color: #dce6f1; font-weight: bold;">0.0</td>
				@endif
			@endif
		@endfor
	</tr>
</table>
@for ($c=0; $c < sizeof($data['mtx']['previousAdSales']); $c++)
<table>
	<tr>
		<td style="background-color: #0070c0; color: #FFFFFF; font-weight: bold;"> {{ $data['brandID'][$c][1] }} </td>
    	@for($m=0; $m < sizeof($data['month']); $m++)
    		<td style="background-color: #a6a6a6; font-weight: bold;"> {{ $data['month'][$m] }} </td>
    	@endfor
    		<td style="background-color: #0f243e; color: #FFFFFF; font-weight: bold;"> Total </td>
    		<td style="background-color: #a6a6a6; font-weight: bold;"> YTD </td>
    	@for($q=0; $q < sizeof($data['quarter']); $q++)
    		<td style="background-color: #a6a6a6; font-weight: bold;">  {{ $data['quarter'][$q] }} </td>
    	@endfor
	</tr>

	<tr>
		<td style="background-color: #c3d8ef; font-weight: bold;"> {{ $data['years'][1] }} Ad Sales </td>
    	@for($d=0; $d < sizeof($data['mtx']['previousAdSales'][$c]); $d++)
    		@if($d == 12)
				<td style="color: #FFFFFF; font-weight: bold; background-color: #143052;">{{ number_format($data['mtx']['previousAdSales'][$c][$d]) }} </td>
    		@else
				<td style="background-color: #c3d8ef; font-weight: bold;">{{ number_format($data['mtx']['previousAdSales'][$c][$d]) }} </td>
    		@endif
    	@endfor
	</tr>

	<tr>
		<td style="background-color: #dce6f1; font-weight: bold;"> {{ $data['years'][1] }} SAP </td>
    	@for($d=0; $d < sizeof($data['mtx']['previousAdSales'][$c]); $d++)
    		@if($d == 12)
    			<td style="color: #FFFFFF; font-weight: bold; background-color: #143052;"> {{ number_format($data['mtx']['previousSAP'][$c][$d]) }} </td>
    		@else
    			<td style="background-color: #dce6f1; font-weight: bold;"> {{ number_format($data['mtx']['previousSAP'][$c][$d]) }} </td>
    		@endif
    	@endfor
	</tr>

	<tr>
		<td style="background-color: #c3d8ef; font-weight: bold;"> {{ $data['years'][0] }} Target </td>
    	@for($d=0; $d < sizeof($data['mtx']['previousAdSales'][$c]); $d++)
    		@if($d == 12)
    			<td style=" color: #FFFFFF; font-weight: bold; background-color: #143052;">{{ number_format($data['mtx']['currentTarget'][$c][$d]) }} </td>
    		@else
    			<td style="background-color: #c3d8ef; font-weight: bold;">{{ number_format($data['mtx']['currentTarget'][$c][$d]) }} </td>
    		@endif
    	@endfor
	</tr>
	
	<tr>
		<td style="background-color: #dce6f1; font-weight: bold;"> {{ $data['years'][0] }} Corporate </td>
    	@for($d=0; $d < sizeof($data['mtx']['previousAdSales'][$c]); $d++)
    		@if($d == 12)
    			<td style="color: #FFFFFF; font-weight: bold; background-color: #143052;"> {{ number_format($data['mtx']['currentCorporate'][$c][$d]) }} </td>
    		@else
    			<td style="background-color: #dce6f1; font-weight: bold;"> {{ number_format($data['mtx']['currentCorporate'][$c][$d]) }} </td>
    		@endif
    	@endfor
	</tr>

	<tr>
		<td style="background-color: #c3d8ef; font-weight: bold;"> {{ $data['years'][0] }} Ad Sales </td>
    	@for($d=0; $d < sizeof($data['mtx']['previousAdSales'][$c]); $d++)
    		@if($d == 12)
    			<td style="color: #FFFFFF; font-weight: bold; background-color: #143052;"> {{ number_format($data['mtx']['currentAdSales'][$c][$d]) }} </td>
    		@else
    			<td style="background-color: #c3d8ef; font-weight: bold;"> {{ number_format($data['mtx']['currentAdSales'][$c][$d]) }} </td>
    		@endif
    	@endfor
	</tr>

	<tr>
		<td style="background-color: #dce6f1; font-weight: bold; "> {{ $data['years'][0] }} SAP </td>
    	@for($d=0; $d < sizeof($data['mtx']['previousAdSales'][$c]); $d++)
    		@if($d == 12)
    			<td style="color: #FFFFFF; font-weight: bold; background-color: #143052; "> {{ number_format($data['mtx']['currentSAP'][$c][$d]) }} </td>
    		@else
    			<td style="background-color: #dce6f1; font-weight: bold;"> {{ number_format($data['mtx']['currentSAP'][$c][$d]) }} </td>
    		@endif
    	@endfor
	</tr>

	<tr>
		<td style="background-color: #c3d8ef; font-weight: bold;"> %({{ $data['years'][0] }}F - 2019) </td>
    	@for($d=0; $d < sizeof($data['mtx']['previousAdSales'][$c]); $d++)
    		@if($d == 12)
    			@if($data['mtx']['previousAdSales'][$c][$d] > 0)
					<td style="color: #FFFFFF; font-weight: bold; background-color: #143052; ">{{($data['mtx']['currentCorporate'][$c][$d]/$data['mtx']['previousAdSales'][$c][$d])*100}}%</td> 
				@else
					<td style="color: #FFFFFF; font-weight: bold; background-color: #143052; ">0.0</td>
				@endif
    		@else
    			@if($data['mtx']['previousAdSales'][$c][$d] > 0)
					<td style="background-color: #c3d8ef; font-weight: bold;">{{($data['mtx']['currentCorporate'][$c][$d]/$data['mtx']['previousAdSales'][$c][$d])*100}}%</td> 
				@else
					<td style="background-color: #c3d8ef; font-weight: bold;">0.0</td>
				@endif
    		@endif				        		 
    	@endfor
	</tr>

	<tr>
		<td style="background-color: #dce6f1; font-weight: bold;"> %({{ $data['years'][0] }}F - Target) </td>
    	@for($d=0; $d < sizeof($data['mtx']['previousAdSales'][$c]); $d++)
    		@if($d == 12)
    			@if($data['mtx']['previousAdSales'][$c][$d] > 0)
					<td style="color: #FFFFFF; font-weight: bold; background-color: #143052; ">{{($data['mtx']['currentCorporate'][$c][$d]/$data['mtx']['currentTarget'][$c][$d])*100}}%</td> 
				@else
					<td style="color: #FFFFFF; font-weight: bold; background-color: #143052; ">0.0</td>
				@endif
    		@else
    			@if($data['mtx']['previousAdSales'][$c][$d] > 0)
					<td style="background-color: #dce6f1; font-weight: bold;">{{($data['mtx']['currentCorporate'][$c][$d]/$data['mtx']['currentTarget'][$c][$d])*100}}%</td> 
				@else
					<td style="background-color: #dce6f1; font-weight: bold;">0.0</td>
				@endif
    		@endif
    	@endfor
	</tr>	
</table>	
@endfor

@elseif($data['type'] == 'ae')

<td style="background-color: #0070c0; color: #FFFFFF; font-weight: bold;" colspan="18">{{ $data['salesRegion'] }} - Consolidate : ({{$data['currencyS']}}/{{strtoupper($data['value'])}})
</td>

<table>
	<tr>
		<td></td>
	</tr>
</table>

<table>
	<tr>
		<td style="background-color: #0f243e; color: #FFFFFF; font-weight: bold;"> DN </td>
		@for($m=0; $m < sizeof($data['month']); $m++)
			<td style="background-color: #a6a6a6; font-weight: bold;"> {{ $data['month'][$m] }} </td>
		@endfor
			<td style="background-color: #0f243e; color: #FFFFFF; font-weight: bold;"> Total </td>
			<td style="background-color: #a6a6a6; font-weight: bold;"> YTD </td>
		@for($q=0; $q < sizeof($data['quarter']); $q++)
			<td style="background-color: #a6a6a6; font-weight: bold;"> {{ $data['quarter'][$q] }} </td>
		@endfor
	</tr>

	<tr>
		<td style="background-color: #143052; color: #FFFFFF; font-weight: bold;"> {{ $data['years'][1] }} Ad Sales </td>
		@for($d=0; $d < sizeof($data['mtxDN']['previousAdSales']); $d++)
			@if($d == 12)
				<td style=" color: #FFFFFF; font-weight: bold; background-color: #143052;"> {{ number_format($data['mtxDN']['previousAdSales'][$d]) }} </td>
			@else 
				<td style="background-color: #c3d8ef; font-weight: bold;"> {{ number_format($data['mtxDN']['previousAdSales'][$d]) }} </td>
			@endif			
		@endfor
	</tr>

	<tr>
		<td style="background-color: #143052; color: #FFFFFF; font-weight: bold;"> {{ $data['years'][1] }} SAP </td>
		@for($d=0; $d < sizeof($data['mtxDN']['previousAdSales']); $d++)
			@if($d == 12)
				<td style=" color: #FFFFFF; font-weight: bold; background-color: #143052;"> {{ number_format($data['mtxDN']['previousSAP'][$d]) }} </td>
			@else
				<td style="background-color: #dce6f1; font-weight: bold;"> {{ number_format($data['mtxDN']['previousSAP'][$d]) }} </td>
			@endif
			
		@endfor
	</tr>

	<tr>
		<td style="background-color: #143052; color: #FFFFFF; font-weight: bold;"> {{ $data['years'][0] }} Target </td>
		@for($d=0; $d < sizeof($data['mtxDN']['previousAdSales']); $d++)
			@if($d == 12)
				<td style="color: #FFFFFF; font-weight: bold; background-color: #143052; color: #FFFFFF; font-weight: bold;"> {{ number_format($data['mtxDN']['currentTarget'][$d]) }} </td>
			@else
				<td style="background-color: #c3d8ef; font-weight: bold;">{{ number_format($data['mtxDN']['currentTarget'][$d]) }} </td>
			@endif
		@endfor
	</tr>

	<tr>
		<td style="background-color: #143052; color: #FFFFFF; font-weight: bold;"> {{ $data['years'][0] }} Corporate </td>
		@for($d=0; $d < sizeof($data['mtxDN']['previousAdSales']); $d++)
			@if($d == 12)
				<td style="color: #FFFFFF; font-weight: bold; background-color: #143052;"> {{ number_format($data['mtxDN']['currentCorporate'][$d]) }} </td>
			@else
				<td style="background-color: #dce6f1; font-weight: bold;"> {{ number_format($data['mtxDN']['currentCorporate'][$d]) }} </td>
			@endif
		@endfor
	</tr>

	<tr>
		<td style="background-color: #143052; color: #FFFFFF; font-weight: bold;"> {{ $data['years'][0] }} Ad Sales </td>
		@for($d=0; $d < sizeof($data['mtxDN']['previousAdSales']); $d++)
			@if($d == 12)
				<td style="color: #FFFFFF; font-weight: bold; background-color: #143052;"> {{ number_format($data['mtxDN']['currentAdSales'][$d]) }} </td>
			@else
				<td style="background-color: #c3d8ef; font-weight: bold;"> {{ number_format($data['mtxDN']['currentAdSales'][$d]) }} </td>
			@endif
		@endfor
	</tr>

	<tr>
		<td style="background-color: #143052; color: #FFFFFF; font-weight: bold;"> {{ $data['years'][0] }} SAP </td>
		@for($d=0; $d < sizeof($data['mtxDN']['previousAdSales']); $d++)
			@if($d == 12)
				<td style="color: #FFFFFF; font-weight: bold; background-color: #143052;"> {{ number_format($data['mtxDN']['currentSAP'][$d]) }} </td>
			@else
				<td style="background-color: #dce6f1; font-weight: bold;"> {{ number_format($data['mtxDN']['currentSAP'][$d]) }} </td>
			@endif
		@endfor
	</tr>

	<tr>
		<td style="background-color: #143052; color: #FFFFFF; font-weight: bold;"> %({{ $data['years'][0] }}F - 2019) </td>
		@for($d=0; $d < sizeof($data['mtxDN']['previousAdSales']); $d++)
			@if($d == 12)
				@if($data['mtxDN']['previousAdSales'][$d] > 0)
					<td style="color: #FFFFFF; font-weight: bold; background-color: #143052;">{{($data['mtxDN']['currentCorporate'][$d]/$data['mtxDN']['previousAdSales'][$d])*100}}</td>
				@else
					<td style=" color: #FFFFFF; font-weight: bold; background-color: #143052;">0.0</td>
				@endif
			@else
				@if($data['mtxDN']['previousAdSales'][$d] > 0)
					<td style=" background-color: #c3d8ef; font-weight: bold;">{{($data['mtxDN']['currentCorporate'][$d]/$data['mtxDN']['previousAdSales'][$d])*100}}</td>
				@else
					<td style="background-color: #c3d8ef; font-weight: bold;">0.0</td>
				@endif
			@endif
		@endfor
	</tr>

	<tr>
		<td style="background-color: #143052; color: #FFFFFF; font-weight: bold;"> %({{ $data['years'][0] }}F - Target) </td>
		@for($d=0; $d < sizeof($data['mtxDN']['previousAdSales']); $d++)
			@if($d == 12)
				@if($data['mtxDN']['previousAdSales'][$d] > 0)
					<td style=" color: #FFFFFF; font-weight: bold; background-color: #143052;">{{($data['mtxDN']['currentCorporate'][$d]/$data['mtxDN']['currentTarget'][$d])*100}}%</td>
				@else
					<td style="color: #FFFFFF; font-weight: bold; background-color: #143052;">0.0</td>
				@endif 
			@else
				@if($data['mtxDN']['previousAdSales'][$d] > 0)
					<td style="background-color: #dce6f1; font-weight: bold;">{{($data['mtxDN']['currentCorporate'][$d]/$data['mtxDN']['currentTarget'][$d])*100}}%</td>
				@else
					<td style="background-color: #dce6f1; font-weight: bold;">0.0</td>
				@endif
			@endif
		@endfor
	</tr>
</table>
@for ($c=0; $c < sizeof($data['mtx']['previousAdSales']); $c++)
<table>
	<tr>
		<td style="background-color: #0070c0; color: #FFFFFF; font-weight: bold;"> {{ $data['typeSelectS'][$c]['salesRep'] }} </td>
    	@for($m=0; $m < sizeof($data['month']); $m++)
    		<td style="background-color: #a6a6a6; font-weight: bold;"> {{ $data['month'][$m] }} </td>
    	@endfor
    		<td style="background-color: #0f243e; color: #FFFFFF; font-weight: bold;"> Total </td>
    		<td style="background-color: #a6a6a6; font-weight: bold;"> YTD </td>
    	@for($q=0; $q < sizeof($data['quarter']); $q++)
    		<td style="background-color: #a6a6a6; font-weight: bold;">  {{ $data['quarter'][$q] }} </td>
    	@endfor
	</tr>

	<tr>
		<td style="background-color: #c3d8ef; font-weight: bold;"> {{ $data['years'][1] }} Ad Sales </td>
    	@for($d=0; $d < sizeof($data['mtx']['previousAdSales'][$c]); $d++)
    		@if($d == 12)
				<td style="color: #FFFFFF; font-weight: bold; background-color: #143052;">{{ number_format($data['mtx']['previousAdSales'][$c][$d]) }} </td>
    		@else
				<td style="background-color: #c3d8ef; font-weight: bold;">{{ number_format($data['mtx']['previousAdSales'][$c][$d]) }} </td>
    		@endif
    	@endfor
	</tr>

	<tr>
		<td style="background-color: #dce6f1; font-weight: bold;"> {{ $data['years'][1] }} SAP </td>
    	@for($d=0; $d < sizeof($data['mtx']['previousAdSales'][$c]); $d++)
    		@if($d == 12)
    			<td style="color: #FFFFFF; font-weight: bold; background-color: #143052;"> {{ number_format($data['mtx']['previousSAP'][$c][$d]) }} </td>
    		@else
    			<td style="background-color: #dce6f1; font-weight: bold;"> {{ number_format($data['mtx']['previousSAP'][$c][$d]) }} </td>
    		@endif
    	@endfor
	</tr>

	<tr>
		<td style="background-color: #c3d8ef; font-weight: bold;"> {{ $data['years'][0] }} Target </td>
    	@for($d=0; $d < sizeof($data['mtx']['previousAdSales'][$c]); $d++)
    		@if($d == 12)
    			<td style=" color: #FFFFFF; font-weight: bold; background-color: #143052;">{{ number_format($data['mtx']['currentTarget'][$c][$d]) }} </td>
    		@else
    			<td style="background-color: #c3d8ef; font-weight: bold;">{{ number_format($data['mtx']['currentTarget'][$c][$d]) }} </td>
    		@endif
    	@endfor
	</tr>
	
	<tr>
		<td style="background-color: #dce6f1; font-weight: bold;"> {{ $data['years'][0] }} Corporate </td>
    	@for($d=0; $d < sizeof($data['mtx']['previousAdSales'][$c]); $d++)
    		@if($d == 12)
    			<td style="color: #FFFFFF; font-weight: bold; background-color: #143052;"> {{ number_format($data['mtx']['currentCorporate'][$c][$d]) }} </td>
    		@else
    			<td style="background-color: #dce6f1; font-weight: bold;"> {{ number_format($data['mtx']['currentCorporate'][$c][$d]) }} </td>
    		@endif
    	@endfor
	</tr>

	<tr>
		<td style="background-color: #c3d8ef; font-weight: bold;"> {{ $data['years'][0] }} Ad Sales </td>
    	@for($d=0; $d < sizeof($data['mtx']['previousAdSales'][$c]); $d++)
    		@if($d == 12)
    			<td style="color: #FFFFFF; font-weight: bold; background-color: #143052;"> {{ number_format($data['mtx']['currentAdSales'][$c][$d]) }} </td>
    		@else
    			<td style="background-color: #c3d8ef; font-weight: bold;"> {{ number_format($data['mtx']['currentAdSales'][$c][$d]) }} </td>
    		@endif
    	@endfor
	</tr>

	<tr>
		<td style="background-color: #dce6f1; font-weight: bold; "> {{ $data['years'][0] }} SAP </td>
    	@for($d=0; $d < sizeof($data['mtx']['previousAdSales'][$c]); $d++)
    		@if($d == 12)
    			<td style="color: #FFFFFF; font-weight: bold; background-color: #143052; "> {{ number_format($data['mtx']['currentSAP'][$c][$d]) }} </td>
    		@else
    			<td style="background-color: #dce6f1; font-weight: bold;"> {{ number_format($data['mtx']['currentSAP'][$c][$d]) }} </td>
    		@endif
    	@endfor
	</tr>

	<tr>
		<td style="background-color: #c3d8ef; font-weight: bold;"> %({{ $data['years'][0] }}F - 2019) </td>
    	@for($d=0; $d < sizeof($data['mtx']['previousAdSales'][$c]); $d++)
    		@if($d == 12)
    			@if($data['mtx']['previousAdSales'][$c][$d] > 0)
					<td style="color: #FFFFFF; font-weight: bold; background-color: #143052; ">{{($data['mtx']['currentCorporate'][$c][$d]/$data['mtx']['previousAdSales'][$c][$d])*100}}%</td> 
				@else
					<td style="color: #FFFFFF; font-weight: bold; background-color: #143052; ">0.0</td>
				@endif
    		@else
    			@if($data['mtx']['previousAdSales'][$c][$d] > 0)
					<td style="background-color: #c3d8ef; font-weight: bold;">{{($data['mtx']['currentCorporate'][$c][$d]/$data['mtx']['previousAdSales'][$c][$d])*100}}%</td> 
				@else
					<td style="background-color: #c3d8ef; font-weight: bold;">0.0</td>
				@endif
    		@endif				        		 
    	@endfor
	</tr>

	<tr>
		<td style="background-color: #dce6f1; font-weight: bold;"> %({{ $data['years'][0] }}F - Target) </td>
    	@for($d=0; $d < sizeof($data['mtx']['previousAdSales'][$c]); $d++)
    		@if($d == 12)
    			@if($data['mtx']['previousAdSales'][$c][$d] > 0)
					<td style="color: #FFFFFF; font-weight: bold; background-color: #143052; ">{{($data['mtx']['currentCorporate'][$c][$d]/$data['mtx']['currentTarget'][$c][$d])*100}}%</td> 
				@else
					<td style="color: #FFFFFF; font-weight: bold; background-color: #143052; ">0.0</td>
				@endif
    		@else
    			@if($data['mtx']['previousAdSales'][$c][$d] > 0)
					<td style="background-color: #dce6f1; font-weight: bold;">{{($data['mtx']['currentCorporate'][$c][$d]/$data['mtx']['currentTarget'][$c][$d])*100}}%</td> 
				@else
					<td style="background-color: #dce6f1; font-weight: bold;">0.0</td>
				@endif
    		@endif
    	@endfor
	</tr>	
</table>	
@endfor

@elseif($data['type'] == 'advertiser')

<td style="background-color: #0070c0; color: #FFFFFF; font-weight: bold;" colspan="18">{{ $data['salesRegion'] }} - Consolidate : ({{$data['currencyS']}}/{{strtoupper($data['value'])}})
</td>

<table>
	<tr>
		<td></td>
	</tr>
</table>

<table>
	<tr>
		<td style="background-color: #0f243e; color: #FFFFFF; font-weight: bold;"> DN </td>
		@for($m=0; $m < sizeof($data['month']); $m++)
			<td style="background-color: #a6a6a6; font-weight: bold;"> {{ $data['month'][$m] }} </td>
		@endfor
			<td style="background-color: #0f243e; color: #FFFFFF; font-weight: bold;"> Total </td>
			<td style="background-color: #a6a6a6; font-weight: bold;"> YTD </td>
		@for($q=0; $q < sizeof($data['quarter']); $q++)
			<td style="background-color: #a6a6a6; font-weight: bold;"> {{ $data['quarter'][$q] }} </td>
		@endfor
	</tr>

	<tr>
		<td style="background-color: #143052; color: #FFFFFF; font-weight: bold;"> {{ $data['years'][1] }} Ad Sales </td>
		@for($d=0; $d < sizeof($data['mtxDN']['previousAdSales']); $d++)
			@if($d == 12)
				<td style=" color: #FFFFFF; font-weight: bold; background-color: #143052;"> {{ number_format($data['mtxDN']['previousAdSales'][$d]) }} </td>
			@else 
				<td style="background-color: #c3d8ef; font-weight: bold;"> {{ number_format($data['mtxDN']['previousAdSales'][$d]) }} </td>
			@endif			
		@endfor
	</tr>

	<tr>
		<td style="background-color: #143052; color: #FFFFFF; font-weight: bold;"> {{ $data['years'][1] }} SAP </td>
		@for($d=0; $d < sizeof($data['mtxDN']['previousAdSales']); $d++)
			@if($d == 12)
				<td style=" color: #FFFFFF; font-weight: bold; background-color: #143052;"> {{ number_format($data['mtxDN']['previousSAP'][$d]) }} </td>
			@else
				<td style="background-color: #dce6f1; font-weight: bold;"> {{ number_format($data['mtxDN']['previousSAP'][$d]) }} </td>
			@endif
			
		@endfor
	</tr>

	<tr>
		<td style="background-color: #143052; color: #FFFFFF; font-weight: bold;"> {{ $data['years'][0] }} Target </td>
		@for($d=0; $d < sizeof($data['mtxDN']['previousAdSales']); $d++)
			@if($d == 12)
				<td style="color: #FFFFFF; font-weight: bold; background-color: #143052; color: #FFFFFF; font-weight: bold;"> {{ number_format($data['mtxDN']['currentTarget'][$d]) }} </td>
			@else
				<td style="background-color: #c3d8ef; font-weight: bold;">{{ number_format($data['mtxDN']['currentTarget'][$d]) }} </td>
			@endif
		@endfor
	</tr>

	<tr>
		<td style="background-color: #143052; color: #FFFFFF; font-weight: bold;"> {{ $data['years'][0] }} Corporate </td>
		@for($d=0; $d < sizeof($data['mtxDN']['previousAdSales']); $d++)
			@if($d == 12)
				<td style="color: #FFFFFF; font-weight: bold; background-color: #143052;"> {{ number_format($data['mtxDN']['currentCorporate'][$d]) }} </td>
			@else
				<td style="background-color: #dce6f1; font-weight: bold;"> {{ number_format($data['mtxDN']['currentCorporate'][$d]) }} </td>
			@endif
		@endfor
	</tr>

	<tr>
		<td style="background-color: #143052; color: #FFFFFF; font-weight: bold;"> {{ $data['years'][0] }} Ad Sales </td>
		@for($d=0; $d < sizeof($data['mtxDN']['previousAdSales']); $d++)
			@if($d == 12)
				<td style="color: #FFFFFF; font-weight: bold; background-color: #143052;"> {{ number_format($data['mtxDN']['currentAdSales'][$d]) }} </td>
			@else
				<td style="background-color: #c3d8ef; font-weight: bold;"> {{ number_format($data['mtxDN']['currentAdSales'][$d]) }} </td>
			@endif
		@endfor
	</tr>

	<tr>
		<td style="background-color: #143052; color: #FFFFFF; font-weight: bold;"> {{ $data['years'][0] }} SAP </td>
		@for($d=0; $d < sizeof($data['mtxDN']['previousAdSales']); $d++)
			@if($d == 12)
				<td style="color: #FFFFFF; font-weight: bold; background-color: #143052;"> {{ number_format($data['mtxDN']['currentSAP'][$d]) }} </td>
			@else
				<td style="background-color: #dce6f1; font-weight: bold;"> {{ number_format($data['mtxDN']['currentSAP'][$d]) }} </td>
			@endif
		@endfor
	</tr>

	<tr>
		<td style="background-color: #143052; color: #FFFFFF; font-weight: bold;"> %({{ $data['years'][0] }}F - 2019) </td>
		@for($d=0; $d < sizeof($data['mtxDN']['previousAdSales']); $d++)
			@if($d == 12)
				@if($data['mtxDN']['previousAdSales'][$d] > 0)
					<td style="color: #FFFFFF; font-weight: bold; background-color: #143052;">{{($data['mtxDN']['currentCorporate'][$d]/$data['mtxDN']['previousAdSales'][$d])*100}}</td>
				@else
					<td style=" color: #FFFFFF; font-weight: bold; background-color: #143052;">0.0</td>
				@endif
			@else
				@if($data['mtxDN']['previousAdSales'][$d] > 0)
					<td style=" background-color: #c3d8ef; font-weight: bold;">{{($data['mtxDN']['currentCorporate'][$d]/$data['mtxDN']['previousAdSales'][$d])*100}}</td>
				@else
					<td style="background-color: #c3d8ef; font-weight: bold;">0.0</td>
				@endif
			@endif
		@endfor
	</tr>

	<tr>
		<td style="background-color: #143052; color: #FFFFFF; font-weight: bold;"> %({{ $data['years'][0] }}F - Target) </td>
		@for($d=0; $d < sizeof($data['mtxDN']['previousAdSales']); $d++)
			@if($d == 12)
				@if($data['mtxDN']['previousAdSales'][$d] > 0)
					<td style=" color: #FFFFFF; font-weight: bold; background-color: #143052;">{{($data['mtxDN']['currentCorporate'][$d]/$data['mtxDN']['currentTarget'][$d])*100}}%</td>
				@else
					<td style="color: #FFFFFF; font-weight: bold; background-color: #143052;">0.0</td>
				@endif 
			@else
				@if($data['mtxDN']['previousAdSales'][$d] > 0)
					<td style="background-color: #dce6f1; font-weight: bold;">{{($data['mtxDN']['currentCorporate'][$d]/$data['mtxDN']['currentTarget'][$d])*100}}%</td>
				@else
					<td style="background-color: #dce6f1; font-weight: bold;">0.0</td>
				@endif
			@endif
		@endfor
	</tr>
</table>
@for ($c=0; $c < sizeof($data['mtx']['previousAdSales']); $c++)
<table>
	<tr>
		<td style="background-color: #0070c0; color: #FFFFFF; font-weight: bold;"> {{ $data['typeSelectS'][$c]['client'] }} </td>
    	@for($m=0; $m < sizeof($data['month']); $m++)
    		<td style="background-color: #a6a6a6; font-weight: bold;"> {{ $data['month'][$m] }} </td>
    	@endfor
    		<td style="background-color: #0f243e; color: #FFFFFF; font-weight: bold;"> Total </td>
    		<td style="background-color: #a6a6a6; font-weight: bold;"> YTD </td>
    	@for($q=0; $q < sizeof($data['quarter']); $q++)
    		<td style="background-color: #a6a6a6; font-weight: bold;">  {{ $data['quarter'][$q] }} </td>
    	@endfor
	</tr>

	<tr>
		<td style="background-color: #c3d8ef; font-weight: bold;"> {{ $data['years'][1] }} Ad Sales </td>
    	@for($d=0; $d < sizeof($data['mtx']['previousAdSales'][$c]); $d++)
    		@if($d == 12)
				<td style="color: #FFFFFF; font-weight: bold; background-color: #143052;">{{ number_format($data['mtx']['previousAdSales'][$c][$d]) }} </td>
    		@else
				<td style="background-color: #c3d8ef; font-weight: bold;">{{ number_format($data['mtx']['previousAdSales'][$c][$d]) }} </td>
    		@endif
    	@endfor
	</tr>

	<tr>
		<td style="background-color: #dce6f1; font-weight: bold;"> {{ $data['years'][1] }} SAP </td>
    	@for($d=0; $d < sizeof($data['mtx']['previousAdSales'][$c]); $d++)
    		@if($d == 12)
    			<td style="color: #FFFFFF; font-weight: bold; background-color: #143052;"> {{ number_format($data['mtx']['previousSAP'][$c][$d]) }} </td>
    		@else
    			<td style="background-color: #dce6f1; font-weight: bold;"> {{ number_format($data['mtx']['previousSAP'][$c][$d]) }} </td>
    		@endif
    	@endfor
	</tr>

	<tr>
		<td style="background-color: #c3d8ef; font-weight: bold;"> {{ $data['years'][0] }} Target </td>
    	@for($d=0; $d < sizeof($data['mtx']['previousAdSales'][$c]); $d++)
    		@if($d == 12)
    			<td style=" color: #FFFFFF; font-weight: bold; background-color: #143052;">{{ number_format($data['mtx']['currentTarget'][$c][$d]) }} </td>
    		@else
    			<td style="background-color: #c3d8ef; font-weight: bold;">{{ number_format($data['mtx']['currentTarget'][$c][$d]) }} </td>
    		@endif
    	@endfor
	</tr>
	
	<tr>
		<td style="background-color: #dce6f1; font-weight: bold;"> {{ $data['years'][0] }} Corporate </td>
    	@for($d=0; $d < sizeof($data['mtx']['previousAdSales'][$c]); $d++)
    		@if($d == 12)
    			<td style="color: #FFFFFF; font-weight: bold; background-color: #143052;"> {{ number_format($data['mtx']['currentCorporate'][$c][$d]) }} </td>
    		@else
    			<td style="background-color: #dce6f1; font-weight: bold;"> {{ number_format($data['mtx']['currentCorporate'][$c][$d]) }} </td>
    		@endif
    	@endfor
	</tr>

	<tr>
		<td style="background-color: #c3d8ef; font-weight: bold;"> {{ $data['years'][0] }} Ad Sales </td>
    	@for($d=0; $d < sizeof($data['mtx']['previousAdSales'][$c]); $d++)
    		@if($d == 12)
    			<td style="color: #FFFFFF; font-weight: bold; background-color: #143052;"> {{ number_format($data['mtx']['currentAdSales'][$c][$d]) }} </td>
    		@else
    			<td style="background-color: #c3d8ef; font-weight: bold;"> {{ number_format($data['mtx']['currentAdSales'][$c][$d]) }} </td>
    		@endif
    	@endfor
	</tr>

	<tr>
		<td style="background-color: #dce6f1; font-weight: bold; "> {{ $data['years'][0] }} SAP </td>
    	@for($d=0; $d < sizeof($data['mtx']['previousAdSales'][$c]); $d++)
    		@if($d == 12)
    			<td style="color: #FFFFFF; font-weight: bold; background-color: #143052; "> {{ number_format($data['mtx']['currentSAP'][$c][$d]) }} </td>
    		@else
    			<td style="background-color: #dce6f1; font-weight: bold;"> {{ number_format($data['mtx']['currentSAP'][$c][$d]) }} </td>
    		@endif
    	@endfor
	</tr>

	<tr>
		<td style="background-color: #c3d8ef; font-weight: bold;"> %({{ $data['years'][0] }}F - 2019) </td>
    	@for($d=0; $d < sizeof($data['mtx']['previousAdSales'][$c]); $d++)
    		@if($d == 12)
    			@if($data['mtx']['previousAdSales'][$c][$d] > 0)
					<td style="color: #FFFFFF; font-weight: bold; background-color: #143052; ">{{($data['mtx']['currentCorporate'][$c][$d]/$data['mtx']['previousAdSales'][$c][$d])*100}}%</td> 
				@else
					<td style="color: #FFFFFF; font-weight: bold; background-color: #143052; ">0.0</td>
				@endif
    		@else
    			@if($data['mtx']['previousAdSales'][$c][$d] > 0)
					<td style="background-color: #c3d8ef; font-weight: bold;">{{($data['mtx']['currentCorporate'][$c][$d]/$data['mtx']['previousAdSales'][$c][$d])*100}}%</td> 
				@else
					<td style="background-color: #c3d8ef; font-weight: bold;">0.0</td>
				@endif
    		@endif				        		 
    	@endfor
	</tr>

	<tr>
		<td style="background-color: #dce6f1; font-weight: bold;"> %({{ $data['years'][0] }}F - Target) </td>
    	@for($d=0; $d < sizeof($data['mtx']['previousAdSales'][$c]); $d++)
    		@if($d == 12)
    			@if($data['mtx']['previousAdSales'][$c][$d] > 0)
					<td style="color: #FFFFFF; font-weight: bold; background-color: #143052; ">{{($data['mtx']['currentCorporate'][$c][$d]/$data['mtx']['currentTarget'][$c][$d])*100}}%</td> 
				@else
					<td style="color: #FFFFFF; font-weight: bold; background-color: #143052; ">0.0</td>
				@endif
    		@else
    			@if($data['mtx']['previousAdSales'][$c][$d] > 0)
					<td style="background-color: #dce6f1; font-weight: bold;">{{($data['mtx']['currentCorporate'][$c][$d]/$data['mtx']['currentTarget'][$c][$d])*100}}%</td> 
				@else
					<td style="background-color: #dce6f1; font-weight: bold;">0.0</td>
				@endif
    		@endif
    	@endfor
	</tr>	
</table>	
@endfor

@endif
