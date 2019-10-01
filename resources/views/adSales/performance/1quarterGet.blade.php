@extends('layouts.mirror')
@section('title', 'quarter')
@section('head')	
	<script src="/js/performance.js"></script>
    <?php include(resource_path('views/auth.php')); ?>
@endsection

@section('content')
	@if($userLevel != 'SU' || $userLevel != 'L0' || $userLevel != 'L1' || $userLevel != 'L3' || $userLevel != 'L4' )
		<div class="container-fluid">
			<div class="row">
				<div class="col">
					<form method="POST" action="{{ route('quarterPerformancePost') }}"  runat="server"  onsubmit="ShowLoading()">
						@csrf
						<div class="row">
							<div class="col">
								<label>Region:</label>
								@if($errors->has('region'))
									<label style="color: red;">* Required</label>
								@endif
								@if($userLevel == 'L0' || $userLevel == 'SU')
									{{ $render->region($salesRegion) }}
								@else
									{{ $render->regionFiltered($salesRegion, $regionID, $special) }}
								@endif
							</div>

							<div class="col">
								<label>Year:</label>
								@if($errors->has('year'))
									<label style="color: red;">* Required</label>
								@endif
								{{ $render->year() }}
							</div>

							<div class="col">
								<label>Tiers:</label>
								@if($errors->has('tier'))
									<label style="color: red;">* Required</label>
								@endif
								{{ $render->tiers() }}
							</div>					

							<div class="col">
								<label>Brands:</label>
								@if($errors->has('brand'))
									<label style="color: red;">* Required</label>
								@endif
								{{ $render->brandPerformance() }}
							</div>	

							<div class="col-2">
								<label>Sales Rep Group:</label>
								@if($errors->has('salesRepGroup'))
									<label style="color: red;">* Required</label>
								@endif
								{{ $render->salesRepGroup($salesRepGroup) }}
							</div>

							<div class="col">
								<label style="float: left;">Sales Rep:</label>
								@if($errors->has('salesRep'))
									<label style="color: red;">* Required</label>
								@endif
								{{$render->salesRep($salesRep)}}
							</div>

							<div class="col">
								<label> Currency: </label>
								@if($errors->has('currency'))
									<label style="color: red;">* Required</label>
								@endif
								{{ $render->currency() }}
							</div>

							<div class="col">
								<label> Value: </label>
								@if($errors->has('value'))
									<label style="color: red;">* Required</label>
								@endif
								{{ $render->value() }}
							</div>

							<div class="col">
								<label> &nbsp; </label>
								<input type="submit" value="Generate" class="btn btn-primary" style="width: 100%">		
							</div>	
						</div>
					</form>
				</div>
			</div>

			<div class="row justify-content-end mt-2">
				<div class="col"></div>
				<div class="col"></div>
				<div class="col"></div>
				<div class="col"></div>
				<div class="col-2"></div>
				<div class="col"></div>
				<div class="col"></div>
				<div class="col"></div>
				<div class="col" style="color: #0070c0;font-size: 22px;">
					Office
				</div>
			</div>

		</div>
	@else
	@endif
	<div id="vlau"></div>
@endsection