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
					{{number_format($data[$m]['sales'], 0, ",", ".")}}
				</td>
				<td style="border:1px solid #FFFFFF">
					{{number_format($data[$m]['actual'], 0, ",", ".")}}
				</td>
				<td style="border:1px solid #FFFFFF">
					{{number_format($data[$m]['target'], 0, ",", ".")}}
				</td>
				<td style="border:1px solid #FFFFFF">
					{{number_format($data[$m]['corporate'], 0, ",", ".")}}
				</td>
				<td style="border:1px solid #FFFFFF">
					{{number_format($data[$m]['pYear'], 0, ",", ".")}}
				</td>
				<td style="border:1px solid #FFFFFF">
					{{number_format($data[$m]['salesOverTarget'], 0, ",", ".")}} %
				</td>
				<td style="border:1px solid #FFFFFF">
					{{number_format($data[$m]['salesOverCorporate'], 0, ",", ".")}} %
				</td>
				<td style="border:1px solid #FFFFFF">
					{{number_format($data[$m]['salesYoY'], 0, ",", ".")}} %
				</td>
			</tr>
		@else
			<tr>
				<td>{{$data[$m]['month']}}</td>
				<td>
					{{number_format($data[$m]['sales'], 0, ",", ".")}}
				</td>
				<td>
					{{number_format($data[$m]['actual'], 0, ",", ".")}}
				</td>
				<td>
					{{number_format($data[$m]['target'], 0, ",", ".")}}
				</td>
				<td>
					{{number_format($data[$m]['corporate'], 0, ",", ".")}}
				</td>
				<td>
					{{number_format($data[$m]['pYear'], 0, ",", ".")}}
				</td>
				<td>
					{{number_format($data[$m]['salesOverTarget'], 0, ",", ".")}} %
				</td>
				<td>
					{{number_format($data[$m]['salesOverCorporate'], 0, ",", ".")}} %
				</td>
				<td>
					{{number_format($data[$m]['salesYoY'], 0, ",", ".")}} %
				</td>
			</tr>
		@endif
	@endfor
</table>