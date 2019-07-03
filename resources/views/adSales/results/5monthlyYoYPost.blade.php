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
				<form method="POST" action="{{ route("resultsMonthlyYoYPost") }}" runat="server"  onsubmit="ShowLoading()">
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

						<div class="col-2">
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
			<div class="col-3" style="color: #0070c0;font-size: 22px;">
				{{$rName}} - Monthly Year Over Year : {{$form}} - {{$year}}
			</div>
			<div class="col-2">
				<button id="buttonModal" type="button" class="btn btn-primary" data-toggle="modal" data-target="#SemestresTotal" style="width: 100%">
					Semestre e Total
				</button>
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

				<tr><td>&nbsp;</td></tr>
				{{$render->assemble($matrix[0],$matrix[1],$form,$pRate,$value,$year,$base->getMonth(), $brands, $source, $region)}}	
			</div>
		</div>
	</div>



	<div class="modal" id="SemestresTotal" role="dialog" style="display: hidden;">
		<div id="myModal" class="modal-dialog modal-xl">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title">Monthly Year Over Year - ({{$pRate[0]['name']}}/{{strtoupper($value)}})</h4>
					<button type="button" class="close" data-dismiss="modal">
          				<span aria-hidden="true">&times;</span>
        			</button>
				</div>
				<div class="modal-body">
					<div class="table-responsive">
						{{ $render->assembleModal($brands, $matrix[1], $year, $source) }}
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
				</div>
			</div>
		</div>	
	</div>
@endsection