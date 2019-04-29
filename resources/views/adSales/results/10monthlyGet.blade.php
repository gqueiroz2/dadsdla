@extends('layouts.mirror')

@section('title', 'Quarter Results')

@section('head')	

@endsection

@section('content')

	<form class="form-inline" role="form" method="POST" action="{{ route('monthlyResultsPost') }}">
		
		@csrf

		<div class="container-fluid">
			<div class="row">
				
				<!-- Region Area -->
				<div class="col-12 col-lg">
					<div class="form-inline">
						<label>Sales Region</label>
						{{$render->region($salesRegion)}}
					</div>
				</div>

				<!-- Brand Area -->
				<div class="col-12 col-lg">
					<div class="form-inline">
						<label>Brand</label>
						{{$render->brand($brand)}}
					</div>
				</div>				

				<!-- 1st Pos Area -->
				<div class="col-12 col-lg">
					<div class="form-inline">
						<label> 1st Pos </label>
						{{$mRender->firstPos()}}
					</div>
				</div>				

				<!-- 2st Pos Area -->
				<div class="col-12 col-lg">
					<div class="form-inline">
						<label> 2st Pos </label>
						{{$mRender->secondPos()}}
					</div>
				</div>				

				
				<div class="col-12 col-lg">
					<div class="form-inline">
						<label> Currency </label>
						{{$render->currency($currencies)}}
						
					</div>
				</div>
				<div class="col-12 col-lg">
					<div class="form-inline">
						<label> Value </label>
						{{$render->value()}}
						
					</div>
				</div>
				<div class="col-12 col-lg">
					<div class="form-inline">
						<input type="submit" value="Search" class="btn btn-primary">		
						
					</div>
				</div>
			</div>
		</div>

	</form>



	
@endsection