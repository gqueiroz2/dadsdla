<table>
	<tr>
		<th colspan="{{sizeof($data)}}">
			<span>
				<b>
					{{$names['region']}} - Churn ({{$names['val']}} - {{$dataChurn[1]}}) Ranking (BKGS) : ({{$names['currency'][0]['name']}}/{{strtoupper($names['value'])}})
				</b>
			</span>
		</th>
	</tr>

	@for($m = 0; $m < sizeof($data[0]); $m++)
		<tr>
			@for($n = 0; $n < sizeof($data); $n++)
				@if(is_numeric($data[$n][$m]))
					@if($data[$n][0] == "Var (%)" || $data[$n][0] == "Var YTD (%)")
						<td>{{round($data[$n][$m])}}</td>
					@elseif($data[$n][0] == "Ranking")
						<td>{{$data[$n][$m]}}º</td>
					@else
						<td>{{round($data[$n][$m])}}</td>
					@endif
				@else
					<td>{{$data[$n][$m]}}</td>
				@endif
			@endfor
		</tr>
		@if($type == "PDF" && $m != 0)
			<?php $c++; ?>
			@if($c == 40 && $m != (sizeof($data[0])-1))
				<tr><td>teste</td></tr>
				<?php $c = 0; ?>
			@endif
		@endif
	@endfor

	<tr>
		@for($t = 0; $t < sizeof($dataTotal); $t++)
			@if(is_numeric($dataTotal[$t]))
				@if($names['val'] == "agency")
					@if($t == 6 || $t == 11)
						<td>{{round($dataTotal[$t])}}</td>
					@else
						<td>{{$dataTotal[$t]}}</td>
					@endif
				@elseif($names['val'] == "client")
					@if($t == 5)
						<td>{{round($dataTotal[$t])}}</td>
					@else
						<td>{{round($dataTotal[$t])}}</td>
					@endif
				@else
					@if($t == 5)
						<td>{{round($dataTotal[$t])}}</td>
					@else
						<td>{{round($dataTotal[$t])}}</td>
					@endif
				@endif
			@else
				<td>{{$dataTotal[$t]}}</td>
			@endif
		@endfor
	</tr>
</table>