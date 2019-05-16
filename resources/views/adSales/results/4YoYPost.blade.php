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
				<form class="form-inline" role="form" method="POST" action="{{ route('resultsYoYGet') }}">
				@csrf
					<div class="col-12 col-lg">
						<div class="form-inline">
							<label>Sales Region</label>
							@if($userLevel == 'L0' || $userLevel == 'SU')
								{{$render->region($salesRegion)}}							
							@else
								{{$render->regionFiltered($salesRegion, $regionID )}}
							@endif
						</div>
					</div>

					<div class="col-12 col-lg">
						<div class="form-inline">
							<label>Year</label>
							{{ $render->year() }}
						</div>
					</div>

					<div class="col-12 col-lg">
						<div class="form-inline">
							<label>Brand</label>
							{{ $render->brand($brand) }}
						</div>
					</div>	

					<div class="col-12 col-lg">
						<div class="form-inline">
							<label> 1st Pos </label>
							{{$render->position("first")}}
						</div>
					</div>	

					<div class="col-12 col-lg">
						<div class="form-inline">
							<label> 2st Pos </label>
							{{$render->position("second")}}
						</div>
					</div>	

					<div class="col-12 col-lg">
						<div class="form-inline">
							<label> 3rd Pos </label>
							{{$render->position("third")}}
						</div>
					</div>	

					<div class="col-12 col-lg">
						<div class="form-inline">
							<label> Currency </label>
							{{$render->currency()}}
						</div>
					</div>	

					<div class="col-12 col-lg">
						<div class="form-inline">
							<label> Value </label>
							{{ $render->value() }}
						</div>
					</div>

					<div class="col-12 col-lg">
						<div class="form-inline">
							<label> &nbsp; </label>
							<input type="submit" value="Generate" class="btn btn-primary" style="width: 100%">
						</div>
					</div>
				</form>
			</div>
		</div>
		
		<br>
		
		<div class="row no-gutters">
			<div class="col-9"></div>
			<div class="col-3" style="color: #0070c0;font-size: 25px">
				Year Over Year ({{$form}}) {{$year}}
			</div>
		</div>

		<br>

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