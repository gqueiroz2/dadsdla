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
							<label style="float: left;">Region:</label>
							{{$render->region($region)}}
						</div>
						<div class="col">
							<label style="float: left;">Year:</label>
							{{$render->year()}}
						</div>
						<div class="col">
							<label style="float: left;">Brands:</label>
							{{$render->brand($brand)}}
						</div>
						<div class="col">
							<label style="float: left;">Source:</label>
							{{$render->source()}}
						</div>
						<div class="col">
							<label style="float: left;">Sales Rep Group:</label>
							{{$render->salesRepGroup($salesRepGroup)}}
						</div>
						<div class="col">
							<label style="float: left;">Sales Rep:</label>
							{{$render->salesRep($salesRep)}}
						</div>
						<div class="col">
							<label style="float: left;">Months:</label>
							{{$render->months()}}
						</div>
						<div class="col">
							<label style="float: left;">Currency:</label>
							{{$render->currency($currency)}}
						</div>
						<div class="col">
							<label style="float: left;">Value:</label>
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