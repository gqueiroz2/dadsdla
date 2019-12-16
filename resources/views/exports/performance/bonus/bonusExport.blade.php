<table>
	<tr>
		<th colspan="{{sizeof($data['quarters'])+3}}">
			{{$data['salesRep'][0]['name']}} - Bonus {{$data['year']}}
		</th>
	</tr>

	<tr><td>&nbsp;</td></tr>

	<tr>
		<td style="background-color: #1E90FF; color: #FFFFFF; font-weight: bold;">
			TARGET
		</td>
		<td style="background-color: #a6a6a6;">&nbsp;</td>
		@for($q = 0; $q < sizeof($data['quarters']); $q++)
			<td style="background-color: #a6a6a6; font-weight: bold;">
				{{$data['quarters'][$q]}}
			</td>
		@endfor
		<td style="background-color: #0f243e; color: #FFFFFF; font-weight: bold;">
			Total
		</td>
	</tr>

	@for($t = 0; $t < sizeof($data['tier']); $t++)
		<tr>
			<td>&nbsp;</td>
			<td>
				{{$data['tier'][$t]}} Net Revenue
			</td>
			@for($q = 0; $q < sizeof($data['quarters']); $q++)
				<td>{{$data['planValue'][0][$t][$q]}}</td>
			@endfor
			<td style="background-color: #143052; color: #FFFFFF; font-weight: bold;">
				{{$data['totalPlanValueTier'][0][$t]}}
			</td>
		</tr>
	@endfor

	<tr>
		<td>&nbsp;</td>
		<td style="background-color: #0f243e; color: #FFFFFF; font-weight: bold;">
			TT Net Revenue
		</td>
		@for($q = 0; $q < sizeof($data['quarters']); $q++)
			<td style="background-color: #143052; color: #FFFFFF; font-weight: bold;">
				{{$data['totalPlanSG'][0][$q]}}
			</td>
		@endfor
		<td style="background-color: #0f243e; color: #FFFFFF; font-weight: bold;">
			{{$data['totalPlanTotalSG'][0]}}
		</td>
	</tr>

	<tr><td>&nbsp;</td></tr>

	<tr>
		<td style="background-color: #0070c0; color: #FFFFFF; font-weight: bold;">
			ACTUAL
		</td>
		<td style="background-color: #a6a6a6;">&nbsp;</td>
		@for($q = 0; $q < sizeof($data['quarters']); $q++)
			<td style="background-color: #a6a6a6; font-weight: bold;">
				{{$data['quarters'][$q]}}
			</td>
		@endfor
		<td style="background-color: #0f243e; color: #FFFFFF; font-weight: bold;">
			Total
		</td>
	</tr>

	@for($t = 0; $t < sizeof($data['tier']); $t++)
		<tr>
			<td>&nbsp;</td>
			<td>{{$data['tier'][$t]}} Net Revenue</td>
			@for($q = 0; $q < sizeof($data['quarters']); $q++)
				<td>{{$data['value'][0][$t][$q]}}</td>
			@endfor
			<td style="background-color: #143052; color: #FFFFFF; font-weight: bold;">
				{{$data['totalValueTier'][0][$t]}}
			</td>
		</tr>
	@endfor

	<tr>
		<td>&nbsp;</td>
		<td style="background-color: #0f243e; color: #FFFFFF; font-weight: bold;">TT Net Revenue</td>
		@for($q = 0; $q < sizeof($data['quarters']); $q++)
			<td style="background-color: #143052; color: #FFFFFF; font-weight: bold;">
				{{$data['totalSG'][0][$q]}}
			</td>
		@endfor
		<td style="background-color: #0f243e; color: #FFFFFF; font-weight: bold;">
			{{$data['totalTotalSG'][0]}}
		</td>
	</tr>

</table>