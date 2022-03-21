@extends('layouts.mirror')
@section('title', 'Pacing DLA')
@section('head')	
	<script src="/js/consolidateDLAResults.js"></script>
	<?php include(resource_path('views/auth.php')); ?>
@endsection
@section('content')
<div class="container-fluid">
	<div class="row">
		<div class="col">
			<form method="POST" action="{{ route('consolidateResultsPostDLA') }}" runat="server"  onsubmit="ShowLoading()">
				@csrf
				<div class="row">
					<div class="col-sm">
						<label class="labelLeft"><span class="bold"> Region: </span></label>
						@if($errors->has('region'))
							<label style="color: red;">* Required</label>
						@endif
						@if($userLevel == 'L0' || $userLevel == 'SU')
							{{$render->regionOffice($region)}}							
						@else
							{{$render->regionFiltered($region, $regionID, $special)}}
						@endif
					</div>

					<div class="col-sm">
						<label class="labelLeft"><span class="bold"> Type: </span></label>
						@if($errors->has('type'))
							<label style="color: red;">* Required</label>
						@endif
						
						{{$render->typeOffice()}}							
						
					</div>

					<div class="col-sm">
						<label class="labelLeft"><span class="bold" style="color: red;" id="typeSelectLabel"> Select Type </span></label>
						@if($errors->has('brand'))
							<label style="color: red;">* Required</label>
						@endif
						{{$render->typeSelectOffice()}}
					</div>						

					<div class="col-sm">
						<label class="labelLeft"><span class="bold"> Currency: </span></label>
						@if($errors->has('currency'))
							<label style="color: red;">* Required</label>
						@endif
						{{$render->currencyUSD()}}
					</div>

					<div class="col-sm">
						<label class="labelLeft"><span class="bold"> Value: </span></label>
						@if($errors->has('value'))
							<label style="color: red;">* Required</label>
						@endif
						{{$render->value3()}}
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
			<span style="float: right;"> Pacing X </span>
		</div>
	</div>	
</div>

<div id="vlau"></div>

@endsection