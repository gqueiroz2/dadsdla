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
		<td style="background-color: #0f243e; color: #FFFFFF; font-weight: bold;"> WBD </td>
		@for($m=0; $m < sizeof($data['monthView']); $m++)
			<td style="background-color: #a6a6a6; font-weight: bold; text-align: right;"> {{ $data['monthView'][$m] }} </td>
		@endfor
			<td style="background-color: #0f243e; color: #FFFFFF; font-weight: bold; text-align: right;"> Total </td>
	</tr>

	<tr>
		<td style="background-color: #143052; color: #FFFFFF; font-weight: bold;"> {{ $data['years'][1] }} Ad Sales </td>
		@for($d=0; $d < sizeof($data['mtxDN']['previousAdSales']); $d++)
			@if($d == 12)
				<td style=" color: #FFFFFF; font-weight: bold; background-color: #143052;"> {{$data['mtxDN']['previousAdSales'][$d] }} </td>
			@else 
				<td style="background-color: #c3d8ef; font-weight: bold;"> {{$data['mtxDN']['previousAdSales'][$d] }} </td>
			@endif			
		@endfor
	</tr>

	<tr>
		<td style="background-color: #143052; color: #FFFFFF; font-weight: bold;"> {{ $data['years'][0] }} Target </td>
		@for($d=0; $d < sizeof($data['mtxDN']['previousAdSales']); $d++)
			@if($d == 12)
				<td style="background-color: #143052; color: #FFFFFF; font-weight: bold;"> {{$data['mtxDN']['currentTarget'][$d] }} </td>
			@else
				<td style="background-color: #c3d8ef; font-weight: bold;">{{$data['mtxDN']['currentTarget'][$d] }} </td>
			@endif
		@endfor
	</tr>

	<tr>
		<td style="background-color: #143052; color: #FFFFFF; font-weight: bold;"> {{ $data['years'][0] }} Corporate </td>
		@for($d=0; $d < sizeof($data['mtxDN']['previousAdSales']); $d++)
			@if($d == 12)
				<td style="color: #FFFFFF; font-weight: bold; background-color: #143052;"> {{$data['mtxDN']['currentCorporate'][$d] }} </td>
			@else
				<td style="background-color: #dce6f1; font-weight: bold;"> {{$data['mtxDN']['currentCorporate'][$d] }} </td>
			@endif
		@endfor
	</tr>

	<tr>
		<td style="background-color: #143052; color: #FFFFFF; font-weight: bold;"> {{ $data['years'][0] }} Ad Sales </td>
		@for($d=0; $d < sizeof($data['mtxDN']['previousAdSales']); $d++)
			@if($d == 12)
				<td style="color: #FFFFFF; font-weight: bold; background-color: #143052;"> {{$data['mtxDN']['currentAdSales'][$d] }} </td>
			@else
				<td style="background-color: #c3d8ef; font-weight: bold;"> {{$data['mtxDN']['currentAdSales'][$d] }} </td>
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

@for ($c=0; $c < sizeof($data['company']); $c++)
<table>
	<tr>
		<td style="background-color: #0070c0; color: #FFFFFF; font-weight: bold;"> {{ strtoupper($data['company'][$c]) }} </td>
    	@for($m=0; $m < sizeof($data['monthView']); $m++)
    		<td style="background-color: #a6a6a6; font-weight: bold; text-align: right;"> {{ $data['monthView'][$m] }} </td>
    	@endfor
    		<td style="background-color: #0f243e; color: #FFFFFF; font-weight: bold; text-align: right;"> Total </td>
	</tr>

	<tr>
		<td style="background-color: #c3d8ef; font-weight: bold;"> {{ $data['years'][1] }} Ad Sales </td>
    	@for($d=0; $d < sizeof($data['mtx']['previousCompany'][0]); $d++)
    		@if($d == 12)
				<td style="color: #FFFFFF; font-weight: bold; background-color: #143052;">{{$data['totalsCompany']['previousCompany'][$c] }} </td>
    		@else
				<td style="background-color: #c3d8ef; font-weight: bold;">{{$data['mtx']['previousCompany'][0][$d][$c] }} </td>
    		@endif
    	@endfor
    	<td style="color: #FFFFFF; font-weight: bold; background-color: #143052;">{{$data['totalsCompany']['previousCompany'][$c] }} </td>
	</tr>

	<tr>
		<td style="background-color: #c3d8ef; font-weight: bold;"> {{ $data['years'][0] }} Target </td>
    	@for($d=0; $d < sizeof($data['mtx']['previousCompany'][0]); $d++)
    		@if($d == 12)
    			<td style=" color: #FFFFFF; font-weight: bold; background-color: #143052;">{{$data['totalsCompany']['currentTargetCompany'][$c] }} </td>
    		@else
    			<td style="background-color: #c3d8ef; font-weight: bold;">{{$data['mtx']['currentTargetCompany'][0][$d][$c] }} </td>
    		@endif
    	@endfor
    	<td style=" color: #FFFFFF; font-weight: bold; background-color: #143052;">{{$data['totalsCompany']['currentTargetCompany'][$c] }} </td>
	</tr>
	
	<tr>
		<td style="background-color: #dce6f1; font-weight: bold;"> {{ $data['years'][0] }} Corporate </td>
    	@for($d=0; $d < sizeof($data['mtx']['previousCompany'][0]); $d++)
    		@if($d == 12)
    			<td style=" color: #FFFFFF; font-weight: bold; background-color: #143052;">{{$data['totalsCompany']['currentCorporateCompany'][$c] }} </td>
    		@else
    			<td style="background-color: #dce6f1; font-weight: bold;"> {{$data['mtx']['currentCorporateCompany'][0][$d][$c] }} </td>
    		@endif
    	@endfor
    	<td style=" color: #FFFFFF; font-weight: bold; background-color: #143052;">{{$data['totalsCompany']['currentCorporateCompany'][$c] }} </td>
	</tr>

	<tr>
		<td style="background-color: #c3d8ef; font-weight: bold;"> {{ $data['years'][0] }} Ad Sales </td>
    	@for($d=0; $d < sizeof($data['mtx']['previousCompany'][0]); $d++)
    		@if($d == 12)
    			<td style=" color: #FFFFFF; font-weight: bold; background-color: #143052;">{{$data['totalsCompany']['currentCompany'][$c] }} </td>
    		@else
    			<td style="background-color: #c3d8ef; font-weight: bold;"> {{$data['mtx']['currentCompany'][0][$d][$c] }} </td>
    		@endif
    	@endfor
    	<td style=" color: #FFFFFF; font-weight: bold; background-color: #143052;">{{$data['totalsCompany']['currentCompany'][$c] }} </td>
	</tr>

	<tr>
		<td style="background-color: #c3d8ef; font-weight: bold;"> %({{ $data['years'][0] }}F - 2019) </td>
    	@for($d=0; $d < sizeof($data['mtx']['previousCompany'][0]); $d++)
    		@if($d == 12)
    			@if($data['totalsCompany']['previousCompany'][$c] > 0)
					<td style="color: #FFFFFF; font-weight: bold; background-color: #143052; text-align: right;">{{number_format($data['totalsCompany']['currentCorporateCompany'][$c]/$data['totalsCompany']['previousCompany'][$c],2)*100}}%</td> 
				@else
					<td style="color: #FFFFFF; font-weight: bold; background-color: #143052; text-align: right;">0.0</td>
				@endif
    		@else
    			@if($data['mtx']['previousCompany'][0][$d][$c] > 0)
					<td style="background-color: #c3d8ef; font-weight: bold; text-align: right;">{{number_format($data['mtx']['currentCorporateCompany'][0][$d][$c]/$data['mtx']['previousCompany'][0][$d][$c],2)*100}}%</td> 
				@else
					<td style="background-color: #c3d8ef; font-weight: bold; text-align: right;">0.0</td>
				@endif
    		@endif				        		 
    	@endfor
    	@if($data['totalsCompany']['previousCompany'][$c] > 0)
			<td style="color: #FFFFFF; font-weight: bold; background-color: #143052; text-align: right;">{{number_format($data['totalsCompany']['currentCorporateCompany'][$c]/$data['totalsCompany']['previousCompany'][$c],2)*100}}%</td> 
		@else
			<td style="color: #FFFFFF; font-weight: bold; background-color: #143052; text-align: right;">0.0</td>
		@endif

	</tr>

	<tr>
		<td style="background-color: #dce6f1; font-weight: bold;"> %({{ $data['years'][0] }}F - Target) </td>
    	@for($d=0; $d < sizeof($data['mtx']['previousCompany'][0]); $d++)

    		@if($data['mtx']['previousCompany'][0][$d][$c])
				@if($d == 12)
					<td style="color: #FFFFFF; font-weight: bold; background-color: #143052; text-align: right;">0%</td>
				@else
					<td style="background-color: #dce6f1; font-weight: bold; text-align: right;">0%</td>
				@endif
			@else
				@if($d == 12)
					@if($data['totalsCompany']['currentTargetCompany'][$c] > 0)
						<td style="color: #FFFFFF; font-weight: bold; background-color: #143052; text-align: right;">{{number_format($data['totalsCompany']['currentCorporateCompany'][$c]/$data['totalsCompany']['currentTargetCompany'][$c],2)*100}}%</td> 
					@else
						<td style="color: #FFFFFF; font-weight: bold; background-color: #143052; text-align: right;">0.0</td>
					@endif 
				@else
					@if($data['mtxDN']['previousAdSales'][$d] > 0)
						<td style="background-color: #dce6f1; font-weight: bold; text-align: right;">{{number_format($data['mtx']['currentCorporateCompany'][0][$d][$c]/$data['mtx']['currentTargetCompany'][0][$d][$c],2)*100}}%</td>
					@else
						<td style="background-color: #dce6f1; font-weight: bold; text-align: right;">0.0</td>
					@endif
				@endif
			@endif
    	@endfor
    	@if($data['totalsCompany']['currentTargetCompany'][$c] > 0)
			<td style="color: #FFFFFF; font-weight: bold; background-color: #143052; text-align: right;">{{number_format($data['totalsCompany']['currentCorporateCompany'][$c]/$data['totalsCompany']['currentTargetCompany'][$c],2)*100}}%</td> 
		@else
			<td style="color: #FFFFFF; font-weight: bold; background-color: #143052; text-align: right;">0.0</td>
		@endif 
</tr>	
</table>	
@endfor