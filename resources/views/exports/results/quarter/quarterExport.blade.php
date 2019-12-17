<table>
	<tr>
		<th colspan="8" style="background-color: #0070c0;">
			<span>
				{{$data['region']}} - Quarter :(BKGS) {{$data['year']}} ({{$data['currency'][0]['name']}}/{{strtoupper($data['value'])}})
			</span>
		</th>
	</tr>

	<tr><td>&nbsp;</td></tr>

	@for($b = 0; $b < sizeof($data['mtx']); $b++)
		@for($l = 0; $l < sizeof($data['mtx'][$b]); $l++)
			<tr>
				@for($v=0; $v < sizeof($data['mtx'][$b][$l]); $v++)
					@if(is_numeric($data['mtx'][$b][$l][$v]))
						@if($v == 3 || $v == 6)
							@if($l == 3)
								<td style="background-color: #c3d8ef">
									{{$data['mtx'][$b][$l][$v]/100}}
								</td>
							@elseif($l == 4)
								<td style="background-color: #4f81bd; color: #FFFFFF; font-weight: bold;">
									{{$data['mtx'][$b][$l][$v]}}
								</td>
							@else
								<td style="background-color: #c3d8ef">
									{{$data['mtx'][$b][$l][$v]}}
								</td>
							@endif
						@elseif($v == 7)
							@if($l == 3)
								<td style="background-color: #143052; color: #FFFFFF; font-weight: bold;">
									{{$data['mtx'][$b][$l][$v]/100}}
								</td>
							@elseif($l == 4)
								<td style="background-color: #0f243e; color: #FFFFFF; font-weight: bold;">
									{{$data['mtx'][$b][$l][$v]}}
								</td>
							@else
								<td style="background-color: #143052; color: #FFFFFF; font-weight: bold;">
									{{$data['mtx'][$b][$l][$v]}}
								</td>
							@endif
						@elseif($l == 1 || $l == 2)
							<td>
								{{$data['mtx'][$b][$l][$v]}}
							</td>
						@elseif($l == 3)
							<td style="background-color: #dce6f1">
								{{$data['mtx'][$b][$l][$v]/100}}
							</td>
						@else
							<td style="background-color: #c3d8ef">
								{{$data['mtx'][$b][$l][$v]}}
							</td>
						@endif
					@else
						@if($l == 0)
							@if($v == 0)
								@if($data['mtx'][$b][$l][$v] == "DN")
									<td style="background-color: #0f243e; color: #FFFFFF; font-weight: bold;">
										{{$data['mtx'][$b][$l][$v]}}
									</td>
								@else
									<td style="background-color: #0070c0; color: #FFFFFF; font-weight: bold;">
										{{$data['mtx'][$b][$l][$v]}}
									</td>
								@endif
							@elseif(($v >= 1 && $v <= 2) || ($v >= 4 && $v <= 5))
								<td style="background-color: #a6a6a6; font-weight: bold;">
									{{$data['mtx'][$b][$l][$v]}}
								</td>
							@elseif($v == 3 || $v == 6)
								<td style="background-color: #4f81bd; color: #FFFFFF; font-weight: bold;">
									{{$data['mtx'][$b][$l][$v]}}
								</td>
							@else
								<td style="background-color: #0f243e; color: #FFFFFF; font-weight: bold;">
									{{$data['mtx'][$b][$l][$v]}}
								</td>
							@endif
						@elseif($l == 1)
							@if($b == sizeof($data['mtx'])-1)
								<td style="background-color: #143052; color: #FFFFFF; font-weight: bold;">
									{{$data['mtx'][$b][$l][$v]}}
								</td>
							@else
								<td style="background-color: #bde3ff">
									{{$data['mtx'][$b][$l][$v]}}
								</td>
							@endif
						@elseif($l == 2 || $l == 3)
							@if($b == sizeof($data['mtx'])-1)
								<td style="background-color: #143052; color: #FFFFFF; font-weight: bold;">
									{{$data['mtx'][$b][$l][$v]}}
								</td>
							@else
								<td style="background-color: #dce6f1">
									{{$data['mtx'][$b][$l][$v]}}
								</td>
							@endif
						@else
							@if($b == sizeof($data['mtx'])-1)
								<td style="background-color: #0f243e; color: #FFFFFF; font-weight: bold;">
									{{$data['mtx'][$b][$l][$v]}}
								</td>
							@else
								<td style="background-color: #c3d8ef">
									{{$data['mtx'][$b][$l][$v]}}
								</td>
							@endif
						@endif
					@endif
				@endfor
			</tr>
		@endfor
		<tr><td>&nbsp;</td></tr>
	@endfor
</table>