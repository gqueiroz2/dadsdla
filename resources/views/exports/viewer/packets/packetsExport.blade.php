<table id="tabelaDados" class="table-responsive">
    <tr>
        <td colspan="9"></td>
        <td colspan="2" style="background-color: #e7eff9;">SUBTOTAL</td>
        <td style="background-color: #e7eff9;">TOTAL</td>
    </tr>
    <tr>
        <td colspan="9"></td>
        <td style="background-color: #e7eff9;">{{$data['total']['tv']}}</td>
        <td style="background-color: #e7eff9;">{{$data['total']['digital']}}</td>
        <td style="background-color: #e7eff9;">{{$data['total']['total']}}</td>
    </tr>
    <tr>
        <td style="background-color: #0f243e; color: white;">Register</td> 
        <td style="background-color: #0f243e; color: white;">Holding</td>
        <td style="background-color: #0f243e; color: white;">Cluster</td>
        <td style="background-color: #0f243e; color: white;">Property</td>
        <td style="background-color: #0f243e; color: white;">Client</td>
        <td style="background-color: #0f243e; color: white;">Agency</td>
        <td style="background-color: #0f243e; color: white;">Segment</td>
        <td style="background-color: #0f243e; color: white;">AE 1</td>
        <td style="background-color: #0f243e; color: white;">AE 2 </td>
        <td style="background-color: #0f243e; color: white;">TV</td>
        <td style="background-color: #0f243e; color: white;">Digital</td>
        <td style="background-color: #0f243e; color: white;">Total</td>
        <td style="background-color: #0f243e; color: white;">Start Month</td>
        <td style="background-color: #0f243e; color: white;">End Month </td>
        <td style="background-color: #0f243e; color: white;">Payment </td>
        <td style="background-color: #0f243e; color: white;">Installments</td>
        <td style="background-color: #0f243e; color: white;">Quota</td>
        <td style="background-color: #0f243e; color: white;">Status</td>
        <td style="background-color: #0f243e; color: white;">Notes</td>
    </tr>
    @for($t=0; $t<sizeof($data['table']);$t++)
    <tr>
        <td>{{$data['table'][$t]['register']}}</td> 
        <td>{{$data['table'][$t]['holding']}}</td>
        <td>{{$data['table'][$t]['cluster']}}</td>
        <td>{{$data['table'][$t]['project']}}</td>
        <td>{{$data['table'][$t]['client']}}</td>
        <td>{{$data['table'][$t]['agency']}}</td>
        <td> {{$data['table'][$t]['segment']}}</td>
        <td>{{$data['table'][$t]['primary_ae']}}</td>
        <td>{{$data['table'][$t]['second_ae']}}</td>
        <td>{{$data['table'][$t]['tv_value']}}</td>
        <td> {{$data['table'][$t]['digital_value']}}</td>
        <td>{{$data['totalPerPacket'][$t]}}</td>
        <td>{{$data['table'][$t]['start_month']}}</td>
        <td>{{$data['table'][$t]['end_month']}}</td>
        <td>{{$data['table'][$t]['payment']}}</td>
        <td>{{$data['table'][$t]['installments']}}</td>
        <td>{{$data['table'][$t]['quota']}}</td>
        <td>{{$data['table'][$t]['status']}}</td>
        <td>{{$data['table'][$t]['notes']}}</td>
    </tr>
    @endfor
</table>