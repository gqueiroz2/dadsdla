
@for($i=0; $i<sizeof($data['idNumber']); $i++)
	<table>
		<tr>
			<td>{{$data['names'][0]}}</td>
			<td>{{$data['names'][1]}}</td>
		</tr>
		<tr>
			<td colspan="2">{{$data['client'][$i]}}</td>
		</tr>
	@for($j=0; $j<sizeof($data['idNumber'][$i]);$j++)
		<tr>
			<td>{{$data['idNumber'][$i][$j][0]}}</td>
			<td>{{$data['idNumber'][$i][$j][1]}}</td>
		</tr>
	@endfor
	</table>
@endfor


