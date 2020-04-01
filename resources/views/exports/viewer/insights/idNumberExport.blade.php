@for($i=0; $i<sizeof($data['idNumber']); $i++)
	<table>
		<tr>
			<td style="background-color: #0f243e; font-weight: bold; color: #FFFFFF;">Copy Title</td>
			<td style="background-color: #0f243e; font-weight: bold; color: #FFFFFF;">House Number</td>
		</tr>
		<tr>
			<td style="background-color: #0f243e; font-weight: bold; color: #FFFFFF;" colspan="2">{{$data['client'][$i]}}</td>
		</tr>
	@for($j=0; $j<sizeof($data['idNumber'][$i]);$j++)
		<tr>
		@if($j % 2 == 0)
			<td style="background-color: #c3d8ef; font-weight: bold; color: #000000;">{{$data['idNumber'][$i][$j][0]}}</td>
			<td style="background-color: #c3d8ef; font-weight: bold; color: #000000;">{{$data['idNumber'][$i][$j][1]}}</td>
		@else
			<td style="background-color: #f9fbfd; font-weight: bold; color: #000000;">{{$data['idNumber'][$i][$j][0]}}</td>
			<td style="background-color: #f9fbfd; font-weight: bold; color: #000000;">{{$data['idNumber'][$i][$j][1]}}</td>
		@endif
			
		</tr>
	@endfor
	</table>
@endfor


