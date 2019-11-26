<table>
	<tr>
		<th colspan="10" style="background-color: #0070c0; font-weight: bold; color: #FFFFFF;">
			{{$data['region']}} - Year Over Year : BKGS - {{$data['year']}} ({{strtoupper($data['currency'][0]['name'])}}/{{strtoupper($data['value'])}})
		</th>
	</tr>

	<tr><td>&nbsp;</td></tr>

	<tr>
		<td style="background-color: #0070c0;">&nbsp;</td>

		@for($i = 1; $i <= 2; $i++)
			<td style="background-color: #0070c0; font-weight: bold; color: #FFFFFF;" colspan="3">S{{$i}}</td>
		@endfor

		<td style="background-color: #0f243e; font-weight: bold; color: #FFFFFF;" colspan="3">TOTAL</td>
	</tr>

	<tr>
		<td style="background-color: #0070c0;">&nbsp;</td>

		@for($i = 0; $i < 3; $i++)
			@if($i == 2)
				<td style="background-color: #0f243e; font-weight: bold; color: #FFFFFF;">
					BKGS {{$data['year']-1}}
				</td>
				<td style="background-color: #0f243e; font-weight: bold; color: #FFFFFF;">
					{{$data['form']}} {{$data['year']}}
				</td>
				<td style="background-color: #0f243e; font-weight: bold; color: #FFFFFF;">
					BKGS {{$data['year']}}
				</td>
			@else
				<td style="background-color: #0070c0; font-weight: bold; color: #FFFFFF;">
					BKGS {{$data['year']-1}}
				</td>
				<td style="background-color: #0070c0; font-weight: bold; color: #FFFFFF;">
					{{$data['form']}} {{$data['year']}}
				</td>
				<td style="background-color: #0070c0; font-weight: bold; color: #FFFFFF;">
					BKGS {{$data['year']}}
				</td>
			@endif
		@endfor
	</tr>

	@for($b = 0; $b < sizeof($data['brands']); $b++)
		<tr>
			@if($b != (sizeof($data['brands'])-1))
				<td style="background-color: #0070c0; font-weight: bold; color: #FFFFFF;">
					{{$data['brands'][$b][1]}}
				</td>

				@for($i = 0; $i < 3; $i++)
					@if($i == 0)
						<td style="background-color: #dce6f1;">
							{{($data['quarter'][0][$i][$b+1]+$data['quarter'][1][$i][$b+1])}}
						</td>
					@elseif($i == 1)
						<td>
							{{($data['quarter'][0][$i][$b+1]+$data['quarter'][1][$i][$b+1])}}
						</td>
					@else
						<td style="background-color: #c3d8ef;">
							{{($data['quarter'][0][$i][$b+1]+$data['quarter'][1][$i][$b+1])}}
						</td>
					@endif
				@endfor

				@for($i = 0; $i < 3; $i++)
					@if($i == 0)
						<td style="background-color: #dce6f1;">
							{{($data['quarter'][2][$i][$b+1]+$data['quarter'][3][$i][$b+1])}}
						</td>
					@elseif($i == 1)
						<td>
							{{($data['quarter'][2][$i][$b+1]+$data['quarter'][3][$i][$b+1])}}
						</td>
					@else
						<td style="background-color: #c3d8ef;">
							{{($data['quarter'][2][$i][$b+1]+$data['quarter'][3][$i][$b+1])}}
						</td>
					@endif
				@endfor

				@for($i = 0; $i < 3; $i++)
					@if($i == 0)
						<td style="background-color: #dce6f1;">
							{{
								(
									($data['quarter'][0][$i][$b+1]+$data['quarter'][1][$i][$b+1])+
									($data['quarter'][2][$i][$b+1]+$data['quarter'][3][$i][$b+1])
								)
							}}
						</td>
					@elseif($i == 1)
						<td>
							{{
								(
									($data['quarter'][0][$i][$b+1]+$data['quarter'][1][$i][$b+1])+
									($data['quarter'][2][$i][$b+1]+$data['quarter'][3][$i][$b+1])
								)
							}}
						</td>
					@else
						<td style="background-color: #c3d8ef;">
							{{
								(
									($data['quarter'][0][$i][$b+1]+$data['quarter'][1][$i][$b+1])+
									($data['quarter'][2][$i][$b+1]+$data['quarter'][3][$i][$b+1])
								)
							}}
						</td>
					@endif
				@endfor

			@else
				<td style="background-color: #0f243e; font-weight: bold; color: #FFFFFF;">
					{{$data['brands'][$b][1]}}
				</td>

				@for($i = 0; $i < 3; $i++)
					<td style="background-color: #0f243e; font-weight: bold; color: #FFFFFF;">
						{{($data['quarter'][0][$i][$b+2]+$data['quarter'][1][$i][$b+2])}}
					</td>
				@endfor

				@for($i = 0; $i < 3; $i++)
					<td style="background-color: #0f243e; font-weight: bold; color: #FFFFFF;">
						{{($data['quarter'][2][$i][$b+2]+$data['quarter'][3][$i][$b+2])}}
					</td>
				@endfor

				@for($i = 0; $i < 3; $i++)
					<td style="background-color: #0f243e; font-weight: bold; color: #FFFFFF;">
						{{
							(
								($data['quarter'][0][$i][$b+2]+$data['quarter'][1][$i][$b+2])+
								($data['quarter'][2][$i][$b+2]+$data['quarter'][3][$i][$b+2])
							)
						}}
					</td>
				@endfor
			@endif
		</tr>
	@endfor
</table>