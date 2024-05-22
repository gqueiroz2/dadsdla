@extends('layouts.mirror')
@section('title', 'Forecast Cicle')
@section('head')    
    <?php include(resource_path('views/auth.php')); 

    $month = array('Jan','Feb','Mar','Q1','Apr','May','Jun','Q2','Jul','Aug','Sep','Q3','Oct','Nov','Dec','Q4');
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
    

    <form method="POST" action="{{ route('AEPost') }}" runat="server"  onsubmit="ShowLoading()" onkeydown="return oddt.key != 'Enter';">
        @csrf
        <div class="container-fluid">       
            <div class="row">
                <div class="col" style="display:none;">
                    <label class='labelLeft'><span class="bold">Region:</span></label>
                    @if($errors->has('region'))
                        <label style="color: red;">* Required</label>
                    @endif
                    {{$render->regionFiltered($region, $regionID, $special )}}
                </div>
                <div class="col" style="display:none;">
                    <label class='labelLeft'><span class="bold">Year:</span></label>
                    @if($errors->has('year'))
                        <label style="color: red;">* Required</label>
                    @endif
                    {{$render->year()}}
                </div>
                <div class="col">
                    <label class='labelLeft'><span class="bold">Sales Rep:</span></label>
                    @if($errors->has('salesRep'))
                        <label style="color: red;">* Required</label>
                    @endif
                    {{$render->salesRep2()}}
                </div>
                <div class="col" style="display: none;">
                    <label class='labelLeft'><span class="bold">Currency:</span></label>
                    @if($errors->has('currency'))
                        <label style="color: red;">* Required</label>
                    @endif
                    {{$render->currency($currency)}}
                </div>  
                <div class="col" style="display: none;">
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
        </div>
    </form>
    <div class="container-fluid">
        <div class="row justify-content-end mt-2">
            <div class="col-2" style="color: #0070c0;font-size: 25px;">
                Forecast Cicle
            </div>

            <div class="col-3">
                <button type="button" id="excel" class="btn btn-primary" style="width: 100%">
                    Generate Excel
                </button>               
            </div>            
        </div>
    </div>

    <br>
    <div class="container-fluid" id="body">
        <form method="POST" action="{{ route('AESave') }}" runat="server"  onsubmit="ShowLoading()" id="forecastForm">
        @csrf

            <div class="row justify-content-end">
                <div class="col">
                    <div class="container-fluid">
                        <div class="row justify-content-end">
                            <div class="col-2">
                                <label class="labelLeft"><span class="bold"> &nbsp; </span> </label>
                                <input type="submit" id="button" value="Save" class="btn btn-primary" style="width: 100%">
                            </div>
                        </div>
                    </div>
                </div>  
            </div>
            <div class="row justify-content-end">               
                <div class="col" >
                    <div class="container-fluid">

                        <table style='width: 100%; zoom: 85%;font-size: 16px;'>
                            <tr class="center">
                                <td style="width: 7% !important; background-color: white;"> &nbsp; </td>
                            </tr>
                            
                        </table>
                        <!--START OF SALES REP TABLE-->
                         <table style='width: 100%; zoom: 85%;font-size: 22px;'>
                            <tr>
                                <th class="newBlue center">{{$salesRepName[0]['salesRep']}} - {{$currency}}/{{strtoupper($value)}}</th>
                            </tr>
                        </table>

                        <table style='width: 100%; zoom: 85%;font-size: 16px;'>
                            <tr class="center">
                                <td style="width: 7% !important; background-color: white;"> &nbsp; </td>
                            </tr>
                        </table>

                        <table style='width: 100%; zoom: 85%;font-size: 14px;'>
                            <input type='hidden' id='clickBoolHeader' value='1'>
                            <tr class="center">
                                <td colspan="2" class="darkBlue">{{$salesRepName[0]['abName']}}</td>
                                @for($m=0; $m <sizeof($month) ; $m++)
                                    @if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) 
                                        <td class='quarter' style='width:5%;'>{{$month[$m]}}</td>
                                    @else
                                        <td class='smBlue' style='width:5%;'>{{$month[$m]}}</td>
                                    @endif
                                @endfor
                                <td class='darkBlue' style='width:5%;'>Total</td>
                            </tr>
                            <tr>
                                <td class='grey clickBoolHeader' id='' rowspan='9' style='text-align:center; border-bottom: 1pt solid black;  width:3%;'>
                                    <span style='font-size:12px;'> WBD</span>
                                </td> 
                                <td class="odd center">TARGET</td>
                                 @for($m=0; $m <sizeof($month); $m++)
                                    @if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) 
                                        <td class="quarter center" style='width:5%; color: black;'>{{number_format($aeTable['total']['currentTarget'][$m],0,',','.')}}</td>
                                    @else
                                        <td class="odd center" style='width:5%;'>{{number_format($aeTable['total']['currentTarget'][$m],0,',','.')}}</td>
                                    @endif
                                @endfor
                                <td class="darkBlue center" style='width:5%;'>{{number_format($aeTable['total']['currentTarget'][$m],0,',','.')}}</td>
                            </tr>
                            <tr>
                                <td class="odd center">FCST - PAY TV</td>
                                 @for($m=0; $m <sizeof($month); $m++)
                                    @if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) 
                                        <td class="quarter center" style='width:5%; color: black;'>{{number_format($aeTable['total']['payTvForecast'][$m],0,',','.')}}</td>
                                    @else
                                        <td class="odd center" style='width:5%;'>{{number_format($aeTable['total']['payTvForecast'][$m],0,',','.')}}</td>
                                    @endif
                                @endfor
                                <td class="darkBlue center" style='width:5%;'>{{number_format($aeTable['total']['payTvForecast'][$m],0,',','.')}}</td>
                            </tr>
                            <tr>
                                <td class="odd center">FCST - DIGITAL</td>
                                 @for($m=0; $m <sizeof($month); $m++)
                                    @if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) 
                                        <td class="quarter center" style='width:5%; color: black;'>{{number_format($aeTable['total']['digitalForecast'][$m],0,',','.')}}</td>
                                    @else
                                        <td class="odd center" style='width:5%;'>{{number_format($aeTable['total']['digitalForecast'][$m],0,',','.')}}</td>
                                    @endif
                                @endfor
                                <td class="darkBlue center" style='width:5%;'>{{number_format($aeTable['total']['digitalForecast'][$m],0,',','.')}}</td>
                            </tr>
                            <tr>
                                <td class="lightGrey center">TOTAL FCST</td>
                                 @for($m=0; $m <sizeof($month); $m++)
                                    @if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) 
                                        <td class="quarter center" style='width:5%; color: black;'>{{number_format($aeTable['total']['forecast'][$m],0,',','.')}}</td>
                                    @else
                                        <td class="lightGrey center" style='width:5%;'>{{number_format($aeTable['total']['forecast'][$m],0,',','.')}}</td>
                                    @endif
                                @endfor
                                <td class="darkBlue center" style='width:5%;'>{{number_format($aeTable['total']['forecast'][$m],0,',','.')}}</td>
                            </tr>
                            <tr>
                                <td class="odd center">BKGS {{$cYear}} - PAY TV </td>
                                 @for($m=0; $m <sizeof($month); $m++)
                                    @if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) 
                                        <td class="quarter center" style='width:5%; color: black;'>{{number_format($aeTable['total']['currentPayTvBookings'][$m],0,',','.')}}</td>
                                    @else
                                        <td class="odd center" style='width:5%;'>{{number_format($aeTable['total']['currentPayTvBookings'][$m],0,',','.')}}</td>
                                    @endif
                                @endfor
                                <td class="darkBlue center" style='width:5%;'>{{number_format($aeTable['total']['currentPayTvBookings'][$m],0,',','.')}}</td>
                            </tr>
                            <tr>
                                <td class="odd center">BKGS {{$cYear}} - DIGITAL </td>
                                 @for($m=0; $m <sizeof($month); $m++)
                                    @if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) 
                                        <td class="quarter center" style='width:5%; color: black;'>{{number_format($aeTable['total']['currentDigitalBookings'][$m],0,',','.')}}</td>
                                    @else
                                        <td class="odd center" style='width:5%;'>{{number_format($aeTable['total']['currentDigitalBookings'][$m],0,',','.')}}</td>
                                    @endif
                                @endfor
                                <td class="darkBlue center" style='width:5%;'>{{number_format($aeTable['total']['currentDigitalBookings'][$m],0,',','.')}}</td>
                            </tr>
                            <tr>
                                <td class="lightGrey center">TOTAL BKGS</td>
                                 @for($m=0; $m <sizeof($month); $m++)
                                    @if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) 
                                        <td class="quarter center" style='width:5%; color: black;'>{{number_format($aeTable['total']['currentBookings'][$m],0,',','.')}}</td>
                                    @else
                                        <td class="lightGrey center" style='width:5%;'>{{number_format($aeTable['total']['currentBookings'][$m],0,',','.')}}</td>
                                    @endif
                                @endfor
                                <td class="darkBlue center" style='width:5%;'>{{number_format($aeTable['total']['currentBookings'][$m],0,',','.')}}</td>
                            </tr>
                            <tr>
                                <td class="newBlue center">BKGS PENDINGS</td>
                                 @for($m=0; $m <sizeof($month); $m++)
                                    <?php
                                        $pending[$m] =  ($aeTable['total']['forecast'][$m] - $aeTable['total']['currentBookings'][$m]); 
                                        if($pending[$m] < 0){
                                            $pending[$m] = 0;
                                        }
                                    ?>
                                    @if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) 
                                        <td class="quarter center" style='width:5%; color: black;'>{{number_format($pending[$m],0,',','.')}}</td>
                                    @else
                                        <td class="newBlue center" style='width:5%;'>{{number_format($pending[$m],0,',','.')}}</td>
                                    @endif
                                @endfor
                                <?php 
                                    $ttPending = 0;
                                    $ttPending = $pending[3] + $pending[7] + $pending[11]+ $pending[15];  
                                ?>
                                 <td class="darkBlue center" style='width:5%;'>{{number_format($ttPending,0,',','.')}}</td>
                               
                            </tr>
                            <tr>
                                <td class="odd center" style="border-style:solid; border-color:black; border-width: 0px 0px 1px 0px;">BKGS {{$pYear}}</td>
                                 @for($m=0; $m <sizeof($month); $m++)
                                    @if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) 
                                        <td class="quarter center" style='width:5%; color: black; border-style:solid; border-color:black; border-width: 0px 0px 1px 0px;'>{{number_format($aeTable['total']['previousBookings'][$m],0,',','.')}}</td>
                                    @else
                                        <td class="odd center" style='width:5%; border-style:solid; border-color:black; border-width: 0px 0px 1px 0px;'>{{number_format($aeTable['total']['previousBookings'][$m],0,',','.')}}</td>
                                    @endif
                                @endfor
                                <td class="darkBlue center" style='width:5%; border-style:solid; border-color:black; border-width: 0px 0px 1px 0px;'>{{number_format($aeTable['total']['previousBookings'][$m],0,',','.')}}</td>
                            </tr>

                            <!--PART OF TOTAL OF WHICH COMPANY PART -->

                            @for($c=0; $c <sizeof($company); $c++)
                                <tr class="clickLoopHeader">
                                    <td class="{{$color[$c]}} " id='' rowspan='9' style='text-align:center; border-bottom: 1pt solid black;  width:3%;'>
                                        <span style='font-size:12px;'> {{$companyView[$c]}}</span>
                                    </td> 
                                    <td class="odd center">TARGET</td>
                                     @for($m=0; $m <sizeof($month); $m++)
                                        @if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) 
                                            <td class="quarter center" style='width:5%; color: black;'>{{number_format($aeTable['companyValues'][$c]['currentTarget'][$m],0,',','.')}}</td>
                                        @else
                                            <td class="odd center" style='width:5%;'>{{number_format($aeTable['companyValues'][$c]['currentTarget'][$m],0,',','.')}}</td>
                                        @endif
                                    @endfor
                                    <td class="darkBlue center" style='width:5%;'>{{number_format($aeTable['companyValues'][$c]['currentTarget'][$m],0,',','.')}}</td>
                                </tr>
                                <tr class="clickLoopHeader">
                                    <td class="odd center">FCST - PAY TV</td>
                                     @for($m=0; $m <sizeof($month); $m++)
                                        @if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) 
                                            <td class="quarter center" style='width:5%; color: black;'>{{number_format($aeTable['companyValues'][$c]['payTvForecast'][$m],0,',','.')}}</td>
                                        @else
                                            <td class="odd center" style='width:5%;'>{{number_format($aeTable['companyValues'][$c]['payTvForecast'][$m],0,',','.')}}</td>
                                        @endif
                                    @endfor
                                    <td class="darkBlue center" style='width:5%;'>{{number_format($aeTable['companyValues'][$c]['payTvForecast'][$m],0,',','.')}}</td>
                                </tr>
                                <tr class="clickLoopHeader">
                                    <td class="odd center">FCST - DIGITAL</td>
                                     @for($m=0; $m <sizeof($month); $m++)
                                        @if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) 
                                            <td class="quarter center" style='width:5%; color: black;'>{{number_format($aeTable['companyValues'][$c]['digitalForecast'][$m],0,',','.')}}</td>
                                        @else
                                            <td class="odd center" style='width:5%;'>{{number_format($aeTable['companyValues'][$c]['digitalForecast'][$m],0,',','.')}}</td>
                                        @endif
                                    @endfor
                                    <td class="darkBlue center" style='width:5%;'>{{number_format($aeTable['companyValues'][$c]['digitalForecast'][$m],0,',','.')}}</td>
                                </tr>
                                <tr class="clickLoopHeader">
                                    <td class="lightGrey center">TOTAL FCST</td>
                                     @for($m=0; $m <sizeof($month); $m++)
                                        @if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) 
                                            <td class="quarter center" style='width:5%; color: black;'>{{number_format($aeTable['companyValues'][$c]['forecast'][$m],0,',','.')}}</td>
                                        @else
                                            <td class="lightGrey center" style='width:5%;'>{{number_format($aeTable['companyValues'][$c]['forecast'][$m],0,',','.')}}</td>
                                        @endif
                                    @endfor
                                    <td class="darkBlue center" style='width:5%;'>{{number_format($aeTable['companyValues'][$c]['forecast'][$m],0,',','.')}}</td>
                                </tr>
                                <tr class="clickLoopHeader">
                                    <td class="odd center">BKGS {{$cYear}} - PAY TV </td>
                                     @for($m=0; $m <sizeof($month); $m++)
                                        @if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) 
                                            <td class="quarter center" style='width:5%; color: black;'>{{number_format($aeTable['companyValues'][$c]['currentPayTvBookings'][$m],0,',','.')}}</td>
                                        @else
                                            <td class="odd center" style='width:5%;'>{{number_format($aeTable['companyValues'][$c]['currentPayTvBookings'][$m],0,',','.')}}</td>
                                        @endif
                                    @endfor
                                    <td class="darkBlue center" style='width:5%;'>{{number_format($aeTable['companyValues'][$c]['currentPayTvBookings'][$m],0,',','.')}}</td>
                                </tr>
                                <tr class="clickLoopHeader">
                                    <td class="odd center">BKGS {{$cYear}} - DIGITAL </td>
                                     @for($m=0; $m <sizeof($month); $m++)
                                        @if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) 
                                            <td class="quarter center" style='width:5%; color: black;'>{{number_format($aeTable['companyValues'][$c]['currentDigitalBookings'][$m],0,',','.')}}</td>
                                        @else
                                            <td class="odd center" style='width:5%;'>{{number_format($aeTable['companyValues'][$c]['currentDigitalBookings'][$m],0,',','.')}}</td>
                                        @endif
                                    @endfor
                                    <td class="darkBlue center" style='width:5%;'>{{number_format($aeTable['companyValues'][$c]['currentDigitalBookings'][$m],0,',','.')}}</td>
                                </tr>
                                <tr class="clickLoopHeader">
                                    <td class="lightGrey center">TOTAL BKGS </td>
                                     @for($m=0; $m <sizeof($month); $m++)
                                        @if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) 
                                            <td class="quarter center" style='width:5%; color: black;'>{{number_format($aeTable['companyValues'][$c]['currentBookings'][$m],0,',','.')}}</td>
                                        @else
                                            <td class="lightGrey center" style='width:5%;'>{{number_format($aeTable['companyValues'][$c]['currentBookings'][$m],0,',','.')}}</td>
                                        @endif
                                    @endfor
                                    <td class="darkBlue center" style='width:5%;'>{{number_format($aeTable['companyValues'][$c]['currentBookings'][$m],0,',','.')}}</td>
                                </tr>
                                <tr class="clickLoopHeader">
                                    <td class="newBlue center">PENDING BKGS </td>
                                     @for($m=0; $m <sizeof($month); $m++)
                                     <?php
                                        $companyPending[$c][$m] =  ($aeTable['companyValues'][$c]['forecast'][$m]) - ($aeTable['companyValues'][$c]['currentBookings'][$m]);
                                        if($companyPending[$c][$m] < 0){
                                            $companyPending[$c][$m] = 0;
                                        }
                                    ?>

                                        @if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) 
                                            <td class="quarter center" style='width:5%; color: black;'>{{number_format($companyPending[$c][$m],0,',','.')}}</td>
                                        @else
                                            <td class="newBlue center" style='width:5%;'>{{number_format($companyPending[$c][$m],0,',','.')}}</td>
                                        @endif
                                    @endfor
                                     <?php 
                                        $ttPendingC = 0;
                                        $ttPendingC = $companyPending[$c][3] + $companyPending[$c][7] + $companyPending[$c][11]+ $companyPending[$c][15];  
                                     ?>
                                 <td class="darkBlue center" style='width:5%;'>{{number_format($ttPendingC,0,',','.')}}</td>
                                    
                                </tr>
                                <tr class="clickLoopHeader">
                                    <td class="odd center" style="border-style:solid; border-color:black; border-width: 0px 0px 1px 0px;">BKGS {{$pYear}}</td>
                                     @for($m=0; $m <sizeof($month); $m++)
                                        @if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) 
                                            <td class="quarter center" style='width:5%; color: black; border-style:solid; border-color:black; border-width: 0px 0px 1px 0px;'>{{number_format($aeTable['companyValues'][$c]['previousBookings'][$m],0,',','.')}}</td>
                                        @else
                                            <td class="odd center" style='width:5%; border-style:solid; border-color:black; border-width: 0px 0px 1px 0px;'>{{number_format($aeTable['companyValues'][$c]['previousBookings'][$m],0,',','.')}}</td>
                                        @endif
                                    @endfor
                                    <td class="darkBlue center" style='width:5%; border-style:solid; border-color:black; border-width: 0px 0px 1px 0px;'>{{number_format($aeTable['companyValues'][$c]['previousBookings'][$m],0,',','.')}}</td>
                                </tr>
                            @endfor
                        </table>

                        <!--START OF CLIENTS TABLE-->
                        @for($a=0; $a <sizeof($clientsTable['clientInfo']) ; $a++)
                            <input type='hidden' readonly='true' type="text" name="currency" id="currency" style="background-color:transparent; border:none; font-weight:bold; text-align:center;" value="{{$currencyID}}">
                            <input type='hidden' readonly='true' type="text" name="value" id="value" style="background-color:transparent; border:none; font-weight:bold; text-align:center;" value="{{$value}}">
                            <input type='hidden' readonly='true' type="text" name="salesRep" id="salesRep" style="background-color:transparent; border:none; font-weight:bold; text-align:center;" value="{{$salesRepID}}">
                            <input type='hidden' readonly='true' type="text" name="client-{{$a}}" id="client-{{$a}}" style="background-color:transparent; border:none; font-weight:bold; text-align:center;" value="{{$clientsTable['clientInfo'][$a]['clientID']}}">
                            <input type='hidden' readonly='true' type="text" name="agency-{{$a}}" id="agency-{{$a}}" style="background-color:transparent; border:none; font-weight:bold; text-align:center;" value="{{$clientsTable['clientInfo'][$a]['agencyID']}}">
                            <table style='width: 100%; zoom: 85%;font-size: 14px;'>
                                <?php if($clientsTable['clientInfo'][$a]['probability'][0]['probability'] == null){
                                        $probability[$a] = intval(100);
                                    }else{
                                        $probability[$a] = $clientsTable['clientInfo'][$a]['probability'][0]['probability'];
                                    }
                                ?>
                                <tr style="display: inline;">
                                    <td style="border-style:solid; border-color:black; border-width: 0px 0px 0px 0px;">Probability:<input placeholder="0" pattern="^\$\d{3.3}(.\d{3})*(\,\d+)?" data-type="currency" type="text" name="probability-{{$a}}" id="probability-{{$a}}" style=" width:70%; background-color:transparent; border:0px; font-weight:bold; text-align:center;" value={{number_format($probability[$a])}}>%</td>    
                                </tr>
                                <input type='hidden' id='clickBool-{{$a}}' value='1'>
                                <tr class="center">
                                    <td colspan="2" class="darkBlue">{{$clientsTable['clientInfo'][$a]['clientName']}} - {{$clientsTable['clientInfo'][$a]['agencyName']}}</td>
                                    @for($m=0; $m <sizeof($month) ; $m++)
                                        @if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) 
                                            <td class='quarter' style='width:5%;'>{{$month[$m]}}</td>
                                        @else
                                            <td class='smBlue' style='width:5%;'>{{$month[$m]}}</td>
                                        @endif
                                    @endfor
                                    <td class='darkBlue' style='width:5%;'>Total</td>
                                </tr>
                                <tr>
                                    <td class='grey clickBool-{{$a}}' id='' rowspan='8' style='text-align:center; border-bottom: 1pt solid black;  width:3%;'>
                                        <span style='font-size:12px;'> WBD</span>
                                    </td>                                     
                                    <td class="odd center">FCST - PAY TV</td>
                                     @for($m=0; $m <sizeof($month); $m++)
                                        @if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) 
                                            <td class="quarter center" style='width:5%; color: black;'>{{number_format($clientsTable['total'][$a]['payTvForecast'][$m],0,',','.')}}</td>
                                        @else
                                            <td class="odd center" style='width:5%;'>{{number_format($clientsTable['total'][$a]['payTvForecast'][$m],0,',','.')}}</td>
                                        @endif
                                    @endfor
                                    <td class="darkBlue center" style='width:5%;'>{{number_format($clientsTable['total'][$a]['payTvForecast'][$m],0,',','.')}}</td>
                                </tr>
                                <tr>
                                    <td class="odd center">FCST - DIGITAL</td>
                                     @for($m=0; $m <sizeof($month); $m++)
                                        @if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) 
                                            <td class="quarter center" style='width:5%; color: black;'>{{number_format($clientsTable['total'][$a]['digitalForecast'][$m],0,',','.')}}</td>
                                        @else
                                            <td class="odd center" style='width:5%;'>{{number_format($clientsTable['total'][$a]['digitalForecast'][$m],0,',','.')}}</td>
                                        @endif
                                    @endfor
                                    <td class="darkBlue center" style='width:5%;'>{{number_format($clientsTable['total'][$a]['digitalForecast'][$m],0,',','.')}}</td>
                                </tr>
                                <tr>
                                    <td class="lightGrey center">TOTAL FCST</td>
                                     @for($m=0; $m <sizeof($month); $m++)
                                        @if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) 
                                            <td class="quarter center" style='width:5%; color: black;'>{{number_format($clientsTable['total'][$a]['forecast'][$m],0,',','.')}}</td>
                                        @else
                                            <td class="lightGrey center" style='width:5%;'>{{number_format($clientsTable['total'][$a]['forecast'][$m],0,',','.')}}</td>
                                        @endif
                                    @endfor
                                    <td class="darkBlue center" style='width:5%;'>{{number_format($clientsTable['total'][$a]['forecast'][$m],0,',','.')}}</td>
                                </tr>
                                <tr>
                                    <td class="odd center">BKGS {{$cYear}} - PAY TV</td>
                                     @for($m=0; $m <sizeof($month); $m++)
                                        @if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) 
                                            <td class="quarter center" style='width:5%; color: black;'>{{number_format($clientsTable['total'][$a]['currentPayTvBookings'][$m],0,',','.')}}</td>
                                        @else
                                            <td class="odd center" style='width:5%;'>{{number_format($clientsTable['total'][$a]['currentPayTvBookings'][$m],0,',','.')}}</td>
                                        @endif
                                    @endfor
                                    <td class="darkBlue center" style='width:5%;'>{{number_format($clientsTable['total'][$a]['currentPayTvBookings'][$m],0,',','.')}}</td>
                                </tr>
                                
                                <tr>
                                    <td class="odd center">BKGS {{$cYear}} - DIGITAL</td>
                                     @for($m=0; $m <sizeof($month); $m++)
                                        @if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) 
                                            <td class="quarter center" style='width:5%; color: black;'>{{number_format($clientsTable['total'][$a]['currentDigitalBookings'][$m],0,',','.')}}</td>
                                        @else
                                            <td class="odd center" style='width:5%;'>{{number_format($clientsTable['total'][$a]['currentDigitalBookings'][$m],0,',','.')}}</td>
                                        @endif
                                    @endfor
                                    <td class="darkBlue center" style='width:5%;'>{{number_format($clientsTable['total'][$a]['currentDigitalBookings'][$m],0,',','.')}}</td>
                                </tr>
                                <tr>
                                    <td class="lightGrey center">TOTAL BKGS</td>
                                     @for($m=0; $m <sizeof($month); $m++)
                                        @if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) 
                                            <td class="quarter center" style='width:5%; color: black;'>{{number_format($clientsTable['total'][$a]['currentBookings'][$m],0,',','.')}}</td>
                                        @else
                                            <td class="lightGrey center" style='width:5%;'>{{number_format($clientsTable['total'][$a]['currentBookings'][$m],0,',','.')}}</td>
                                        @endif
                                    @endfor
                                    <td class="darkBlue center" style='width:5%;'>{{number_format($clientsTable['total'][$a]['currentBookings'][$m],0,',','.')}}</td>
                                </tr>
                                <tr>
                                    <td class="newBlue center">PENDING BKGS</td>
                                     @for($m=0; $m <sizeof($month); $m++)
                                        @if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) 
                                            <td class="quarter center" style='width:5%; color: black;'>{{number_format($clientsTable['total'][$a]['pending'][$m],0,',','.')}}</td>
                                        @else
                                            <td class="newBlue center" style='width:5%;'>{{number_format($clientsTable['total'][$a]['pending'][$m],0,',','.')}}</td>
                                        @endif
                                    @endfor
                                    <td class="darkBlue center" style='width:5%;'>{{number_format($clientsTable['total'][$a]['pending'][$m],0,',','.')}}</td>
                                </tr>
                                <tr>
                                    <td class="odd center" style="border-style:solid; border-color:black; border-width: 0px 0px 1px 0px;">BKGS {{$pYear}}</td>
                                     @for($m=0; $m <sizeof($month); $m++)
                                        @if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) 
                                            <td class="quarter center" style='width:5%; color: black; border-style:solid; border-color:black; border-width: 0px 0px 1px 0px;'>{{number_format($clientsTable['total'][$a]['previousBookings'][$m],0,',','.')}}</td>
                                        @else
                                            <td class="odd center" style='width:5%; border-style:solid; border-color:black; border-width: 0px 0px 1px 0px;'>{{number_format($clientsTable['total'][$a]['previousBookings'][$m],0,',','.')}}</td>
                                        @endif
                                    @endfor
                                    <td class="darkBlue center" style='width:5%; border-style:solid; border-color:black; border-width: 0px 0px 1px 0px;'>{{number_format($clientsTable['total'][$a]['previousBookings'][$m],0,',','.')}}</td>
                                </tr>

                                <!--PART OF WHICH COMPANY BY CLIENT -->

                                @for($c=0; $c <sizeof($company); $c++)
                                    <tr class="clickLoop-{{$a}}">
                                        <td class="{{$color[$c]}} " id='' rowspan='8' style='text-align:center; border-bottom: 1pt solid black;  width:3%;'>
                                            <span style='font-size:12px;'> {{$companyView[$c]}}</span>
                                        </td>                                         
                                        <td class="odd center">FCST - PAY TV</td>
                                         @for($m=0; $m <sizeof($month); $m++)
                                            @if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) 
                                                <td class="quarter center" style="width:3%; color: black;">{{number_format($clientsTable['companyValues'][$a][$c]['payTvForecast'][$m])}}</td>
                                            @else
                                                @if($m >= date('n')+$num)
                                                    <td class="odd center" style="width:3%;"><input style="color: red; width:100%; background-color:transparent; border:none; font-weight:bold; text-align:center;" placeholder="0" pattern="^\$\d{3.3}(.\d{3})*(\,\d+)?" data-type="currency" type="text" name="payTvForecast-{{$a}}-{{$c}}-{{$month[$m]}}" id="payTvForecast-{{$a}}-{{$c}}-{{$month[$m]}}" value="{{number_format($clientsTable['companyValues'][$a][$c]['payTvForecast'][$m],0,',','.')}}"></td>
                                                @else
                                                    <td class="odd center" style="width:3%;">{{number_format($clientsTable['companyValues'][$a][$c]['payTvForecast'][$m],0,',','.')}}</td>
                                                @endif
                                            @endif
                                        @endfor
                                        
                                        <td class="darkBlue center" style='width:5%;'>{{number_format($clientsTable['companyValues'][$a][$c]['payTvForecast'][$m],0,',','.')}}</td>
                                    </tr>
                                    <tr class="clickLoop-{{$a}}">
                                        <td class="odd center">FCST - DIGITAL</td>
                                         @for($m=0; $m <sizeof($month); $m++)
                                            @if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) 
                                                <td class="quarter center" style="width:3%; color: black;">{{number_format($clientsTable['companyValues'][$a][$c]['digitalForecast'][$m],0,',','.')}}</td>
                                            @else
                                                 @if($m >= date('n')+$num)
                                                    <td class="odd center" style="width:3%;"><input style="color: red; width:100%; background-color:transparent; border:none; font-weight:bold; text-align:center;" placeholder="0" pattern="^\$\d{3.3}(.\d{3})*(\,\d+)?" data-type="currency" type="text" name="digitalForecast-{{$a}}-{{$c}}-{{$month[$m]}}" id="digitalForecast-{{$a}}-{{$c}}-{{$month[$m]}}" value="{{number_format($clientsTable['companyValues'][$a][$c]['digitalForecast'][$m],0,',','.')}}"></td>

                                                @else
                                                    <td class="odd center" style="width:3%;">{{number_format($clientsTable['companyValues'][$a][$c]['digitalForecast'][$m],0,',','.')}}</td>
                                                @endif
                                            @endif
                                        @endfor
                                        <td class="darkBlue center" style='width:5%;'>{{number_format($clientsTable['companyValues'][$a][$c]['digitalForecast'][$m],0,',','.')}}</td>
                                    </tr>
                                    <tr class="clickLoop-{{$a}}">
                                        <td class="lightGrey center">TOTAL FCST</td>
                                         @for($m=0; $m <sizeof($month); $m++)
                                            @if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) 
                                                <td class="quarter center" style="width:3%; color: black;">{{number_format($clientsTable['companyValues'][$a][$c]['forecast'][$m],0,',','.')}}</td>
                                            @else                                                
                                                <td class="lightGrey center" style="width:3%;">{{number_format($clientsTable['companyValues'][$a][$c]['forecast'][$m],0,',','.')}}</td>
                                            @endif
                                        @endfor
                                        <td class="darkBlue center" style='width:5%;'>{{number_format($clientsTable['companyValues'][$a][$c]['forecast'][$m],0,',','.')}}</td>
                                    </tr>
                                    <tr class="clickLoop-{{$a}}">
                                        <td class="odd center">BKGS {{$cYear}} - PAY TV</td>
                                         @for($m=0; $m <sizeof($month); $m++)
                                            @if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) 
                                                <td class="quarter center" style='width:5%; color: black;'>{{number_format($clientsTable['companyValues'][$a][$c]['currentPayTvBookings'][$m],0,',','.')}}</td>
                                            @else
                                                <td class="odd center" style='width:5%;'>{{number_format($clientsTable['companyValues'][$a][$c]['currentPayTvBookings'][$m],0,',','.')}}</td>
                                            @endif
                                        @endfor
                                        <td class="darkBlue center" style='width:5%;'>{{number_format($clientsTable['companyValues'][$a][$c]['currentPayTvBookings'][$m],0,',','.')}}</td>
                                    </tr>
                                    <tr class="clickLoop-{{$a}}">
                                        <td class="odd center">BKGS {{$cYear}} - DIGITAL</td>
                                         @for($m=0; $m <sizeof($month); $m++)
                                            @if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) 
                                                <td class="quarter center" style='width:5%; color: black;'>{{number_format($clientsTable['companyValues'][$a][$c]['currentDigitalBookings'][$m],0,',','.')}}</td>
                                            @else
                                                <td class="odd center" style='width:5%;'>{{number_format($clientsTable['companyValues'][$a][$c]['currentDigitalBookings'][$m],0,',','.')}}</td>
                                            @endif
                                        @endfor
                                        <td class="darkBlue center" style='width:5%;'>{{number_format($clientsTable['companyValues'][$a][$c]['currentDigitalBookings'][$m],0,',','.')}}</td>
                                    </tr>
                                    <tr class="clickLoop-{{$a}}">
                                        <td class="lightGrey center">TOTAL BKGS</td>
                                         @for($m=0; $m <sizeof($month); $m++)
                                            @if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) 
                                                <td class="quarter center" style='width:5%; color: black;'>{{number_format($clientsTable['companyValues'][$a][$c]['currentBookings'][$m],0,',','.')}}</td>
                                            @else
                                                <td class="lightGrey center" style='width:5%;'>{{number_format($clientsTable['companyValues'][$a][$c]['currentBookings'][$m],0,',','.')}}</td>
                                            @endif
                                        @endfor
                                        <td class="darkBlue center" style='width:5%;'>{{number_format($clientsTable['companyValues'][$a][$c]['currentBookings'][$m],0,',','.')}}</td>
                                    </tr>
                                    <tr class="clickLoop-{{$a}}">
                                        <td class="newBlue center">PENDING BKGS </td>
                                         @for($m=0; $m <sizeof($month); $m++)
                                            @if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) 
                                                <td class="quarter center" style='width:5%; color: black;'>{{number_format($clientsTable['companyValues'][$a][$c]['pending'][$m],0,',','.')}}</td>
                                            @else
                                                <td class="newBlue center" style='width:5%;'>{{number_format($clientsTable['companyValues'][$a][$c]['pending'][$m],0,',','.')}}</td>
                                            @endif
                                        @endfor
                                        <td class="darkBlue center" style='width:5%;'>{{number_format($clientsTable['companyValues'][$a][$c]['pending'][$m],0,',','.')}}</td>
                                    </tr>
                                    <tr class="clickLoop-{{$a}}">
                                        <td class="odd center" style="border-style:solid; border-color:black; border-width: 0px 0px 1px 0px;">BKGS {{$pYear}}</td>
                                         @for($m=0; $m <sizeof($month); $m++)
                                            @if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) 
                                                <td class="quarter center" style='width:5%; color: black; border-style:solid; border-color:black; border-width: 0px 0px 1px 0px;'>{{number_format($clientsTable['companyValues'][$a][$c]['previousBookings'][$m],0,',','.')}}</td>
                                            @else
                                                <td class="odd center" style='width:5%; border-style:solid; border-color:black; border-width: 0px 0px 1px 0px;'>{{number_format($clientsTable['companyValues'][$a][$c]['previousBookings'][$m],0,',','.')}}</td>
                                            @endif
                                        @endfor
                                        <td class="darkBlue center" style='width:5%; border-style:solid; border-color:black; border-width: 0px 0px 1px 0px;'>{{number_format($clientsTable['companyValues'][$a][$c]['previousBookings'][$m],0,',','.')}}</td>
                                    </tr>
                                @endfor
                            </table>
                        @endfor
                         <table style='width: 100%; zoom: 85%;font-size: 16px;'>
                            <tr class="center">
                                <td style="width: 7% !important; background-color: white;"> &nbsp; </td>
                            </tr>
                            
                        </table>
                        
                        <div class='col'>
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalExemplo" style="width: 100%">
                              Add Client to Forecast
                            </button>
                        </div>
                       <!-- Modal to insert a new client -->
                        <div class="modal fade" id="modalExemplo" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                          <div class="modal-dialog" role="document">
                            <div class="modal-content">
                              <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">New Client</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                                      <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                              <div class="modal-body">
                                <div class="row justify-content-center">          
                                    <div class="col">       
                                        <div class="form-group">
                                            <label><b> Client: </b></label> 
                                             <select class='selectpicker' id='client' name='client[]' data-selected-text-format='count' data-width='100%' class='form-control' data-live-search='true'>
                                                <option selected='true' value="0">Select a Client</option>
                                                @for ($s=0; $s < sizeof($list); $s++)
                                                    <option value='{{$list[$s]['id']}},{{$list[$s]['aID']}}'> {{$list[$s]["client"]}} - {{$list[$s]["agency"]}} </option> 
                                                @endfor
                                            </select>                                                                             
                                        </div>
                                    </div>
                                </div>
                              </div>
                              <div class="modal-footer">
                                 <button type="submit" class="btn btn-primary" style="width: 100%;">Submit</button>
                              </div>
                            </div>
                          </div>
                        </div>

                         <table style='width: 100%; zoom: 85%;font-size: 16px;'>
                            <tr class="center">
                                <td style="width: 7% !important; background-color: white;"> &nbsp; </td>
                            </tr>
                            
                        </table>
                        
                    </div>
                </div>  
            </div>
        </form>
    </div>

<div id="vlau"></div>

<!--THIS SCRIPT IS TO OPEN THE COMPANY VALUES FOR WICH CLIENT OR OPEN THE COMPANY VALUES OF SALES REP-->
<script type="text/javascript">
    $(document).ready(function() {

        $('.clickLoopHeader').hide();

        $(".clickBoolHeader").click(function(e) {
            var myBool = $("#clickBoolHeader").val();

            if (myBool == 1) {
                e
                $(".clickLoopHeader").show();
                myBool = 0;

            } else {
                $(".clickLoopHeader").hide();
                myBool = 1;
            }
            $("#clickBoolHeader").val(myBool);

        });

        @for($a=0;$a<sizeof($clientsTable['clientInfo']);$a++)

            $('.clickLoop-'+ {{ $a }}).hide();

            $(".clickBool-"+ {{ $a }}).click(function(e) {
                var myBool = $("#clickBool-"+ {{ $a }}).val();

                if (myBool == 1) {e
                    $(".clickLoop-"+ {{ $a }}).show();
                    myBool = 0;

                } else {
                    $(".clickLoop-"+ {{ $a }}).hide();
                    myBool = 1;
                }
                $("#clickBool-"+ {{ $a }}).val(myBool);

            });
        @endfor
     
    })
</script>

<script type="text/javascript">
    // função para desabilitar a tecla F5.
    window.onkeydown = function (e) {
        if (e.keyCode === 116) {
            alert("Função não permitida para evitar duplicidades indevidas!");
            e.keyCode = 0;
            e.returnValue = false;
            return false;
        }
    }
</script>

<!-- javascript to be able to edit the front and make calculations of numbers -->
<script type="text/javascript">
    $("input[data-type='currency']").on({
        keyup: function() {
          formatCurrency($(this));
        },
        blur: function() { 
          formatCurrency($(this), "blur");
        }
    });


    function formatNumber(n) {
      // format number 1000000 to 1,234,567
      return n.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ".")
    }


    function formatCurrency(input, blur) {
      // appends $ to value, validates decimal side
      // and puts cursor back in right position.
      
      // get input value
      var input_val = input.val();
      
      // don't validate empty input
      if (input_val === "") { return; }
      
      // original length
      var original_len = input_val.length;

      // initial caret position 
      var caret_pos = input.prop("selectionStart");
        
      // check for decimal
      if (input_val.indexOf(",") >= 0) {

        // get position of first decimal
        // this prevents multiple decimals from
        // being entered
        var decimal_pos = input_val.indexOf(",");

        // split number by decimal point
        var left_side = input_val.substring(0, decimal_pos);
        var right_side = input_val.substring(decimal_pos);

        // add commas to left side of number
        left_side = formatNumber(left_side);

        // validate right side
        right_side = formatNumber(right_side);
        
        // On blur make sure 2 numbers after decimal
        if (blur === "blur") {
          right_side += "00";
        }
        
        // Limit decimal to only 2 digits
        right_side = right_side.substring(0, 2);

        // join number by .
        input_val =  left_side + "," + right_side;

      } else {
        // no decimal entered
        // add commas to number
        // remove all non-digits
        input_val = formatNumber(input_val);
        input_val = input_val;
        
      /*  // final formatting
        if (blur === "blur") {
          input_val += ",00";
        }*/
      }
      
      // send updated string to input
      input.val(input_val);

      // put caret back in the right position
      var updated_len = input_val.length;
      caret_pos = updated_len - original_len + caret_pos;
      input[0].setSelectionRange(caret_pos, caret_pos);
    }



    $(document).ready(function () {    
        $('.numberonly').keypress(function (e) {    
            var charCode = (e.which) ? e.which : oddt.keyCode    
            if (String.fromCharCode(charCode).match('/\B(?=(\d{3})+(?!\d))/g, "."'))  
                return false;                        
        });    
    });

    $(window).keydown(function(oddt) {
        if (oddt.keyCode == 13) {
            oddt.proddtDefault();
            return false;
        }
    });

</script>

<!-- javascript to make the excel export -->
<script type="text/javascript">
            
    $(document).ready(function(){

        ajaxSetup();

        $('#excel').click(function(oddt){

            var currency = "<?php echo $currency; ?>";
            var value = "<?php echo $value; ?>";
            var salesRep = "<?php echo $salesRepID; ?>";
            var region = "<? echo $region?>";
            var year = "<? echo $cYear?>"

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
                url: "/generate/excel/pandr/aeView",
                type: "POST",
                data: {currency,value,salesRep,title, typeExport, auxTitle,region,year},
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

