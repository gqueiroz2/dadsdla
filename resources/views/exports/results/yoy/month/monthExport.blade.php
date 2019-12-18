<table>
	<tr>
		<th colspan="13" style="background-color: #0070c0; font-weight: bold; color: #FFFFFF;">
			{{$data['region']}} - Year Over Year : BKGS - {{$data['year']}} ({{strtoupper($data['currency'][0]['name'])}}/{{strtoupper($data['value'])}})
		</th>
	</tr>

	<tr><td>&nbsp;</td></tr>

	@for($i = 0, $j = 0; $i < sizeof($data['months']); $i+=3, $j++)
		<tr>
			<td style="background-color: #0070c0;">&nbsp;</td>
			@for($m = $i, $k = 0; $m < ($i+3); $m++, $k++)
				@if($k == 1)
					<td colspan="3" style="background-color: #004b84; font-weight: bold; color: #FFFFFF;">
						{{$data['months'][$m][0]}}
					</td>
				@else
					<td colspan="3" style="background-color: #0070c0; font-weight: bold; color: #FFFFFF;">
						{{$data['months'][$m][0]}}
					</td>	
				@endif
			@endfor
			<td colspan="3" style="background-color: #0f243e; font-weight: bold; color: #FFFFFF;">
				Q{{($j+1)}}
			</td>
		</tr>
		<tr>
			<td style="background-color: #0070c0;">&nbsp;</td>
			@for($m = 0, $k = 0; $m <= 3; $m++, $k++)
				@if($k == 1)
					<td style="background-color: #004b84; font-weight: bold; color: #FFFFFF;">
						BKGS {{$data['year']-1}}
					</td>
					<td style="background-color: #004b84; font-weight: bold; color: #FFFFFF;">
						{{$data['form']}} {{$data['year']}}
					</td>
					<td style="background-color: #004b84; font-weight: bold; color: #FFFFFF;">
						BKGS {{$data['year']}}
					</td>
				@elseif($k == 3)
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
					@for($m = $i; $m < ($i+3); $m++)
						@for($k = 0; $k < 3; $k++)
							@if($k == 0)
								<td style="background-color: #dce6f1;">
									{{$data['mtx'][$b][$k][$m+1]}}
								</td>
							@elseif($k == 1)
								<td>
									{{$data['mtx'][$b][$k][$m+1]}}
								</td>
							@else
								<td style="background-color: #c3d8ef;">
									{{$data['mtx'][$b][$k][$m+1]}}
								</td>
							@endif
						@endfor
					@endfor

					@for($m = 0; $m < 3; $m++)
						@if($m == 0)
							<td style="background-color: #dce6f1;">
								{{$data['quarter'][$j][$m][$b+1]}}
							</td>
						@elseif($m == 1)
							<td>
								{{$data['quarter'][$j][$m][$b+1]}}
							</td>
						@else
							<td style="background-color: #c3d8ef;">
								{{$data['quarter'][$j][$m][$b+1]}}
							</td>
						@endif
					@endfor
				@else
					<td style="background-color: #0f243e; font-weight: bold; color: #FFFFFF;">
						{{$data['brands'][$b][1]}}
					</td>
					@for($m = $i; $m < ($i+3); $m++)
						@for($k = 0; $k < 3; $k++)
							<td style="background-color: #143052; font-weight: bold; color: #FFFFFF;">
								{{$data['mtx'][($b+1)][$k][$m+1]}}
							</td>
						@endfor
					@endfor

					@for($m = 0; $m < 3; $m++)
						<td style="background-color: #0f243e; font-weight: bold; color: #FFFFFF;">
							{{$data['quarter'][$j][$m][$b+2]}}
						</td>
					@endfor
				@endif
			</tr>
		@endfor

		<tr><td>&nbsp;</td></tr>

	@endfor
</table>