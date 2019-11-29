<table>
	<tr>
		<th colspan="{{sizeof($data)}}">
			<span>
				<b>
					{{$names['region']}} - Churn ({{$names['val']}} - {{$dataChurn}}) Ranking (BKGS) : ({{$names['currency'][0]['name']}}/{{strtoupper($names['value'])}})
				</b>
			</span>
		</th>
	</tr>

	@for($m = 0; $m < sizeof($data[0]); $m++)
		<tr>
			@for($n = 0; $n < sizeof($data); $n++)
				@if(is_numeric($data[$n][$m]))
					@if($data[$n][0] == "Var (%)" || $data[$n][0] == "Var YTD (%)")
						<td>{{$data[$n][$m]/100}}</td>
					@elseif($data[$n][0] == "Ranking")
						<td>{{$data[$n][$m]}}º</td>
					@else
						<td>{{$data[$n][$m]}}</td>
					@endif
				@else
					<td>{{$data[$n][$m]}}</td>
				@endif
			@endfor
		</tr>
	@endfor

	<tr>
		@for($t = 0; $t < sizeof($dataTotal); $t++)
			@if(is_numeric($dataTotal[$t]))
				@if($names['type'] == "agency")
					@if($t == 6 || $t == 11)
						<td>{{$dataTotal[$t]/100}}</td>
					@else
						<td>{{$dataTotal[$t]}}</td>
					@endif
				@elseif($names['type'] == "client")
					@if($t == 6)
						<td>{{$dataTotal[$t]/100}}</td>
					@else
						<td>{{$dataTotal[$t]}}</td>
					@endif
				@else
					@if($t == 5)
						<td>{{$dataTotal[$t]/100}}</td>
					@else
						<td>{{$dataTotal[$t]}}</td>
					@endif
				@endif
			@else
				<td>{{$dataTotal[$t]}}</td>
			@endif
		@endfor
	</tr>
</table>