@extends('layouts.mirror')
@section('title', 'AE Report')
@section('head')	
    <?php include(resource_path('views/auth.php')); ?>
    <script src="/js/pandr.js"></script>
@endsection
@section('content')
	<div class="container-fluid">
		<div class="row justify-content-end mt-2">
			<div class="col-3" style="color: #0070c0;font-size: 25px;">
				Account Executive Report
			</div>
		</div>
	</div>

	<form method="POST" action="{{ route('AEPost') }}" runat="server"  onsubmit="ShowLoading()">
		@csrf
		<div class="container-fluid">		
			<div class="row">
				<div class="col">
					<label class='labelLeft'><span class="bold">Region:</span></label>
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
					<label class='labelLeft'><span class="bold">Sales Rep:</span></label>
					@if($errors->has('salesRep'))
						<label style="color: red;">* Required</label>
					@endif
					{{$render->salesRep2()}}
				</div>
				<div class="col">
					<label class='labelLeft'><span class="bold">Currency:</span></label>
					@if($errors->has('currency'))
						<label style="color: red;">* Required</label>
					@endif
					{{$render->currency($currency)}}
				</div>	
				<div class="col">
					<label class='labelLeft'> &nbsp; </label>
					<input style="width: 100%;" type="submit" value="Generate" class="btn btn-primary">		
				</div>			
			</div>
		</div>
	</form>
	<div id="vlau">
		
	</div>

@endsection