@extends('layouts.mirror')
@section('title', 'Pacing Office')
@section('head')		
	<?php include(resource_path('views/auth.php')); ?>
@endsection
@section('content')
<div class="container-fluid">
	<div class="row">
		<div class="col">
			<form method="POST" action="{{ route('consolidateResultsPostOffice') }}" runat="server"  onsubmit="ShowLoading()">
				@csrf
				<div class="row">
					<div class="col-sm">
						<label class="labelLeft"><span class="bold"> Region: </span></label>
						@if($errors->has('region'))
							<label style="color: red;">* Required</label>
						@endif	
						@if($regionName == 'Brazil')
							{{$render->regionFiltered($region, $regionID, $special)}}																			
						@else
							{{$render->regionOffice($region, $regionName)}}														
						@endif
					</div>	

					<div class="col-sm">
						<label class="labelLeft"><span class="bold"> Company: </span></label>
						@if($errors->has('company'))
							<label style="color: red;">* Required</label>
						@endif
						{{$render->company()}}
					</div>									

					<div class="col-sm">
						<label class="labelLeft"><span class="bold"> Currency: </span></label>
						@if($errors->has('currency'))
							<label style="color: red;">* Required</label>
						@endif
						@if($regionName == 'Brazil')
							{{$render->currencyOffice()}}																			
						@else
							{{$render->currencyUSD($region, $regionName)}}														
						@endif	
						
					</div>

					<div class="col-sm">
						<label class="labelLeft"><span class="bold"> Value: </span></label>
						@if($errors->has('value'))
							<label style="color: red;">* Required</label>
						@endif
						{{$render->value4()}}
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
			<span style="float: right;"> Pacing Office </span>
		</div>
	</div>	
</div>

<div id="vlau"></div>

@endsection