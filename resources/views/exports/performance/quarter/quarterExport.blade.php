<table>
	<tr>
		<th colspan="7">
			{{$data['region']}} - Office {{$data['year']}} ({{$data['currency'][0]['name']}}/{{strtoupper($data['value'])}})
		</th>
	</tr>

	<tr>
		<th colspan="7">Sales Group: {{$data['sales']['salesRepGroup']}} / Sales Representative: {{$data['sales']['salesRep']}}</th>
	</tr>

	<tr><td>&nbsp;</td></tr>

	@for ($t=0; $t < sizeof($data['mtx']); $t++)
		<tr>
			<td colspan="7">{{$data['tiers'][$t]}}</td>
		</tr>
		<tr><td>&nbsp;</td></tr>
		@for($b=0; $b < sizeof($data['mtx'][$t]); $b++)
			<tr>
				<?php $c = 1; ?>
				@if($data['mtx'][$t][$b][0][0] == "TOTH")
					OTH
				@else
					<td>{{$data['mtx'][$t][$b][0][0]}}</td>
				@endif
				@for($v=0; $v < sizeof($data['mtx'][$t][$b][$c]); $v++)	
					@if($v == 3 || $v == 6)
					@elseif($v == 7)
						<td style="background-color: #0f243e; color: #FFFFFF; font-weight: bold;">{{$data['mtx'][$t][$b][$c][$v]}}</td>
					@else
						<td style="background-color: #a6a6a6; font-weight: bold;">{{$data['mtx'][$t][$b][$c][$v]}}</td>
					@endif
				@endfor
			</tr>
			<tr>
				<?php $c = 2; ?>
				<td>&nbsp;</td>
				@for($v=0; $v < sizeof($data['mtx'][$t][$b][$c]); $v++)
					@if($v == 3 || $v == 6)
					@elseif($v == 0)
						<td style="background-color:#bde3ff;">{{$data['mtx'][$t][$b][$c][$v]}}</td>
					@elseif($v == 7)
						<td style="background-color: #143052; color: #FFFFFF; font-weight: bold;">{{$data['mtx'][$t][$b][$c][$v]}}</td>
					@else
						<td>{{$data['mtx'][$t][$b][$c][$v]}}</td>
					@endif
				@endfor
			</tr>
			<tr>
				<?php $c = 3; ?>
				<td>&nbsp;</td>
				@for($v=0; $v < sizeof($data['mtx'][$t][$b][$c]); $v++)
					@if($v == 3 || $v == 6)
					@elseif($v == 0)
						<td style="background-color:#dce6f1;">{{$data['mtx'][$t][$b][$c][$v]}}</td>
					@elseif($v == 7)
						<td style="background-color: #143052; color: #FFFFFF; font-weight: bold;">{{$data['mtx'][$t][$b][$c][$v]}}</td>
					@else
						<td>{{$data['mtx'][$t][$b][$c][$v]}}</td>
					@endif
				@endfor
			</tr>
			<tr>
				<?php $c = 4; ?>
				<td>&nbsp;</td>
				@for($v=0; $v < sizeof($data['mtx'][$t][$b][$c]); $v++)
					@if($v == 3 || $v == 6)
					@elseif($v == 7)
						<td style="background-color: #143052; color: #FFFFFF; font-weight: bold;">{{$data['mtx'][$t][$b][$c][$v]}}</td>
					@else
						<td style="background-color:#dce6f1;">{{$data['mtx'][$t][$b][$c][$v]}}</td>
					@endif
				@endfor
			</tr>
			<tr>
				<?php $c = 5; ?>
				<td>&nbsp;</td>
				@for($v=0; $v < sizeof($data['mtx'][$t][$b][$c]); $v++)
					@if($v == 3 || $v == 6)
					@elseif($v == 7)
						<td style="background-color: #0f243e; color: #FFFFFF; font-weight: bold;">{{$data['mtx'][$t][$b][$c][$v]/100}}</td>
					@else
						@if($v == 0)
							<td style="background-color:#c3d8ef;">{{$data['mtx'][$t][$b][$c][$v]}}</td>
						@else
							<td style="background-color:#c3d8ef;">{{$data['mtx'][$t][$b][$c][$v]/100}}</td>
						@endif
					@endif
				@endfor
			</tr>
			<tr><td>&nbsp;</td></tr>
		@endfor
	@endfor
</table>