@extends('layouts.mirror')

@section('title', 'YoY Results')

@section('head')	

@endsection

@section('content')

	<form class="form-inline" role="form" method="POST" action="{{ route('ResultsYoYPost') }}">
		
		@csrf

		<div class="container-fluid">
			<div class="row">
				
				<!-- Region Area -->
				<div class="col-12 col-lg">
					<div class="form-inline">
						<label>Sales Region</label>
						<select id="salesRegion" name="salesRegion" style="width: 100%">
							<option value=""> Select </option>
							@for ($i = 0; $i < sizeof($salesRegion); $i++)
								<option value="{{ $salesRegion[$i]['id'] }}"> {{ $salesRegion[$i]['name'] }} </option>	
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
							@for ($i = 0; $i < sizeof($years); $i++)
								<option value="{{ $years[$i] }}"> {{ $years[$i] }} </option>
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
							@for ($i = 0; $i < sizeof($brands); $i++)
								<option value="{{ $brands[$i]['id'] }}"> {{ $brands[$i]['name'] }} </option>
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
							@for ($i = 0; $i < 10; $i++)
								<option value="{{ $plans[$i]['id'] }}"> {{ $plans[$i]['name'] }} </option>
							@endfor
						</select>
					</div>
				</div>				

				<!-- 3st Pos Area -->
				<div class="col-12 col-lg">
					<div class="form-inline">
						<label> 2st Pos </label>
						<select name="secondPos" id="secondPos" style="width: 100%;">
							
						</select>
					</div>
				</div>				

			</div>
		</div>
	</form>

@endsection