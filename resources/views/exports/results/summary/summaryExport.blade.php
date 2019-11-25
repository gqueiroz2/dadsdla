<table>
	<tr>
		<th style="background-color: #0f243e;border:1px solid #FFFFFF" colspan="9">
			<span>
				{{$headInfo['region']}} - {{$tab}} Summary : BKGS - {{$headInfo['year']}} ({{$headInfo['currency'][0]['name']}}/{{$headInfo['value']}})
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
				<td style="border:1px solid #FFFFFF">{{$data[$m]['month']}}</td>
				<td style="border:1px solid #FFFFFF">
					{{$data[$m]['sales']}}
				</td>
				<td style="border:1px solid #FFFFFF">
					{{$data[$m]['actual']}}
				</td>
				<td style="border:1px solid #FFFFFF">
					{{$data[$m]['target']}}
				</td>
				<td style="border:1px solid #FFFFFF">
					{{$data[$m]['corporate']}}
				</td>
				<td style="border:1px solid #FFFFFF">
					{{$data[$m]['pYear']}}
				</td>
				<td style="border:1px solid #FFFFFF">
					{{$data[$m]['salesOverTarget']/100}} 
				</td>
				<td style="border:1px solid #FFFFFF">
					{{$data[$m]['salesOverCorporate']/100}} 
				</td>
				<td style="border:1px solid #FFFFFF">
					{{$data[$m]['salesYoY']/100}} 
				</td>
			</tr>
		@else
			<tr>
				<td>{{$data[$m]['month']}}</td>
				<td>
					{{$data[$m]['sales']}}
				</td>
				<td>
					{{$data[$m]['actual']}}
				</td>
				<td>
					{{$data[$m]['target']}}
				</td>
				<td>
					{{$data[$m]['corporate']}}
				</td>
				<td>
					{{$data[$m]['pYear']}}
				</td>
				<td>
					{{$data[$m]['salesOverTarget']/100}}
				</td>
				<td>
					{{$data[$m]['salesOverCorporate']/100}}
				</td>
				<td>
					{{$data[$m]['salesYoY']/100}}
				</td>
			</tr>
		@endif
	@endfor
</table>