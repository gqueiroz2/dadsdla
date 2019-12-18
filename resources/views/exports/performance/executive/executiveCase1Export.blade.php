<table>
	<tr>
		<th colspan="{{sizeof($data['mtx']['case1']['totalSGVarPrc'][0])+3}}">
			{{$data['mtx']['region']}} - Executive {{$data['mtx']['year']}} ({{$data['mtx']['currency']}}/{{$data['mtx']['valueView']}}) - BKGS
		</th>
	</tr>

	@for($s=0; $s < sizeof($data['mtx']['case1']['value']); $s++)
		@if($data['mtx']['salesRep'][$s]['salesRep'] == 'Martin Hernandez' && $data['mtx']['region'] == 'Chile')
			@if(($s+1) == sizeof($data['mtx']['salesRep']))
				@break;
			@else
				<?php $s++; ?>;
			@endif
		@elseif($data['mtx']['salesRep'][$s]['salesRep'] == 'Martin Hernandez' && $data['mtx']['region'] == 'Peru')
			@if(($s+1) == sizeof($data['mtx']['salesRep']))
				@break
			@else
				<?php $s++; ?>;
			@endif
		@elseif($data['mtx']['salesRep'][$s]['salesRep'] == 'Armstrong Boada' && $data['mtx']['region'] == 'Venezuela')
			@if(($s+1) == sizeof($data['mtx']['salesRep']))
				@break
			@else
				<?php $s++; ?>;
			@endif
		@elseif($data['mtx']['salesRep'][$s]['salesRep'] == 'Armstrong Boada' && $data['mtx']['region'] == 'Panama')
			@if(($s+1) == sizeof($data['mtx']['salesRep']))
				@break
			@else
				<?php $s++; ?>;
			@endif
		@elseif($data['mtx']['salesRep'][$s]['salesRep'] == 'Armstrong Boada' && $data['mtx']['region'] == 'Dominican Republic')
			@if(($s+1) == sizeof($data['mtx']['salesRep']))
				@break
			@else
				<?php $s++; ?>;
			@endif
		@elseif($data['mtx']['salesRep'][$s]['salesRep'] == 'Armstrong Boada' && $data['mtx']['region'] == 'Ecuador')
			@if(($s+1) == sizeof($data['mtx']['salesRep']))
				@break
			@else
				<?php $s++; ?>;
			@endif
		@elseif($data['mtx']['salesRep'][$s]['salesRep'] == 'Jesse Leon' && $data['mtx']['region'] == 'New York International')
			@if(($s+1) == sizeof($data['mtx']['salesRep']))
				@break
			@else
				{{$s++}};
			@endif
		@elseif($data['mtx']['salesRep'][$s]['salesRep'] == 'Jesse Leon' && $data['mtx']['region'] == 'NY International')
			@if(($s+1) == sizeof($data['mtx']['salesRep']))
				@break
			@else
				<?php $s++; ?>;
			@endif
		@endif

		<tr><td>&nbsp;</td></tr>

		<tr>
			<th colspan="{{sizeof($data['mtx']['case1']['totalSGVarPrc'][0])+3}}">
				{{$data['mtx']['salesRep'][$s]['salesRep']}}
			</th>
		</tr>

		<tr><td>&nbsp;</td></tr>

		@for($t=0; $t < sizeof($data['mtx']['case1']['value'][$s]); $t++)
			<tr>
				<td>
					@if($data['mtx']['tier'][$t] == 'TOTH')
						OTH
					@else
						{{$data['mtx']['tier'][$t]}}
					@endif
				</td>
				<td style="background-color: #a6a6a6;"></td>
				@for($q=0; $q < sizeof($data['mtx']['quarters']); $q++)
					<td style="background-color: #a6a6a6; font-weight: bold;">
						{{$data['mtx']['quarters'][$q]}}
					</td>
				@endfor
				<td style="background-color: #0f243e; font-weight: bold; color: #FFFFFF;">Total</td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td style="background-color: #dce6f1;">
					Target {{$data['mtx']['year']}}
				</td>
				@for($q=0; $q <sizeof($data['mtx']['quarters']); $q++)
					<td style="background-color: #dce6f1;">
						{{$data['mtx']['case1']['planValue'][$s][$t][$q]}}
					</td>
				@endfor
				<td style="background-color: #143052; font-weight: bold; color: #FFFFFF;">
					{{$data['mtx']['case1']['totalPlanValueTier'][$s][$t]}}
				</td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td style="background-color: #c3d8ef;">
					BKGS {{$data['mtx']['year']}}
				</td>
				@for($q=0; $q < sizeof($data['mtx']['quarters']); $q++)
					<td style="background-color: #c3d8ef;">
						{{$data['mtx']['case1']['value'][$s][$t][$q]}}
					</td>
				@endfor
				<td style="background-color: #143052; font-weight: bold; color: #FFFFFF;">
					{{$data['mtx']['case1']['totalValueTier'][$s][$t]}}
				</td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td style="background-color: #dce6f1;">Var Abs</td>
				@for($q=0; $q <sizeof($data['mtx']['quarters']);$q++)
					<td style="background-color: #dce6f1;">
						{{$data['mtx']['case1']['varAbs'][$s][$t][$q]}}
					</td>
				@endfor
				<td style="background-color: #143052; font-weight: bold; color: #FFFFFF;">
					{{$data['mtx']['case1']['totalVarAbs'][$s][$t]}}
				</td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td style='background-color: #c3d8ef;'>Var %</td>
				@for($q=0; $q < sizeof($data['mtx']['quarters']); $q++)
					<td style="background-color: #c3d8ef;">
						{{round($data['mtx']['case1']['varPrc'][$s][$t][$q])/100}}
					</td>
				@endfor
				<td style="background-color: #0f243e; font-weight: bold; color: #FFFFFF;">
					{{round($data['mtx']['case1']['totalVarPrc'][$s][$t])/100}}
				</td>
			</tr>
			<tr><td>&nbsp;</td></tr>
		@endfor

		<tr>
			<td>TT</td>
			<td style="background-color: #a6a6a6;"></td>
			@for($q=0; $q < sizeof($data['mtx']['quarters']); $q++)
				<td style="background-color: #a6a6a6; font-weight: bold;">
					{{$data['mtx']['quarters'][$q]}}
				</td>
			@endfor
			<td style="background-color: #0f243e; font-weight: bold; color: #FFFFFF;">Total</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td style="background-color: #dce6f1;">
				Target {{$data['mtx']['year']}}
			</td>
			@for($q=0; $q < sizeof($data['mtx']['quarters']);$q++)
				<td style="background-color: #dce6f1;">
					{{$data['mtx']['case1']['totalPlanSG'][$s][$q]}}
				</td>
			@endfor
			<td style="background-color: #143052; font-weight: bold; color: #FFFFFF;">
				{{$data['mtx']['case1']['totalPlanTotalSG'][$s]}}
			</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td style="background-color: #c3d8ef;">
				BKGS {{$data['mtx']['year']}}
			</td>
			@for($q=0; $q < sizeof($data['mtx']['quarters']); $q++)
				<td style="background-color: #c3d8ef;">
					{{$data['mtx']['case1']['totalSG'][$s][$q]}}
				</td>
			@endfor
			<td style="background-color: #143052; font-weight: bold; color: #FFFFFF;">
				{{$data['mtx']['case1']['totalTotalSG'][$s]}}
			</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td style="background-color: #dce6f1;">Var Abs</td>
			@for($q=0; $q < sizeof($data['mtx']['quarters']); $q++)
				<td style="background-color: #dce6f1;">
					{{$data['mtx']['case1']['totalSGVarAbs'][$s][$q]}}
				</td>
			@endfor
			<td style="background-color: #143052; font-weight: bold; color: #FFFFFF;">
				{{$data['mtx']['case1']['totalTotalSGVarAbs'][$s]}}
			</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td style="background-color: #c3d8ef;">Var %</td>
			@for($q=0; $q < sizeof($data['mtx']['quarters']); $q++)
				<td style="background-color: #c3d8ef;">
					{{round($data['mtx']['case1']['totalSGVarPrc'][$s][$q])/100}}
				</td>
			@endfor
			<td style="background-color: #0f243e; font-weight: bold; color: #FFFFFF;">
				{{round($data['mtx']['case1']['totalTotalSGVarPrc'][$s])/100}}
			</td>
		</tr>
	@endfor
</table>