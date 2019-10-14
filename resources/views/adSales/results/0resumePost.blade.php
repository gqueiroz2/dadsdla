@extends('layouts.mirror')
@section('title', 'Resume Results')
@section('head')	
	<script src="/js/resultsResume.js"></script>
    <?php include(resource_path('views/auth.php')); ?>
@endsection
@section('content')
	<div class="container-fluid">
		<div class="row">
			<div class="col">
				<form method="POST" action="{{ route('resultsResumePost') }}" runat="server"  onsubmit="ShowLoading()">
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
							<label class="labelLeft"><span class="bold"> Brand: </span></label>
							{{$render->brand($brand)}}
						</div>
						<div class="col-sm">
							<label class="labelLeft"><span class="bold"> Currency: </span></label>
							{{$render->currency($currency)}}
						</div>
						<div class="col-sm">
							<label class="labelLeft"><span class="bold"> Value: </span></label>
							{{$render->value2()}}
						</div>
						<div class="col-sm">
							<label> &nbsp; </label>
							<input type="submit" value="Generate" class="btn btn-primary" style="width: 100%;">		
						</div>
					</div>
				</form>
			</div>
		</div>
		
		<form method="POST" action="{{ route('summaryExcel') }}" runat="server">
			@csrf
			<input type="hidden" name="regionExcel" value="{{$regionExcel}}">
			<input type="hidden" name="brandExcel" value="{{ base64_encode(json_encode($brandExcel)) }}">
			<input type="hidden" name="currencyExcel" value="{{ base64_encode(json_encode($currencyExcel)) }}">
			<input type="hidden" name="valueExcel" value="{{$valueExcel}}">

			<div class="row justify-content-end mt-2">
				<div class="col-sm"></div>
				<div class="col-sm"></div>
				<div class="col-sm"></div>
				<div class="col-sm" style="color: #0070c0;font-size: 22px">
					<span style="float: right;"> {{$rName}} - Summary : {{$salesShow}} - {{$cYear}} </span>
				</div>

				<div class="col-sm">
					<button type="submit" class="btn btn-primary" style="width: 100%">
						Generate Excel
					</button>
				</div>
			</div>
		</form>
	</div>
	
	@for($t = 0; $t < sizeof($matrix); $t++)
		<div class="container-fluid" style="margin-right: 0.5%; margin-left: 0.5%; font-size:12px;">
			<div class="row mt-2">
				<div class="col-sm table-responsive">				
					{{ $render->assemble($salesRegion, $salesShow, $cYear, $currencyS, $valueS, $pYear, $matrix[$t], $names[$t]) }}
				</div>
			</div>
		</div>
	@endfor
@endsection