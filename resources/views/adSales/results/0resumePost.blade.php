@extends('layouts.mirror')
@section('title', 'Resume Results')
@section('head')	
	<script src="/js/resultsResume.js"></script>
    <?php include(resource_path('views/auth.php')); ?>
@endsection
@section('content')
	<div class="container-fluid">
		<div class="row">
			<div class="col">
				<form method="POST" action="{{ route('resultsResumePost') }}" runat="server" onsubmit="ShowLoading()">
					@csrf
					<div class="row">
						<div class="col-sm">
							<label class="labelLeft"><span class="bold"> Region: </span></label>
							@if($userLevel == 'L0' || $userLevel == 'SU')
								{{$render->region($region)}}
							@else
								{{$render->regionFiltered($region, $regionID, $special)}}
							@endif
						</div>
						<div class="col-sm">
							<label class="labelLeft"><span class="bold"> Brand: </span></label>
							{{$render->brand($brand)}}
						</div>
						<div class="col-sm">
							<label class="labelLeft"><span class="bold"> Currency: </span></label>
							{{$render->currency($currency)}}
						</div>
						<div class="col-sm">
							<label class="labelLeft"><span class="bold"> Value: </span></label>
							{{$render->value2()}}
						</div>
						<div class="col-sm">
							<label> &nbsp; </label>
							<input type="submit" value="Generate" class="btn btn-primary" style="width: 100%;">		
						</div>
					</div>
				</form>
			</div>
		</div>

		<div class="row justify-content-end mt-2">
			<div class="col-sm"></div>
			<div class="col-sm"></div>
			<div class="col-sm"></div>
			<div class="col-sm">
				<select id="ExcelPDF" class="form-control">
					<option value="Excel">Excel</option>
					<option value="PDF">PDF</option>
				</select>
			</div>
			<div class="col-sm" style="color: #0070c0;font-size: 22px">
				<span style="float: right;"> {{$rName}} - Summary : {{$salesShow}} - {{$cYear}} </span>
			</div>

			<div class="col-sm">
				<button id="excel" type="button" class="btn btn-primary" style="width: 100%">
					Generate Excel
				</button>
			</div>
		</div>


	</div>
	@for($t = 0; $t < sizeof($matrix); $t++)
		<div class="container-fluid" style="margin-right: 0.5%; margin-left: 0.5%; font-size:12px;">
			<div class="row mt-2">
				<div class="col-sm table-responsive">				
					{{ $render->assemble($salesRegion, $salesShow, $cYear, $currencyS, $valueS, $pYear, $matrix[$t], $names[$t]) }}
				</div>
			</div>
		</div>
	@endfor

	<div id="vlau"></div>

	<script type="text/javascript">

		$(document).ready(function() {

			ajaxSetup();

			$("#ExcelPDF").change(function(event){
				if ($("#ExcelPDF").val() == "PDF") {
					$("#excel").text("Generate PDF");
				}else{
					$("#excel").text("Generate Excel");
				}
			});

			$("#ExcelPDF").hide();


			$("#excel").click(function(event){

				var regionExcel = <?php echo $regionExcel; ?>;
				var valueExcel = "<?php echo $valueExcel; ?>";
				var yearExcel = "<?php echo base64_encode(json_encode($yearExcel)); ?>";
				var currencyExcel = "<?php echo base64_encode(json_encode($currencyExcel)); ?>";
				var brandsExcel = "<?php echo base64_encode(json_encode($brandsExcel)); ?>";

				var div = document.createElement('div');
				var img = document.createElement('img');
				img.src = '/loading_excel.gif';
				div.innerHTML = "Generating File...<br/>";
				div.style.cssText = 'position: absolute; left: 0px; top:0px;  margin:0px;        width: 100%;        height: 100%;        display:block;        z-index: 99999;        opacity: 0.9;        -moz-opacity: 0;        filter: alpha(opacity = 45);        background: white;        background-image: url("/Loading.gif");        background-repeat: no-repeat;        background-position:50% 50%;        text-align: center;        overflow: hidden;   font-size:30px;     font-weight: bold;        color: black;        padding-top: 20%';
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
						url: "/generate/excel/results/summary",
						type: "POST",
						data: {regionExcel, valueExcel, yearExcel, currencyExcel, brandsExcel, title, typeExport, auxTitle},
						/*success: function(output){
							$("#vlau").html(output);
						},*/
						success: function(result, status, xhr){
							var disposition = xhr.getResponseHeader('content-disposition');
					        var matches = /"([^"]*)"/.exec(disposition);
					        var filename = (matches != null && matches[1] ? matches[1] : title);

							// The actual download
					        var blob = new Blob([result], {
					            type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
					        });
					        var link = document.createElement('a');
					        link.href = window.URL.createObjectURL(blob);
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
				}else{

					var title = "<?php echo $titlePdf; ?>";

					$.ajax({
						xhrFields: {
							responseType: 'blob',
						},
						url: "/generate/excel/results/summary",
						type: "POST",
						data: {regionExcel, valueExcel, yearExcel, currencyExcel, brandsExcel, title, typeExport, auxTitle},
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
		});
	</script>
@endsection