<table>
	<tr>
		<th colspan="{{sizeof($data['mtx']['quarters'])+3}}">
			{{$data['mtx']['region']}} - Core {{$data['mtx']['year']}} ({{$data['mtx']['currency']}}/{{$data['mtx']['valueView']}}) - BKGS
		</th>
	</tr>

	<tr><td>&nbsp;</td></tr>

	@for($sg=0; $sg < sizeof($data['mtx']["salesGroup"]); $sg++)

		<tr>
			<th colspan="{{sizeof($data['mtx']['quarters'])+3}}">
				{{$data['mtx']['salesGroup'][$sg]['name']}}
			</th>
		</tr>

		<tr><td>&nbsp;</td></tr>

		@for ($b=0; $b < sizeof($data['mtx']["brand"]); $b++)
			<tr>
				<td>{{$data['mtx']["brand"][$b][1]}}</td>
				<td style="background-color: #a6a6a6;"></td>
				@for ($q=0; $q < sizeof($data['mtx']["quarters"]); $q++)
					<td style="background-color: #a6a6a6; font-weight: bold;">{{$data['mtx']["quarters"][$q]}}</td>
				@endfor
				<td style="background-color: #0f243e; color: #FFFFFF; font-weight: bold;">Total</td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td style="background-color: #dce6f1;">Target {{$data['cYear']}}</td>
				@for ($q=0; $q < sizeof($data['mtx']["quarters"]); $q++)
					<td style="background-color: #dce6f1;">{{$data['mtx']["case2"]["planValue"][$sg][$b][$q]}}</td>
				@endfor
				<td style="background-color: #143052; color: #FFFFFF; font-weight: bold;">{{$data['mtx']["case2"]["totalPlanValueBrand"][$sg][$b]}}</td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td style="background-color: #c3d8ef;">BKGS {{$data['cYear']}}</td>
				@for ($q=0; $q < sizeof($data['mtx']["quarters"]); $q++)
					<td style="background-color: #c3d8ef;">{{$data['mtx']["case2"]["value"][$sg][$b][$q]}}</td>
				@endfor
				<td style="background-color: #143052; color: #FFFFFF; font-weight: bold;">{{$data['mtx']["case2"]["totalValueBrand"][$sg][$b]}}</td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td style="background-color: #dce6f1;">Var Abs</td>
				@for ($q=0; $q < sizeof($data['mtx']["quarters"]); $q++)
					<td style="background-color: #dce6f1;">{{$data['mtx']["case2"]["varAbs"][$sg][$b][$q]}}</td>
				@endfor
				<td style="background-color: #143052; color: #FFFFFF; font-weight: bold;">{{$data['mtx']["case2"]["totalVarAbs"][$sg][$b]}}</td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td style="background-color: #c3d8ef;">Var %</td>
				@for ($q=0; $q < sizeof($data['mtx']["quarters"]); $q++)
					<td style="background-color: #c3d8ef;">{{$data['mtx']["case2"]["varPrc"][$sg][$b][$q]/100}}</td>
				@endfor
				<td style="background-color: #0f243e; color: #FFFFFF; font-weight: bold;">{{$data['mtx']["case2"]["totalVarPrc"][$sg][$b]/100}}</td>
			</tr>

			<tr><td>&nbsp;</td></tr>
		@endfor

		<tr>
			<td>DN</td>
			<td style="background-color: #a6a6a6;"></td>
			@for ($q=0; $q < sizeof($data['mtx']["quarters"]); $q++)
				<td style="background-color: #a6a6a6; font-weight: bold;">{{$data['mtx']["quarters"][$q]}}</td>
			@endfor
			<td style="background-color: #0f243e; color: #FFFFFF; font-weight: bold;">Total</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td style="background-color: #dce6f1;">Target {{$data['cYear']}}</td>
			@for ($q=0; $q < sizeof($data['mtx']["quarters"]); $q++)
				<td style="background-color: #dce6f1;">{{$data['mtx']["case2"]["dnPlanValue"][$sg][$q]}}</td>
			@endfor
			<td style="background-color: #143052; color: #FFFFFF; font-weight: bold;">{{$data['mtx']["case2"]["dnTotalPlanValue"][$sg]}}</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td style="background-color: #c3d8ef;">BKGS {{$data['cYear']}}</td>
			@for ($q=0; $q < sizeof($data['mtx']["quarters"]); $q++)
				<td style="background-color: #c3d8ef;">{{$data['mtx']["case2"]["dnValue"][$sg][$q]}}</td>
			@endfor
			<td style="background-color: #143052; color: #FFFFFF; font-weight: bold;">{{$data['mtx']["case2"]["dnTotalValue"][$sg]}}</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td style="background-color: #dce6f1;">Var Abs</td>
			@for ($q=0; $q < sizeof($data['mtx']["quarters"]); $q++)
				<td style="background-color: #dce6f1;">{{$data['mtx']["case2"]["dnVarAbs"][$sg][$q]}}</td>
			@endfor
			<td style="background-color: #143052; color: #FFFFFF; font-weight: bold;">{{$data['mtx']["case2"]["dnTotalVarAbs"][$sg]}}</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td style="background-color: #c3d8ef;">Var %</td>
			@for ($q=0; $q < sizeof($data['mtx']["quarters"]); $q++)
				<td style="background-color: #c3d8ef;">{{$data['mtx']["case2"]["dnVarPrc"][$sg][$q]/100}}</td>
			@endfor
			<td style="background-color: #0f243e; color: #FFFFFF; font-weight: bold;">{{$data['mtx']["case2"]["dnTotalVarPrc"][$sg]/100}}</td>
		</tr>

		<tr><td>&nbsp;</td></tr>
	@endfor

	<tr>
		<th colspan="{{sizeof($data['mtx']['quarters'])+3}}">
			Total
		</th>
	</tr>

	<tr><td>&nbsp;</td></tr>

	@for ($t=0; $t < sizeof($data['mtx']["brand"]); $t++)
		<tr>
			<td>{{$data['mtx']["brand"][$t][1]}}</td>
			<td style="background-color: #a6a6a6;"></td>
			@for ($q=0; $q < sizeof($data['mtx']["quarters"]); $q++)
				<td style="background-color: #a6a6a6; font-weight: bold;">{{$data['mtx']["quarters"][$q]}}</td>
			@endfor
			<td style="background-color: #0f243e; color: #FFFFFF; font-weight: bold;">Total</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td style="background-color: #dce6f1;">Target {{$data['cYear']}}</td>
			@for ($q=0; $q < sizeof($data['mtx']["quarters"]); $q++)
				<td style="background-color: #dce6f1;">{{$data['mtx']["total"]["case2"]["planValues"][$t][$q]}}</td>
			@endfor
			<td style="background-color: #143052; color: #FFFFFF; font-weight: bold;">{{$data['mtx']["total"]["case2"]["totalPlanValueBrand"][$t]}}</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td style="background-color: #c3d8ef;">BKGS {{$data['cYear']}}</td>
			@for ($q=0; $q < sizeof($data['mtx']["quarters"]); $q++)
				<td style="background-color: #c3d8ef;">{{$data['mtx']["total"]["case2"]["values"][$t][$q]}}</td>
			@endfor
			<td style="background-color: #143052; color: #FFFFFF; font-weight: bold;">{{$data['mtx']["total"]["case2"]["totalValueBrand"][$t]}}</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td style="background-color: #dce6f1;">Var Abs</td>
			@for ($q=0; $q < sizeof($data['mtx']["quarters"]); $q++)
				<td style="background-color: #dce6f1;">{{$data['mtx']["total"]["case2"]["varAbs"][$t][$q]}}</td>
			@endfor
			<td style="background-color: #143052; color: #FFFFFF; font-weight: bold;">{{$data['mtx']["total"]["case2"]["totalVarAbs"][$t]}}</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td style="background-color: #c3d8ef;">Var %</td>
			@for ($q=0; $q < sizeof($data['mtx']["quarters"]); $q++)
				<td style="background-color: #c3d8ef;">{{$data['mtx']["total"]["case2"]["varPrc"][$t][$q]/100}}</td>
			@endfor
			<td style="background-color: #0f243e; color: #FFFFFF; font-weight: bold;">{{$data['mtx']["total"]["case2"]["totalVarPrc"][$t]/100}}</td>
		</tr>

		<tr><td>&nbsp;</td></tr>

	@endfor

	<tr>
		<td>DN</td>
		<td style="background-color: #a6a6a6;"></td>
		@for ($q=0; $q < sizeof($data['mtx']["quarters"]); $q++)
			<td style="background-color: #a6a6a6; font-weight: bold;">{{$data['mtx']["quarters"][$q]}}</td>
		@endfor
		<td style="background-color: #0f243e; color: #FFFFFF; font-weight: bold;">Total</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td style="background-color: #dce6f1;">Target {{$data['cYear']}}</td>
		@for ($q=0; $q < sizeof($data['mtx']["quarters"]); $q++)
			<td style="background-color: #dce6f1;">{{$data['mtx']["total"]["case2"]["dnPlanValue"][$q]}}</td>
		@endfor
		<td style="background-color: #143052; color: #FFFFFF; font-weight: bold;">{{$data['mtx']["total"]["case2"]["dnTotalPlanValue"]}}</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td style="background-color: #c3d8ef;">BKGS {{$data['cYear']}}</td>
		@for ($q=0; $q < sizeof($data['mtx']["quarters"]); $q++)
			<td style="background-color: #c3d8ef;">{{$data['mtx']["total"]["case2"]["dnValue"][$q]}}</td>
		@endfor
		<td style="background-color: #143052; color: #FFFFFF; font-weight: bold;">{{$data['mtx']["total"]["case2"]["dnTotalValue"]}}</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td style="background-color: #dce6f1;">Var Abs</td>
		@for ($q=0; $q < sizeof($data['mtx']["quarters"]); $q++)
			<td style="background-color: #dce6f1;">{{$data['mtx']["total"]["case2"]["dnVarAbs"][$q]}}</td>
		@endfor
		<td style="background-color: #143052; color: #FFFFFF; font-weight: bold;">{{$data['mtx']["total"]["case2"]["dnTotalVarAbs"]}}</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td style="background-color: #c3d8ef;">Var %</td>
		@for ($q=0; $q < sizeof($data['mtx']["quarters"]); $q++)
			<td style="background-color: #c3d8ef;">{{$data['mtx']["total"]["case2"]["dnVarPrc"][$q]/100}}</td>
		@endfor
		<td style="background-color: #0f243e; color: #FFFFFF; font-weight: bold;">{{$data['mtx']["total"]["case2"]["dnTotalVarPrc"]/100}}</td>
	</tr>

</table>