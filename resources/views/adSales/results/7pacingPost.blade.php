@extends('layouts.mirror')
@section('title', 'Monthly Results')
@section('head')	
	<script src="/js/results.js"></script>
	<?php include(resource_path('views/auth.php')); ?>
@endsection
@section('content')
<div class="container-fluid">
	<div class="row">
		<div class="col">
			<form method="POST" action="{{ route('resultsPacingPost') }}" runat="server"  onsubmit="ShowLoading()">
				@csrf
				<div class="row">
					<div class="col-sm">
						<label class="labelLeft"><span class="bold"> Region: </span></label>
						@if($errors->has('region'))
							<label style="color: red;">* Required</label>
						@endif
						{{$render->newRegionFiltered($regionName,$regionID)}}
						{{-- @if($userLevel == 'L0' || $userLevel == 'SU') 
							{{$render->region($region)}}						
							
						@else
							{{$render->regionFiltered($region, $regionID, $special)}}
						@endif
						--}}
					</div>

					<div class="col-sm">
						<label class="labelLeft"><span class="bold"> Brand: </span></label>
						@if($errors->has('brand'))
							<label style="color: red;">* Required</label>
						@endif
						{{$render->brand($brand)}}
					</div>	

					<div class="col-sm">
						<label class="labelLeft"><span class="bold"> Currency: </span></label>
					 	@if($errors->has('currency'))
							<label style="color: red;">* Required</label>
						@endif
						{{$render->newCurrency($regionName,$regionCurrencies)}}
					</div>

					<div class="col-sm">
						<label class="labelLeft"><span class="bold"> Value: </span></label>
						@if($errors->has('value'))
							<label style="color: red;">* Required</label>
						@endif
						{{$render->value2()}}
					</div>

					<div class="col-sm">
						<label class="labelLeft"><span class="bold"> &nbsp; </span> </label>
						<input type="submit" value="Generate" class="btn btn-primary" style="width: 100%;">
					</div>
				</div>
			</form>
		</div>
	</div>

	<div class="row justify-content-end mt-2">
		<div class="col-sm" style="color: #0070c0;font-size: 22px;">
			<span style="float: right;"> Pacing ({{$currencyS}}/{{strtoupper($value)}}) </span>
		</div>
	</div>	


	
</div>
	
	<?php

		$month = array("January","February","March","April","May","June","July","August","September","October","November","December");

		$quarter = array("Q1","Q2","Q3","Q4");

	?>

	<div class="container-fluid">
		<div class="row">
			<div class="col"> 
				<table style='width: 100%; zoom: 85%;font-size: 16px;'>
					<tr>
						<th colspan='19' class='lightBlue'>
							<center>
								<span style='font-size:24px;'> 
									{{ $salesRegion }} - Pacing : ({{$currencyS}}/{{strtoupper($value)}})
								</span>
							</center>
						</th>
					</tr>

					<tr><td> &nbsp; </td></tr>

					<!--
							
							START OF DN 
							
					-->

					<tr>
		        		<td class='darkBlue center'> DN </td>
			        	@for($m=0; $m < sizeof($month); $m++)
			        		<td class='lightGrey center'> {{ $month[$m] }} </td>
			        	@endfor
			        		<td class='darkBlue center'> Total </td>
			        		<td class='lightGrey center'> YTD </td>
			        	@for($q=0; $q < sizeof($quarter); $q++)
			        		<td class='lightGrey center'> {{ $quarter[$q] }} </td>
			        	@endfor
		        	</tr>
					
					<tr class="center">
		        		<td class="smBlue"> {{ $years[1] }} Ad Sales </td>
			        	@for($d=0; $d < sizeof($mtxDN['previousAdSales']); $d++)
			        		@if($d == 12)
			        			<?php $clr = 'smBlue'; ?>
			        		@else
			        			<?php $clr = 'medBlue'; ?>
			        		@endif
			        		<td class="{{$clr}}"> {{ number_format($mtxDN['previousAdSales'][$d]) }} </td>
			        	@endfor
		        	</tr>

		        	<tr class="center">
		        		<td class="smBlue"> {{ $years[1] }} SAP </td>
			        	@for($d=0; $d < sizeof($mtxDN['previousAdSales']); $d++)
			        		@if($d == 12)
			        			<?php $clr = 'smBlue'; ?>
			        		@else
			        			<?php $clr = 'rcBlue'; ?>
			        		@endif
			        		<td class="{{$clr}}"> {{ number_format($mtxDN['previousSAP'][$d]) }} </td>
			        	@endfor
		        	</tr>

		        	<tr class="center">
		        		<td class="smBlue"> {{ $years[0] }} Target </td>
			        	@for($d=0; $d < sizeof($mtxDN['previousAdSales']); $d++)
			        		@if($d == 12)
			        			<?php $clr = 'smBlue'; ?>
			        		@else
			        			<?php $clr = 'medBlue'; ?>
			        		@endif
			        		<td class="{{$clr}}"> {{ number_format($mtxDN['currentTarget'][$d]) }} </td>
			        	@endfor
		        	</tr>
		        	
		        	<tr class="center">
		        		<td class="smBlue"> {{ $years[0] }} Corporate </td>
			        	@for($d=0; $d < sizeof($mtxDN['previousAdSales']); $d++)
			        		@if($d == 12)
			        			<?php $clr = 'smBlue'; ?>
			        		@else
			        			<?php $clr = 'rcBlue'; ?>
			        		@endif
			        		<td class="{{$clr}}"> {{ number_format($mtxDN['currentCorporate'][$d]) }} </td>
			        	@endfor
		        	</tr>

		        	<tr class="center">
		        		<td class="smBlue"> {{ $years[0] }} Ad Sales </td>
			        	@for($d=0; $d < sizeof($mtxDN['previousAdSales']); $d++)
			        		@if($d == 12)
			        			<?php $clr = 'smBlue'; ?>
			        		@else
			        			<?php $clr = 'medBlue'; ?>
			        		@endif
			        		<td class="{{$clr}}"> {{ number_format($mtxDN['currentAdSales'][$d]) }} </td>
			        	@endfor
		        	</tr>

		        	<tr class="center">
		        		<td class="smBlue"> {{ $years[0] }} SAP </td>
			        	@for($d=0; $d < sizeof($mtxDN['previousAdSales']); $d++)
			        		@if($d == 12)
			        			<?php $clr = 'smBlue'; ?>
			        		@else
			        			<?php $clr = 'rcBlue'; ?>
			        		@endif
			        		<td class="{{$clr}}"> {{ number_format($mtxDN['currentSAP'][$d]) }} </td>
			        	@endfor
		        	</tr>

		        	<tr class="center">
		        		<td class="smBlue"> %({{ $years[0] }}F - 2019) </td>
			        	@for($d=0; $d < sizeof($mtxDN['previousAdSales']); $d++)
			        		@if($d == 12)
			        			<?php $clr = 'smBlue'; ?>
			        		@else
			        			<?php $clr = 'medBlue'; ?>
			        		@endif
			        		<?php
			        			if($mtxDN['previousAdSales'][$d] > 0){
			        				$temp = ($mtxDN['currentCorporate'][$d]/$mtxDN['previousAdSales'][$d])*100;
			        			}else{
			        				$temp = 0.0;
			        			}
			        		?>
			        		<td class="{{$clr}}"> {{ number_format($temp) }}% </td>
			        	@endfor
		        	</tr>

		        	<tr class="center">
		        		<td class="smBlue"> %({{ $years[0] }}F - Target) </td>
			        	@for($d=0; $d < sizeof($mtxDN['previousAdSales']); $d++)
			        		@if($d == 12)
			        			<?php $clr = 'smBlue'; ?>
			        		@else
			        			<?php $clr = 'rcBlue'; ?>
			        		@endif
			        		<?php
			        			if($mtxDN['previousAdSales'][$d] > 0){
			        				$temp = ($mtxDN['currentCorporate'][$d]/$mtxDN['currentTarget'][$d])*100;
			        			}else{
			        				$temp = 0.0;
			        			}
			        		?>
			        		<td class="{{$clr}}"> {{ number_format($temp) }}% </td>
			        	@endfor
		        	</tr>

					<tr><td> &nbsp; </td></tr>

					<!--
							
							END OF DN 
							
					-->


					<!--
							
							START OF THE OTHER BRANDS 
							
					-->
			        @for ($c=0; $c < sizeof($mtx['previousAdSales']); $c++)
				        
			        	<tr>
			        		<td class='lightBlue center'> {{ $brandID[$c][1] }} </td>
				        	@for($m=0; $m < sizeof($month); $m++)
				        		<td class='lightGrey center'> {{ $month[$m] }} </td>
				        	@endfor
				        		<td class='darkBlue center'> Total </td>
				        		<td class='lightGrey center'> YTD </td>
				        	@for($q=0; $q < sizeof($quarter); $q++)
				        		<td class='lightGrey center'> {{ $quarter[$q] }} </td>
				        	@endfor
			        	</tr>

			        	<tr class="center">
			        		<td class="medBlue"> {{ $years[1] }} Ad Sales </td>
				        	@for($d=0; $d < sizeof($mtx['previousAdSales'][$c]); $d++)
				        		@if($d == 12)
				        			<?php $clr = 'smBlue'; ?>
				        		@else
				        			<?php $clr = 'medBlue'; ?>
				        		@endif
				        		<td class="{{$clr}}"> {{ number_format($mtx['previousAdSales'][$c][$d]) }} </td>
				        	@endfor
			        	</tr>

			        	<tr class="center">
			        		<td class="rcBlue"> {{ $years[1] }} SAP </td>
				        	@for($d=0; $d < sizeof($mtx['previousAdSales'][$c]); $d++)
				        		@if($d == 12)
				        			<?php $clr = 'smBlue'; ?>
				        		@else
				        			<?php $clr = 'rcBlue'; ?>
				        		@endif
				        		<td class="{{$clr}}"> {{ number_format($mtx['previousSAP'][$c][$d]) }} </td>
				        	@endfor
			        	</tr>

			        	<tr class="center">
			        		<td class="medBlue"> {{ $years[0] }} Target </td>
				        	@for($d=0; $d < sizeof($mtx['previousAdSales'][$c]); $d++)
				        		@if($d == 12)
				        			<?php $clr = 'smBlue'; ?>
				        		@else
				        			<?php $clr = 'medBlue'; ?>
				        		@endif
				        		<td class="{{$clr}}"> {{ number_format($mtx['currentTarget'][$c][$d]) }} </td>
				        	@endfor
			        	</tr>
			        	
			        	<tr class="center">
			        		<td class="rcBlue"> {{ $years[0] }} Corporate </td>
				        	@for($d=0; $d < sizeof($mtx['previousAdSales'][$c]); $d++)
				        		@if($d == 12)
				        			<?php $clr = 'smBlue'; ?>
				        		@else
				        			<?php $clr = 'rcBlue'; ?>
				        		@endif
				        		<td class="{{$clr}}"> {{ number_format($mtx['currentCorporate'][$c][$d]) }} </td>
				        	@endfor
			        	</tr>

			        	<tr class="center">
			        		<td class="medBlue"> {{ $years[0] }} Ad Sales </td>
				        	@for($d=0; $d < sizeof($mtx['previousAdSales'][$c]); $d++)
				        		@if($d == 12)
				        			<?php $clr = 'smBlue'; ?>
				        		@else
				        			<?php $clr = 'medBlue'; ?>
				        		@endif
				        		<td class="{{$clr}}"> {{ number_format($mtx['currentAdSales'][$c][$d]) }} </td>
				        	@endfor
			        	</tr>

			        	<tr class="center">
			        		<td class="rcBlue"> {{ $years[0] }} SAP </td>
				        	@for($d=0; $d < sizeof($mtx['previousAdSales'][$c]); $d++)
				        		@if($d == 12)
				        			<?php $clr = 'smBlue'; ?>
				        		@else
				        			<?php $clr = 'rcBlue'; ?>
				        		@endif
				        		<td class="{{$clr}}"> {{ number_format($mtx['currentSAP'][$c][$d]) }} </td>
				        	@endfor
			        	</tr>

			        	<tr class="center">
			        		<td class="medBlue"> %({{ $years[0] }}F - 2019) </td>
				        	@for($d=0; $d < sizeof($mtx['previousAdSales'][$c]); $d++)
				        		@if($d == 12)
				        			<?php $clr = 'smBlue'; ?>
				        		@else
				        			<?php $clr = 'medBlue'; ?>
				        		@endif				        		 
			        			<?php
			        				if($mtx['previousAdSales'][$c][$d] > 0){
			        					$temp = ($mtx['currentCorporate'][$c][$d]/$mtx['previousAdSales'][$c][$d])*100; 
			        				}else{
			        					$temp = 0.0;
			        				}
			        			?>
				        		<td class="{{$clr}}"> {{ number_format($temp) }} </td>
				        	@endfor
			        	</tr>

			        	<tr class="center">
			        		<td class="rcBlue"> %({{ $years[0] }}F - Target) </td>
				        	@for($d=0; $d < sizeof($mtx['previousAdSales'][$c]); $d++)
				        		@if($d == 12)
				        			<?php $clr = 'smBlue'; ?>
				        		@else
				        			<?php $clr = 'rcBlue'; ?>
				        		@endif
				        		<?php
			        				if($mtx['currentTarget'][$c][$d]){
			        					$temp = ($mtx['currentCorporate'][$c][$d]/$mtx['currentTarget'][$c][$d])*100;
			        				}else{
			        					$temp = 0.0;
			        				}
			        			?>
				        		<td class="{{$clr}}"> {{ number_format($temp) }} </td>
				        	@endfor
			        	</tr>

			        	<tr><td> &nbsp; </td></tr>
		        	@endfor

		        	<!--
							
							END OF THE OTHER BRANDS 
							
					-->
		        </table>
        	</div>
        </div>			
	</div>

<div id="vlau"></div>
	
@endsection