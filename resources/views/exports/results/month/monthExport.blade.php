<table>
	<tr>
		<th style="background-color: #0070c0" colspan="14">
			<span>
				{{$data['region']}} - Month : BKGS - {{$data['year']}} ({{strtoupper($data['currency'][0]['name'])}}/{{strtoupper($data['value'])}})
			</span>
		</th>
	</tr>

	<tr><td>&nbsp;</td></tr>

	@for($m = 0; $m < sizeof($data['mtx']); $m++)
		@for($n = 0; $n < sizeof($data['mtx'][$m]); $n++)
			<tr>
			@for($o = 0; $o < sizeof($data['mtx'][$m][$n]); $o++)
				@if(is_numeric($data['mtx'][$m][$n][$o]))
					@if($n == 3)
						@if($o == 13)
							<td style="background-color: #143052; color: #FFFFFF; font-weight: bold;">
								{{$data['mtx'][$m][$n][$o]/100}}
							</td>
						@else
							<td style="background-color: #dce6f1">
								{{$data['mtx'][$m][$n][$o]/100}}
							</td>
						@endif
					@elseif($n == 4)
						@if($o == 13)
							<td style="background-color: #0f243e; color: #FFFFFF; font-weight: bold;">
								{{$data['mtx'][$m][$n][$o]}}
							</td>
						@else
							<td style="background-color: #c3d8ef">
								{{$data['mtx'][$m][$n][$o]}}
							</td>
						@endif
					@else
						@if($o == 13)
							<td style="background-color: #143052; color: #FFFFFF; font-weight: bold;">
								{{$data['mtx'][$m][$n][$o]}}
							</td>
						@else
							<td>
								{{$data['mtx'][$m][$n][$o]}}
							</td>
						@endif
					@endif
				@else
					@if($n == 0 && $o == 0)
						@if ($data['mtx'][$m][$n][$o] == "DN") 
							<td style='background-color: #0f243e; color: #FFFFFF; font-weight: bold;'>
								{{$data['mtx'][$m][$n][$o]}}
							</td>
						@else
							<td style='background-color: #0070c0; color: #FFFFFF; font-weight: bold;'>
								{{$data['mtx'][$m][$n][$o]}}
							</td>
						@endif
					@elseif($n == 0 && $o != 0)
						@if($o == 13)
							<td style='background-color: #0f243e; color: #FFFFFF; font-weight: bold;'>
								{{$data['mtx'][$m][$n][$o]}}
							</td>
						@else
							<td style='background-color: #a6a6a6; font-weight: bold;'>
								{{$data['mtx'][$m][$n][$o]}}
							</td>
						@endif
					@elseif($n == 1 && $o == 0)
						@if ($m == (sizeof($data['mtx'])-1)) 
							<td style='background-color: #143052; color: #FFFFFF; font-weight: bold;'>
								{{$data['mtx'][$m][$n][$o]}}
							</td>
						@else
							<td style='background-color: #bde3ff; font-weight: bold;'>
								{{$data['mtx'][$m][$n][$o]}}
							</td>
						@endif
					@elseif( ($n == 2 || $n == 3)  && $o == 0)
						@if ($m == (sizeof($data['mtx'])-1)) 
							<td style='background-color: #143052; color: #FFFFFF; font-weight: bold;'>
								{{$data['mtx'][$m][$n][$o]}}
							</td>
						@else
							<td style='background-color: #dce6f1; font-weight: bold;'>
								{{$data['mtx'][$m][$n][$o]}}
							</td>
						@endif
					@elseif($n == 4 && $o == 0)
						@if ($m == (sizeof($data['mtx'])-1)) 
							<td style='background-color: #0f243e; color: #FFFFFF; font-weight: bold;'>
								{{$data['mtx'][$m][$n][$o]}}
							</td>
						@else
							<td style="background-color: #c3d8ef; font-weight: bold;">
								{{$data['mtx'][$m][$n][$o]}}
							</td>
						@endif
					@else
						<td>{{$data['mtx'][$m][$n][$o]}}</td>
					@endif
				@endif
			@endfor
			</tr>
		@endfor
		<tr><td>&nbsp;</td></tr>
	@endfor
</table>