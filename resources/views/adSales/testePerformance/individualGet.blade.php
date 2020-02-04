@extends('layouts.mirror')
@section('title', 'Executive')
@section('head')	
<script src="/js/performance.js"></script>
    <?php include(resource_path('views/auth.php')); 
    ?>
@endsection
@section('content')
	@if($userLevel != 'SU' || $userLevel != 'L0' || $userLevel != 'L1' || $userLevel != 'L3' || $userLevel != 'L4' )
		<div class="container-fluid">		
			<div class="row">
				<div class="col">
					

					<form method="POST" action="{{ route('individualPost') }}"  runat="server"  onsubmit="ShowLoading()">
						@csrf
						<div class="row justify-content-center">
							<div class="col">	
								<label class='labelLeft'><span class="bold">Region:</span></label>
								@if($errors->has('region'))
									<label style="color: red;">* Required</label>
								@endif
								@if($userLevel == 'L0' || $userLevel == 'SU')
									{{$render->region($region)}}							
								@else
									{{$render->regionFiltered($region, $regionID , $special)}}
								@endif
							</div>
							<div class="col">
								<label class='labelLeft'><span class="bold">Year:</span></label>
								@if($errors->has('year'))
									<label style="color: red;">* Required</label>
								@endif
								{{$render->year()}}
							</div>
							<div class="col">
								<label class='labelLeft'><span class="bold">Tiers:</span></label>
								@if($errors->has('brand'))
									<label style="color: red;">* Required</label>
								@endif
								{{$render->tiers()}}
							</div>
							<div class="col">
								<label class='labelLeft'><span class="bold">Brands:</span></label>
								@if($errors->has('brand'))
									<label style="color: red;">* Required</label>
								@endif
								{{$render->brand($brand)}}
							</div>
							<div class="col">
								<label class='labelLeft'><span class="bold">Months:</span></label>
								@if($errors->has('month'))
									<label style="color: red;">* Required</label>
								@endif
								{{$render->months()}}
							</div>
						</div>
						<div class="row justify-content-center">
							<div class="col">
								<label class='labelLeft'><span class="bold">Sales Rep Group:</span></label>
								@if($errors->has('salesRepGroup'))
									<label style="color: red;">* Required</label>
								@endif
								{{$render->salesRepGroup($salesRepGroup)}}
							</div>
							<div class="col">
								<label class='labelLeft'><span class="bold">Sales Rep:</span></label>
								@if($errors->has('salesRep'))
									<label style="color: red;">* Required</label>
								@endif
								{{$render->salesRep()}}
							</div>
							<div class="col">
								<label class='labelLeft'><span class="bold">Currency:</span></label>
								@if($errors->has('currency'))
									<label style="color: red;">* Required</label>
								@endif
								{{$render->currency($currency)}}
							</div>
							<div class="col">
								<label class='labelLeft'><span class="bold">Value:</span></label>
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
					<div class="row justify-content-end">
						<div class="col col-3"  style="text-align: center; margin-top: 2%;">
							<span class="reportsTitle">Individual Performance</span>
						</div>
					</div>
				</div>
			</div>
		</div>
	@else
	@endif
	<div id="vlau"></div>

@endsection
