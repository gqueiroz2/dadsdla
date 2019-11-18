<table>
	<tr>
		<th colspan="15" style="background-color:#0070c0; ">
			{{$data['region']}} - Year Over Year : BKGS - {{$data['year']}} ({{strtoupper($data['currency'][0]['name'])}}/{{strtoupper($data['value'])}})			
		</th>
	</tr>
	
	@for($d=0;$d<sizeof($data['mtx']);$d++)
		@if($data['mtx'][$d][0][0] == "DN")
			<tr>
				<td style="background-color:#0f243e; " >
					{{$data["mtx"][$d][0][0]}}
				</td>
			</tr>
		@else
			<tr>
				<td >
					{{$data["mtx"][$d][0][0]}}
				</td>
			</tr>
		@endif

		@for($l=0;$l<sizeof($data['mtx'][$d]);$l++)
			<tr>
				@for($v=0;$v<sizeof($data['mtx'][$d][$l]);$v++)
					@if(is_numeric($data['mtx'][$d][$l][$v]))
						@if($v == 13)
							@if($l == 5)
								<td style="background-color:#0f243e;">{{$data["mtx"][$d][$l][$v]}}</td>
							@else
								<td style="background-color:#143052;">{{$data["mtx"][$d][$l][$v]}}</td>
							@endif
						@elseif($l == 1 || $l == 2)
							<td>{{$data["mtx"][$d][$l][$v]}}</td>
						@elseif($l == 3)
							<td style="background-color:#dce6f1;">{{$data["mtx"][$d][$l][$v]}}</td>
						@else
							<td style="background-color:#c3d8ef;">{{$data["mtx"][$d][$l][$v]}}</td>
						@endif
					@else
						@if($l == 0)
							@if($v == 0)
								@if($d == (sizeof($data["mtx"])-1))
									<td style="background-color: #0f243e;">&nbsp;</td>
								@else
									<td style="background-color: #a6a6a6;">&nbsp;</td>
								@endif
							@elseif($v != 13)
								<td style="background-color: #a6a6a6;">{{$data["mtx"][$d][$l][$v]}}</td>
							@else
								<td style="background-color:  #0f243e;">{{$data["mtx"][$d][$l][$v]}}</td>
							@endif
						@elseif($l == 1)
							@if($d == (sizeof($data["mtx"])-1))
								<td style="background-color:#143052;">{{$data["mtx"][$d][$l][$v]}}</td>
							@else
								<td style="background-color:#bde3ff; ">{{$data["mtx"][$d][$l][$v]}}</td>
							@endif
						@elseif($l == 2 || $l == 3)
							@if($d == (sizeof($data["mtx"])-1))
								<td style="background-color: #143052;">{{$data["mtx"][$d][$l][$v]}}</td>
							@else
								<td style="background-color:#dce6f1;"> {{$data["mtx"][$d][$l][$v]}}</td>
							@endif
						@else
							@if($d == (sizeof($data["mtx"])-1) && ($l == 5))
								<td style="background-color: #0f243e;"> {{$data["mtx"][$d][$l][$v]}}</td>
							@elseif($d == (sizeof($data["mtx"])-1))
								<td style="background-color: #143052;">{{$data["mtx"][$d][$l][$v]}}</td>
							@else
								<td style="background-color: #c3d8ef;">{{$data["mtx"][$d][$l][$v]}}</td>
							@endif
						@endif
					@endif
				@endfor
			</tr>
		@endfor
		<tr><td>&nbsp;</td></tr>
	@endfor
</table>