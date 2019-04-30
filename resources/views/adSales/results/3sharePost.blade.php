@extends('layouts.mirror')

@section('title', 'Monthly Results')

@section('head')	

@endsection

@section('content')
	<div class="container-fluid">
		<div class="row">
			<div class="col">
				<form method="POST" action="{{ route('resultsSharePost') }}">
					@csrf
					<div class="row">
						<div class="col">
							<label>Region:</label>
							{{$render->region($region)}}
						</div>
						<div class="col">
							<label>Year:</label>
							{{$render->year()}}
						</div>
						<div class="col">
							<label>Brands:</label>
							{{$render->brand($brand)}}
						</div>
						<div class="col">
							<label>Source:</label>
							{{$render->source()}}
						</div>
						<div class="col">
							<label>Sales Rep Group:</label>
							{{$render->salesRepGroup($salesRepGroup)}}
						</div>
						<div class="col">
							<label>Sales Rep:</label>
							{{$render->salesRep($salesRep)}}
						</div>
						<div class="col">
							<label>Months:</label>
							{{$render->months()}}
						</div>
						<div class="col">
							<label>Currency:</label>
							{{$render->currency($currency)}}
						</div>
						<div class="col">
							<label>Value:</label>
							{{$render->value()}}
						</div>
						<div class="col">
							<input type="submit" value="Seach" class="btn btn-primary">		
						</div>
					</div>
				</form>
			</div>
		</div>
		<div class="row">
			<div class="col">
				{{$render->mtx($mtx)}}
			</div>
		</div>	
	</div>


@endsection