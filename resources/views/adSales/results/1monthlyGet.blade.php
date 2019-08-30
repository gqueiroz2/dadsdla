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
			<form method="POST" action="{{ route('resultsMonthlyPost') }}" runat="server"  onsubmit="ShowLoading()">
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
						<label class="labelLeft"><span class="bold"> Year: </span></label>
						@if($errors->has('year'))
							<label style="color: red;">* Required</label>
						@endif
						{{$render->year($regionID)}}					
					</div>	

					<div class="col-sm">
						<label class="labelLeft"><span class="bold"> Brand: </span></label>
						@if($errors->has('brand'))
							<label style="color: red;">* Required</label>
						@endif
						{{$render->brand($brand)}}
					</div>	

					<div class="col-sm">
						<label class="labelLeft"><span class="bold"> 1st Pos </span></label>
						@if($errors->has('secondPos'))
							<label style="color: red;">* Required</label>
						@endif
						{{$render->position("second")}}
					</div>				

					<div class="col-sm">
						<label class="labelLeft"><span class="bold"> 2st Pos </span></label>
						@if($errors->has('thirdPos'))
							<label style="color: red;">* Required</label>
						@endif
						{{$render->position("third")}}
					</div>				

					<div class="col-sm">
						<label class="labelLeft"><span class="bold"> Currency: </span></label>
						@if($errors->has('currency'))
							<label style="color: red;">* Required</label>
						@endif
						{{$render->currency($currency)}}
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
			<span style="float: right;"> Month </span>
		</div>
	</div>	
</div>

<div id="vlau"></div>
	
@endsection