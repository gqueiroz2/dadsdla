@extends('layouts.mirror')
@section('title', 'Monthly Results')
@section('head')	
	<script src="/js/resultsShare.js"></script>
	<script src="/js/results.js"></script>
    <?php include(resource_path('views/auth.php')); ?>
@endsection

@section('content')

	<style type="text/css">
		table{
			width: 100%;
			float: right !important;
		}
		th, td{
			text-align: center;
			font-size: 12px;
			padding: 3px;
		}

	</style>


	<div class="container-fluid">
		<div class="row">
			<div class="col">
				<form method="POST" action="{{ route('resultsSharePost') }}">
					@csrf
					<div class="row justify-content-center">
						<div class="col">
							<label class='labelLeft'>Region:</label>
							@if($userLevel == 'L0' || $userLevel == 'SU')
								{{$render->region($region)}}							
							@else
								{{$render->regionFiltered($region, $regionID )}}
							@endif
						</div>
						<div class="col">
							<label class='labelLeft'>Year:</label>
							{{$render->year()}}
						</div>
						<div class="col">
							<label class='labelLeft'>Brands:</label>
							{{$render->brand($brand)}}
						</div>
						<div class="col">
							<label class='labelLeft'>Source:</label>
							{{$render->source()}}
						</div>
						<div class="col">
							<label class='labelLeft'>Sales Rep Group:</label>
							{{$render->salesRepGroup($salesRepGroup)}}
						</div>

					</div>
					<div class="row justify-content-center">
						<div class="col">
							<label class='labelLeft'>Sales Rep:</label>
							{{$render->salesRep($salesRep)}}
						</div>
						<div class="col">
							<label class='labelLeft'>Currency:</label>
							{{$render->currency($currency)}}
						</div>
						<div class="col">
							<label class='labelLeft'>Months:</label>
							{{$render->months()}}
						</div>
						<div class="col">
							<label class='labelLeft'>Value:</label>
							{{$render->value()}}
						</div>
						<div class="col">
							<label class='labelLeft'> &nbsp; </label>
							<input style="width: 100%;" type="submit" value="Generate" class="btn btn-primary">		
						</div>
					</div>
				</form>
				<div class="row justify-content-end">
					<div class="col col-3" style="text-align: center; margin-top: 2%; ">
						<h1 class="reportsTitle" >Share ({{$mtx["region"]}} / {{$mtx["year"]}})</h1>
					</div>
				</div>
				
				<!--<div class="row justify-content-end no-gutters">
					<div class="col-3" style="color: #0070c0;font-size: 25px">
						<form class="form-inline" method="POST" action="#">
							@csrf
							 <button class="btn btn-primary" style="width: 100%">Generate Excel</button>
						</form>
					</div>
				</div>-->

			</div>
		</div>
		<div class="row mt-2">
			<div class="col">
				<div class="container-fluid">
					<div class="form-group">
						<div class="form-inline">
							<div class="row" style="margin-right: 0.5%; margin-left: 0.5%; width: 100%;">
								<div class="col col-3" style="zoom:125%; display: block; margin-top: 8%;">
									<div id="chart_div"></div>
								</div>
								<div class="col col-9" style=" width: 100%; margin-top: 5%;">
									{{$render->mtx($mtx)}}
								</div>
							</div>	
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	
	<script type="text/javascript">
		google.charts.load('current', {'packages':['corechart']});

		google.charts.setOnLoadCallback(drawChart);

		function drawChart(){

			var data = new google.visualization.DataTable();

			data.addColumn("string","brand");
			data.addColumn("number","value");
			data.addRows([
				@for($i = 0; $i<sizeof($mtx["brand"]); $i++)
					@if($i == (sizeof($mtx["brand"])-1))
				    	['{{$mtx["brand"][$i]}}',{{$mtx["total"][$i]}}]
				    @else
				    	['{{$mtx["brand"][$i]}}',{{$mtx["total"][$i]}}],
				    @endif
				@endfor
			]);

			var options = {
				chartArea:{
					'width':'100%',
					'height':'100%'
				},
				'width': '100%',
				'height': '100%',
				backgroundColor:'transparent',
				legend:'none',
				pieSliceText: 'label',
				pieSliceTextStyle: {
					fontSize:'13'
				},
				slices:{
					@for($b = 0; $b<sizeof($mtx["brandColor"]); $b++)
						@if ($b == sizeof($mtx["brandColor"]) -1 ) 
							{{$b}}: {textStyle: {color: '{{$mtx["brandTextColor"][$b]}}' },color: '{{$mtx["brandColor"][$b]}}'  }
						@else
							{{$b}}: {textStyle: {color: '{{$mtx["brandTextColor"][$b]}}' },color: '{{$mtx["brandColor"][$b]}}'  },
						@endif
					@endfor
				}

			};
			var chart = new google.visualization.PieChart(document.getElementById('chart_div'));
        	chart.draw(data, options);
		
		}
	</script>

@endsection