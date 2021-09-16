@if($data['type'] == 'brand')
<td style="background-color: #0070c0; color: #FFFFFF; font-weight: bold;" colspan="19">Consolidate - Brand : ({{$data['currencyS']}}/{{strtoupper($data['value'])}})
</td>

<table>
	<tr>
		<td></td>
	</tr>
</table>

<table>
	<tr>
		<td style="background-color: #0f243e; color: #FFFFFF; font-weight: bold; "> DN </td>
		@for($m=0; $m < sizeof($data['monthView']); $m++)
			<td style="background-color: #a6a6a6; font-weight: bold; float: right;"> {{ $data['monthView'][$m] }} </td>
		@endfor
			<td style="background-color: #0f243e; color: #FFFFFF; font-weight: bold; float: right;"> Total </td>
			<td style="background-color: #a6a6a6; font-weight: bold; float: right;"> YTD </td>
		@for($q=0; $q < sizeof($data['quarter']); $q++)
			<td style="background-color: #a6a6a6; font-weight: bold; float: right;"> {{ $data['quarter'][$q] }} </td>
		@endfor
	</tr>

	<tr>
		<td style="background-color: #143052; color: #FFFFFF; font-weight: bold;"> {{ $data['years'][1] }} Ad Sales </td>
		@for($d=0; $d < sizeof($data['mtxDN']['previousAdSales']); $d++)
			@if($d == 12)
				<td style=" color: #FFFFFF; font-weight: bold; background-color: #143052;"> {{$data['mtxDN']['previousAdSales'][$d]}} </td>
			@else 
				<td style="background-color: #c3d8ef; font-weight: bold;"> {{$data['mtxDN']['previousAdSales'][$d]}} </td>
			@endif			
		@endfor
	</tr>

	<tr>
		<td style="background-color: #143052; color: #FFFFFF; font-weight: bold;"> {{ $data['years'][1] }} SAP </td>
		@for($d=0; $d < sizeof($data['mtxDN']['previousAdSales']); $d++)
			@if($d == 12)
				<td style=" color: #FFFFFF; font-weight: bold; background-color: #143052;"> {{$data['mtxDN']['previousSAP'][$d]}} </td>
			@else
				<td style="background-color: #dce6f1; font-weight: bold;"> {{$data['mtxDN']['previousSAP'][$d]}} </td>
			@endif
			
		@endfor
	</tr>

	<tr>
		<td style="background-color: #143052; color: #FFFFFF; font-weight: bold;"> {{ $data['years'][0] }} Target </td>
		@for($d=0; $d < sizeof($data['mtxDN']['previousAdSales']); $d++)
			@if($d == 12)
				<td style="background-color: #143052; color: #FFFFFF; font-weight: bold;"> {{$data['mtxDN']['currentTarget'][$d]}} </td>
			@else
				<td style="background-color: #c3d8ef; font-weight: bold;">{{$data['mtxDN']['currentTarget'][$d]}} </td>
			@endif
		@endfor
	</tr>

	<tr>
		<td style="background-color: #143052; color: #FFFFFF; font-weight: bold;"> {{ $data['years'][0] }} Corporate </td>
		@for($d=0; $d < sizeof($data['mtxDN']['previousAdSales']); $d++)
			@if($d == 12)
				<td style="color: #FFFFFF; font-weight: bold; background-color: #143052;"> {{$data['mtxDN']['currentCorporate'][$d]}} </td>
			@else
				<td style="background-color: #dce6f1; font-weight: bold;"> {{$data['mtxDN']['currentCorporate'][$d]}} </td>
			@endif
		@endfor
	</tr>

	<tr>
		<td style="background-color: #143052; color: #FFFFFF; font-weight: bold;"> {{ $data['years'][0] }} Ad Sales </td>
		@for($d=0; $d < sizeof($data['mtxDN']['previousAdSales']); $d++)
			@if($d == 12)
				<td style="color: #FFFFFF; font-weight: bold; background-color: #143052;"> {{$data['mtxDN']['currentAdSales'][$d]}} </td>
			@else
				<td style="background-color: #c3d8ef; font-weight: bold;"> {{$data['mtxDN']['currentAdSales'][$d]}} </td>
			@endif
		@endfor
	</tr>

	<tr>
		<td style="background-color: #143052; color: #FFFFFF; font-weight: bold;"> {{ $data['years'][0] }} SAP </td>
		@for($d=0; $d < sizeof($data['mtxDN']['previousAdSales']); $d++)
			@if($d == 12)
				<td style="color: #FFFFFF; font-weight: bold; background-color: #143052;"> {{$data['mtxDN']['currentSAP'][$d]}} </td>
			@else
				<td style="background-color: #dce6f1; font-weight: bold;"> {{$data['mtxDN']['currentSAP'][$d]}} </td>
			@endif
		@endfor
	</tr>

	<tr>
		<td style="background-color: #143052; color: #FFFFFF; font-weight: bold;"> %({{ $data['years'][0] }}F - 2019) </td>
		@for($d=0; $d < sizeof($data['mtxDN']['previousAdSales']); $d++)
			@if($d == 12)
				@if($data['mtxDN']['previousAdSales'][$d] > 0)
					<td style="color: #FFFFFF; font-weight: bold; background-color: #143052; text-align: right;">{{number_format($data['mtxDN']['currentCorporate'][$d]/$data['mtxDN']['previousAdSales'][$d],2)*100}}%</td>
				@else
					<td style=" color: #FFFFFF; font-weight: bold; background-color: #143052; text-align: right;">0.0</td>
				@endif
			@else
				@if($data['mtxDN']['previousAdSales'][$d] > 0)
					<td style=" background-color: #c3d8ef; font-weight: bold; text-align: right;">{{number_format($data['mtxDN']['currentCorporate'][$d]/$data['mtxDN']['previousAdSales'][$d],2)*100}}%</td>
				@else
					<td style="background-color: #c3d8ef; font-weight: bold; text-align: right;">0.0</td>
				@endif
			@endif
		@endfor
	</tr>

	<tr>
		<td style="background-color: #143052; color: #FFFFFF; font-weight: bold;"> %({{ $data['years'][0] }}F - Target) </td>
		@for($d=0; $d < sizeof($data['mtxDN']['previousAdSales']); $d++)
			@if($data['mtxDN']['currentTarget'][$d] == 0)
				@if($d == 12)
					<td style="color: #FFFFFF; font-weight: bold; background-color: #143052; text-align: right;">0%</td>
				@else
					<td style="background-color: #dce6f1; font-weight: bold; text-align: right;">0%</td>
				@endif
			@else
				@if($d == 12)
					@if($data['mtxDN']['previousAdSales'][$d] > 0)
						<td style=" color: #FFFFFF; font-weight: bold; background-color: #143052; text-align: right;">{{number_format($data['mtxDN']['currentCorporate'][$d]/$data['mtxDN']['currentTarget'][$d],2)*100}}%</td>
					@else
						<td style="color: #FFFFFF; font-weight: bold; background-color: #143052; text-align: right;">0.0</td>
					@endif 
				@else
					@if($data['mtxDN']['previousAdSales'][$d] > 0)
						<td style="background-color: #dce6f1; font-weight: bold; text-align: right;">{{number_format($data['mtxDN']['currentCorporate'][$d]/$data['mtxDN']['currentTarget'][$d],2)*100}}%</td>
					@else
						<td style="background-color: #dce6f1; font-weight: bold; text-align: right;">0.0</td>
					@endif
				@endif
			@endif
		@endfor
	</tr>	
</table>
@for ($c=0; $c < sizeof($data['mtx']['previousAdSales']); $c++)
<table>
	<tr>
		<td style="background-color: #0070c0; color: #FFFFFF; font-weight: bold;"> {{ $data['brandID'][$c][1] }} </td>
    	@for($m=0; $m < sizeof($data['monthView']); $m++)
    		<td style="background-color: #a6a6a6; font-weight: bold; float: right;"> {{ $data['monthView'][$m] }} </td>
    	@endfor
    		<td style="background-color: #0f243e; color: #FFFFFF; font-weight: bold; float: right;"> Total </td>
    		<td style="background-color: #a6a6a6; font-weight: bold; float: right;"> YTD </td>
    	@for($q=0; $q < sizeof($data['quarter']); $q++)
    		<td style="background-color: #a6a6a6; font-weight: bold; float: right;">  {{ $data['quarter'][$q] }} </td>
    	@endfor
	</tr>

	<tr>
		<td style="background-color: #c3d8ef; font-weight: bold;"> {{ $data['years'][1] }} Ad Sales </td>
    	@for($d=0; $d < sizeof($data['mtx']['previousAdSales'][$c]); $d++)
    		@if($d == 12)
				<td style="color: #FFFFFF; font-weight: bold; background-color: #143052;">{{$data['mtx']['previousAdSales'][$c][$d]}} </td>
    		@else
				<td style="background-color: #c3d8ef; font-weight: bold;">{{$data['mtx']['previousAdSales'][$c][$d]}} </td>
    		@endif
    	@endfor
	</tr>

	<tr>
		<td style="background-color: #dce6f1; font-weight: bold;"> {{ $data['years'][1] }} SAP </td>
    	@for($d=0; $d < sizeof($data['mtx']['previousAdSales'][$c]); $d++)
    		@if($d == 12)
    			<td style="color: #FFFFFF; font-weight: bold; background-color: #143052;"> {{$data['mtx']['previousSAP'][$c][$d]}} </td>
    		@else
    			<td style="background-color: #dce6f1; font-weight: bold;"> {{$data['mtx']['previousSAP'][$c][$d]}} </td>
    		@endif
    	@endfor
	</tr>

	<tr>
		<td style="background-color: #c3d8ef; font-weight: bold;"> {{ $data['years'][0] }} Target </td>
    	@for($d=0; $d < sizeof($data['mtx']['previousAdSales'][$c]); $d++)
    		@if($d == 12)
    			<td style=" color: #FFFFFF; font-weight: bold; background-color: #143052;">{{$data['mtx']['currentTarget'][$c][$d]}} </td>
    		@else
    			<td style="background-color: #c3d8ef; font-weight: bold;">{{$data['mtx']['currentTarget'][$c][$d]}} </td>
    		@endif
    	@endfor
	</tr>
	
	<tr>
		<td style="background-color: #dce6f1; font-weight: bold;"> {{ $data['years'][0] }} Corporate </td>
    	@for($d=0; $d < sizeof($data['mtx']['previousAdSales'][$c]); $d++)
    		@if($d == 12)
    			<td style="color: #FFFFFF; font-weight: bold; background-color: #143052;"> {{$data['mtx']['currentCorporate'][$c][$d]}} </td>
    		@else
    			<td style="background-color: #dce6f1; font-weight: bold;"> {{$data['mtx']['currentCorporate'][$c][$d]}} </td>
    		@endif
    	@endfor
	</tr>

	<tr>
		<td style="background-color: #c3d8ef; font-weight: bold;"> {{ $data['years'][0] }} Ad Sales </td>
    	@for($d=0; $d < sizeof($data['mtx']['previousAdSales'][$c]); $d++)
    		@if($d == 12)
    			<td style="color: #FFFFFF; font-weight: bold; background-color: #143052;"> {{$data['mtx']['currentAdSales'][$c][$d]}} </td>
    		@else
    			<td style="background-color: #c3d8ef; font-weight: bold;"> {{$data['mtx']['currentAdSales'][$c][$d]}} </td>
    		@endif
    	@endfor
	</tr>

	<tr>
		<td style="background-color: #dce6f1; font-weight: bold; "> {{ $data['years'][0] }} SAP </td>
    	@for($d=0; $d < sizeof($data['mtx']['previousAdSales'][$c]); $d++)
    		@if($d == 12)
    			<td style="color: #FFFFFF; font-weight: bold; background-color: #143052;"> {{$data['mtx']['currentSAP'][$c][$d]}} </td>
    		@else
    			<td style="background-color: #dce6f1; font-weight: bold;"> {{$data['mtx']['currentSAP'][$c][$d]}} </td>
    		@endif
    	@endfor
	</tr>

	<tr>
		<td style="background-color: #c3d8ef; font-weight: bold;"> %({{ $data['years'][0] }}F - 2019) </td>
    	@for($d=0; $d < sizeof($data['mtx']['previousAdSales'][$c]); $d++)
    		@if($d == 12)
    			@if($data['mtx']['previousAdSales'][$c][$d] > 0)
					<td style="color: #FFFFFF; font-weight: bold; background-color: #143052; text-align: right;">{{number_format($data['mtx']['currentCorporate'][$c][$d]/$data['mtx']['previousAdSales'][$c][$d],2)*100}}%</td> 
				@else
					<td style="color: #FFFFFF; font-weight: bold; background-color: #143052; text-align: right;">0.0</td>
				@endif
    		@else
    			@if($data['mtx']['previousAdSales'][$c][$d] > 0)
					<td style="background-color: #c3d8ef; font-weight: bold; text-align: right;">{{number_format($data['mtx']['currentCorporate'][$c][$d]/$data['mtx']['previousAdSales'][$c][$d],2)*100}}%</td> 
				@else
					<td style="background-color: #c3d8ef; font-weight: bold; text-align: right;">0.0</td>
				@endif
    		@endif				        		 
    	@endfor
	</tr>

	<tr>
		<td style="background-color: #dce6f1; font-weight: bold;"> %({{ $data['years'][0] }}F - Target) </td>
    	@for($d=0; $d < sizeof($data['mtx']['previousAdSales'][$c]); $d++)

    		@if($data['mtxDN']['currentTarget'][$d] == 0)
				@if($d == 12)
					<td style="color: #FFFFFF; font-weight: bold; background-color: #143052; text-align: right;">0%</td>
				@else
					<td style="background-color: #dce6f1; font-weight: bold; text-align: right;">0%</td>
				@endif
			@else
				@if($d == 12)
					@if($data['mtxDN']['previousAdSales'][$d] > 0)
						<td style=" color: #FFFFFF; font-weight: bold; background-color: #143052; text-align: right;">{{number_format($data['mtxDN']['currentCorporate'][$d]/$data['mtxDN']['currentTarget'][$d],2)*100}}%</td>
					@else
						<td style="color: #FFFFFF; font-weight: bold; background-color: #143052; text-align: right;">0.0</td>
					@endif 
				@else
					@if($data['mtxDN']['previousAdSales'][$d] > 0)
						<td style="background-color: #dce6f1; font-weight: bold; text-align: right;">{{number_format($data['mtxDN']['currentCorporate'][$d]/$data['mtxDN']['currentTarget'][$d],2)*100}}%</td>
					@else
						<td style="background-color: #dce6f1; font-weight: bold; text-align: right;">0.0</td>
					@endif
				@endif
			@endif
    	@endfor
	</tr>	
</table>	
@endfor

@elseif($data['type'] == 'ae')

<td style="background-color: #0070c0; color: #FFFFFF; font-weight: bold;" colspan="19">Consolidate - AE : ({{$data['currencyS']}}/{{strtoupper($data['value'])}})
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
			<td style="background-color: #a6a6a6; font-weight: bold; float: right;"> {{ $data['monthView'][$m] }} </td>
		@endfor
			<td style="background-color: #0f243e; color: #FFFFFF; font-weight: bold; float: right;"> Total </td>
			<td style="background-color: #a6a6a6; font-weight: bold; float: right;"> YTD </td>
		@for($q=0; $q < sizeof($data['quarter']); $q++)
			<td style="background-color: #a6a6a6; font-weight: bold; float: right;"> {{ $data['quarter'][$q] }} </td>
		@endfor
	</tr>

	<tr>
		<td style="background-color: #143052; color: #FFFFFF; font-weight: bold;"> {{ $data['years'][1] }} Ad Sales </td>
		@for($d=0; $d < sizeof($data['mtxDN']['previousAdSales']); $d++)
			@if($d == 12)
				<td style=" color: #FFFFFF; font-weight: bold; background-color: #143052;"> {{$data['mtxDN']['previousAdSales'][$d]}} </td>
			@else 
				<td style="background-color: #c3d8ef; font-weight: bold;"> {{$data['mtxDN']['previousAdSales'][$d]}} </td>
			@endif			
		@endfor
	</tr>

	<tr>
		<td style="background-color: #143052; color: #FFFFFF; font-weight: bold;"> {{ $data['years'][1] }} SAP </td>
		@for($d=0; $d < sizeof($data['mtxDN']['previousAdSales']); $d++)
			@if($d == 12)
				<td style=" color: #FFFFFF; font-weight: bold; background-color: #143052;"> {{$data['mtxDN']['previousSAP'][$d]}} </td>
			@else
				<td style="background-color: #dce6f1; font-weight: bold;"> {{$data['mtxDN']['previousSAP'][$d]}} </td>
			@endif
			
		@endfor
	</tr>

	<tr>
		<td style="background-color: #143052; color: #FFFFFF; font-weight: bold;"> {{ $data['years'][0] }} Target </td>
		@for($d=0; $d < sizeof($data['mtxDN']['previousAdSales']); $d++)
			@if($d == 12)
				<td style="background-color: #143052; color: #FFFFFF; font-weight: bold;"> {{$data['mtxDN']['currentTarget'][$d]}} </td>
			@else
				<td style="background-color: #c3d8ef; font-weight: bold;">{{$data['mtxDN']['currentTarget'][$d]}} </td>
			@endif
		@endfor
	</tr>

	<tr>
		<td style="background-color: #143052; color: #FFFFFF; font-weight: bold;"> {{ $data['years'][0] }} Corporate </td>
		@for($d=0; $d < sizeof($data['mtxDN']['previousAdSales']); $d++)
			@if($d == 12)
				<td style="color: #FFFFFF; font-weight: bold; background-color: #143052;"> {{$data['mtxDN']['currentCorporate'][$d]}} </td>
			@else
				<td style="background-color: #dce6f1; font-weight: bold;"> {{$data['mtxDN']['currentCorporate'][$d]}} </td>
			@endif
		@endfor
	</tr>

	<tr>
		<td style="background-color: #143052; color: #FFFFFF; font-weight: bold;"> {{ $data['years'][0] }} Ad Sales </td>
		@for($d=0; $d < sizeof($data['mtxDN']['previousAdSales']); $d++)
			@if($d == 12)
				<td style="color: #FFFFFF; font-weight: bold; background-color: #143052;"> {{$data['mtxDN']['currentAdSales'][$d]}} </td>
			@else
				<td style="background-color: #c3d8ef; font-weight: bold;"> {{$data['mtxDN']['currentAdSales'][$d]}} </td>
			@endif
		@endfor
	</tr>

	<tr>
		<td style="background-color: #143052; color: #FFFFFF; font-weight: bold;"> {{ $data['years'][0] }} SAP </td>
		@for($d=0; $d < sizeof($data['mtxDN']['previousAdSales']); $d++)
			@if($d == 12)
				<td style="color: #FFFFFF; font-weight: bold; background-color: #143052;"> {{$data['mtxDN']['currentSAP'][$d]}} </td>
			@else
				<td style="background-color: #dce6f1; font-weight: bold;"> {{$data['mtxDN']['currentSAP'][$d]}} </td>
			@endif
		@endfor
	</tr>

	<tr>
		<td style="background-color: #143052; color: #FFFFFF; font-weight: bold;"> %({{ $data['years'][0] }}F - 2019) </td>
		@for($d=0; $d < sizeof($data['mtxDN']['previousAdSales']); $d++)
			@if($d == 12)
				@if($data['mtxDN']['previousAdSales'][$d] > 0)
					<td style="color: #FFFFFF; font-weight: bold; background-color: #143052; text-align: right;">{{number_format($data['mtxDN']['currentCorporate'][$d]/$data['mtxDN']['previousAdSales'][$d],2)*100}}%</td>
				@else
					<td style=" color: #FFFFFF; font-weight: bold; background-color: #143052; text-align: right;">0.0</td>
				@endif
			@else
				@if($data['mtxDN']['previousAdSales'][$d] > 0)
					<td style=" background-color: #c3d8ef; font-weight: bold; text-align: right;">{{number_format($data['mtxDN']['currentCorporate'][$d]/$data['mtxDN']['previousAdSales'][$d],2)*100}}%</td>
				@else
					<td style="background-color: #c3d8ef; font-weight: bold; text-align: right;">0.0</td>
				@endif
			@endif
		@endfor
	</tr>

	<tr>
		<td style="background-color: #143052; color: #FFFFFF; font-weight: bold;"> %({{ $data['years'][0] }}F - Target) </td>
		@for($d=0; $d < sizeof($data['mtxDN']['previousAdSales']); $d++)
			@if($data['mtxDN']['currentTarget'][$d] == 0)
				@if($d == 12)
					<td style="color: #FFFFFF; font-weight: bold; background-color: #143052; text-align: right;">0%</td>
				@else
					<td style="background-color: #dce6f1; font-weight: bold; text-align: right;">0%</td>
				@endif
			@else
				@if($d == 12)
					@if($data['mtxDN']['previousAdSales'][$d] > 0)
						<td style=" color: #FFFFFF; font-weight: bold; background-color: #143052; text-align: right;">{{number_format($data['mtxDN']['currentCorporate'][$d]/$data['mtxDN']['currentTarget'][$d],2)*100}}%</td>
					@else
						<td style="color: #FFFFFF; font-weight: bold; background-color: #143052; text-align: right;">0.0</td>
					@endif 
				@else
					@if($data['mtxDN']['previousAdSales'][$d] > 0)
						<td style="background-color: #dce6f1; font-weight: bold; text-align: right;">{{number_format($data['mtxDN']['currentCorporate'][$d]/$data['mtxDN']['currentTarget'][$d],2)*100}}%</td>
					@else
						<td style="background-color: #dce6f1; font-weight: bold; text-align: right;">0.0</td>
					@endif
				@endif
			@endif
		@endfor
	</tr>	
</table>
@for ($c=0; $c < sizeof($data['mtx']['previousAdSales']); $c++)
<table>
	<tr>
		<td style="background-color: #0070c0; color: #FFFFFF; font-weight: bold;"> {{ $data['typeSelectS'][$c]['salesRep'] }} </td>
    	@for($m=0; $m < sizeof($data['monthView']); $m++)
    		<td style="background-color: #a6a6a6; font-weight: bold; float: right;"> {{ $data['monthView'][$m] }} </td>
    	@endfor
    		<td style="background-color: #0f243e; color: #FFFFFF; font-weight: bold; float: right;"> Total </td>
    		<td style="background-color: #a6a6a6; font-weight: bold; float: right;"> YTD </td>
    	@for($q=0; $q < sizeof($data['quarter']); $q++)
    		<td style="background-color: #a6a6a6; font-weight: bold; float: right;">  {{ $data['quarter'][$q] }} </td>
    	@endfor
	</tr>

	<tr>
		<td style="background-color: #c3d8ef; font-weight: bold;"> {{ $data['years'][1] }} Ad Sales </td>
    	@for($d=0; $d < sizeof($data['mtx']['previousAdSales'][$c]); $d++)
    		@if($d == 12)
				<td style="color: #FFFFFF; font-weight: bold; background-color: #143052;">{{$data['mtx']['previousAdSales'][$c][$d]}} </td>
    		@else
				<td style="background-color: #c3d8ef; font-weight: bold;">{{$data['mtx']['previousAdSales'][$c][$d]}} </td>
    		@endif
    	@endfor
	</tr>

	<tr>
		<td style="background-color: #dce6f1; font-weight: bold;"> {{ $data['years'][1] }} SAP </td>
    	@for($d=0; $d < sizeof($data['mtx']['previousAdSales'][$c]); $d++)
    		@if($d == 12)
    			<td style="color: #FFFFFF; font-weight: bold; background-color: #143052;"> {{$data['mtx']['previousSAP'][$c][$d]}} </td>
    		@else
    			<td style="background-color: #dce6f1; font-weight: bold;"> {{$data['mtx']['previousSAP'][$c][$d]}} </td>
    		@endif
    	@endfor
	</tr>

	<tr>
		<td style="background-color: #c3d8ef; font-weight: bold;"> {{ $data['years'][0] }} Target </td>
    	@for($d=0; $d < sizeof($data['mtx']['previousAdSales'][$c]); $d++)
    		@if($d == 12)
    			<td style=" color: #FFFFFF; font-weight: bold; background-color: #143052;">{{$data['mtx']['currentTarget'][$c][$d]}} </td>
    		@else
    			<td style="background-color: #c3d8ef; font-weight: bold;">{{$data['mtx']['currentTarget'][$c][$d]}} </td>
    		@endif
    	@endfor
	</tr>
	
	<tr>
		<td style="background-color: #dce6f1; font-weight: bold;"> {{ $data['years'][0] }} Corporate </td>
    	@for($d=0; $d < sizeof($data['mtx']['previousAdSales'][$c]); $d++)
    		@if($d == 12)
    			<td style="color: #FFFFFF; font-weight: bold; background-color: #143052;"> {{$data['mtx']['currentCorporate'][$c][$d]}} </td>
    		@else
    			<td style="background-color: #dce6f1; font-weight: bold;"> {{$data['mtx']['currentCorporate'][$c][$d]}} </td>
    		@endif
    	@endfor
	</tr>

	<tr>
		<td style="background-color: #c3d8ef; font-weight: bold;"> {{ $data['years'][0] }} Ad Sales </td>
    	@for($d=0; $d < sizeof($data['mtx']['previousAdSales'][$c]); $d++)
    		@if($d == 12)
    			<td style="color: #FFFFFF; font-weight: bold; background-color: #143052;"> {{$data['mtx']['currentAdSales'][$c][$d]}} </td>
    		@else
    			<td style="background-color: #c3d8ef; font-weight: bold;"> {{$data['mtx']['currentAdSales'][$c][$d]}} </td>
    		@endif
    	@endfor
	</tr>

	<tr>
		<td style="background-color: #dce6f1; font-weight: bold; "> {{ $data['years'][0] }} SAP </td>
    	@for($d=0; $d < sizeof($data['mtx']['previousAdSales'][$c]); $d++)
    		@if($d == 12)
    			<td style="color: #FFFFFF; font-weight: bold; background-color: #143052;"> {{$data['mtx']['currentSAP'][$c][$d]}} </td>
    		@else
    			<td style="background-color: #dce6f1; font-weight: bold;"> {{$data['mtx']['currentSAP'][$c][$d]}} </td>
    		@endif
    	@endfor
	</tr>

	<tr>
		<td style="background-color: #c3d8ef; font-weight: bold;"> %({{ $data['years'][0] }}F - 2019) </td>
    	@for($d=0; $d < sizeof($data['mtx']['previousAdSales'][$c]); $d++)
    		@if($d == 12)
    			@if($data['mtx']['previousAdSales'][$c][$d] > 0)
					<td style="color: #FFFFFF; font-weight: bold; background-color: #143052; text-align: right;">{{number_format($data['mtx']['currentCorporate'][$c][$d]/$data['mtx']['previousAdSales'][$c][$d],2)*100}}%</td> 
				@else
					<td style="color: #FFFFFF; font-weight: bold; background-color: #143052; text-align: right;">0.0</td>
				@endif
    		@else
    			@if($data['mtx']['previousAdSales'][$c][$d] > 0)
					<td style="background-color: #c3d8ef; font-weight: bold; text-align: right;">{{number_format($data['mtx']['currentCorporate'][$c][$d]/$data['mtx']['previousAdSales'][$c][$d],2)*100}}%</td> 
				@else
					<td style="background-color: #c3d8ef; font-weight: bold; text-align: right;">0.0</td>
				@endif
    		@endif				        		 
    	@endfor
	</tr>

	<tr>
		<td style="background-color: #dce6f1; font-weight: bold;"> %({{ $data['years'][0] }}F - Target) </td>
    	@for($d=0; $d < sizeof($data['mtx']['previousAdSales'][$c]); $d++)

    		@if($data['mtxDN']['currentTarget'][$d] == 0)
				@if($d == 12)
					<td style="color: #FFFFFF; font-weight: bold; background-color: #143052; text-align: right;">0%</td>
				@else
					<td style="background-color: #dce6f1; font-weight: bold; text-align: right;">0%</td>
				@endif
			@else
				@if($d == 12)
					@if($data['mtxDN']['previousAdSales'][$d] > 0)
						<td style=" color: #FFFFFF; font-weight: bold; background-color: #143052; text-align: right;">{{number_format($data['mtxDN']['currentCorporate'][$d]/$data['mtxDN']['currentTarget'][$d],2)*100}}%</td>
					@else
						<td style="color: #FFFFFF; font-weight: bold; background-color: #143052; text-align: right;">0.0</td>
					@endif 
				@else
					@if($data['mtxDN']['previousAdSales'][$d] > 0)
						<td style="background-color: #dce6f1; font-weight: bold; text-align: right;">{{number_format($data['mtxDN']['currentCorporate'][$d]/$data['mtxDN']['currentTarget'][$d],2)*100}}%</td>
					@else
						<td style="background-color: #dce6f1; font-weight: bold; text-align: right;">0.0</td>
					@endif
				@endif
			@endif
    	@endfor
	</tr>	
</table>	
@endfor

@elseif($data['type'] == 'advertiser')
<td style="background-color: #0070c0; color: #FFFFFF; font-weight: bold;" colspan="19">Consolidate - Advertiser : ({{$data['currencyS']}}/{{strtoupper($data['value'])}})
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
			<td style="background-color: #a6a6a6; font-weight: bold; float: right;"> {{ $data['monthView'][$m] }} </td>
		@endfor
			<td style="background-color: #0f243e; color: #FFFFFF; font-weight: bold; float: right;"> Total </td>
			<td style="background-color: #a6a6a6; font-weight: bold; float: right;"> YTD </td>
		@for($q=0; $q < sizeof($data['quarter']); $q++)
			<td style="background-color: #a6a6a6; font-weight: bold; float: right;"> {{ $data['quarter'][$q] }} </td>
		@endfor
	</tr>

	<tr>
		<td style="background-color: #143052; color: #FFFFFF; font-weight: bold;"> {{ $data['years'][1] }} Ad Sales </td>
		@for($d=0; $d < sizeof($data['mtxDN']['previousAdSales']); $d++)
			@if($d == 12)
				<td style=" color: #FFFFFF; font-weight: bold; background-color: #143052;"> {{$data['mtxDN']['previousAdSales'][$d]}} </td>
			@else 
				<td style="background-color: #c3d8ef; font-weight: bold;"> {{$data['mtxDN']['previousAdSales'][$d]}} </td>
			@endif			
		@endfor
	</tr>

	<tr>
		<td style="background-color: #143052; color: #FFFFFF; font-weight: bold;"> {{ $data['years'][1] }} SAP </td>
		@for($d=0; $d < sizeof($data['mtxDN']['previousAdSales']); $d++)
			@if($d == 12)
				<td style=" color: #FFFFFF; font-weight: bold; background-color: #143052;"> {{$data['mtxDN']['previousSAP'][$d]}} </td>
			@else
				<td style="background-color: #dce6f1; font-weight: bold;"> {{$data['mtxDN']['previousSAP'][$d]}} </td>
			@endif
			
		@endfor
	</tr>

	<tr>
		<td style="background-color: #143052; color: #FFFFFF; font-weight: bold;"> {{ $data['years'][0] }} Target </td>
		@for($d=0; $d < sizeof($data['mtxDN']['previousAdSales']); $d++)
			@if($d == 12)
				<td style="background-color: #143052; color: #FFFFFF; font-weight: bold;"> {{$data['mtxDN']['currentTarget'][$d]}} </td>
			@else
				<td style="background-color: #c3d8ef; font-weight: bold;">{{$data['mtxDN']['currentTarget'][$d]}} </td>
			@endif
		@endfor
	</tr>

	<tr>
		<td style="background-color: #143052; color: #FFFFFF; font-weight: bold;"> {{ $data['years'][0] }} Corporate </td>
		@for($d=0; $d < sizeof($data['mtxDN']['previousAdSales']); $d++)
			@if($d == 12)
				<td style="color: #FFFFFF; font-weight: bold; background-color: #143052;"> {{$data['mtxDN']['currentCorporate'][$d]}} </td>
			@else
				<td style="background-color: #dce6f1; font-weight: bold;"> {{$data['mtxDN']['currentCorporate'][$d]}} </td>
			@endif
		@endfor
	</tr>

	<tr>
		<td style="background-color: #143052; color: #FFFFFF; font-weight: bold;"> {{ $data['years'][0] }} Ad Sales </td>
		@for($d=0; $d < sizeof($data['mtxDN']['previousAdSales']); $d++)
			@if($d == 12)
				<td style="color: #FFFFFF; font-weight: bold; background-color: #143052;"> {{$data['mtxDN']['currentAdSales'][$d]}} </td>
			@else
				<td style="background-color: #c3d8ef; font-weight: bold;"> {{$data['mtxDN']['currentAdSales'][$d]}} </td>
			@endif
		@endfor
	</tr>

	<tr>
		<td style="background-color: #143052; color: #FFFFFF; font-weight: bold;"> {{ $data['years'][0] }} SAP </td>
		@for($d=0; $d < sizeof($data['mtxDN']['previousAdSales']); $d++)
			@if($d == 12)
				<td style="color: #FFFFFF; font-weight: bold; background-color: #143052;"> {{$data['mtxDN']['currentSAP'][$d]}} </td>
			@else
				<td style="background-color: #dce6f1; font-weight: bold;"> {{$data['mtxDN']['currentSAP'][$d]}} </td>
			@endif
		@endfor
	</tr>

	<tr>
		<td style="background-color: #143052; color: #FFFFFF; font-weight: bold;"> %({{ $data['years'][0] }}F - 2019) </td>
		@for($d=0; $d < sizeof($data['mtxDN']['previousAdSales']); $d++)
			@if($d == 12)
				@if($data['mtxDN']['previousAdSales'][$d] > 0)
					<td style="color: #FFFFFF; font-weight: bold; background-color: #143052; text-align: right;">{{number_format($data['mtxDN']['currentCorporate'][$d]/$data['mtxDN']['previousAdSales'][$d],2)*100}}%</td>
				@else
					<td style=" color: #FFFFFF; font-weight: bold; background-color: #143052; text-align: right;">0.0</td>
				@endif
			@else
				@if($data['mtxDN']['previousAdSales'][$d] > 0)
					<td style=" background-color: #c3d8ef; font-weight: bold; text-align: right;">{{number_format($data['mtxDN']['currentCorporate'][$d]/$data['mtxDN']['previousAdSales'][$d],2)*100}}%</td>
				@else
					<td style="background-color: #c3d8ef; font-weight: bold; text-align: right;">0.0</td>
				@endif
			@endif
		@endfor
	</tr>

	<tr>
		<td style="background-color: #143052; color: #FFFFFF; font-weight: bold;"> %({{ $data['years'][0] }}F - Target) </td>
		@for($d=0; $d < sizeof($data['mtxDN']['previousAdSales']); $d++)
			@if($data['mtxDN']['currentTarget'][$d] == 0)
				@if($d == 12)
					<td style="color: #FFFFFF; font-weight: bold; background-color: #143052; text-align: right;">0%</td>
				@else
					<td style="background-color: #dce6f1; font-weight: bold; text-align: right;">0%</td>
				@endif
			@else
				@if($d == 12)
					@if($data['mtxDN']['previousAdSales'][$d] > 0)
						<td style=" color: #FFFFFF; font-weight: bold; background-color: #143052; text-align: right;">{{number_format($data['mtxDN']['currentCorporate'][$d]/$data['mtxDN']['currentTarget'][$d],2)*100}}%</td>
					@else
						<td style="color: #FFFFFF; font-weight: bold; background-color: #143052; text-align: right;">0.0</td>
					@endif 
				@else
					@if($data['mtxDN']['previousAdSales'][$d] > 0)
						<td style="background-color: #dce6f1; font-weight: bold; text-align: right;">{{number_format($data['mtxDN']['currentCorporate'][$d]/$data['mtxDN']['currentTarget'][$d],2)*100}}%</td>
					@else
						<td style="background-color: #dce6f1; font-weight: bold; text-align: right;">0.0</td>
					@endif
				@endif
			@endif
		@endfor
	</tr>	
</table>
@for ($c=0; $c < sizeof($data['mtx']['previousAdSales']); $c++)
<table>
	<tr>
		<td style="background-color: #0070c0; color: #FFFFFF; font-weight: bold;"> {{ $data['typeSelectS'][$c]['client'] }} </td>
    	@for($m=0; $m < sizeof($data['monthView']); $m++)
    		<td style="background-color: #a6a6a6; font-weight: bold; float: right;"> {{ $data['monthView'][$m] }} </td>
    	@endfor
    		<td style="background-color: #0f243e; color: #FFFFFF; font-weight: bold; float: right;"> Total </td>
    		<td style="background-color: #a6a6a6; font-weight: bold; float: right;"> YTD </td>
    	@for($q=0; $q < sizeof($data['quarter']); $q++)
    		<td style="background-color: #a6a6a6; font-weight: bold; float: right;">  {{ $data['quarter'][$q] }} </td>
    	@endfor
	</tr>

	<tr>
		<td style="background-color: #c3d8ef; font-weight: bold;"> {{ $data['years'][1] }} Ad Sales </td>
    	@for($d=0; $d < sizeof($data['mtx']['previousAdSales'][$c]); $d++)
    		@if($d == 12)
				<td style="color: #FFFFFF; font-weight: bold; background-color: #143052;">{{$data['mtx']['previousAdSales'][$c][$d]}} </td>
    		@else
				<td style="background-color: #c3d8ef; font-weight: bold;">{{$data['mtx']['previousAdSales'][$c][$d]}} </td>
    		@endif
    	@endfor
	</tr>

	<tr>
		<td style="background-color: #dce6f1; font-weight: bold;"> {{ $data['years'][1] }} SAP </td>
    	@for($d=0; $d < sizeof($data['mtx']['previousAdSales'][$c]); $d++)
    		@if($d == 12)
    			<td style="color: #FFFFFF; font-weight: bold; background-color: #143052;"> {{$data['mtx']['previousSAP'][$c][$d]}} </td>
    		@else
    			<td style="background-color: #dce6f1; font-weight: bold;"> {{$data['mtx']['previousSAP'][$c][$d]}} </td>
    		@endif
    	@endfor
	</tr>

	<tr>
		<td style="background-color: #c3d8ef; font-weight: bold;"> {{ $data['years'][0] }} Target </td>
    	@for($d=0; $d < sizeof($data['mtx']['previousAdSales'][$c]); $d++)
    		@if($d == 12)
    			<td style=" color: #FFFFFF; font-weight: bold; background-color: #143052;">{{$data['mtx']['currentTarget'][$c][$d]}} </td>
    		@else
    			<td style="background-color: #c3d8ef; font-weight: bold;">{{$data['mtx']['currentTarget'][$c][$d]}} </td>
    		@endif
    	@endfor
	</tr>
	
	<tr>
		<td style="background-color: #dce6f1; font-weight: bold;"> {{ $data['years'][0] }} Corporate </td>
    	@for($d=0; $d < sizeof($data['mtx']['previousAdSales'][$c]); $d++)
    		@if($d == 12)
    			<td style="color: #FFFFFF; font-weight: bold; background-color: #143052;"> {{$data['mtx']['currentCorporate'][$c][$d]}} </td>
    		@else
    			<td style="background-color: #dce6f1; font-weight: bold;"> {{$data['mtx']['currentCorporate'][$c][$d]}} </td>
    		@endif
    	@endfor
	</tr>

	<tr>
		<td style="background-color: #c3d8ef; font-weight: bold;"> {{ $data['years'][0] }} Ad Sales </td>
    	@for($d=0; $d < sizeof($data['mtx']['previousAdSales'][$c]); $d++)
    		@if($d == 12)
    			<td style="color: #FFFFFF; font-weight: bold; background-color: #143052;"> {{$data['mtx']['currentAdSales'][$c][$d]}} </td>
    		@else
    			<td style="background-color: #c3d8ef; font-weight: bold;"> {{$data['mtx']['currentAdSales'][$c][$d]}} </td>
    		@endif
    	@endfor
	</tr>

	<tr>
		<td style="background-color: #dce6f1; font-weight: bold; "> {{ $data['years'][0] }} SAP </td>
    	@for($d=0; $d < sizeof($data['mtx']['previousAdSales'][$c]); $d++)
    		@if($d == 12)
    			<td style="color: #FFFFFF; font-weight: bold; background-color: #143052;"> {{$data['mtx']['currentSAP'][$c][$d]}} </td>
    		@else
    			<td style="background-color: #dce6f1; font-weight: bold;"> {{$data['mtx']['currentSAP'][$c][$d]}} </td>
    		@endif
    	@endfor
	</tr>

	<tr>
		<td style="background-color: #c3d8ef; font-weight: bold;"> %({{ $data['years'][0] }}F - 2019) </td>
    	@for($d=0; $d < sizeof($data['mtx']['previousAdSales'][$c]); $d++)
    		@if($d == 12)
    			@if($data['mtx']['previousAdSales'][$c][$d] > 0)
					<td style="color: #FFFFFF; font-weight: bold; background-color: #143052; text-align: right;">{{number_format($data['mtx']['currentCorporate'][$c][$d]/$data['mtx']['previousAdSales'][$c][$d],2)*100}}%</td> 
				@else
					<td style="color: #FFFFFF; font-weight: bold; background-color: #143052; text-align: right;">0.0</td>
				@endif
    		@else
    			@if($data['mtx']['previousAdSales'][$c][$d] > 0)
					<td style="background-color: #c3d8ef; font-weight: bold; text-align: right;">{{number_format($data['mtx']['currentCorporate'][$c][$d]/$data['mtx']['previousAdSales'][$c][$d],2)*100}}%</td> 
				@else
					<td style="background-color: #c3d8ef; font-weight: bold; text-align: right;">0.0</td>
				@endif
    		@endif				        		 
    	@endfor
	</tr>

	<tr>
		<td style="background-color: #dce6f1; font-weight: bold;"> %({{ $data['years'][0] }}F - Target) </td>
    	@for($d=0; $d < sizeof($data['mtx']['previousAdSales'][$c]); $d++)

    		@if($data['mtxDN']['currentTarget'][$d] == 0)
				@if($d == 12)
					<td style="color: #FFFFFF; font-weight: bold; background-color: #143052; text-align: right;">0%</td>
				@else
					<td style="background-color: #dce6f1; font-weight: bold; text-align: right;">0%</td>
				@endif
			@else
				@if($d == 12)
					@if($data['mtxDN']['previousAdSales'][$d] > 0)
						<td style=" color: #FFFFFF; font-weight: bold; background-color: #143052; text-align: right;">{{number_format($data['mtxDN']['currentCorporate'][$d]/$data['mtxDN']['currentTarget'][$d],2)*100}}%</td>
					@else
						<td style="color: #FFFFFF; font-weight: bold; background-color: #143052; text-align: right;">0.0</td>
					@endif 
				@else
					@if($data['mtxDN']['previousAdSales'][$d] > 0)
						<td style="background-color: #dce6f1; font-weight: bold; text-align: right;">{{number_format($data['mtxDN']['currentCorporate'][$d]/$data['mtxDN']['currentTarget'][$d],2)*100}}%</td>
					@else
						<td style="background-color: #dce6f1; font-weight: bold; text-align: right;">0.0</td>
					@endif
				@endif
			@endif
    	@endfor
	</tr>	
</table>	
@endfor

@elseif($data['type'] == 'agency')

<td style="background-color: #0070c0; color: #FFFFFF; font-weight: bold;" colspan="19">Consolidate - Agency : ({{$data['currencyS']}}/{{strtoupper($data['value'])}})
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
			<td style="background-color: #a6a6a6; font-weight: bold; float: right;"> {{ $data['monthView'][$m] }} </td>
		@endfor
			<td style="background-color: #0f243e; color: #FFFFFF; font-weight: bold; float: right;"> Total </td>
			<td style="background-color: #a6a6a6; font-weight: bold; float: right;"> YTD </td>
		@for($q=0; $q < sizeof($data['quarter']); $q++)
			<td style="background-color: #a6a6a6; font-weight: bold; float: right;"> {{ $data['quarter'][$q] }} </td>
		@endfor
	</tr>

	<tr>
		<td style="background-color: #143052; color: #FFFFFF; font-weight: bold;"> {{ $data['years'][1] }} Ad Sales </td>
		@for($d=0; $d < sizeof($data['mtxDN']['previousAdSales']); $d++)
			@if($d == 12)
				<td style=" color: #FFFFFF; font-weight: bold; background-color: #143052;"> {{$data['mtxDN']['previousAdSales'][$d]}} </td>
			@else 
				<td style="background-color: #c3d8ef; font-weight: bold;"> {{$data['mtxDN']['previousAdSales'][$d]}} </td>
			@endif			
		@endfor
	</tr>

	<tr>
		<td style="background-color: #143052; color: #FFFFFF; font-weight: bold;"> {{ $data['years'][1] }} SAP </td>
		@for($d=0; $d < sizeof($data['mtxDN']['previousAdSales']); $d++)
			@if($d == 12)
				<td style=" color: #FFFFFF; font-weight: bold; background-color: #143052;"> {{$data['mtxDN']['previousSAP'][$d]}} </td>
			@else
				<td style="background-color: #dce6f1; font-weight: bold;"> {{$data['mtxDN']['previousSAP'][$d]}} </td>
			@endif
			
		@endfor
	</tr>

	<tr>
		<td style="background-color: #143052; color: #FFFFFF; font-weight: bold;"> {{ $data['years'][0] }} Target </td>
		@for($d=0; $d < sizeof($data['mtxDN']['previousAdSales']); $d++)
			@if($d == 12)
				<td style="background-color: #143052; color: #FFFFFF; font-weight: bold;"> {{$data['mtxDN']['currentTarget'][$d]}} </td>
			@else
				<td style="background-color: #c3d8ef; font-weight: bold;">{{$data['mtxDN']['currentTarget'][$d]}} </td>
			@endif
		@endfor
	</tr>

	<tr>
		<td style="background-color: #143052; color: #FFFFFF; font-weight: bold;"> {{ $data['years'][0] }} Corporate </td>
		@for($d=0; $d < sizeof($data['mtxDN']['previousAdSales']); $d++)
			@if($d == 12)
				<td style="color: #FFFFFF; font-weight: bold; background-color: #143052;"> {{$data['mtxDN']['currentCorporate'][$d]}} </td>
			@else
				<td style="background-color: #dce6f1; font-weight: bold;"> {{$data['mtxDN']['currentCorporate'][$d]}} </td>
			@endif
		@endfor
	</tr>

	<tr>
		<td style="background-color: #143052; color: #FFFFFF; font-weight: bold;"> {{ $data['years'][0] }} Ad Sales </td>
		@for($d=0; $d < sizeof($data['mtxDN']['previousAdSales']); $d++)
			@if($d == 12)
				<td style="color: #FFFFFF; font-weight: bold; background-color: #143052;"> {{$data['mtxDN']['currentAdSales'][$d]}} </td>
			@else
				<td style="background-color: #c3d8ef; font-weight: bold;"> {{$data['mtxDN']['currentAdSales'][$d]}} </td>
			@endif
		@endfor
	</tr>

	<tr>
		<td style="background-color: #143052; color: #FFFFFF; font-weight: bold;"> {{ $data['years'][0] }} SAP </td>
		@for($d=0; $d < sizeof($data['mtxDN']['previousAdSales']); $d++)
			@if($d == 12)
				<td style="color: #FFFFFF; font-weight: bold; background-color: #143052;"> {{$data['mtxDN']['currentSAP'][$d]}} </td>
			@else
				<td style="background-color: #dce6f1; font-weight: bold;"> {{$data['mtxDN']['currentSAP'][$d]}} </td>
			@endif
		@endfor
	</tr>

	<tr>
		<td style="background-color: #143052; color: #FFFFFF; font-weight: bold;"> %({{ $data['years'][0] }}F - 2019) </td>
		@for($d=0; $d < sizeof($data['mtxDN']['previousAdSales']); $d++)
			@if($d == 12)
				@if($data['mtxDN']['previousAdSales'][$d] > 0)
					<td style="color: #FFFFFF; font-weight: bold; background-color: #143052; text-align: right;">{{number_format($data['mtxDN']['currentCorporate'][$d]/$data['mtxDN']['previousAdSales'][$d],2)*100}}%</td>
				@else
					<td style=" color: #FFFFFF; font-weight: bold; background-color: #143052; text-align: right;">0.0</td>
				@endif
			@else
				@if($data['mtxDN']['previousAdSales'][$d] > 0)
					<td style=" background-color: #c3d8ef; font-weight: bold; text-align: right;">{{number_format($data['mtxDN']['currentCorporate'][$d]/$data['mtxDN']['previousAdSales'][$d],2)*100}}%</td>
				@else
					<td style="background-color: #c3d8ef; font-weight: bold; text-align: right;">0.0</td>
				@endif
			@endif
		@endfor
	</tr>

	<tr>
		<td style="background-color: #143052; color: #FFFFFF; font-weight: bold;"> %({{ $data['years'][0] }}F - Target) </td>
		@for($d=0; $d < sizeof($data['mtxDN']['previousAdSales']); $d++)
			@if($data['mtxDN']['currentTarget'][$d] == 0)
				@if($d == 12)
					<td style="color: #FFFFFF; font-weight: bold; background-color: #143052; text-align: right;">0%</td>
				@else
					<td style="background-color: #dce6f1; font-weight: bold; text-align: right;">0%</td>
				@endif
			@else
				@if($d == 12)
					@if($data['mtxDN']['previousAdSales'][$d] > 0)
						<td style=" color: #FFFFFF; font-weight: bold; background-color: #143052; text-align: right;">{{number_format($data['mtxDN']['currentCorporate'][$d]/$data['mtxDN']['currentTarget'][$d],2)*100}}%</td>
					@else
						<td style="color: #FFFFFF; font-weight: bold; background-color: #143052; text-align: right;">0.0</td>
					@endif 
				@else
					@if($data['mtxDN']['previousAdSales'][$d] > 0)
						<td style="background-color: #dce6f1; font-weight: bold; text-align: right;">{{number_format($data['mtxDN']['currentCorporate'][$d]/$data['mtxDN']['currentTarget'][$d],2)*100}}%</td>
					@else
						<td style="background-color: #dce6f1; font-weight: bold; text-align: right;">0.0</td>
					@endif
				@endif
			@endif
		@endfor
	</tr>	
</table>
@for ($c=0; $c < sizeof($data['mtx']['previousAdSales']); $c++)
<table>
	<tr>
		<td style="background-color: #0070c0; color: #FFFFFF; font-weight: bold;"> {{ $data['typeSelectS'][$c]['agency'] }} </td>
    	@for($m=0; $m < sizeof($data['monthView']); $m++)
    		<td style="background-color: #a6a6a6; font-weight: bold; float: right;"> {{ $data['monthView'][$m] }} </td>
    	@endfor
    		<td style="background-color: #0f243e; color: #FFFFFF; font-weight: bold; float: right;"> Total </td>
    		<td style="background-color: #a6a6a6; font-weight: bold; float: right;"> YTD </td>
    	@for($q=0; $q < sizeof($data['quarter']); $q++)
    		<td style="background-color: #a6a6a6; font-weight: bold; float: right;">  {{ $data['quarter'][$q] }} </td>
    	@endfor
	</tr>

	<tr>
		<td style="background-color: #c3d8ef; font-weight: bold;"> {{ $data['years'][1] }} Ad Sales </td>
    	@for($d=0; $d < sizeof($data['mtx']['previousAdSales'][$c]); $d++)
    		@if($d == 12)
				<td style="color: #FFFFFF; font-weight: bold; background-color: #143052;">{{$data['mtx']['previousAdSales'][$c][$d]}} </td>
    		@else
				<td style="background-color: #c3d8ef; font-weight: bold;">{{$data['mtx']['previousAdSales'][$c][$d]}} </td>
    		@endif
    	@endfor
	</tr>

	<tr>
		<td style="background-color: #dce6f1; font-weight: bold;"> {{ $data['years'][1] }} SAP </td>
    	@for($d=0; $d < sizeof($data['mtx']['previousAdSales'][$c]); $d++)
    		@if($d == 12)
    			<td style="color: #FFFFFF; font-weight: bold; background-color: #143052;"> {{$data['mtx']['previousSAP'][$c][$d]}} </td>
    		@else
    			<td style="background-color: #dce6f1; font-weight: bold;"> {{$data['mtx']['previousSAP'][$c][$d]}} </td>
    		@endif
    	@endfor
	</tr>

	<tr>
		<td style="background-color: #c3d8ef; font-weight: bold;"> {{ $data['years'][0] }} Target </td>
    	@for($d=0; $d < sizeof($data['mtx']['previousAdSales'][$c]); $d++)
    		@if($d == 12)
    			<td style=" color: #FFFFFF; font-weight: bold; background-color: #143052;">{{$data['mtx']['currentTarget'][$c][$d]}} </td>
    		@else
    			<td style="background-color: #c3d8ef; font-weight: bold;">{{$data['mtx']['currentTarget'][$c][$d]}} </td>
    		@endif
    	@endfor
	</tr>
	
	<tr>
		<td style="background-color: #dce6f1; font-weight: bold;"> {{ $data['years'][0] }} Corporate </td>
    	@for($d=0; $d < sizeof($data['mtx']['previousAdSales'][$c]); $d++)
    		@if($d == 12)
    			<td style="color: #FFFFFF; font-weight: bold; background-color: #143052;"> {{$data['mtx']['currentCorporate'][$c][$d]}} </td>
    		@else
    			<td style="background-color: #dce6f1; font-weight: bold;"> {{$data['mtx']['currentCorporate'][$c][$d]}} </td>
    		@endif
    	@endfor
	</tr>

	<tr>
		<td style="background-color: #c3d8ef; font-weight: bold;"> {{ $data['years'][0] }} Ad Sales </td>
    	@for($d=0; $d < sizeof($data['mtx']['previousAdSales'][$c]); $d++)
    		@if($d == 12)
    			<td style="color: #FFFFFF; font-weight: bold; background-color: #143052;"> {{$data['mtx']['currentAdSales'][$c][$d]}} </td>
    		@else
    			<td style="background-color: #c3d8ef; font-weight: bold;"> {{$data['mtx']['currentAdSales'][$c][$d]}} </td>
    		@endif
    	@endfor
	</tr>

	<tr>
		<td style="background-color: #dce6f1; font-weight: bold; "> {{ $data['years'][0] }} SAP </td>
    	@for($d=0; $d < sizeof($data['mtx']['previousAdSales'][$c]); $d++)
    		@if($d == 12)
    			<td style="color: #FFFFFF; font-weight: bold; background-color: #143052;"> {{$data['mtx']['currentSAP'][$c][$d]}} </td>
    		@else
    			<td style="background-color: #dce6f1; font-weight: bold;"> {{$data['mtx']['currentSAP'][$c][$d]}} </td>
    		@endif
    	@endfor
	</tr>

	<tr>
		<td style="background-color: #c3d8ef; font-weight: bold;"> %({{ $data['years'][0] }}F - 2019) </td>
    	@for($d=0; $d < sizeof($data['mtx']['previousAdSales'][$c]); $d++)
    		@if($d == 12)
    			@if($data['mtx']['previousAdSales'][$c][$d] > 0)
					<td style="color: #FFFFFF; font-weight: bold; background-color: #143052; text-align: right;">{{number_format($data['mtx']['currentCorporate'][$c][$d]/$data['mtx']['previousAdSales'][$c][$d],2)*100}}%</td> 
				@else
					<td style="color: #FFFFFF; font-weight: bold; background-color: #143052; text-align: right;">0.0</td>
				@endif
    		@else
    			@if($data['mtx']['previousAdSales'][$c][$d] > 0)
					<td style="background-color: #c3d8ef; font-weight: bold; text-align: right;">{{number_format($data['mtx']['currentCorporate'][$c][$d]/$data['mtx']['previousAdSales'][$c][$d],2)*100}}%</td> 
				@else
					<td style="background-color: #c3d8ef; font-weight: bold; text-align: right;">0.0</td>
				@endif
    		@endif				        		 
    	@endfor
	</tr>

	<tr>
		<td style="background-color: #dce6f1; font-weight: bold;"> %({{ $data['years'][0] }}F - Target) </td>
    	@for($d=0; $d < sizeof($data['mtx']['previousAdSales'][$c]); $d++)

    		@if($data['mtxDN']['currentTarget'][$d] == 0)
				@if($d == 12)
					<td style="color: #FFFFFF; font-weight: bold; background-color: #143052; text-align: right;">0%</td>
				@else
					<td style="background-color: #dce6f1; font-weight: bold; text-align: right;">0%</td>
				@endif
			@else
				@if($d == 12)
					@if($data['mtxDN']['previousAdSales'][$d] > 0)
						<td style=" color: #FFFFFF; font-weight: bold; background-color: #143052; text-align: right;">{{number_format($data['mtxDN']['currentCorporate'][$d]/$data['mtxDN']['currentTarget'][$d],2)*100}}%</td>
					@else
						<td style="color: #FFFFFF; font-weight: bold; background-color: #143052; text-align: right;">0.0</td>
					@endif 
				@else
					@if($data['mtxDN']['previousAdSales'][$d] > 0)
						<td style="background-color: #dce6f1; font-weight: bold; text-align: right;">{{number_format($data['mtxDN']['currentCorporate'][$d]/$data['mtxDN']['currentTarget'][$d],2)*100}}%</td>
					@else
						<td style="background-color: #dce6f1; font-weight: bold; text-align: right;">0.0</td>
					@endif
				@endif
			@endif
    	@endfor
	</tr>	
</table>	
@endfor

@elseif($data['type'] == 'agencyGroup')

<td style="background-color: #0070c0; color: #FFFFFF; font-weight: bold;" colspan="19">Consolidate - Agency Group : ({{$data['currencyS']}}/{{strtoupper($data['value'])}})
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
			<td style="background-color: #a6a6a6; font-weight: bold; float: right;"> {{ $data['monthView'][$m] }} </td>
		@endfor
			<td style="background-color: #0f243e; color: #FFFFFF; font-weight: bold; float: right;"> Total </td>
			<td style="background-color: #a6a6a6; font-weight: bold; float: right;"> YTD </td>
		@for($q=0; $q < sizeof($data['quarter']); $q++)
			<td style="background-color: #a6a6a6; font-weight: bold; float: right;"> {{ $data['quarter'][$q] }} </td>
		@endfor
	</tr>

	<tr>
		<td style="background-color: #143052; color: #FFFFFF; font-weight: bold;"> {{ $data['years'][1] }} Ad Sales </td>
		@for($d=0; $d < sizeof($data['mtxDN']['previousAdSales']); $d++)
			@if($d == 12)
				<td style=" color: #FFFFFF; font-weight: bold; background-color: #143052;"> {{$data['mtxDN']['previousAdSales'][$d]}} </td>
			@else 
				<td style="background-color: #c3d8ef; font-weight: bold;"> {{$data['mtxDN']['previousAdSales'][$d]}} </td>
			@endif			
		@endfor
	</tr>

	<tr>
		<td style="background-color: #143052; color: #FFFFFF; font-weight: bold;"> {{ $data['years'][1] }} SAP </td>
		@for($d=0; $d < sizeof($data['mtxDN']['previousAdSales']); $d++)
			@if($d == 12)
				<td style=" color: #FFFFFF; font-weight: bold; background-color: #143052;"> {{$data['mtxDN']['previousSAP'][$d]}} </td>
			@else
				<td style="background-color: #dce6f1; font-weight: bold;"> {{$data['mtxDN']['previousSAP'][$d]}} </td>
			@endif
			
		@endfor
	</tr>

	<tr>
		<td style="background-color: #143052; color: #FFFFFF; font-weight: bold;"> {{ $data['years'][0] }} Target </td>
		@for($d=0; $d < sizeof($data['mtxDN']['previousAdSales']); $d++)
			@if($d == 12)
				<td style="background-color: #143052; color: #FFFFFF; font-weight: bold;"> {{$data['mtxDN']['currentTarget'][$d]}} </td>
			@else
				<td style="background-color: #c3d8ef; font-weight: bold;">{{$data['mtxDN']['currentTarget'][$d]}} </td>
			@endif
		@endfor
	</tr>

	<tr>
		<td style="background-color: #143052; color: #FFFFFF; font-weight: bold;"> {{ $data['years'][0] }} Corporate </td>
		@for($d=0; $d < sizeof($data['mtxDN']['previousAdSales']); $d++)
			@if($d == 12)
				<td style="color: #FFFFFF; font-weight: bold; background-color: #143052;"> {{$data['mtxDN']['currentCorporate'][$d]}} </td>
			@else
				<td style="background-color: #dce6f1; font-weight: bold;"> {{$data['mtxDN']['currentCorporate'][$d]}} </td>
			@endif
		@endfor
	</tr>

	<tr>
		<td style="background-color: #143052; color: #FFFFFF; font-weight: bold;"> {{ $data['years'][0] }} Ad Sales </td>
		@for($d=0; $d < sizeof($data['mtxDN']['previousAdSales']); $d++)
			@if($d == 12)
				<td style="color: #FFFFFF; font-weight: bold; background-color: #143052;"> {{$data['mtxDN']['currentAdSales'][$d]}} </td>
			@else
				<td style="background-color: #c3d8ef; font-weight: bold;"> {{$data['mtxDN']['currentAdSales'][$d]}} </td>
			@endif
		@endfor
	</tr>

	<tr>
		<td style="background-color: #143052; color: #FFFFFF; font-weight: bold;"> {{ $data['years'][0] }} SAP </td>
		@for($d=0; $d < sizeof($data['mtxDN']['previousAdSales']); $d++)
			@if($d == 12)
				<td style="color: #FFFFFF; font-weight: bold; background-color: #143052;"> {{$data['mtxDN']['currentSAP'][$d]}} </td>
			@else
				<td style="background-color: #dce6f1; font-weight: bold;"> {{$data['mtxDN']['currentSAP'][$d]}} </td>
			@endif
		@endfor
	</tr>

	<tr>
		<td style="background-color: #143052; color: #FFFFFF; font-weight: bold;"> %({{ $data['years'][0] }}F - 2019) </td>
		@for($d=0; $d < sizeof($data['mtxDN']['previousAdSales']); $d++)
			@if($d == 12)
				@if($data['mtxDN']['previousAdSales'][$d] > 0)
					<td style="color: #FFFFFF; font-weight: bold; background-color: #143052; text-align: right;">{{number_format($data['mtxDN']['currentCorporate'][$d]/$data['mtxDN']['previousAdSales'][$d],2)*100}}%</td>
				@else
					<td style=" color: #FFFFFF; font-weight: bold; background-color: #143052; text-align: right;">0.0</td>
				@endif
			@else
				@if($data['mtxDN']['previousAdSales'][$d] > 0)
					<td style=" background-color: #c3d8ef; font-weight: bold; text-align: right;">{{number_format($data['mtxDN']['currentCorporate'][$d]/$data['mtxDN']['previousAdSales'][$d],2)*100}}%</td>
				@else
					<td style="background-color: #c3d8ef; font-weight: bold; text-align: right;">0.0</td>
				@endif
			@endif
		@endfor
	</tr>

	<tr>
		<td style="background-color: #143052; color: #FFFFFF; font-weight: bold;"> %({{ $data['years'][0] }}F - Target) </td>
		@for($d=0; $d < sizeof($data['mtxDN']['previousAdSales']); $d++)
			@if($data['mtxDN']['currentTarget'][$d] == 0)
				@if($d == 12)
					<td style="color: #FFFFFF; font-weight: bold; background-color: #143052; text-align: right;">0%</td>
				@else
					<td style="background-color: #dce6f1; font-weight: bold; text-align: right;">0%</td>
				@endif
			@else
				@if($d == 12)
					@if($data['mtxDN']['previousAdSales'][$d] > 0)
						<td style=" color: #FFFFFF; font-weight: bold; background-color: #143052; text-align: right;">{{number_format($data['mtxDN']['currentCorporate'][$d]/$data['mtxDN']['currentTarget'][$d],2)*100}}%</td>
					@else
						<td style="color: #FFFFFF; font-weight: bold; background-color: #143052; text-align: right;">0.0</td>
					@endif 
				@else
					@if($data['mtxDN']['previousAdSales'][$d] > 0)
						<td style="background-color: #dce6f1; font-weight: bold; text-align: right;">{{number_format($data['mtxDN']['currentCorporate'][$d]/$data['mtxDN']['currentTarget'][$d],2)*100}}%</td>
					@else
						<td style="background-color: #dce6f1; font-weight: bold; text-align: right;">0.0</td>
					@endif
				@endif
			@endif
		@endfor
	</tr>	
</table>
@for ($c=0; $c < sizeof($data['mtx']['previousAdSales']); $c++)
<table>
	<tr>
		<td style="background-color: #0070c0; color: #FFFFFF; font-weight: bold;"> {{ $data['typeSelectS'][$c]['agencyGroup'] }} </td>
    	@for($m=0; $m < sizeof($data['monthView']); $m++)
    		<td style="background-color: #a6a6a6; font-weight: bold; float: right;"> {{ $data['monthView'][$m] }} </td>
    	@endfor
    		<td style="background-color: #0f243e; color: #FFFFFF; font-weight: bold; float: right;"> Total </td>
    		<td style="background-color: #a6a6a6; font-weight: bold; float: right;"> YTD </td>
    	@for($q=0; $q < sizeof($data['quarter']); $q++)
    		<td style="background-color: #a6a6a6; font-weight: bold; float: right;">  {{ $data['quarter'][$q] }} </td>
    	@endfor
	</tr>

	<tr>
		<td style="background-color: #c3d8ef; font-weight: bold;"> {{ $data['years'][1] }} Ad Sales </td>
    	@for($d=0; $d < sizeof($data['mtx']['previousAdSales'][$c]); $d++)
    		@if($d == 12)
				<td style="color: #FFFFFF; font-weight: bold; background-color: #143052;">{{$data['mtx']['previousAdSales'][$c][$d]}} </td>
    		@else
				<td style="background-color: #c3d8ef; font-weight: bold;">{{$data['mtx']['previousAdSales'][$c][$d]}} </td>
    		@endif
    	@endfor
	</tr>

	<tr>
		<td style="background-color: #dce6f1; font-weight: bold;"> {{ $data['years'][1] }} SAP </td>
    	@for($d=0; $d < sizeof($data['mtx']['previousAdSales'][$c]); $d++)
    		@if($d == 12)
    			<td style="color: #FFFFFF; font-weight: bold; background-color: #143052;"> {{$data['mtx']['previousSAP'][$c][$d]}} </td>
    		@else
    			<td style="background-color: #dce6f1; font-weight: bold;"> {{$data['mtx']['previousSAP'][$c][$d]}} </td>
    		@endif
    	@endfor
	</tr>

	<tr>
		<td style="background-color: #c3d8ef; font-weight: bold;"> {{ $data['years'][0] }} Target </td>
    	@for($d=0; $d < sizeof($data['mtx']['previousAdSales'][$c]); $d++)
    		@if($d == 12)
    			<td style=" color: #FFFFFF; font-weight: bold; background-color: #143052;">{{$data['mtx']['currentTarget'][$c][$d]}} </td>
    		@else
    			<td style="background-color: #c3d8ef; font-weight: bold;">{{$data['mtx']['currentTarget'][$c][$d]}} </td>
    		@endif
    	@endfor
	</tr>
	
	<tr>
		<td style="background-color: #dce6f1; font-weight: bold;"> {{ $data['years'][0] }} Corporate </td>
    	@for($d=0; $d < sizeof($data['mtx']['previousAdSales'][$c]); $d++)
    		@if($d == 12)
    			<td style="color: #FFFFFF; font-weight: bold; background-color: #143052;"> {{$data['mtx']['currentCorporate'][$c][$d]}} </td>
    		@else
    			<td style="background-color: #dce6f1; font-weight: bold;"> {{$data['mtx']['currentCorporate'][$c][$d]}} </td>
    		@endif
    	@endfor
	</tr>

	<tr>
		<td style="background-color: #c3d8ef; font-weight: bold;"> {{ $data['years'][0] }} Ad Sales </td>
    	@for($d=0; $d < sizeof($data['mtx']['previousAdSales'][$c]); $d++)
    		@if($d == 12)
    			<td style="color: #FFFFFF; font-weight: bold; background-color: #143052;"> {{$data['mtx']['currentAdSales'][$c][$d]}} </td>
    		@else
    			<td style="background-color: #c3d8ef; font-weight: bold;"> {{$data['mtx']['currentAdSales'][$c][$d]}} </td>
    		@endif
    	@endfor
	</tr>

	<tr>
		<td style="background-color: #dce6f1; font-weight: bold; "> {{ $data['years'][0] }} SAP </td>
    	@for($d=0; $d < sizeof($data['mtx']['previousAdSales'][$c]); $d++)
    		@if($d == 12)
    			<td style="color: #FFFFFF; font-weight: bold; background-color: #143052;"> {{$data['mtx']['currentSAP'][$c][$d]}} </td>
    		@else
    			<td style="background-color: #dce6f1; font-weight: bold;"> {{$data['mtx']['currentSAP'][$c][$d]}} </td>
    		@endif
    	@endfor
	</tr>

	<tr>
		<td style="background-color: #c3d8ef; font-weight: bold;"> %({{ $data['years'][0] }}F - 2019) </td>
    	@for($d=0; $d < sizeof($data['mtx']['previousAdSales'][$c]); $d++)
    		@if($d == 12)
    			@if($data['mtx']['previousAdSales'][$c][$d] > 0)
					<td style="color: #FFFFFF; font-weight: bold; background-color: #143052; text-align: right;">{{number_format($data['mtx']['currentCorporate'][$c][$d]/$data['mtx']['previousAdSales'][$c][$d],2)*100}}%</td> 
				@else
					<td style="color: #FFFFFF; font-weight: bold; background-color: #143052; text-align: right;">0.0</td>
				@endif
    		@else
    			@if($data['mtx']['previousAdSales'][$c][$d] > 0)
					<td style="background-color: #c3d8ef; font-weight: bold; text-align: right;">{{number_format($data['mtx']['currentCorporate'][$c][$d]/$data['mtx']['previousAdSales'][$c][$d],2)*100}}%</td> 
				@else
					<td style="background-color: #c3d8ef; font-weight: bold; text-align: right;">0.0</td>
				@endif
    		@endif				        		 
    	@endfor
	</tr>

	<tr>
		<td style="background-color: #dce6f1; font-weight: bold;"> %({{ $data['years'][0] }}F - Target) </td>
    	@for($d=0; $d < sizeof($data['mtx']['previousAdSales'][$c]); $d++)

    		@if($data['mtxDN']['currentTarget'][$d] == 0)
				@if($d == 12)
					<td style="color: #FFFFFF; font-weight: bold; background-color: #143052; text-align: right;">0%</td>
				@else
					<td style="background-color: #dce6f1; font-weight: bold; text-align: right;">0%</td>
				@endif
			@else
				@if($d == 12)
					@if($data['mtxDN']['previousAdSales'][$d] > 0)
						<td style=" color: #FFFFFF; font-weight: bold; background-color: #143052; text-align: right;">{{number_format($data['mtxDN']['currentCorporate'][$d]/$data['mtxDN']['currentTarget'][$d],2)*100}}%</td>
					@else
						<td style="color: #FFFFFF; font-weight: bold; background-color: #143052; text-align: right;">0.0</td>
					@endif 
				@else
					@if($data['mtxDN']['previousAdSales'][$d] > 0)
						<td style="background-color: #dce6f1; font-weight: bold; text-align: right;">{{number_format($data['mtxDN']['currentCorporate'][$d]/$data['mtxDN']['currentTarget'][$d],2)*100}}%</td>
					@else
						<td style="background-color: #dce6f1; font-weight: bold; text-align: right;">0.0</td>
					@endif
				@endif
			@endif
    	@endfor
	</tr>	
</table>	
@endfor

@endif