@extends('layouts.mirror')

@section('title', '@')

@section('head')
    <script src="/js/viewer.js"></script>
    <?php include(resource_path('views/auth.php')); 
    	var_dump("post");
     ?>
@endsection

@section('content')

	<div class="container-fluid">
		<div class="row">
			<div class="col">
				<form method="POST" action="{{ route('basePost') }}" runat="server"  onsubmit="ShowLoading()">
					@csrf
					<div class="col">
						<label class="labelLeft">Region: </label>
							@if($errors->has('region'))
								<label style="color:red;">*Required</label>
							@endif

							@if($userLevel == 'L0' || $userLevel == 'SU')
								{{$render->region($region)}}
							@else
								{{$render->regionFiltered($region,$regionID,$special)}}
							@endif
					</div>

					<div class="col">
						<label class="labelLeft">Source: </label>
							@if($errors->has('brand'))
								<label style="color:red;">*Required</label>
							@endif
							{{$render->sourceDataBase()}}
					</div>

					<div>
						<label class="labelLeft">PI: </label>
						@if($errors->has('brand'))
								<label style="color:red;">*Required</label>
						@endif
						{{$render->piNumber()}}
					</div>

					<div>
						<label class="labelLeft">Year: </label>
							@if($errors->has('year'))
								<label style="color:red;">*Required</label>
							@endif
							{{$render->year($regionID)}}
					</div>

					<div class="col">
						<label class="labelLeft">Months: </label>
							@if($errors->has('month'))
								<label style="color:red;">*Required</label>
							@endif
							{{$render->months()}}
					</div>

					<div class="col">
						<label class="label">Brand: </label>
							@if($errors->has('brand'))
								<label style="color:red;">*Required</label>
							@endif
							{{$render->brand($brands)}}
					</div>

					<div class="col">
						<label class="labelLeft">Sales Rep:</label>
							@if($errors->has('salesRep'))
								<label style="color:red;">*Required</label>
							@endif
							{{$render->salesRep()}}
					</div>

					<div class="col">
						<label class="labelLeft">Currency: </label>
							@if($errors->has('currency'))
								<label style="color:red;">*Required</label>
							@endif
							{{$render->currency()}}
					</div>

					<div class="col">
						<label class="labelLeft">Value: </label>
							@if($errors->has('value'))
								<label style="color:red;">*Required</label>
							@endif
							{{$render->value()}}
					</div>

				</form>
			</div>
		</div>
	</div>

	<div class="row justify-content-end mt-2">
		<div class="col" style="color: #0070c0; font-size:22px">
			<span style="float: right; margin-right: 2.5%;">Data Current Through: DD-MM-YY (<?php echo date('d \/ m \/ y'); ?>)</span>
		</div>

	</div>

@endsection