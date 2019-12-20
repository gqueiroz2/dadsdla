<table>
	<tr>
		<th colspan="{{sizeof($data['mtx'])}}">
			{{$data['names']['name']}} Ranking (BKGS) : ({{$data['currency'][0]['name']}}/{{strtoupper($data['value'])}})
		</th>
	</tr>
	<tr>
		<th colspan="{{sizeof($data['mtx'])}}">
			{{$data['names']['months']}}
		</th>
	</tr>
	<tr>
		<th colspan="{{sizeof($data['mtx'])}}">
			VAR ABS. and VAR % are a coparison with {{$data['names']['years']}}
		</th>
	</tr>
	<tr><td>&nbsp;</td></tr>

	@for($m = 0; $m < $data['nPos']; $m++)
		<tr>
			@for($i = 0; $i < sizeof($data['mtx']); $i++)
				@if($m == 0)
					<td>{{$data['mtx'][$i][$m]}}</td>
				@elseif(!is_numeric($data['mtx'][$i][$m]))
					@if($data['mtx'][$i][$m] == "Others")
						<td> - </td>
					@else
						<td>{{$data['mtx'][$i][$m]}}</td>
					@endif
				@else
					@if(substr($data['mtx'][$i][0], 0, 3) == "Pos")
						@if($data['mtx'][$i][$m] != "-")
							<td>{{$data['mtx'][$i][$m]}} º</td>
						@else
							<td>{{$data['mtx'][$i][$m]}}</td>
						@endif
					@elseif($data['mtx'][$i][0] == "VAR %")
						<td>{{round($data['mtx'][$i][$m])}}</td>
					@else
						<td>{{round($data['mtx'][$i][$m])}}</td>
					@endif
				@endif
			@endfor
		</tr>
		@if($type == "PDF" && $m != 0)
			<?php $c++; ?>
			@if($c == 40 && $m != ($data['nPos']-1))
				<tr><td>teste</td></tr>
				<?php $c = 0; ?>
			@endif
		@endif
	@endfor

	<tr>
		@for($t = 0; $t < sizeof($data['total']); $t++)
			@if(is_numeric($data['total']))
				@if($data['mtx'][$t][0] == "VAR %")
					<td>{{round($data['total'][$t])}}</td>
				@else
					<td>{{round($data['total'][$t])}}</td>
				@endif
			@else
				@if($data['total'][$t] != "-")
					<td>{{$data['total'][$t]}}</td>
				@else
					<td>&nbsp;</td>
				@endif
			@endif
		@endfor
	</tr>

</table>