@extends('layouts.mirror')
@section('title', 'Share Results')
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
			/*font-size: 12px;*/
			padding: 3px;
		}

	</style>


	<div class="container-fluid">
		<div class="row">
			<div class="col">
				<form method="POST" action="{{ route('resultsSharePost') }}" runat="server"  onsubmit="ShowLoading()">
					@csrf
					<div class="row justify-content-center">
						<div class="col-sm">
							<label class='labelLeft'>Region:</label>
							@if($userLevel == 'L0' || $userLevel == 'SU')
								{{$render->region($region)}}							
							@else
								{{$render->regionFiltered($region, $regionID, $special)}}
							@endif
						</div>
						<div class="col-sm">
							<label class='labelLeft'>Year:</label>
							{{$render->year()}}
						</div>
						<div class="col-sm">
							<label class='labelLeft'>Months:</label>
							{{$render->months()}}
						</div>
						<div class="col-sm">
							<label class='labelLeft'>Brands:</label>
							{{$render->brand($brand)}}
						</div>
						<div class="col-sm">
							<label class='labelLeft'>Source:</label>
							{{$render->source()}}
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
							<label class='labelLeft'>Sales Rep:</label>
							{{$render->salesRep($salesRep)}}
						</div>
						<div class="col-sm">
							<label class='labelLeft'>Currency:</label>
							{{$render->currency($currency)}}
						</div>
						<div class="col-sm">
							<label class='labelLeft'>Value:</label>
							{{$render->value()}}
						</div>
						<div class="col-sm">
							<label class='labelLeft'> &nbsp; </label>
							<input style="width: 100%;" type="submit" value="Generate" class="btn btn-primary">		
						</div>
					</div>
				</form>
				<div class="row justify-content-end">

					<div class="col-sm" style="color: #0070c0;font-size: 22px;">
						<span style="float: right;"> {{$rName}} - Share : {{$mtx["source"]}} - {{$mtx["year"]}} </span>
					</div>
				</div>
			</div>
		</div>
		<div class="row mt-2">
			<div class="col">
				<div class="container-fluid">
					<div class="form-group">
						<div class="form-inline">
							<div class="row" style="margin-right: 0.5%; margin-left: 0.5%; width: 100%;">
								<div class="col-sm-3" id="div1" style="zoom:125%; display: block; margin-top: 8%;">
									<div id="chart_div" style="display: block; position: absolute; top: -25%; left: 0; width: 100%; height: 150%;"></div>
									<div id="chart_div2" style="display: none; position: absolute; top: -25%; left: 0; width: 100%; height: 150%;"></div>
								</div>
								<div class="col-sm-9" id="div2" style=" width: 100%; margin-top: 5%;">
									{{$render->mtx($mtx)}}
								</div>
							</div>	
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<?php


	?>
	
	<script type="text/javascript">

		google.charts.load('current', {'packages':['corechart']});

		google.charts.setOnLoadCallback(drawChart1);
		google.charts.setOnLoadCallback(drawChart2);

		function drawChart1(){

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
					fontSize:'25'
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

		function drawChart2(){

			var data = new google.visualization.DataTable();

			data.addColumn("string","brand");
			data.addColumn("number","value");
			data.addColumn({'type': 'string', 'role': 'tooltip', 'p': {'html': true}});
			data.addRows([
				@for($i = 0; $i<sizeof($mtx["salesRep"]); $i++)
					@if($i == (sizeof($mtx["salesRep"])-1))
				    	['{{$mtx["salesRepAB"][$i]}}',{{$mtx["dn"][$i]}},createCustomHTML('{{$mtx["salesRep"][$i]}}','{{number_format($mtx["dn"][$i],3,",",".")}}','{{number_format($mtx["share"][$i],1,".",",")}}')]
				    @else
				    	['{{$mtx["salesRepAB"][$i]}}',{{$mtx["dn"][$i]}},createCustomHTML('{{$mtx["salesRep"][$i]}}','{{number_format($mtx["dn"][$i],3,",",".")}}','{{number_format($mtx["share"][$i],1,".",",")}}')],
				    @endif
				@endfor
			]);

			var options = {
				chart: {
		            title: 'Nearby galaxies',
		            subtitle: 'distance on the left, brightness on the right'
		          },
				chartArea:{
					'width':'100%',
					'height':'100%'
				},
				'width': '100%',
				focusTarget: 'category',
				tooltip: { isHtml: true },
				'height': '100%',
				backgroundColor:'transparent',
				legend:'none',
				pieSliceText: 'label',
				pieSliceTextStyle: {
					fontSize:'18'
				}
				
				
			};
			var chart = new google.visualization.PieChart(document.getElementById('chart_div2'));
        	chart.draw(data, options);
		

		}

		function createCustomHTML(name,total,share){
			return "<div style='margin-left:5px;margin-right:5px;'>"+name+"</div>"+
			"<div style='font-weight:bold; white-space:nowrap;margin-left:5px;margin-right:5px;margin-bottom:5px;'>"+total+" ("+share+"%)</div>";
		}

	    $(window).resize(function(){

	    	google.charts.setOnLoadCallback(drawChart1);
			google.charts.setOnLoadCallback(drawChart2);	

			$("#table-share").css("width","100%");

		});

		$("#chart_div").click(function(){

			$(this).css("display","none");
			$("#chart_div2").css("display","block");
			google.charts.setOnLoadCallback(drawChart2);

		});

		$("#chart_div2").click(function(){
			$(this).css("display","none");
			$("#chart_div").css("display","block");
	    	google.charts.setOnLoadCallback(drawChart1);

		});

	</script>

@endsection
