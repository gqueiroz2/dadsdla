@extends('layouts.mirror')
@section('title', 'Base Viewer')
@section('head')
    <script src="/js/viewer.js"></script>
    <?php include(resource_path('views/auth.php'));?>
@endsection
@section('content')

	<div class="container-fluid">
        <div class="row">
            <div class="col">
                <form method="POST" action="{{ route('basePost') }}" runat="server"  onsubmit="ShowLoading()">
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
                            <label class="labelLeft"><span class="bold"> Source: </span></label>
                            @if($errors->has('sourceDataBase'))
                                <label style="color: red;">* Required</label>
                            @endif
                            {{$render->sourceDataBase()}}
                        </div>
                        
                        <div class="col" id="especificNumberCol" style="display:none;">
                            <label class="labelLeft"><span class="bold" id="especificNumberName"> Map Number: </span></label>
                            {{$render->especificNumber($brands)}}
                        </div>
                        
                        <div class="col">
                            <label class="labelLeft"><span class="bold"> Year: </span></label>
                            @if($errors->has('year'))
                                <label style="color: red;">* Required</label>
                            @endif
                            {{$render->year($regionID)}}                    
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
                            {{$render->brandViewer()}}
                        </div>
                         
                                            
                    </div>

                    <div class="row">
                        <div class="col">
                            <label class='labelLeft'><span class="bold">Sales Rep:</span></label>
                            @if($errors->has('salesRep'))
                                <label style="color: red;">* Required</label>
                            @endif
                            {{$render->salesRep()}}
                        </div>
                        <div class="col">
                            <label class='labelLeft'><span class="bold">Agency:</span></label>
                            @if($errors->has('agency'))
                                <label style="color: red;">* Required</label>
                            @endif
                            {{$render->AgencyForm()}}
                        </div>

                        <div class="col">
                            <label class='labelLeft'><span class="bold">Client:</span></label>
                            @if($errors->has('client'))
                                <label style="color: red;">* Required</label>
                            @endif
                            {{$render->ClientForm()}}
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
                        <div class="col">
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
            <div class="col-sm"></div>
            <div class="col-sm"></div>
            <div class="col-sm"></div>
            <div class="col-sm"></div> 
            <div class="col-sm-4" style="color: #0070c0; font-size:22px">
                <span style="float: right; margin-right: 2.5%;">Data Current Through: DD-MM-YY (<?php echo date('d/m/Y'); ?>)</span>
            </div>

            <div class="col-sm-2">
                <button type="button" id="excel" class="btn btn-primary" style="width: 100%">
                    Generate Excel
                </button>               
            </div>
    	</div>
    </div>

        <div class="container-fluid">
            <div class="row justify-content-center mt-2">
                <div class="col">
                    {{$bRender->assemble($mtx,$value,$months,$year,$regions,$brand,$source,$currencies,$total)}}
                </div>
            </div>
        </div>
        
        <div id="vlau"></div>

        <script type="text/javascript">
            
            $(document).ready(function(){

                ajaxSetup();

                $('#excel').click(function(event){

                    var regionExcel = "<?php echo $regionExcel; ?>";
                    var sourceExcel = "<?php echo $sourceExcel; ?>";
                    var yearExcel = "<?php echo base64_encode(json_encode($yearExcel)); ?>";
                    var monthExcel = "<?php echo base64_encode(json_encode($monthExcel)); ?>";
                    var brandExcel = "<?php echo base64_encode(json_encode($brandExcel)); ?>";
                    var salesRepExcel = "<?php echo base64_encode(json_encode($salesRepExcel)); ?>";
                    var agencyExcel = "<?php echo base64_encode(json_encode($agencyExcel)); ?>";
                    var clientExcel = "<?php echo base64_encode(json_encode($clientExcel)); ?>";
                    var currencyExcel = "<?php echo  base64_encode(json_encode($currencyExcel)); ?>";
                    var valueExcel = "<?php echo $valueExcel; ?>";
                    var title = "<?php echo $title; ?>";

                    /*var div = document.createElement('div');
                    var img = document.createElement('img');
                    img.src = '/loading_excel.gif';
                    div.innerHTML ="Generating Excel...</br>";
                    div.style.cssText = 'position: absolute; left: 0px; top:0px;  margin:0px;        width: 100%;        height: 100%;        display:block;        z-index: 99999;        opacity: 0.9;        -moz-opacity: 0;        filter: alpha(opacity = 45);        background: white;    background-repeat: no-repeat;        background-position:50% 50%;        text-align: center;        overflow: hidden;   font-size:30px;     font-weight: bold;        color: black;        padding-top: 20%';
                    div.appendChild(img);
                    document.body.appendChild(div);*/

                    $.ajax({
                        /*xhrFields: {
                            responseType: 'blob',
                        },*/
                        url: "/generate/excel/viewer/vBase",
                        type: "POST",
                        data: {regionExcel,sourceExcel,yearExcel,monthExcel,brandExcel,salesRepExcel,agencyExcel,clientExcel,currencyExcel,valueExcel,title},
                        success: function(output){
                            $("#vlau").html(output);
                        },
                       /* success: function(result,status,xhr){
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
                        },*/
                        error: function(xhr, ajaxOptions, thrownError){
                            //document.body.removeChild(div);
                            alert(xhr.status+" "+thrownError);
                        }
                    });

                });
            });

        </script>
@endsection