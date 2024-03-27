@extends('layouts.mirror')
@section('title', 'Base Viewer')
@section('head')
    <script src="/js/viewer.js"></script>
    <?php 
        include(resource_path('views/auth.php'));
        use App\base;
        $bs = new base();
    ?>
@endsection
@section('content')

	<div class="container-fluid">
        <div class="row">
            <div class="col">
                <form method="POST" action="{{ route('basePost') }}" runat="server"  onsubmit="ShowLoading()">
                    @csrf
                     <div class="row">    
                        <div class="col">
                            <label class="labelLeft"><span class="bold"> Holding: </span></label>
                            @if($errors->has('company'))
                                <label style="color: red;">* Required</label>
                            @endif
                            {{$render->company()}}
                        </div>                    

                        <div class="col" style="display: none;">
                            <label class="labelLeft"><span class="bold"> Region: </span></label>

                            @if($errors->has('region'))
                                <label style="color: red;">* Required</label>
                            @endif
                                {{$render->regionFiltered($region, $regionID, $special)}}
                        </div>

                        <div class="col">
                            <label class="labelLeft"><span class="bold"> Year: </span></label>
                            @if($errors->has('year'))
                                <label style="color: red;">* Required</label>
                            @endif
                            {{$render->year($regionID)}}                    
                        </div> 

                        <div class="col" style="display:none;">
                            <label class="labelLeft"><span class="bold"> Source: </span></label>
                            @if($errors->has('sourceDataBase'))
                                <label style="color: red;">* Required</label>
                            @endif
                            {{$render->sourceDataBase()}}
                        </div>
                        <div class="col">
                            <label class='labelLeft'><span class="bold">Months:</span></label>
                            @if($errors->has('month'))
                                <label style="color: red;">* Required</label>
                            @endif
                            {{$render->months()}}
                        </div>
                        <div class="col" style="display:none;">
                            <label class="labelLeft"><span class="bold"> Brand: </span></label>
                            @if($errors->has('brand'))
                                <label style="color: red;">* Required</label>
                            @endif
                            {{$render->brand($brands)}}
                        </div>

                        <div class="col">
                            <label class="labelLeft"><span class="bold"> Platform: </span></label>
                            @if($errors->has('platform'))
                                <label style="color: red;">* Required</label>
                            @endif
                            {{$render->platform()}}
                        </div>                         
                                            
                    </div>

                    <div class="row">

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

                            <input type="hidden" name="sizeOfClient" id="sizeOfClient" value="">
                        </div>

                        <div class="col">
                            <label class='labelLeft'><span class="bold">Manager:</span></label>
                            @if($errors->has('manager'))
                                <label style="color: red;">* Required</label>
                            @endif
                                {{$render->director()}}
                        </div>

                        <div class="col">
                            <label class='labelLeft'><span class="bold">Sales Rep:</span></label>
                            @if($errors->has('salesRep'))
                                <label style="color: red;">* Required</label>
                            @endif
                                {{$render->salesRep()}}
                        </div>              


                        <div class="col" style="display: none;">
                            <label class="labelLeft"><span class="bold"> Currency: </span></label>
                            @if($errors->has('currency'))
                                <label style="color: red;">* Required</label>
                            @endif
                            {{$render->currency($currencies)}}
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
            <div class="col"></div>
            <div class="col"></div>
            <div class="col"></div>
            <div class="col"></div>
            <div class="col"></div>
            <div class="col"></div>
            <div class="col" style="visibility: hidden;">
                <select id="ExcelPDF" class="form-control">
                    <option value="Excel">Excel</option>
                    <option value="PDF">PDF</option>
                </select>
            </div>
            
            <div class="col-4" style="color: #0070c0; font-size:22px">
                <span style="float: right; margin-right: 2.5%;">Data Current Through: <?php echo $bs->sourceCMAPS($source); ?></span>
            </div> 
            <div class="col-2">
                
                    <button type="button" id="excel" class="btn btn-primary" style="width: 100%">
                        Generate Excel
                    </button>
            </div>           
    	</div>
    </div>

    <div class="container-fluid">
        <div class="row mt-4">
            <div class="col table-responsive">
                @if($mtx)
                    <table style='width: 100%;'>
                        <tr>  
                            <th class='newBlue center' colspan='16' style='font-size:22px; width:100%;'> Brazil - Viewer WBD {{$year}} </th>
                        </tr>

                        <tr class='center'>
                            <td class='rcBlue' style='width:5%;'>Company</td>
                            <td class='rcBlue' style='width:3%;'>Year</td>
                            <td class='rcBlue' style='width:3%;'>Month</td>
                            <td class='rcBlue' style='width:5%;'>Previous AE</td>
                            <td class='rcBlue' style='width:5%;'>Client</td>
                            <td class='rcBlue' style='width:3%;'>Agency</td>
                            <td class='rcBlue' style='width:3%;'>Platform</td>
                            <td class='rcBlue' style='width:3%;'>Brand</td>
                            <td class='rcBlue' style='width:3%;'>Feed Code</td>
                            <td class='rcBlue' style='width:3%;'>Order</td>
                            <td class='rcBlue' style='width:3%;'>Pi Number</td>
                            <td class='rcBlue' style='width:3%;'>Property</td>
                            <td class='rcBlue' style='width:5%;'>Core</td>
                            <td class='rcBlue' style='width:5%;'>Current AE</td>
                            <td class='rcBlue' style='width:5%;'>Gross Revenue</td>
                            <td class='rcBlue' style='width:5%;'>Net Revenue</td>                   
                        </tr>

                        <tr style='font-size:14px;'>
                            <td class='darkBlue center'>Total</td>
                            <td class='darkBlue' colspan='13'></td>
                            <td class='darkBlue center' >{{number_format($total['sumGrossRevenue'],0,",",".")}}</td>
                            <td class='darkBlue center' >{{number_format($total['sumNetRevenue'],0,",",".")}}</td>
                        </tr>

                        @for ($m=0; $m <sizeof($mtx) ; $m++)

                        <tr class='center' style='font-size:12px;'>
                            <td class='even'>{{$mtx[$m]['company']}}</td>
                            <td class='even'>{{$mtx[$m]['year']}}</td>
                            <td class='even'>{{$mtx[$m]['month']}}</td>
                            <td class='even'>{{$mtx[$m]['oldRep']}}</td>
                            <td class='even'>{{$mtx[$m]['client']}}</td>
                            <td class='even'>{{$mtx[$m]['agency']}}</td>
                            <td class='even'>{{$mtx[$m]['feedType']}}</td>
                            <td class='even'>{{$mtx[$m]['brand']}}</td>
                            <td class='even'>{{$mtx[$m]['feedCode']}}</td>
                            <td class='even'>{{$mtx[$m]['internalCode']}}</td>
                            <td class='even'>{{$mtx[$m]['piNumber']}}</td>
                            <td class='even'>{{$mtx[$m]['property']}}</td>
                            <td class='even'>{{$mtx[$m]['manager']}}</td>
                            <td class='even'>{{$mtx[$m]['salesRep']}}</td>                        
                            <td class='even'>{{number_format($mtx[$m]['grossRevenue'],0,",",".")}}</td>
                            <td class='even'>{{number_format($mtx[$m]['netRevenue'],0,",",".")}}</td>
                        @endfor
                        </tr>
                    </table>
                @else
                    THERE IS NO DATA TO THE SELECTED YEAR !!!
                @endif
            </div>
        </div>
    </div>
    
    <div id="vlau"></div>

<script type="text/javascript">
    
    $(document).ready(function(){

        ajaxSetup();

        $("#ExcelPDF").change(function(event){
           
            $("#excel").text("Generate Excel");
     
        });

        $('#excel').click(function(event){
            var regionExcel = "<?php echo $regionExcel; ?>";
            var sourceExcel = "<?php echo $sourceExcel; ?>";
            var yearExcel = "<?php echo $yearExcel; ?>";
            var monthExcel = "<?php echo $monthExcel; ?>";
            var salesRepExcel = "<?php echo $salesRepExcel; ?>";
            var managerExcel = "<?php echo $managerExcel; ?>";
            var agencyExcel = "<?php echo $agencyExcel; ?>";
            var clientExcel = "<?php echo $clientExcel; ?>";
            var currencyExcel = "<?php echo $currencyExcel; ?>";
            var valueExcel = "<?php echo $valueExcel; ?>";
            var userRegionExcel = "<?php echo $userRegionExcel; ?>";
            var platformExcel = "<?php echo $platformExcel; ?>";
            var companyExcel = "<?php echo $companyExcel; ?>";

            var div = document.createElement('div');
            var img = document.createElement('img');
            img.src = '/loading_excel.gif';
            div.innerHTML ="Generating File...</br>";
            div.style.cssText = 'position: absolute; left: 0px; top:0px;  margin:0px;        width: 100%;        height: 100%;        display:block;        z-index: 99999;        opacity: 0.9;        -moz-opacity: 0;        filter: alpha(opacity = 45);        background: white;    background-repeat: no-repeat;        background-position:50% 50%;        text-align: center;        overflow: hidden;   font-size:30px;     font-weight: bold;        color: black;        padding-top: 20%';
            div.appendChild(img);
            document.body.appendChild(div);

            var typeExport = $("#ExcelPDF").val();
            //var typeExport = "Excel";
            var auxTitle = "<?php echo $title; ?>";

            var title = "<?php echo $titleExcel; ?>";
            
            $.ajax({
                xhrFields: {
                    responseType: 'blob',
                },
                url: "/generate/excel/viewer/vBase",
                type: "POST",
                data: {regionExcel,sourceExcel,yearExcel,monthExcel,salesRepExcel,managerExcel,agencyExcel,clientExcel,currencyExcel,valueExcel,title, typeExport, auxTitle, userRegionExcel,platformExcel,companyExcel},
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