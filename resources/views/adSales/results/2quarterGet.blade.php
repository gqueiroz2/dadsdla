@extends('layouts.mirror')
@section('title', 'Quarter Results')
@section('head')	
	<script src="/js/results.js"></script>
    <?php include(resource_path('views/auth.php')); ?>
@endsection
@section('content')

	<div class="container-fluid">
		<div class="row">
			<div class="col">
				<form method="POST" action="{{ route('resultsQuarterPost') }}" runat="server"  onsubmit="ShowLoading()">
					@csrf
					<div class="row">
						<div class="col-sm">
							<label class="labelLeft"><span class="bold"> Region: </span></label>
							@if($errors->has('region'))
								<label style="color: red;">* Required</label>
							@endif
							@if($userLevel == 'L0' || $userLevel == 'SU')
								{{$qRender->region($salesRegion)}}							
							@else
								{{$qRender->regionFiltered($salesRegion, $regionID, $special)}}
							@endif
						</div>

						<div class="col-sm">
							<label class="labelLeft"><span class="bold"> Year: </span></label>
							@if($errors->has('year'))
								<label style="color: red;">* Required</label>
							@endif
							{{$qRender->year($regionID)}}					
						</div>	

						<div class="col-sm">
							<label class="labelLeft"><span class="bold"> Brand: </span></label>
							@if($errors->has('brand'))
								<label style="color: red;">* Required</label>
							@endif
							{{$qRender->brand($brands)}}
						</div>	

						<div class="col-sm">
							<label class="labelLeft"><span class="bold"> 1st Pos </span></label>
							@if($errors->has('secondPos'))
								<label style="color: red;">* Required</label>
							@endif
							{{$qRender->position("second")}}
						</div>				

						<div class="col-sm">
							<label class="labelLeft"><span class="bold"> 2st Pos </span></label>
							@if($errors->has('thirdPos'))
								<label style="color: red;">* Required</label>
							@endif
							{{$qRender->position("third")}}
						</div>				

						<div class="col-sm">
							<label class="labelLeft"><span class="bold"> Currency: </span></label>
							@if($errors->has('currency'))
								<label style="color: red;">* Required</label>
							@endif
							{{$qRender->currency($currency)}}
						</div>

						<div class="col-sm">
							<label class="labelLeft"><span class="bold"> Value: </span></label>
							@if($errors->has('value'))
								<label style="color: red;">* Required</label>
							@endif
							{{$qRender->value()}}
						</div>

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
				<span style="float: right;"> Quarter </span>
			</div>
		</div>	
	
	</div>



	<script type="text/javascript">
		ajaxSetup();
	</script>

@endsection