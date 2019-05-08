@extends('layouts.mirror')
@section('title', 'Share')
@section('head')	
	<script src="/js/resultsShare.js"></script>
    <?php include(resource_path('views/auth.php')); 
    ?>
@endsection
@section('content')
	<div class="container-fluid">		
		<div class="row">
			<div class="col">
				<form method="POST" action="{{ route('resultsSharePost') }}">
					@csrf
					<div class="row justify-content-center">
						<div class="col">	
							<label class='labelLeft'>Region:</label>
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
							<label class='labelLeft'>Year:</label>
							@if($errors->has('year'))
								<label style="color: red;">* Required</label>
							@endif
							{{$render->year()}}
						</div>
						<div class="col">
							<label class='labelLeft'>Brands:</label>
							@if($errors->has('brand'))
								<label style="color: red;">* Required</label>
							@endif
							{{$render->brand($brand)}}
						</div>
						<div class="col">
							<label class='labelLeft'>Source:</label>
							@if($errors->has('source'))
								<label style="color: red;">* Required</label>
							@endif
							{{$render->source()}}
						</div>
						<div class="col">
							<label class='labelLeft'>Sales Rep Group:</label>
							@if($errors->has('salesRepGroup'))
								<label style="color: red;">* Required</label>
							@endif
							{{$render->salesRepGroup($salesRepGroup)}}
						</div>

					</div>
					<div class="row justify-content-center">
						<div class="col">
							<label class='labelLeft'>Sales Rep:</label>
							@if($errors->has('salesRep'))
								<label style="color: red;">* Required</label>
							@endif
							{{$render->salesRep($salesRep)}}
						</div>
						<div class="col">
							<label class='labelLeft'>Currency:</label>
							@if($errors->has('currency'))
								<label style="color: red;">* Required</label>
							@endif
							{{$render->currency($currency)}}
						</div>
						<div class="col">
							<label class='labelLeft'>Months:</label>
							@if($errors->has('month'))
								<label style="color: red;">* Required</label>
							@endif
							{{$render->months()}}
						</div>
						<div class="col">
							<label class='labelLeft'>Value:</label>
							@if($errors->has('value'))
								<label style="color: red;">* Required</label>
							@endif
							{{$render->value()}}
						</div>
						<div class="col">
							<label class='labelLeft'> &nbsp; </label>
							<input style="width: 100%;" type="submit" value="Generate" class="btn btn-primary">		
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>

	<div id="vlau"></div>

	<script type="text/javascript">
		var level = '{{$userLevel}}';
	</script>

@endsection