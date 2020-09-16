<table >
	<tr>
		<td style="background-color: #002060;font-weight: bold; font-size: 12px; color: #FFFFFF; "> AGÊNCIA </td>
		<td style="background-color: #d9e1f2; text-align: center; font-weight: bold; font-size: 12px;"> {{ strtoupper($data['agencyGroupName']) }}  </td>
	</tr>
	<tr>
		<td style="background-color: #01528c; font-weight: bold; font-size: 12px; color: #FFFFFF;"> TABELA </td>
		<td style="background-color: #d9e1f2; font-weight: bold; font-size: 12px; text-align: center;"> {{ $data['cYear'] }} </td>
	</tr>					
</table>

<table>
	<tr >
		<td style="background-color: #01528c; font-weight: bold; font-size: 12px; color: #FFFFFF;"> INVESTIMENTO </td>
		<td style="background-color: #d9e1f2; text-align: center; font-weight: bold; font-size: 12px;"> {{ number_format($data['bvAnalisis']['currentVal']) }} </td>
	</tr>
	<tr>
		<td style="background-color: #01528c; font-weight: bold; font-size: 12px; color: #FFFFFF;"> FAIXA ATUAL </td>
		<td style="background-color: #d9e1f2; text-align: center; font-weight: bold; font-size: 12px;">
			@if($data['bvAnalisis']['currentPercentage'] <= 0)
				-
			@else
				{{ number_format( ($data['bvAnalisis']['currentPercentage'])*100 ) }}% 
			@endif
		</td>
	</tr>
	<tr>
		<td style="background-color: #01528c; font-weight: bold; font-size: 12px; color: #FFFFFF;"> REMUNERAÇÃO ATUAL </td>
		<td style="background-color: #d9e1f2; text-align: center; font-weight: bold; font-size: 12px;"> 
			@if($data['bvAnalisis']['currentBV'])
				{{ number_format($data['bvAnalisis']['currentBV']) }} 
			@else
				-
			@endif
		</td>
	</tr>					
</table>

<table>
	<tr>
		<td style="background-color: #01528c; font-weight: bold; font-size: 12px; color: #FFFFFF;"> DIF. PRÓXIMA FAIXA </td>
		<td style="background-color: #d9e1f2; text-align: center; font-weight: bold; font-size: 12px;"> 
			@if($data['bvAnalisis']['nextBandDiff'])
				{{ number_format($data['bvAnalisis']['nextBandDiff']) }} 
			@else
				-
			@endif							
		</td>
	</tr>
	<tr>
		<td style="background-color: #01528c; font-weight: bold; font-size: 12px; color: #FFFFFF;"> PRÓXIMA FAIXA </td>
		<td style="background-color: #d9e1f2; text-align: center; font-weight: bold; font-size: 12px;"> 
			@if($data['bvAnalisis']['nextBandPercentage'])
				{{ number_format( ($data['bvAnalisis']['nextBandPercentage']) ) }}% 
			@else
				-
			@endif							
		</td>
	</tr>
	<tr>
		<td style="background-color: #01528c; font-weight: bold; font-size: 12px; color: #FFFFFF;"> REM. PRÓXIMA FAIXA </td>
		<td style="background-color: #d9e1f2; text-align: center; font-weight: bold; font-size: 12px;"> 
			@if($data['bvAnalisis']['nextBandBV'])
				{{ number_format( ($data['bvAnalisis']['nextBandBV']) ) }} 
			@else
				-
			@endif							
		</td>
	</tr>				
</table>

<table>
	<tr>
		<td style="background-color: #01528c; font-weight: bold; font-size: 12px; color: #FFFFFF;"> DIFERENÇA TETO </td>
		<td style="background-color: #d9e1f2; text-align: center; font-weight: bold; font-size: 12px;"> 
			@if($data['bvAnalisis']['maxBandCurrentVal'])
				{{ number_format( ($data['bvAnalisis']['maxBandDiff']) ) }}
			@else
				-
			@endif 
		</td>
	</tr>
	<tr>
		<td style="background-color: #01528c; font-weight: bold; font-size: 12px; color: #FFFFFF;"> TETO FAIXA </td>
		<td style="background-color: #d9e1f2; text-align: center; font-weight: bold; font-size: 12px;"> 
			@if($data['bvAnalisis']['maxBandPercentage'])
				{{ number_format( ($data['bvAnalisis']['maxBandPercentage']) ) }}% 
			@else
				-
			@endif	
		</td>
	</tr>
	<tr>
		<td style="background-color: #01528c; font-weight: bold; font-size: 12px; color: #FFFFFF;"> REMUNERAÇÃO TETO </td>
		<td style="background-color: #d9e1f2; text-align: center; font-weight: bold; font-size: 12px;"> 
			@if($data['bvAnalisis']['maxBandBV'])
				{{ number_format( ($data['bvAnalisis']['maxBandBV']) ) }} 
			@else
				-
			@endif
		</td>
	</tr>					
</table>

<table>
	<tr>
		<td style="background-color: #01528c; font-weight: bold; font-size: 12px; color: #FFFFFF; text-align: center;">
			 TABELA {{ $data['yearsBand'][0] }}
		</td>
	</tr>
	<tr>
		<td style="background-color: #01528c; font-weight: bold; font-size: 12px; color: #FFFFFF; text-align: center;">DE</td>
		<td style="background-color: #01528c; font-weight: bold; font-size: 12px; color: #FFFFFF; text-align: center;">ATÉ</td>
		<td style="background-color: #01528c; font-weight: bold; font-size: 12px; color: #FFFFFF; text-align: center;">%</td>
	</tr>

	@if($data['bands'][0])
		@for($i=0;$i< sizeof($data['bands'][0]) ;$i++)
			<tr>
				<td style="background-color: #d9e1f2; font-weight: bold; font-size: 12px; text-align: center;">{{ number_format( $data['bands'][0][$i]['fromValue'] ) }}</td>
				@if($data['bands'][0][$i]['toValue'] == -1)
					<td style="background-color: #d9e1f2; font-weight: bold; font-size: 12px; text-align: center;"></td>
				@else
					<td style="background-color: #d9e1f2; font-weight: bold; font-size: 12px; text-align: center;">{{ number_format( $data['bands'][0][$i]['toValue'] ) }}</td>
				@endif
				<td style="background-color: #d9e1f2; font-weight: bold; font-size: 12px; text-align: center;">{{ number_format( ($data['bands'][0][$i]['percentage'])*100 ) }}%</td>
			</tr>
		@endfor
	@else
		<tr>
			<td style="background-color: #d9e1f2; font-weight: bold; font-size: 12px; text-align: center;"> 
				<center>
					Não existe informação de faixas para este ano.
				</center>
			</td>
		</tr>
	@endif
</table>

@if($data['forecast'])
	<table>
		<tr>
			<td style="font-weight: bold; font-size: 12px; text-align: center;"> PREVISÃO </td>
		</tr>
		<tr>
			<td style="text-align: center; background-color: #01528c; font-weight: bold; font-size: 12px; color: #FFFFFF;"> CLIENTE </td>
			@for($m = $data['startMonthFcst']; $m < sizeof($data['monthsMidName']);$m++)
				<td style="text-align: center; background-color: #01528c; font-weight: bold; font-size: 12px; color: #FFFFFF;"> {{ strtoupper($data['monthsMidName'][$m]) }} </td>
			@endfor
			<td style="text-align: center; background-color: #01528c; font-weight: bold; font-size: 12px; color: #FFFFFF;"> TOTAL </td>
		</tr>
		@for($f = 0; $f < sizeof($data['forecast']); $f++)
			<tr>
				@if(strtoupper($data['forecast'][$f]['client']) == "TOTAL")
					<td style="text-align: center; background-color: #0f243e; font-weight: bold; font-size: 12px; color: #FFFFFF;"> {{ strtoupper($data['forecast'][$f]['client']) }} </td>
					@for($m = $data['startMonthFcst']; $m < sizeof($data['monthsMidName']);$m++)
						<td style="text-align: center; background-color: #0f243e; font-weight: bold; font-size: 12px; color: #FFFFFF;"> {{ number_format($data['forecast'][$f]['split'][$m]) }} </td>
					@endfor
					<td style="text-align: center; background-color: #0f243e; font-weight: bold; font-size: 12px; color: #FFFFFF;"> {{ number_format($data['forecast'][$f]['revenue']) }} </td>
				@else
					<td style="text-align: center; background-color: #d9e1f2; font-weight: bold; font-size: 12px; "> {{ strtoupper($data['forecast'][$f]['client']) }} </td>
					@for($m = $data['startMonthFcst']; $m < sizeof($data['monthsMidName']);$m++)
						<td style="text-align: center; background-color: #d9e1f2; font-weight: bold; font-size: 12px; "> {{ number_format($data['forecast'][$f]['split'][$m]) }} </td>
					@endfor	
					<td style="text-align: center; background-color: #d9e1f2; font-weight: bold; font-size: 12px;"> {{ number_format($data['forecast'][$f]['revenue']) }} </td>
				@endif							
			</tr>
		@endfor						
	</table>
@else
	<table>
		<tr>
			<td style="text-align: center; background-color: #01528c; font-weight: bold; font-size: 12px; color: #FFFFFF;"><center> SEM PREVISÃO PARA AGÊNCIA </center></td>
		</tr>
	</table>
@endif

<table>
	<tr>
		<td style="background-color: #01528c; text-align: center; font-weight: bold; font-size: 12px; color: #FFFFFF;"> CANAIS </td>
		@for($tc =0;$tc < sizeof($data['mountBV']['byBrand']);$tc++)
			@if(number_format( $data['mountBV']['byBrand'][$tc]['value']  >= 1))
				@if($data['mountBV']['byBrand'][$tc]['brand'] == "TOTAL")						
					<td style="background-color: #01528c; text-align: center; font-weight: bold; font-size: 12px; color: #FFFFFF;">{{ $data['mountBV']['byBrand'][$tc]['brand'] }}</td>
				@elseif($data['mountBV']['byBrand'][$tc]['brand'] == "DC")
					<td style="background-color: #0070c0; text-align: center; font-weight: bold; font-size: 12px; color: #FFFFFF;">{{ $data['mountBV']['byBrand'][$tc]['brand'] }}</td>
				@elseif($data['mountBV']['byBrand'][$tc]['brand'] == "HH")
					<td style="background-color: #ff6600; text-align: center; font-weight: bold; font-size: 12px; color: #FFFFFF;">{{ $data['mountBV']['byBrand'][$tc]['brand'] }}</td>
				@elseif($data['mountBV']['byBrand'][$tc]['brand'] == "DK")
					<td style="background-color: #ffff00; text-align: center; font-weight: bold; font-size: 12px; color: #FFFFFF;">{{ $data['mountBV']['byBrand'][$tc]['brand'] }}</td>
				@elseif($data['mountBV']['byBrand'][$tc]['brand'] == "AP")
					<td style="background-color:  #009933; text-align: center; font-weight: bold; font-size: 12px; color: #FFFFFF;">{{ $data['mountBV']['byBrand'][$tc]['brand'] }}</td>
				@elseif($data['mountBV']['byBrand'][$tc]['brand'] == "TLC")
					<td style="background-color:  #ff0000; text-align: center; font-weight: bold; font-size: 12px; color: #FFFFFF;">{{ $data['mountBV']['byBrand'][$tc]['brand'] }}</td>
				@elseif($data['mountBV']['byBrand'][$tc]['brand'] == "ID")
					<td style="background-color: #000000; text-align: center; font-weight: bold; font-size: 12px; color: #FFFFFF;">{{ $data['mountBV']['byBrand'][$tc]['brand'] }}</td>
				@elseif($data['mountBV']['byBrand'][$tc]['brand'] == "DT")
					<td style="background-color: #002060; text-align: center; font-weight: bold; font-size: 12px; color: #FFFFFF;">{{ $data['mountBV']['byBrand'][$tc]['brand'] }}</td>
				@elseif($data['mountBV']['byBrand'][$tc]['brand'] == "FN")
					<td style="background-color: #ff0000; text-align: center; font-weight: bold; font-size: 12px; color: #FFFFFF;">{{ $data['mountBV']['byBrand'][$tc]['brand'] }}</td>
				@elseif($data['mountBV']['byBrand'][$tc]['brand'] == "ONL")
					<td style="background-color: #6600ff; text-align: center; font-weight: bold; font-size: 12px; color: #FFFFFF;">{{ $data['mountBV']['byBrand'][$tc]['brand'] }}</td>
				@elseif($data['mountBV']['byBrand'][$tc]['brand'] == "VIX")
					<td style="background-color: #004b84; text-align: center; font-weight: bold; font-size: 12px; color: #FFFFFF;">{{ $data['mountBV']['byBrand'][$tc]['brand'] }}</td>
				@elseif($data['mountBV']['byBrand'][$tc]['brand'] == "OTH")
					<td style="background-color: #808080; text-align: center; font-weight: bold; font-size: 12px; color: #FFFFFF;">{{ $data['mountBV']['byBrand'][$tc]['brand'] }}</td>
				@elseif($data['mountBV']['byBrand'][$tc]['brand'] == "HGTV")
					<td style="background-color: #88cc00; text-align: center; font-weight: bold; font-size: 12px; color: #FFFFFF;">{{ $data['mountBV']['byBrand'][$tc]['brand'] }}</td>
				@elseif($data['mountBV']['byBrand'][$tc]['brand'] == "GC")
					<td style="background-color:#01528c; text-align: center; font-weight: bold; font-size: 12px; color: #FFFFFF;">{{ $data['mountBV']['byBrand'][$tc]['brand'] }}</td>
				@endif
			@endif
		@endfor						
	</tr>
	<tr>
		<td style="text-align: center;background-color: #d9e1f2; font-weight: bold; font-size: 12px;"> INVESTIMENTO </td>
		@for($tc =0;$tc < sizeof($data['mountBV']['byBrand']);$tc++)
			@if(number_format( $data['mountBV']['byBrand'][$tc]['value']  >= 1))							
				<td style="text-align: center;background-color: #d9e1f2; font-weight: bold; font-size: 12px;">{{ number_format( $data['mountBV']['byBrand'][$tc]['value'] ) }}</td>
			@endif
		@endfor
	</tr>
</table>

<table>
	<tr>
		<td style="background-color: #01528c; text-align: center; font-weight: bold; font-size: 12px; color: #FFFFFF;"> MÊS </td>
		@for($tc =0;$tc < sizeof($data['mountBV']['byMonth']);$tc++)							
			@if($data['mountBV']['byMonth'][$tc]['month'] == "TOTAL")
				<td style="background-color: #01528c; text-align: center; font-weight: bold; font-size: 12px; color: #FFFFFF;">{{ $data['mountBV']['byMonth'][$tc]['month'] }}</td>
			@else
				<td style="background-color: #01528c; text-align: center; font-weight: bold; font-size: 12px; color: #FFFFFF;">{{ strtoupper( $data['monthsMidName'][$data['mountBV']['byMonth'][$tc]['month'] - 1] ) }}</td>
			@endif
		@endfor						
	</tr>
	<tr>
		<td style="text-align: center;background-color: #d9e1f2; font-weight: bold; font-size: 12px;"> INVESTIMENTO </td>
		@for($tc =0;$tc < sizeof($data['mountBV']['byMonth']);$tc++)
			<td style="text-align: center;background-color: #d9e1f2; font-weight: bold; font-size: 12px;">{{ number_format( $data['mountBV']['byMonth'][$tc]['value'] ) }}</td>
		@endfor							
	</tr>
</table>
