<table>
	<tr>
		<th colspan="{{sizeof($data['mtx'])}}">
			<span>
				<b>
					{{$data['region']}} - New Ranking (BKGS) : ({{$data['currency'][0]['name']}}/{{strtoupper($data['value'])}})
				</b>
			</span>
		</th>
	</tr>
	@if($data['type'] != "sector")
		<tr>
			<th colspan="{{sizeof($data['mtx'])}}">
				<span>
					<b>
						Refer to the brands: {{$data['headNames']['brands']}}
					</b>
				</span>
			</th>	
		</tr>
	@endif		
	<tr>
		<th colspan="{{sizeof($data['mtx'])}}">
			<span>
				<b>
					Refer to the period: {{$data['headNames']['months']}}
				</b>
			</span>
		</th>
	</tr>

	@for($m = 0; $m < sizeof($data['mtx'][0]); $m++)
		<tr>
			@for($n=0; $n < sizeof($data['mtx']); $n++)
				@if(is_numeric($data['mtx'][$n][$m]))
					@if($data['mtx'][$n][0] == "Var (%)" || $data['mtx'][$n][0] == "Var YTD (%)")
						<td>{{$data['mtx'][$n][$m]/100}}</td>
					@elseif($data['mtx'][$n][0] == "Ranking")
						<td>{{$data['mtx'][$n][$m]}}º</td>
					@else
						<td>{{$data['mtx'][$n][$m]}}</td>
					@endif
				@else
					<td>{{$data['mtx'][$n][$m]}}</td>
				@endif
			@endfor
		</tr>
		@if($type == "PDF" && $m != 0)
			<?php $c++; ?>
			@if($c == 40 && $m != (sizeof($data['mtx'][0])-1))
				<tr><td>teste</td></tr>
				<?php $c = 0; ?>
			@endif
		@endif
	@endfor

	<tr>
		@for($t = 0; $t < sizeof($data['total']); $t++)
			@if(is_numeric($data['total'][$t]))
				@if($data['type'] == "agency")
					@if($t == 5)
						<td>{{$data['total'][$t]/100}}</td>
					@else
						<td>{{$data['total'][$t]}}</td>
					@endif
				@else
					@if($t == 4)
						<td>{{$data['total'][$t]/100}}</td>
					@else
						<td>{{$data['total'][$t]}}</td>
					@endif
				@endif
			@else
				<td>{{$data['total'][$t]}}</td>
			@endif
		@endfor
	</tr>
</table>