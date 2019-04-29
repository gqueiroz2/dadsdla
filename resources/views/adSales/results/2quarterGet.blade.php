@extends('layouts.mirror')

@section('title', 'Quarter Results')

@section('head')	

@endsection

@section('content')

	<form class="form-inline" role="form" method="POST" action="resultsQuarterController">
		
		@csrf

		<div class="container-fluid">
			<div class="row">
				
				<!-- Region Area -->
				<div class="col-12 col-lg">
					<div class="form-inline">
						<label>Sales Region</label>
						<select id="salesRegion" name="salesRegion" style="width: 100%">
							<option value=""> Select </option>
							@for ($r = 0; $r < sizeof($salesRegion); $r++)
								<option value="{{ $salesRegion[$r]['id'] }}"> {{ $salesRegion[$r]['name'] }} </option>	
							@endfor
						</select>
					</div>
				</div>

				<!-- Region Area -->
				<div class="col-12 col-lg">
					<div class="form-inline">
						<label>Year</label>
						<select id="year" name="year" style="width: 100%">
							<option value=""> Select </option>
							@for ($y = 0; $y < sizeof($years); $y++)
								<option value="{{ $years[$y] }}"> {{ $years[$y] }} </option>
							@endfor
						</select>
					</div>
				</div>

				<!-- Brand Area -->
				<div class="col-12 col-lg">
					<div class="form-inline">
						<label>Brand</label>
						<select id="brand[]" name="brand" style="width: 100%" multiple="true">
							<option value="DN"> DN </option>
							@for ($b = 0; $b < sizeof($brands); $b++)
								<option value="{{ $brands[$b]['id'] }}"> {{ $brands[$b]['name'] }} </option>
							@endfor
						</select>
					</div>
				</div>				

				<!-- 1st Pos Area -->
				<div class="col-12 col-lg">
					<div class="form-inline">
						<label> 1st Pos </label>
						<select name="firstPos" id="firstPos" style="width: 100%;">
							
						</select>
					</div>
				</div>				

				<!-- 2st Pos Area -->
				<div class="col-12 col-lg">
					<div class="form-inline">
						<label> 2st Pos </label>
						<select name="secondPos" id="secondPos" style="width: 100%;">
							
						</select>
					</div>
				</div>				

				
				<div class="col-12 col-lg">
					<div class="form-inline">
						<label> Currency </label>
						<select name="currency" id="currency" style="width: 100%;">
							<option value=""> Select </option>
							@for ($c = 0; $c < sizeof($currencies); $c++)
								<option value="{{ $currencies[$c]['id'] }}"> {{ $currencies[$c]['name'] }} </option>
							@endfor
						</select>

						
					</div>
				</div>
			</div>
		</div>

	</form>

	<script type="text/javascript">
		ajaxSetup();
	</script>

@endsection