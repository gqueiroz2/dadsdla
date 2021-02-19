<td style="background-color: #0070c0; color: #FFFFFF; font-weight: bold;" colspan="19">									
	DLA - Consolidate - : ({{$data['currencyS']}}/{{strtoupper($data['value'])}})
</td>

<table>
	<tr>
		<td></td>
	</tr>
</table>

<table>
	<tr>
		<td style="background-color: #0f243e; color: #FFFFFF; font-weight: bold;"> DN </td>
		@for($m=0; $m < sizeof($data['monthView']); $m++)
			<td style="background-color: #a6a6a6; font-weight: bold;"> {{ $data['monthView'][$m] }} </td>
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
				<td style=" color: #FFFFFF; font-weight: bold; background-color: #143052; text-align: left;"> {{ number_format($data['mtxDN']['previousAdSales'][$d],2,',','.') }} </td>
			@else 
				<td style="background-color: #c3d8ef; font-weight: bold; text-align: left;"> {{ number_format($data['mtxDN']['previousAdSales'][$d],2,',','.') }} </td>
			@endif			
		@endfor
	</tr>

	<tr>
		<td style="background-color: #143052; color: #FFFFFF; font-weight: bold;"> {{ $data['years'][1] }} SAP </td>
		@for($d=0; $d < sizeof($data['mtxDN']['previousAdSales']); $d++)
			@if($d == 12)
				<td style=" color: #FFFFFF; font-weight: bold; background-color: #143052; text-align: left;"> {{ number_format($data['mtxDN']['previousSAP'][$d],2,',','.') }} </td>
			@else
				<td style="background-color: #dce6f1; font-weight: bold; text-align: left;"> {{ number_format($data['mtxDN']['previousSAP'][$d],2,',','.') }} </td>
			@endif
			
		@endfor
	</tr>

	<tr>
		<td style="background-color: #143052; color: #FFFFFF; font-weight: bold;"> {{ $data['years'][0] }} Target </td>
		@for($d=0; $d < sizeof($data['mtxDN']['previousAdSales']); $d++)
			@if($d == 12)
				<td style="background-color: #143052; color: #FFFFFF; font-weight: bold; text-align: left;"> {{ number_format($data['mtxDN']['currentTarget'][$d],2,',','.') }} </td>
			@else
				<td style="background-color: #c3d8ef; font-weight: bold; text-align: left;">{{ number_format($data['mtxDN']['currentTarget'][$d],2,',','.') }} </td>
			@endif
		@endfor
	</tr>

	<tr>
		<td style="background-color: #143052; color: #FFFFFF; font-weight: bold;"> {{ $data['years'][0] }} Corporate </td>
		@for($d=0; $d < sizeof($data['mtxDN']['previousAdSales']); $d++)
			@if($d == 12)
				<td style="color: #FFFFFF; font-weight: bold; background-color: #143052; text-align: left;"> {{ number_format($data['mtxDN']['currentCorporate'][$d],2,',','.') }} </td>
			@else
				<td style="background-color: #dce6f1; font-weight: bold; text-align: left;"> {{ number_format($data['mtxDN']['currentCorporate'][$d],2,',','.') }} </td>
			@endif
		@endfor
	</tr>

	<tr>
		<td style="background-color: #143052; color: #FFFFFF; font-weight: bold;"> {{ $data['years'][0] }} Ad Sales </td>
		@for($d=0; $d < sizeof($data['mtxDN']['previousAdSales']); $d++)
			@if($d == 12)
				<td style="color: #FFFFFF; font-weight: bold; background-color: #143052; text-align: left;"> {{ number_format($data['mtxDN']['currentAdSales'][$d],2,',','.') }} </td>
			@else
				<td style="background-color: #c3d8ef; font-weight: bold; text-align: left;"> {{ number_format($data['mtxDN']['currentAdSales'][$d],2,',','.') }} </td>
			@endif
		@endfor
	</tr>

	<tr>
		<td style="background-color: #143052; color: #FFFFFF; font-weight: bold;"> {{ $data['years'][0] }} SAP </td>
		@for($d=0; $d < sizeof($data['mtxDN']['previousAdSales']); $d++)
			@if($d == 12)
				<td style="color: #FFFFFF; font-weight: bold; background-color: #143052; text-align: left;"> {{ number_format($data['mtxDN']['currentSAP'][$d],2,',','.') }} </td>
			@else
				<td style="background-color: #dce6f1; font-weight: bold; text-align: left;"> {{ number_format($data['mtxDN']['currentSAP'][$d],2,',','.') }} </td>
			@endif
		@endfor
	</tr>

	<tr>
		<td style="background-color: #143052; color: #FFFFFF; font-weight: bold;"> %({{ $data['years'][0] }}F - 2019) </td>
		@for($d=0; $d < sizeof($data['mtxDN']['previousAdSales']); $d++)
			@if($d == 12)
				@if($data['mtxDN']['previousAdSales'][$d] > 0)
					<td style="color: #FFFFFF; font-weight: bold; background-color: #143052; text-align: left;">{{($data['mtxDN']['currentCorporate'][$d]/$data['mtxDN']['previousAdSales'][$d])*100}}</td>
				@else
					<td style=" color: #FFFFFF; font-weight: bold; background-color: #143052; text-align: left;">0.0</td>
				@endif
			@else
				@if($data['mtxDN']['previousAdSales'][$d] > 0)
					<td style=" background-color: #c3d8ef; font-weight: bold; text-align: left;">{{($data['mtxDN']['currentCorporate'][$d]/$data['mtxDN']['previousAdSales'][$d])*100}}</td>
				@else
					<td style="background-color: #c3d8ef; font-weight: bold; text-align: left;">0.0</td>
				@endif
			@endif
		@endfor
	</tr>

	<tr>
		<td style="background-color: #143052; color: #FFFFFF; font-weight: bold;"> %({{ $data['years'][0] }}F - Target) </td>
		@for($d=0; $d < sizeof($data['mtxDN']['previousAdSales']); $d++)
			@if($data['mtxDN']['currentTarget'][$d] == 0)
				@if($d == 12)
					<td style="color: #FFFFFF; font-weight: bold; background-color: #143052; text-align: left;">0%</td>
				@else
					<td style="background-color: #dce6f1; font-weight: bold; text-align: left;">0%</td>
				@endif
			@else
				@if($d == 12)
					@if($data['mtxDN']['previousAdSales'][$d] > 0)
						<td style=" color: #FFFFFF; font-weight: bold; background-color: #143052; text-align: left;">{{($data['mtxDN']['currentCorporate'][$d]/$data['mtxDN']['currentTarget'][$d])*100}}%</td>
					@else
						<td style="color: #FFFFFF; font-weight: bold; background-color: #143052; text-align: left;">0.0</td>
					@endif 
				@else
					@if($data['mtxDN']['previousAdSales'][$d] > 0)
						<td style="background-color: #dce6f1; font-weight: bold; text-align: left;">{{($data['mtxDN']['currentCorporate'][$d]/$data['mtxDN']['currentTarget'][$d])*100}}%</td>
					@else
						<td style="background-color: #dce6f1; font-weight: bold; text-align: left;">0.0</td>
					@endif
				@endif
			@endif
		@endfor
	</tr>	
</table>

@for ($c=0; $c < sizeof($data['mtx']['previousAdSales']); $c++)
<table>
	<tr>
		<td style="background-color: #0070c0; color: #FFFFFF; font-weight: bold;"> {{ $data['typeSelectN'][$c]['name'] }} </td>
    	@for($m=0; $m < sizeof($data['monthView']); $m++)
    		<td style="background-color: #a6a6a6; font-weight: bold;"> {{ $data['monthView'][$m] }} </td>
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
				<td style="color: #FFFFFF; font-weight: bold; background-color: #143052; text-align: left;">{{ number_format($data['mtx']['previousAdSales'][$c][$d],2,',','.') }} </td>
    		@else
				<td style="background-color: #c3d8ef; font-weight: bold; text-align: left;">{{ number_format($data['mtx']['previousAdSales'][$c][$d],2,',','.') }} </td>
    		@endif
    	@endfor
	</tr>

	<tr>
		<td style="background-color: #dce6f1; font-weight: bold;"> {{ $data['years'][1] }} SAP </td>
    	@for($d=0; $d < sizeof($data['mtx']['previousAdSales'][$c]); $d++)
    		@if($d == 12)
    			<td style="color: #FFFFFF; font-weight: bold; background-color: #143052; text-align: left;"> {{ number_format($data['mtx']['previousSAP'][$c][$d],2,',','.') }} </td>
    		@else
    			<td style="background-color: #dce6f1; font-weight: bold; text-align: left;"> {{ number_format($data['mtx']['previousSAP'][$c][$d],2,',','.') }} </td>
    		@endif
    	@endfor
	</tr>

	<tr>
		<td style="background-color: #c3d8ef; font-weight: bold;"> {{ $data['years'][0] }} Target </td>
    	@for($d=0; $d < sizeof($data['mtx']['previousAdSales'][$c]); $d++)
    		@if($d == 12)
    			<td style=" color: #FFFFFF; font-weight: bold; background-color: #143052; text-align: left;">{{ number_format($data['mtx']['currentTarget'][$c][$d],2,',','.') }} </td>
    		@else
    			<td style="background-color: #c3d8ef; font-weight: bold; text-align: left;">{{ number_format($data['mtx']['currentTarget'][$c][$d],2,',','.') }} </td>
    		@endif
    	@endfor
	</tr>
	
	<tr>
		<td style="background-color: #dce6f1; font-weight: bold;"> {{ $data['years'][0] }} Corporate </td>
    	@for($d=0; $d < sizeof($data['mtx']['previousAdSales'][$c]); $d++)
    		@if($d == 12)
    			<td style="color: #FFFFFF; font-weight: bold; background-color: #143052; text-align: left;"> {{ number_format($data['mtx']['currentCorporate'][$c][$d],2,',','.') }} </td>
    		@else
    			<td style="background-color: #dce6f1; font-weight: bold; text-align: left;"> {{ number_format($data['mtx']['currentCorporate'][$c][$d],2,',','.') }} </td>
    		@endif
    	@endfor
	</tr>

	<tr>
		<td style="background-color: #c3d8ef; font-weight: bold;"> {{ $data['years'][0] }} Ad Sales </td>
    	@for($d=0; $d < sizeof($data['mtx']['previousAdSales'][$c]); $d++)
    		@if($d == 12)
    			<td style="color: #FFFFFF; font-weight: bold; background-color: #143052; text-align: left;"> {{ number_format($data['mtx']['currentAdSales'][$c][$d],2,',','.') }} </td>
    		@else
    			<td style="background-color: #c3d8ef; font-weight: bold; text-align: left;"> {{ number_format($data['mtx']['currentAdSales'][$c][$d],2,',','.') }} </td>
    		@endif
    	@endfor
	</tr>

	<tr>
		<td style="background-color: #dce6f1; font-weight: bold; "> {{ $data['years'][0] }} SAP </td>
    	@for($d=0; $d < sizeof($data['mtx']['previousAdSales'][$c]); $d++)
    		@if($d == 12)
    			<td style="color: #FFFFFF; font-weight: bold; background-color: #143052; text-align: left;"> {{ number_format($data['mtx']['currentSAP'][$c][$d],2,',','.') }} </td>
    		@else
    			<td style="background-color: #dce6f1; font-weight: bold; text-align: left;"> {{ number_format($data['mtx']['currentSAP'][$c][$d],2,',','.') }} </td>
    		@endif
    	@endfor
	</tr>

	<tr>
		<td style="background-color: #c3d8ef; font-weight: bold;"> %({{ $data['years'][0] }}F - 2019) </td>
    	@for($d=0; $d < sizeof($data['mtx']['previousAdSales'][$c]); $d++)
    		@if($d == 12)
    			@if($data['mtx']['previousAdSales'][$c][$d] > 0)
					<td style="color: #FFFFFF; font-weight: bold; background-color: #143052; text-align: left;">{{($data['mtx']['currentCorporate'][$c][$d]/$data['mtx']['previousAdSales'][$c][$d])*100}}%</td> 
				@else
					<td style="color: #FFFFFF; font-weight: bold; background-color: #143052; text-align: left;">0.0</td>
				@endif
    		@else
    			@if($data['mtx']['previousAdSales'][$c][$d] > 0)
					<td style="background-color: #c3d8ef; font-weight: bold; text-align: left;">{{($data['mtx']['currentCorporate'][$c][$d]/$data['mtx']['previousAdSales'][$c][$d])*100}}%</td> 
				@else
					<td style="background-color: #c3d8ef; font-weight: bold; text-align: left;">0.0</td>
				@endif
    		@endif				        		 
    	@endfor
	</tr>

	<tr>
		<td style="background-color: #dce6f1; font-weight: bold;"> %({{ $data['years'][0] }}F - Target) </td>
    	@for($d=0; $d < sizeof($data['mtx']['previousAdSales'][$c]); $d++)

    		@if($data['mtxDN']['currentTarget'][$d] == 0)
				@if($d == 12)
					<td style="color: #FFFFFF; font-weight: bold; background-color: #143052; text-align: left;">0%</td>
				@else
					<td style="background-color: #dce6f1; font-weight: bold; text-align: left;">0%</td>
				@endif
			@else
				@if($d == 12)
					@if($data['mtxDN']['previousAdSales'][$d] > 0)
						<td style=" color: #FFFFFF; font-weight: bold; background-color: #143052; text-align: left;">{{($data['mtxDN']['currentCorporate'][$d]/$data['mtxDN']['currentTarget'][$d])*100}}%</td>
					@else
						<td style="color: #FFFFFF; font-weight: bold; background-color: #143052; text-align: left;">0.0</td>
					@endif 
				@else
					@if($data['mtxDN']['previousAdSales'][$d] > 0)
						<td style="background-color: #dce6f1; font-weight: bold; text-align: left;">{{($data['mtxDN']['currentCorporate'][$d]/$data['mtxDN']['currentTarget'][$d])*100}}%</td>
					@else
						<td style="background-color: #dce6f1; font-weight: bold; text-align: left;">0.0</td>
					@endif
				@endif
			@endif
    	@endfor
	</tr>	
</table>	
@endfor