<table>
<tr>
    <td style="background-color: #757171; color: #ffffff; font-weight: bold; text-align: center;" colspan="13"> WBD ( {{$data['currencyName']}} / {{strtoupper($data['value'])}} )</td>
</tr>

<tr>
    <td style="background-color: #757171; color: #ffffff; font-weight: bold; text-align: center;"> LOG </td>
    <td style="background-color: #757171; color: #ffffff; font-weight: bold; text-align: center;"> {{$data['realDate']}} </td>
    <td style="background-color: #757171; color: #ffffff; font-weight: bold; text-align: center;" colspan="3"> {{$data['cYear']}}</td>
    <td style="background-color: #757171; color: #ffffff; font-weight: bold; text-align: center;" colspan="2"> {{$data['pYear']}}</td>
    <td style="background-color: #757171; color: #ffffff; font-weight: bold; text-align: center;" colspan="1"> {{$data['ppYear']}}</td>
    <td style="background-color: #757171; color: #ffffff; font-weight: bold; text-align: center;" colspan="5"> {{$data['cYear']}} VAR (%)</td>
</tr>   

<tr>
    <td style="background-color: #a6a6a6; color: #000000; font-weight: bold; text-align: center;"> MONTH </td>
    <td style="background-color: #a6a6a6; color: #000000; font-weight: bold; text-align: center;"> PLATAFORM </td>
    <td style="background-color: #143052; color: #ffffff; font-weight: bold; text-align: center;"> BKGS </td>
    <td style="background-color: #143052; color: #ffffff; font-weight: bold; text-align: center;"> PLAN </td>
    <td style="background-color: #143052; color: #ffffff; font-weight: bold; text-align: center;"> FCST </td>
    <td style="background-color: #143052; color: #ffffff; font-weight: bold; text-align: center;"> SCREENSHOT </td>
    <td style="background-color: #143052; color: #ffffff; font-weight: bold; text-align: center;"> BKGS </td>
    <td style="background-color: #143052; color: #ffffff; font-weight: bold; text-align: center;"> BKGS </td>
    <td style="background-color: #143052; color: #ffffff; font-weight: bold; text-align: center;"> PLAN </td>
    <td style="background-color: #143052; color: #ffffff; font-weight: bold; text-align: center;"> FCST </td>
    <td style="background-color: #143052; color: #ffffff; font-weight: bold; text-align: center;"> SCREENSHOT </td>
    <td style="background-color: #143052; color: #ffffff; font-weight: bold; text-align: center;"> BKGS {{$data['pYear']}} </td>
    <td style="background-color: #143052; color: #ffffff; font-weight: bold; text-align: center;"> BKGS {{$data['ppYear']}} </td>
</tr>

@for($m = 0; $m < 3; $m++)
    <td style="color: #ffffff;"> {{ $monthForm = $data['base']->intToMonth(array($data['month'] + $m))[0]}}</td> 
     <tr>
        <td style="background-color: #e6e6e6; color: #000000; font-weight: bold; text-align: center; float: center;" rowspan="4"> {{$monthForm}} </td>
    </tr>
    <tr>
        <td style="background-color: #e7eff9; color: #000000; font-weight: bold; text-align: center;"> TV </td>
        <td style="background-color: #e7eff9; color: #000000; font-weight: bold; text-align: center;"> {{$data['disc'][$m][0]['currentYTD']+$data['wm'][$m][0]['currentYTD']+$data['sony'][$m][0]['currentYTD']}}</td>
        <td style="background-color: #e7eff9; color: #000000; font-weight: bold; text-align: center;"> {{$data['total'][$m][0]['currentPlan']}} </td>
        <td style="background-color: #e7eff9; color: #000000; font-weight: bold; text-align: center;"> {{$data['total'][$m][0]['currentFcst']}} </td>
        <td style="background-color: #e7eff9; color: #000000; font-weight: bold; text-align: center;"> {{$data['total'][$m][0]['previousSS']}} </td>
        <td style="background-color: #e7eff9; color: #000000; font-weight: bold; text-align: center;"> {{$data['total'][$m][0]['previousSap']}} </td>
        <td style="background-color: #e7eff9; color: #000000; font-weight: bold; text-align: center;"> {{$data['total'][$m][0]['pPSap']}} </td>
        <td style="background-color: #e7eff9; color: #000000; font-weight: bold; text-align: center;"> {{number_format($data['total'][$m][0]['currentPlanPercent'],0)}} % </td>
        <td style="background-color: #e7eff9; color: #000000; font-weight: bold; text-align: center;"> {{number_format($data['total'][$m][0]['currentFcstPercent'],0)}} % </td>
        <td style="background-color: #e7eff9; color: #000000; font-weight: bold; text-align: center;"> {{number_format($data['total'][$m][0]['ssPercent'],0)}} % </td>
        <td style="background-color: #e7eff9; color: #000000; font-weight: bold; text-align: center;"> {{number_format($data['total'][$m][0]['pSapPercent'],0)}} % </td>
        <td style="background-color: #e7eff9; color: #000000; font-weight: bold; text-align: center;"> {{number_format($data['total'][$m][0]['ppSapPercent'],0)}} % </td>
    </tr>

    <tr>
        <td style="background-color: #f9fbfd; color: #000000; font-weight: bold; text-align: center;"> ONL </td>
        <td style="background-color: #f9fbfd; color: #000000; font-weight: bold; text-align: center;"> {{$data['disc'][$m][1]['currentYTD']+$data['wm'][$m][1]['currentYTD']+$data['sony'][$m][1]['currentYTD']}} </td>
        <td style="background-color: #f9fbfd; color: #000000; font-weight: bold; text-align: center;"> {{$data['total'][$m][1]['currentPlan']}} </td>
        <td style="background-color: #f9fbfd; color: #000000; font-weight: bold; text-align: center;"> {{$data['total'][$m][1]['currentFcst']}} </td>
        <td style="background-color: #f9fbfd; color: #000000; font-weight: bold; text-align: center;"> {{$data['total'][$m][1]['previousSS']}} </td>
        <td style="background-color: #f9fbfd; color: #000000; font-weight: bold; text-align: center;"> {{$data['total'][$m][1]['previousSap']}} </td>
        <td style="background-color: #f9fbfd; color: #000000; font-weight: bold; text-align: center;"> {{$data['total'][$m][1]['pPSap']}} </td>
        <td style="background-color: #f9fbfd; color: #000000; font-weight: bold; text-align: center;"> {{number_format($data['total'][$m][1]['currentPlanPercent'],0)}} % </td>
        <td style="background-color: #f9fbfd; color: #000000; font-weight: bold; text-align: center;"> {{number_format($data['total'][$m][1]['currentFcstPercent'],0)}} % </td>
        <td style="background-color: #f9fbfd; color: #000000; font-weight: bold; text-align: center;"> {{number_format($data['total'][$m][1]['ssPercent'],0)}} % </td>
        <td style="background-color: #f9fbfd; color: #000000; font-weight: bold; text-align: center;"> {{number_format($data['total'][$m][1]['pSapPercent'],0)}} % </td>
        <td style="background-color: #f9fbfd; color: #000000; font-weight: bold; text-align: center;"> {{number_format($data['total'][$m][1]['ppSapPercent'],0)}} % </td>
    </tr>

    <tr>
        <td style="background-color: #e7eff9; color: #000000; font-weight: bold; text-align: center;"> TOTAL </td>
        <td style="background-color: #e7eff9; color: #000000; font-weight: bold; text-align: center;"> {{$data['disc'][$m][2]['currentYTD']+$data['wm'][$m][2]['currentYTD']+$data['sony'][$m][2]['currentYTD']}} </td>
        <td style="background-color: #e7eff9; color: #000000; font-weight: bold; text-align: center;"> {{$data['total'][$m][2]['currentPlan']}} </td>
        <td style="background-color: #e7eff9; color: #000000; font-weight: bold; text-align: center;"> {{$data['total'][$m][2]['currentFcst']}} </td>
        <td style="background-color: #e7eff9; color: #000000; font-weight: bold; text-align: center;"> {{$data['total'][$m][2]['previousSS']}} </td>
        <td style="background-color: #e7eff9; color: #000000; font-weight: bold; text-align: center;"> {{$data['total'][$m][2]['previousSap']}} </td>
        <td style="background-color: #e7eff9; color: #000000; font-weight: bold; text-align: center;"> {{$data['total'][$m][2]['pPSap']}} </td>
        <td style="background-color: #e7eff9; color: #000000; font-weight: bold; text-align: center;"> {{number_format($data['total'][$m][2]['currentPlanPercent'],0)}} % </td>
        <td style="background-color: #e7eff9; color: #000000; font-weight: bold; text-align: center;"> {{number_format($data['total'][$m][2]['currentFcstPercent'],0)}} % </td>
        <td style="background-color: #e7eff9; color: #000000; font-weight: bold; text-align: center;"> {{number_format($data['total'][$m][2]['ssPercent'],0)}} % </td>
        <td style="background-color: #e7eff9; color: #000000; font-weight: bold; text-align: center;"> {{number_format($data['total'][$m][2]['pSapPercent'],0)}} % </td>
        <td style="background-color: #e7eff9; color: #000000; font-weight: bold; text-align: center;"> {{number_format($data['total'][$m][2]['ppSapPercent'],0)}} % </td>
    </tr>
@endfor

<tr>
    <td style="background-color: #e6e6e6; color: #000000; font-weight: bold; text-align: center; float: center;" rowspan="4"> (JAN-{{$data['actualMonth']}}) </td>
</tr>

<tr>
        <td style="background-color: #e7eff9; color: #000000; font-weight: bold; text-align: center;"> TV </td>
        <td style="background-color: #e7eff9; color: #000000; font-weight: bold; text-align: center;"> {{$data['disc'][3][0]['currentYTD']+$data['wm'][3][0]['currentYTD']+$data['sony'][3][0]['currentYTD']}} </td>
        <td style="background-color: #e7eff9; color: #000000; font-weight: bold; text-align: center;"> {{$data['total'][3][0]['currentPlan']}} </td>
        <td style="background-color: #e7eff9; color: #000000; font-weight: bold; text-align: center;"> {{$data['total'][3][0]['currentFcst']}} </td>
        <td style="background-color: #e7eff9; color: #000000; font-weight: bold; text-align: center;"> {{$data['total'][3][0]['previousSS']}} </td>
        <td style="background-color: #e7eff9; color: #000000; font-weight: bold; text-align: center;"> {{$data['total'][3][0]['previousSap']}} </td>
        <td style="background-color: #e7eff9; color: #000000; font-weight: bold; text-align: center;"> {{$data['total'][3][0]['pPSap']}} </td>
        <td style="background-color: #e7eff9; color: #000000; font-weight: bold; text-align: center;"> {{number_format($data['total'][3][0]['currentPlanPercent'],0)}} % </td>
        <td style="background-color: #e7eff9; color: #000000; font-weight: bold; text-align: center;"> {{number_format($data['total'][3][0]['currentFcstPercent'],0)}} % </td>
        <td style="background-color: #e7eff9; color: #000000; font-weight: bold; text-align: center;"> {{number_format($data['total'][3][0]['ssPercent'],0)}} % </td>
        <td style="background-color: #e7eff9; color: #000000; font-weight: bold; text-align: center;"> {{number_format($data['total'][3][0]['pSapPercent'],0)}} % </td>
        <td style="background-color: #e7eff9; color: #000000; font-weight: bold; text-align: center;"> {{number_format($data['total'][3][0]['ppSapPercent'],0)}} % </td>
    </tr>

    <tr>
        <td style="background-color: #f9fbfd; color: #000000; font-weight: bold; text-align: center;"> ONL </td>
        <td style="background-color: #f9fbfd; color: #000000; font-weight: bold; text-align: center;"> {{$data['disc'][3][1]['currentYTD']+$data['wm'][3][1]['currentYTD']+$data['sony'][3][1]['currentYTD']}} </td>
        <td style="background-color: #f9fbfd; color: #000000; font-weight: bold; text-align: center;"> {{$data['total'][3][1]['currentPlan']}} </td>
        <td style="background-color: #f9fbfd; color: #000000; font-weight: bold; text-align: center;"> {{$data['total'][3][1]['currentFcst']}} </td>
        <td style="background-color: #f9fbfd; color: #000000; font-weight: bold; text-align: center;"> {{$data['total'][3][1]['previousSS']}} </td>
        <td style="background-color: #f9fbfd; color: #000000; font-weight: bold; text-align: center;"> {{$data['total'][3][1]['previousSap']}} </td>
        <td style="background-color: #f9fbfd; color: #000000; font-weight: bold; text-align: center;"> {{$data['total'][3][1]['pPSap']}} </td>
        <td style="background-color: #f9fbfd; color: #000000; font-weight: bold; text-align: center;"> {{number_format($data['total'][3][1]['currentPlanPercent'],0)}} % </td>
        <td style="background-color: #f9fbfd; color: #000000; font-weight: bold; text-align: center;"> {{number_format($data['total'][3][1]['currentFcstPercent'],0)}} % </td>
        <td style="background-color: #f9fbfd; color: #000000; font-weight: bold; text-align: center;"> {{number_format($data['total'][3][1]['ssPercent'],0)}} % </td>
        <td style="background-color: #f9fbfd; color: #000000; font-weight: bold; text-align: center;"> {{number_format($data['total'][3][1]['pSapPercent'],0)}} % </td>
        <td style="background-color: #f9fbfd; color: #000000; font-weight: bold; text-align: center;"> {{number_format($data['total'][3][1]['ppSapPercent'],0)}} % </td>
    </tr>

    <tr>
        <td style="background-color: #e7eff9; color: #000000; font-weight: bold; text-align: center;"> TOTAL </td>
        <td style="background-color: #e7eff9; color: #000000; font-weight: bold; text-align: center;"> {{$data['disc'][3][2]['currentYTD']+$data['wm'][3][2]['currentYTD']+$data['sony'][3][2]['currentYTD']}} </td>
        <td style="background-color: #e7eff9; color: #000000; font-weight: bold; text-align: center;"> {{$data['total'][3][2]['currentPlan']}} </td>
        <td style="background-color: #e7eff9; color: #000000; font-weight: bold; text-align: center;"> {{$data['total'][3][2]['currentFcst']}} </td>
        <td style="background-color: #e7eff9; color: #000000; font-weight: bold; text-align: center;"> {{$data['total'][3][2]['previousSS']}} </td>
        <td style="background-color: #e7eff9; color: #000000; font-weight: bold; text-align: center;"> {{$data['total'][3][2]['previousSap']}} </td>
        <td style="background-color: #e7eff9; color: #000000; font-weight: bold; text-align: center;"> {{$data['total'][3][2]['pPSap']}} </td>
        <td style="background-color: #e7eff9; color: #000000; font-weight: bold; text-align: center;"> {{number_format($data['total'][3][2]['currentPlanPercent'],0)}} % </td>
        <td style="background-color: #e7eff9; color: #000000; font-weight: bold; text-align: center;"> {{number_format($data['total'][3][2]['currentFcstPercent'],0)}} % </td>
        <td style="background-color: #e7eff9; color: #000000; font-weight: bold; text-align: center;"> {{number_format($data['total'][3][2]['ssPercent'],0)}} % </td>
        <td style="background-color: #e7eff9; color: #000000; font-weight: bold; text-align: center;"> {{number_format($data['total'][3][2]['pSapPercent'],0)}} % </td>
        <td style="background-color: #e7eff9; color: #000000; font-weight: bold; text-align: center;"> {{number_format($data['total'][3][2]['ppSapPercent'],0)}} % </td>
    </tr>

</table>

<table>
<tr>
    <td style="background-color: #0070c0;  color: #ffffff; font-weight: bold; text-align: center;" colspan="13"> Discovery ( {{$data['currencyName']}} / {{strtoupper($data['value'])}} )</td>
</tr>

<tr>
    <td style="background-color: #757171; color: #ffffff; font-weight: bold; text-align: center;"> LOG </td>
    <td style="background-color: #757171; color: #ffffff; font-weight: bold; text-align: center;"> {{$data['realDate']}} </td>
    <td style="background-color: #757171; color: #ffffff; font-weight: bold; text-align: center;" colspan="3"> {{$data['cYear']}}</td>
    <td style="background-color: #757171; color: #ffffff; font-weight: bold; text-align: center;" colspan="2"> {{$data['pYear']}}</td>
    <td style="background-color: #757171; color: #ffffff; font-weight: bold; text-align: center;" colspan="1"> {{$data['ppYear']}}</td>
    <td style="background-color: #757171; color: #ffffff; font-weight: bold; text-align: center;" colspan="5"> {{$data['cYear']}} VAR (%) </td>
</tr>   

<tr>
    <td style="background-color: #a6a6a6; color: #000000; font-weight: bold; text-align: center;"> MONTH </td>
    <td style="background-color: #a6a6a6; color: #000000; font-weight: bold; text-align: center;"> PLATAFORM </td>
    <td style="background-color: #143052; color: #ffffff; font-weight: bold; text-align: center;"> BKGS </td>
    <td style="background-color: #143052; color: #ffffff; font-weight: bold; text-align: center;"> PLAN </td>
    <td style="background-color: #143052; color: #ffffff; font-weight: bold; text-align: center;"> FCST </td>
    <td style="background-color: #143052; color: #ffffff; font-weight: bold; text-align: center;"> SCREENSHOT </td>
    <td style="background-color: #143052; color: #ffffff; font-weight: bold; text-align: center;"> BKGS </td>
    <td style="background-color: #143052; color: #ffffff; font-weight: bold; text-align: center;"> BKGS </td>
    <td style="background-color: #143052; color: #ffffff; font-weight: bold; text-align: center;"> PLAN </td>
    <td style="background-color: #143052; color: #ffffff; font-weight: bold; text-align: center;"> FCST  </td>
    <td style="background-color: #143052; color: #ffffff; font-weight: bold; text-align: center;"> SCREENSHOT </td>
    <td style="background-color: #143052; color: #ffffff; font-weight: bold; text-align: center;"> BKGS {{$data['pYear']}} </td>
    <td style="background-color: #143052; color: #ffffff; font-weight: bold; text-align: center;"> BKGS {{$data['ppYear']}} </td>
</tr>

@for($m = 0; $m < 3; $m++)
    <td style="color: #ffffff;"> {{ $monthForm = $data['base']->intToMonth(array($data['month'] + $m))[0]}}</td> 
     <tr>
        <td style="background-color: #e6e6e6; color: #000000; font-weight: bold; text-align: center; float: center;" rowspan="4"> {{$monthForm}} </td>
    </tr>
    <tr>
        <td style="background-color: #e7eff9; color: #000000; font-weight: bold; text-align: center;"> TV </td>
        <td style="background-color: #e7eff9; color: #000000; font-weight: bold; text-align: center;"> {{$data['disc'][$m][0]['currentYTD']}} </td>
        <td style="background-color: #e7eff9; color: #000000; font-weight: bold; text-align: center;"> {{$data['disc'][$m][0]['currentPlan']}} </td>
        <td style="background-color: #e7eff9; color: #000000; font-weight: bold; text-align: center;"> {{$data['disc'][$m][0]['currentFcst']}} </td>
        <td style="background-color: #e7eff9; color: #000000; font-weight: bold; text-align: center;"> {{$data['disc'][$m][0]['previousSS']}} </td>
        <td style="background-color: #e7eff9; color: #000000; font-weight: bold; text-align: center;"> {{$data['disc'][$m][0]['previousSap']}} </td>
        <td style="background-color: #e7eff9; color: #000000; font-weight: bold; text-align: center;"> {{$data['disc'][$m][0]['pPSap']}} </td>
        <td style="background-color: #e7eff9; color: #000000; font-weight: bold; text-align: center;"> {{number_format($data['disc'][$m][0]['currentPlanPercent'],0)}} % </td>
        <td style="background-color: #e7eff9; color: #000000; font-weight: bold; text-align: center;"> {{number_format($data['disc'][$m][0]['currentFcstPercent'],0)}} % </td>
        <td style="background-color: #e7eff9; color: #000000; font-weight: bold; text-align: center;"> {{number_format($data['disc'][$m][0]['ssPercent'],0)}} % </td>
        <td style="background-color: #e7eff9; color: #000000; font-weight: bold; text-align: center;"> {{number_format($data['disc'][$m][0]['pSapPercent'],0)}} % </td>
        <td style="background-color: #e7eff9; color: #000000; font-weight: bold; text-align: center;"> {{number_format($data['disc'][$m][0]['ppSapPercent'],0)}} % </td>
    </tr>

    <tr>
        <td style="background-color: #f9fbfd; color: #000000; font-weight: bold; text-align: center;"> ONL </td>
        <td style="background-color: #f9fbfd; color: #000000; font-weight: bold; text-align: center;"> {{$data['disc'][$m][1]['currentYTD']}} </td>
        <td style="background-color: #f9fbfd; color: #000000; font-weight: bold; text-align: center;"> {{$data['disc'][$m][1]['currentPlan']}} </td>
        <td style="background-color: #f9fbfd; color: #000000; font-weight: bold; text-align: center;"> {{$data['disc'][$m][1]['currentFcst']}} </td>
        <td style="background-color: #f9fbfd; color: #000000; font-weight: bold; text-align: center;"> {{$data['disc'][$m][1]['previousSS']}} </td>
        <td style="background-color: #f9fbfd; color: #000000; font-weight: bold; text-align: center;"> {{$data['disc'][$m][1]['previousSap']}} </td>
        <td style="background-color: #f9fbfd; color: #000000; font-weight: bold; text-align: center;"> {{$data['disc'][$m][1]['pPSap']}} </td>
        <td style="background-color: #f9fbfd; color: #000000; font-weight: bold; text-align: center;"> {{number_format($data['disc'][$m][1]['currentPlanPercent'],0)}} % </td>
        <td style="background-color: #f9fbfd; color: #000000; font-weight: bold; text-align: center;"> {{number_format($data['disc'][$m][1]['currentFcstPercent'],0)}} % </td>
        <td style="background-color: #f9fbfd; color: #000000; font-weight: bold; text-align: center;"> {{number_format($data['disc'][$m][1]['ssPercent'],0)}} % </td>
        <td style="background-color: #f9fbfd; color: #000000; font-weight: bold; text-align: center;"> {{number_format($data['disc'][$m][1]['pSapPercent'],0)}} % </td>
        <td style="background-color: #f9fbfd; color: #000000; font-weight: bold; text-align: center;"> {{number_format($data['disc'][$m][1]['ppSapPercent'],0)}} % </td>
    </tr>

    <tr>
        <td style="background-color: #e7eff9; color: #000000; font-weight: bold; text-align: center;"> TOTAL </td>
        <td style="background-color: #e7eff9; color: #000000; font-weight: bold; text-align: center;"> {{$data['disc'][$m][2]['currentYTD']}} </td>
        <td style="background-color: #e7eff9; color: #000000; font-weight: bold; text-align: center;"> {{$data['disc'][$m][2]['currentPlan']}} </td>
        <td style="background-color: #e7eff9; color: #000000; font-weight: bold; text-align: center;"> {{$data['disc'][$m][2]['currentFcst']}} </td>
        <td style="background-color: #e7eff9; color: #000000; font-weight: bold; text-align: center;"> {{$data['disc'][$m][2]['previousSS']}} </td>
        <td style="background-color: #e7eff9; color: #000000; font-weight: bold; text-align: center;"> {{$data['disc'][$m][2]['previousSap']}} </td>
        <td style="background-color: #e7eff9; color: #000000; font-weight: bold; text-align: center;"> {{$data['disc'][$m][2]['pPSap']}} </td>
        <td style="background-color: #e7eff9; color: #000000; font-weight: bold; text-align: center;"> {{number_format($data['disc'][$m][2]['currentPlanPercent'],0)}} % </td>
        <td style="background-color: #e7eff9; color: #000000; font-weight: bold; text-align: center;"> {{number_format($data['disc'][$m][2]['currentFcstPercent'],0)}} % </td>
        <td style="background-color: #e7eff9; color: #000000; font-weight: bold; text-align: center;"> {{number_format($data['disc'][$m][2]['ssPercent'],0)}} % </td>
        <td style="background-color: #e7eff9; color: #000000; font-weight: bold; text-align: center;"> {{number_format($data['disc'][$m][2]['pSapPercent'],0)}} % </td>
        <td style="background-color: #e7eff9; color: #000000; font-weight: bold; text-align: center;"> {{number_format($data['disc'][$m][2]['ppSapPercent'],0)}} % </td>
    </tr>
@endfor

<tr>
    <td style="background-color: #e6e6e6; color: #000000; font-weight: bold; text-align: center; float: center;" rowspan="4"> (JAN-{{$data['actualMonth']}}) </td>
</tr>

<tr>
        <td style="background-color: #e7eff9; color: #000000; font-weight: bold; text-align: center;"> TV </td>
        <td style="background-color: #e7eff9; color: #000000; font-weight: bold; text-align: center;"> {{$data['disc'][3][0]['currentYTD']}} </td>
        <td style="background-color: #e7eff9; color: #000000; font-weight: bold; text-align: center;"> {{$data['disc'][3][0]['currentPlan']}} </td>
        <td style="background-color: #e7eff9; color: #000000; font-weight: bold; text-align: center;"> {{$data['disc'][3][0]['currentFcst']}} </td>
        <td style="background-color: #e7eff9; color: #000000; font-weight: bold; text-align: center;"> {{$data['disc'][3][0]['previousSS']}} </td>
        <td style="background-color: #e7eff9; color: #000000; font-weight: bold; text-align: center;"> {{$data['disc'][3][0]['previousSap']}} </td>
        <td style="background-color: #e7eff9; color: #000000; font-weight: bold; text-align: center;"> {{$data['disc'][3][0]['pPSap']}} </td>
        <td style="background-color: #e7eff9; color: #000000; font-weight: bold; text-align: center;"> {{number_format($data['disc'][3][0]['currentPlanPercent'],0)}} % </td>
        <td style="background-color: #e7eff9; color: #000000; font-weight: bold; text-align: center;"> {{number_format($data['disc'][3][0]['currentFcstPercent'],0)}} % </td>
        <td style="background-color: #e7eff9; color: #000000; font-weight: bold; text-align: center;"> {{number_format($data['disc'][3][0]['ssPercent'],0)}} % </td>
        <td style="background-color: #e7eff9; color: #000000; font-weight: bold; text-align: center;"> {{number_format($data['disc'][3][0]['pSapPercent'],0)}} % </td>
        <td style="background-color: #e7eff9; color: #000000; font-weight: bold; text-align: center;"> {{number_format($data['disc'][3][0]['ppSapPercent'],0)}} % </td>
    </tr>

    <tr>
        <td style="background-color: #f9fbfd; color: #000000; font-weight: bold; text-align: center;"> ONL </td>
        <td style="background-color: #f9fbfd; color: #000000; font-weight: bold; text-align: center;"> {{$data['disc'][3][1]['currentYTD']}} </td>
        <td style="background-color: #f9fbfd; color: #000000; font-weight: bold; text-align: center;"> {{$data['disc'][3][1]['currentPlan']}} </td>
        <td style="background-color: #f9fbfd; color: #000000; font-weight: bold; text-align: center;"> {{$data['disc'][3][1]['currentFcst']}} </td>
        <td style="background-color: #f9fbfd; color: #000000; font-weight: bold; text-align: center;"> {{$data['disc'][3][1]['previousSS']}} </td>
        <td style="background-color: #f9fbfd; color: #000000; font-weight: bold; text-align: center;"> {{$data['disc'][3][1]['previousSap']}} </td>
        <td style="background-color: #f9fbfd; color: #000000; font-weight: bold; text-align: center;"> {{$data['disc'][3][1]['pPSap']}} </td>
        <td style="background-color: #f9fbfd; color: #000000; font-weight: bold; text-align: center;"> {{number_format($data['disc'][3][1]['currentPlanPercent'],0)}} % </td>
        <td style="background-color: #f9fbfd; color: #000000; font-weight: bold; text-align: center;"> {{number_format($data['disc'][3][1]['currentFcstPercent'],0)}} % </td>
        <td style="background-color: #f9fbfd; color: #000000; font-weight: bold; text-align: center;"> {{number_format($data['disc'][3][1]['ssPercent'],0)}} % </td>
        <td style="background-color: #f9fbfd; color: #000000; font-weight: bold; text-align: center;"> {{number_format($data['disc'][3][1]['pSapPercent'],0)}} % </td>
        <td style="background-color: #f9fbfd; color: #000000; font-weight: bold; text-align: center;"> {{number_format($data['disc'][3][1]['ppSapPercent'],0)}} % </td>
    </tr>

    <tr>
        <td style="background-color: #e7eff9; color: #000000; font-weight: bold; text-align: center;"> TOTAL </td>
        <td style="background-color: #e7eff9; color: #000000; font-weight: bold; text-align: center;"> {{$data['disc'][3][2]['currentYTD']}} </td>
        <td style="background-color: #e7eff9; color: #000000; font-weight: bold; text-align: center;"> {{$data['disc'][3][2]['currentPlan']}} </td>
        <td style="background-color: #e7eff9; color: #000000; font-weight: bold; text-align: center;"> {{$data['disc'][3][2]['currentFcst']}} </td>
        <td style="background-color: #e7eff9; color: #000000; font-weight: bold; text-align: center;"> {{$data['disc'][3][2]['previousSS']}} </td>
        <td style="background-color: #e7eff9; color: #000000; font-weight: bold; text-align: center;"> {{$data['disc'][3][2]['previousSap']}} </td>
        <td style="background-color: #e7eff9; color: #000000; font-weight: bold; text-align: center;"> {{$data['disc'][3][2]['pPSap']}} </td>
        <td style="background-color: #e7eff9; color: #000000; font-weight: bold; text-align: center;"> {{number_format($data['disc'][3][2]['currentPlanPercent'],0)}} % </td>
        <td style="background-color: #e7eff9; color: #000000; font-weight: bold; text-align: center;"> {{number_format($data['disc'][3][2]['currentFcstPercent'],0)}} % </td>
        <td style="background-color: #e7eff9; color: #000000; font-weight: bold; text-align: center;"> {{number_format($data['disc'][3][2]['ssPercent'],0)}} % </td>
        <td style="background-color: #e7eff9; color: #000000; font-weight: bold; text-align: center;"> {{number_format($data['disc'][3][2]['pSapPercent'],0)}} % </td>
        <td style="background-color: #e7eff9; color: #000000; font-weight: bold; text-align: center;"> {{number_format($data['disc'][3][2]['ppSapPercent'],0)}} % </td>
    </tr>

</table>

<table>
<tr>
    <td style="background-color: #000000;  color: #ffffff; font-weight: bold; text-align: center;" colspan="13"> SONY ( {{$data['currencyName']}} / {{strtoupper($data['value'])}} )</td>
</tr>

<tr>
    <td style="background-color: #757171; color: #ffffff; font-weight: bold; text-align: center;"> LOG </td>
    <td style="background-color: #757171; color: #ffffff; font-weight: bold; text-align: center;"> {{$data['realDate']}} </td>
    <td style="background-color: #757171; color: #ffffff; font-weight: bold; text-align: center;" colspan="3"> {{$data['cYear']}}</td>
    <td style="background-color: #757171; color: #ffffff; font-weight: bold; text-align: center;" colspan="2"> {{$data['pYear']}}</td>
    <td style="background-color: #757171; color: #ffffff; font-weight: bold; text-align: center;" colspan="1"> {{$data['ppYear']}}</td>
    <td style="background-color: #757171; color: #ffffff; font-weight: bold; text-align: center;" colspan="5"> {{$data['cYear']}} VAR (%) </td>
</tr>   

<tr>
    <td style="background-color: #a6a6a6; color: #000000; font-weight: bold; text-align: center;"> MONTH </td>
    <td style="background-color: #a6a6a6; color: #000000; font-weight: bold; text-align: center;"> PLATAFORM </td>
    <td style="background-color: #143052; color: #ffffff; font-weight: bold; text-align: center;"> BKGS </td>
    <td style="background-color: #143052; color: #ffffff; font-weight: bold; text-align: center;"> PLAN </td>
    <td style="background-color: #143052; color: #ffffff; font-weight: bold; text-align: center;"> FCST </td>
    <td style="background-color: #143052; color: #ffffff; font-weight: bold; text-align: center;"> SCREENSHOT </td>
    <td style="background-color: #143052; color: #ffffff; font-weight: bold; text-align: center;"> BKGS </td>
    <td style="background-color: #143052; color: #ffffff; font-weight: bold; text-align: center;"> BKGS </td>
    <td style="background-color: #143052; color: #ffffff; font-weight: bold; text-align: center;"> PLAN </td>
    <td style="background-color: #143052; color: #ffffff; font-weight: bold; text-align: center;"> FCST  </td>
    <td style="background-color: #143052; color: #ffffff; font-weight: bold; text-align: center;"> SCREENSHOT </td>
    <td style="background-color: #143052; color: #ffffff; font-weight: bold; text-align: center;"> BKGS {{$data['pYear']}} </td>
    <td style="background-color: #143052; color: #ffffff; font-weight: bold; text-align: center;"> BKGS {{$data['ppYear']}} </td>
</tr>

@for($m = 0; $m < 3; $m++)
    <td style="color: #ffffff;"> {{ $monthForm = $data['base']->intToMonth(array($data['month'] + $m))[0]}}</td> 
     <tr>
        <td style="background-color: #e6e6e6; color: #000000; font-weight: bold; text-align: center; margin-left: 50%;" rowspan="4"> {{$monthForm}} </td>
    </tr>
    <tr>
        <td style="background-color: #e7eff9; color: #000000; font-weight: bold; text-align: center;"> TV </td>
        <td style="background-color: #e7eff9; color: #000000; font-weight: bold; text-align: center;"> {{$data['sony'][$m][0]['currentYTD']}} </td>
        <td style="background-color: #e7eff9; color: #000000; font-weight: bold; text-align: center;"> {{$data['sony'][$m][0]['currentPlan']}} </td>
        <td style="background-color: #e7eff9; color: #000000; font-weight: bold; text-align: center;"> {{$data['sony'][$m][0]['currentFcst']}} </td>
        <td style="background-color: #e7eff9; color: #000000; font-weight: bold; text-align: center;"> {{$data['sony'][$m][0]['previousSS']}} </td>
        <td style="background-color: #e7eff9; color: #000000; font-weight: bold; text-align: center;"> {{$data['sony'][$m][0]['previousSap']}} </td>
        <td style="background-color: #e7eff9; color: #000000; font-weight: bold; text-align: center;"> {{$data['sony'][$m][0]['pPSap']}} </td>
        <td style="background-color: #e7eff9; color: #000000; font-weight: bold; text-align: center;"> {{number_format($data['sony'][$m][0]['currentPlanPercent'],0)}} % </td>
        <td style="background-color: #e7eff9; color: #000000; font-weight: bold; text-align: center;"> {{number_format($data['sony'][$m][0]['currentFcstPercent'],0)}} % </td>
        <td style="background-color: #e7eff9; color: #000000; font-weight: bold; text-align: center;"> {{number_format($data['sony'][$m][0]['ssPercent'],0)}} % </td>
        <td style="background-color: #e7eff9; color: #000000; font-weight: bold; text-align: center;"> {{number_format($data['sony'][$m][0]['pSapPercent'],0)}} % </td>
        <td style="background-color: #e7eff9; color: #000000; font-weight: bold; text-align: center;"> {{number_format($data['sony'][$m][0]['ppSapPercent'],0)}} % </td>
    </tr>

    <tr>
        <td style="background-color: #f9fbfd; color: #000000; font-weight: bold; text-align: center;"> ONL </td>
        <td style="background-color: #f9fbfd; color: #000000; font-weight: bold; text-align: center;"> {{$data['sony'][$m][1]['currentYTD']}} </td>
        <td style="background-color: #f9fbfd; color: #000000; font-weight: bold; text-align: center;"> {{$data['sony'][$m][1]['currentPlan']}} </td>
        <td style="background-color: #f9fbfd; color: #000000; font-weight: bold; text-align: center;"> {{$data['sony'][$m][1]['currentFcst']}} </td>
        <td style="background-color: #f9fbfd; color: #000000; font-weight: bold; text-align: center;"> {{$data['sony'][$m][1]['previousSS']}} </td>
        <td style="background-color: #f9fbfd; color: #000000; font-weight: bold; text-align: center;"> {{$data['sony'][$m][1]['previousSap']}} </td>
        <td style="background-color: #f9fbfd; color: #000000; font-weight: bold; text-align: center;"> {{$data['sony'][$m][1]['pPSap']}} </td>
        <td style="background-color: #f9fbfd; color: #000000; font-weight: bold; text-align: center;"> {{number_format($data['sony'][$m][1]['currentPlanPercent'],0)}} % </td>
        <td style="background-color: #f9fbfd; color: #000000; font-weight: bold; text-align: center;"> {{number_format($data['sony'][$m][1]['currentFcstPercent'],0)}} % </td>
        <td style="background-color: #f9fbfd; color: #000000; font-weight: bold; text-align: center;"> {{number_format($data['sony'][$m][1]['ssPercent'],0)}} % </td>
        <td style="background-color: #f9fbfd; color: #000000; font-weight: bold; text-align: center;"> {{number_format($data['sony'][$m][1]['pSapPercent'],0)}} % </td>
        <td style="background-color: #f9fbfd; color: #000000; font-weight: bold; text-align: center;"> {{number_format($data['sony'][$m][1]['ppSapPercent'],0)}} % </td>
    </tr>

    <tr>
        <td style="background-color: #e7eff9; color: #000000; font-weight: bold; text-align: center;"> TOTAL </td>
        <td style="background-color: #e7eff9; color: #000000; font-weight: bold; text-align: center;"> {{$data['sony'][$m][2]['currentYTD']}} </td>
        <td style="background-color: #e7eff9; color: #000000; font-weight: bold; text-align: center;"> {{$data['sony'][$m][2]['currentPlan']}} </td>
        <td style="background-color: #e7eff9; color: #000000; font-weight: bold; text-align: center;"> {{$data['sony'][$m][2]['currentFcst']}} </td>
        <td style="background-color: #e7eff9; color: #000000; font-weight: bold; text-align: center;"> {{$data['sony'][$m][2]['previousSS']}} </td>
        <td style="background-color: #e7eff9; color: #000000; font-weight: bold; text-align: center;"> {{$data['sony'][$m][2]['previousSap']}} </td>
        <td style="background-color: #e7eff9; color: #000000; font-weight: bold; text-align: center;"> {{$data['sony'][$m][2]['pPSap']}} </td>
        <td style="background-color: #e7eff9; color: #000000; font-weight: bold; text-align: center;"> {{number_format($data['sony'][$m][2]['currentPlanPercent'],0)}} % </td>
        <td style="background-color: #e7eff9; color: #000000; font-weight: bold; text-align: center;"> {{number_format($data['sony'][$m][2]['currentFcstPercent'],0)}} % </td>
        <td style="background-color: #e7eff9; color: #000000; font-weight: bold; text-align: center;"> {{number_format($data['sony'][$m][2]['ssPercent'],0)}} % </td>
        <td style="background-color: #e7eff9; color: #000000; font-weight: bold; text-align: center;"> {{number_format($data['sony'][$m][2]['pSapPercent'],0)}} % </td>
        <td style="background-color: #e7eff9; color: #000000; font-weight: bold; text-align: center;"> {{number_format($data['sony'][$m][2]['ppSapPercent'],0)}} % </td>
    </tr>
@endfor

<tr>
    <td style="background-color: #e6e6e6; color: #000000; font-weight: bold; text-align: center; float: center;" rowspan="4"> (JAN-{{$data['actualMonth']}}) </td>
</tr>

<tr>
        <td style="background-color: #e7eff9; color: #000000; font-weight: bold; text-align: center;"> TV </td>
        <td style="background-color: #e7eff9; color: #000000; font-weight: bold; text-align: center;"> {{$data['sony'][3][0]['currentYTD']}} </td>
        <td style="background-color: #e7eff9; color: #000000; font-weight: bold; text-align: center;"> {{$data['sony'][3][0]['currentPlan']}} </td>
        <td style="background-color: #e7eff9; color: #000000; font-weight: bold; text-align: center;"> {{$data['sony'][3][0]['currentFcst']}} </td>
        <td style="background-color: #e7eff9; color: #000000; font-weight: bold; text-align: center;"> {{$data['sony'][3][0]['previousSS']}} </td>
        <td style="background-color: #e7eff9; color: #000000; font-weight: bold; text-align: center;"> {{$data['sony'][3][0]['previousSap']}} </td>
        <td style="background-color: #e7eff9; color: #000000; font-weight: bold; text-align: center;"> {{$data['sony'][3][0]['pPSap']}} </td>
        <td style="background-color: #e7eff9; color: #000000; font-weight: bold; text-align: center;"> {{number_format($data['sony'][3][0]['currentPlanPercent'],0)}} % </td>
        <td style="background-color: #e7eff9; color: #000000; font-weight: bold; text-align: center;"> {{number_format($data['sony'][3][0]['currentFcstPercent'],0)}} % </td>
        <td style="background-color: #e7eff9; color: #000000; font-weight: bold; text-align: center;"> {{number_format($data['sony'][3][0]['ssPercent'],0)}} % </td>
        <td style="background-color: #e7eff9; color: #000000; font-weight: bold; text-align: center;"> {{number_format($data['sony'][3][0]['pSapPercent'],0)}} % </td>
        <td style="background-color: #e7eff9; color: #000000; font-weight: bold; text-align: center;"> {{number_format($data['sony'][3][0]['ppSapPercent'],0)}} % </td>
    </tr>

    <tr>
        <td style="background-color: #f9fbfd; color: #000000; font-weight: bold; text-align: center;"> ONL </td>
        <td style="background-color: #f9fbfd; color: #000000; font-weight: bold; text-align: center;"> {{$data['sony'][3][1]['currentYTD']}} </td>
        <td style="background-color: #f9fbfd; color: #000000; font-weight: bold; text-align: center;"> {{$data['sony'][3][1]['currentPlan']}} </td>
        <td style="background-color: #f9fbfd; color: #000000; font-weight: bold; text-align: center;"> {{$data['sony'][3][1]['currentFcst']}} </td>
        <td style="background-color: #f9fbfd; color: #000000; font-weight: bold; text-align: center;"> {{$data['sony'][3][1]['previousSS']}} </td>
        <td style="background-color: #f9fbfd; color: #000000; font-weight: bold; text-align: center;"> {{$data['sony'][3][1]['previousSap']}} </td>
        <td style="background-color: #f9fbfd; color: #000000; font-weight: bold; text-align: center;"> {{$data['sony'][3][1]['pPSap']}} </td>
        <td style="background-color: #f9fbfd; color: #000000; font-weight: bold; text-align: center;"> {{number_format($data['sony'][3][1]['currentPlanPercent'],0)}} % </td>
        <td style="background-color: #f9fbfd; color: #000000; font-weight: bold; text-align: center;"> {{number_format($data['sony'][3][1]['currentFcstPercent'],0)}} % </td>
        <td style="background-color: #f9fbfd; color: #000000; font-weight: bold; text-align: center;"> {{number_format($data['sony'][3][1]['ssPercent'],0)}} % </td>
        <td style="background-color: #f9fbfd; color: #000000; font-weight: bold; text-align: center;"> {{number_format($data['sony'][3][1]['pSapPercent'],0)}} % </td>
        <td style="background-color: #f9fbfd; color: #000000; font-weight: bold; text-align: center;"> {{number_format($data['sony'][3][1]['ppSapPercent'],0)}} % </td>
    </tr>

    <tr>
        <td style="background-color: #e7eff9; color: #000000; font-weight: bold; text-align: center;"> TOTAL </td>
        <td style="background-color: #e7eff9; color: #000000; font-weight: bold; text-align: center;"> {{$data['sony'][3][2]['currentYTD']}} </td>
        <td style="background-color: #e7eff9; color: #000000; font-weight: bold; text-align: center;"> {{$data['sony'][3][2]['currentPlan']}} </td>
        <td style="background-color: #e7eff9; color: #000000; font-weight: bold; text-align: center;"> {{$data['sony'][3][2]['currentFcst']}} </td>
        <td style="background-color: #e7eff9; color: #000000; font-weight: bold; text-align: center;"> {{$data['sony'][3][2]['previousSS']}} </td>
        <td style="background-color: #e7eff9; color: #000000; font-weight: bold; text-align: center;"> {{$data['sony'][3][2]['previousSap']}} </td>
        <td style="background-color: #e7eff9; color: #000000; font-weight: bold; text-align: center;"> {{$data['sony'][3][2]['pPSap']}} </td>
        <td style="background-color: #e7eff9; color: #000000; font-weight: bold; text-align: center;"> {{number_format($data['sony'][3][2]['currentPlanPercent'],0)}} % </td>
        <td style="background-color: #e7eff9; color: #000000; font-weight: bold; text-align: center;"> {{number_format($data['sony'][3][2]['currentFcstPercent'],0)}} % </td>
        <td style="background-color: #e7eff9; color: #000000; font-weight: bold; text-align: center;"> {{number_format($data['sony'][3][2]['ssPercent'],0)}} % </td>
        <td style="background-color: #e7eff9; color: #000000; font-weight: bold; text-align: center;"> {{number_format($data['sony'][3][2]['pSapPercent'],0)}} % </td>
        <td style="background-color: #e7eff9; color: #000000; font-weight: bold; text-align: center;"> {{number_format($data['sony'][3][2]['ppSapPercent'],0)}} % </td>
    </tr>

</table>

<table>
<tr>
    <td style="background-color: #0f243e;  color: #ffffff; font-weight: bold; text-align: center;" colspan="13"> WARNER MIDIA ( {{$data['currencyName']}} / {{strtoupper($data['value'])}} )</td>
</tr>

<tr>
    <td style="background-color: #757171; color: #ffffff; font-weight: bold; text-align: center;"> LOG </td>
    <td style="background-color: #757171; color: #ffffff; font-weight: bold; text-align: center;"> {{$data['realDate']}} </td>
    <td style="background-color: #757171; color: #ffffff; font-weight: bold; text-align: center;" colspan="3"> {{$data['cYear']}}</td>
    <td style="background-color: #757171; color: #ffffff; font-weight: bold; text-align: center;" colspan="2"> {{$data['pYear']}}</td>
    <td style="background-color: #757171; color: #ffffff; font-weight: bold; text-align: center;" colspan="1"> {{$data['ppYear']}}</td>
    <td style="background-color: #757171; color: #ffffff; font-weight: bold; text-align: center;" colspan="5"> {{$data['cYear']}} VAR (%) </td>
</tr>   

<tr>
    <td style="background-color: #a6a6a6; color: #000000; font-weight: bold; text-align: center;"> MONTH </td>
    <td style="background-color: #a6a6a6; color: #000000; font-weight: bold; text-align: center;"> PLATAFORM </td>
    <td style="background-color: #143052; color: #ffffff; font-weight: bold; text-align: center;"> BKGS </td>
    <td style="background-color: #143052; color: #ffffff; font-weight: bold; text-align: center;"> PLAN </td>
    <td style="background-color: #143052; color: #ffffff; font-weight: bold; text-align: center;"> FCST </td>
    <td style="background-color: #143052; color: #ffffff; font-weight: bold; text-align: center;"> SCREENSHOT </td>
    <td style="background-color: #143052; color: #ffffff; font-weight: bold; text-align: center;"> BKGS </td>
    <td style="background-color: #143052; color: #ffffff; font-weight: bold; text-align: center;"> BKGS </td>
    <td style="background-color: #143052; color: #ffffff; font-weight: bold; text-align: center;"> PLAN </td>
    <td style="background-color: #143052; color: #ffffff; font-weight: bold; text-align: center;"> FCST  </td>
    <td style="background-color: #143052; color: #ffffff; font-weight: bold; text-align: center;"> SCREENSHOT </td>
    <td style="background-color: #143052; color: #ffffff; font-weight: bold; text-align: center;"> BKGS {{$data['pYear']}} </td>
    <td style="background-color: #143052; color: #ffffff; font-weight: bold; text-align: center;"> BKGS {{$data['ppYear']}} </td>
</tr>

@for($m = 0; $m < 3; $m++)
    <td style="color: #ffffff;"> {{ $monthForm = $data['base']->intToMonth(array($data['month'] + $m))[0]}}</td> 
     <tr>
        <td style="background-color: #e6e6e6; color: #000000; font-weight: bold; text-align: center; margin-left: 50%;" rowspan="4"> {{$monthForm}} </td>
    </tr>
    <tr>
        <td style="background-color: #e7eff9; color: #000000; font-weight: bold; text-align: center;"> TV </td>
        <td style="background-color: #e7eff9; color: #000000; font-weight: bold; text-align: center;"> {{$data['wm'][$m][0]['currentYTD']}} </td>
        <td style="background-color: #e7eff9; color: #000000; font-weight: bold; text-align: center;"> {{$data['wm'][$m][0]['currentPlan']}} </td>
        <td style="background-color: #e7eff9; color: #000000; font-weight: bold; text-align: center;"> {{$data['wm'][$m][0]['currentFcst']}} </td>
        <td style="background-color: #e7eff9; color: #000000; font-weight: bold; text-align: center;"> {{$data['wm'][$m][0]['previousSS']}} </td>
        <td style="background-color: #e7eff9; color: #000000; font-weight: bold; text-align: center;"> {{$data['wm'][$m][0]['previousSap']}} </td>
        <td style="background-color: #e7eff9; color: #000000; font-weight: bold; text-align: center;"> {{$data['wm'][$m][0]['pPSap']}} </td>
        <td style="background-color: #e7eff9; color: #000000; font-weight: bold; text-align: center;"> {{number_format($data['wm'][$m][0]['currentPlanPercent'],0)}} % </td>
        <td style="background-color: #e7eff9; color: #000000; font-weight: bold; text-align: center;"> {{number_format($data['wm'][$m][0]['currentFcstPercent'],0)}} % </td>
        <td style="background-color: #e7eff9; color: #000000; font-weight: bold; text-align: center;"> {{number_format($data['wm'][$m][0]['ssPercent'],0)}} % </td>
        <td style="background-color: #e7eff9; color: #000000; font-weight: bold; text-align: center;"> {{number_format($data['wm'][$m][0]['pSapPercent'],0)}} % </td>
        <td style="background-color: #e7eff9; color: #000000; font-weight: bold; text-align: center;"> {{number_format($data['wm'][$m][0]['ppSapPercent'],0)}} % </td>
    </tr>

    <tr>
        <td style="background-color: #f9fbfd; color: #000000; font-weight: bold; text-align: center;"> ONL </td>
        <td style="background-color: #f9fbfd; color: #000000; font-weight: bold; text-align: center;"> {{$data['wm'][$m][1]['currentYTD']}} </td>
        <td style="background-color: #f9fbfd; color: #000000; font-weight: bold; text-align: center;"> {{$data['wm'][$m][1]['currentPlan']}} </td>
        <td style="background-color: #f9fbfd; color: #000000; font-weight: bold; text-align: center;"> {{$data['wm'][$m][1]['currentFcst']}} </td>
        <td style="background-color: #f9fbfd; color: #000000; font-weight: bold; text-align: center;"> {{$data['wm'][$m][1]['previousSS']}} </td>
        <td style="background-color: #f9fbfd; color: #000000; font-weight: bold; text-align: center;"> {{$data['wm'][$m][1]['previousSap']}} </td>
        <td style="background-color: #f9fbfd; color: #000000; font-weight: bold; text-align: center;"> {{$data['wm'][$m][1]['pPSap']}} </td>
        <td style="background-color: #f9fbfd; color: #000000; font-weight: bold; text-align: center;"> {{number_format($data['wm'][$m][1]['currentPlanPercent'],0)}} % </td>
        <td style="background-color: #f9fbfd; color: #000000; font-weight: bold; text-align: center;"> {{number_format($data['wm'][$m][1]['currentFcstPercent'],0)}} % </td>
        <td style="background-color: #f9fbfd; color: #000000; font-weight: bold; text-align: center;"> {{number_format($data['wm'][$m][1]['ssPercent'],0)}} % </td>
        <td style="background-color: #f9fbfd; color: #000000; font-weight: bold; text-align: center;"> {{number_format($data['wm'][$m][1]['pSapPercent'],0)}} % </td>
        <td style="background-color: #f9fbfd; color: #000000; font-weight: bold; text-align: center;"> {{number_format($data['wm'][$m][1]['ppSapPercent'],0)}} % </td>
    </tr>

    <tr>
        <td style="background-color: #e7eff9; color: #000000; font-weight: bold; text-align: center;"> TOTAL </td>
        <td style="background-color: #e7eff9; color: #000000; font-weight: bold; text-align: center;"> {{$data['wm'][$m][2]['currentYTD']}} </td>
        <td style="background-color: #e7eff9; color: #000000; font-weight: bold; text-align: center;"> {{$data['wm'][$m][2]['currentPlan']}} </td>
        <td style="background-color: #e7eff9; color: #000000; font-weight: bold; text-align: center;"> {{$data['wm'][$m][2]['currentFcst']}} </td>
        <td style="background-color: #e7eff9; color: #000000; font-weight: bold; text-align: center;"> {{$data['wm'][$m][2]['previousSS']}} </td>
        <td style="background-color: #e7eff9; color: #000000; font-weight: bold; text-align: center;"> {{$data['wm'][$m][2]['previousSap']}} </td>
        <td style="background-color: #e7eff9; color: #000000; font-weight: bold; text-align: center;"> {{$data['wm'][$m][2]['pPSap']}} </td>
        <td style="background-color: #e7eff9; color: #000000; font-weight: bold; text-align: center;"> {{number_format($data['wm'][$m][2]['currentPlanPercent'],0)}} % </td>
        <td style="background-color: #e7eff9; color: #000000; font-weight: bold; text-align: center;"> {{number_format($data['wm'][$m][2]['currentFcstPercent'],0)}} % </td>
        <td style="background-color: #e7eff9; color: #000000; font-weight: bold; text-align: center;"> {{number_format($data['wm'][$m][2]['ssPercent'],0)}} % </td>
        <td style="background-color: #e7eff9; color: #000000; font-weight: bold; text-align: center;"> {{number_format($data['wm'][$m][2]['pSapPercent'],0)}} % </td>
        <td style="background-color: #e7eff9; color: #000000; font-weight: bold; text-align: center;"> {{number_format($data['wm'][$m][2]['ppSapPercent'],0)}} % </td>
    </tr>
@endfor

<tr>
    <td style="background-color: #e6e6e6; color: #000000; font-weight: bold; text-align: center; float: center;" rowspan="4"> (JAN-{{$data['actualMonth']}}) </td>
</tr>

<tr>
        <td style="background-color: #e7eff9; color: #000000; font-weight: bold; text-align: center;"> TV </td>
        <td style="background-color: #e7eff9; color: #000000; font-weight: bold; text-align: center;"> {{$data['wm'][3][0]['currentYTD']}} </td>
        <td style="background-color: #e7eff9; color: #000000; font-weight: bold; text-align: center;"> {{$data['wm'][3][0]['currentPlan']}} </td>
        <td style="background-color: #e7eff9; color: #000000; font-weight: bold; text-align: center;"> {{$data['wm'][3][0]['currentFcst']}} </td>
        <td style="background-color: #e7eff9; color: #000000; font-weight: bold; text-align: center;"> {{$data['wm'][3][0]['previousSS']}} </td>
        <td style="background-color: #e7eff9; color: #000000; font-weight: bold; text-align: center;"> {{$data['wm'][3][0]['previousSap']}} </td>
        <td style="background-color: #e7eff9; color: #000000; font-weight: bold; text-align: center;"> {{$data['wm'][3][0]['pPSap']}} </td>
        <td style="background-color: #e7eff9; color: #000000; font-weight: bold; text-align: center;"> {{number_format($data['wm'][3][0]['currentPlanPercent'],0)}} % </td>
        <td style="background-color: #e7eff9; color: #000000; font-weight: bold; text-align: center;"> {{number_format($data['wm'][3][0]['currentFcstPercent'],0)}} % </td>
        <td style="background-color: #e7eff9; color: #000000; font-weight: bold; text-align: center;"> {{number_format($data['wm'][3][0]['ssPercent'],0)}} % </td>
        <td style="background-color: #e7eff9; color: #000000; font-weight: bold; text-align: center;"> {{number_format($data['wm'][3][0]['pSapPercent'],0)}} % </td>
        <td style="background-color: #e7eff9; color: #000000; font-weight: bold; text-align: center;"> {{number_format($data['wm'][3][0]['ppSapPercent'],0)}} % </td>
    </tr>

    <tr>
        <td style="background-color: #f9fbfd; color: #000000; font-weight: bold; text-align: center;"> ONL </td>
        <td style="background-color: #f9fbfd; color: #000000; font-weight: bold; text-align: center;"> {{$data['wm'][3][1]['currentYTD']}} </td>
        <td style="background-color: #f9fbfd; color: #000000; font-weight: bold; text-align: center;"> {{$data['wm'][3][1]['currentPlan']}} </td>
        <td style="background-color: #f9fbfd; color: #000000; font-weight: bold; text-align: center;"> {{$data['wm'][3][1]['currentFcst']}} </td>
        <td style="background-color: #f9fbfd; color: #000000; font-weight: bold; text-align: center;"> {{$data['wm'][3][1]['previousSS']}} </td>
        <td style="background-color: #f9fbfd; color: #000000; font-weight: bold; text-align: center;"> {{$data['wm'][3][1]['previousSap']}} </td>
        <td style="background-color: #f9fbfd; color: #000000; font-weight: bold; text-align: center;"> {{$data['wm'][3][1]['pPSap']}} </td>
        <td style="background-color: #f9fbfd; color: #000000; font-weight: bold; text-align: center;"> {{number_format($data['wm'][3][1]['currentPlanPercent'],0)}} % </td>
        <td style="background-color: #f9fbfd; color: #000000; font-weight: bold; text-align: center;"> {{number_format($data['wm'][3][1]['currentFcstPercent'],0)}} % </td>
        <td style="background-color: #f9fbfd; color: #000000; font-weight: bold; text-align: center;"> {{number_format($data['wm'][3][1]['ssPercent'],0)}} % </td>
        <td style="background-color: #f9fbfd; color: #000000; font-weight: bold; text-align: center;"> {{number_format($data['wm'][3][1]['pSapPercent'],0)}} % </td>
        <td style="background-color: #f9fbfd; color: #000000; font-weight: bold; text-align: center;"> {{number_format($data['wm'][3][1]['ppSapPercent'],0)}} % </td>
    </tr>

    <tr>
        <td style="background-color: #e7eff9; color: #000000; font-weight: bold; text-align: center;"> TOTAL </td>
        <td style="background-color: #e7eff9; color: #000000; font-weight: bold; text-align: center;"> {{$data['wm'][3][2]['currentYTD']}} </td>
        <td style="background-color: #e7eff9; color: #000000; font-weight: bold; text-align: center;"> {{$data['wm'][3][2]['currentPlan']}} </td>
        <td style="background-color: #e7eff9; color: #000000; font-weight: bold; text-align: center;"> {{$data['wm'][3][2]['currentFcst']}} </td>
        <td style="background-color: #e7eff9; color: #000000; font-weight: bold; text-align: center;"> {{$data['wm'][3][2]['previousSS']}} </td>
        <td style="background-color: #e7eff9; color: #000000; font-weight: bold; text-align: center;"> {{$data['wm'][3][2]['previousSap']}} </td>
        <td style="background-color: #e7eff9; color: #000000; font-weight: bold; text-align: center;"> {{$data['wm'][3][2]['pPSap']}} </td>
        <td style="background-color: #e7eff9; color: #000000; font-weight: bold; text-align: center;"> {{number_format($data['wm'][3][2]['currentPlanPercent'],0)}} % </td>
        <td style="background-color: #e7eff9; color: #000000; font-weight: bold; text-align: center;"> {{number_format($data['wm'][3][2]['currentFcstPercent'],0)}} % </td>
        <td style="background-color: #e7eff9; color: #000000; font-weight: bold; text-align: center;"> {{number_format($data['wm'][3][2]['ssPercent'],0)}} % </td>
        <td style="background-color: #e7eff9; color: #000000; font-weight: bold; text-align: center;"> {{number_format($data['wm'][3][2]['pSapPercent'],0)}} % </td>
        <td style="background-color: #e7eff9; color: #000000; font-weight: bold; text-align: center;"> {{number_format($data['wm'][3][2]['ppSapPercent'],0)}} % </td>
    </tr>

</table>