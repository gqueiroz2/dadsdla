<table>
    <tr>
        <td style='text-align: center; font-weight: bold; background-color: #0f243e; color: white;'>Sales Rep</td>
        <td style='text-align: center; font-weight: bold; background-color: #0f243e; color: white;'>Company</td>
        <td style='text-align: center; font-weight: bold; background-color: #0f243e; color: white;'>Client</td>
        <td style='text-align: center; font-weight: bold; background-color: #0f243e; color: white;'>Agency</td>
        <td style='text-align: center; font-weight: bold; background-color: #0f243e; color: white;'>Plataform</td>
        <td style='text-align: center; font-weight: bold; background-color: #0f243e; color: white;'>Type</td>
        <td style='text-align: center; font-weight: bold; background-color: #0f243e; color: white;'>Probability</td>
        @for($m=0; $m <sizeof($data['monthConsolidate']) ; $m++)
            <td style="text-align: center; font-weight: bold; background-color: #0f243e; color: white;'">{{$data['monthConsolidate'][$m]}}</td>
        @endfor
    </tr>
@for($a=0; $a <sizeof($data['clientsTable']['clientInfo']) ; $a++)
    <!--PART OF WHICH COMPANY BY CLIENT -->

    @for($c=0; $c <sizeof($data['company']); $c++)
       <!-- <tr>
            <td style='text-align:center;'>{{$data['salesRepName'][0]['salesRep']}}</td>
            <td style='text-align:center;'>{{$data['companyView'][$c]}}</td>
            <td style='text-align:center;'>{{$data['clientsTable']['clientInfo'][$a]['clientName']}}</td>
            <td style='text-align:center;'>{{$data['clientsTable']['clientInfo'][$a]['agencyName']}}</td>
            <td style='text-align:center;'>Pay TV</td>
            <td style='text-align: center;'>BKGS</td>
            <td style='text-align: center;'>{{$data['clientsTable']['clientInfo'][$a]['probability'][0]['probability']}}%</td>
             @for($m=0; $m <sizeof($data['monthConsolidate']); $m++)
                <td style='text-align: center;'>{{$data['clientsTable']['companyValues'][$a][$c]['currentPayTvBookingsC'][$m]}}</td>            
            @endfor

        </tr>-->
        <tr>
            <td style='text-align:center;'>{{$data['salesRepName'][0]['salesRep']}}</td>
            <td style='text-align:center;'>{{$data['companyView'][$c]}}</td>
            <td style='text-align:center;'>{{$data['clientsTable']['clientInfo'][$a]['clientName']}}</td>
            <td style='text-align:center;'>{{$data['clientsTable']['clientInfo'][$a]['agencyName']}}</td>
            <td style='text-align:center;'>Pay TV</td>
            <td style='text-align: center;'>FCST</td> 
            <td style='text-align: center;'>{{$data['clientsTable']['clientInfo'][$a]['probability'][0]['probability']}}%</td>
            @for($m=0; $m <sizeof($data['monthConsolidate']); $m++)
                @if($m >= $data['date'])
                    <td style="text-align: center;">{{(($data['clientsTable']['companyValues'][$a][$c]['payTvForecastC'][$m])*($data['clientsTable']['clientInfo'][$a]['probability'][0]['probability']/100))}}</td>
                @else
                    <td style="text-align: center;">{{(($data['clientsTable']['companyValues'][$a][$c]['payTvForecastC'][$m]))}}</td>
                @endif
            @endfor  
        </tr>
        <!--<tr>
            <td style='text-align:center;'>{{$data['salesRepName'][0]['salesRep']}}</td>
            <td style='text-align:center;'>{{$data['companyView'][$c]}}</td>
            <td style='text-align:center;'>{{$data['clientsTable']['clientInfo'][$a]['clientName']}}</td>
            <td style='text-align:center;'>{{$data['clientsTable']['clientInfo'][$a]['agencyName']}}</td>
            <td style='text-align:center;'>Digital</td>
            <td style='text-align: center;'>BKGS</td>
            <td style='text-align: center;'>{{$data['clientsTable']['clientInfo'][$a]['probability'][0]['probability']}}%</td>
             @for($m=0; $m <sizeof($data['monthConsolidate']); $m++)
                <td style='text-align: center;'>{{$data['clientsTable']['companyValues'][$a][$c]['currentDigitalBookingsC'][$m]}}</td>
            @endfor
        </tr>-->
        <tr>
            <td style='text-align:center;'>{{$data['salesRepName'][0]['salesRep']}}</td>
            <td style='text-align:center;'>{{$data['companyView'][$c]}}</td>
            <td style='text-align:center;'>{{$data['clientsTable']['clientInfo'][$a]['clientName']}}</td>
            <td style='text-align:center;'>{{$data['clientsTable']['clientInfo'][$a]['agencyName']}}</td>
            <td style='text-align:center;'>Digital</td>
            <td style='text-align: center;'>FCST</td>
            <td style='text-align: center;'>{{$data['clientsTable']['clientInfo'][$a]['probability'][0]['probability']}}%</td>
             @for($m=0; $m <sizeof($data['monthConsolidate']); $m++)
                @if($m >= $data['date'])
                    <td style="text-align: center;">{{(($data['clientsTable']['companyValues'][$a][$c]['digitalForecastC'][$m])*($data['clientsTable']['clientInfo'][$a]['probability'][0]['probability']/100))}}</td>
                @else
                    <td style="text-align: center;">{{(($data['clientsTable']['companyValues'][$a][$c]['digitalForecastC'][$m]))}}</td>
                @endif
             @endfor
        </tr>
        
    @endfor
@endfor
</table>