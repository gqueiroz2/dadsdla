@extends('layouts.mirror')
@section('title', 'quarter')
@section('head')	
	<script src="/js/performance.js"></script>
    <?php include(resource_path('views/auth.php')); ?>
@endsection

@section('content')
	<div class="container-fluid">
		<div class="row">
			<div class="col">
				<form method="POST" action="{{ route('quarterPerformancePost') }}"  runat="server"  onsubmit="ShowLoading()">
					@csrf
					<div class="row">
						<div class="col-sm">
							<label>Region:</label>
							@if($errors->has('region'))
								<label style="color: red;">* Required</label>
							@endif
							@if($userLevel == 'L0' || $userLevel == 'SU')
								{{ $render->region($salesRegion) }}
							@else
								{{ $render->regionFiltered($salesRegion, $regionID, $special) }}
							@endif
						</div>

						<div class="col-sm">
							<label>Year:</label>
							@if($errors->has('year'))
								<label style="color: red;">* Required</label>
							@endif
							{{ $render->year() }}
						</div>

						<div class="col-sm">
							<label>Tiers:</label>
							@if($errors->has('tier'))
								<label style="color: red;">* Required</label>
							@endif
							{{ $render->tiers() }}
						</div>					

						<div class="col-sm">
							<label>Brands:</label>
							@if($errors->has('brand'))
								<label style="color: red;">* Required</label>
							@endif
							{{ $render->brandPerformance() }}
						</div>	

						<div class="col-sm">
							<label>Sales Rep Group:</label>
							@if($errors->has('salesRepGroup'))
								<label style="color: red;">* Required</label>
							@endif
							{{ $render->salesRepGroup($salesRepGroup) }}
						</div>

						<div class="col-sm">
							<label style="float: left;">Sales Rep:</label>
							@if($errors->has('salesRep'))
								<label style="color: red;">* Required</label>
							@endif
							{{$render->salesRep($salesRep)}}
						</div>

						<div class="col-sm">
							<label> Currency: </label>
							@if($errors->has('currency'))
								<label style="color: red;">* Required</label>
							@endif
							{{ $render->currency() }}
						</div>

						<div class="col-sm">
							<label> Value: </label>
							@if($errors->has('value'))
								<label style="color: red;">* Required</label>
							@endif
							{{ $render->value() }}
						</div>
						<div class="col-sm">
							<label> &nbsp; </label>
							<input type="submit" value="Generate" class="btn btn-primary" style="width: 100%">		
						</div>
					</div>
				</form>
			</div>
		</div>

		<div class="row justify-content-end mt-2">
			<div class="col-sm"></div>
			<div class="col-sm"></div>
			<div class="col-sm"></div>
			<div class="col-sm"></div>
			<div class="col-sm"></div>
			<div class="col-sm"></div>
			<div class="col-sm">
				<select id="ExcelPDF" class="form-control">
					<option value="Excel">Excel</option>
					<option value="PDF">PDF</option>
				</select>
			</div>
			<div class="col-sm" style="color: #0070c0;font-size: 22px;">
				{{$rName}} - Office : {{$year}}
			</div>
			<div class="col-sm">
				<button id="excel" type="button" class="btn btn-primary" style="width: 100%">
					Generate Excel
				</button>				
			</div>
		</div>

	</div>

	<div class="container-fluid" style="margin-right: 0.5%; margin-left: 0.5%; font-size: 12px">
		<div class="row mt-2">
			<div class="col-sm">
				{{ $render->assemble($mtx, $region, $pRate, $value, $year, $sales, $tiers) }}
			</div>
		</div>
	</div>

	<div id="vlau"></div>

	<script type="text/javascript">
		
		ajaxSetup();

		$("#ExcelPDF").change(function(event){
			if ($("#ExcelPDF").val() == "PDF") {
				$("#excel").text("Generate PDF");
			}else{
				$("#excel").text("Generate Excel");
			}
		});

		$('#ExcelPDF').hide();

		$('#excel').click(function(event){

				var region = "<?php echo $regionExcel; ?>";
				var year = "<?php echo $yearExcel; ?>";
				var brands = <?php echo json_encode($brandsExcel); ?>;
				var currency = "<?php echo $currencyExcel; ?>";
				var salesRepGroup = <?php echo json_encode($salesRepGroupExcel); ?>;
				var salesRep = <?php echo json_encode($salesRepExcel); ?>;
				var value = "<?php echo $valueExcel; ?>";
				var tiers = <?php echo json_encode($tiersExcel); ?>;

				var div = document.createElement('div');
				var img = document.createElement('img');
				img.src = '/loading_excel.gif';
				div.innerHTML = "Generating File...</br>";
				div.style.cssText = 'position: absolute; left: 0px; top:0px;  margin:0px;        width: 100%;        height: 100%;        display:block;        z-index: 99999;        opacity: 0.9;        -moz-opacity: 0;        filter: alpha(opacity = 45);        background: white;    background-repeat: no-repeat;        background-position:50% 50%;        text-align: center;        overflow: hidden;   font-size:30px;     font-weight: bold;        color: black;        padding-top: 20%';
				div.appendChild(img);
				document.body.appendChild(div);

				var typeExport = $("#ExcelPDF").val();
				var auxTitle = "<?php echo $title; ?>";

				if (typeExport == "Excel") {

					var title = "<?php echo $titleExcel; ?>";

					$.ajax({
						xhrFields: {
							responseType: 'blob',
						},
						url: "/generate/excel/performance/quarter",
						type: "POST",
						data: {region, year, brands, salesRepGroup, salesRep, currency, value, tiers, title, typeExport, auxTitle},
						/*success:function(output){
							$("#vlau").html(output);
						},*/
						success: function(result,status,xhr){
							var disposition = xhr.getResponseHeader('content-disposition');
							var matches =/"([^"]*)"/.exec(disposition);
							var filename = (matches != null && matches[1] ? matches[1] : title);

							//download
							var blob = new Blob([result],{
								type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
							});
							var link =  document.createElement('a');
							link.href = window.URL.createObjectURL(blob);
							link.download = filename;

							document.body.appendChild(link);

							link.click();
							document.body.removeChild(link);
							document.body.removeChild(div);
						},
						error: function(xhr,ajaxOptions,thrownError){
							document.body.removeChild(div);
							alert(xhr.status+" "+thrownError);
						}
					});
				}else{
					var title = "<?php echo $titlePdf; ?>";
					
					$.ajax({
						xhrFields: {
							responseType: 'blob',
						},
						url: "/generate/excel/performance/quarter",
						type: "POST",
						data: {region, year, brands, salesRepGroup, salesRep, currency, value, tiers, title, typeExport, auxTitle},
						/*success: function(output){
							$("#vlau").html(output);
						},*/
						success: function(result, status, xhr){
							var disposition = xhr.getResponseHeader('content-disposition');
					        var matches = /"([^"]*)"/.exec(disposition);
					        var filename = (matches != null && matches[1] ? matches[1] : title);
					        var link = document.createElement('a');
					        link.href = window.URL.createObjectURL(result);
					        link.download = filename;

					        document.body.appendChild(link);

					        link.click();
					        document.body.removeChild(link);
					        document.body.removeChild(div);
						},
						error: function(xhr, ajaxOptions,thrownError){
							document.body.removeChild(div);
	                        alert(xhr.status+" "+thrownError);
	                    }
					});
				}
			});

	</script>
@endsection