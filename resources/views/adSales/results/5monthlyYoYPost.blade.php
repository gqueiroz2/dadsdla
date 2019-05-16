@extends('layouts.mirror')
@section('title', 'monthly YoY Results')
@section('head')	
	<script src="/js/resultsYoY.js"></script>
	<script src="/js/results.js"></script>
    <?php include(resource_path('views/auth.php')); ?>
@endsection
@section('content')

	<div class="container-fluid">
		<div class="row">
			<div class="col">
				<form class="form-inline" role="form" method="POST" action="{{ route("resultsMonthlyYoYPost") }}">
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
	</div>
	
	<br>
		
	<div class="row no-gutters">
		<div class="col-9"></div>
		<div class="col-3" style="color: #0070c0;font-size: 25px">
			Monthly Year Over Year ({{$form}}) {{$year}}
		</div>
	</div>

	<div class="col-sm-2" style="float: right">
		<button id="buttonModal" type="button" class="btn btn-primary" data-toggle="modal" data-target="#SemestresTotal" style="width: 90%">
			Semestre e Total
		</button>
	</div>

	<div class="modal" id="SemestresTotal" role="dialog" style="display: hidden;">
		<div id="myModal" class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title">Monthly Year Over Year - ({{$pRate[0]['name']}}/{{$value}})</h4>
					<button type="button" class="close" data-dismiss="modal">
          				<span aria-hidden="true">&times;</span>
        			</button>
				</div>
				<div class="modal-body">
					{{ $renderMonthlyYoY->assembleModal($brands, $matrix[1], $year) }}
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
				</div>
			</div>
		</div>	
	</div>

	<br><br>

	<div class="container-fluid" style="margin-right: 0.5%; margin-left: 0.5%; font-size: 12px">
		<div class="row mt-2">
			<div class="col">

				<tr><td>&nbsp;</td></tr>
				{{$renderMonthlyYoY->assemble($matrix[0],$matrix[1],$form,$pRate,$value,$year,$base->getMonth(), $brands)}}
				
			</div>
		</div>
	</div>

@endsection