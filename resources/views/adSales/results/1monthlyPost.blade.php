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
			<form class="form-inline" role="form" method="POST" action="{{ route('resultsMonthlyPost') }}">
				@csrf
				<div class="container-fluid">
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
							<label class="labelLeft"><span class="bold"> Year: </span></label>
							{{$render->year($regionID)}}					
						</div>	

						<div class="col">
							<label class="labelLeft"><span class="bold"> Brand: </span></label>
							{{$render->brand($brand)}}
						</div>	

						<div class="col-12 col-lg">
							<label class="labelLeft"><span class="bold"> 1st Pos </span></label>
							{{$render->position("second")}}
						</div>				

						<div class="col-12 col-lg">
							<label class="labelLeft"><span class="bold"> 2st Pos </span></label>
							{{$render->position("third")}}
						</div>				

						<div class="col">
							<label class="labelLeft"><span class="bold"> Currency: </span></label>
							{{$render->currency($currency)}}
						</div>

						<div class="col">
							<label class="labelLeft"><span class="bold"> Value: </span></label>
							{{$render->value()}}
						</div>

						<div class="col-12 col-lg">
							<div class="form-inline">
								<label class="labelLeft"><span class="bold"> &nbsp; </span> </label>
								<input type="submit" value="Generate" class="btn btn-primary" style="width: 100%;">						
							</div>
						</div>
					</div>
				</div>

			</form>
		</div>
	</div>

	<br>
		
	<div class="row no-gutters">
		<div class="col-9"></div>
		<div class="col-3" style="color: #0070c0;font-size: 25px">
			Monthly ({{$form}}) {{$year}}
		</div>
	</div>

	<div class="row mt-2">
		<div class="col table-responsive">
			{{ $render->assemble($mtx,$currencyS,$value,$year,$form) }}
		</div>
	</div>
</div>

	
@endsection