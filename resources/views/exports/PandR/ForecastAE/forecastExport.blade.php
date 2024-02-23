 <table style='width: 100%; zoom: 85%;font-size: 22px;'>
    <tr>
        <th style="text-align: center; font-weight: bold; background-color: #0070c0; color: #FFFFFF;" colspan='5'>{{$data['salesRepName'][0]['salesRep']}} - {{$data['monthName'][0]}}</th>
    </tr>
</table>

<table>
    <tr>
        <td style='text-align: center; font-weight: bold; background-color: #0f243e; color: #FFFFFF;'>{{$data['salesRepName'][0]['salesRep']}}</td>
         @for($c=0; $c <sizeof($data['company']); $c++)
            <td style='text-align: center; font-weight: bold; background-color: {{$data['color'][$c]}}; color: #FFFFFF;'>
                {{$data['companyView'][$c]}}
            </td>   
        @endfor
        <td style="text-align: center; font-weight: bold; background-color: #0070c0; color: #FFFFFF;">Total</td>
    </tr>
    <tr>
        <td style='text-align: center; font-weight: bold; background-color: #e7eff9;'>TARGET</td>
         @for($c=0; $c <sizeof($data['company']); $c++)
            <td style='text-align: center; font-weight: bold; background-color: #e7eff9;'>
               {{$data['aeTable']['companyValues'][$c]['currentTarget']}}
            </td>   
        @endfor
        <td style='text-align: center; font-weight: bold; background-color: #e7eff9;'>{{$data['aeTable']['total']['currentTarget']}}</td>
    </tr>
    <tr>
        <td style='text-align: center; font-weight: bold; background-color: #e7eff9;'>FCST - PAY TV</td>
         @for($c=0; $c <sizeof($data['company']); $c++)
            <td style='text-align: center; font-weight: bold; background-color: #e7eff9;'>
               {{$data['aeTable']['companyValues'][$c]['payTvForecast']}}
            </td>   
        @endfor
        <td style='text-align: center; font-weight: bold; background-color: #e7eff9;'>{{$data['aeTable']['total']['payTvForecast']}}</td>
    </tr>
    <tr>
        <td style='text-align: center; font-weight: bold; background-color: #e7eff9;'>FCST - DIGITAL</td>
        @for($c=0; $c <sizeof($data['company']); $c++)
            <td style='text-align: center; font-weight: bold; background-color: #e7eff9;'>
               {{$data['aeTable']['companyValues'][$c]['digitalForecast']}}
            </td>   
        @endfor
        <td style='text-align: center; font-weight: bold; background-color: #e7eff9;'>{{$data['aeTable']['total']['digitalForecast']}}</td>
    </tr>
    <tr>
        <td style='text-align: center; font-weight: bold; background-color: #e7eff9;'>BKGS {{$data['year']}} - PAY TV </td>
        @for($c=0; $c <sizeof($data['company']); $c++)
            <td style='text-align: center; font-weight: bold; background-color: #e7eff9;' >
               {{$data['aeTable']['companyValues'][$c]['currentPayTvBookings']}}
            </td>   
        @endfor
        <td style='text-align: center; font-weight: bold; background-color: #e7eff9;'>{{$data['aeTable']['total']['currentPayTvBookings']}}</td>
    </tr>
    <tr>
        <td style='text-align: center; font-weight: bold; background-color: #e7eff9;'>BKGS {{$data['year']}} - DIGITAL </td>
        @for($c=0; $c <sizeof($data['company']); $c++)
            <td style='text-align: center; font-weight: bold; background-color: #e7eff9;'>
               {{$data['aeTable']['companyValues'][$c]['currentDigitalBookings']}}
            </td>   
        @endfor
        <td style='text-align: center; font-weight: bold; background-color: #e7eff9;'>{{$data['aeTable']['total']['currentDigitalBookings']}}</td>
    </tr>
    <tr>
        <td style='text-align: center; font-weight: bold; background-color: #e7eff9;'>TOTAL (BKGS+FCST) </td>
        @for($c=0; $c <sizeof($data['company']); $c++)
            <td style='text-align: center; font-weight: bold; background-color: #e7eff9;'>
               {{$data['aeTable']['companyValues'][$c]['forecastBookings']}}
            </td>   
        @endfor
        <td style='text-align: center; font-weight: bold; background-color: #e7eff9;'>{{$data['aeTable']['total']['forecastBookings']}}</td>
    </tr>
    <tr>
        <td style='text-align: center; font-weight: bold; background-color: #e7eff9;'>BKGS {{$data['pYear']}}</td>
        @for($c=0; $c <sizeof($data['company']); $c++)
            <td style='text-align: center; font-weight: bold; background-color: #e7eff9;'>
               {{$data['aeTable']['companyValues'][$c]['previousBookings']}}
            </td>   
        @endfor
        <td style='text-align: center; font-weight: bold; background-color: #e7eff9;'>{{$data['aeTable']['total']['previousBookings']}}</td>
    </tr>
</table>

                    <!--START OF CLIENTS TABLE-->
@for($a=0; $a <sizeof($data['clientsTable']['clientInfo']) ; $a++)

<table>
    <tr>
        <td style='text-align: center; font-weight: bold; background-color: #e7eff9;'>{{$data['clientsTable']['clientInfo'][$a]['probability'][0]['probability']}}%</td>    
    </tr>
    <tr>
        <td style='text-align: center; font-weight: bold; background-color: #0f243e; color: #FFFFFF;'>{{$data['clientsTable']['clientInfo'][$a]['clientName']}} - {{$data['clientsTable']['clientInfo'][$a]['agencyName']}}</td>
        @for($c=0; $c <sizeof($data['company']); $c++)
            <td style='text-align: center; font-weight: bold; background-color: {{$data['color'][$c]}}; color: #FFFFFF;'>
                {{$data['companyView'][$c]}}
            </td>   
        @endfor
        <td style='text-align: center; font-weight: bold; background-color: #0f243e; color: #FFFFFF;'>Total</td>
    </tr>
    <tr>                                 
        <td style='text-align: center; font-weight: bold; background-color: #e7eff9;'>FCST - PAY TV</td>
         @for($c=0; $c <sizeof($data['company']); $c++)
            <td style='text-align: center; font-weight: bold; background-color: #e7eff9;'>{{$data['clientsTable']['companyValues'][$a][$c]['payTvForecast']}}</td> 
        @endfor
        <td style='text-align: center; font-weight: bold; background-color: #e7eff9;'>{{$data['clientsTable']['total'][$a]['payTvForecast']}}</td>
    </tr>
    <tr>
        <td style='text-align: center; font-weight: bold; background-color: #e7eff9;'>FCST - DIGITAL</td>
        @for($c=0; $c <sizeof($data['company']); $c++)
            <td style='text-align: center; font-weight: bold; background-color: #e7eff9;'>{{$data['clientsTable']['companyValues'][$a][$c]['digitalForecast']}}</td>
        @endfor
        <td style='text-align: center; font-weight: bold; background-color: #e7eff9;'>{{$data['clientsTable']['total'][$a]['digitalForecast']}}</td>
    </tr>
    <tr>
        <td style='text-align: center; font-weight: bold; background-color: #e7eff9;'>BKGS {{$data['year']}} - PAY TV</td>
        @for($c=0; $c <sizeof($data['company']); $c++)
            <td style='text-align: center; font-weight: bold; background-color: #e7eff9;'>{{$data['clientsTable']['companyValues'][$a][$c]['currentPayTvBookings']}}</td>
        @endfor
        <td style='text-align: center; font-weight: bold; background-color: #e7eff9;'>{{$data['clientsTable']['total'][$a]['currentPayTvBookings']}}</td>
    </tr>
    <tr>
        <td style='text-align: center; font-weight: bold; background-color: #e7eff9;'>BKGS {{$data['year']}} - DIGITAL</td>
        @for($c=0; $c <sizeof($data['company']); $c++)
            <td style='text-align: center; font-weight: bold; background-color: #e7eff9;'>{{$data['clientsTable']['companyValues'][$a][$c]['currentDigitalBookings']}}</td>
        @endfor
        <td style='text-align: center; font-weight: bold; background-color: #e7eff9;'>{{$data['clientsTable']['total'][$a]['currentDigitalBookings']}}</td>
    </tr>
    <tr>
        <td style='text-align: center; font-weight: bold; background-color: #e7eff9;'>BKGS {{$data['pYear']}}</td>
        @for($c=0; $c <sizeof($data['company']); $c++)
            <td style='text-align: center; font-weight: bold; background-color: #e7eff9;'>{{$data['clientsTable']['companyValues'][$a][$c]['previousBookings']}}</td>
        @endfor
        <td style='text-align: center; font-weight: bold; background-color: #e7eff9;'>{{$data['clientsTable']['total'][$a]['previousBookings']}}</td>
    </tr>
</table>
@endfor

@if($data['newClientsTable']['clientInfo'] != null)

                         <!--START OF NEW CLIENTS TABLE-->
    @for($z=0; $z <sizeof($data['newClientsTable']['clientInfo']) ; $z++)    

    <table>
        <tr>
            <td style='text-align: center; font-weight: bold; background-color: #e7eff9;'>{{$data['newClientsTable']['clientInfo'][$z]['probability'][0]['probability']}}%</td>    
        </tr>
        <tr>
            <td style='text-align: center; font-weight: bold; background-color: #0f243e; color: #FFFFFF;'>{{$data['newClientsTable']['clientInfo'][$z]['clientName']}} - {{$data['newClientsTable']['clientInfo'][$z]['agencyName']}}</td>
            @for($c=0; $c <sizeof($data['company']); $c++)
                <td style='text-align: center; font-weight: bold; background-color: {{$data['color'][$c]}}; color: #FFFFFF;'>
                    {{$data['companyView'][$c]}}
                </td>   
            @endfor
            <td style='text-align: center; font-weight: bold; background-color: #e7eff9;'>Total</td>
        </tr>
        <tr>                                 
            <td style='text-align: center; font-weight: bold; background-color: #e7eff9;'>FCST - PAY TV</td>
             @for($c=0; $c <sizeof($data['company']); $c++)
                <td style='text-align: center; font-weight: bold; background-color: #e7eff9;'>{{$data['newClientsTable']['companyValues'][$z][$c]['payTvForecast']}}</td> 
            @endfor
            <td style='text-align: center; font-weight: bold; background-color: #e7eff9;'>{{$data['newClientsTable']['total'][$z]['payTvForecast']}}</td>
        </tr>
        <tr>
            <td style='text-align: center; font-weight: bold; background-color: #e7eff9;'>FCST - DIGITAL</td>
            @for($c=0; $c <sizeof($data['company']); $c++)
                <td style='text-align: center; font-weight: bold; background-color: #e7eff9;'>{{$data['newClientsTable']['companyValues'][$z][$c]['digitalForecast']}}</td>
            @endfor
            <td style='text-align: center; font-weight: bold; background-color: #e7eff9;'>{{$data['newClientsTable']['total'][$z]['digitalForecast']}}</td>
        </tr>
        <tr>
            <td style='text-align: center; font-weight: bold; background-color: #e7eff9;'>BKGS {{$data['year']}} - PAY TV</td>
            @for($c=0; $c <sizeof($data['company']); $c++)
                <td style='text-align: center; font-weight: bold; background-color: #e7eff9;'>0</td>
            @endfor
            <td style='text-align: center; font-weight: bold; background-color: #e7eff9;'>0</td>
        </tr>
        <tr>
            <td style='text-align: center; font-weight: bold; background-color: #e7eff9;'>BKGS {{$data['year']}} - DIGITAL</td>
            @for($c=0; $c <sizeof($data['company']); $c++)
                <td style='text-align: center; font-weight: bold; background-color: #e7eff9;'>0</td>
            @endfor
            <td style='text-align: center; font-weight: bold; background-color: #e7eff9;'>0</td>
        </tr>
        <tr>
            <td style='text-align: center; font-weight: bold; background-color: #e7eff9;'>BKGS {{$data['pYear']}}</td>
            @for($c=0; $c <sizeof($data['company']); $c++)
                <td style='text-align: center; font-weight: bold; background-color: #e7eff9;'>0</td>
            @endfor
            <td style='text-align: center; font-weight: bold; background-color: #e7eff9;'>0</td>
        </tr>
    </table>
    @endfor
@endif