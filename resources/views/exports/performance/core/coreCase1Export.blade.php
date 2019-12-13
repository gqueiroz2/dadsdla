<table>
	<tr>
		<th colspan="{{sizeof($data['mtx']['quarters'])+3}}">
			{{$data['mtx']['region']}} - Core {{$data['mtx']['year']}} ({{$data['mtx']['currency']}}/{{$data['mtx']['valueView']}}) - BKGS
		</th>
	</tr>

	<tr><td>&nbsp;</td></tr>

	@for($sg=0; $sg < sizeof($data['mtx']["case1"]["value"]); $sg++)

		<tr>
			<th colspan="{{sizeof($data['mtx']['quarters'])+3}}">
				{{$data['mtx']['salesGroup'][$sg]['name']}}
			</th>
		</tr>

		<tr><td>&nbsp;</td></tr>

		@for($t=0; $t < sizeof($data['mtx']["case1"]["value"][$sg]); $t++)
			<tr>
				<td>
					@if($data['mtx']["tier"][$t] == "TOTH") 
	    				OTH
	    			@else
						{{$data['mtx']["tier"][$t]}}
	    			@endif
    			</td>
    			<td style="background-color: #a6a6a6;">&nbsp;</td>
    			@for($q=0; $q < sizeof($data['mtx']["quarters"]); $q++)
    				<td style="background-color: #a6a6a6; font-weight: bold;">
    					{{$data['mtx']["quarters"][$q]}}
    				</td>
    			@endfor
    			<td style="background-color: #0f243e; color: #FFFFFF; font-weight: bold;">Total</td>
			</tr>
			<tr>
			 	<td>&nbsp;</td>
    			<td style="background-color: #dce6f1;">
    				Target {{$data['cYear']}}
    			</td>
    			@for ($q=0; $q < sizeof($data['mtx']["quarters"]); $q++)
    				<td style="background-color: #dce6f1;">
    				 	{{$data['mtx']["case1"]["planValue"][$sg][$t][$q]}}
    				</td>
    			@endfor
    			<td style="background-color: #143052; color: #FFFFFF; font-weight: bold;">
    				{{$data['mtx']["case1"]["totalPlanValueTier"][$sg][$t]}}
    			</td>
    		 </tr>
    		 <tr>
    		 	<td>&nbsp;</td>
    			<td style="background-color: #c3d8ef;">BKGS {{$data['cYear']}} </td>
    			@for ($q=0; $q < sizeof($data['mtx']["quarters"]); $q++)
    				<td style="background-color: #c3d8ef;">
    					{{$data['mtx']["case1"]["value"][$sg][$t][$q]}}
    				</td>
    			@endfor
    			<td style="background-color: #143052; color: #FFFFFF; font-weight: bold;">
    			 	{{$data['mtx']["case1"]["totalValueTier"][$sg][$t]}}
    			</td>
    		 </tr>
    		 <tr>
    		 	<td>&nbsp;</td>
    			<td style="background-color: #dce6f1;">Var Abs</td>
    			@for ($q=0; $q < sizeof($data['mtx']["quarters"]); $q++)
    				<td style="background-color: #dce6f1;">
    				 	{{$data['mtx']["case1"]["varAbs"][$sg][$t][$q]}}
    				</td>
    			@endfor
    			<td style="background-color: #143052; color: #FFFFFF; font-weight: bold;">
    			 	{{$data['mtx']["case1"]["totalVarAbs"][$sg][$t]}}
    			</td>
    		 </tr>
    		 <tr>
    		 	<td>&nbsp;</td>
    			<td style="background-color: #c3d8ef;">Var %</td>
    			@for ($q=0; $q < sizeof($data['mtx']["quarters"]); $q++)
    				<td style="background-color: #c3d8ef;">
    				 	{{$data['mtx']["case1"]["varPrc"][$sg][$t][$q]/100}}
    				</td>
    			@endfor
    			<td style="background-color: #0f243e; color: #FFFFFF; font-weight: bold;">
    				{{$data['mtx']["case1"]["totalVarPrc"][$sg][$t]/100}}
    			</td>
    		 </tr>

    		 <tr><td>&nbsp;</td></tr>
		@endfor

		<tr>
			<td>TT</td>
			<td style="background-color: #a6a6a6;">&nbsp;</td>
			@for ($q=0; $q < sizeof($data['mtx']["quarters"]); $q++)
				<td style="background-color: #a6a6a6; font-weight: bold;">
					{{$data['mtx']["quarters"][$q]}}
				</td>
			@endfor
			<td style="background-color: #0f243e; color: #FFFFFF; font-weight: bold;">Total</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td style="background-color: #dce6f1">
				Target {{$data['cYear']}}
			</td>
			@for ($q=0; $q < sizeof($data['mtx']["quarters"]); $q++)
				<td style="background-color: #dce6f1">
					{{$data['mtx']["case1"]["totalPlanSG"][$sg][$q]}}
				</td>
			@endfor
			<td style="background-color: #143052; color: #FFFFFF; font-weight: bold;">
				{{$data['mtx']["case1"]["totalPlanTotalSG"][$sg]}}
			</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
    		<td style="background-color: #c3d8ef">
    			BKGS {{$data['cYear']}}
    		</td>
			@for ($q=0; $q < sizeof($data['mtx']["quarters"]); $q++)
				<td style="background-color: #c3d8ef">
					{{$data['mtx']["case1"]["totalSG"][$sg][$q]}}
				</td>
			@endfor
			<td style="background-color: #143052; color: #FFFFFF; font-weight: bold;">
				{{$data['mtx']["case1"]["totalTotalSG"][$sg]}}
			</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
    		<td style="background-color: #dce6f1">Var Abs</td>
			@for ($q=0; $q < sizeof($data['mtx']["quarters"]); $q++)
				<td style="background-color: #dce6f1">
					{{$data['mtx']["case1"]["totalSGVarAbs"][$sg][$q]}}
				</td>
			@endfor
			<td style="background-color: #143052; color: #FFFFFF; font-weight: bold;">
				{{$data['mtx']["case1"]["totalTotalSGVarAbs"][$sg]}}
			</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
    		<td style="background-color: #c3d8ef">Var %</td>
			@for ($q=0; $q < sizeof($data['mtx']["quarters"]); $q++)
				<td style="background-color: #c3d8ef">
					{{$data['mtx']["case1"]["totalSGVarPrc"][$sg][$q]/100}}
				</td>
			@endfor
			<td style="background-color: #0f243e; color: #FFFFFF; font-weight: bold;">
				{{$data['mtx']["case1"]["totalTotalSGVarPrc"][$sg]/100}}
			</td>
		</tr>

		<tr><td>&nbsp;</td></tr>
	@endfor

	<tr>
		<th colspan="{{sizeof($data['mtx']['quarters'])+3}}">
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
			@for ($q=0; $q < sizeof($data['mtx']["quarters"]); $q++)
				<td style="background-color: #a6a6a6; font-weight: bold;">{{$data['mtx']["quarters"][$q]}}</td>
			@endfor
			<td style="background-color: #0f243e; color: #FFFFFF; font-weight: bold;">Total</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td style="background-color: #dce6f1;">Target {{$data['cYear']}}</td>
			@for ($q=0; $q < sizeof($data['mtx']["quarters"]); $q++)
				<td style="background-color: #dce6f1;">{{$data['mtx']["total"]["case1"]["planValues"][$t][$q]}}</td>
			@endfor
			<td style="background-color: #143052; color: #FFFFFF; font-weight: bold;">{{$data['mtx']["total"]["case1"]["totalPlanValueTier"][$t]}}</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td style="background-color: #c3d8ef;">BKGS {{$data['cYear']}} </td>
			@for ($q=0; $q < sizeof($data['mtx']["quarters"]); $q++)
				<td style="background-color: #c3d8ef;">{{$data['mtx']["total"]["case1"]["values"][$t][$q]}}</td>
			@endfor
			<td style="background-color: #143052; color: #FFFFFF; font-weight: bold;">{{$data['mtx']["total"]["case1"]["totalValueTier"][$t]}}</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td style="background-color: #dce6f1;">Var Abs</td>
			@for ($q=0; $q < sizeof($data['mtx']["quarters"]); $q++)
				<td style="background-color: #dce6f1;">{{$data['mtx']["total"]["case1"]["varAbs"][$t][$q]}}</td>
			@endfor
			<td style="background-color: #143052; color: #FFFFFF; font-weight: bold;">{{$data['mtx']["total"]["case1"]["totalVarAbs"][$t]}}</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td style="background-color: #c3d8ef;">Var %</td>
			@for ($q=0; $q < sizeof($data['mtx']["quarters"]); $q++)
				<td style="background-color: #c3d8ef;">{{$data['mtx']["total"]["case1"]["varPrc"][$t][$q]/100}}</td>
			@endfor
			<td style="background-color: #0f243e; color: #FFFFFF; font-weight: bold;">{{$data['mtx']["total"]["case1"]["totalVarPrc"][$t]/100}}</td>
		</tr>

		<tr><td>&nbsp;</td></tr>
		
	@endfor
	
	<tr>
		<td>TT</td>
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
			<td style="background-color: #dce6f1;">{{$data['mtx']["total"]["case1"]["dnPlanValue"][$q]}}</td>
		@endfor
		<td style="background-color: #143052; color: #FFFFFF; font-weight: bold;">{{$data['mtx']["total"]["case1"]["dnTotalPlanValue"]}}</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td style="background-color: #c3d8ef;">BKGS {{$data['cYear']}}</td>
		@for ($q=0; $q < sizeof($data['mtx']["quarters"]); $q++)
			<td style="background-color: #c3d8ef;">{{$data['mtx']["total"]["case1"]["dnValue"][$q]}}</td>
		@endfor
		<td style="background-color: #143052; color: #FFFFFF; font-weight: bold;">{{$data['mtx']["total"]["case1"]["dnTotalValue"]}}</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td style="background-color: #dce6f1;">Var Abs</td>
		@for ($q=0; $q < sizeof($data['mtx']["quarters"]); $q++)
			<td style="background-color: #dce6f1;">{{$data['mtx']["total"]["case1"]["dnVarAbs"][$q]}}</td>
		@endfor
		<td style="background-color: #143052; color: #FFFFFF; font-weight: bold;">{{$data['mtx']["total"]["case1"]["dnTotalVarAbs"]}}</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td style="background-color: #c3d8ef;">Var %</td>
		@for ($q=0; $q < sizeof($data['mtx']["quarters"]); $q++)
			<td style="background-color: #c3d8ef;">{{$data['mtx']["total"]["case1"]["dnVarPrc"][$q]/100}}</td>
		@endfor
		<td style="background-color: #0f243e; color: #FFFFFF; font-weight: bold;">{{$data['mtx']["total"]["case1"]["dnTotalVarPrc"]/100}}</td>
	</tr>
</table>