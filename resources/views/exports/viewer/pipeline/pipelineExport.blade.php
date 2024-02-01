<table>
    <tr>
        <td colspan="9"></td>
        <td colspan="2" style="background-color: #e7eff9;">SUBTOTAL</td>
        <td style="background-color: #e7eff9;">TOTAL</td>
    </tr>
    <tr>
        <td colspan="9"></td>
        <td style="background-color: #e7eff9; text-align: center;">{{$data['total']['tv']}}</td>
        <td style="background-color: #e7eff9; text-align: center;">{{$data['total']['digital']}}</td>
        <td style="background-color: #e7eff9; text-align: center;">{{$data['total']['total']}}</td>
    </tr>
    <tr>
        <td style="background-color: #0f243e; color: white; text-align: center;">Register</td> 
        <td style="background-color: #0f243e; color: white; text-align: center;">Cluster</td>
        <td style="background-color: #0f243e; color: white; text-align: center;">Property</td>
        <td style="background-color: #0f243e; color: white; text-align: center;">Client</td>
        <td style="background-color: #0f243e; color: white; text-align: center;">Agency</td>
        <td style="background-color: #0f243e; color: white; text-align: center;">Products</td>
        <td style="background-color: #0f243e; color: white; text-align: center;">AE 1</td>
        <td style="background-color: #0f243e; color: white; text-align: center;">AE 2 </td>
        <td style="background-color: #0f243e; color: white; text-align: center;">Manager</td>
        <td style="background-color: #0f243e; color: white; text-align: center;">TV</td>
        <td style="background-color: #0f243e; color: white; text-align: center;">Digital</td>
        <td style="background-color: #0f243e; color: white; text-align: center;">Total</td>
        <td style="background-color: #0f243e; color: white; text-align: center;">Start Month</td>
        <td style="background-color: #0f243e; color: white; text-align: center;">End Month </td>
        <td style="background-color: #0f243e; color: white; text-align: center;">Quota</td>
        <td style="background-color: #0f243e; color: white; text-align: center;">Status</td>
        <td style="background-color: #0f243e; color: white; text-align: center;">Notes</td>
    </tr>
     @for($t=0; $t<sizeof($data['table']);$t++)
        <tr>
            <td style="text-align: center;">{{$data['table'][$t]['register']}}</td>
            <td style="text-align: center;">{{$data['table'][$t]['cluster']}}</td>
            <td style="text-align: center;">{{$data['table'][$t]['project']}}</td>
            <td style="text-align: center;">{{$data['table'][$t]['client']}}</td>
            <td style="text-align: center;">{{$data['table'][$t]['agency']}}</td>
            <td style="text-align: center;">{{$data['table'][$t]['product']}}</td>
            <td style="text-align: center;">{{$data['table'][$t]['primary_ae']}}</td>
            <td style="text-align: center;">{{$data['table'][$t]['second_ae']}}</td>
            <td style="text-align: center;">{{$data['table'][$t]['manager']}}</td>
            <td style="text-align: center;">{{$data['table'][$t]['tv_value']}}</td>
            <td style="text-align: center;"> {{$data['table'][$t]['digital_value']}}</td>
            <td style="text-align: center;">{{$data['totalPerPacket'][$t]}}</td>
            <td style="text-align: center;">{{$data['table'][$t]['start_month']}}</td>
            <td style="text-align: center;">{{$data['table'][$t]['end_month']}}</td>
            <td style="text-align: center;">{{$data['table'][$t]['quota']}}</td>
            <td style="text-align: center;">{{$data['table'][$t]['status']}}</td>
            <td style="text-align: center;">{{$data['table'][$t]['notes']}}</td>
        </tr>
    @endfor
</table>