@extends('layouts.mirror')
@section('title', 'YoY Results')
@section('head')	
	<script src="/js/resultsYoY.js"></script>
    <?php include(resource_path('views/auth.php')); ?>
@endsection
@section('content')

	<div class="container-fluid">
		<div class="row">
			<div class="col">
				<form class="form-inline" role="form" method="POST" action="{{ route('resultsYoYGet') }}">
				@csrf
					<div class="col-12 col-lg">
						<div class="form-inline">
							<label>Sales Region</label>
							@if($userLevel == 'L0' || $userLevel == 'SU')
								{{$render->region($salesRegion)}}							
							@else
								{{$render->regionFiltered($salesRegion, $regionID )}}
							@endif
						</div>
					</div>

					<div class="col-12 col-lg">
						<div class="form-inline">
							<label>Year</label>
							{{ $render->year() }}
						</div>
					</div>

					<div class="col-12 col-lg">
						<div class="form-inline">
							<label>Brand</label>
							{{ $render->brand($brandsValue) }}
						</div>
					</div>	

					<div class="col-12 col-lg">
						<div class="form-inline">
							<label> 1st Pos </label>
							{{$render->position("first")}}
						</div>
					</div>	

					<div class="col-12 col-lg">
						<div class="form-inline">
							<label> 2st Pos </label>
							{{$render->position("second")}}
						</div>
					</div>	

					<div class="col-12 col-lg">
						<div class="form-inline">
							<label> 3rd Pos </label>
							{{$render->position("third")}}
						</div>
					</div>	

					<div class="col-12 col-lg">
						<div class="form-inline">
							<label> Currency </label>
							{{$render->currency()}}
						</div>
					</div>	

					<div class="col-12 col-lg">
						<div class="form-inline">
							<label> Value </label>
							{{ $render->value() }}
						</div>
					</div>

					<div class="col-12 col-lg">
						<div class="form-inline">
							<label> &nbsp; </label>
							<input type="submit" value="Generate" class="btn btn-primary" style="width: 100%">
						</div>
					</div>
				</form>
			</div>
		</div>
		
		<br>
		
		<div class="row no-gutters">
			<div class="col-9"></div>
			<div class="col-3" style="color: #0070c0;font-size: 25px">
				Year Over Year ({{$form}}) {{$year}}
			</div>
		</div>

		<br>

		<!--<div class="row no-gutters">
			<div class="col-9"></div>
			<div class="col-3" style="color: #0070c0;font-size: 25px">
				<form class="form-inline" method="POST" action="#">
					@csrf
					 <button class="btn btn-primary" style="width: 100%">Generate Excel</button>
				</form>
			</div>
		</div>-->

	</div>

	<div class="container-fluid" style="margin-right: 0.5%; margin-left: 0.5%; font-size: 12px">
		<div class="row mt-2">
			<div class="col">				
				<table style="width: 100%">
					<tr>
						<th class="lightBlue center" colspan="15">
							<span style="font-size:18px;"> 
								Year Over Year :({{$form}}) {{$year}} ({{strtoupper($value)}}
								/{{strtoupper($pRate[0]['name'])}}) {{ $region[0]['name'] }}
							</span>
						</th>
					</tr>

					<tr><td>&nbsp;</td></tr>

					@for($i = 0; $i < $size; $i++)
						{{var_dump($size)}}
						<tr>
							{{$renderYoY->brandTable($brandsValueArray[$i], $brandsValueArray[$i])}}
						</tr> 

                        <tr>
                            {{$renderYoY->renderData($matrix[$i][0],1,"grey","darkBlue")}}
                        </tr>
                    
                        <tr>
                           	{{$renderYoY->renderData($matrix[$i][1],2,"lightb","othersc","smBlue")}}
                        </tr>
                        <tr>
                            {{$renderYoY->renderData($matrix[$i][2],3,"rcBlue","othersc","smBlue")}}
                        </tr>
                        <tr>
                            {{$renderYoY->renderData($matrix[$i][3],4,"rcBlue","smBlue")}}
                        </tr>
                        <tr>
                            {{$renderYoY->renderData($matrix[$i][4],5,"medBlue","smBlue")}}
                        </tr>
                        <tr>
                            {{$renderYoY->renderData($matrix[$i][5],6,"medBlue","darkBlue")}}
                        </tr>
                   
                        <tr><td>&nbsp;</td></tr>

					@endfor
				</table>
			</div>
		</div>
	</div>

	<div id="vlau"></div>

@endsection