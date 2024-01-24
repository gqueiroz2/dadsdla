@if($data['payTv'][0]['station'] != false)
<table>
	<tr>
		<td style='background-color: #0047b3; text-align: center; font-weight: bold;' > PAY TV {{$data['year']-1}}</td>
   	<td style="font-weight: bold; background-color: #c3d8ef; text-align: center;">SOA</td>
	</tr>
	@for($p = 0; $p<sizeof($data['payTv']); $p++)
	<tr>
		@if($data['payTv'][$p]['station'] == 'WBD')
   		<td style="font-weight: bold; background-color: #c3d8ef; text-align: center;">{{$data['payTv'][$p]['station']}}</td>
   		<td style="font-weight: bold; background-color: #c3d8ef; text-align: center;">{{number_format(($data['payTv'][$p]['percentage']*100),0,',','.')}}%</td>
		@else
			<td style='background-color: #f9fbfd; text-align:center; font-weight: bold;'>{{$data['payTv'][$p]['station']}}</td>
			<td style='background-color: #f9fbfd; text-align:center; font-weight: bold;'>{{number_format(($data['payTv'][$p]['percentage']*100),0,',','.')}}%</td>
		@endif
	</tr>	
	@endfor
</table>
@else
<td>No information</td>
@endif