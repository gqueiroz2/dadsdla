@extends('layouts.mirror')
@section('title', 'Consolidate Results')
@section('head')	
	<script src="/js/consolidateResults.js"></script>
	<?php include(resource_path('views/auth.php')); ?>
@endsection
@section('content')
<div class="container-fluid">
	<div class="row">
		<div class="col">
			<form method="POST" action="{{ route('consolidateResultsPost') }}" runat="server"  onsubmit="ShowLoading()">
				@csrf
				<div class="row">

					<div class="col-sm">
						<label class="labelLeft"><span class="bold"> Region: </span></label>
						@if($errors->has('region'))
							<label style="color: red;">* Required</label>
						@endif
						@if($userLevel == 'L0' || $userLevel == 'SU')
							{{$render->region($region)}}							
						@else
							{{$render->regionFiltered($region, $regionID, $special)}}
						@endif
					</div>

					<div class="col-sm">
						<label class="labelLeft"><span class="bold"> Type: </span></label>
						@if($errors->has('type'))
							<label style="color: red;">* Required</label>
						@endif
						
						{{$render->type()}}							
						
					</div>

					<div class="col-sm">
						<label class="labelLeft"><span class="bold" style="color: red;" id="typeSelectLabel"> Select Region </span></label>
						@if($errors->has('typeSelect'))
							<label style="color: red;">* Required</label>
						@endif
						{{$render->typeSelect()}}
					</div>						

					<div class="col-sm">
						<label class="labelLeft"><span class="bold"> Currency: </span></label>
						@if($errors->has('currency'))
							<label style="color: red;">* Required</label>
						@endif
						{{$render->currency()}}
					</div>

					<div class="col-sm">
						<label class="labelLeft"><span class="bold"> Value: </span></label>
						@if($errors->has('value'))
							<label style="color: red;">* Required</label>
						@endif
						{{$render->value()}}
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
			<span style="float: right;"> Pacing </span>
		</div>
		<div class="col-2">
            <button type="button" id="excel" class="btn btn-primary" style="width: 100%">
                Generate Excel
            </button>               
        </div>           
	</div>	

	<div class="row justify-content-end mt-2"></div>
	</div>
	<?php
		$month = array("January","February","March","April","May","June","July","August","September","October","November","December");
		$quarter = array("Q1","Q2","Q3","Q4");
	?>
	@if($type == 'brand')

		<?php $brandID = $typeSelect; ?>

		<div class="container-fluid">
			<div class="row">
				<div class="col"> 
					<div class="container-fluid" style='width: 100%; zoom: 85%;font-size: 16px;'>
						<div class="row">
							<div class="col lightBlue">
								<center>
									<span style='font-size:24px; z-index: 1 !important;'>
										{{ $salesRegion }} - Consolidate - Brand : ({{$currencyName}}/{{strtoupper($value)}})
									</span>
								</center>
							</div>
						</div>

						<div class="row sticky-top" style= "z-index: 1 !important;">
							<table style='width: 100%; zoom: 85%;font-size: 16px;'>
								<tr class="center">
					        		<td style="width: 7% !important; background-color: white;"> &nbsp; </td>
					        	</tr>
					        </table>

							<table style='width: 100%; zoom: 85%;font-size: 16px;'>
								<tr class="center">
					        		<td class='darkBlue center' style="width: 7% !important;"> WBD </td>
						        	@for($m=0; $m < sizeof($month); $m++)
						        		<td class='lightGrey center' style="width: 4%;"> {{ $month[$m] }} </td>
						        	@endfor
						        		<td class='darkBlue center' style="width: 4%;"> Total </td>				        		
				        		</tr>
							
								<tr class="center">
					        		<td class="smBlue" style="width: 7% !important;"> {{ $years[1] }} Ad Sales </td>
						        	@for($d=0; $d < sizeof($mtxDN['previousAdSales']); $d++)
						        		@if($d == 12)
						        			<?php $clr = 'smBlue'; ?>
						        		@else
						        			<?php $clr = 'medBlue'; ?>
						        		@endif
						        		<td class="{{$clr}}" style="width: 4%;"> {{ number_format($mtxDN['previousAdSales'][$d]) }} </td>
						        	@endfor
					        	</tr>

					        	<tr class="center">
					        		<td class="smBlue" style="width: 7% !important;"> {{ $years[0] }} Target </td>
						        	@for($d=0; $d < sizeof($mtxDN['previousAdSales']); $d++)
						        		@if($d == 12)
						        			<?php $clr = 'smBlue'; ?>
						        		@else
						        			<?php $clr = 'medBlue'; ?>
						        		@endif
						        		<td class="{{$clr}}" style="width: 4%;"> {{ number_format($mtxDN['currentTarget'][$d]) }} </td>
						        	@endfor
					        	</tr>
					        	
					        	<tr class="center">
					        		<td class="smBlue" style="width: 7% !important;"> {{ $years[0] }} Corporate </td>
						        	@for($d=0; $d < sizeof($mtxDN['previousAdSales']); $d++)
						        		@if($d == 12)
						        			<?php $clr = 'smBlue'; ?>
						        		@else
						        			<?php $clr = 'rcBlue'; ?>
						        		@endif
						        		<td class="{{$clr}}" style="width: 4%;"> {{ number_format($mtxDN['currentCorporate'][$d]) }} </td>
						        	@endfor
					        	</tr>

					        	<tr class="center">
					        		<td class="smBlue" style="width: 7% !important;"> {{ $years[0] }} Ad Sales </td>
						        	@for($d=0; $d < sizeof($mtxDN['previousAdSales']); $d++)
						        		@if($d == 12)
						        			<?php $clr = 'smBlue'; ?>
						        		@else
						        			<?php $clr = 'medBlue'; ?>
						        		@endif
						        		<td class="{{$clr}}" style="width: 4%;"> {{ number_format($mtxDN['currentAdSales'][$d]) }} </td>
						        	@endfor
					        	</tr>

					        	<tr class="center">
					        		<td class="smBlue" style="width: 7% !important;"> %({{ $years[0] }}F - {{ $years[1] }}) </td>
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
						        		<td class="{{$clr}}" style="width: 4%;"> {{ number_format($temp) }}% </td>
						        	@endfor
					        	</tr>

					        	<tr class="center">
					        		<td class="smBlue" style="width: 7% !important;"> %({{ $years[0] }}F - Target) </td>
						        	@for($d=0; $d < sizeof($mtxDN['previousAdSales']); $d++)
						        		@if($d == 12)
						        			<?php $clr = 'smBlue'; ?>
						        		@else
						        			<?php $clr = 'rcBlue'; ?>
						        		@endif
						        		<?php
						        			if($mtxDN['currentTarget'][$d] > 0){
						        				$temp = ($mtxDN['currentCorporate'][$d]/$mtxDN['currentTarget'][$d])*100;
						        			}else{
						        				$temp = 0.0;
						        			}
						        		?>
						        		<td class="{{$clr}}" style="width: 4%;"> {{ number_format($temp) }}% </td>
						        	@endfor
					        	</tr>
					        </table>

					        <table style='width: 100%; zoom: 85%;font-size: 16px;'>
								<tr class="center">
					        		<td style="width: 7% !important; background-color: white;"> &nbsp; </td>
					        	</tr>
					        </table>
				        </div>

				        @for ($c=0; $c < sizeof($mtx['previousAdSales']); $c++)
						    <div class="row mt-2">
						    	<table style='width: 100%; zoom: 85%;font-size: 16px;'>
						        	<tr class="center">
						        		<td class='lightBlue center' style="width: 7% !important;"> {{ $brandID[$c][1] }} </td>
							        	@for($m=0; $m < sizeof($month); $m++)
							        		<td class='lightGrey center' style="width: 4%;"> {{ $month[$m] }} </td>
							        	@endfor
							        		<td class='darkBlue center' style="width: 4%;"> Total </td>
						        	</tr>

						        	<tr class="center">
						        		<td class="medBlue" style="width: 7% !important;"> {{ $years[1] }} Ad Sales </td>
							        	@for($d=0; $d < sizeof($mtx['previousAdSales'][$c]); $d++)
							        		@if($d == 12)
							        			<?php $clr = 'smBlue'; ?>
							        		@else
							        			<?php $clr = 'medBlue'; ?>
							        		@endif
							        		<td class="{{$clr}}" style="width: 4%;"> {{ number_format($mtx['previousAdSales'][$c][$d]) }} </td>
							        	@endfor
						        	</tr>

						        	<tr class="center">
						        		<td class="medBlue" style="width: 7% !important;"> {{ $years[0] }} Target </td>
							        	@for($d=0; $d < sizeof($mtx['previousAdSales'][$c]); $d++)
							        		@if($d == 12)
							        			<?php $clr = 'smBlue'; ?>
							        		@else
							        			<?php $clr = 'medBlue'; ?>
							        		@endif
							        		<td class="{{$clr}}" style="width: 4%;"> {{ number_format($mtx['currentTarget'][$c][$d]) }} </td>
							        	@endfor
						        	</tr>
						        	
						        	<tr class="center">
						        		<td class="rcBlue" style="width: 7% !important;"> {{ $years[0] }} Corporate </td>
							        	@for($d=0; $d < sizeof($mtx['previousAdSales'][$c]); $d++)
							        		@if($d == 12)
							        			<?php $clr = 'smBlue'; ?>
							        		@else
							        			<?php $clr = 'rcBlue'; ?>
							        		@endif
							        		<td class="{{$clr}}" style="width: 4%;"> {{ number_format($mtx['currentCorporate'][$c][$d]) }} </td>
							        	@endfor
						        	</tr>

						        	<tr class="center">
						        		<td class="medBlue" style="width: 7% !important;"> {{ $years[0] }} Ad Sales </td>
							        	@for($d=0; $d < sizeof($mtx['previousAdSales'][$c]); $d++)
							        		@if($d == 12)
							        			<?php $clr = 'smBlue'; ?>
							        		@else
							        			<?php $clr = 'medBlue'; ?>
							        		@endif
							        		<td class="{{$clr}}" style="width: 4%;"> {{ number_format($mtx['currentAdSales'][$c][$d]) }} </td>
							        	@endfor
						        	</tr>

						        	<tr class="center">
						        		<td class="medBlue" style="width: 7% !important;"> %({{ $years[0] }}F - {{ $years[1] }}) </td>
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
							        		<td class="{{$clr}}" style="width: 4%;"> {{ number_format($temp) }}% </td>
							        	@endfor
						        	</tr>

						        	<tr class="center">
						        		<td class="rcBlue" style="width: 7% !important;"> %({{ $years[0] }}F - Target) </td>
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
							        		<td class="{{$clr}}" style="width: 4%;"> {{ number_format($temp) }}% </td>
							        	@endfor
						        	</tr>	
						        </table>	
						    </div>	        	
			        	@endfor		        	
			        </div>

						
	        	</div>
	        </div>			
		</div>

	@elseif($type == "ae")	

		<div class="container-fluid">
			<div class="row">
				<div class="col"> 
					<div class="container-fluid" style='width: 100%; zoom: 85%;font-size: 16px;'>
						<div class="row">
							
							<div class="col lightBlue">
								<center>
									<span style='font-size:24px;'> 
										{{ $salesRegion }} - Consolidate - AE: ({{$currencyName }}/{{strtoupper($value)}})
									</span>
								</center>
							</div>
						</div>

						<div class="row sticky-top">
							<table style='width: 100%; zoom: 85%;font-size: 16px;'>
								<tr class="center">
					        		<td style="width: 7% !important; background-color: white;"> &nbsp; </td>
					        	</tr>
					        </table>

							<table style='width: 100%; zoom: 85%;font-size: 16px;'>
								<tr class="center">
					        		<td class='darkBlue center' style="width: 7% !important;"> WBD </td>
						        	@for($m=0; $m < sizeof($month); $m++)
						        		<td class='lightGrey center' style="width: 4%;"> {{ $month[$m] }} </td>
						        	@endfor
						        		<td class='darkBlue center' style="width: 4%;"> Total </td>
						 
				        		</tr>
							
								<tr class="center">
					        		<td class="smBlue" style="width: 7% !important;"> {{ $years[1] }} Ad Sales </td>
						        	@for($d=0; $d < sizeof($mtxDN['previousAdSales']); $d++)
						        		@if($d == 12)
						        			<?php $clr = 'smBlue'; ?>
						        		@else
						        			<?php $clr = 'medBlue'; ?>
						        		@endif
						        		<td class="{{$clr}}" style="width: 4%;"> {{ number_format($mtxDN['previousAdSales'][$d]) }} </td>
						        	@endfor
					        	</tr>

					        	
					        	<tr class="center">
					        		<td class="smBlue" style="width: 7% !important;"> {{ $years[0] }} Target </td>
						        	@for($d=0; $d < sizeof($mtxDN['previousAdSales']); $d++)
						        		@if($d == 12)
						        			<?php $clr = 'smBlue'; ?>
						        		@else
						        			<?php $clr = 'medBlue'; ?>
						        		@endif
						        		<td class="{{$clr}}" style="width: 4%;"> {{ number_format($mtxDN['currentTarget'][$d]) }} </td>
						        	@endfor
					        	</tr>
					        	
					        	<tr class="center">
					        		<td class="smBlue" style="width: 7% !important;"> {{ $years[0] }} Corporate </td>
						        	@for($d=0; $d < sizeof($mtxDN['previousAdSales']); $d++)
						        		@if($d == 12)
						        			<?php $clr = 'smBlue'; ?>
						        		@else
						        			<?php $clr = 'rcBlue'; ?>
						        		@endif
						        		<td class="{{$clr}}" style="width: 4%;"> {{ number_format($mtxDN['currentCorporate'][$d]) }} </td>
						        	@endfor
					        	</tr>

					        	<tr class="center">
					        		<td class="smBlue" style="width: 7% !important;"> {{ $years[0] }} Ad Sales </td>
						        	@for($d=0; $d < sizeof($mtxDN['previousAdSales']); $d++)
						        		@if($d == 12)
						        			<?php $clr = 'smBlue'; ?>
						        		@else
						        			<?php $clr = 'medBlue'; ?>
						        		@endif
						        		<td class="{{$clr}}" style="width: 4%;"> {{ number_format($mtxDN['currentAdSales'][$d]) }} </td>
						        	@endfor
					        	</tr>

					        	<tr class="center">
					        		<td class="smBlue" style="width: 7% !important;"> %({{ $years[0] }}F - {{ $years[1] }}) </td>
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
						        		<td class="{{$clr}}" style="width: 4%;"> {{ number_format($temp) }}% </td>
						        	@endfor
					        	</tr>

					        	<tr class="center">
					        		<td class="smBlue" style="width: 7% !important;"> %({{ $years[0] }}F - Target) </td>
						        	@for($d=0; $d < sizeof($mtxDN['previousAdSales']); $d++)
						        		@if($d == 12)
						        			<?php $clr = 'smBlue'; ?>
						        		@else
						        			<?php $clr = 'rcBlue'; ?>
						        		@endif
						        		<?php
						        			if($mtxDN['currentTarget'][$d] > 0){
						        				$temp = ($mtxDN['currentCorporate'][$d]/$mtxDN['currentTarget'][$d])*100;
						        			}else{
						        				$temp = 0.0;
						        			}
						        		?>
						        		<td class="{{$clr}}" style="width: 4%;"> {{ number_format($temp) }}% </td>
						        	@endfor
					        	</tr>
					        </table>

					        <table style='width: 100%; zoom: 85%;font-size: 16px;'>
								<tr class="center">
					        		<td style="width: 7% !important; background-color: white;"> &nbsp; </td>
					        	</tr>
					        </table>
				        </div>

				        @for ($c=0; $c < sizeof($mtx['previousAdSales']); $c++)
						    <div class="row mt-2">
						    	<table style='width: 100%; zoom: 85%;font-size: 16px;'>
						        	<tr class="center">
						        		<td class='lightBlue center' style="width: 7% !important;"> {{$typeSelectS[$c]['salesRep']}} </td>
							        	@for($m=0; $m < sizeof($month); $m++)
							        		<td class='lightGrey center' style="width: 4%;"> {{ $month[$m] }} </td>
							        	@endfor
							        		<td class='darkBlue center' style="width: 4%;"> Total </td>
						        	</tr>

						        	<tr class="center">
						        		<td class="medBlue" style="width: 7% !important;"> {{ $years[1] }} Ad Sales </td>
							        	@for($d=0; $d < sizeof($mtx['previousAdSales'][$c]); $d++)
							        		@if($d == 12)
							        			<?php $clr = 'smBlue'; ?>
							        		@else
							        			<?php $clr = 'medBlue'; ?>
							        		@endif
							        		<td class="{{$clr}}" style="width: 4%;"> {{ number_format($mtx['previousAdSales'][$c][$d]) }} </td>
							        	@endfor
						        	</tr>

						        	<tr class="center">
						        		<td class="medBlue" style="width: 7% !important;"> {{ $years[0] }} Target </td>
							        	@for($d=0; $d < sizeof($mtx['previousAdSales'][$c]); $d++)
							        		@if($d == 12)
							        			<?php $clr = 'smBlue'; ?>
							        		@else
							        			<?php $clr = 'medBlue'; ?>
							        		@endif
							        		<td class="{{$clr}}" style="width: 4%;"> {{ number_format($mtx['currentTarget'][$c][$d]) }} </td>
							        	@endfor
						        	</tr>
						        	
						        	<tr class="center">
						        		<td class="rcBlue" style="width: 7% !important;"> {{ $years[0] }} Corporate </td>
							        	@for($d=0; $d < sizeof($mtx['previousAdSales'][$c]); $d++)
							        		@if($d == 12)
							        			<?php $clr = 'smBlue'; ?>
							        		@else
							        			<?php $clr = 'rcBlue'; ?>
							        		@endif
							        		<td class="{{$clr}}" style="width: 4%;"> {{ number_format($mtx['currentCorporate'][$c][$d]) }} </td>
							        	@endfor
						        	</tr>

						        	<tr class="center">
						        		<td class="medBlue" style="width: 7% !important;"> {{ $years[0] }} Ad Sales </td>
							        	@for($d=0; $d < sizeof($mtx['previousAdSales'][$c]); $d++)
							        		@if($d == 12)
							        			<?php $clr = 'smBlue'; ?>
							        		@else
							        			<?php $clr = 'medBlue'; ?>
							        		@endif
							        		<td class="{{$clr}}" style="width: 4%;"> {{ number_format($mtx['currentAdSales'][$c][$d]) }} </td>
							        	@endfor
						        	</tr>

						        	<tr class="center">
						        		<td class="medBlue" style="width: 7% !important;"> %({{ $years[0] }}F - {{ $years[1] }}) </td>
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
							        		<td class="{{$clr}}" style="width: 4%;"> {{ number_format($temp) }}% </td>
							        	@endfor
						        	</tr>

						        	<tr class="center">
						        		<td class="rcBlue" style="width: 7% !important;"> %({{ $years[0] }}F - Target) </td>
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
							        		<td class="{{$clr}}" style="width: 4%;"> {{ number_format($temp) }}% </td>
							        	@endfor
						        	</tr>	
						        </table>	
						    </div>	        	
			        	@endfor		        	
			        </div>

						
	        	</div>
	        </div>			
		</div>

	@elseif($type == "advertiser" || $type == "agency" || $type == "agencyGroup") 	

		@if($type == "advertiser")
			<?php 
				$index = 'client'; 
				$typeShow = 'Advertiser';
			?>
		@elseif($type == "agency")
			<?php 
				$index = 'agency'; 
				$typeShow = 'Agency';
			?>
		@elseif($type == "agencyGroup")
			<?php 
				$index = 'agencyGroup'; 
				$typeShow = 'Agency Group';
			?>
		@endif

		<div class="container-fluid">
			<div class="row">
				<div class="col"> 
					<div class="container-fluid" style='width: 100%; zoom: 85%;font-size: 16px;'>
						<div class="row">
							
							<div class="col lightBlue">
								<center>
									<span style='font-size:24px;'> 
										{{ $salesRegion }} - Consolidate - {{$typeShow}} : ({{$currencyName}}/{{strtoupper($value)}})
									</span>
								</center>
							</div>
						</div>

						<div class="row sticky-top">
							<table style='width: 100%; zoom: 85%;font-size: 16px;'>
								<tr class="center">
					        		<td style="width: 7% !important; background-color: white;"> &nbsp; </td>
					        	</tr>
					        </table>

							<table style='width: 100%; zoom: 85%;font-size: 16px;'>
								<tr class="center">
					        		<td class='darkBlue center' style="width: 7% !important;"> WBD </td>
						        	@for($m=0; $m < sizeof($month); $m++)
						        		<td class='lightGrey center' style="width: 4%;"> {{ $month[$m] }} </td>
						        	@endfor
						        		<td class='darkBlue center' style="width: 4%;"> Total </td>						        		
				        		</tr>
							
								<tr class="center">
					        		<td class="smBlue" style="width: 7% !important;"> {{ $years[1] }} Ad Sales </td>
						        	@for($d=0; $d < sizeof($mtxDN['previousAdSales']); $d++)
						        		@if($d == 12)
						        			<?php $clr = 'smBlue'; ?>
						        		@else
						        			<?php $clr = 'medBlue'; ?>
						        		@endif
						        		<td class="{{$clr}}" style="width: 4%;"> {{ number_format($mtxDN['previousAdSales'][$d]) }} </td>
						        	@endfor
					        	</tr>

					        	<tr class="center">
					        		<td class="smBlue" style="width: 7% !important;"> {{ $years[0] }} Target </td>
						        	@for($d=0; $d < sizeof($mtxDN['previousAdSales']); $d++)
						        		@if($d == 12)
						        			<?php $clr = 'smBlue'; ?>
						        		@else
						        			<?php $clr = 'medBlue'; ?>
						        		@endif
						        		<td class="{{$clr}}" style="width: 4%;"> {{ number_format($mtxDN['currentTarget'][$d]) }} </td>
						        	@endfor
					        	</tr>
					        	
					        	<tr class="center">
					        		<td class="smBlue" style="width: 7% !important;"> {{ $years[0] }} Corporate </td>
						        	@for($d=0; $d < sizeof($mtxDN['previousAdSales']); $d++)
						        		@if($d == 12)
						        			<?php $clr = 'smBlue'; ?>
						        		@else
						        			<?php $clr = 'rcBlue'; ?>
						        		@endif
						        		<td class="{{$clr}}" style="width: 4%;"> {{ number_format($mtxDN['currentCorporate'][$d]) }} </td>
						        	@endfor
					        	</tr>

					        	<tr class="center">
					        		<td class="smBlue" style="width: 7% !important;"> {{ $years[0] }} Ad Sales </td>
						        	@for($d=0; $d < sizeof($mtxDN['previousAdSales']); $d++)
						        		@if($d == 12)
						        			<?php $clr = 'smBlue'; ?>
						        		@else
						        			<?php $clr = 'medBlue'; ?>
						        		@endif
						        		<td class="{{$clr}}" style="width: 4%;"> {{ number_format($mtxDN['currentAdSales'][$d]) }} </td>
						        	@endfor
					        	</tr>

					        	<tr class="center">
					        		<td class="smBlue" style="width: 7% !important;"> %({{ $years[0] }}F - {{ $years[1] }}) </td>
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
						        		<td class="{{$clr}}" style="width: 4%;"> {{ number_format($temp) }}% </td>
						        	@endfor
					        	</tr>

					        	<tr class="center">
					        		<td class="smBlue" style="width: 7% !important;"> %({{ $years[0] }}F - Target) </td>
						        	@for($d=0; $d < sizeof($mtxDN['previousAdSales']); $d++)
						        		@if($d == 12)
						        			<?php $clr = 'smBlue'; ?>
						        		@else
						        			<?php $clr = 'rcBlue'; ?>
						        		@endif
						        		<?php
						        			if($mtxDN['currentTarget'][$d] > 0){
						        				$temp = ($mtxDN['currentCorporate'][$d]/$mtxDN['currentTarget'][$d])*100;
						        			}else{
						        				$temp = 0.0;
						        			}
						        		?>
						        		<td class="{{$clr}}" style="width: 4%;"> {{ number_format($temp) }}% </td>
						        	@endfor
					        	</tr>
					        </table>

					        <table style='width: 100%; zoom: 85%;font-size: 16px;'>
								<tr class="center">
					        		<td style="width: 7% !important; background-color: white;"> &nbsp; </td>
					        	</tr>
					        </table>
				        </div>

				        @for ($c=0; $c < sizeof($mtx['previousAdSales']); $c++)
						    <div class="row mt-2">
						    	<table style='width: 100%; zoom: 85%;font-size: 16px;'>
						        	<tr class="center">
						        		<td class='lightBlue center' style="width: 7% !important;"> 
						        			<?php
						        				//$typeSelectS[$c][$index];
						        				echo $newMtx[$c]['typeSelect'][$index];
						        			?>
						        		</td>
							        	@for($m=0; $m < sizeof($month); $m++)
							        		<td class='lightGrey center' style="width: 4%;"> {{ $month[$m] }} </td>
							        	@endfor
							        		<td class='darkBlue center' style="width: 4%;"> Total </td>							        		
						        	</tr>

						        	<tr class="center">
						        		<td class="medBlue" style="width: 7% !important;"> {{ $years[1] }} Ad Sales </td>
							        	@for($d=0; $d < sizeof($mtx['previousAdSales'][$c]); $d++)
							        		@if($d == 12)
							        			<?php $clr = 'smBlue'; ?>
							        		@else
							        			<?php $clr = 'medBlue'; ?>
							        		@endif
							        		<td class="{{$clr}}" style="width: 4%;"> {{ number_format($newMtx[$c]['previousAdSales'][$d]) }} </td>
							        	@endfor
						        	</tr>

						        	<tr class="center">
						        		<td class="medBlue" style="width: 7% !important;"> {{ $years[0] }} Target </td>
							        	@for($d=0; $d < sizeof($mtx['previousAdSales'][$c]); $d++)
							        		@if($d == 12)
							        			<?php $clr = 'smBlue'; ?>
							        		@else
							        			<?php $clr = 'medBlue'; ?>
							        		@endif
							        		<td class="{{$clr}}" style="width: 4%;"> {{ number_format($newMtx[$c]['currentTarget'][$d]) }} </td>
							        	@endfor
						        	</tr>
						        	
						        	<tr class="center">
						        		<td class="rcBlue" style="width: 7% !important;"> {{ $years[0] }} Corporate </td>
							        	@for($d=0; $d < sizeof($mtx['previousAdSales'][$c]); $d++)
							        		@if($d == 12)
							        			<?php $clr = 'smBlue'; ?>
							        		@else
							        			<?php $clr = 'rcBlue'; ?>
							        		@endif
							        		<td class="{{$clr}}" style="width: 4%;"> {{ number_format($newMtx[$c]['currentCorporate'][$d]) }} </td>
							        	@endfor
						        	</tr>

						        	<tr class="center">
						        		<td class="medBlue" style="width: 7% !important;"> {{ $years[0] }} Ad Sales </td>
							        	@for($d=0; $d < sizeof($mtx['previousAdSales'][$c]); $d++)
							        		@if($d == 12)
							        			<?php $clr = 'smBlue'; ?>
							        		@else
							        			<?php $clr = 'medBlue'; ?>
							        		@endif
							        		<td class="{{$clr}}" style="width: 4%;"> {{ number_format($newMtx[$c]['currentAdSales'][$d]) }} </td>
							        	@endfor
						        	</tr>

						        	<tr class="center">
						        		<td class="medBlue" style="width: 7% !important;"> %({{ $years[0] }}F - {{ $years[1] }}) </td>
							        	@for($d=0; $d < sizeof($mtx['previousAdSales'][$c]); $d++)
							        		@if($d == 12)
							        			<?php $clr = 'smBlue'; ?>
							        		@else
							        			<?php $clr = 'medBlue'; ?>
							        		@endif				        		 
						        			<?php
						        				if($newMtx[$c]['previousAdSales'][$d] > 0){
						        					$temp = ($newMtx[$c]['currentCorporate'][$d]/$newMtx[$c]['previousAdSales'][$d])*100; 
						        				}else{
						        					$temp = 0.0;
						        				}
						        			?>
							        		<td class="{{$clr}}" style="width: 4%;"> {{ number_format($temp) }}% </td>
							        	@endfor
						        	</tr>

						        	<tr class="center">
						        		<td class="rcBlue" style="width: 7% !important;"> %({{ $years[0] }}F - Target) </td>
							        	@for($d=0; $d < sizeof($mtx['previousAdSales'][$c]); $d++)
							        		@if($d == 12)
							        			<?php $clr = 'smBlue'; ?>
							        		@else
							        			<?php $clr = 'rcBlue'; ?>
							        		@endif
							        		<?php
						        				if($newMtx[$c]['currentTarget'][$d]){
						        					$temp = ($newMtx[$c]['currentCorporate'][$d]/$newMtx[$c]['currentTarget'][$d])*100;
						        				}else{
						        					$temp = 0.0;
						        				}
						        			?>
							        		<td class="{{$clr}}" style="width: 4%;"> {{ number_format($temp) }}% </td>
							        	@endfor
						        	</tr>	
						        </table>	
						    </div>	        	
			        	@endfor		        	
			        </div>

						
	        	</div>
	        </div>			
		</div>

	@endif

</div>

<div id="vlau"></div>

<script type="text/javascript">
    $(document).ready(function(){

        ajaxSetup();

        $('#excel').click(function(event){
            var typeExcel = "<?php echo $typeExcel; ?>";
            var regionExcel = "<?php echo $regionExcel; ?>";
            var valueExcel = "<?php echo $valueExcel; ?>";
            var currencyExcel = "<?php echo $currencyExcel; ?>";
            var typeSelectExcel = "<?php echo base64_encode(json_encode($typeSelectExcel)); ?>";
            var userRegionExcel = "<?php echo $userRegionExcel; ?>";


            var div = document.createElement('div');
            var img = document.createElement('img');
            img.src = '/loading_excel.gif';
            div.innerHTML ="Generating File...</br>";
            div.style.cssText = 'position: absolute; left: 0px; top:0px;  margin:0px;        width: 100%;        height: 100%;        display:block;        z-index: 99999;        opacity: 0.9;        -moz-opacity: 0;        filter: alpha(opacity = 45);        background: white;    background-repeat: no-repeat;        background-position:50% 50%;        text-align: center;        overflow: hidden;   font-size:30px;     font-weight: bold;        color: black;        padding-top: 20%';
            div.appendChild(img);
            document.body.appendChild(div);

            //var typeExport = $("#excel").val();
            var typeExport = "Excel";

            var title = "<?php echo $titleExcel; ?>";
            var auxTitle = "<?php echo $titleExcel; ?>";
                
                $.ajax({
                    xhrFields: {
                        responseType: 'blob',
                    },
                    url: "/generate/excel/results/consolidate",
                    type: "POST",
                    data: {title, typeExport, auxTitle, typeExcel, regionExcel,typeSelectExcel,valueExcel, currencyExcel,userRegionExcel},
                    /*success: function(output){
                        $("#vlau").html(output);
                    },*/
                    success: function(result,status,xhr){
                        var disposition = xhr.getResponseHeader('content-disposition');
                        var matches = /"([^"]*)"/.exec(disposition);
                        var filename = (matches != null && matches[1] ? matches[1] : title);

                        //download
                        var blob = new Blob([result], {
                            type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
                        });
                        var link = document.createElement('a');
                        link.href = window.URL.createObjectURL(blob);
                        link.download = filename;

                        document.body.appendChild(link);

                        link.click();
                        document.body.removeChild(link);
                        document.body.removeChild(div);
                    },
                    error: function(xhr, ajaxOptions, thrownError){
                        document.body.removeChild(div);
                        alert(xhr.status+" "+thrownError);
                    }
                });                    
            });
        });
</script>

@endsection