@extends('layouts.mirror')
@section('title', 'Dashboards Overview')
@section('head')	
	<script src="/js/dashboards-overview.js"></script>
    <?php include(resource_path('views/auth.php')); ?>
@endsection

@section('content')
	<div class="container-fluid">
		<div class="row">
			<div class="col">
				<form method="POST" action="{{ route('rankingPost') }}" runat="server" onsubmit="ShowLoading()">
					@csrf
					<div class="row">
						<div class="col">
							<label class="labelLeft bold"> Region: </label>
							@if($errors->has('region'))
								<label style="color: red;">* Required</label>
							@endif
							@if($userLevel == 'L0' || $userLevel == 'SU')
								{{$render->region($salesRegion)}}
							@else
								{{$render->regionFiltered($region, $regionID)}}
							@endif
						</div>
						<div class="col">
							<label class="labelLeft bold"> Type: </label>
							@if($errors->has('type'))
								<label style="color: red;">* Required</label>
							@else
								{{$render->type()}}
							@endif
						</div>						
						<div class="col">
							<label class="labelLeft bold" id="labelBaseFilter"> <span style="color: red;"> Select Type </span> </label>
							@if($errors->has('baseFilter'))
								<label style="color: red;">* Required</label>
							@endif
							{{$render->baseFilter()}}
						</div>
						<div class="col">
							<label class="labelLeft bold" id="labelSecondaryFilter"> <span style="color: red;"> Select Type </span> </label>
							@if($errors->has('secondaryFilter'))
								<label style="color: red;">* Required</label>
							@endif
							{{$render->secondaryFilter()}}
							
						</div>						
						<div class="col">
							<label class="labelLeft bold"> Currency: </label>
							@if($errors->has('currency'))
								<label style="color: red;">* Required</label>
							@endif
							{{$render->currency()}}
						</div>
						<div class="col">
							<label class="labelLeft bold"> Value: </label>
							@if($errors->has('value'))
								<label style="color: red;">* Required</label>
							@endif
							{{$render->value2()}}
						</div>
						<div class="col">
							<label class="labelLeft"><span class="bold"> &nbsp; </span> </label>
							<input type="submit" value="Generate" class="btn btn-primary" style="width: 100%;">
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>

	<div id="vlau"></div>

@endsection



    

    