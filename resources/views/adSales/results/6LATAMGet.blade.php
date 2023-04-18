@extends('layouts.mirror')
@section('title', 'Daily Results')
@section('head')	
	<script src="/js/resultsLATAM.js"></script>
	<?php include(resource_path('views/auth.php')); ?>
@endsection
@section('content')
<div class="container-fluid">
	<div class="row">
		<div class="col">
			<form method="POST" action="{{ route('resultsLATAMPost') }}" runat="server"  onsubmit="ShowLoading()">
				@csrf
				<div class="row">
					<div class="col-sm">
						<label class="labelLeft"><span class="bold"> Region: </span></label>
						@if($errors->has('region'))
							<label style="color: red;">* Required</label>
						@endif
						@if($userLevel == 'L0' || $userLevel == 'SU')
							{{$render->region($region)}}							
						@else
							{{$render->regionFiltered($region, $regionID, $special)}}
						@endif							
					</div>

					<div class="col-sm">
						<label class="labelLeft"><span class="bold"> Currency: </span></label>
						@if($errors->has('currency'))
							<label style="color: red;">* Required</label>
						@endif
						{{$render->currency()}}
					</div>

					<div class="col-sm">
						<label class="labelLeft"><span class="bold"> Value: </span></label>
						@if($errors->has('value'))
							<label style="color: red;">* Required</label>
						@endif
						{{$render->value4()}}
					</div>

					<!--<div class="row justify-content-center">          
						<div class="col">       
							<div class="form-group">
								<label><b> Date: </b></label> 
								@if($errors->has('log'))
									<label style="color: red;">* Required</label>
								@endif
								<input type="date" class="form-control" name="log" value="{{date("m/d/Y")}}">
							</div>
						</div>
					</div>  -->

					<div class="col-sm">
						<label class="labelLeft"><span class="bold"> &nbsp; </span> </label>
						<input type="submit" value="Generate" class="btn btn-primary" style="width: 100%;">
					</div>
				</div>
			</form>
		</div>
	</div>

	<div class="row justify-content-end mt-2">
		<div class="col-sm" style="color: #0070c0;font-size: 22px;">
			<span style="float: right;"> Daily Results </span>
		</div>
	</div>	
</div>

<div id="vlau"></div>
	
@endsection