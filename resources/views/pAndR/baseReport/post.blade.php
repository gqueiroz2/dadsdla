@extends('layouts.mirror')
@section('title', 'Consolidate View')
@section('head')	
    <?php include(resource_path('views/auth.php')); ?>
    <script src="/js/pandrBaseReport.js"></script>
@endsection
@section('content')

	

	<form method="POST" action="{{ route('BaseReportPandRPost') }}" runat="server"  onsubmit="ShowLoading()">
		@csrf
		<div class="container-fluid">
			<div class="row">
				<div class="col">
					<label class='labelLeft'><span class="bold">Region:</span></label>
					@if($errors->has('region'))
						<label style="color: red;">* Required</label>
					@endif
					@if($userLevel == 'L0' || $userLevel == 'SU')
						{{$render->region($region)}}							
					@else
						{{$render->regionFiltered($region, $regionID, $special )}}
					@endif
				</div>
				<div class="col">
					<label class='labelLeft'><span class="bold">Year:</span></label>
					@if($errors->has('year'))
						<label style="color: red;">* Required</label>
					@endif
					{{$render->year()}}
				</div>

				<div class="col">
					<label class='labelLeft'><span class="bold">Filter:</span></label>
					@if($errors->has('baseFilter'))
						<label style="color: red;">* Required</label>
					@endif
					{{$render->baseReportFilter()}}
				</div>

				<div class="col" style="display: none;">
					<label class='labelLeft'><span class="bold">Sales Rep:</span></label>
					@if($errors->has('salesRep'))
						<label style="color: red;">* Required</label>
					@endif
					{{$render->salesRep()}}
				</div>

				<div class="col">
					<label class='labelLeft'><span class="bold">Currency:</span></label>
					@if($errors->has('currency'))
						<label style="color: red;">* Required</label>
					@endif
					{{$render->currency($currency)}}
				</div>	
				<div class="col">
					<label class="labelLeft"><span class="bold"> Value: </span></label>
						@if($errors->has('value'))
							<label style="color: red;">* Required</label>
						@endif
						{{$render->value2()}}
				</div>

				<div class="col">
					<label class='labelLeft'> &nbsp; </label>
					<input style="width: 100%;" type="submit" value="Generate" class="btn btn-primary">		
				</div>			
			</div>
			<br>			
		</div>
	</form>
	<div class="container-fluid">
		<div class="row justify-content-end mt-2">
			<div class="col-2" style="color: #0070c0;font-size: 25px;">
				<span style="float: right; margin-right: 2.5%;">Base Report </span>
			</div>

			<div class="col-2">
                <button type="button" id="excel" class="btn btn-primary" style="width: 100%">
                    Generate Excel
                </button>               
            </div>      
		</div>
	</div>
	<div id="vlau">

	</div>

	<div class="container-fluid" id="body" >
        <div class="row mt-2 justify-content-end">
            <div class="col" style="width: 100%;">
                <center>
                    {{$render->AE1($forRender,$userName,$baseReport)}}
                </center>
            </div>
        </div>
    </div>

    <script type="text/javascript">
    	
    	$('.linked').scroll(function(){
    		$('.linked').scrollLeft($(this).scrollLeft());
        });

    </script>

     <script type="text/javascript">
        $(document).ready(function(){

            ajaxSetup();

            $('#excel').click(function(event){
                var yearExcel = "<?php echo $yearExcel; ?>";
                var regionExcel = "<?php echo $regionExcel; ?>";
                var valueExcel = "<?php echo $valueExcel; ?>";
                var currencyExcel = "<?php echo $currencyExcel; ?>";
                var salesRepExcel = "<?php echo base64_encode(json_encode($salesRepExcel)); ?>";
                var baseReportExcel = "<?php echo $baseReportExcel; ?>";
                var userRegionExcel = "<?php echo $userRegionExcel; ?>";


                var div = document.createElement('div');
                var img = document.createElement('img');
                img.src = '/loading_excel.gif';
                div.innerHTML ="Generating File...</br>";
                div.style.cssText = 'position: absolute; left: 0px; top:0px;  margin:0px;        width: 100%;        height: 100%;        display:block;        z-index: 99999;        opacity: 0.9;        -moz-opacity: 0;        filter: alpha(opacity = 45);        background: white;    background-repeat: no-repeat;        background-position:50% 50%;        text-align: center;        overflow: hidden;   font-size:30px;     font-weight: bold;        color: black;        padding-top: 20%';
                div.appendChild(img);
                document.body.appendChild(div);

                var typeExport = $("#excel").val();

                var title = "<?php echo $titleExcel; ?>";
                var auxTitle = "<?php echo $titleExcel; ?>";
                    
                    $.ajax({
                        xhrFields: {
                            responseType: 'blob',
                        },
                        url: "/generate/excel/pandr/baseReport",
                        type: "POST",
                        data: {title, typeExport, yearExcel,regionExcel,valueExcel,currencyExcel,salesRepExcel,baseReportExcel,auxTitle, userRegionExcel},
                        /*success: function(output){
                            $("#vlau").html(output);
                        },*/
                        success: function(result,status,xhr){
                            var disposition = xhr.getResponseHeader('content-disposition');
                            var matches = /"([^"]*)"/.exec(disposition);
                            var filename = (matches != null && matches[1] ? matches[1] : title);

                            //download
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
                        error: function(xhr, ajaxOptions, thrownError){
                            document.body.removeChild(div);
                            alert(xhr.status+" "+thrownError);
                        }
                    });                    
                });
            });
    </script>
@endsection