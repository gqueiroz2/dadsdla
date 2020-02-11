
@for($i=0; $i<sizeof($data['idNumber']); $i++)
	<table>
		<tr>
			<td style="background-color: #0f243e; font-weight: bold; color: #FFFFFF;">Copy Key</td>
			<td style="background-color: #0f243e; font-weight: bold; color: #FFFFFF;">Media Item</td>
		</tr>
		<tr>
			<td style="background-color: #0f243e; font-weight: bold; color: #FFFFFF;" colspan="2">{{$data['client'][$i]}}</td>
		</tr>
	@for($j=0; $j<sizeof($data['idNumber'][$i]);$j++)
		@if($j % 2 == 0)
			{{$color = '#c3d8ef;'}}
		@else

		@endif
		<tr>
			<td style="background-color: {{$color}} font-weight: bold; color: #000000;">{{$data['idNumber'][$i][$j][0]}}</td>
			<td style="background-color: {{$color}} font-weight: bold; color: #000000;">{{$data['idNumber'][$i][$j][1]}}</td>
		</tr>
	@endfor
	</table>
@endfor


