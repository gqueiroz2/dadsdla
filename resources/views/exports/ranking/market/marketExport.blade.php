<table>
	@if(is_string($data))
		<tr>
			<td style="color: red">{{$data}}</td>
		</tr>
	@else
		<tr>
			<th colspan="{{sizeof($data)}}">
				<span>
					<b>
						{{$names['region']}} - Market ({{$names['val']}} - {{$dataMarket[1]}}) Ranking (BKGS) : ({{$names['currency'][0]['name']}}/{{strtoupper($names['value'])}})
					</b>
				</span>
			</th>
		</tr>

		@for($m = 0; $m < sizeof($data[0]); $m++)
			<tr>
				@for($n = 0; $n < sizeof($data); $n++)
					@if(is_numeric($data[$n][$m]))
						@if($data[$n][0] == "Var (%)" || $data[$n][0] == "Share Bookings ".$names['years'][0] || $data[$n][0] == "Share Bookings ".$names['years'][1] || $data[$n][0] == "% YoY")
							<td>{{round($data[$n][$m])}}</td>
						@elseif($data[$n][0] == "Ranking")
							<td>{{$data[$n][$m]}}º</td>
						@else
							@if($data[$n][$m] == 0)
								<td> - </td>
							@else
								<td>{{round($data[$n][$m])}}</td>
							@endif
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

		@if(!is_null($dataTotal))
			<tr>
				@for($t=0; $t < sizeof($dataTotal); $t++)
					@if($t == $pos[0] || $t == $pos[1] || $t == $pos[2])
						<td>{{round($dataTotal[$t])}}</td>
					@else
						@if(is_numeric($dataTotal[$t]))
							<td>{{round($dataTotal[$t])}}</td>
						@else
							<td>{{$dataTotal[$t]}}</td>
						@endif
					@endif
				@endfor
			</tr>
		@endif

	@endif
</table>