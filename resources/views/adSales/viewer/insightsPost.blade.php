@extends('layouts.mirror')
@section('title', 'Insights Viewer')
@section('head')

    <script src="/js/insights.js"></script>

    <?php include(resource_path('views/auth.php'));?>

@endsection
@section('content')

	<div class="container-fluid">
		<div class="row">
			<div class="col">
				<form method="POST" action="{{route('insightsPost')}}" runat="server" onsubmit="ShowLoading()">
					@csrf
					<div class="row">
						
						<div class="col">
							<label class="labelLeft"><span class="bold"> Region: </span></label>

                            @if($errors->has('region'))
                                <label style="color: red;">* Required</label>
                            @endif

                            @if($userLevel == 'L0' || $userLevel == 'SU')
                                {{$render->region($region)}}                            
                            @else
                                {{$render->regionFiltered($region, $regionID, $special)}}
                            @endif
						</div>

                        <div class="col">
                            <label class='labelLeft'><span class="bold">Client:</span></label>
                            @if($errors->has('client'))
                                <label style="color: red;">* Required</label>
                            @endif
                            {{$render->ClientForm()}}
                        </div>
                        
                        <div class="col">
                            <label class='labelLeft'><span class="bold">Months:</span></label>
                            @if($errors->has('month'))
                                <label style="color: red;">* Required</label>
                            @endif
                            {{$render->months()}}
                        </div>
                        
                        <div class="col">
                            <label class="labelLeft"><span class="bold"> Brand: </span></label>
                            @if($errors->has('brand'))
                                <label style="color: red;">* Required</label>
                            @endif
                            {{$render->brand($brand)}}
                        </div>

                        <div class="col">
                            <label class='labelLeft'><span class="bold">Sales Rep:</span></label>
                            @if($errors->has('salesRep'))
                                <label style="color: red;">* Required</label>
                            @endif
                            {{$render->salesRep()}}
                        </div>                        

                        <div class="col">
                            <label class="labelLeft"><span class="bold"> Currency: </span></label>
                            @if($errors->has('currency'))
                                <label style="color: red;">* Required</label>
                            @endif
                            {{$render->currency($currencies)}}
                        </div>

                        <div class="col">
                            <label class="labelLeft"><span class="bold"> Value: </span></label>
                            @if($errors->has('value'))
                                <label style="color: red;">* Required</label>
                            @endif
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
            <div class="col-sm"></div>
			<div class="col-sm-4" style="color: #0070c0; font-size:22px">
                <span style="float: right; margin-right: 2.5%;">Insights</span>
            </div>

            <div class="col-sm">
                <button class="btn btn-primary" type="button" id="excel" style="width: 100%">
                    Generate Excel
                </button>
            </div>
        </div>
	</div>

    <div id="vlau"></div>

    <script type="text/javascript">
        
        $(document).ready(function(event){

            ajaxSetup();

            $('#excel').click(function(event){

                var regionExcel = "<? echo $regionExcel; ?>";
                var clientExcel = "<? echo base64_encode(json_encode($clientExcel)); ?>";
                var monthExcel = "<? echo base64_encode(json_encode($monthExcel)); ?>";
                var brandExcel = "<? echo base64_encode(json_encode($brandExcel)); ?>";
                var salesRepExcel = "<? echo base64_encode(json_encode($salesRepExcel));?>";
                var currencyExcel = "<? echo $currencyExcel; ?>";
                var valueExcel = "<? echo $valueExcel; ?>";
                var title = "<? echo $title; ?>";

                var div = document.createElement('div');
                var img = document.createElement('img');
                img.src = '/loading_excel.gif';
                div.innerHTML = "Generating Excel...</br>";
                div.style.cssText = 'position: absolute; left: 0px; top:0px;  margin:0px;        width: 100%;        height: 100%;        display:block;        z-index: 99999;        opacity: 0.9;        -moz-opacity: 0;        filter: alpha(opacity = 45);        background: white;    background-repeat: no-repeat;        background-position:50% 50%;        text-align: center;        overflow: hidden;   font-size:30px;     font-weight: bold;        color: black;        padding-top: 20%';
                div.appendChild(img);
                document.body.appendChild(div);

                $.ajax({
                    xhrFields: {
                        responseType: 'blob';
                    },
                    url: "generate/excel/viewer/vInsights",
                    type: "POST",
                    data: {regionExcel,clientExcel,monthExcel,brandExcel,salesRepExcel,currencyExcel,valueExcel,title},
                    /*success: function(output){
                        $("#vlau").html(output);
                    },*/
                    success: function(result,status,xhr){
                        var disposition = xhr.getResponseHeader('content-disposition');
                        var matches = /"([^"]*)/.exec(disposition);
                        var filename = (matches != null && matches[1] ? matches[1] : title);

                        //download
                        var blob = new Blob =  new Blob([result], {
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
                    error: function(xhr,ajaxOptions,thrownError){
                        document.body.removeChild(div);
                        alert(xhr.status+" "+thrownError);
                    }
                });
            });
        });
    </script>

@endsection