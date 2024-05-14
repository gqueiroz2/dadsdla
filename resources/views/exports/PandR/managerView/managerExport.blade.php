<table>
     <tr>
        <td style='text-align: center; font-weight: bold; background-color: #0f243e; color: white;'>Manager</td>
        <td style='text-align: center; font-weight: bold; background-color: #0f243e; color: white;'>Sales Rep</td>
        <td style='text-align: center; font-weight: bold; background-color: #0f243e; color: white;'>Company</td>
        <td style='text-align: center; font-weight: bold; background-color: #0f243e; color: white;'>Plataform</td>
        <td style='text-align: center; font-weight: bold; background-color: #0f243e; color: white;'>Type</td>
        <td style='text-align: center; font-weight: bold; background-color: #0f243e; color: white;'>Month</td>
        <td style='text-align: center; font-weight: bold; background-color: #0f243e; color: white;'>Value</td>
    </tr>

<!--START OF CURRENT MONTH CLIENTS TABLE-->
@for($a=0; $a <sizeof($data['repsTableC']['repValues']) ; $a++)
    @for($c=0; $c<sizeof($data['company']); $c++)
    <tr>
        <td style='text-align: center;'>{{$data['managerName']}}</td>
        <td style='text-align:center;'>{{$data['repsTableC']['repInfo'][$a]['salesRep']}}</td>
        <td style='text-align:center;'>{{$data['companyView'][$c]}}</td>
        <td style='text-align:center;'>Pay TV</td>
        <td style='text-align: center;'>FCST</td>
        <td style='text-align: center;'>{{$data['currentMonthName'][0]}}</td>
        <td style='text-align: center;'>{{$data['repsTableC']['repValues'][$a][$c]['payTvForecast']}}</td>
    </tr>
   
    <tr>
        <td style='text-align: center;'>{{$data['managerName']}}</td>
        <td style='text-align:center;'>{{$data['repsTableC']['repInfo'][$a]['salesRep']}}</td>
        <td style='text-align:center;'>{{$data['companyView'][$c]}}</td>
        <td style='text-align:center;'>Digital</td>
        <td style='text-align: center;'>FCST</td>
        <td style='text-align: center;'>{{$data['currentMonthName'][0]}}</td>
        <td style='text-align: center;'>{{$data['repsTableC']['repValues'][$a][$c]['digitalForecast']}}</td>
    </tr>
    @endfor    
@endfor


<!--START OF NEXT MONTH CLIENTS TABLE-->
@for($a=0; $a <sizeof($data['repsTableN']['repValues']) ; $a++)
    @for($c=0; $c<sizeof($data['company']); $c++)
    <tr>
        <td style='text-align: center;'>{{$data['managerName']}}</td>
        <td style='text-align:center;'>{{$data['repsTableN']['repInfo'][$a]['salesRep']}}</td>
        <td style='text-align:center;'>{{$data['companyView'][$c]}}</td>
        <td style='text-align:center;'>Pay TV</td>
        <td style='text-align: center;'>FCST</td>
        <td style='text-align: center;'>{{$data['nextMonthName'][0]}}</td>
        <td style='text-align: center;'>{{$data['repsTableN']['repValues'][$a][$c]['payTvForecast']}}</td>
    </tr>
   
    <tr>
        <td style='text-align: center;'>{{$data['managerName']}}</td>
        <td style='text-align:center;'>{{$data['repsTableN']['repInfo'][$a]['salesRep']}}</td>
        <td style='text-align:center;'>{{$data['companyView'][$c]}}</td>
        <td style='text-align:center;'>Digital</td>
        <td style='text-align: center;'>FCST</td>
        <td style='text-align: center;'>{{$data['nextMonthName'][0]}}</td>
        <td style='text-align: center;'>{{$data['repsTableN']['repValues'][$a][$c]['digitalForecast']}}</td>
    </tr>
    @endfor    
@endfor

<!--START OF NEXT NEXT MONTH CLIENTS TABLE-->
@for($a=0; $a <sizeof($data['repsTableNN']['repValues']) ; $a++)
    @for($c=0; $c<sizeof($data['company']); $c++)
    <tr>
        <td style='text-align: center;'>{{$data['managerName']}}</td>
        <td style='text-align:center;'>{{$data['repsTableNN']['repInfo'][$a]['salesRep']}}</td>
        <td style='text-align:center;'>{{$data['companyView'][$c]}}</td>
        <td style='text-align:center;'>Pay TV</td>
        <td style='text-align: center;'>FCST</td>
        <td style='text-align: center;'>{{$data['nextNMonthName'][0]}}</td>
        <td style='text-align: center;'>{{$data['repsTableNN']['repValues'][$a][$c]['payTvForecast']}}</td>
    </tr>
   
    <tr>
        <td style='text-align: center;'>{{$data['managerName']}}</td>
        <td style='text-align:center;'>{{$data['repsTableNN']['repInfo'][$a]['salesRep']}}</td>
        <td style='text-align:center;'>{{$data['companyView'][$c]}}</td>
        <td style='text-align:center;'>Digital</td>
        <td style='text-align: center;'>FCST</td>
        <td style='text-align: center;'>{{$data['nextNMonthName'][0]}}</td>
        <td style='text-align: center;'>{{$data['repsTableNN']['repValues'][$a][$c]['digitalForecast']}}</td>
    </tr>
    @endfor    
@endfor

</table>
