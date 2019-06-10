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
				<form method="POST" action="{{ route('resultsResumePost') }}">
					@csrf
					<div class="row">
						<div class="col">
							<label class="labelLeft"><span class="bold"> Region: </span></label>
							@if($userLevel == 'L0' || $userLevel == 'SU')
								{{$render->region($region)}}							
							@else
								{{$render->regionFiltered($region, $regionID )}}
							@endif
						</div>
						<div class="col">
							<label class="labelLeft"><span class="bold"> Brand: </span></label>
							{{$render->brand($brand)}}
						</div>
						<div class="col">
							<label class="labelLeft"><span class="bold"> Currency: </span></label>
							{{$render->currency($currency)}}
						</div>
						<div class="col">
							<label class="labelLeft"><span class="bold"> Value: </span></label>
							{{$render->value2()}}
						</div>
						<div class="col">
							<label> &nbsp; </label>
							<input type="submit" value="Generate" class="btn btn-primary" style="width: 100%;">		
						</div>
					</div>
				</form>
			</div>
		</div>
		
		<div class="row justify-content-end mt-2">
			<div class="col"></div>
			<div class="col"></div>
			<div class="col"></div>
			<div class="col" style="color: #0070c0;font-size: 22px">
				{{$rName}} - Summary : {{$salesShow}} - {{$cYear}}
			</div>

			<div class="col">
				<button type="button" class="btn btn-primary" style="width: 100%">
					Generate Excel
				</button>				
			</div>
		</div>
	</div>
	
	<div class="container-fluid" style="margin-right: 0.5%; margin-left: 0.5%; font-size:12px;">
		<div class="row mt-2">
			<div class="col">				
				{{ $render->assemble($salesRegion, $salesShow, $cYear, $currencyS, $valueS, $pYear, $matrix[0], "TV") }}
			</div>
		</div>
	</div>

	<div class="container-fluid"  style="margin-right: 0.5%; margin-left: 0.5%; font-size:12px;">
		<div class="row mt-2">
			<div class="col">
				{{ $render->assemble($salesRegion, $salesShow, $cYear, $currencyS, $valueS, $pYear, $matrix[1], "Digital") }}	
			</div>
		</div>		
	</div>

	<div class="container-fluid"  style="margin-right: 0.5%; margin-left: 0.5%; font-size:12px;">
		<div class="row mt-2">
			<div class="col">
				{{ $render->assemble($salesRegion, $salesShow, $cYear, $currencyS, $valueS, $pYear, $matrix[2], "DN") }}	
			</div>
		</div>		
	</div>
@endsection