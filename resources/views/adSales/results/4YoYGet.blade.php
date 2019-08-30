@extends('layouts.mirror')
@section('title', 'YoY Results')
@section('head')	
	<script src="/js/resultsYoY.js"></script>
	<script src="/js/results.js"></script>
    <?php include(resource_path('views/auth.php')); ?>
@endsection
@section('content')

	<div class="container-fluid">
		<div class="row">
			<div class="col-sm">
				<form method="POST" action="{{ route('resultsYoYPost') }}" runat="server"  onsubmit="ShowLoading()">
					@csrf
					<div class="row">
						<div class="col-sm">
							<div class="form-inline">
								<label>Region:</label>
								@if($errors->has('region'))
									<label style="color: red;">* Required</label>
								@endif
								@if($userLevel == 'L0' || $userLevel == 'SU')
									{{$render->region($salesRegion)}}							
								@else
									{{$render->regionFiltered($salesRegion, $regionID, $special)}}
								@endif
							</div>
						</div>

						<div class="col-sm">
							<div class="form-inline">
								<label>Year:</label>
								@if($errors->has('year'))
									<label style="color: red;">* Required</label>
								@endif
								{{ $render->year() }}
							</div>
						</div>

						<div class="col-sm">
							<div class="form-inline">
								<label>Brand:</label>
								@if($errors->has('brand'))
									<label style="color: red;">* Required</label>
								@endif
								{{ $render->brand($brandsValue) }}
							</div>
						</div>	

						<div class="col-sm">
							<div class="form-inline">
								<label> 1st Pos: </label>
								@if($errors->has('firstPos'))
									<label style="color: red;">* Required</label>
								@endif
								{{$render->position("first")}}
							</div>
						</div>	

						<div class="col-sm">
							<div class="form-inline">
								<label> 2st Pos: </label>
								@if($errors->has('secondPos'))
									<label style="color: red;">* Required</label>
								@endif
								{{$render->position("second")}}
							</div>
						</div>	

						<div class="col-sm">
							<div class="form-inline">
								<label> 3rd Pos: </label>
								@if($errors->has('thirdPos'))
									<label style="color: red;">* Required</label>
								@endif
								{{$render->position("third")}}
							</div>
						</div>	

						<div class="col-sm">
							<div class="form-inline">
								<label> Currency: </label>
								@if($errors->has('currency'))
									<label style="color: red;">* Required</label>
								@endif
								{{$render->currency()}}
							</div>
						</div>	

						<div class="col-sm">
							<div class="form-inline">
								<label> Value: </label>
								@if($errors->has('value'))
									<label style="color: red;">* Required</label>
								@endif
								{{ $render->value() }}
							</div>
						</div>

						<div class="col-sm">
							<div class="form-inline">
								<label> &nbsp; </label>
								<input type="submit" value="Generate" class="btn btn-primary" style="width: 100%">		
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>

		<div class="row justify-content-end mt-2">
			<div class="col-sm" style="color: #0070c0;font-size: 22px;">
				<div style="float: right;"> Year Over Year </div>
			</div>
		</div>	


	</div>

	<div id="vlau"></div>



@endsection