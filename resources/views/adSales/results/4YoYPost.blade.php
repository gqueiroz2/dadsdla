@extends('layouts.mirror')
@section('title', 'YoY Results')
@section('head')	
	<script src="/js/resultsYoY.js"></script>
	<script src="/js/results.js"></script>
    <?php include(resource_path('views/auth.php')); ?>
@endsection
@section('content')

	<div class="container-fluid">
		<div class="row">
			<div class="col">
				<form method="POST" action="{{ route('resultsYoYGet') }}">
					@csrf
					<div class="row">
						<div class="col">
							<label>Sales Region</label>
							@if($userLevel == 'L0' || $userLevel == 'SU')
								{{$render->region($salesRegion)}}							
							@else
								{{$render->regionFiltered($salesRegion, $regionID )}}
							@endif
						</div>

						<div class="col">
							<label>Year</label>
							{{ $render->year() }}
						</div>

						<div class="col">
							<label>Brand</label>
							{{ $render->brand($brand) }}
						</div>	

						<div class="col">
							<label> 1st Pos </label>
							{{$render->position("first")}}
						</div>	

						<div class="col">
							<label> 2st Pos </label>
							{{$render->position("second")}}
						</div>	

						<div class="col">
							<label> 3rd Pos </label>
							{{$render->position("third")}}
						</div>	

						<div class="col">
							<label> Currency </label>
							{{$render->currency()}}
						</div>	

						<div class="col">
							<label> Value </label>
							{{ $render->value() }}
						</div>

						<div class="col-2">
							<label> &nbsp; </label>
							<input type="submit" value="Generate" class="btn btn-primary" style="width: 100%">
						</div>
					</div>
				</form>
			</div>
		</div>
		
		<div class="row justify-content-end mt-2">
			<div class="col"></div>
			<div class="col"></div>
			<div class="col"></div>
			<div class="col"></div>
			<div class="col"></div>
			<div class="col"></div>
			<div class="col"></div>
			<div class="col"></div>
			<div class="col-3" style="color: #0070c0;font-size: 22px;">
				{{$rName}} - Year Over Year : {{$form}} - {{$year}}
			</div>
			<div class="col-2">
				<button type="button" class="btn btn-primary" style="width: 100%">
					Generate Excel
				</button>				
			</div>
		</div>	

	</div>

	<div class="container-fluid" style="margin-right: 0.5%; margin-left: 0.5%; font-size: 12px">
		<div class="row mt-2">
			<div class="col">
				{{$renderYoY->assemble($matrix,$form,$pRate,$value,$year,$region)}}
			</div>
		</div>
	</div>

	<div id="vlau"></div>

@endsection