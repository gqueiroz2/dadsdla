<table>
	<tr>
		<th style="text-align: center; font-weight: bold; background-color: #0070c0; color: #FFFFFF;" colspan="19">{{$data['salesRepName'][0]['salesRep']}} - {{$data['currency']}}/{{strtoupper($data['value'])}} </th>
	</tr>
</table>

 <table>
    <tr>
        <td colspan="2" style="text-align: center;  font-weight: bold; background-color: #0f243e; color: #FFFFFF; ">{{$data['salesRepName'][0]['abName']}}</td>
        @for($m=0; $m < sizeof($data['month']); $m++)
            @if($m == 3 || $m == 7 || $m == 11 || $m == 15)
                <td style="text-align: center; font-weight: bold; background-color: #4f81bd; color: #FFFFFF;">{{$data['month'][$m]}}</td>
            @else
                <td style="text-align: center; font-weight: bold; background-color: #143052; color: #FFFFFF;">{{$data['month'][$m]}}</td>
            @endif
        @endfor
        <td style='text-align: center; font-weight: bold; background-color: #0f243e; color: white;'>TOTAL</td>
    </tr>
    <tr>
        <td rowspan='5' style='text-align: center; font-weight: bold; background-color: #757171; color: white;'>
            <span> WBD</span>
        </td> 
        <td style="text-align: center; font-weight: bold; background-color:#e7eff9;">TARGET</td>
         @for($m=0; $m <sizeof($data['month']); $m++)
            @if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) 
                <td style='text-align: center;  font-weight: bold; background-color:#4f81bd; color: white;'>{{$data['aeTable']['total']['currentTarget'][$m]}}</td>
            @else
                <td style="text-align: center; font-weight: bold; background-color:#e7eff9;">{{$data['aeTable']['total']['currentTarget'][$m]}}</td>
            @endif
        @endfor
        <td style='text-align: center; font-weight: bold; background-color: #0f243e; color: white;'>{{$data['aeTable']['total']['currentTarget'][$m]}}</td>
    </tr>
    <tr>
    <td style='text-align: center; font-weight: bold; background-color: #e7eff9;'>FCST - PAY TV</td>
     @for($m=0; $m <sizeof($data['month']); $m++)
        @if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) 
            <td style='text-align: center;  font-weight: bold; background-color:#4f81bd; color: white;'>{{$data['aeTable']['total']['payTvForecast'][$m]}}</td>
        @else
            <td style='text-align: center; font-weight: bold; background-color: #e7eff9;'>{{$data['aeTable']['total']['payTvForecast'][$m]}}</td>
        @endif
    @endfor
    <td style='text-align: center; font-weight: bold; background-color: #0f243e; color: white;'>{{$data['aeTable']['total']['payTvForecast'][$m]}}</td>
    </tr>
    <tr>
    <td style="text-align: center; font-weight: bold; background-color:#e7eff9;">FCST - DIGITAL</td>
     @for($m=0; $m <sizeof($data['month']); $m++)
        @if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) 
            <td style='text-align: center;  font-weight: bold; background-color:#4f81bd; color: white;'>{{$data['aeTable']['total']['digitalForecast'][$m]}}</td>
        @else
            <td style="text-align: center; font-weight: bold; background-color:#e7eff9;">{{$data['aeTable']['total']['digitalForecast'][$m]}}</td>
        @endif
    @endfor
    <td style='text-align: center; font-weight: bold; background-color: #0f243e; color: white;'>{{$data['aeTable']['total']['digitalForecast'][$m]}}</td>
    </tr>
    <tr>
    <td style='text-align: center; font-weight: bold; background-color: #e7eff9;'>BKGS {{$data['cYear']}}</td>
     @for($m=0; $m <sizeof($data['month']); $m++)
        @if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) 
            <td style='text-align: center;  font-weight: bold; background-color:#4f81bd; color: white;'>{{$data['aeTable']['total']['currentBookings'][$m]}}</td>
        @else
            <td style='text-align: center; font-weight: bold; background-color: #e7eff9;'>{{$data['aeTable']['total']['currentBookings'][$m]}}</td>
        @endif
    @endfor
    <td style='text-align: center; font-weight: bold; background-color: #0f243e; color: white;'>{{$data['aeTable']['total']['currentBookings'][$m]}}</td>
    </tr>
    <tr>
    <td style="text-align: center; font-weight: bold; background-color:#e7eff9;">BKGS {{$data['pYear']}}</td>
     @for($m=0; $m <sizeof($data['month']); $m++)
        @if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) 
            <td style='text-align: center;  font-weight: bold; background-color:#4f81bd; color: white;'>{{$data['aeTable']['total']['previousBookings'][$m]}}</td>
        @else
            <td style="text-align: center; font-weight: bold; background-color:#e7eff9;">{{$data['aeTable']['total']['previousBookings'][$m]}}</td>
        @endif
    @endfor
    <td style='text-align: center; font-weight: bold; background-color: #0f243e; color: white; border-style:solid; border-color:black; border-width: 0px 0px 1px 0px;'>{{$data['aeTable']['total']['previousBookings'][$m]}}</td>
    </tr>

    <!--PART OF TOTAL OF WHICH COMPANY PART -->

    @for($c=0; $c <sizeof($data['company']); $c++)
    <tr>
        <td rowspan='5' style='text-align: center; background-color: {{$data['color'][$c]}} color: white;'>
            <span> {{$data['companyView'][$c]}}</span>
        </td> 
        <td style="text-align: center; font-weight: bold; background-color:#e7eff9;">TARGET</td>
         @for($m=0; $m <sizeof($data['month']); $m++)
            @if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) 
                <td style='text-align: center;  font-weight: bold; background-color:#4f81bd; color: white;'>{{$data['aeTable']['companyValues'][$c]['currentTarget'][$m]}}</td>
            @else
                <td style="text-align: center; font-weight: bold; background-color:#e7eff9;">{{$data['aeTable']['companyValues'][$c]['currentTarget'][$m]}}</td>
            @endif
        @endfor
        <td style='text-align: center; font-weight: bold; background-color: #0f243e; color: white;'>{{$data['aeTable']['companyValues'][$c]['currentTarget'][$m]}}</td>
    </tr>
    <tr>
        <td style='text-align: center; font-weight: bold; background-color: #e7eff9;'>FCST - PAY TV</td>
         @for($m=0; $m <sizeof($data['month']); $m++)
            @if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) 
                <td style='text-align: center;  font-weight: bold; background-color:#4f81bd; color: white;'>{{$data['aeTable']['companyValues'][$c]['payTvForecast'][$m]}}</td>
            @else
                <td style="text-align: center; font-weight: bold; background-color:#e7eff9;">{{$data['aeTable']['companyValues'][$c]['payTvForecast'][$m]}}</td>
            @endif
        @endfor
        <td style='text-align: center; font-weight: bold; background-color: #0f243e; color: white;'>{{$data['aeTable']['companyValues'][$c]['payTvForecast'][$m]}}</td>
    </tr>
    <tr>
        <td style="text-align: center; font-weight: bold; background-color:#e7eff9;">FCST - DIGITAL</td>
         @for($m=0; $m <sizeof($data['month']); $m++)
            @if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) 
                <td style='text-align: center;  font-weight: bold; background-color:#4f81bd; color: white;'>{{$data['aeTable']['companyValues'][$c]['digitalForecast'][$m]}}</td>
            @else
                <td style="text-align: center; font-weight: bold; background-color:#e7eff9;">{{$data['aeTable']['companyValues'][$c]['digitalForecast'][$m]}}</td>
            @endif
        @endfor
        <td style='text-align: center; font-weight: bold; background-color: #0f243e; color: white;'>{{$data['aeTable']['companyValues'][$c]['digitalForecast'][$m]}}</td>
    </tr>
    <tr>
        <td style='text-align: center; font-weight: bold; background-color: #e7eff9;'>BKGS {{$data['cYear']}}</td>
         @for($m=0; $m <sizeof($data['month']); $m++)
            @if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) 
                <td style='text-align: center;  font-weight: bold; background-color:#4f81bd; color: white;'>{{$data['aeTable']['companyValues'][$c]['currentBookings'][$m]}}</td>
            @else
                <td style="text-align: center; font-weight: bold; background-color:#e7eff9;">{{$data['aeTable']['companyValues'][$c]['currentBookings'][$m]}}</td>
            @endif
        @endfor
        <td style='text-align: center; font-weight: bold; background-color: #0f243e; color: white;'>{{$data['aeTable']['companyValues'][$c]['currentBookings'][$m]}}</td>
    </tr>
    <tr>
        <td style="text-align: center; font-weight: bold; background-color:#e7eff9;">BKGS {{$data['pYear']}}</td>
         @for($m=0; $m <sizeof($data['month']); $m++)
            @if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) 
                <td style='text-align: center;  font-weight: bold; background-color:#4f81bd; color: white;'>{{$data['aeTable']['companyValues'][$c]['previousBookings'][$m]}}</td>
            @else
                <td style="text-align: center; font-weight: bold; background-color:#e7eff9;">{{$data['aeTable']['companyValues'][$c]['previousBookings'][$m]}}</td>
            @endif
        @endfor
        <td style='text-align: center; font-weight: bold; background-color: #0f243e; color: white;'>{{$data['aeTable']['companyValues'][$c]['previousBookings'][$m]}}</td>
    </tr>
    @endfor
</table>

<!--START OF CLIENTS TABLE-->
@for($a=0; $a <sizeof($data['clientsTable']['clientInfo']) ; $a++)
<table>
    <tr>
        <td colspan='2' style='text-align: center; font-weight: bold; background-color: #0f243e; color: white;'>{{$data['clientsTable']['clientInfo'][$a]['clientName']}} - {{$data['clientsTable']['clientInfo'][$a]['agencyName']}}</td>
        @for($m=0; $m <sizeof($data['month']) ; $m++)
            @if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) 
                <td style='text-align: center;  font-weight: bold; background-color:#4f81bd; color: #FFFFFF;'>{{$data['month'][$m]}}</td>
            @else
                <td style="text-align: center; font-weight: bold; background-color: #143052; color: #FFFFFF;">{{$data['month'][$m]}}</td>
            @endif
        @endfor
        <td style='text-align: center; font-weight: bold; background-color: #0f243e; color: white;'>Total</td>
    </tr>
    <tr>
        <td rowspan='4' style='text-align: center; font-weight: bold; background-color: #757171; color: white;'>
            <span> WBD</span>
        </td>                                     
        <td style='text-align: center; font-weight: bold; background-color: #e7eff9;'>FCST - PAY TV</td>
         @for($m=0; $m <sizeof($data['month']); $m++)
            @if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) 
                <td style='text-align: center;  font-weight: bold; background-color:#4f81bd; color: white;'>{{$data['clientsTable']['total'][$a]['payTvForecast'][$m]}}</td>
            @else
                <td style="text-align: center; font-weight: bold; background-color:#e7eff9;">{{$data['clientsTable']['total'][$a]['payTvForecast'][$m]}}</td>
            @endif
        @endfor
        <td style='text-align: center; font-weight: bold; background-color: #0f243e; color: white;'>{{$data['clientsTable']['total'][$a]['payTvForecast'][$m]}}</td>
    </tr>
    <tr>
        <td style="text-align: center; font-weight: bold; background-color: #e7eff9;">FCST - DIGITAL</td>
         @for($m=0; $m <sizeof($data['month']); $m++)
            @if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) 
                <td style='text-align: center;  font-weight: bold; background-color:#4f81bd; color: white;'>{{$data['clientsTable']['total'][$a]['digitalForecast'][$m]}}</td>
            @else
                 <td style="text-align: center; font-weight: bold; background-color:#e7eff9;">{{$data['clientsTable']['total'][$a]['digitalForecast'][$m]}}</td>
            @endif
        @endfor
        <td style='text-align: center; font-weight: bold; background-color: #0f243e; color: white;'>{{$data['clientsTable']['total'][$a]['digitalForecast'][$m]}}</td>
    </tr>
    <tr>
        <td style='text-align: center; font-weight: bold; background-color: #e7eff9;'>BKGS {{$data['cYear']}}</td>
         @for($m=0; $m <sizeof($data['month']); $m++)
            @if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) 
                <td style='text-align: center;  font-weight: bold; background-color:#4f81bd; color: white;'>{{$data['clientsTable']['total'][$a]['currentBookings'][$m]}}</td>
            @else
                 <td style="text-align: center; font-weight: bold; background-color:#e7eff9;">{{$data['clientsTable']['total'][$a]['currentBookings'][$m]}}</td>
            @endif
        @endfor
        <td style='text-align: center; font-weight: bold; background-color: #0f243e; color: white;'>{{$data['clientsTable']['total'][$a]['currentBookings'][$m]}}</td>
    </tr>
    <tr>
        <td style="text-align: center; font-weight: bold; background-color: #e7eff9; ">BKGS {{$data['pYear']}}</td>
         @for($m=0; $m <sizeof($data['month']); $m++)
            @if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) 
                <td style='text-align: center;  font-weight: bold; background-color:#4f81bd; color: white;'>{{$data['clientsTable']['total'][$a]['previousBookings'][$m]}}</td>
            @else
                <td style="text-align: center; font-weight: bold; background-color:#e7eff9;">{{$data['clientsTable']['total'][$a]['previousBookings'][$m]}}</td>
            @endif
        @endfor
        <td style='text-align: center; font-weight: bold; background-color: #0f243e; color: white;'>{{$data['clientsTable']['total'][$a]['previousBookings'][$m]}}</td>
    </tr>

    <!--PART OF WHICH COMPANY BY CLIENT -->

    @for($c=0; $c <sizeof($data['company']); $c++)
        <tr>
            <td rowspan='4' style='color: white; text-align: center; background-color: {{$data['color'][$c]}} text-align:center;'>
                <span> {{$data['companyView'][$c]}}</span>
            </td>                                         
            <td style='text-align: center; font-weight: bold; background-color: #e7eff9;'>FCST - PAY TV</td>
             @for($m=0; $m <sizeof($data['month']); $m++)
                @if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) 
                    <td style='text-align: center;  font-weight: bold; background-color:#4f81bd; color: white;'>{{$data['clientsTable']['companyValues'][$a][$c]['payTvForecast'][$m]}}</td>
                @else
                    @if($m >= date('n'))
                        <td style='text-align: center; font-weight: bold; background-color: #e7eff9; color: red;'>{{$data['clientsTable']['companyValues'][$a][$c]['payTvForecast'][$m]}}</td>
                    @else
                        <td style="text-align: center; font-weight: bold; background-color:#e7eff9;">{{$data['clientsTable']['companyValues'][$a][$c]['payTvForecast'][$m]}}</td>
                    @endif
                @endif
            @endfor             
            <td style='text-align: center; font-weight: bold; background-color: #0f243e; color: white;'>{{$data['clientsTable']['companyValues'][$a][$c]['payTvForecast'][$m]}}</td>
        </tr>
        <tr>
            <td style="text-align: center; font-weight: bold; background-color: #e7eff9; ">FCST - DIGITAL</td>
             @for($m=0; $m <sizeof($data['month']); $m++)
                @if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) 
                    <td style='text-align: center;  font-weight: bold; background-color:#4f81bd; color: white;'>{{$data['clientsTable']['companyValues'][$a][$c]['digitalForecast'][$m]}}</td>
                @else
                     @if($m >= date('n'))
                         <td style="text-align: center; font-weight: bold; background-color:#e7eff9; color: red">{{$data['clientsTable']['companyValues'][$a][$c]['digitalForecast'][$m]}}</td>

                    @else
                        <td style="text-align: center; font-weight: bold; background-color:#e7eff9;">{{$data['clientsTable']['companyValues'][$a][$c]['digitalForecast'][$m]}}</td>
                    @endif
                @endif
            @endfor
            <td style='text-align: center; font-weight: bold; background-color: #0f243e; color: white;'>{{$data['clientsTable']['companyValues'][$a][$c]['digitalForecast'][$m]}}</td>
        </tr>
        <tr>
            <td style='text-align: center; font-weight: bold; background-color: #e7eff9;'>BKGS {{$data['cYear']}}</td>
             @for($m=0; $m <sizeof($data['month']); $m++)
                @if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) 
                    <td style='text-align: center;  font-weight: bold; background-color:#4f81bd; color: white;'>{{$data['clientsTable']['companyValues'][$a][$c]['currentBookings'][$m]}}</td>
                @else
                    <td style='text-align: center; font-weight: bold; background-color: #e7eff9;'>{{$data['clientsTable']['companyValues'][$a][$c]['currentBookings'][$m]}}</td>
                @endif
            @endfor
            <td style='text-align: center; font-weight: bold; background-color: #0f243e; color: white;'>{{$data['clientsTable']['companyValues'][$a][$c]['currentBookings'][$m]}}</td>
        </tr>
        <tr>
            <td style="text-align: center; font-weight: bold; background-color: #e7eff9;">BKGS {{$data['pYear']}}</td>
             @for($m=0; $m <sizeof($data['month']); $m++)
                @if ($m == 3 || $m == 7 || $m == 11 || $m == 15 ) 
                    <td style='text-align: center; font-weight: bold; background-color:#4f81bd; color: white;'>{{$data['clientsTable']['companyValues'][$a][$c]['previousBookings'][$m]}}</td>
                @else
                    <td style="text-align: center; font-weight: bold; background-color:#e7eff9;">{{$data['clientsTable']['companyValues'][$a][$c]['previousBookings'][$m]}}</td>
                @endif
            @endfor
            <td style='text-align: center; font-weight: bold; background-color: #0f243e; color: white;'>{{$data['clientsTable']['companyValues'][$a][$c]['previousBookings'][$m]}}</td>
        </tr>
    @endfor
</table>
@endfor
