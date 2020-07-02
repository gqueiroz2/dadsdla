@extends('layouts.mirror')
@section('title', 'Dashboards Overview')
@section('head')	
	<script src="/js/dashboards-bv.js"></script>
    <?php include(resource_path('views/auth.php')); ?>
    <style>
    	.table-outside-border{
    		border: 1px solid black;
    	}

    	.dot {
			height: 25px;
			width: 25px;
			background-color: #bbb;
			border-radius: 50%;
			display: inline-block;
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
							{{$render->valueNet()}}
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

				<div style="float: right; margin-right: 5%;"> BV - ({{$currencyShow}}/{{$valueShow}})  </div>
			</div>
		</div>	
	</div>

	<div class="container-fluid">
		<div class="row mt-2">
			<div class="col">
				<table class="table table-borderless table-outside-border">
					<tr>
						<td style="background-color: #002060;color:white; width: 50%;"> AGÊNCIA </td>
						<td style="background-color: #d5dee4;width: 50%;"> {{ strtoupper($agencyGroupName) }}  </td>
					</tr>
					<tr>
						<td class="dc"> TABELA </td>
						<td style="background-color: #d9e1f2;"> {{ $cYear }} </td>
					</tr>					
				</table>
			</div>

			<div class="col">
				<table class="table table-borderless table-outside-border">
					<tr>
						<td class="dc" style="width: 50%;"> INVESTIMENTO </td>
						<td style="background-color: #d9e1f2;width: 50%;"> {{ number_format($bvAnalisis['currentVal']) }} </td>
					</tr>
					<tr>
						<td class="dc"> FAIXA ATUAL </td>
						<td style="background-color: #d9e1f2;">
							@if($bvAnalisis['currentPercentage'] <= 0)
								-
							@else
								{{ number_format( ($bvAnalisis['currentPercentage'])*100 ) }}% 
							@endif
						</td>
					</tr>
					<tr>
						<td class="dc"> REMUNERAÇÃO ATUAL </td>
						<td style="background-color: #d9e1f2;"> 
							@if($bvAnalisis['currentBV'])
								{{ number_format($bvAnalisis['currentBV']) }} 
							@else
								-
							@endif
						</td>
					</tr>					
				</table>
			</div>

			<div class="col">
				<table class="table table-borderless table-outside-border">
					<tr>
						<td class="dc"> DIF. PRÓXIMA FAIXA </td>
						<td style="background-color: #d9e1f2;"> 
							@if($bvAnalisis['nextBandDiff'])
								{{ number_format($bvAnalisis['nextBandDiff']) }} 
							@else
								-
							@endif							
						</td>
					</tr>
					<tr>
						<td class="dc"> PRÓXIMA FAIXA </td>
						<td style="background-color: #d9e1f2;"> 
							@if($bvAnalisis['nextBandPercentage'])
								{{ number_format( ($bvAnalisis['nextBandPercentage']) ) }}% 
							@else
								-
							@endif							
						</td>
					</tr>
					<tr>
						<td class="dc" style="width: 50%;"> REM. PRÓXIMA FAIXA </td>
						<td style="background-color: #d9e1f2;width: 50%;"> 
							@if($bvAnalisis['nextBandBV'])
								{{ number_format( ($bvAnalisis['nextBandBV']) ) }} 
							@else
								-
							@endif							
						</td>
					</tr>				
				</table>
			</div>

			<div class="col">
				<table class="table table-borderless table-outside-border">
					<tr>
						<td class="dc" style="width: 50%;"> DIFERENÇA TETO </td>
						<td style="background-color: #d9e1f2;width: 50%;"> 
							@if($bvAnalisis['maxBandCurrentVal'])
								{{ number_format( ($bvAnalisis['maxBandCurrentVal']) ) }}
							@else
								-
							@endif 
						</td>
					</tr>
					<tr>
						<td class="dc"> TETO FAIXA </td>
						<td style="background-color: #d9e1f2;"> 
							@if($bvAnalisis['maxBandPercentage'])
								{{ number_format( ($bvAnalisis['maxBandPercentage']) ) }}% 
							@else
								-
							@endif	
						</td>
					</tr>
					<tr>
						<td class="dc"> REMUNERAÇÃO TETO </td>
						<td style="background-color: #d9e1f2;"> 
							@if($bvAnalisis['maxBandBV'])
								{{ number_format( ($bvAnalisis['maxBandBV']) ) }} 
							@else
								-
							@endif
						</td>
					</tr>					
				</table>
			</div>
		</div>
					
		<div class="row">
			<div class="col-3" id="tableActualBandsDiv">
				<table class="table table-borderless table-outside-border" id="tableActualBands">
					<tr class="dc">
						<td colspan="3">
							<center> TABELA {{ $yearsBand[0] }} </center>
						</td>
					</tr>
					<tr class="dc">
						<td style="width: 40%;">DE</td>
						<td style="width: 40%;">ATÉ</td>
						<td style="width: 20%;">%</td>
					</tr>

					@if($bands[0])
						@for($i=0;$i< sizeof($bands[0]) ;$i++)
							@if($i%2==0)
								<?php $color = '#d5dee4;';?>
							@else
								<?php $color = '#d9e1f2;'?>
							@endif
							<tr style="background-color: {{$color}};">
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

			<div class="col">
				<center>
					<span style="font-weight: bold; font-size: 18px;"> PREVISÃO </span>
				</center>

				@if($forecast)
					<table class="table table-borderless table-outside-border" style="margin-top: 1.25%;">
						<tr class="dc">
							<td> CLIENTE </td>
							@for($m = $startMonthFcst; $m < sizeof($monthsMidName);$m++)
								<td> {{ strtoupper($monthsMidName[$m]) }} </td>
							@endfor
							<td> TOTAL </td>
						</tr>
						@for($f = 0; $f < sizeof($forecast); $f++)

							@if(strtoupper($forecast[$f]['client']) == "TOTAL")
								<?php 
									$color = '#0f243e;';
									$bckGrd = 'color:white;';
								?>
							@elseif($f%2==0)
								<?php 
									$color = '#d5dee4;';
									$bckGrd = '';
								?>
							@else
								<?php 
									$color = '#d9e1f2;';
									$bckGrd = '';
								?>
							@endif
							<tr style="background-color: {{$color}}; {{$bckGrd}}">
								<td> {{ strtoupper($forecast[$f]['client']) }} </td>
								@for($m = $startMonthFcst; $m < sizeof($monthsMidName);$m++)
									<td> {{ number_format($forecast[$f]['split'][$m]) }} </td>
								@endfor								
								<td> {{ number_format($forecast[$f]['revenue']) }} </td>
							</tr>
						@endfor						
					</table>
				@else

				@endif
			</div>

			<div class="col-3" id="byBrandDiv">
				<div id="byBrand"></div>
				<center>
				<div class="form-inline">
					@for($b = 0; $b < sizeof($graph['byBrand']['brandNames']); $b++)
						<div style="margin-left: 1.25%;">
							<span class="dot" style="background-color: {{$graph['byBrand']['brandColor'][$b]}};"></span>
							<span style="font-weight: bold; font-size: 14px;"> {{$graph['byBrand']['brandNames'][$b]}} </span>
						</div>
					@endfor
				</div>
				</center>
			</div>
		</div>

		<div class="row mt-2">
			<div class="col">
				<center>
					<span style="font-weight: bold; font-size: 18px;"> CLIENTES </span>
				</center>
				<div id="childGraph" style="margin-top: 1.25%;"></div>
			</div>
		</div>

		<div class="row mt-4">
			<div class="col">
				<center>
					<button type="button" id="showTabelaClientes" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
						TABELA DE CLIENTES
					</button>
				</center>
			</div>
		</div>
					

		<div class="row mt-2" id="divTabelaClientes" >
			<div class="col">
				<center>
					<table class="table table-borderless table-outside-border" style="width: 50%; margin-top: 1.25%;">
						<tr class="dc" style="text-align: center;">
							<td> CLIENTE </td>
							<td> VALOR </td>
						</tr>
						@for($tc =0;$tc < sizeof($mountBV['child']);$tc++)
							@if(strtoupper($mountBV['child'][$tc]['client']) == "TOTAL")
								<?php 
									$color = '#0f243e;';
									$bckGrd = 'color:white;';
								?>
							@elseif($tc%2==0)
								<?php 
									$color = '#d5dee4;';
									$bckGrd = '';
								?>
							@else
								<?php 
									$color = '#d9e1f2;';
									$bckGrd = '';
								?>
							@endif

							<tr style="text-align: center;background-color: {{$color}}; {{$bckGrd}}">
								<td>{{ strtoupper( $mountBV['child'][$tc]['client'] ) }}</td>
								<td>{{ number_format( $mountBV['child'][$tc]['total'] ) }}</td>
							</tr>
						@endfor	
					</table>						
				</center>				
			</div>
		</div>

		
			
		

		<div class="row mt-5" style="margin-bottom: 2%;">
			<div class="col">
				<center>
					<span style="font-weight: bold; font-size: 18px;"> MESES </span>
				</center>
				<div id="byMonthGraph" style="margin-top: 1.25%;"></div>
			</div>
		</div>

		<div class="row mt-4">
			<div class="col">
				<center>
					<button type="button" id="showTabelaMeses" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
						TABELA MESES
					</button>
				</center>
			</div>
		</div>

		<div class="row mt-2"  id="divTabelaMeses">
			<div class="col">
				<center>
					<table class="table table-borderless table-outside-border" style="width: 100%; margin-top: 1.25%;">
						<!--<tr class="dc" style="text-align: center;">
							<td colspan="14"> INVESTIMENTO MENSAL </td>
						</tr>-->
						<tr class="dc" style="text-align: center;">
							<td style="width: 10%;"> MÊS </td>
							@for($tc =0;$tc < sizeof($mountBV['byMonth']);$tc++)							
								@if($mountBV['byMonth'][$tc]['month'] == "TOTAL")
									<td style="width: 7%;">{{ $mountBV['byMonth'][$tc]['month'] }}</td>
								@else
								<td style="width: 7%;">{{ strtoupper( $monthsMidName[$mountBV['byMonth'][$tc]['month'] - 1] ) }}</td>
								@endif
							@endfor						
						</tr>
						<tr style="text-align: center;background-color: #d9e1f2;">
							<td> INVESTIMENTO </td>
							@for($tc =0;$tc < sizeof($mountBV['byMonth']);$tc++)
								<td>{{ number_format( $mountBV['byMonth'][$tc]['value'] ) }}</td>
							@endfor							
						</tr>
				</center>				
			</div>
		</div>
	</div>



	<!-- Modal -->
	{{--
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
		      	</div>
		    </div>
		</div>
	</div>
	--}}

	<div id="vlau"></div>
	<div id="vlau1"></div>

	<script type="text/javascript">
		$(document).ready(function(){
			$('#showTabelaClientes').click(function(){
				$("divTabelaClientes").hide();
			});

			$('#showTabelaMeses').click(function(){
				alert("AKI2");
			});
		});
	</script>

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
		        legend: { position: "none" },
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

				



    

    