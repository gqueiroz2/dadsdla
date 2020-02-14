@extends('layouts.mirror')
@section('title', 'Executive')
@section('head')	
<script src="/js/performance.js"></script>
    <?php include(resource_path('views/auth.php')); 
    ?>
    <style>
	table{
		text-align: center;
	}
</style>
@endsection
@section('content')
	<div class="container-fluid">		
		<div class="row">
			<div class="col">
				<form method="POST" action="{{ route('individualPost') }}"  runat="server"  onsubmit="ShowLoading()">
					@csrf
					<div class="row justify-content-center">
						<div class="col-sm">	
							<label class='labelLeft'><span class="bold">Region:</span></label>
							@if($errors->has('region'))
								<label style="color: red;">* Required</label>
							@endif
							@if($userLevel == 'L0' || $userLevel == 'SU')
								{{$render->region($region)}}							
							@else
								{{$render->regionFiltered($region, $regionID, $special)}}
							@endif
						</div>
						<div class="col">
                            <label class="labelLeft"><span class="bold"> Year: </span></label>
                            @if($errors->has('year'))
                                <label style="color: red;">* Required</label>
                            @endif
                            {{$render->year($regionID)}}                    
                        </div>
						<div class="col-sm">
							<label class='labelLeft'><span class="bold">Tiers:</span></label>
							@if($errors->has('brand'))
								<label style="color: red;">* Required</label>
							@endif
							{{$render->tiers()}}
						</div>
						<div class="col-sm">
							<label class='labelLeft'><span class="bold">Brands:</span></label>
							@if($errors->has('brand'))
								<label style="color: red;">* Required</label>
							@endif
							{{$render->brand($brand)}}
						</div>
						<div class="col-sm">
							<label class='labelLeft'><span class="bold">Months:</span></label>
							@if($errors->has('month'))
								<label style="color: red;">* Required</label>
							@endif
							{{$render->months()}}
						</div>
					</div>
					<div class="row justify-content-center">
						<div class="col-sm">
							<label class='labelLeft'><span class="bold">Sales Rep Group:</span></label>
							@if($errors->has('salesRepGroup'))
								<label style="color: red;">* Required</label>
							@endif
							{{$render->salesRepGroup($salesRepGroup)}}
						</div>
						<div class="col-sm">
							<label class='labelLeft'><span class="bold">Sales Rep:</span></label>
							@if($errors->has('salesRep'))
								<label style="color: red;">* Required</label>
							@endif
							{{$render->salesRep()}}
						</div>
						<div class="col-sm">
							<label class='labelLeft'><span class="bold">Currency:</span></label>
							@if($errors->has('currency'))
								<label style="color: red;">* Required</label>
							@endif
							{{$render->currency($currency)}}
						</div>
						<div class="col-sm">
							<label class='labelLeft'><span class="bold">Value:</span></label>
							@if($errors->has('value'))
								<label style="color: red;">* Required</label>
							@endif
							{{$render->value()}}
						</div>
						<div class="col-sm">
							<label class='labelLeft'> &nbsp; </label>
							<input style="width: 100%;" type="submit" value="Generate" class="btn btn-primary">		
						</div>
					</div>
				</form>
				<div class="row justify-content-end mt-2">
					<div class="col-sm"></div>
					<div class="col-sm">
						<select id="ExcelPDF" class="form-control">
							<option value="Excel">Excel</option>
							<option value="PDF">PDF</option>
						</select>
					</div>
					@if($render->bonus($user))
						<div class="col-sm" style="color: #0070c0;font-size: 22px;">
							<div style="float: right;">
								Individual Performance
							</div>
						</div>
						<div class="col-sm">
							<button id="bonusExcel" type="button" class="btn btn-primary" style="width: 100%">
								Generate Bonus Excel
							</button>
						</div>
					@else
						<div class="col-sm"></div>	
						<div class="col-sm" style="color: #0070c0;font-size: 22px;">
							<div style="float: right;">
								Individual Performance
							</div>
						</div>
					@endif
					<div class="col-sm">
						<button id="excel" type="button" class="btn btn-primary" style="width: 100%">
							Generate Excel
						</button>
					</div>
				</div>
			</div>
		</div>