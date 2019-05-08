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
							@if($errors->has('region'))
								<label style="color: red;">* Required</label>
							@endif
							@if($userLevel == 'L0' || $userLevel == 'SU')
								{{$render->region($region)}}							
							@else
								{{$render->regionFiltered($region, $regionID )}}
							@endif
						</div>

						<div class="col">
							<label class="labelLeft"><span class="bold"> Year: </span></label>
							@if($errors->has('year'))
								<label style="color: red;">* Required</label>
							@endif
							{{$render->year()}}					
						</div>	

						<div class="col">
							<label class="labelLeft"><span class="bold"> Brand: </span></label>
							@if($errors->has('brand'))
								<label style="color: red;">* Required</label>
							@endif
							{{$render->brand($brand)}}
						</div>	

						<div class="col-12 col-lg">
							<label class="labelLeft"><span class="bold"> 1st Pos </span></label>
							@if($errors->has('secondPos'))
								<label style="color: red;">* Required</label>
							@endif
							{{$render->position("second")}}
						</div>				

						<div class="col-12 col-lg">
							<label class="labelLeft"><span class="bold"> 2st Pos </span></label>
							@if($errors->has('thirdPos'))
								<label style="color: red;">* Required</label>
							@endif
							{{$render->position("third")}}
						</div>				

						<div class="col">
							<label class="labelLeft"><span class="bold"> Currency: </span></label>
							@if($errors->has('currency'))
								<label style="color: red;">* Required</label>
							@endif
							{{$render->currency($currency)}}
						</div>

						<div class="col">
							<label class="labelLeft"><span class="bold"> Value: </span></label>
							@if($errors->has('value'))
								<label style="color: red;">* Required</label>
							@endif
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
</div>



	
@endsection