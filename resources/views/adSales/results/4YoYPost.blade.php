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
			<div class="col-sm">
				<form method="POST" action="{{ route('resultsYoYGet') }}" runat="server"  onsubmit="ShowLoading()">
					@csrf
					<div class="row">
						<div class="col-sm">
							<label>Sales Region</label>
							@if($userLevel == 'L0' || $userLevel == 'SU')
								{{$render->region($region)}}							
							@elseif($userLevel == '1B')
								{{$render->regionFilteredReps($region, $regionID)}}
							@else
								{{$render->regionFiltered($region, $regionID)}}
							@endif
						</div>

						<div class="col-sm">
							<label>Year</label>
							{{ $render->year() }}
						</div>

						<div class="col-sm">
							<label>Brand</label>
							{{ $render->brand($brand) }}
						</div>	

						<div class="col-sm">
							<label> 1st Pos </label>
							{{$render->position("first")}}
						</div>	

						<div class="col-sm">
							<label> 2st Pos </label>
							{{$render->position("second")}}
						</div>	

						<div class="col-sm">
							<label> 3rd Pos </label>
							{{$render->position("third")}}
						</div>	

						<div class="col-sm">
							<label> Currency </label>
							{{$render->currency()}}
						</div>	

						<div class="col-sm">
							<label> Value </label>
							{{ $render->value() }}
						</div>

						<div class="col-sm-2">
							<label> &nbsp; </label>
							<input type="submit" value="Generate" class="btn btn-primary" style="width: 100%">
						</div>
					</div>
				</form>
			</div>
		</div>
		
		<div class="row justify-content-end mt-2">
			<div class="col-sm"></div>
			<div class="col-sm"></div>
			<div class="col-sm"></div>
			<div class="col-sm"></div>
			<div class="col-sm"></div>
			<div class="col-sm"></div>
			<div class="col-sm"></div>
			<div class="col-sm"></div>
			<div class="col-sm-3" style="color: #0070c0;font-size: 22px;">
				<span style="float: right;"> {{$rName}} - Year Over Year : {{$form}} - {{$year}} </span>
			</div>
			<div class="col-sm-2">
				<button type="button" class="btn btn-primary" style="width: 100%">
					Generate Excel
				</button>				
			</div>
		</div>	

	</div>

	<div class="container-fluid" style="margin-right: 0.5%; margin-left: 0.5%; font-size: 12px">
		<div class="row mt-2">
			<div class="col-sm table-responsive-sm">
				{{$render->assemble($matrix,$form,$pRate,$value,$year,$region)}}
			</div>
		</div>
	</div>

	<div id="vlau"></div>

@endsection