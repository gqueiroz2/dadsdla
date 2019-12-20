<table>
	<tr>
		<th colspan="{{sizeof($data)}}">
			{{$names['head']['name']}} - ({{$dataRanking[1]}}) Ranking (BKGS) : ({{$names['currency'][0]['name']}}/{{strtoupper($names['value'])}})
		</th>
	</tr>
	<tr>
		<th colspan="{{sizeof($data)}}">
			{{$names['head']['months']}}
		</th>
	</tr>
	<tr>
		<th colspan="{{sizeof($data)}}">
			VAR ABS. and VAR % are a coparison with {{$names['head']['years']}}
		</th>
	</tr>
	<tr><td>&nbsp;</td></tr>

	@for($m = 0; $m < sizeof($data[0]); $m++)
		<tr>
			@for($i = 0; $i < sizeof($data); $i++)
				@if($m == 0)
					<td>{{$data[$i][$m]}}</td>
				@elseif(!is_numeric($data[$i][$m]))
					@if($data[$i][$m] == "Others")
						<td> - </td>
					@else
						<td>{{$data[$i][$m]}}</td>
					@endif
				@else
					@if(substr($data[$i][0], 0, 3) == "Pos")
						@if($data[$i][$m] != "-")
							<td>{{$data[$i][$m]}} º</td>
						@else
							<td>{{$data[$i][$m]}}</td>
						@endif
					@elseif($data[$i][0] == "VAR %")
						<td>{{round($data[$i][$m])}}</td>
					@else
						<td>{{round($data[$i][$m])}}</td>
					@endif
				@endif
			@endfor
		</tr>
		@if($type == "PDF" && $m != 0)
			<?php $c++; ?>
			@if($c == 35 && $m != (sizeof($data[0])-1))
				<tr><td>teste</td></tr>
				<?php $c = 0; ?>
			@endif
		@endif
	@endfor

	<tr>
		@for($t = 0; $t < sizeof($dataTotal); $t++)
			@if(is_numeric($dataTotal))
				@if($data[$t][0] == "VAR %")
					<td>{{round($dataTotal[$t])}}</td>
				@else
					<td>{{round($dataTotal[$t])}}</td>
				@endif
			@else
				@if($data[$t][0] == "VAR %")
					<td>{{$dataTotal[$t]}}</td>
				@elseif($dataTotal[$t] != "-")
					<td>{{$dataTotal[$t]}}</td>
				@else
					<td>&nbsp;</td>
				@endif
			@endif
		@endfor
	</tr>

</table>