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
			<form method="POST" action="{{ route('resultsMonthlyPost') }}" runat="server" onsubmit="ShowLoading()">
				@csrf
				<div class="row">
					<div class="col-sm">
						<label class="labelLeft"><span class="bold"> Region: </span></label>
						@if($userLevel == 'L0' || $userLevel == 'SU')
							{{$render->region($region)}}							
						@else
							{{$render->regionFiltered($region, $regionID, $special)}}
						@endif
					</div>

					<div class="col-sm">
						<label class="labelLeft"><span class="bold"> Year: </span></label>
						{{$render->year($regionID)}}					
					</div>	

					<div class="col-sm">
						<label class="labelLeft"><span class="bold"> Brand: </span></label>
						{{$render->brand($brand)}}
					</div>	

					<div class="col-sm">
						<label class="labelLeft"><span class="bold"> 1st Pos </span></label>
						{{$render->position("second")}}
					</div>				

					<div class="col-sm">
						<label class="labelLeft"><span class="bold"> 2st Pos </span></label>
						{{$render->position("third")}}
					</div>				

					<div class="col-sm">
						<label class="labelLeft"><span class="bold"> Currency: </span></label>
						{{$render->currency($currency)}}
					</div>

					<div class="col-sm">
						<label class="labelLeft"><span class="bold"> Value: </span></label>
						{{$render->value()}}
					</div>

					<div class="col-sm-2">
						<label class="labeexpressionlLeft"><span class="bold"> &nbsp; </span> </label>
						<input type="submit" value="Generate" class="btn btn-primary" style="width: 100%;">
					</div>
				</div>
			</form>	
		</div>
	</div>

	<form method="POST" action="{{ route('monthExcel') }}" runat="server">
		@csrf
		<input type="hidden" name="region" value="{{$regionID}}">
		<input type="hidden" name="year" value="{{$year}}">
		<input type="hidden" name="brand" value="{{ base64_encode(json_encode($brandID)) }}">
		<input type="hidden" name="firstPos" value="{{$firstPos}}">
		<input type="hidden" name="secondPos" value="{{$secondPos}}">
		<input type="hidden" name="currency" value="{{ base64_encode(json_encode($tmp)) }}">
		<input type="hidden" name="value" value="{{$value}}">

		<div class="row justify-content-end mt-2">
			<div class="col-sm"></div>
			<div class="col-sm"></div>
			<div class="col-sm"></div>
			<div class="col-sm"></div>
			<div class="col-sm"></div>
			<div class="col-sm"></div>
			<div class="col-sm-2" style="color: #0070c0;font-size: 22px;">
				<span style="float: right;"> {{$rName}} - Month : {{$form}} - {{$year}} </span>
			</div>
			<div class="col-sm-2">
				<input type="submit" value="Generate Excel" class="btn btn-primary" style="width: 100%;">
			</div>
		</div>
	</form>
	
</div>

<div class="container-fluid">
	<div class="row mt-2">
		<div class="col-sm table-responsive-sm">
			{{ $render->assemble($mtx,$currencyS,$value,$year,$form, $salesRegion) }}
		</div>
	</div>	
</div>
	


	
@endsection