@extends('layouts.mirror')
@section('title', 'AE Report')
@section('head')
    <?php include resource_path('views/auth.php'); 
        $month = date('F');
        $company = array('3','1','2');

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
    <style type="text/css">
        table {
            border-collapse: collapse;
        }

        #loading {
            position: absolute;
            left: 0px;
            top:0px;
            margin:0px;
            width: 100%;
            height: 105%;
            display:block;
            z-index: 99999;
            opacity: 0.9;
            -moz-opacity: 0;
            filter: alpha(opacity = 45);
            background: white;
            background-image: url("/loading.gif");
            background-repeat: no-repeat;
            background-position:50% 50%;
            text-align: center;
            overflow: hidden;
            font-size:30px;
            font-weight: bold;
            color: black;
        }
    </style>


@endsection
@section('content')

     <form method="POST" action="{{ route('forecastByAEPost') }}" runat="server"  onsubmit="ShowLoading()" onkeydown="return event.key != 'Enter';">
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
                Monthly Forecast
            </div>
            <div class="col-2">
                <button type="button" id="excel" class="btn btn-primary" style="width: 100%;">
                    Generate Excel
                </button>
            </div>
        </div>
    </div>

    <br>
    <div class="container-fluid" id="body">
        <form method="POST" action="{{ route('forecastByAESave') }}" runat="server" onsubmit="ShowLoading()">
            @csrf
            <div class="row justify-content-end">
                <div class="col">
                    <div class="container-fluid" style="margin-left: 10px;">
                        <div class="row justify-content-end" >
                            <div class="col-2">
                                <input type="submit" id="button" value="Save" class="btn btn-primary"
                                    style="width: 100%">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
 
            <div class="row mt-2 justify-content-end">
                <div class="col" style="width: 100%;">
                    <!--START OF SALES REP TABLE-->
                     <table style='width: 100%; zoom: 85%;font-size: 22px;'>
                        <tr>
                            <th class="newBlue center">{{$salesRepName[0]['salesRep']}} - {{$month}}</th>
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
                            <td class="darkBlue" style="width:5%;">{{$salesRepName[0]['salesRep']}}</td>
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
                                <td class="even" id='' style='text-align:center; width:3%;'>
                                   {{number_format($aeTable['companyValues'][$c]['currentTarget'])}}
                                </td>   
                            @endfor
                            <td class="darkBlue center" style='width:5%;'>{{number_format($aeTable['total']['currentTarget'])}}</td>
                        </tr>
                        <tr>
                            <td class="odd center">FCST - PAY TV</td>
                             @for($c=0; $c <sizeof($company); $c++)
                                <td class="odd" id='' style='text-align:center; width:5%;'>
                                   {{number_format($aeTable['companyValues'][$c]['payTvForecast'])}}
                                </td>   
                            @endfor
                            <td class="darkBlue center" style='width:5%;'>{{number_format($aeTable['total']['payTvForecast'])}}</td>
                        </tr>
                        <tr>
                            <td class="even center">FCST - DIGITAL</td>
                            @for($c=0; $c <sizeof($company); $c++)
                                <td class="even" id='' style='text-align:center; width:5%;'>
                                   {{number_format($aeTable['companyValues'][$c]['digitalForecast'])}}
                                </td>   
                            @endfor
                            <td class="darkBlue center" style='width:5%;'>{{number_format($aeTable['total']['digitalForecast'])}}</td>
                        </tr>
                        <tr>
                            <td class="odd center">BKGS {{$cYear}} - PAY TV </td>
                            @for($c=0; $c <sizeof($company); $c++)
                                <td class="odd" id='' style='text-align:center; width:3%;'>
                                   {{number_format($aeTable['companyValues'][$c]['currentPayTvBookings'])}}
                                </td>   
                            @endfor
                            <td class="darkBlue center" style='width:5%;'>{{number_format($aeTable['total']['currentPayTvBookings'])}}</td>
                        </tr>
                        <tr>
                            <td class="even center">BKGS {{$cYear}} - DIGITAL </td>
                            @for($c=0; $c <sizeof($company); $c++)
                                <td class="even" id='' style='text-align:center; width:3%;'>
                                   {{number_format($aeTable['companyValues'][$c]['currentDigitalBookings'])}}
                                </td>   
                            @endfor
                            <td class="darkBlue center" style='width:5%;'>{{number_format($aeTable['total']['currentDigitalBookings'])}}</td>
                        </tr>
                        <tr>
                            <td class="odd center" style="border-style:solid; border-color:black; border-width: 0px 0px 1px 0px;">BKGS {{$pYear}}</td>
                            @for($c=0; $c <sizeof($company); $c++)
                                <td class="odd" id='' style='text-align:center; border-bottom: 1pt solid black;  width:3%;'>
                                   {{number_format($aeTable['companyValues'][$c]['previousBookings'])}}
                                </td>   
                            @endfor
                            <td class="darkBlue center" style='width:5%; border-style:solid; border-color:black; border-width: 0px 0px 1px 0px;'>{{number_format($aeTable['total']['previousBookings'])}}</td>
                        </tr>
                    </table>

                    <!--START OF CLIENTS TABLE-->
                    @for($a=0; $a <sizeof($clientsTable['clientInfo']) ; $a++)
                        <input type='hidden' readonly='true' type="text" name="currency" id="currency" style="background-color:transparent; border:none; font-weight:bold; text-align:center;" value="{{$currencyID}}">
                        <input type='hidden' readonly='true' type="text" name="value" id="value" style="background-color:transparent; border:none; font-weight:bold; text-align:center;" value="{{$value}}">
                        <input type='hidden' readonly='true' type="text" name="salesRep" id="salesRep" style="background-color:transparent; border:none; font-weight:bold; text-align:center;" value="{{$salesRepID}}">
                        <input type='hidden' readonly='true' type="text" name="client-{{$a}}" id="client-{{$a}}" style="background-color:transparent; border:none; font-weight:bold; text-align:center;" value="{{$clientsTable['clientInfo'][$a]['clientID']}}">
                        <input type='hidden' readonly='true' type="text" name="agency-{{$a}}" id="agency-{{$a}}" style="background-color:transparent; border:none; font-weight:bold; text-align:center;" value="{{$clientsTable['clientInfo'][$a]['agencyID']}}">


                        <table style='width: 100%; zoom: 85%;font-size: 16px;'>
                            <tr class="center">
                                <td style="width: 7% !important; background-color: white;"> &nbsp; </td>
                            </tr>
                        </table>

                        <table style='width: 100%; zoom: 85%;font-size: 16px;'>
                            <tr>
                                <td style="border-style:solid; border-color:black; border-width: 0px 0px 0px 0px; width:1%;">Probability: <input placeholder="0" pattern="^\$\d{3.3}(.\d{3})*(\,\d+)?" data-type="currency" type="text" name="probability-{{$a}}" id="probability-{{$a}}" style=" width:25%; background-color:transparent; border:0px; font-weight:bold; text-align:center;" value={{number_format($clientsTable['clientInfo'][$a]['probability'][0]['probability'])}}>%</td>    
                            </tr>
                            <tr class="center">
                                <td class="darkBlue" style="width:5%;">{{$clientsTable['clientInfo'][$a]['clientName']}} - {{$clientsTable['clientInfo'][$a]['agencyName']}}</td>
                                @for($c=0; $c <sizeof($company); $c++)
                                    <td class="{{$color[$c]}} " id=''style='text-align:center; width:3%;'>
                                        {{$companyView[$c]}}
                                    </td>   
                                @endfor
                                <td class='darkBlue' style="width:5%;">Total</td>
                            </tr>
                            <tr>                                 
                                <td class="odd center">FCST - PAY TV</td>
                                 @for($c=0; $c <sizeof($company); $c++)
                                    <td class="odd center" style="width:3%;"><input style="color: red; width:100%; background-color:transparent; border:none; font-weight:bold; text-align:center;" placeholder="0" pattern="^\$\d{3.3}(.\d{3})*(\,\d+)?" data-type="currency" type="text" name="payTvForecast-{{$a}}-{{$c}}-{{$month}}" id="payTvForecast-{{$a}}-{{$c}}-{{$month}}" value="{{number_format($clientsTable['companyValues'][$a][$c]['payTvForecast'],0,',','.')}}"></td> 
                                @endfor
                                <td class="darkBlue center" style='width:5%;'>{{number_format($clientsTable['total'][$a]['payTvForecast'])}}</td>
                            </tr>
                            <tr>
                                <td class="even center">FCST - DIGITAL</td>
                                @for($c=0; $c <sizeof($company); $c++)
                                    <td class="even center" style="width:3%;"><input style="color: red; width:100%; background-color:transparent; border:none; font-weight:bold; text-align:center;" placeholder="0" pattern="^\$\d{3.3}(.\d{3})*(\,\d+)?" data-type="currency" type="text" name="digitalForecast-{{$a}}-{{$c}}-{{$month}}" id="digitalForecast-{{$a}}-{{$c}}-{{$month}}" value="{{number_format($clientsTable['companyValues'][$a][$c]['digitalForecast'],0,',','.')}}"></td>
                                @endfor
                                <td class="darkBlue center" style='width:5%;'>{{number_format($clientsTable['total'][$a]['digitalForecast'])}}</td>
                            </tr>
                            <tr>
                                <td class="odd center">BKGS {{$cYear}} - PAY TV</td>
                                @for($c=0; $c <sizeof($company); $c++)
                                    <td class="odd center" style='width:5%;'>{{number_format($clientsTable['companyValues'][$a][$c]['currentPayTvBookings'])}}</td>
                                @endfor
                                <td class="darkBlue center" style='width:5%;'>{{number_format($clientsTable['total'][$a]['currentPayTvBookings'])}}</td>
                            </tr>
                            <tr>
                                <td class="even center">BKGS {{$cYear}} - DIGITAL</td>
                                @for($c=0; $c <sizeof($company); $c++)
                                    <td class="even center" style='width:5%;'>{{number_format($clientsTable['companyValues'][$a][$c]['currentDigitalBookings'])}}</td>
                                @endfor
                                <td class="darkBlue center" style='width:5%;'>{{number_format($clientsTable['total'][$a]['currentDigitalBookings'])}}</td>
                            </tr>
                            <tr>
                                <td class="odd center" style="border-style:solid; border-color:black; border-width: 0px 0px 1px 0px;">BKGS {{$pYear}}</td>
                                @for($c=0; $c <sizeof($company); $c++)
                                    <td class="odd center" style='width:5%; border-style:solid; border-color:black; border-width: 0px 0px 1px 0px;'>{{number_format($clientsTable['companyValues'][$a][$c]['previousBookings'])}}</td>
                                @endfor
                                <td class="darkBlue center" style='width:5%; border-style:solid; border-color:black; border-width: 0px 0px 1px 0px;'>{{number_format($clientsTable['total'][$a]['previousBookings'])}}</td>
                            </tr>
                        </table>
                    @endfor

                     <!--START OF CLIENTS TABLE-->
                    @if($newClientsTable['clientInfo'][0]['clientName'] != null)
                        @for($z=0; $z <sizeof($newClientsTable['clientInfo']) ; $z++)
                            <input type='hidden' readonly='true' type="text" name="currency" id="currency" style="background-color:transparent; border:none; font-weight:bold; text-align:center;" value="{{$currencyID}}">
                            <input type='hidden' readonly='true' type="text" name="value" id="value" style="background-color:transparent; border:none; font-weight:bold; text-align:center;" value="{{$value}}">
                            <input type='hidden' readonly='true' type="text" name="salesRep" id="salesRep" style="background-color:transparent; border:none; font-weight:bold; text-align:center;" value="{{$salesRepID}}">
                            <input type='hidden' readonly='true' type="text" name="clientNew-{{$z}}" id="clientNew-{{$z}}" style="background-color:transparent; border:none; font-weight:bold; text-align:center;" value="{{$newClientsTable['clientInfo'][$z]['clientName']}}">
                            <input type='hidden' readonly='true' type="text" name="agencyNew-{{$z}}" id="agencyNew-{{$z}}" style="background-color:transparent; border:none; font-weight:bold; text-align:center;" value="{{$newClientsTable['clientInfo'][$z]['agencyName']}}">
                           <input type='hidden' readonly='true' type="text" name="count" id="count[]" style="background-color:transparent; border:none; font-weight:bold; text-align:center;" value="{{$z}}">

                            <table style='width: 100%; zoom: 85%;font-size: 16px;'>
                                <tr class="center">
                                    <td style="width: 7% !important; background-color: white;"> &nbsp; </td>
                                </tr>
                            </table>

                            <table style='width: 100%; zoom: 85%;font-size: 16px;'>
                                <tr>
                                    <td style="border-style:solid; border-color:black; border-width: 0px 0px 0px 0px; width:1%;">Probability: <input placeholder="0" pattern="^\$\d{3.3}(.\d{3})*(\,\d+)?" data-type="currency" type="text" name="probabilityNew-{{$z}}" id="probabilityNew-{{$z}}" style=" width:25%; background-color:transparent; border:0px; font-weight:bold; text-align:center;" value={{number_format($newClientsTable['clientInfo'][$z]['probability'][0]['probability'])}}>%</td>    
                                </tr>
                                <tr class="center">
                                    <td class="darkBlue" style="width:5%;">{{$newClientsTable['clientInfo'][$z]['clientName']}} - {{$newClientsTable['clientInfo'][$z]['agencyName']}}</td>
                                    @for($c=0; $c <sizeof($company); $c++)
                                        <td class="{{$color[$c]}} " id=''style='text-align:center; width:3%;'>
                                            {{$companyView[$c]}}
                                        </td>   
                                    @endfor
                                    <td class='darkBlue' style="width:5%;">Total</td>
                                </tr>
                                <tr>                                 
                                    <td class="odd center">FCST - PAY TV</td>
                                     @for($c=0; $c <sizeof($company); $c++)
                                        <td class="odd center" style="width:3%;"><input style="color: red; width:100%; background-color:transparent; border:none; font-weight:bold; text-align:center;" placeholder="0" pattern="^\$\d{3.3}(.\d{3})*(\,\d+)?" data-type="currency" type="text" name="payTvForecastNew-{{$z}}-{{$c}}-{{$month}}" id="payTvForecastNew-{{$z}}-{{$c}}-{{$month}}" value="{{number_format($newClientsTable['companyValues'][$z][$c]['payTvForecast'],0,',','.')}}"></td> 
                                    @endfor
                                    <td class="darkBlue center" style='width:5%;'>{{number_format($newClientsTable['total'][$z]['payTvForecast'])}}</td>
                                </tr>
                                <tr>
                                    <td class="even center">FCST - DIGITAL</td>
                                    @for($c=0; $c <sizeof($company); $c++)
                                        <td class="even center" style="width:3%;"><input style="color: red; width:100%; background-color:transparent; border:none; font-weight:bold; text-align:center;" placeholder="0" pattern="^\$\d{3.3}(.\d{3})*(\,\d+)?" data-type="currency" type="text" name="digitalForecastNew-{{$z}}-{{$c}}-{{$month}}" id="digitalForecastNew-{{$z}}-{{$c}}-{{$month}}" value="{{number_format($newClientsTable['companyValues'][$z][$c]['digitalForecast'],0,',','.')}}"></td>
                                    @endfor
                                    <td class="darkBlue center" style='width:5%;'>{{number_format($newClientsTable['total'][$z]['digitalForecast'])}}</td>
                                </tr>
                                <tr>
                                    <td class="odd center">BKGS {{$cYear}} - PAY TV</td>
                                    @for($c=0; $c <sizeof($company); $c++)
                                        <td class="odd center" style='width:5%;'>0</td>
                                    @endfor
                                    <td class="darkBlue center" style='width:5%;'>0</td>
                                </tr>
                                <tr>
                                    <td class="even center">BKGS {{$cYear}} - DIGITAL</td>
                                    @for($c=0; $c <sizeof($company); $c++)
                                        <td class="even center" style='width:5%;'>0</td>
                                    @endfor
                                    <td class="darkBlue center" style='width:5%;'>0</td>
                                </tr>
                                <tr>
                                    <td class="odd center" style="border-style:solid; border-color:black; border-width: 0px 0px 1px 0px;">BKGS {{$pYear}}</td>
                                    @for($c=0; $c <sizeof($company); $c++)
                                        <td class="odd center" style='width:5%; border-style:solid; border-color:black; border-width: 0px 0px 1px 0px;'>0</td>
                                    @endfor
                                    <td class="darkBlue center" style='width:5%; border-style:solid; border-color:black; border-width: 0px 0px 1px 0px;'>0</td>
                                </tr>
                            </table>
                        @endfor
                    @endif
                    
                     <table style='width: 100%; zoom: 85%;font-size: 16px;'>
                        <tr class="center">
                            <td style="width: 7% !important; background-color: white;"> &nbsp; </td>
                        </tr>
                        
                    </table>

                    <div class="col">
                        <input type="submit" id="button" value="Save" class="btn btn-primary"
                                        style="width: 100%">
                    </div>    

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
                                            <tr style="font-weight: bold;">
                                                 <input type='hidden' readonly='true' type="text" name="wm" id="wm" style="background-color:transparent; border:none; font-weight:bold; text-align:center;" value="0">
                                                  <input type='hidden' readonly='true' type="text" name="spt" id="spt" style="background-color:transparent; border:none; font-weight:bold; text-align:center;" value="0">
                                                   <input type='hidden' readonly='true' type="text" name="dc" id="dc" style="background-color:transparent; border:none; font-weight:bold; text-align:center;" value="0">
                                                   <input type='hidden' readonly='true' type="text" name="newProbability" id="newProbability" style="background-color:transparent; border:none; font-weight:bold; text-align:center;" value="0">
                                                <td>Client: <input type="text" maxlength="300" name="newClient" id="newClient"  style="border-style:solid; border-color:black; border-width: 1px 1px 1px 1px; width: 100%; background-color:transparent;" value=""></td> 

                                                <td>Agency: <input type="text" maxlength="300" name="newAgency" id="newAgency"  style="border-style:solid; border-color:black; border-width: 1px 1px 1px 1px; width: 100%; background-color:transparent;" value=""></td>   
                                            </tr>
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
            var charCode = (e.which) ? e.which : event.keyCode    
            if (String.fromCharCode(charCode).match('/\B(?=(\d{3})+(?!\d))/g, "."'))  
                return false;                        
        });    
    });

    $(window).keydown(function(event) {
        if (event.keyCode == 13) {
            event.preventDefault();
            return false;
        }
    });

</script>
@endsection
