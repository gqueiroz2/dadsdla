@extends('layouts.mirror')

@section('title', 'Resume Results')

@section('head')	

@endsection

@section('content')
	<div class="container-fluid">
		<div class="row">
			<div class="col">
				<form method="POST" action="{{ route('resultsResumePost') }}">
					@csrf
					<div class="row">
						<div class="col">
							<label>Region:</label>
							{{$render->region($region)}}
						</div>
						<div class="col">
							<label>Brands:</label>
							{{$render->brand($brand)}}
						</div>
						<div class="col">
							<label>Font:</label>
							{{$render->font()}}
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
							<label> &nbsp; </label>
							<input type="submit" value="Generate" class="btn btn-primary" style="width: 100%;">		
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>


@endsection