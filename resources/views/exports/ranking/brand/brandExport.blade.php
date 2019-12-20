<table>
	<tr>
		<th colspan="{{$sizeCols}}">
			<span>
				<b>
					{{$names['region']}} - ({{$dataBrand}}/{{ucfirst($dataType)}}) Ranking (BKGS) : ({{$names['currency'][0]['name']}}/{{strtoupper($names['value'])}})
				</b>
			</span>
		</th>
	</tr>
	
	@for($m = 0; $m < sizeof($data[0]); $m++)
		<tr>
			@for($n = 0; $n < sizeof($data); $n++)
				@if($m == 0)
					<td>{{$data[$n][$m]}}</td>
				@else
					@if(is_numeric($data[$n][$m]))
						@if($n == $pos)
							<td>{{$data[$n][$m]/100}}</td>
						@else
							@if($data[$n][$m] == 0)
								<td> - </td>
							@else
								<td>{{$data[$n][$m]}}</td>
							@endif
						@endif
					@else
						<td>{{$data[$n][$m]}}</td>	
					@endif
				@endif
			@endfor
		</tr>
		@if($type == "PDF")
			<?php $c++; ?>
			@if($c == 41 && $m != (sizeof($data[0])-1))
				<tr><td>teste</td></tr>
				<?php $c = 0; ?>
			@endif
		@endif
	@endfor

	<tr>
		@for($t = 0; $t < sizeof($dataTotal); $t++)
			@if($t == $pos)
				<td>{{$dataTotal[$t]/100}}</td>
			@elseif(is_numeric($dataTotal[$t]))
				<td>{{$dataTotal[$t]}}</td>
			@else
				<td>{{$dataTotal[$t]}}</td>
			@endif
		@endfor
	</tr>

</table>