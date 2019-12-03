@extends('layouts.mirror')
@section('title', 'Ranking Market')
@section('head')	
	<script src="/js/rankingMarket.js"></script>
    <?php include(resource_path('views/auth.php')); ?>
@endsection

@section('content')
	<div class="container-fluid">
		<div class="row">
			<div class="col">
				<form method="POST" action="{{ route('marketPost') }}" runat="server" onsubmit="ShowLoading()">
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
								{{$render->regionFiltered($salesRegion, $regionID, $special)}}
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
							<label class="labelLeft bold"> Brand: </label>
							@if($errors->has('brands'))
								<label style="color: red">* Required</label>
							@endif
							{{$render->brand($brand)}}
						</div>
						<div class="col">
							<label class="labelLeft bold">Months:</label>
							@if($errors->has('month'))
								<label style="color: red">* Required</label>
							@endif
							{{$render->months()}}
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
			@if($type != "sector")
				<div class="col-sm">
					{{$render->search($mtx, $type)}}
				</div>
				<div class="col-sm">
					<button id="excel" type="button" class="btn btn-primary" style="width: 100%">
						Generate Excel
					</button>
				</div>
				<div class="col-sm"></div>
				<div class="col-sm"></div>
				<div class="col-sm"></div>
				<div class="col-sm"></div>
				<div class="col-sm" style="color: #0070c0;font-size: 22px;">
					<div style="float: right;"> 
						{{$rName}} - {{ucfirst($type)}} Market Ranking 
					</div>
				</div>
			@else
				<div class="col-sm"></div>
				<div class="col-sm"></div>
				<div class="col-sm"></div>
				<div class="col-sm"></div>
				<div class="col-sm"></div>
				<div class="col-sm" style="color: #0070c0;font-size: 22px;">
					<div style="float: right;"> 
						{{$rName}} - {{ucfirst($type)}} Market Ranking 
					</div>
				</div>
				<div class="col-sm">
					<button id="excel" type="button" class="btn btn-primary" style="width: 100%">
						Generate Excel
					</button>
				</div>
			@endif
		</div>
	</div>
	
	<div class="container-fluid">
		<div class="row mt-2 justify-content-center">
			<div class="col">
				{{$render->assembler($mtx, $total, $pRate, $value, $type, $names, $rtr)}}
			</div>
		</div>
	</div>

	<div id="vlau"></div>

	<script type="text/javascript">
		$(document).ready(function(){
			
			var months = <?php echo json_encode($months); ?>;
            var type = "{{$type}}";
            var value = "{{$value}}";
            var currency = <?php echo json_encode($pRate); ?>;
            var region = "{{$region}}";
            var brands = <?php echo json_encode($brands); ?>;

			ajaxSetup();

			@for($m = 0; $m < sizeof($mtx[0]); $m++)
				$(document).on('click', "#"+type+{{$m}}, function(){

                    var name = $(this).text();

                    if ($("#sub"+type+{{$m}}).css("display") == "none") {

                        $.ajax({
                            url: "/ajaxRanking/marketSubRanking",
                            method: "POST",
                            data: {name, months, type, value, currency, region, brands},
                            success: function(output){
                                $("#sub"+type+{{$m}}).html(output);
                                $("#sub"+type+{{$m}}).css("display", "");
                            },
                            error: function(xhr, ajaxOptions,thrownError){
                                alert(xhr.status+" "+thrownError);
                            }
                        });
                    }else{
                    	$("#sub"+type+{{$m}}).html(" ");
                        $("#sub"+type+{{$m}}).css("display", "none");
                    }
                });
            @endfor

            $("#excel").click(function(event){
            	
            	var regionExcel = "{{$regionExcel}}";
				var typeExcel = "{{$typeExcel}}";
				var brandsExcel = "<?php echo base64_encode(json_encode($brandsExcel)); ?>";
				var monthsExcel = "<?php echo base64_encode(json_encode($monthsExcel)); ?>";
				var currencyExcel = "<?php echo base64_encode(json_encode($currencyExcel)); ?>";
	            var valueExcel = "{{$valueExcel}}";
	            var yearsExcel = "<?php echo base64_encode(json_encode($yearsExcel)); ?>";

	            var names = "";
	            if("{{$type}}" == "sector"){
	            	names = "<?php echo base64_encode(json_encode($namesExcel)); ?>"
	            }else{
	            	names = $("#namesExcel").val();
	            }

	            var title = "<?php echo $title; ?>";

	            /*var div = document.createElement('div');
				var img = document.createElement('img');
				img.src = '/loading_excel.gif';
				div.innerHTML = "Generating Excel...<br/>";
				div.style.cssText = 'position: absolute; left: 0px; top:0px;  margin:0px;        width: 100%;        height: 100%;        display:block;        z-index: 99999;        opacity: 0.9;        -moz-opacity: 0;        filter: alpha(opacity = 45);        background: white;        background-image: url("/Loading.gif");        background-repeat: no-repeat;        background-position:50% 50%;        text-align: center;        overflow: hidden;   font-size:30px;     font-weight: bold;        color: black;        padding-top: 20%';
				div.appendChild(img);
				document.body.appendChild(div);*/

				$.ajax({
					/*xhrFields: {
						responseType: 'blob',
					},*/
					url: "/generate/excel/ranking/market",
					type: "POST",
					data: {regionExcel, typeExcel, brandsExcel, monthsExcel, currencyExcel, valueExcel, yearsExcel, names, title},
					/*success: function(result, status, xhr){
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
					},*/
					success: function(output) {
						$('#vlau').html(output);
					},
					error: function(xhr, ajaxOptions,thrownError){
						//document.body.removeChild(div);
                        alert(xhr.status+" "+thrownError);
                    }

				});
            });
		});
	</script>

@endsection
