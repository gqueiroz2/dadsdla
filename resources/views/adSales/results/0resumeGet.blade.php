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
							<label class="labelLeft"><span class="bold"> Region: </span></label>
							{{$render->region($region)}}
						</div>
						<div class="col">
							<label class="labelLeft"><span class="bold"> Brands: </span></label>
							{{$render->brand($brand)}}
						</div>
						<div class="col">
							<label class="labelLeft"><span class="bold"> Currency: </span></label>
							<label>Font:</label>
							{{$render->font($region,2019)}}
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
							<label class="labelLeft"><span class="bold"> Value: </span></label>
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