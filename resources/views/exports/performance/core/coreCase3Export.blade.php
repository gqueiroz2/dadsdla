<table>
	<tr>
		<th colspan="{{sizeof($data['mtx']['month'])+3}}">
			{{$data['mtx']['region']}} - Core {{$data['mtx']['year']}} ({{$data['mtx']['currency']}}/{{$data['mtx']['valueView']}}) - BKGS
		</th>
	</tr>

	<tr><td>&nbsp;</td></tr>

	@for($sg=0; $sg < sizeof($data['mtx']["salesGroup"]); $sg++)

		<tr>
			<th colspan="{{sizeof($data['mtx']['month'])+3}}">
				{{$data['mtx']['salesGroup'][$sg]['name']}}
			</th>
		</tr>

		<tr><td>&nbsp;</td></tr>

		@for ($b=0; $b < sizeof($data['mtx']["tier"]); $b++)
    		<tr>
    			<td>
	    			@if ($data['mtx']["tier"][$b] == "TOTH")
	    				OTH
	    			@else
	    				{{$data['mtx']["tier"][$b]}}
	    			@endif
    			</td>
    			<td style="background-color: #a6a6a6;"></td>
    			@for ($q=0; $q < sizeof($data['mtx']["month"]); $q++)
    				<td style="background-color: #a6a6a6; font-weight: bold;">{{$data['mtx']["month"][$q]}}</td>
    			@endfor
    			<td style="background-color: #0f243e; color: #FFFFFF; font-weight: bold;">Total</td>
			</tr>
    		<tr>
    			<td>&nbsp;</td>
    			<td style="background-color: #dce6f1;">Target {{$data['cYear']}}</td>
    			@for ($q=0; $q < sizeof($data['mtx']["month"]); $q++)
    				<td style="background-color: #dce6f1;">{{$data['mtx']["case3"]["planValues"][$sg][$b][$q]}}</td>
    			@endfor
    			<td style="background-color: #143052; color: #FFFFFF; font-weight: bold;">{{$data['mtx']["case3"]["totalPlanValueTier"][$sg][$b]}}</td>
			</tr>
			<tr>
				<td>&nbsp;</td>
    			<td style="background-color: #c3d8ef;">BKGS {{$data['cYear']}}</td>
    			@for ($q=0; $q < sizeof($data['mtx']["month"]); $q++)
    				<td style="background-color: #c3d8ef;">{{$data['mtx']["case3"]["values"][$sg][$b][$q]}}</td>
    			@endfor
    			<td style="background-color: #143052; color: #FFFFFF; font-weight: bold;">{{$data['mtx']["case3"]["totalValueTier"][$sg][$b]}}</td>
			</tr>
			<tr>
				<td>&nbsp;</td>
    			<td style="background-color: #dce6f1;">Var Abs</td>
    			@for ($q=0; $q < sizeof($data['mtx']["month"]); $q++)
    				<td style="background-color: #dce6f1;">{{$data['mtx']["case3"]["varAbs"][$sg][$b][$q]}}</td>
    			@endfor
    			<td style="background-color: #143052; color: #FFFFFF; font-weight: bold;">{{$data['mtx']["case3"]["totalVarAbs"][$sg][$b]}}</td>
			</tr>
			<tr>
				<td>&nbsp;</td>
    			<td style="background-color: #c3d8ef;">Var %</td>
    			@for ($q=0; $q < sizeof($data['mtx']["month"]); $q++)
    				<td style="background-color: #c3d8ef;">{{$data['mtx']["case3"]["varPrc"][$sg][$b][$q]/100}}</td>
    			@endfor
    			<td style="background-color: #0f243e; color: #FFFFFF; font-weight: bold;">{{$data['mtx']["case3"]["totalVarPrc"][$sg][$b]/100}}</td>
			</tr>

			<tr><td>&nbsp;</td></tr>

		@endfor

		<tr>
			<td>TT</td>
			<td style="background-color: #a6a6a6;"></td>
			@for ($q=0; $q < sizeof($data['mtx']["month"]); $q++)
				<td style="background-color: #a6a6a6; font-weight: bold;">{{$data['mtx']["month"][$q]}}</td>
			@endfor
			<td style="background-color: #0f243e; color: #FFFFFF; font-weight: bold;">Total</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td style="background-color: #dce6f1;">Target {{$data['cYear']}}</td>
			@for ($q=0; $q < sizeof($data['mtx']["month"]); $q++)
				<td style="background-color: #dce6f1;">{{$data['mtx']["case3"]["dnPlanValue"][$sg][$q]}}</td>
			@endfor
			<td style="background-color: #143052; color: #FFFFFF; font-weight: bold;">{{$data['mtx']["case3"]["dnTotalPlanValue"][$sg]}}</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td style="background-color: #c3d8ef;">BKGS {{$data['cYear']}}</td>
			@for ($q=0; $q < sizeof($data['mtx']["month"]); $q++)
				<td style="background-color: #c3d8ef;">{{$data['mtx']["case3"]["dnValue"][$sg][$q]}}</td>
			@endfor
			<td style="background-color: #143052; color: #FFFFFF; font-weight: bold;">{{$data['mtx']["case3"]["dnTotalValue"][$sg]}}</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td style="background-color: #dce6f1;">Var Abs</td>
			@for ($q=0; $q < sizeof($data['mtx']["month"]); $q++)
				<td style="background-color: #dce6f1;">{{$data['mtx']["case3"]["dnVarAbs"][$sg][$q]}}</td>
			@endfor
			<td style="background-color: #143052; color: #FFFFFF; font-weight: bold;">{{$data['mtx']["case3"]["dnTotalVarAbs"][$sg]}}</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td style="background-color: #c3d8ef;">Var %</td>
			@for ($q=0; $q < sizeof($data['mtx']["month"]); $q++)
				<td style="background-color: #c3d8ef;">{{$data['mtx']["case3"]["dnVarPrc"][$sg][$q]/100}}</td>
			@endfor
			<td style="background-color: #0f243e; color: #FFFFFF; font-weight: bold;">{{$data['mtx']["case3"]["dnTotalVarPrc"][$sg]/100}}</td>
		</tr>

		<tr><td>&nbsp;</td></tr>

	@endfor

	<tr>
		<th colspan="{{sizeof($data['mtx']['month'])+3}}">
			Total
		</th>
	</tr>

	<tr><td>&nbsp;</td></tr>

	@for ($t=0; $t < sizeof($data['mtx']["tier"]); $t++)
		<tr>
			<td>
				@if ($data['mtx']["tier"][$t] == "TOTH")
					OTH
				@else
					{{$data['mtx']["tier"][$t]}}
				@endif
			</td>
			<td style="background-color: #a6a6a6;"></td>
				@for ($q=0; $q < sizeof($data['mtx']["month"]); $q++)
					<td style="background-color: #a6a6a6; font-weight: bold;">{{$data['mtx']["month"][$q]}}</td>
				@endfor
			<td style="background-color: #0f243e; color: #FFFFFF; font-weight: bold;">Total</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td style="background-color: #dce6f1;">Target {{$data['cYear']}}</td>
			@for ($q=0; $q < sizeof($data['mtx']["month"]); $q++)
				<td style="background-color: #dce6f1;">{{$data['mtx']["total"]["case3"]["planValues"][$t][$q]}}</td>
			@endfor
			<td style="background-color: #143052; color: #FFFFFF; font-weight: bold;">{{$data['mtx']["total"]["case3"]["totalPlanValueTier"][$t]}}</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td style="background-color: #c3d8ef;">BKGS {{$data['cYear']}}</td>
			@for ($q=0; $q < sizeof($data['mtx']["month"]); $q++)
				<td style="background-color: #c3d8ef;">{{$data['mtx']["total"]["case3"]["values"][$t][$q]}}</td>
			@endfor
			<td style="background-color: #143052; color: #FFFFFF; font-weight: bold;">{{$data['mtx']["total"]["case3"]["totalValueTier"][$t]}}</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td style="background-color: #dce6f1;">Var Abs</td>
			@for ($q=0; $q < sizeof($data['mtx']["month"]); $q++)
				<td style="background-color: #dce6f1;">{{$data['mtx']["total"]["case3"]["varAbs"][$t][$q]}}</td>
			@endfor
			<td style="background-color: #143052; color: #FFFFFF; font-weight: bold;">{{$data['mtx']["total"]["case3"]["totalVarAbs"][$t]}}</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td style="background-color: #c3d8ef;">Var %</td>
			@for ($q=0; $q < sizeof($data['mtx']["month"]); $q++)
				<td style="background-color: #c3d8ef;">{{$data['mtx']["total"]["case3"]["varPrc"][$t][$q]/100}}</td>
			@endfor
			<td style="background-color: #0f243e; color: #FFFFFF; font-weight: bold;">{{$data['mtx']["total"]["case3"]["totalVarPrc"][$t]/100}}</td>
		</tr>

		<tr><td>&nbsp;</td></tr>

	@endfor

	<tr>
		<td>TT</td>
		<td style="background-color: #a6a6a6;"></td>
		@for ($q=0; $q < sizeof($data['mtx']["month"]); $q++)
			<td style="background-color: #a6a6a6; font-weight: bold;">{{$data['mtx']["month"][$q]}}</td>
		@endfor
		<td  style="background-color: #0f243e; color: #FFFFFF; font-weight: bold;" >Total</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td style="background-color: #dce6f1;">Target {{$data['cYear']}}</td>
		@for ($q=0; $q < sizeof($data['mtx']["month"]); $q++)
			<td style="background-color: #dce6f1;">{{$data['mtx']["total"]["case3"]["dnPlanValue"][$q]}}</td>
		@endfor
		<td style="background-color: #143052; color: #FFFFFF; font-weight: bold;" >{{$data['mtx']["total"]["case3"]["dnTotalPlanValue"]}}</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td style="background-color: #c3d8ef;">BKGS {{$data['cYear']}}</td>
		@for ($q=0; $q < sizeof($data['mtx']["month"]); $q++)
			<td style="background-color: #c3d8ef;">{{$data['mtx']["total"]["case3"]["dnValue"][$q]}}</td>
		@endfor
		<td style="background-color: #143052; color: #FFFFFF; font-weight: bold;">{{$data['mtx']["total"]["case3"]["dnTotalValue"]}}</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td style="background-color: #dce6f1;">Var Abs</td>
		@for ($q=0; $q < sizeof($data['mtx']["month"]); $q++)
			<td style="background-color: #dce6f1;">{{$data['mtx']["total"]["case3"]["dnVarAbs"][$q]}}</td>
		@endfor
		<td style="background-color: #143052; color: #FFFFFF; font-weight: bold;" >{{$data['mtx']["total"]["case3"]["dnTotalVarAbs"]}}</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td style="background-color: #c3d8ef;">Var %</td>
		@for ($q=0; $q < sizeof($data['mtx']["month"]); $q++)
			<td style="background-color: #c3d8ef;">{{$data['mtx']["total"]["case3"]["dnVarPrc"][$q]/100}}</td>
		@endfor
		<td style="background-color: #0f243e; color: #FFFFFF; font-weight: bold;" >{{$data['mtx']["total"]["case3"]["dnTotalVarPrc"]/100}}</td>
	</tr>

</table>