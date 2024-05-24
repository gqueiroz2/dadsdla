@extends('layouts.mirror')
@section('title', 'Manager View')
@section('head')	
    <?php include(resource_path('views/auth.php'));
    	$company = array('1','2','3');

        for ($c=0; $c < sizeof($company); $c++) { 
            if ($company[$c] == '1') {
                $color[$c] = 'dc';
                $companyView[$c] = 'DSC';
            }elseif ($company[$c] == '2') {
                $color[$c] = 'sony';
                $companyView[$c] = 'SPT';
            }elseif ($company[$c]) {
                $color[$c] = 'dn';
                $companyView[$c] = 'WM';
            }
        }

     ?>
    <script src="/js/pandr.js"></script>
    
@endsection
@section('content')

	<form method="POST" action="{{ route('VPPost') }}" runat="server"  onsubmit="ShowLoading()">
		@csrf
		<div class="container-fluid">		
			<div class="row">
			 	<div class="col">
                    <label class='labelLeft'><span class="bold">Manager:</span></label>
                    @if($errors->has('manager'))
                        <label style="color: red;">* Required</label>
                    @endif
                        {{$render->manager($user)}}
                </div>
                 <div class="col">
	                    <label class='labelLeft'><span class="bold">Month:</span></label>
	                    @if($errors->has('year'))
	                        <label style="color: red;">* Required</label>
	                    @endif
	                    {{$render->month($months)}}
	                </div>
				<div class="col">
					<label class='labelLeft'> &nbsp; </label>
					<input style="width: 100%;" type="submit" value="Generate" class="btn btn-primary">		
				</div>	
			</div>
		</div>
	</form>

     <div class="container-fluid">
        <div class="row justify-content-end mt-2">
            <div class="col-2">
                <button type="button" id="excel" class="btn btn-primary" style="width: 100%;">
                    Generate Excel
                </button>
            </div>
        </div>
    </div>

	<div id="body" >
		<div class="container-fluid">
            <table style='width: 100%; zoom: 85%;font-size: 16px;'>
                <tr class="center">
                    <td style="width: 7% !important; background-color: white;"> &nbsp; </td>
                </tr>
            </table>
            
            <table style='width: 100%; zoom: 85%;font-size: 22px;'>
                <tr>
                    <th class="newBlue center">{{$managerName}} - {{$monthName[0]}}</th>
                </tr>
            </table>

            <table style='width: 100%; zoom: 85%;font-size: 16px;'>
                <tr class="center">
                    <td style="width: 7% !important; background-color: white;"> &nbsp; </td>
                </tr>
            </table>

        	<table style='width: 100%; zoom: 85%;font-size: 16px;'>
                <input type='hidden' id='clickBoolHeader' value='1'>
                <tr class="center">
                    <td class="darkBlue" style="width:5%;">{{$managerName}}</td>
                     @for($c=0; $c <sizeof($company); $c++)
                        <td class="{{$color[$c]}} " id=''style='text-align:center; width:3%;'>
                            {{$companyView[$c]}}
                        </td>   
                    @endfor
                    <td class='darkBlue' style="width:5%;">Total</td>
                </tr>
                <tr>
                    <td class="even center">TARGET</td>
                     @for($c=0; $c <sizeof($company); $c++)
                        <td class="even" id='' style='text-align:center; width:3%;'>{{number_format($managerTable['managerValues'][$c]['currentTarget'],0,',','.')}}
                        </td>   
                    @endfor
                     <td class="darkBlue center" style='width:5%;'>{{number_format($managerTable['total']['currentTarget'],0,',','.')}}</td>
                </tr>
                <tr>
                    <td class="odd center">FCST - PAY TV</td>
                     @for($c=0; $c <sizeof($company); $c++)
                        <td class="odd" id='' style='text-align:center; width:5%;'>
                           {{number_format($managerTable['managerValues'][$c]['payTvForecast'],0,',','.')}}
                        </td>   
                    @endfor
                    <td class="darkBlue center" style='width:5%;'>{{number_format($managerTable['total']['payTvForecast'],0,',','.')}}</td>
                </tr>
                <tr>
                    <td class="even center">FCST - DIGITAL</td>
                    @for($c=0; $c <sizeof($company); $c++)
                        <td class="even" id='' style='text-align:center; width:5%;'>
                           {{number_format($managerTable['managerValues'][$c]['digitalForecast'],0,',','.')}}
                        </td>   
                    @endfor
                    <td class="darkBlue center" style='width:5%;'>{{number_format($managerTable['total']['digitalForecast'],0,',','.')}}</td>
                </tr>
                <tr>
                    <td class="grey center">TOTAL FCST</td>
                    @for($c=0; $c <sizeof($company); $c++)
                        <td class="grey" id='' style='text-align:center; width:5%;'>
                           {{number_format($managerTable['managerValues'][$c]['forecast'],0,',','.')}}
                        </td>   
                    @endfor
                    <td class="grey center" style='width:5%;'>{{number_format($managerTable['total']['forecast'],0,',','.')}}</td>
                </tr>
                <tr>
                    <td class="odd center">BKGS {{$cYear}} - PAY TV </td>
                    @for($c=0; $c <sizeof($company); $c++)
                        <td class="odd" id='' style='text-align:center; width:3%;'>
                           {{number_format($managerTable['managerValues'][$c]['payTvBookings'],0,',','.')}}
                        </td>   
                    @endfor
                    <td class="darkBlue center" style='width:5%;'>{{number_format($managerTable['total']['payTvBookings'],0,',','.')}}</td>
                </tr>
                <tr>
                    <td class="even center">BKGS {{$cYear}} - DIGITAL </td>
                    @for($c=0; $c <sizeof($company); $c++)
                        <td class="even" id='' style='text-align:center; width:3%;'>
                           {{number_format($managerTable['managerValues'][$c]['digitalBookings'],0,',','.')}}
                        </td>   
                    @endfor
                    <td class="darkBlue center" style='width:5%;'>{{number_format($managerTable['total']['digitalBookings'],0,',','.')}}</td>
                </tr>
                <tr>
                    <td class="grey center">TOTAL BKGS</td>
                    @for($c=0; $c <sizeof($company); $c++)
                        <td class="grey" id='' style='text-align:center; width:3%;'>
                           {{number_format($managerTable['managerValues'][$c]['bookings'],0,',','.')}}
                        </td>   
                    @endfor
                    <td class="grey center" style='width:5%;'>{{number_format($managerTable['total']['bookings'],0,',','.')}}</td>
                </tr>
                <tr>
                    <td class="newBlue center">BKGS PENDING</td>
                    @for($c=0; $c <sizeof($company); $c++)
                        <td class="newBlue" id='' style='text-align:center; width:3%;'>
                           {{number_format($managerTable['managerValues'][$c]['pending'],0,',','.')}}
                        </td>   
                    @endfor
                    <td class="newBlue center" style='width:5%;'>{{number_format($managerTable['total']['pending'],0,',','.')}}</td>
                </tr>
                <tr>
                    <td class="even center" style="border-style:solid; border-color:black; border-width: 0px 0px 1px 0px;">BKGS {{$pYear}}</td>
                    @for($c=0; $c <sizeof($company); $c++)
                        <td class="even" id='' style='text-align:center; border-bottom: 1pt solid black;  width:3%;'>
                           {{number_format($managerTable['managerValues'][$c]['previousBookings'],0,',','.')}}
                        </td>   
                    @endfor
                    <td class="darkBlue center" style='width:5%; border-style:solid; border-color:black; border-width: 0px 0px 1px 0px;'>{{number_format($managerTable['total']['previousBookings'],0,',','.')}}</td>
                </tr>
            </table>
            
            <table style='width: 100%; zoom: 85%;font-size: 16px;'>
                <tr class="center">
                    <td style="width: 7% !important; background-color: white;"> &nbsp; </td>
                </tr>
            </table>
            <!-- START OF REPS TABLE -->
        	@for($r = 0; $r <sizeof($repsTable['repValues']);$r++)
            <table style='width: 100%; zoom: 85%;font-size: 16px;'>
                <input type='hidden' id='clickBoolHeader' value='1'>
                <tr class="center">
                    <td class="darkBlue" style="width:5%;">{{$repsTable['repInfo'][$r]['salesRep']}}</td>
                     @for($c=0; $c <sizeof($company); $c++)
                        <td class="{{$color[$c]}} " id=''style='text-align:center; width:3%;'>
                            {{$companyView[$c]}}
                        </td>   
                    @endfor
                    <td class='darkBlue' style="width:5%;">Total</td>
                </tr>
                <tr>
                    <td class="even center">TARGET</td>
                     @for($c=0; $c <sizeof($company); $c++)
                        <td class="even" id='' style='text-align:center; width:3%;'> {{number_format($repsTable['repValues'][$r][$c]['currentTarget'],0,',','.')}}
                        </td>   
                    @endfor
                     <td class="darkBlue center" style='width:5%;'>{{number_format($repsTable['total'][$r]['currentTarget'],0,',','.')}}</td>
                </tr>
                <tr>
                    <td class="odd center">FCST - PAY TV</td>
                     @for($c=0; $c <sizeof($company); $c++)
                        <td class="odd" id='' style='text-align:center; width:5%;'>
                           {{number_format($repsTable['repValues'][$r][$c]['payTvForecast'],0,',','.')}}
                        </td>   
                    @endfor
                    <td class="darkBlue center" style='width:5%;'>{{number_format($repsTable['total'][$r]['payTvForecast'],0,',','.')}}</td>
                </tr>
                <tr>
                    <td class="even center">FCST - DIGITAL</td>
                    @for($c=0; $c <sizeof($company); $c++)
                        <td class="even" id='' style='text-align:center; width:5%;'>
                           {{number_format($repsTable['repValues'][$r][$c]['digitalForecast'],0,',','.')}}
                        </td>   
                    @endfor
                    <td class="darkBlue center" style='width:5%;'>{{number_format($repsTable['total'][$r]['digitalForecast'],0,',','.')}}</td>
                </tr>
                <tr>
                    <td class="grey center">TOTAL FCST</td>
                    @for($c=0; $c <sizeof($company); $c++)
                        <td class="grey" id='' style='text-align:center; width:5%;'>
                           {{number_format($repsTable['repValues'][$r][$c]['forecast'],0,',','.')}}
                        </td>   
                    @endfor
                    <td class="grey center" style='width:5%;'>{{number_format($repsTable['total'][$r]['forecast'],0,',','.')}}</td>
                </tr>
                <tr>
                    <td class="odd center">BKGS {{$cYear}} - PAY TV </td>
                    @for($c=0; $c <sizeof($company); $c++)
                        <td class="odd" id='' style='text-align:center; width:3%;'>
                           {{number_format($repsTable['repValues'][$r][$c]['payTvBookings'],0,',','.')}}
                        </td>   
                    @endfor
                    <td class="darkBlue center" style='width:5%;'>{{number_format($repsTable['total'][$r]['payTvBookings'],0,',','.')}}</td>
                </tr>
                <tr>
                    <td class="even center">BKGS {{$cYear}} - DIGITAL </td>
                    @for($c=0; $c <sizeof($company); $c++)
                        <td class="even" id='' style='text-align:center; width:3%;'>
                           {{number_format($repsTable['repValues'][$r][$c]['digitalBookings'],0,',','.')}}
                        </td>   
                    @endfor
                    <td class="darkBlue center" style='width:5%;'>{{number_format($repsTable['total'][$r]['digitalBookings'],0,',','.')}}</td>
                </tr>
                <tr>
                    <td class="grey center">TOTAL BKGS</td>
                    @for($c=0; $c <sizeof($company); $c++)
                        <td class="grey" id='' style='text-align:center; width:3%;'>
                           {{number_format($repsTable['repValues'][$r][$c]['bookings'],0,',','.')}}
                        </td>   
                    @endfor
                    <td class="grey center" style='width:5%;'>{{number_format($repsTable['total'][$r]['bookings'],0,',','.')}}</td>
                </tr>
                <tr>
                    <td class="newBlue center">BKGS PENDING</td>
                    @for($c=0; $c <sizeof($company); $c++)
                        <td class="newBlue" id='' style='text-align:center; width:3%;'>
                           {{number_format($repsTable['repValues'][$r][$c]['pending'],0,',','.')}}
                        </td>   
                    @endfor
                    <td class="newBlue center" style='width:5%;'>{{number_format($repsTable['total'][$r]['pending'],0,',','.')}}</td>
                </tr>
                <tr>
                    <td class="even center" style="border-style:solid; border-color:black; border-width: 0px 0px 1px 0px;">BKGS {{$pYear}}</td>
                    @for($c=0; $c <sizeof($company); $c++)
                        <td class="even" id='' style='text-align:center; border-bottom: 1pt solid black;  width:3%;'>
                           {{number_format($repsTable['repValues'][$r][$c]['previousBookings'],0,',','.')}}
                        </td>   
                    @endfor
                    <td class="darkBlue center" style='width:5%; border-style:solid; border-color:black; border-width: 0px 0px 1px 0px;'>{{number_format($repsTable['total'][$r]['previousBookings'],0,',','.')}}</td>
                </tr>
            </table>
            <table style='width: 100%; zoom: 85%;font-size: 16px;'>
                <tr class="center">
                    <td style="width: 7% !important; background-color: white;"> &nbsp; </td>
                </tr>
            </table>
            @endfor
		</div>
	</div>


<!-- javascript to make the excel export -->
<script type="text/javascript">
            
    $(document).ready(function(){

        ajaxSetup();

        $('#excel').click(function(event){

            var month = "<?php echo $month; ?>";
            var manager = "<?php echo base64_encode(json_encode($manager)); ?>";
            var user = "<?php echo base64_encode(json_encode($user)); ?>";

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
                url: "/generate/excel/pandr/vpView",
                type: "POST",
                data: {month,manager,user,title, typeExport, auxTitle},
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