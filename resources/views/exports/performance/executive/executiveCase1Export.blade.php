<table>
	<tr>
		<th>{{$data['mtx']['region']}} - Executive {{$data['mtx']['year']}} ({{$data['mtx']['currency']}}/{{$data['mtx']['valueView']}}) - BKGS</th>
	</tr>
</table>


@for($m=0; $m < sizeof($data['mtx']['case1']['value']); $s++)
	@if($data['mtx']['salesRep'][$m]['salesRep'] == 'Martin Hernandez' && $data['mtx']['region'] == 'Chile')
		@if(($m+1) == sizeof($data['mtx']['salesRep']))
			@break
		@else
			{{$m++}};
		@endif
	@elseif($data['mtx']['salesRep'][$m]['salesRep'] == 'Martin Hernandez' && $data['mtx']['region'] == 'Peru')
		@if(($m+1) == sizeof($data['mtx']['salesRep']))
			@break
		@else
			{{$m++}};
		@endif
	@elseif($data['mtx']['salesRep'][$m]['salesRep'] == 'Armstrong Boada' && $data['mtx']['region'] == 'Venezuela')
		@if(($m+1) == sizeof($data['mtx']['salesRep']))
			@break
		@else
			{{$m++}};
		@endif
	@elseif($data['mtx']['salesRep'][$m]['salesRep'] == 'Armstrong Boada' && $data['mtx']['region'] == 'Panama')
		@if(($m+1) == sizeof($data['mtx']['salesRep']))
			@break
		@else
			{{$m++}};
		@endif
	@elseif($data['mtx']['salesRep'][$m]['salesRep'] == 'Armstrong Boada' && $data['mtx']['region'] == 'Dominican Republic')
		@if(($m+1) == sizeof($data['mtx']['salesRep']))
			@break
		@else
			{{$m++}};
		@endif
	@elseif($data['mtx']['salesRep'][$m]['salesRep'] == 'Armstrong Boada' && $data['mtx']['region'] == 'Ecuador')
		@if(($m+1) == sizeof($data['mtx']['salesRep']))
			@break
		@else
			{{$m++}};
		@endif
	@elseif($data['mtx']['salesRep'][$m]['salesRep'] == 'Jesse Leon' && $data['mtx']['region'] == 'New York International')
		@if(($m+1) == sizeof($data['mtx']['salesRep']))
			@break
		@else
			{{$m++}};
		@endif
	@elseif($data['mtx']['salesRep'][$m]['salesRep'] == 'Jesse Leon' && $data['mtx']['region'] == 'NY International')
		@if(($m+1) == sizeof($data['mtx']['salesRep']))
			@break
		@else
			{{$m++}};
		@endif
	@endif

	{{--@if(sizeof($data['mtx']['salesRep']) == 1)

	@endif--}}

	<table>
		<th> {{$data['mtx']['salesRep'][$m]['salesRep']}}</th>
	</table>

	@for($t=0; $t < sizeof($data['mtx']['case1']['value'][$m]); $t)
		<table>
			<tr>
				<td {{--rowspan='5' {{strtolower($data['mtx']['tier'][$t])}}--}}>
				
				@if($data['mtx']['tier'][$t] == 'TOTH')
					OTH
				@else
					{{$data['mtx']['tier'][$t]}}
				@endif
				</td>
				<td style="background-color: #a6a6a6;"></td>
				@for($q=0; $q <sizeof($data['mtx']['quarters']); $q++)
					<td style="background-color: #a6a6a6;">{{$data['mtx']['quarters'][$q]}}</td>
				@endfor	
				<td style="background-color: #0f243e;">Total</td>
			</tr>
			<tr>
				<td style="background-color: #dce6f1;">Target {{$data['year']}}</td>
				@for($q=0; $q <sizeof($data['mtx']['quarters']); $q++)
					<td style="background-color: #dce6f1;">{{$data['mtx']['case1']['planValue'][$m][$t][$q]}}</td>
				@endfor
				<td style="background-color: #143052;">{{$data['mtx']['case1']['totalPlanValueTier'][$m][$t]}}</td>
			</tr>
			<tr>
				<td style="background-color: #c3d8ef;">BKGS {{$data['year']}}</td>
				@for($q=0; $q <sizeof($data['mtx']['quarters']); $q++)
					<td style="background-color: #c3d8ef;">{{$data['mtx']['case1']['value'][$m][$t][$q]}}</td>
				@endfor
				<td style="background-color: #143052;">{{$data['mtx']['case1']['totalPlanValueTier'][$m][$t]}}</td>
			</tr>
			<tr>
				<td style="background-color: #dce6f1;">Var Abs</td>
				@for($q=0; $q <sizeof($data['mtx']['quarters']);$q++)
					<td style="background-color: #dce6f1;">{{$data['mtx']['case1']['varAbs'][$m][$t][$q]}}</td>
				@endfor
				<td style="background-color: #143052;">{{$mtx['mtx']['case1']['totalVarAbs'][$m][$t]}}</td>
			</tr>
			<tr>
				<td style='background-color: #c3d8ef;'>Var %</td>
				@for($q=0; $q < sizeof($data['mtx']['quarters']); $q++)
					<td style="background-color: #c3d8ef;">{{$data['mtx']['case 1']['varPrc'][$m][$t][$q]}}</td>
				@endfor
				<td style="background-color: #0f243e;">{{$data['mtx']['case1']['totalVarPrc'][$m][$t]}}</td>
			</tr>
		</table>
	@endfor

	<table>
		<tr>
			<td {{--rowspan='5'--}} style="background-color: #0f243e;">TT</td>
			<td style="background-color: #a6a6a6;"></td>
			@for($q=0; $q < sizeof($data['mtx']['quarters']); $q++)
				<td style="background-color: #a6a6a6;">{{$data['mtx']['quarters'][$q]}}</td>
			@endfor
			<td style="background-color: #0f243e;">Total</td>
		</tr>
		<tr>
			<td style="background-color: #dce6f1;">Target {{$data['year']}}</td>
			@for($q=0; $q < sizeof($data['mtx']['quarters']);$q++)
				<td style="background-color: #dce6f1;">{{$data['mtx']['case1']['totalPlanSG'][$m][$q]}}</td>
			@endfor
			<td style="background-color: #143052;">{{$data['mtx']['case1']['totalPlanTotalSG'][$m]}}</td>
		</tr>
		<tr>
			<td style="background-color: #c3d8ef;">BKGS {{$data['year']}}</td>
			@for($q=0; $q < sizeof($data['mtx']['quarters']); $q++)
				<td style="background-color: #c3d8ef;">{{$data['mtx']['case1']['totalSG'][$m][$q]}}</td>
			@endfor
			<td style="background-color: #143052;">{{$data['mtx']['totalTotalSG'][$m]}}</td>
		</tr>
		<tr>
			<td style="background-color: #dce6f1;">Var Abs</td>
			@for($q=0; $q < sizeof($data['mtx']['quarters']); $q++)
				<td style="background-color: #dce6f1;">{{$data['mtx']['case1']['totalSGVarAbs'][$m][$q]}}</td>
			@endfor
			<td style="background-color: #143052;">{{$data['mtx']['case1']['totalTotalSGVarAbs'][$m]}}</td>
		</tr>
		<tr>
			<td style="background-color: #c3d8ef;">Var %</td>
			@for($q=0; $q < sizeof($data['mtx']['quarters']); $q++)
				<td style="background-color: #c3d8ef;">{{$data['mtx']['case1']['totalSGVarPrc'][$m][$q]}}</td>
			@endfor
			<td style="background-color: #0f243e;">{{$data['mtx']['case1']['totalTotalSGVarPrc'][$m]}}</td>
		</tr>
	</table>
@endfor