<table>
	<tr>
		<th style="background-color: #0f243e;border:1px solid #FFFFFF" colspan="9">
			<span>
				{{$headInfo['region']}} - {{$tab}} Summary : BKGS - {{$headInfo['year']}} ({{$headInfo['currency'][0]['name']}}/{{strtoupper($headInfo['value'])}})
			</span>
		</th>
	</tr>

	<tr>
		<th style="background-color: #0f243e; border:1px solid #FFFFFF">MONTH</th>
		<th style="background-color: #0070c0; border:1px solid #FFFFFF">
			BKGS {{$headInfo['year']}}
		</th>
		<th style="background-color: #0070c0; border:1px solid #FFFFFF">
			SAP {{$headInfo['year']}}
		</th>
		<th style="background-color: #0f243e; border:1px solid #FFFFFF">
			TARGET {{$headInfo['year']}}
		</th>
		<th style="background-color: #0f243e; border:1px solid #FFFFFF">
			CORP. FCST {{$headInfo['year']}}
		</th>
		<th style="background-color: #0f243e; border:1px solid #FFFFFF">
			BKGS {{$headInfo['year']-1}}
		</th>
		<th style="background-color: #757171; border:1px solid #FFFFFF">
			BKGS/TARGET
		</th>
		<th style="background-color: #757171; border:1px solid #FFFFFF">
			BKGS/CORP. FCST
		</th>
		<th style="background-color: #757171; border:1px solid #FFFFFF">
			BKGS ({{$headInfo['year']}}/{{$headInfo['year']-1}})
		</th>
	</tr>

	@for($m = 0; $m < sizeof($data); $m++)
		@if($m == sizeof($data)-1)
			<tr>
				<td style="border:1px solid #FFFFFF; background-color: #0f243e; color: #FFFFFF; font-weight: bold;">{{$data[$m]['month']}}</td>
				<td style="border:1px solid #FFFFFF; background-color: #0f243e; color: #FFFFFF; font-weight: bold;">
					{{$data[$m]['sales']}}
				</td>
				<td style="border:1px solid #FFFFFF; background-color: #0f243e; color: #FFFFFF; font-weight: bold;">
					{{$data[$m]['actual']}}
				</td>
				<td style="border:1px solid #FFFFFF; background-color: #0f243e; color: #FFFFFF; font-weight: bold;">
					{{$data[$m]['target']}}
				</td>
				<td style="border:1px solid #FFFFFF; background-color: #0f243e; color: #FFFFFF; font-weight: bold;">
					{{$data[$m]['corporate']}}
				</td>
				<td style="border:1px solid #FFFFFF; background-color: #0f243e; color: #FFFFFF; font-weight: bold;">
					{{$data[$m]['pYear']}}
				</td>
				<td style="border:1px solid #FFFFFF; background-color: #0f243e; color: #FFFFFF; font-weight: bold;">
					{{round($data[$m]['salesOverTarget'])/100}} 
				</td>
				<td style="border:1px solid #FFFFFF; background-color: #0f243e; color: #FFFFFF; font-weight: bold;">
					{{round($data[$m]['salesOverCorporate'])/100}}
				</td>
				<td style="border:1px solid #FFFFFF; background-color: #0f243e; color: #FFFFFF; font-weight: bold;">
					{{round($data[$m]['salesYoY'])/100}} 
				</td>
			</tr>
		@else
			<tr>
				@if($m%2 == 0)
					<td style="background-color: #e7eff9">{{$data[$m]['month']}}</td>
					<td style="background-color: #e7eff9">
						{{$data[$m]['sales']}}
					</td>
					<td style="background-color: #e7eff9">
						{{$data[$m]['actual']}}
					</td>
					<td style="background-color: #e7eff9">
						{{$data[$m]['target']}}
					</td>
					<td style="background-color: #e7eff9">
						{{$data[$m]['corporate']}}
					</td>
					<td style="background-color: #e7eff9">
						{{$data[$m]['pYear']}}
					</td>
					<td style="background-color: #e7eff9">
						{{round($data[$m]['salesOverTarget'])/100}}
					</td>
					<td style="background-color: #e7eff9">
						{{round($data[$m]['salesOverCorporate'])/100}}
					</td>
					<td style="background-color: #e7eff9">
						{{round($data[$m]['salesYoY'])/100}}
					</td>
				@else
					<td style="background-color: #f9fbfd;">{{$data[$m]['month']}}</td>
					<td style="background-color: #f9fbfd;">
						{{$data[$m]['sales']}}
					</td>
					<td style="background-color: #f9fbfd;">
						{{$data[$m]['actual']}}
					</td>
					<td style="background-color: #f9fbfd;">
						{{$data[$m]['target']}}
					</td>
					<td style="background-color: #f9fbfd;">
						{{$data[$m]['corporate']}}
					</td>
					<td style="background-color: #f9fbfd;">
						{{$data[$m]['pYear']}}
					</td>
					<td style="background-color: #f9fbfd;">
						{{round($data[$m]['salesOverTarget'])/100}}
					</td>
					<td style="background-color: #f9fbfd;">
						{{round($data[$m]['salesOverCorporate'])/100}}
					</td>
					<td style="background-color: #f9fbfd;">
						{{round($data[$m]['salesYoY'])/100}}
					</td>
				@endif
			</tr>
		@endif
	@endfor
</table>