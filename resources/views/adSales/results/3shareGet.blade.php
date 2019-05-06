@extends('layouts.mirror')
@section('title', 'Share')
@section('head')	
	<script src="/js/resultsShare.js"></script>
@endsection
@section('content')
	<div class="container-fluid">
		
		<div class="row">
			<div class="col">
				<form method="POST" action="{{ route('resultsSharePost') }}">
					@csrf
					<div class="row">
						<div class="col col-2">
							<label class='labelLeft'>Region:</label>
							{{$render->region($region)}}
						</div>
						<div class="col col-2">
							<label class='labelLeft'>Year:</label>
							{{$render->year()}}
						</div>
						<div class="col col-2">
							<label class='labelLeft'>Brands:</label>
							{{$render->brand($brand)}}
						</div>
						<div class="col col-2">
							<label class='labelLeft'>Source:</label>
							{{$render->source()}}
						</div>
						<div class="col col-2">
							<label class='labelLeft'>Sales Rep Group:</label>
							{{$render->salesRepGroup($salesRepGroup)}}
						</div>
						<div class="col col-2">
							<label class='labelLeft'>Sales Rep:</label>
							{{$render->salesRep($salesRep)}}
						</div>
						<div class="col col-2">
							<label class='labelLeft'>Months:</label>
							{{$render->months()}}
						</div>
						<div class="col col-2">
							<label class='labelLeft'>Currency:</label>
							{{$render->currency($currency)}}
						</div>
						<div class="col col-2">
							<label class='labelLeft'>Value:</label>
							{{$render->value()}}
						</div>
						<div class="col col-2">
							<label class='labelLeft'> &nbsp; </label>
							<input style="width: 100%;" type="submit" value="Generate" class="btn btn-primary">		
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>

	<div id="vlau"></div>

@endsection