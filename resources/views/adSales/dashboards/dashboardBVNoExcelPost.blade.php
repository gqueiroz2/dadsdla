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

<script type="text/javascript">
	var screenW = screen.width;
	var screenH = screen.height;

	var widthChart = screenW/4;

	console.log("Your screen resolution is: " + screen.width + "x" + screen.height);

	

</script>

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
							<label class="labelLeft bold" > Agency Group </label>
							@if($errors->has('baseFilter'))
								<label style="color: red;">* Required</label>
							@endif
							{{$render->agencyGroupForm()}}
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
				<!-- Button trigger modal -->
				<div style="float: right;">
					<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
					  	Info. 2019
					</button>
				</div>

				<div style="float: right; margin-right: 5%;"> BV </div>
			</div>
		</div>	
	</div>

	<div class="container-fluid">
		<div class="row mt-2">
			<div class="col">
				<table class="table table-borderless table-outside-border">
					<tr>
						<td style="background-color: #002060;color:white; width: 50%;"> Agência </td>
						<td style="background-color: #d5dee4;width: 50%;"> {{$agencyGroupName}}  </td>
					</tr>
					<tr>
						<td class="dc"> Tabela </td>
						<td style="background-color: #d9e1f2;"> {{ $cYear }} </td>
					</tr>					
				</table>
			</div>

			<div class="col">
				<table class="table table-borderless table-outside-border">
					<tr>
						<td class="dc" style="width: 50%;"> ACTUALS </td>
						<td style="background-color: #d9e1f2;width: 50%;"> {{ number_format($bvAnalisis['currentVal']) }} </td>
					</tr>
					<tr>
						<td class="dc"> FX ATUAL </td>
						<td style="background-color: #d9e1f2;">
							@if($bvAnalisis['currentPercentage'] <= 0)
								-
							@else
								{{ number_format( ($bvAnalisis['currentPercentage'])*100 ) }}% 
							@endif
						</td>
					</tr>
					<tr>
						<td class="dc"> REM. ATUAL </td>
						<td style="background-color: #d9e1f2;"> {{ number_format($bvAnalisis['currentBV']) }} </td>
					</tr>					
				</table>
			</div>

			<div class="col">
				<table class="table table-borderless table-outside-border">
					<tr>
						<td class="dc"> DIF.PRÓX. </td>
						<td style="background-color: #d9e1f2;"> {{ number_format($bvAnalisis['nextBandDiff']) }} </td>
					</tr>
					<tr>
						<td class="dc"> PROX.FX </td>
						<td style="background-color: #d9e1f2;"> {{ number_format( ($bvAnalisis['nextBandPercentage']) ) }}% </td>
					</tr>
					<tr>
						<td class="dc" style="width: 50%;"> REM. PRÓX. FX </td>
						<td style="background-color: #d9e1f2;width: 50%;"> {{ number_format( ($bvAnalisis['nextBandBV']) ) }} </td>
					</tr>				
				</table>
			</div>

			<div class="col">
				<table class="table table-borderless table-outside-border">
					<tr>
						<td class="dc" style="width: 50%;"> DIF. TETO </td>
						<td style="background-color: #d9e1f2;width: 50%;"> {{ number_format( ($bvAnalisis['maxBandCurrentVal']) ) }} </td>
					</tr>
					<tr>
						<td class="dc"> TETO FX. </td>
						<td style="background-color: #d9e1f2;"> {{ number_format( ($bvAnalisis['maxBandPercentage']) ) }}% </td>
					</tr>
					<tr>
						<td class="dc"> REM. TETO </td>
						<td style="background-color: #d9e1f2;"> {{ number_format( ($bvAnalisis['maxBandBV']) ) }} </td>
					</tr>					
				</table>
			</div>
		</div>

		
					
		<div class="row">
			<div class="col-3" id="tableActualBandsDiv">
				<table class="table table-borderless table-outside-border" id="tableActualBands">
					<tr class="dc">
						<td colspan="3">
							<center> {{ $yearsBand[0] }} </center>
						</td>
					</tr>
					<tr class="dc">
						<td style="width: 40%;">De</td>
						<td style="width: 40%;">Até</td>
						<td style="width: 20%;">%</td>
					</tr>

					@if($bands[0])
						@for($i=0;$i< sizeof($bands[0]) ;$i++)
							<tr style="background-color: #d9e1f2;">
								<td>{{ number_format( $bands[0][$i]['fromValue'] ) }}</td>
								@if($bands[0][$i]['toValue'] == -1)
									<td> &nbsp; </td>
								@else
									<td>{{ number_format( $bands[0][$i]['toValue'] ) }}</td>
								@endif
								<td>{{ number_format( ($bands[0][$i]['percentage'])*100 ) }}%</td>
							</tr>
						@endfor
					@else
						<tr style="background-color: #d9e1f2;">
							<td colspan="3"> 
								<center>
									Não existe informação de faixas para este ano.
								</center>
							</td>
						</tr>
					@endif
				</table>
			</div>

			<div class="col-5" id="byBrandDiv">
				<div id="byBrand"></div>
			</div>
		</div>

		<div class="row mt-2">
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



	<!-- Modal -->
	<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog" role="document">
		    <div class="modal-content">
		      	<div class="modal-header">
		        	<h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
		        	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
		          		<span aria-hidden="true">&times;</span>
		        	</button>
		      	</div>
			    <div class="modal-body">
			    	<div class="container-fluid">
				    	<div class="row">
							<div class="col">
								<table class="table table-borderless table-outside-border">
									<tr class="dc">
										<td colspan="3">
											<center> {{ $yearsBand[1] }} </center>
										</td>
									</tr>
									<tr class="dc">
										<td style="width: 40%;">De</td>
										<td style="width: 40%;">Até</td>
										<td style="width: 20%;">%</td>
									</tr>
									@if($bands[1])
										@for($i=0;$i< sizeof($bands[1]) ;$i++)
											<tr style="background-color: #d9e1f2;">
												<td>{{ number_format( $bands[1][$i]['fromValue'] ) }}</td>
												@if($bands[1][$i]['toValue'] == -1)
													<td> &nbsp; </td>
												@else
													<td>{{ number_format( $bands[1][$i]['toValue'] ) }}</td>
												@endif
												<td>{{ number_format( ($bands[1][$i]['percentage'])*100 ) }}%</td>
											</tr>
										@endfor
									@else
										<tr style="background-color: #d9e1f2;">
											<td colspan="3"> 
												<center>
													Não existe informação de faixas para este ano.
												</center>
											</td>
										</tr>
									@endif
								</table>
							</div>
						</div>
					</div>
			    </div>
		      	<div class="modal-footer">
		        	<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
		        	<button type="button" class="btn btn-primary">Save changes</button>
		      	</div>
		    </div>
		</div>
	</div>


	<div id="vlau"></div>
	<div id="vlau1"></div>

	

	<script>
      	google.charts.load('current', {'packages':['corechart']});
      	google.charts.setOnLoadCallback(drawChart);

	    function drawChart() {
	    	var options = {
				legend:'none',
		        width: '100%',
		        height: '100%',
		        chartArea: {
		            left: "3%",
		            top: "3%",
		            height: "94%",
		            width: "94%"
		        },
				backgroundColor:'transparent',
				pieSliceText: 'none',
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
	        
	        divElement = document.querySelector("#tableActualBandsDiv"); 
   
            elemRect = divElement.getBoundingClientRect(); 
        
            elemHeight = elemRect.height; 
   
	        $('#byBrand').css('height', elemHeight+'px');
	        
	        chart.draw(data, options);

	   	}

    </script>

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

	
@endsection

				



    

    