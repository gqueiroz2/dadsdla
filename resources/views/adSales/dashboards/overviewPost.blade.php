@extends('layouts.mirror')
@section('title', 'Dashboards Overview')
@section('head')	
	<script src="/js/dashboards-overview.js"></script>
    <?php include(resource_path('views/auth.php')); ?>
    <style type="text/css">
    	
    .graphInner{
        position: absolute;
        top: 0;
        left: 0;
        width:100%;
        height:100%;

    }

    </style>
@endsection

@section('content')
	<div class="container-fluid">
		<div class="row">
			<div class="col">
				<form method="POST" action="{{ route('overviewPost') }}" runat="server" onsubmit="ShowLoading()">
					@csrf
					<div class="row">
						<div class="col">
							<label class="labelLeft bold"> Region: </label>
							@if($errors->has('region'))
								<label style="color: red;">* Required</label>
							@endif
							@if($userLevel == 'L0' || $userLevel == 'SU')
								{{$render->region($salesRegion)}}
							@else
								{{$render->regionFiltered($region, $regionID)}}
							@endif
						</div>
						<div class="col">
							<label class="labelLeft bold"> Type: </label>
							@if($errors->has('type'))
								<label style="color: red;">* Required</label>
							@else
								{{$render->type()}}
							@endif
						</div>						
						<div class="col">
							<label class="labelLeft bold" > <span style="color: red;" id="labelBaseFilter"> Select Type </span> </label>
							@if($errors->has('baseFilter'))
								<label style="color: red;">* Required</label>
							@endif
							{{$render->baseFilter()}}
						</div>
						<div class="col">
							<label class="labelLeft bold" id="labelSecondaryFilter"> <span style="color: red;"> Select Type </span> </label>
							@if($errors->has('secondaryFilter'))
								<label style="color: red;">* Required</label>
							@endif
							{{$render->secondaryFilter()}}
							
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
				<div style="float: right;"> Overview </div>
			</div>
		</div>	
	</div>
	<div class="container-fluid">

		<?php 
			$flow = array("CYear","PYear","PPYear");
			$render->assembler($con,$handle,$type,$baseFilter,$secondaryFilter,$flow);
		?>

	</div>

	<div id="vlau"></div>

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
	          	<?php echo $monthChart; ?>
	        ]);
			var options = {
				hAxis: {
		          	title: 'Month'
		        },
		        vAxis: {
		          	title: 'Revenue'
		        },
		        series: {
		          	1: {curveType: 'function'}
		        },
				'width': '100%',
				'height': '100%',
				backgroundColor:'transparent',
				
				
				/*colors: ['#0070c0','#ff3300','#ffff00','#009933','#ff0000','#000000','#002060','#ff0000','#6600ff','#004b84','#808080','#88cc00'
				]*/
			};
			var chart = new google.visualization.LineChart(document.getElementById('overviewMonthChart'));
        	chart.draw(data, options);
		}
	</script>

	<script>
		google.charts.load('current', {
				callback: function () {
    				overviewChildChart();
    				$(window).resize(overviewChildChart);
  				},
				'packages':['corechart','bar']
		});
		google.charts.setOnLoadCallback(overviewChildChart);
		function overviewChildChart(){
			var data = google.visualization.arrayToDataTable([
		        [
		        	@for($c = 0 ; $c < sizeof($childChart["label"]);$c++)
		        		<?php 
		        			echo "'".$childChart["label"][$c]."',";
		        		?>
		        	@endfor
		         	{ role: 'annotation' } ],
		        [
		        	@for($c = 0 ; $c < sizeof($childChart["cYear"]);$c++)
		        		<?php 
		        			if($c == 0){
		        				echo "'".$childChart["cYear"][$c]."',";
		        			}else{
		        				echo "".$childChart["cYear"][$c].",";
		        			}
		        		?>
		        	@endfor
		        	<?php echo "' '"; ?>
		        	
		        ],
		        [
		        	@for($c = 0 ; $c < sizeof($childChart["pYear"]);$c++)
		        		<?php 
		        			if($c == 0){
		        				echo "'".$childChart["pYear"][$c]."',";
		        			}else{
		        				echo "".$childChart["pYear"][$c].",";
		        			}
		        		?>
		        	@endfor
		        	<?php echo "' '"; ?>
		        ],
		        [
		        	@for($c = 0 ; $c < sizeof($childChart["ppYear"]);$c++)
		        		<?php 
		        			if($c == 0){
		        				echo "'".$childChart["ppYear"][$c]."',";
		        			}else{
		        				echo "".$childChart["ppYear"][$c].",";
		        			}
		        		?>
		        	@endfor
		        	<?php echo "' '"; ?>
		        ]
		     ]);
			
			var options = {
				isStacked: 'percent',
				chart: {
		            title: 'Company Performance',
		            subtitle: 'Sales, Expenses, and Profit: 2014-2017',
		        },
				
				'width': '100%',
				'height': '100%',
				backgroundColor:'transparent',
				legend: { position: 'bottom' },
				bar: { groupWidth: '60%' },
        		hAxis: {
		            minValue: 0,
		            ticks: [0, .3, .6, .9, 1]
		        }
				/*colors: ['#0070c0','#ff3300','#ffff00','#009933','#ff0000','#000000','#002060','#ff0000','#6600ff','#004b84','#808080','#88cc00'
				]*/
			};
			var chart = new google.visualization.BarChart(document.getElementById('overviewChildChart'));
        	chart.draw(data, options);
		}
	</script>

	<script>
		google.charts.load('current', {
				callback: function () {
    				overviewBrandCharCYear();
    				$(window).resize(overviewBrandCharCYear);
  				},
				'packages':['corechart']
		});
		google.charts.setOnLoadCallback(overviewBrandCharCYear);
		function overviewBrandCharCYear(){
			var data = new google.visualization.DataTable();
			data.addColumn("string","Brand");
			data.addColumn("number","Value");
			data.addRows([
				@for($b = 0; $b < sizeof($brandChart[0]); $b++)
				    ['{{$brandChart[0][$b]["label"]}}',{{$brandChart[0][$b]["value"]}}],
				@endfor
			]);
			var options = {
				is3D: true,
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
					fontSize:'25','color':'black'
				},
				slices:{
					0: {textStyle: {color: 'black' },color: '#0070c0'  },
					1: {textStyle: {color: 'black' },color: '#ff3300'  },
					2: {textStyle: {color: 'black' },color: '#ffff00'  },
					3: {textStyle: {color: 'black' },color: '#009933'  },
					4: {textStyle: {color: 'black' },color: '#ff0000'  },
					5: {textStyle: {color: 'white' },color: '#000000'  },
					6: {textStyle: {color: 'white' },color: '#002060'  },
					7: {textStyle: {color: 'black' },color: '#ff0000'  },
					8: {textStyle: {color: 'black' },color: '#6600ff'  },
					9: {textStyle: {color: 'white' },color: '#004b84'  },
					10: {textStyle: {color: 'white' },color: '#808080'  },
					11: {textStyle: {color: 'black' },color: '#88cc00'  }
				}
			};
			var chart = new google.visualization.PieChart(document.getElementById('overviewBrandChartCYear'));
        	chart.draw(data, options);
		}
	</script>

	<script>
		google.charts.load('current', {
				callback: function () {
    				overviewBrandCharPYear();
    				$(window).resize(overviewBrandCharPYear);
  				},
				'packages':['corechart']
		});
		google.charts.setOnLoadCallback(overviewBrandCharPYear);
		function overviewBrandCharPYear(){
			var data = new google.visualization.DataTable();
			data.addColumn("string","Brand");
			data.addColumn("number","Value");
			data.addRows([
				@for($b = 0; $b < sizeof($brandChart[1]); $b++)
				    ['{{$brandChart[1][$b]["label"]}}',{{$brandChart[1][$b]["value"]}}],
				@endfor
			]);
			var options = {
				is3D: true,
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
					fontSize:'25','color':'black'
				},
				slices:{
					0: {textStyle: {color: 'black' },color: '#0070c0'  },
					1: {textStyle: {color: 'black' },color: '#ff3300'  },
					2: {textStyle: {color: 'black' },color: '#ffff00'  },
					3: {textStyle: {color: 'black' },color: '#009933'  },
					4: {textStyle: {color: 'black' },color: '#ff0000'  },
					5: {textStyle: {color: 'white' },color: '#000000'  },
					6: {textStyle: {color: 'white' },color: '#002060'  },
					7: {textStyle: {color: 'black' },color: '#ff0000'  },
					8: {textStyle: {color: 'black' },color: '#6600ff'  },
					9: {textStyle: {color: 'white' },color: '#004b84'  },
					10: {textStyle: {color: 'white' },color: '#808080'  },
					11: {textStyle: {color: 'black' },color: '#88cc00'  }
				}
				
			};
			var chart = new google.visualization.PieChart(document.getElementById('overviewBrandChartPYear'));
        	chart.draw(data, options);
		}
	</script>

	<script>
		google.charts.load('current', {
				callback: function () {
    				overviewBrandCharPPYear();
    				$(window).resize(overviewBrandCharPPYear);
  				},
				'packages':['corechart']
		});
		google.charts.setOnLoadCallback(overviewBrandCharPPYear);
		function overviewBrandCharPPYear(){
			var data = new google.visualization.DataTable();
			data.addColumn("string","Brand");
			data.addColumn("number","Value");
			data.addRows([
				@for($b = 0; $b < sizeof($brandChart[2]); $b++)
				    ['{{$brandChart[2][$b]["label"]}}',{{$brandChart[2][$b]["value"]}}],
				@endfor
			]);
			var options = {
				is3D: true,
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
					fontSize:'25','color':'black'
				},
				slices:{
					0: {textStyle: {color: 'black' },color: '#0070c0'  },
					1: {textStyle: {color: 'black' },color: '#ff3300'  },
					2: {textStyle: {color: 'black' },color: '#ffff00'  },
					3: {textStyle: {color: 'black' },color: '#009933'  },
					4: {textStyle: {color: 'black' },color: '#ff0000'  },
					5: {textStyle: {color: 'white' },color: '#000000'  },
					6: {textStyle: {color: 'white' },color: '#002060'  },
					7: {textStyle: {color: 'black' },color: '#ff0000'  },
					8: {textStyle: {color: 'black' },color: '#6600ff'  },
					9: {textStyle: {color: 'white' },color: '#004b84'  },
					10: {textStyle: {color: 'white' },color: '#808080'  },
					11: {textStyle: {color: 'black' },color: '#88cc00'  }
				}
			};
			var chart = new google.visualization.PieChart(document.getElementById('overviewBrandChartPPYear'));
        	chart.draw(data, options);
		}
	</script>

	<script>
		google.charts.load('current', {
				callback: function () {
    				overviewBrandChartColumn();
    				$(window).resize(overviewBrandChartColumn);
  				},
				'packages':['corechart']
		});
		google.charts.setOnLoadCallback(overviewBrandChartColumn);
		function overviewBrandChartColumn(){
			var data = google.visualization.arrayToDataTable([
	          	<?php echo $brandChartColumn; ?>
	        ]);		

			var options = {
		        vAxis: {
		          	viewWindow:{
		          		max:{{ $maxChartColumn }},
		          	}
		        },
				chartArea:{
					'width':'100%',
				},
				'width': '100%',
				'height': '100%',
				backgroundColor:'transparent',
				legend:'none',
				pieSliceText: 'label',
				pieSliceTextStyle: {
					fontSize:'25','color':'black'
				},
				colors: ['#0000cc', '#3366ff', '#99ccff', ]
			};
			var chart = new google.visualization.ColumnChart(document.getElementById('overviewBrandChartColumn'));
        	chart.draw(data, options);
		}
	</script>
	

@endsection



    

    

    