@extends('layouts.mirror')
@section('title', 'Property Report')
@section('head')    
    <?php include(resource_path('views/auth.php')); 

    $month = array('Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec');
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
@endsection
@section('content')
    

    <form method="POST" action="{{ route('propertyPost') }}" runat="server"  onsubmit="ShowLoading()" onkeydown="return event.key != 'Enter';">
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
                Property Report
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
       <!-- <form method="POST" action="{{ route('AESave') }}" runat="server"  onsubmit="ShowLoading()" id="forecastForm">-->
        @csrf        
      <!-- </form>-->
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
            <tr class="center">
                <td class="darkBlue center">Type</td>
                <td class="darkBlue center">Property</td>
                <td class="darkBlue center">Cluster</td>
                <td class="darkBlue center">Company</td>
                <td class="darkBlue center">Client</td>
                <td class="darkBlue center">Agency</td>
                <td class="darkBlue center">AE</td>
                <td class="darkBlue center">Platform</td>
                <td class="darkBlue center">Payment</td>
                <td class="darkBlue center">Installments</td>
                <td class="darkBlue center">Probability</td>
                @for($m=0; $m <sizeof($month) ; $m++)
                    <td class='smBlue' style='width:3%;'>{{$month[$m]}}</td>
                @endfor                                
            </tr>
            @for($t=0; $t <sizeof($table); $t++)
            <tr>
                <td class="odd">BKGS {{$cYear}}</td>                            
                <td class="odd center" style='width:10%;'>{{$table['client'][$t]['property']}}</td>
                <td class="odd center" style='width:5%;'>{{$table['client'][$t]['cluster']}}</td>
                <td class="odd center" style='width:5%;'>{{$table['client'][$t]['company']}}</td>
                <td class="odd center" style='width:7%;'>{{$table['client'][$t]['clientName']}}</td>
                <td class="odd center" style='width:7%;'>{{$table['client'][$t]['agencyName']}}</td>
                <td class="odd center" style='width:5%;'>{{$table['client'][$t]['salesRep']}}</td>
                <td class="odd center" style='width:5%;'>Digital</td>
                <div class="col-3" style="font-weight:bold; text-align:center;">
                    <td class="odd center">
                        <select class='selectpicker' name='payment-{{$t}}' style="background-color:transparent; border:none; font-weight:bold; text-align:center;">
                            <option value='anticipated' selected='true' style="text-align: center; font-weight: bold;"> Anticipated </option>
                            <option value='installments' style="text-align: center; font-weight: bold;"> Installments </option>   
                            <option value='30DFM' style="text-align: center; font-weight: bold;"> 30DFM </option>
                        </select>
                    </td>
                </div>
                <td class="odd center" style='width:5%;'><input style="color: red; width:100%; background-color:transparent; border:none; font-weight:bold; text-align:center;" placeholder="0" pattern="^\$\d{3.3}(.\d{3})*(\,\d+)?" data-type="currency" type="text" name="Installments-{{$t}}" id="Installments-{{$t}}" value=""></td>
                <td class="odd center" style='width:5%;'> 
                    <select class='selectpicker' name='probability-{{$t}}'>
                        <option value='25' selected='true'> 25% </option>
                        <option value='50'> 50% </option>   
                        <option value='75'> 75% </option>       
                    </select>
                </td>
                @for($m=0; $m <sizeof($month); $m++)
                    <td class="odd center" style='width:3%;'>{{$table['currentDigital'][$t][$m]['revenue']}}</td>
                @endfor                      
            </tr>
            <tr>
                <td class="odd">BKGS {{$cYear}}</td>                            
                <td class="odd center" style='width:5%;'>{{$table['client'][$t]['property']}}</td>
                <td class="odd center" style='width:5%;'>{{$table['client'][$t]['cluster']}}</td>
                <td class="odd center" style='width:5%;'>{{$table['client'][$t]['company']}}</td>
                <td class="odd center" style='width:5%;'>{{$table['client'][$t]['clientName']}}</td>
                <td class="odd center" style='width:5%;'>{{$table['client'][$t]['agencyName']}}</td>
                <td class="odd center" style='width:5%;'>{{$table['client'][$t]['salesRep']}}</td>
                <td class="odd center" style='width:5%;'>Pay TV</td>
                <td class="odd center" style='width:5%;'>
                    <select class='selectpicker' name='payment-{{$t}}' style="background-color:transparent; border:none; font-weight:bold; text-align:center;">
                        <option value='anticipated' selected='true' style="text-align: center; font-weight: bold;"> Anticipated </option>
                        <option value='installments'> Installments </option>   
                        <option value='30DFM'> 30DFM </option>
                    </select>
                </td>
                <td class="odd center" style='width:5%;'><input style="color: red; width:100%; background-color:transparent; border:none; font-weight:bold; text-align:center;" placeholder="0" pattern="^\$\d{3.3}(.\d{3})*(\,\d+)?" data-type="currency" type="text" name="Installments-{{$t}}" id="Installments-{{$t}}" value=""></td>
                <td class="odd center" style='width:5%;'> 
                    <select class='selectpicker' name='probability-{{$t}}'>
                        <option value='25' selected='true'> 25% </option>
                        <option value='50'> 50% </option>   
                        <option value='75'> 75% </option>       
                    </select>
                </td>
                @for($m=0; $m <sizeof($month); $m++)
                    <td class="odd center" style='width:3%;'>{{$table['currentPayTv'][$t][$m]['revenue']}}</td>
                @endfor                      
            </tr>
           <!-- <tr>
                <td class="odd">BKGS {{$pYear}}</td>                            
                <td class="odd center" style='width:5%;'>{{$table['client'][$t]['property']}}</td>
                <td class="odd center" style='width:5%;'>{{$table['client'][$t]['cluster']}}</td>
                <td class="odd center" style='width:5%;'>{{$table['client'][$t]['company']}}</td>
                <td class="odd center" style='width:5%;'>{{$table['client'][$t]['clientName']}}</td>
                <td class="odd center" style='width:5%;'>{{$table['client'][$t]['agencyName']}}</td>
                <td class="odd center" style='width:5%;'>{{$table['client'][$t]['salesRep']}}</td>
                <td class="odd center" style='width:5%;'>Digital</td>
                <td class="odd center" style='width:5%;'>pagamento</td>
                <td class="odd center" style='width:5%;'>parcela</td>
                <td class="odd center" style='width:5%;'>%</td>
                @for($m=0; $m <sizeof($month); $m++)
                    <td class="odd center" style='width:5%;'>{{$table['previousDigital'][$t][$m]['revenue']}}</td>
                @endfor                      
            </tr>
             <tr>
                <td class="odd">BKGS {{$pYear}}</td>                            
                <td class="odd center" style='width:5%;'>{{$table['client'][$t]['property']}}</td>
                <td class="odd center" style='width:5%;'>{{$table['client'][$t]['cluster']}}</td>
                <td class="odd center" style='width:5%;'>{{$table['client'][$t]['company']}}</td>
                <td class="odd center" style='width:5%;'>{{$table['client'][$t]['clientName']}}</td>
                <td class="odd center" style='width:5%;'>{{$table['client'][$t]['agencyName']}}</td>
                <td class="odd center" style='width:5%;'>{{$table['client'][$t]['salesRep']}}</td>
                <td class="odd center" style='width:5%;'>Pay TV</td>
                <td class="odd center" style='width:5%;'>pagamento</td>
                <td class="odd center" style='width:5%;'>parcela</td>
                <td class="odd center" style='width:5%;'>%</td>
                @for($m=0; $m <sizeof($month); $m++)
                    <td class="odd center" style='width:5%;'>{{$table['previousPayTv'][$t][$m]['revenue']}}</td>
                @endfor                      
            </tr>-->
            @endfor
            
    </table>
    </div>

<div id="vlau"></div>



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

