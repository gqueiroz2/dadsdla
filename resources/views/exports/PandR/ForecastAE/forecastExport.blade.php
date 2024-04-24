<table>
     <tr>
        <td style='text-align: center; font-weight: bold; background-color: #0f243e; color: white;'>Sales Rep</td>
        <td style='text-align: center; font-weight: bold; background-color: #0f243e; color: white;'>Company</td>
        <td style='text-align: center; font-weight: bold; background-color: #0f243e; color: white;'>Client</td>
        <td style='text-align: center; font-weight: bold; background-color: #0f243e; color: white;'>Agency</td>
        <td style='text-align: center; font-weight: bold; background-color: #0f243e; color: white;'>Plataform</td>
        <td style='text-align: center; font-weight: bold; background-color: #0f243e; color: white;'>Type</td>
        <td style='text-align: center; font-weight: bold; background-color: #0f243e; color: white;'>Probability</td>
        <td style='text-align: center; font-weight: bold; background-color: #0f243e; color: white;'>Month</td>
        <td style='text-align: center; font-weight: bold; background-color: #0f243e; color: white;'>Value</td>
    </tr>

<!--START OF CURRENT MONTH CLIENTS TABLE-->
@for($a=0; $a <sizeof($data['clientsTableCMonth']['clientInfo']) ; $a++)
    @for($c=0; $c<sizeof($data['company']); $c++)
    <!--<tr>
        <td style='text-align:center;'>{{$data['salesRepName'][0]['salesRep']}}</td>
        <td style='text-align:center;'>{{$data['companyView'][$c]}}</td>
        <td style='text-align:center;'>{{$data['clientsTableCMonth']['clientInfo'][$a]['clientName']}}</td>
        <td style='text-align:center;'>{{$data['clientsTableCMonth']['clientInfo'][$a]['agencyName']}}</td>
        <td style='text-align:center;'>Pay TV</td>
        <td style='text-align: center;'>BKGS</td>
        <td style='text-align: center;'>{{$data['clientsTableCMonth']['clientInfo'][$a]['probability'][0]['probability']}}%</td>        
        <td style='text-align: center;'>{{$data['currentMonthName'][0]}}</td>
        <td style='text-align: center;'>{{$data['clientsTableCMonth']['companyValues'][$a][$c]['currentPayTvBookings']}}</td>
    </tr>-->
    <tr>
        <td style='text-align:center;'>{{$data['salesRepName'][0]['salesRep']}}</td>
        <td style='text-align:center;'>{{$data['companyView'][$c]}}</td>
        <td style='text-align:center;'>{{$data['clientsTableCMonth']['clientInfo'][$a]['clientName']}}</td>
        <td style='text-align:center;'>{{$data['clientsTableCMonth']['clientInfo'][$a]['agencyName']}}</td>
        <td style='text-align:center;'>Pay TV</td>
        <td style='text-align: center;'>FCST</td>
        <td style='text-align: center;'>{{$data['clientsTableCMonth']['clientInfo'][$a]['probability'][0]['probability']}}%</td>        
        <td style='text-align: center;'>{{$data['currentMonthName'][0]}}</td>
        <td style='text-align: center;'>{{$data['clientsTableCMonth']['companyValues'][$a][$c]['payTvForecast']*($data['clientsTableCMonth']['clientInfo'][$a]['probability'][0]['probability']/100)}}</td>
    </tr>
   <!-- <tr>
        <td style='text-align:center;'>{{$data['salesRepName'][0]['salesRep']}}</td>
        <td style='text-align:center;'>{{$data['companyView'][$c]}}</td>
        <td style='text-align:center;'>{{$data['clientsTableCMonth']['clientInfo'][$a]['clientName']}}</td>
        <td style='text-align:center;'>{{$data['clientsTableCMonth']['clientInfo'][$a]['agencyName']}}</td>
        <td style='text-align:center;'>Digital</td>
        <td style='text-align: center;'>BKGS</td>
        <td style='text-align: center;'>{{$data['clientsTableCMonth']['clientInfo'][$a]['probability'][0]['probability']}}%</td>        
        <td style='text-align: center;'>{{$data['currentMonthName'][0]}}</td>
        <td style='text-align: center;'>{{$data['clientsTableCMonth']['companyValues'][$a][$c]['currentDigitalBookings']}}</td>
    </tr>-->
    <tr>
        <td style='text-align:center;'>{{$data['salesRepName'][0]['salesRep']}}</td>
        <td style='text-align:center;'>{{$data['companyView'][$c]}}</td>
        <td style='text-align:center;'>{{$data['clientsTableCMonth']['clientInfo'][$a]['clientName']}}</td>
        <td style='text-align:center;'>{{$data['clientsTableCMonth']['clientInfo'][$a]['agencyName']}}</td>
        <td style='text-align:center;'>Digital</td>
        <td style='text-align: center;'>FCST</td>
        <td style='text-align: center;'>{{$data['clientsTableCMonth']['clientInfo'][$a]['probability'][0]['probability']}}%</td>        
        <td style='text-align: center;'>{{$data['currentMonthName'][0]}}</td>
        <td style='text-align: center;'>{{$data['clientsTableCMonth']['companyValues'][$a][$c]['digitalForecast']*($data['clientsTableCMonth']['clientInfo'][$a]['probability'][0]['probability']/100)}}</td>
    </tr>
    @endfor    
@endfor

@if($data['newClientsTableCMonth']['clientInfo'] != null)
@for($z=0; $z <sizeof($data['newClientsTableCMonth']['clientInfo']) ; $z++)
    @for($c=0; $c<sizeof($data['company']); $c++)
    <!--<tr>
        <td style='text-align:center;'>{{$data['salesRepName'][0]['salesRep']}}</td>
        <td style='text-align:center;'>{{$data['companyView'][$c]}}</td>
        <td style='text-align:center;'>{{$data['newClientsTableCMonth']['clientInfo'][$z]['clientName']}}</td>
        <td style='text-align:center;'>{{$data['newClientsTableCMonth']['clientInfo'][$z]['agencyName']}}</td>
        <td style='text-align:center;'>Pay TV</td>
        <td style='text-align: center;'>BKGS</td>
        <td style='text-align: center;'>{{$data['newClientsTableCMonth']['clientInfo'][$z]['probability'][0]['probability']}}%</td>        
        <td style='text-align: center;'>{{$data['currentMonthName'][0]}}</td>
        <td style='text-align: center;'>0</td>
    </tr>-->
    <tr>
        <td style='text-align:center;'>{{$data['salesRepName'][0]['salesRep']}}</td>
        <td style='text-align:center;'>{{$data['companyView'][$c]}}</td>
        <td style='text-align:center;'>{{$data['newClientsTableCMonth']['clientInfo'][$z]['clientName']}}</td>
        <td style='text-align:center;'>{{$data['newClientsTableCMonth']['clientInfo'][$z]['agencyName']}}</td>
        <td style='text-align:center;'>Pay TV</td>
        <td style='text-align: center;'>FCST</td>
        <td style='text-align: center;'>{{$data['newClientsTableCMonth']['clientInfo'][$z]['probability'][0]['probability']}}%</td>        
        <td style='text-align: center;'>{{$data['currentMonthName'][0]}}</td>
        <td style='text-align: center;'>{{$data['newClientsTableCMonth']['companyValues'][$z][$c]['payTvForecast']*($data['newClientsTableCMonth']['clientInfo'][$z]['probability'][0]['probability']/100)}}</td>
    </tr>
    <!--<tr>
        <td style='text-align:center;'>{{$data['salesRepName'][0]['salesRep']}}</td>
        <td style='text-align:center;'>{{$data['companyView'][$c]}}</td>
        <td style='text-align:center;'>{{$data['newClientsTableCMonth']['clientInfo'][$z]['clientName']}}</td>
        <td style='text-align:center;'>{{$data['newClientsTableCMonth']['clientInfo'][$z]['agencyName']}}</td>
        <td style='text-align:center;'>Digital</td>
        <td style='text-align: center;'>BKGS</td>
        <td style='text-align: center;'>{{$data['newClientsTableCMonth']['clientInfo'][$z]['probability'][0]['probability']}}%</td>        
        <td style='text-align: center;'>{{$data['currentMonthName'][0]}}</td>
        <td style='text-align: center;'>0</td>
    </tr>-->
    <tr>
        <td style='text-align:center;'>{{$data['salesRepName'][0]['salesRep']}}</td>
        <td style='text-align:center;'>{{$data['companyView'][$c]}}</td>
        <td style='text-align:center;'>{{$data['newClientsTableCMonth']['clientInfo'][$z]['clientName']}}</td>
        <td style='text-align:center;'>{{$data['newClientsTableCMonth']['clientInfo'][$z]['agencyName']}}</td>
        <td style='text-align:center;'>Digital</td>
        <td style='text-align: center;'>FCST</td>
        <td style='text-align: center;'>{{$data['newClientsTableCMonth']['clientInfo'][$z]['probability'][0]['probability']}}%</td>        
        <td style='text-align: center;'>{{$data['currentMonthName'][0]}}</td>
        <td style='text-align: center;'>{{$data['newClientsTableCMonth']['companyValues'][$z][$c]['digitalForecast']*($data['newClientsTableCMonth']['clientInfo'][$z]['probability'][0]['probability']/100)}}</td>
    </tr>
    @endfor
@endfor
@endif

<!--START OF NEXT MONTH CLIENTS TABLE-->
@for($a=0; $a <sizeof($data['clientsTableNMonth']['clientInfo']) ; $a++)
    @for($c=0; $c<sizeof($data['company']); $c++)
    <!--<tr>
        <td style='text-align:center;'>{{$data['salesRepName'][0]['salesRep']}}</td>
        <td style='text-align:center;'>{{$data['companyView'][$c]}}</td>
        <td style='text-align:center;'>{{$data['clientsTableNMonth']['clientInfo'][$a]['clientName']}}</td>
        <td style='text-align:center;'>{{$data['clientsTableNMonth']['clientInfo'][$a]['agencyName']}}</td>
        <td style='text-align:center;'>Pay TV</td>
        <td style='text-align: center;'>BKGS</td>
        <td style='text-align: center;'>{{$data['clientsTableNMonth']['clientInfo'][$a]['probability'][0]['probability']}}%</td>        
        <td style='text-align: center;'>{{$data['nextMonthName'][0]}}</td>
        <td style='text-align: center;'>{{$data['clientsTableNMonth']['companyValues'][$a][$c]['currentPayTvBookings']}}</td>
    </tr>-->
    <tr>
        <td style='text-align:center;'>{{$data['salesRepName'][0]['salesRep']}}</td>
        <td style='text-align:center;'>{{$data['companyView'][$c]}}</td>
        <td style='text-align:center;'>{{$data['clientsTableNMonth']['clientInfo'][$a]['clientName']}}</td>
        <td style='text-align:center;'>{{$data['clientsTableNMonth']['clientInfo'][$a]['agencyName']}}</td>
        <td style='text-align:center;'>Pay TV</td>
        <td style='text-align: center;'>FCST</td>
        <td style='text-align: center;'>{{$data['clientsTableNMonth']['clientInfo'][$a]['probability'][0]['probability']}}%</td>        
        <td style='text-align: center;'>{{$data['nextMonthName'][0]}}</td>
        <td style='text-align: center;'>{{$data['clientsTableNMonth']['companyValues'][$a][$c]['payTvForecast']*($data['clientsTableNMonth']['clientInfo'][$a]['probability'][0]['probability']/100)}}</td>
    </tr>
    <!--<tr>
        <td style='text-align:center;'>{{$data['salesRepName'][0]['salesRep']}}</td>
        <td style='text-align:center;'>{{$data['companyView'][$c]}}</td>
        <td style='text-align:center;'>{{$data['clientsTableNMonth']['clientInfo'][$a]['clientName']}}</td>
        <td style='text-align:center;'>{{$data['clientsTableNMonth']['clientInfo'][$a]['agencyName']}}</td>
        <td style='text-align:center;'>Digital</td>
        <td style='text-align: center;'>BKGS</td>
        <td style='text-align: center;'>{{$data['clientsTableNMonth']['clientInfo'][$a]['probability'][0]['probability']}}%</td>        
        <td style='text-align: center;'>{{$data['nextMonthName'][0]}}</td>
        <td style='text-align: center;'>{{$data['clientsTableNMonth']['companyValues'][$a][$c]['currentDigitalBookings']}}</td>
    </tr>-->
    <tr>
        <td style='text-align:center;'>{{$data['salesRepName'][0]['salesRep']}}</td>
        <td style='text-align:center;'>{{$data['companyView'][$c]}}</td>
        <td style='text-align:center;'>{{$data['clientsTableNMonth']['clientInfo'][$a]['clientName']}}</td>
        <td style='text-align:center;'>{{$data['clientsTableNMonth']['clientInfo'][$a]['agencyName']}}</td>
        <td style='text-align:center;'>Digital</td>
        <td style='text-align: center;'>FCST</td>
        <td style='text-align: center;'>{{$data['clientsTableNMonth']['clientInfo'][$a]['probability'][0]['probability']}}%</td>        
        <td style='text-align: center;'>{{$data['nextMonthName'][0]}}</td>
        <td style='text-align: center;'>{{$data['clientsTableNMonth']['companyValues'][$a][$c]['digitalForecast']*($data['clientsTableNMonth']['clientInfo'][$a]['probability'][0]['probability']/100)}}</td>
    </tr>
    @endfor    
@endfor

@if($data['newClientsTableNMonth']['clientInfo'] != null)
@for($z=0; $z <sizeof($data['newClientsTableNMonth']['clientInfo']) ; $z++)
    @for($c=0; $c<sizeof($data['company']); $c++)
    <!--<tr>
        <td style='text-align:center;'>{{$data['salesRepName'][0]['salesRep']}}</td>
        <td style='text-align:center;'>{{$data['companyView'][$c]}}</td>
        <td style='text-align:center;'>{{$data['newClientsTableNMonth']['clientInfo'][$z]['clientName']}}</td>
        <td style='text-align:center;'>{{$data['newClientsTableNMonth']['clientInfo'][$z]['agencyName']}}</td>
        <td style='text-align:center;'>Pay TV</td>
        <td style='text-align: center;'>BKGS</td>
        <td style='text-align: center;'>{{$data['newClientsTableNMonth']['clientInfo'][$z]['probability'][0]['probability']}}%</td>        
        <td style='text-align: center;'>{{$data['nextMonthName'][0]}}</td>
        <td style='text-align: center;'>0</td>
    </tr>-->
    <tr>
        <td style='text-align:center;'>{{$data['salesRepName'][0]['salesRep']}}</td>
        <td style='text-align:center;'>{{$data['companyView'][$c]}}</td>
        <td style='text-align:center;'>{{$data['newClientsTableNMonth']['clientInfo'][$z]['clientName']}}</td>
        <td style='text-align:center;'>{{$data['newClientsTableNMonth']['clientInfo'][$z]['agencyName']}}</td>
        <td style='text-align:center;'>Pay TV</td>
        <td style='text-align: center;'>FCST</td>
        <td style='text-align: center;'>{{$data['newClientsTableNMonth']['clientInfo'][$z]['probability'][0]['probability']}}%</td>        
        <td style='text-align: center;'>{{$data['nextMonthName'][0]}}</td>
        <td style='text-align: center;'>{{$data['newClientsTableNMonth']['companyValues'][$z][$c]['payTvForecast']*($data['newClientsTableNMonth']['clientInfo'][$z]['probability'][0]['probability']/100)}}</td>
    </tr>
    <!--<tr>
        <td style='text-align:center;'>{{$data['salesRepName'][0]['salesRep']}}</td>
        <td style='text-align:center;'>{{$data['companyView'][$c]}}</td>
        <td style='text-align:center;'>{{$data['newClientsTableNMonth']['clientInfo'][$z]['clientName']}}</td>
        <td style='text-align:center;'>{{$data['newClientsTableNMonth']['clientInfo'][$z]['agencyName']}}</td>
        <td style='text-align:center;'>Digital</td>
        <td style='text-align: center;'>BKGS</td>
        <td style='text-align: center;'>{{$data['newClientsTableNMonth']['clientInfo'][$z]['probability'][0]['probability']}}%</td>        
        <td style='text-align: center;'>{{$data['nextMonthName'][0]}}</td>
        <td style='text-align: center;'>0</td>
    </tr>-->
    <tr>
        <td style='text-align:center;'>{{$data['salesRepName'][0]['salesRep']}}</td>
        <td style='text-align:center;'>{{$data['companyView'][$c]}}</td>
        <td style='text-align:center;'>{{$data['newClientsTableNMonth']['clientInfo'][$z]['clientName']}}</td>
        <td style='text-align:center;'>{{$data['newClientsTableNMonth']['clientInfo'][$z]['agencyName']}}</td>
        <td style='text-align:center;'>Digital</td>
        <td style='text-align: center;'>FCST</td>
        <td style='text-align: center;'>{{$data['newClientsTableNMonth']['clientInfo'][$z]['probability'][0]['probability']}}%</td>        
        <td style='text-align: center;'>{{$data['nextMonthName'][0]}}</td>
        <td style='text-align: center;'>{{$data['newClientsTableNMonth']['companyValues'][$z][$c]['digitalForecast']*($data['newClientsTableNMonth']['clientInfo'][$z]['probability'][0]['probability']/100)}}</td>
    </tr>
    @endfor
@endfor
@endif

<!--START OF NEXT MONTH CLIENTS TABLE-->
@for($a=0; $a <sizeof($data['clientsTableNNMonth']['clientInfo']) ; $a++)
    @for($c=0; $c<sizeof($data['company']); $c++)
    <!--<tr>
        <td style='text-align:center;'>{{$data['salesRepName'][0]['salesRep']}}</td>
        <td style='text-align:center;'>{{$data['companyView'][$c]}}</td>
        <td style='text-align:center;'>{{$data['clientsTableNNMonth']['clientInfo'][$a]['clientName']}}</td>
        <td style='text-align:center;'>{{$data['clientsTableNNMonth']['clientInfo'][$a]['agencyName']}}</td>
        <td style='text-align:center;'>Pay TV</td>
        <td style='text-align: center;'>BKGS</td>
        <td style='text-align: center;'>{{$data['clientsTableNNMonth']['clientInfo'][$a]['probability'][0]['probability']}}%</td>        
        <td style='text-align: center;'>{{$data['nextNMonthName'][0]}}</td>
        <td style='text-align: center;'>{{$data['clientsTableNNMonth']['companyValues'][$a][$c]['currentPayTvBookings']}}</td>
    </tr>-->
    <tr>
        <td style='text-align:center;'>{{$data['salesRepName'][0]['salesRep']}}</td>
        <td style='text-align:center;'>{{$data['companyView'][$c]}}</td>
        <td style='text-align:center;'>{{$data['clientsTableNNMonth']['clientInfo'][$a]['clientName']}}</td>
        <td style='text-align:center;'>{{$data['clientsTableNNMonth']['clientInfo'][$a]['agencyName']}}</td>
        <td style='text-align:center;'>Pay TV</td>
        <td style='text-align: center;'>FCST</td>
        <td style='text-align: center;'>{{$data['clientsTableNNMonth']['clientInfo'][$a]['probability'][0]['probability']}}%</td>        
        <td style='text-align: center;'>{{$data['nextNMonthName'][0]}}</td>
        <td style='text-align: center;'>{{$data['clientsTableNNMonth']['companyValues'][$a][$c]['payTvForecast']*($data['clientsTableNNMonth']['clientInfo'][$a]['probability'][0]['probability']/100)}}</td>
    </tr>
    <!--<tr>
        <td style='text-align:center;'>{{$data['salesRepName'][0]['salesRep']}}</td>
        <td style='text-align:center;'>{{$data['companyView'][$c]}}</td>
        <td style='text-align:center;'>{{$data['clientsTableNNMonth']['clientInfo'][$a]['clientName']}}</td>
        <td style='text-align:center;'>{{$data['clientsTableNNMonth']['clientInfo'][$a]['agencyName']}}</td>
        <td style='text-align:center;'>Digital</td>
        <td style='text-align: center;'>BKGS</td>
        <td style='text-align: center;'>{{$data['clientsTableNNMonth']['clientInfo'][$a]['probability'][0]['probability']}}%</td>        
        <td style='text-align: center;'>{{$data['nextNMonthName'][0]}}</td>
        <td style='text-align: center;'>{{$data['clientsTableNNMonth']['companyValues'][$a][$c]['currentDigitalBookings']}}</td>
    </tr>-->
    <tr>
        <td style='text-align:center;'>{{$data['salesRepName'][0]['salesRep']}}</td>
        <td style='text-align:center;'>{{$data['companyView'][$c]}}</td>
        <td style='text-align:center;'>{{$data['clientsTableNNMonth']['clientInfo'][$a]['clientName']}}</td>
        <td style='text-align:center;'>{{$data['clientsTableNNMonth']['clientInfo'][$a]['agencyName']}}</td>
        <td style='text-align:center;'>Digital</td>
        <td style='text-align: center;'>FCST</td>
        <td style='text-align: center;'>{{$data['clientsTableNNMonth']['clientInfo'][$a]['probability'][0]['probability']}}%</td>        
        <td style='text-align: center;'>{{$data['nextNMonthName'][0]}}</td>
        <td style='text-align: center;'>{{$data['clientsTableNNMonth']['companyValues'][$a][$c]['digitalForecast']*($data['clientsTableNNMonth']['clientInfo'][$a]['probability'][0]['probability']/100)}}</td>
    </tr>
    @endfor    
@endfor

@if($data['newClientsTableNNMonth']['clientInfo'] != null)
@for($z=0; $z <sizeof($data['newClientsTableNNMonth']['clientInfo']) ; $z++)
    @for($c=0; $c<sizeof($data['company']); $c++)
    <!--<tr>
        <td style='text-align:center;'>{{$data['salesRepName'][0]['salesRep']}}</td>
        <td style='text-align:center;'>{{$data['companyView'][$c]}}</td>
        <td style='text-align:center;'>{{$data['newClientsTableNNMonth']['clientInfo'][$z]['clientName']}}</td>
        <td style='text-align:center;'>{{$data['newClientsTableNNMonth']['clientInfo'][$z]['agencyName']}}</td>
        <td style='text-align:center;'>Pay TV</td>
        <td style='text-align: center;'>BKGS</td>
        <td style='text-align: center;'>{{$data['newClientsTableNNMonth']['clientInfo'][$z]['probability'][0]['probability']}}%</td>        
        <td style='text-align: center;'>{{$data['nextNMonthName'][0]}}</td>
        <td style='text-align: center;'>0</td>
    </tr>-->
    <tr>
        <td style='text-align:center;'>{{$data['salesRepName'][0]['salesRep']}}</td>
        <td style='text-align:center;'>{{$data['companyView'][$c]}}</td>
        <td style='text-align:center;'>{{$data['newClientsTableNNMonth']['clientInfo'][$z]['clientName']}}</td>
        <td style='text-align:center;'>{{$data['newClientsTableNNMonth']['clientInfo'][$z]['agencyName']}}</td>
        <td style='text-align:center;'>Pay TV</td>
        <td style='text-align: center;'>FCST</td>
        <td style='text-align: center;'>{{$data['newClientsTableNNMonth']['clientInfo'][$z]['probability'][0]['probability']}}%</td>        
        <td style='text-align: center;'>{{$data['nextNMonthName'][0]}}</td>
        <td style='text-align: center;'>{{$data['newClientsTableNNMonth']['companyValues'][$z][$c]['payTvForecast']*($data['newClientsTableNNMonth']['clientInfo'][$z]['probability'][0]['probability']/100)}}</td>
    </tr>
    <!--<tr>
        <td style='text-align:center;'>{{$data['salesRepName'][0]['salesRep']}}</td>
        <td style='text-align:center;'>{{$data['companyView'][$c]}}</td>
        <td style='text-align:center;'>{{$data['newClientsTableNNMonth']['clientInfo'][$z]['clientName']}}</td>
        <td style='text-align:center;'>{{$data['newClientsTableNNMonth']['clientInfo'][$z]['agencyName']}}</td>
        <td style='text-align:center;'>Digital</td>
        <td style='text-align: center;'>BKGS</td>
        <td style='text-align: center;'>{{$data['newClientsTableNNMonth']['clientInfo'][$z]['probability'][0]['probability']}}%</td>        
        <td style='text-align: center;'>{{$data['nextNMonthName'][0]}}</td>
        <td style='text-align: center;'>0</td>
    </tr>-->
    <tr>
        <td style='text-align:center;'>{{$data['salesRepName'][0]['salesRep']}}</td>
        <td style='text-align:center;'>{{$data['companyView'][$c]}}</td>
        <td style='text-align:center;'>{{$data['newClientsTableNNMonth']['clientInfo'][$z]['clientName']}}</td>
        <td style='text-align:center;'>{{$data['newClientsTableNNMonth']['clientInfo'][$z]['agencyName']}}</td>
        <td style='text-align:center;'>Digital</td>
        <td style='text-align: center;'>FCST</td>
        <td style='text-align: center;'>{{$data['newClientsTableNNMonth']['clientInfo'][$z]['probability'][0]['probability']}}%</td>        
        <td style='text-align: center;'>{{$data['nextNMonthName'][0]}}</td>
        <td style='text-align: center;'>{{$data['newClientsTableNNMonth']['companyValues'][$z][$c]['digitalForecast']*($data['newClientsTableNNMonth']['clientInfo'][$z]['probability'][0]['probability']/100)}}</td>
    </tr>
    @endfor
@endfor
@endif

</table>
