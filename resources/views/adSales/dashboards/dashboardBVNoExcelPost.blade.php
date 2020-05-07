@extends('layouts.mirror')
@section('title', 'Dashboards Overview')
@section('head')	
	<script src="/js/dashboards-bv.js"></script>
    <?php include(resource_path('views/auth.php')); ?>
    <style>
    	.table-outside-border{
    		border: 1px solid black;
    	}
    </style>
@endsection

@section('content')
	<div class="container-fluid">
		<div class="row">
			<div class="col">
				<form method="POST" action="{{ route('dashboardBVPost') }}" runat="server" onsubmit="ShowLoading()">
					@csrf
					<div class="row">
						<div class="col">
							<label class="labelLeft bold"> Region: </label>
							@if($errors->has('region'))
								<label style="color: red;">* Required</label>
							@endif
							{{$render->region($salesRegion)}}							
						</div>
						<div class="col">
							<label class="labelLeft bold"> Type: </label>
							@if($errors->has('type'))
								<label style="color: red;">* Required</label>
							@endif
							{{$render->type()}}
						</div>
						<div class="col">
							<label class="labelLeft bold" > <span style="color: red;" id="labelBaseFilter"> Select Type </span> </label>
							@if($errors->has('baseFilter'))
								<label style="color: red;">* Required</label>
							@endif
							{{$render->baseFilter()}}
						</div>						
						<div class="col">
							<label class="labelLeft bold"> Currency: </label>
							@if($errors->has('currency'))
								<label style="color: red;">* Required</label>
							@endif
							{{$render->currency()}}
						</div>
						<div class="col">
							<label class="labelLeft bold"> Value: </label>
							@if($errors->has('value'))
								<label style="color: red;">* Required</label>
							@endif
							{{$render->value2()}}
						</div>
						<div class="col">
							<label class="labelLeft"><span class="bold"> &nbsp; </span> </label>
							<input type="submit" value="Generate" class="btn btn-primary" style="width: 100%;">
						</div>
					</div>
				</form>
			</div>
		</div>

		<div class="row justify-content-end mt-2">
			<div class="col-sm" style="color: #0070c0;font-size: 22px;">
				<div style="float: right;"> BV - No Excel </div>
			</div>
		</div>	
	</div>

	<div class="container-fluid">
		<div class="row mt-2">
			<div class="col">
				<table class="table table-borderless table-outside-border">
					<tr>
						<td style="background-color: #002060;color:white; width: 50%;"> {{$subT}} </td>
						<td style="background-color: #d5dee4;width: 50%;"> {{ $choice }} </td>
					</tr>
					<tr>
						<td class="dc"> Tabela </td>
						<td style="background-color: #d9e1f2;"> {{ $cYear }} </td>
					</tr>
					<tr>
						<td colspan="2" style="background-color: #d9e1f2;"><center> Apuração - {{ $base->formatData("aaaa-mm-dd","dd/mm/aaaa", $base->source('CMAPS')) }} </center></td>
					</tr>					
				</table>
			</div>

			<div class="col">
				<table class="table table-borderless table-outside-border">
					<tr>
						<td class="dc" style="width: 50%;"> ACTUALS </td>
						<td style="background-color: #d9e1f2;width: 50%;"> VALUE </td>
					</tr>
					<tr>
						<td class="dc"> FX ATUAL </td>
						<td style="background-color: #d9e1f2;"> POR </td>
					</tr>
					<tr>
						<td class="dc"> REM. ATUAL </td>
						<td style="background-color: #d9e1f2;"> VALUE </td>
					</tr>					
				</table>
			</div>

			<div class="col">
				<table class="table table-borderless table-outside-border">
					<tr>
						<td class="dc" style="width: 50%;"> PRÓX. FX </td>
						<td style="background-color: #d9e1f2;width: 50%;"> VALUE </td>
					</tr>
					<tr>
						<td class="dc"> DIF.PRÓX. </td>
						<td style="background-color: #d9e1f2;"> POR </td>
					</tr>
					<tr>
						<td class="dc"> PROX.FX </td>
						<td style="background-color: #d9e1f2;"> VALUE </td>
					</tr>					
				</table>
			</div>

			<div class="col">
				<table class="table table-borderless table-outside-border">
					<tr>
						<td class="dc" style="width: 50%;"> PRÓX. FX </td>
						<td style="background-color: #d9e1f2;width: 50%;"> VALUE </td>
					</tr>
					<tr>
						<td class="dc"> DIF.PRÓX. </td>
						<td style="background-color: #d9e1f2;"> POR </td>
					</tr>
					<tr>
						<td class="dc"> PROX.FX </td>
						<td style="background-color: #d9e1f2;"> VALUE </td>
					</tr>					
				</table>
			</div>
		</div>

		<div class="row">
			<div class="col-3">
				<table class="table table-borderless table-outside-border">
					<tr class="dc">
						<td colspan="3">
							<center> {{ $cYear }} </center>
						</td>
					</tr>
					<tr class="dc">
						<td style="width: 40%;">De</td>
						<td style="width: 40%;">Até</td>
						<td style="width: 20%;">%</td>
					</tr>

					@for($i=0;$i<10;$i++)
						<tr style="background-color: #d9e1f2;">
							<td>XXX,XXX,XXX</td>
							<td>XXX,XXX,XXX</td>
							<td>XXX,XXX,XXX</td>
						</tr>
					@endfor
					
					<tr class="dc">
						<td colspan="3"> Real Net Year </td>
					</tr>
				</table>
			</div>

			<div class="col-9">
				<div class="container-fluid">
					<div class="row">
						<div class="col-8" style="border: 1px solid black;">
							<div class="container-fluid">
								<div class="row">
									<div class="col">
										<div id="childGraph"></div>
									</div>
								</div>
								<div class="row">
									<div class="col">
										<div id="byMonthGraph"></div>
									</div>
								</div>								
							</div>
						</div>
						<div class="col-4" style="border: 1px solid black;">
							<div class="container-fluid">
								<div class="row">
									<div class="col">
										<div id="byBrand"></div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div id="vlau"></div>
	<div id="vlau1"></div>

	<script>
		google.charts.load('current', {
				callback: function () {
    				overviewMonthChart();
    				$(window).resize(overviewMonthChart);
  				},
				'packages':['corechart']
		});
		google.charts.setOnLoadCallback(overviewMonthChart);
		function overviewMonthChart(){
			var data = google.visualization.arrayToDataTable([
	          	<?php echo $graph['byMonth']; ?>
	        ]);
			var options = {
		        series: {
		          	1: {curveType: 'function'}
		        },
				legend:'none',
				'width': '100%',
				'height': '100%',
				backgroundColor:'transparent',
				
			};
			var chart = new google.visualization.LineChart(document.getElementById('byMonthGraph'));
        	chart.draw(data, options);
		}
	</script>

	<script>
		google.charts.load('current', {packages: ['corechart', 'bar']});
		google.charts.setOnLoadCallback(drawTitleSubtitle);

		function drawTitleSubtitle() {
		      var data = google.visualization.arrayToDataTable(<?php echo $graph['child']; ?>);

		      var options = {		        
		        legend:'none',
				'width': '100%',
				'height': '100%',
				backgroundColor:'transparent',        
		        bars: 'horizontal'
		      };
		      var materialChart = new google.charts.Bar(document.getElementById('childGraph'));
		      materialChart.draw(data, options);
		    }
	</script>

	<script>
      	google.charts.load('current', {'packages':['corechart']});
      	google.charts.setOnLoadCallback(drawChart);

	    function drawChart() {
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
					fontSize:'25'
				},
				slices:{
					@for($b = 0; $b<sizeof($graph['byBrand']['brandColor']); $b++)
						@if ($b == sizeof($graph['byBrand']['brandColor']) -1 ) 
							{{$b}}: {textStyle: {color: '{{$graph['byBrand']["brandTextColor"][$b]}}' },color: '{{$graph['byBrand']['brandColor'][$b]}}'  }
						@else
							{{$b}}: {textStyle: {color: '{{$graph['byBrand']["brandTextColor"][$b]}}' },color: '{{$graph['byBrand']['brandColor'][$b]}}'  },
						@endif
					@endfor
				}

			}; 
	    	var data = google.visualization.arrayToDataTable(<?php echo $graph['byBrand']['graph']; ?>);
	        var chart = new google.visualization.PieChart(document.getElementById('byBrand'));
	        chart.draw(data, options);
	   	}

    </script>
@endsection

				{{--
				slices:{
					@for($b = 0; $b<sizeof($mtx['brandColor']); $b++)
						@if ($b == sizeof($mtx['brandColor']) -1 ) 
							{{$b}}: {textStyle: {color: '{{$mtx["brandTextColor"][$b]}}' },color: '{{$mtx['brandColor'][$b]}}'  }
						@else
							{{$b}}: {textStyle: {color: '{{$mtx["brandTextColor"][$b]}}' },color: '{{$mtx['brandColor'][$b]}}'  },
						@endif
					@endfor
				}
				--}}



    

    