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
			<div class="col-sm" style="color: #0070c0;font-size: 22px">
				<span style="float: right;"> {{$rName}} - Summary : {{$salesShow}} - {{$cYear}} </span>
			</div>

			<div class="col-sm">
				<button id="excel" type="button" class="btn btn-primary" style="width: 100%">
					Generate Excel
				</button>
			</div>
		</div>

		{{--<form method="POST" action="{{ route('summaryExcel') }}" runat="server">
			@csrf
			<input type="hidden" name="regionExcel" value="{{$regionExcel}}">
			<input type="hidden" name="valueExcel" value="{{$valueExcel}}">
			<input type="hidden" name="planExcel" value="{{ base64_encode(json_encode($plan)) }}">
			<input type="hidden" name="yearExcel" value="{{ base64_encode(json_encode($yearExcel)) }}">
			<input type="hidden" name="currencyExcel" value="{{ base64_encode(json_encode($currencyExcel)) }}">
			<input type="hidden" name="finalExcel" value="{{base64_encode(json_encode($finalExcel))}}">

			<div class="row justify-content-end mt-2">
				<div class="col-sm"></div>
				<div class="col-sm"></div>
				<div class="col-sm"></div>
				<div class="col-sm" style="color: #0070c0;font-size: 22px">
					<span style="float: right;"> {{$rName}} - Summary : {{$salesShow}} - {{$cYear}} </span>
				</div>

				<div class="col-sm">
					<button type="submit" class="btn btn-primary" style="width: 100%">
						Generate Excel
					</button>
				</div>
			</div>
		</form>--}}
	</div>
	<div id="vlau"></div>
	@for($t = 0; $t < sizeof($matrix); $t++)
		<div class="container-fluid" style="margin-right: 0.5%; margin-left: 0.5%; font-size:12px;">
			<div class="row mt-2">
				<div class="col-sm table-responsive">				
					{{ $render->assemble($salesRegion, $salesShow, $cYear, $currencyS, $valueS, $pYear, $matrix[$t], $names[$t]) }}
				</div>
			</div>
		</div>
	@endfor

	<script type="text/javascript">

		/*function ShowLoadingExcel(e) {
				
				return true;
				// These 2 lines cancel form submission, so only use if needed.
				//window.event.cancelBubble = true;
				//e.stopPropagation();
			}*/

		$(document).ready(function() {

			ajaxSetup();

			$("#excel").click(function(event){

				var regionExcel = "{{$regionExcel}}";
				var valueExcel = "{{$valueExcel}}";
				var planExcel = "{{base64_encode(json_encode($plan))}}";
				var yearExcel = "{{base64_encode(json_encode($yearExcel))}}";
				var currencyExcel = "{{base64_encode(json_encode($currencyExcel))}}";
				var finalExcel = "{{base64_encode(json_encode($finalExcel))}}";
				var title = regionExcel+" - Summary.xlsx";

				var div = document.createElement('div');
				var img = document.createElement('img');
				img.src = '/loading.gif';
				div.innerHTML = "Processing Request...<br/>";
				div.style.cssText = 'position: absolute; left: 0px; top:0px;  margin:0px;        width: 100%;        height: 100%;        display:block;        z-index: 99999;        opacity: 0.9;        -moz-opacity: 0;        filter: alpha(opacity = 45);        background: white;        background-image: url("/Loading.gif");        background-repeat: no-repeat;        background-position:50% 50%;        text-align: center;        overflow: hidden;   font-size:30px;     font-weight: bold;        color: black;        padding-top: 20%';
				div.appendChild(img);
				document.body.appendChild(div);

				$.ajax({
					xhrFields: {
						responseType: 'blob',
					},
					url: "/generate/excel/summary",
					type: "POST",
					data: {regionExcel, valueExcel, planExcel, yearExcel, currencyExcel, finalExcel, title},
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
                        alert(xhr.status+" "+thrownError);
                    }
				});
			});

		});
	</script>
@endsection