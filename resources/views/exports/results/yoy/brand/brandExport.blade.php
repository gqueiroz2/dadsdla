<table>
	<tr>
		<th colspan="15" style="background-color:#0070c0;">
			{{$data['region']}} - Year Over Year : BKGS - {{$data['year']}} ({{strtoupper($data['currency'][0]['name'])}}/{{strtoupper($data['value'])}})			
		</th>
	</tr>
	<tr><td>&nbsp;</td></tr>
	@for($d = 0; $d < sizeof($data['mtx']); $d++)
		@for($l = 0; $l < sizeof($data['mtx'][$d]); $l++)
			<tr>
				@for($v = 0; $v < sizeof($data['mtx'][$d][$l]); $v++)
					@if($l == 0)
						@if($v > 0 && $v < 13)
							<td style="background-color: #a6a6a6; font-weight: bold;">
								{{$data['mtx'][$d][$l][$v]}}
							</td>
						@elseif($v == 0)
							<td style="font-weight: bold;">
								{{$data['mtx'][$d][$l][$v]}}
							</td>
							@if($d == (sizeof($data['mtx'])-1))
								<td style="background-color: #0f243e;">&nbsp;</td>
							@else
								<td style="background-color: #a6a6a6;">&nbsp;</td>
							@endif
						@else
							<td style="background-color: #0f243e; font-weight: bold; color: #FFFFFF;">
								{{$data['mtx'][$d][$l][$v]}}	
							</td>
						@endif
					@elseif($l == 1 || $l == 2)
						@if($v > 0 && $v < 13)
							<td style="font-weight: bold;">
								{{$data['mtx'][$d][$l][$v]}}	
							</td>
						@elseif($v == 0)
							<td>&nbsp;</td>
							@if($d == (sizeof($data['mtx'])-1))
								<td style="background-color: #143052; font-weight: bold; color: #FFFFFF;">
									{{$data['mtx'][$d][$l][$v]}}
								</td>
							@elseif($l == 2)
								<td style="background-color: #bde3ff; font-weight: bold;">
									{{$data['mtx'][$d][$l][$v]}}	
								</td>
							@else
								<td style="background-color: #dce6f1; font-weight: bold;">
									{{$data['mtx'][$d][$l][$v]}}	
								</td>
							@endif
						@else
							<td style="background-color: #143052; font-weight: bold; color: #FFFFFF;">
								{{$data['mtx'][$d][$l][$v]}}	
							</td>
						@endif
					@elseif($l == 3)
						@if($v > 0 && $v < 13)
							<td style="background-color: #dce6f1; font-weight: bold;">
								{{$data['mtx'][$d][$l][$v]}}	
							</td>
						@elseif($v == 0)
							<td>&nbsp;</td>
							@if($d == (sizeof($data['mtx'])-1))
								<td style="background-color: #143052; font-weight: bold; color: #FFFFFF;">
									{{$data['mtx'][$d][$l][$v]}}	
								</td>
							@else
								<td style="background-color: #dce6f1; font-weight: bold;">
									{{$data['mtx'][$d][$l][$v]}}	
								</td>
							@endif
						@else
							<td style="background-color: #143052; font-weight: bold; color: #FFFFFF;">
								{{$data['mtx'][$d][$l][$v]}}	
							</td>
						@endif
					@else
						@if($v > 0 && $v < 13)
							<td style="background-color: #c3d8ef; font-weight: bold;">
								{{$data['mtx'][$d][$l][$v]}}	
							</td>
						@elseif($v == 0)
							<td>&nbsp;</td>
							@if($d == (sizeof($data['mtx'])-1))
								@if($l == 4)
									<td style="background-color: #143052; font-weight: bold; color: #FFFFFF;">
										{{$data['mtx'][$d][$l][$v]}}	
									</td>
								@else
									<td style="background-color: #0f243e; font-weight: bold; color: #FFFFFF;">
										{{$data['mtx'][$d][$l][$v]}}	
									</td>
								@endif
							@else
								<td style="background-color: #c3d8ef; font-weight: bold;">
									{{$data['mtx'][$d][$l][$v]}}	
								</td>
							@endif
						@else
							@if($l == 4)
								<td style="background-color: #143052; font-weight: bold; color: #FFFFFF;">
									{{$data['mtx'][$d][$l][$v]}}	
								</td>
							@else
								<td style="background-color: #0f243e; font-weight: bold; color: #FFFFFF;">
									{{$data['mtx'][$d][$l][$v]}}	
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