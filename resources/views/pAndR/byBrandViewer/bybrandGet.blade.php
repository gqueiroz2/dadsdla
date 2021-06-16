@extends('layouts.mirror')
@section('title', 'Brand Viewer')
@section('head')	
    <?php include(resource_path('views/auth.php')); ?>
    <script src="/js/pandr.js"></script>
@endsection
@section('content')
	

	<form method="POST" action="{{ route('byBrandPost') }}" runat="server"  onsubmit="ShowLoading()">
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
						{{$render->regionFiltered($region, $regionID, $special )}}
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
					<label class='labelLeft'><span class="bold">Currency:</span></label>
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
						{{$render->value2()}}
				</div>

				<div class="col">
					<label class='labelLeft'> &nbsp; </label>
					<input style="width: 100%;" type="submit" value="Generate" class="btn btn-primary">		
				</div>			
			</div>
			<br>
			<div class="row">
				<center style="width: 100%;">
					<div class="col-3">
						@if($typeMsg == "Success")
							<div class="alert alert-info">
								{{$msg}}
							</div>
						@elseif($typeMsg == "Error")
							<div class="alert alert-danger">
								{{$msg}}
							</div>
						@endif
					</div>
				</center>
			</div>
		</div>
	</form>
	<div class="container-fluid">
		<div class="row justify-content-end mt-2">
			<div class="col-3" style="color: #0070c0;font-size: 25px;">
				By Brand Viewer
			</div>
		</div>
	</div>
	<div id="vlau">
		
	</div>

@endsection