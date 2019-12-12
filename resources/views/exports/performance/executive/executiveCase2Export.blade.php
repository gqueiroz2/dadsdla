<table>
	<tr>
		<th colspan="{{sizeof($data['mtx']['case2']['dnValue'][0])+3}}">
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
			<th colspan="{{sizeof($data['mtx']['case2']['dnValue'][0])+3}}">
				{{$data['mtx']['salesRep'][$s]['salesRep']}}
			</th>	
		</tr>

		<tr><td>&nbsp;</td></tr>

		@for($b=0; $b < sizeof($data['mtx']['brand']); $b++)
			<tr>
				<td>
					{{$data['mtx']['brand'][$b][1]}}
				</td>
				<td style="background-color: #a6a6a6"></td>
				@for($q=0; $q < sizeof($data['mtx']['quarters']); $q++)
					<td style="background-color: #a6a6a6; font-weight: bold;">
						{{$data['mtx']['quarters'][$q]}}
					</td>
				@endfor
				<td style="background-color: #0f243e; color: #FFFFFF; font-weight: bold;">
					Total
				</td>
			</tr>
			<tr>
				<td>&nbsp;</td>
    			<td style="background-color: #dce6f1">
    				Target {{$data['cYear']}}
    			</td>
    			@for ($q=0; $q < sizeof($data['mtx']["quarters"]); $q++) 
    				<td style="background-color: #dce6f1">
    					{{$data['mtx']["case2"]["planValue"][$s][$b][$q]}}
    				</td>
    			@endfor
    			<td style="background-color: #143052; color: #FFFFFF; font-weight: bold">
    				{{$data['mtx']["case2"]["totalPlanValueBrand"][$s][$b]}}
    			</td>
			</tr>
			<tr>
				<td>&nbsp;</td>
    			<td style="background-color: #c3d8ef">BKGS {{$data['cYear']}}</td>
    			@for ($q=0; $q < sizeof($data['mtx']["quarters"]); $q++)
    				<td style="background-color: #c3d8ef">
    					{{$data['mtx']["case2"]["value"][$s][$b][$q]}}
    				</td>
    			@endfor
    			<td style="background-color: #143052; color: #FFFFFF; font-weight: bold">
    				{{$data['mtx']["case2"]["totalValueBrand"][$s][$b]}}
    			</td>
			</tr>
			<tr>
				<td>&nbsp;</td>
    			<td style="background-color: #dce6f1">Var Abs</td>
    			@for ($q=0; $q < sizeof($data['mtx']["quarters"]); $q++)
    				<td style="background-color: #dce6f1">
    					{{$data['mtx']["case2"]["varAbs"][$s][$b][$q]}}
    				</td>
    			@endfor
    			<td style="background-color: #143052; color: #FFFFFF; font-weight: bold">
    				{{$data['mtx']["case2"]["totalVarAbs"][$s][$b]}}
    			</td>
			</tr>
			<tr>
				<td>&nbsp;</td>
    			<td style="background-color: #c3d8ef">Var %</td>
    			@for ($q=0; $q < sizeof($data['mtx']["quarters"]); $q++)
    				<td style="background-color: #c3d8ef">
    					{{$data['mtx']["case2"]["varPrc"][$s][$b][$q]/100}}
    				</td>
    			@endfor
    			<td style="background-color: #0f243e; color: #FFFFFF; font-weight: bold">
    				{{$data['mtx']["case2"]["totalVarPrc"][$s][$b]/100}}
    			</td>
			</tr>
			<tr><td>&nbsp;</td></tr>
		@endfor

		<tr>
			<td>DN</td>
			<td style="background-color: #a6a6a6"></td>
			@for ($q=0; $q < sizeof($data['mtx']["quarters"]); $q++)
				<td style="background-color: #a6a6a6; font-weight: bold;">
					{{$data['mtx']["quarters"][$q]}}
				</td>
			@endfor
			<td style="background-color: #0f243e; color: #FFFFFF; font-weight: bold;">
				Total
			</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td style="background-color: #dce6f1">Target {{$data['cYear']}}</td>
			@for ($q=0; $q < sizeof($data['mtx']["quarters"]); $q++)
				<td style="background-color: #dce6f1">
					{{$data['mtx']["case2"]["dnPlanValue"][$s][$q]}}
				</td>
			@endfor
			<td style="background-color: #143052; color: #FFFFFF; font-weight: bold">
				{{$data['mtx']["case2"]["dnTotalPlanValue"][$s]}}
			</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td style="background-color: #c3d8ef">BKGS {{$data['cYear']}}</td>
			@for ($q=0; $q < sizeof($data['mtx']["quarters"]); $q++)
				<td style="background-color: #c3d8ef">
					{{$data['mtx']["case2"]["dnValue"][$s][$q]}}
				</td>
			@endfor
			<td style="background-color: #143052; color: #FFFFFF; font-weight: bold">
				{{$data['mtx']["case2"]["dnTotalValue"][$s]}}
			</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td style="background-color: #dce6f1">Var Abs</td>
			@for ($q=0; $q < sizeof($data['mtx']["quarters"]); $q++)
				<td style="background-color: #dce6f1">
					{{$data['mtx']["case2"]["dnVarAbs"][$s][$q]}}
				</td>
			@endfor
			<td style="background-color: #143052; color: #FFFFFF; font-weight: bold">
				{{$data['mtx']["case2"]["dnTotalVarAbs"][$s]}}
			</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td style="background-color: #c3d8ef">Var %</td>
			@for ($q=0; $q < sizeof($data['mtx']["quarters"]); $q++)
				<td style="background-color: #c3d8ef">
					{{$data['mtx']["case2"]["dnVarPrc"][$s][$q]/100}}
				</td>
			@endfor
			<td style="background-color: #0f243e; color: #FFFFFF; font-weight: bold">
				{{$data['mtx']["case2"]["dnTotalVarPrc"][$s]/100}}
			</td>
		</tr>
	@endfor
</table>