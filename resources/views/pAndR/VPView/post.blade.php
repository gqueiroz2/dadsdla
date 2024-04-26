@extends('layouts.mirror')
@section('title', 'Advertisers Adjust')
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
                        <td class="even" id='' style='text-align:center; width:3%;'>0
                        </td>   
                    @endfor
                    <td class="darkBlue center" style='width:5%;'>0</td>
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
                        <td class="even" id='' style='text-align:center; width:3%;'>0
                        </td>   
                    @endfor
                    <td class="darkBlue center" style='width:5%;'>0</td>
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

@endsection