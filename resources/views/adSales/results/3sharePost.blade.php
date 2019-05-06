@extends('layouts.mirror')
@section('title', 'Monthly Results')
@section('head')	
	<script src="/js/resultsShare.js"></script>
@endsection

@section('content')
	<div class="container-fluid">
		<div class="row">
			<div class="col">
				<form method="POST" action="{{ route('resultsSharePost') }}">
					@csrf
					<div class="container-fluid">
						<div class="row">
						<div class="col col-2">
							<label class='labelLeft'>Region:</label>
							{{$render->region($region)}}
						</div>
						<div class="col col-2">
							<label class='labelLeft'>Year:</label>
							{{$render->year()}}
						</div>
						<div class="col col-2">
							<label class='labelLeft'>Brands:</label>
							{{$render->brand($brand)}}
						</div>
						<div class="col col-2">
							<label class='labelLeft'>Source:</label>
							{{$render->source()}}
						</div>
						<div class="col col-2">
							<label class='labelLeft'>Sales Rep Group:</label>
							{{$render->salesRepGroup($salesRepGroup)}}
						</div>
						<div class="col col-2">
							<label class='labelLeft'>Sales Rep:</label>
							{{$render->salesRep($salesRep)}}
						</div>
						<div class="col col-2">
							<label class='labelLeft'>Months:</label>
							{{$render->months()}}
						</div>
						<div class="col col-2">
							<label class='labelLeft'>Currency:</label>
							{{$render->currency($currency)}}
						</div>
						<div class="col col-2">
							<label class='labelLeft'>Value:</label>
							{{$render->value()}}
						</div>
						<div class="col col-2">
							<label class='labelLeft'> &nbsp; </label>
							<input style="width: 100%;" type="submit" value="Generate" class="btn btn-primary">		
						</div>
					</div>
					</div>
				</form>
			</div>
		</div>
		<div class="row">
			<div class="col">
				<div class="container-fluid">
					<div class="form-group">
						<div class="form-inline">
							<div class="row justify-content-center" style="margin-right: 0.5%; margin-left: 0.5%;">
								<div class="col col-4">
									<div id="chart_div"></div>
								</div>
								<div class="col col-8">
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
				'chartArea':{
					left:0,
					top:50,
					width:'100%',
					height:'100%'
				},
				'backgroundColor':'transparent',
				'legend':'none'

			};
			var chart = new google.visualization.PieChart(document.getElementById('chart_div'));
        	chart.draw(data, options);
		
		}
	</script>

@endsection