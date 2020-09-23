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
			<span style="float: right;"> Pacing </span>
		</div>
	</div>	


	
</div>

	<div class="container-fluid">
		<div class="row">
			<div class="col"> 
				<table>
		        @for ($c=0; $c < sizeof($preMtx); $c++)
		        	<tr>
		        	@for($d=0; $d < sizeof($preMtx[$c]); $d++)
		        		<td> {{ $preMtx[$c][$d] }} </td>
		        	@endfor
		        	</tr>
		        @endfor
		        </table>
        	</div>
        </div>			
	</div>

<div id="vlau"></div>
	
@endsection