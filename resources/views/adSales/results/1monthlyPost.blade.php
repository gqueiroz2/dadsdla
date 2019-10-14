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
						{{$render->year()}}					
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
						{{$render->currency()}}
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

	<form method="POST" action="{{ route('monthExcel') }}" runat="server" onsubmit="ShowLoading()">
		@csrf
		<input type="hidden" name="regionExcel" value="{{$regionExcel}}">
		<input type="hidden" name="yearExcel" value="{{$yearExcel}}">
		<input type="hidden" name="brandExcel" value="{{ base64_encode(json_encode($brandExcel)) }}">
		<input type="hidden" name="firstPosExcel" value="{{$firstPosExcel}}">
		<input type="hidden" name="secondPosExcel" value="{{$secondPosExcel}}">
		<input type="hidden" name="currencyExcel" value="{{ base64_encode(json_encode($currencyExcel)) }}">
		<input type="hidden" name="valueExcel" value="{{$valueExcel}}">

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