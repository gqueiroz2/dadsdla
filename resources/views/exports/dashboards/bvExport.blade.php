<table >
	<tr>
		<td style=" background-color: #002060;font-weight: bold; color: #FFFFFF; "> AGÊNCIA </td>
		<td style="background-color: #d9e1f2; text-align: center; font-weight: bold;" colspan="2"> {{ strtoupper($data['agencyGroupName']) }}  </td>
		<td></td>
		<td style="background-color: #01528c; font-weight: bold; color: #FFFFFF;"> INVESTIMENTO </td>
		<td style="background-color: #d9e1f2; text-align: center; font-weight: bold;" colspan="2"> {{ number_format($data['bvAnalisis']['currentVal']) }} </td>
		<td></td>
		<td style="background-color: #01528c; font-weight: bold; color: #FFFFFF;"> DIF. PRÓXIMA FAIXA </td>
		<td style="background-color: #d9e1f2; text-align: center; font-weight: bold;" colspan="2"> 
			@if($data['bvAnalisis']['nextBandDiff'])
				{{ number_format($data['bvAnalisis']['nextBandDiff']) }} 
			@else
				-
			@endif							
		</td>
		<td></td>
		<td style="background-color: #01528c; font-weight: bold; color: #FFFFFF;"> DIFERENÇA TETO </td>
		<td style="background-color: #d9e1f2; text-align: center; font-weight: bold;" colspan="2"> 
			@if($data['bvAnalisis']['maxBandCurrentVal'])
				{{ number_format( ($data['bvAnalisis']['maxBandDiff']) ) }}
			@else
				-
			@endif 
		</td>
	</tr>
	<tr>
		<td style="background-color: #01528c; font-weight: bold; color: #FFFFFF;"> TABELA </td>
		<td style="background-color: #d9e1f2; font-weight: bold; text-align: center;" colspan="2"> {{ $data['cYear'] }} </td>
		<td></td>
		<td style="background-color: #01528c; font-weight: bold; color: #FFFFFF;"> FAIXA ATUAL </td>
		<td style="background-color: #d9e1f2; text-align: center; font-weight: bold;" colspan="2">
			@if($data['bvAnalisis']['currentPercentage'] <= 0)
				-
			@else
				{{ number_format( ($data['bvAnalisis']['currentPercentage'])*100 ) }}% 
			@endif
		</td>
		<td></td>
		<td style="background-color: #01528c; font-weight: bold; color: #FFFFFF;"> PRÓXIMA FAIXA </td>
		<td style="background-color: #d9e1f2; text-align: center; font-weight: bold;" colspan="2"> 
			@if($data['bvAnalisis']['nextBandPercentage'])
				{{ number_format( ($data['bvAnalisis']['nextBandPercentage']) ) }}% 
			@else
				-
			@endif							
		</td>
		<td></td>
		<td style="background-color: #01528c; font-weight: bold; color: #FFFFFF;"> TETO FAIXA </td>
		<td style="background-color: #d9e1f2; text-align: center; font-weight: bold;" colspan="2"> 
			@if($data['bvAnalisis']['maxBandPercentage'])
				{{ number_format( ($data['bvAnalisis']['maxBandPercentage']) ) }}% 
			@else
				-
			@endif	
		</td>
	</tr>

	<tr>
		<td colspan="4"></td>
		<td style="background-color: #01528c; font-weight: bold; color: #FFFFFF;"> REMUNERAÇÃO ATUAL </td>
		<td style="background-color: #d9e1f2; text-align: center; font-weight: bold;" colspan="2"> 
			@if($data['bvAnalisis']['currentBV'])
				{{ number_format($data['bvAnalisis']['currentBV']) }} 
			@else
				-
			@endif
		</td>
		<td></td>
		<td style="background-color: #01528c; font-weight: bold; color: #FFFFFF;"> REM. PRÓXIMA FAIXA </td>
		<td style="background-color: #d9e1f2; text-align: center; font-weight: bold;" colspan="2"> 
			@if($data['bvAnalisis']['nextBandBV'])
				{{ number_format( ($data['bvAnalisis']['nextBandBV']) ) }} 
			@else
				-
			@endif							
		</td>
		<td></td>
		<td style="background-color: #01528c; font-weight: bold; color: #FFFFFF;"> REMUNERAÇÃO TETO </td>
		<td style="background-color: #d9e1f2; text-align: center; font-weight: bold;" colspan="2"> 
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
		<td colspan='3'style="background-color: #01528c; font-weight: bold; color: #FFFFFF; text-align: center;">
			 TABELA {{ $data['yearsBand'][0] }}
		</td>
	</tr>
	<tr>
		<td style="background-color: #01528c; font-weight: bold; color: #FFFFFF; text-align: center;">DE</td>
		<td style="background-color: #01528c; font-weight: bold; color: #FFFFFF; text-align: center;">ATÉ</td>
		<td style="background-color: #01528c; font-weight: bold; color: #FFFFFF; text-align: center;">%</td>
	</tr>

	@if($data['bands'][0])
		@for($i=0;$i< sizeof($data['bands'][0]) ;$i++)
			<tr>
				<td style="background-color: #d9e1f2; font-weight: bold; text-align: center;">{{ number_format( $data['bands'][0][$i]['fromValue'] ) }}</td>
				@if($data['bands'][0][$i]['toValue'] == -1)
					<td style="background-color: #d9e1f2; font-weight: bold; text-align: center;"></td>
				@else
					<td style="background-color: #d9e1f2; font-weight: bold; text-align: center;">{{ number_format( $data['bands'][0][$i]['toValue'] ) }}</td>
				@endif
				<td style="background-color: #d9e1f2; font-weight: bold; text-align: center;">{{ number_format( ($data['bands'][0][$i]['percentage'])*100 ) }}%</td>
			</tr>
		@endfor
	@else
		<tr>
			<td colspan="3" style="background-color: #d9e1f2; font-weight: bold; text-align: center;"> 
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
			<td style="font-weight: bold; text-align: center;"> PREVISÃO </td>
		</tr>
		<tr>
			<td style="text-align: center; background-color: #01528c; font-weight: bold; color: #FFFFFF;"> CLIENTE </td>
			@for($m = $data['startMonthFcst']; $m < sizeof($data['monthsMidName']);$m++)
				<td style="text-align: center; background-color: #01528c; font-weight: bold; color: #FFFFFF;"> {{ strtoupper($data['monthsMidName'][$m]) }} </td>
			@endfor
			<td style="text-align: center; background-color: #01528c; font-weight: bold; color: #FFFFFF;"> TOTAL </td>
		</tr>
		@for($f = 0; $f < sizeof($data['forecast']); $f++)
			<tr>
				@if(strtoupper($data['forecast'][$f]['client']) == "TOTAL")
					<td style="text-align: center; background-color: #0f243e; font-weight: bold; color: #FFFFFF;"> {{ strtoupper($data['forecast'][$f]['client']) }} </td>
					@for($m = $data['startMonthFcst']; $m < sizeof($data['monthsMidName']);$m++)
						<td style="text-align: center; background-color: #0f243e; font-weight: bold; color: #FFFFFF;"> {{ number_format($data['forecast'][$f]['split'][$m]) }} </td>
					@endfor
					<td style="text-align: center; background-color: #0f243e; font-weight: bold; color: #FFFFFF;"> {{ number_format($data['forecast'][$f]['revenue']) }} </td>
				@else
					<td style="text-align: center; background-color: #d9e1f2; font-weight: bold; "> {{ strtoupper($data['forecast'][$f]['client']) }} </td>
					@for($m = $data['startMonthFcst']; $m < sizeof($data['monthsMidName']);$m++)
						<td style="text-align: center; background-color: #d9e1f2; font-weight: bold; "> {{ number_format($data['forecast'][$f]['split'][$m]) }} </td>
					@endfor	
					<td style="text-align: center; background-color: #d9e1f2; font-weight: bold;"> {{ number_format($data['forecast'][$f]['revenue']) }} </td>
				@endif							
			</tr>
		@endfor						
	</table>
@else
	<table>
		<tr>
			<td style="text-align: center; background-color: #01528c; font-weight: bold; color: #FFFFFF;"><center> SEM PREVISÃO PARA AGÊNCIA </center></td>
		</tr>
	</table>
@endif

<table>
	<tr>
		<td style="background-color: #01528c; text-align: center; font-weight: bold; color: #FFFFFF;"> CANAIS </td>
		@for($tc =0;$tc < sizeof($data['mountBV']['byBrand']);$tc++)
			@if(number_format( $data['mountBV']['byBrand'][$tc]['value']  >= 1))
				@if($data['mountBV']['byBrand'][$tc]['brand'] == "TOTAL")						
					<td style="background-color: #01528c; text-align: center; font-weight: bold; color: #FFFFFF;">{{ $data['mountBV']['byBrand'][$tc]['brand'] }}</td>
				@else
					<td style="background-color: #01528c; text-align: center; font-weight: bold; color: #FFFFFF;">{{ $data['mountBV']['byBrand'][$tc]['brand'] }}</td>				
				@endif
			@endif
		@endfor						
	</tr>
	<tr>
		<td style="text-align: center;background-color: #d9e1f2; font-weight: bold;"> INVESTIMENTO </td>
		@for($tc =0;$tc < sizeof($data['mountBV']['byBrand']);$tc++)
			@if(number_format( $data['mountBV']['byBrand'][$tc]['value']  >= 1))							
				<td style="text-align: center;background-color: #d9e1f2; font-weight: bold;">{{ number_format( $data['mountBV']['byBrand'][$tc]['value'] ) }}</td>
			@endif
		@endfor
	</tr>
</table>

<table>
	<tr>
		<td style="background-color: #01528c; text-align: center; font-weight: bold; color: #FFFFFF;"> CLIENTE </td>
		<td style="background-color: #01528c; text-align: center; font-weight: bold; color: #FFFFFF;"> VALOR </td>
	</tr>
	@for($tc =0;$tc < sizeof($data['mountBV']['child']);$tc++)
		@if(strtoupper($data['mountBV']['child'][$tc]['client']) == "TOTAL")
		<tr>
			<td style="text-align: center;background-color: #0f243e;  color: #FFFFFF; font-weight: bold;">{{ strtoupper( $data['mountBV']['child'][$tc]['client'] ) }}</td>
			<td style="text-align: center;background-color: #0f243e;  color: #FFFFFF; font-weight: bold;">{{ number_format( $data['mountBV']['child'][$tc]['total'] ) }}</td>
		</tr>
		@else
		<tr>
			<td style="text-align: center;background-color:  #d9e1f2; font-weight: bold;">{{ strtoupper( $data['mountBV']['child'][$tc]['client'] ) }}</td>
			<td style="text-align: center;background-color:  #d9e1f2; font-weight: bold;">{{ number_format( $data['mountBV']['child'][$tc]['total'] ) }}</td>
		</tr>
		@endif
	@endfor	
</table>				

<table>
	<tr>
		<td style="background-color: #01528c; text-align: center; font-weight: bold; color: #FFFFFF;"> MÊS </td>
		@for($tc =0;$tc < sizeof($data['mountBV']['byMonth']);$tc++)							
			@if($data['mountBV']['byMonth'][$tc]['month'] == "TOTAL")
				<td style="background-color: #01528c; text-align: center; font-weight: bold; color: #FFFFFF;">{{ $data['mountBV']['byMonth'][$tc]['month'] }}</td>
			@else
				<td style="background-color: #01528c; text-align: center; font-weight: bold; color: #FFFFFF;">{{ strtoupper( $data['monthsMidName'][$data['mountBV']['byMonth'][$tc]['month'] - 1] ) }}</td>
			@endif
		@endfor						
	</tr>
	<tr>
		<td style="text-align: center;background-color: #d9e1f2; font-weight: bold;"> INVESTIMENTO </td>
		@for($tc =0;$tc < sizeof($data['mountBV']['byMonth']);$tc++)
			<td style="text-align: center;background-color: #d9e1f2; font-weight: bold;">{{ number_format( $data['mountBV']['byMonth'][$tc]['value'] ) }}</td>
		@endfor							
	</tr>
</table>

<table >
	<tr>
		<td colspan="3" style="background-color: #01528c; text-align: center; font-weight: bold; color: #FFFFFF;">
			{{ $data['yearsBand'][1] }}
		</td>
	</tr>
	<tr>
		<td style="background-color: #01528c; text-align: center; font-weight: bold; color: #FFFFFF;">De</td>
		<td style="background-color: #01528c; text-align: center; font-weight: bold; color: #FFFFFF;">Até</td>
		<td style="background-color: #01528c; text-align: center; font-weight: bold; color: #FFFFFF;">%</td>
	</tr>
	@if($data['bands'][1])
		@for($i=0;$i< sizeof($data['bands'][1]) ;$i++)
			<tr>
				<td style="background-color: #d9e1f2; text-align: center; font-weight: bold;">{{ number_format( $data['bands'][1][$i]['fromValue'] ) }}</td>
				@if($data['bands'][1][$i]['toValue'] == -1)
					<td style="background-color: #d9e1f2; text-align: center; font-weight: bold;"> - </td>
				@else
					<td style="background-color: #d9e1f2; text-align: center; font-weight: bold;">{{ number_format( $data['bands'][1][$i]['toValue'] ) }}</td>
				@endif
				<td style="background-color: #d9e1f2; text-align: center; font-weight: bold;">{{ number_format( ($data['bands'][1][$i]['percentage'])*100 ) }}%</td>
			</tr>
		@endfor
	@else
		<tr>
			<td colspan="3" style="background-color: #01528c; text-align: center; font-weight: bold; color: #FFFFFF;"> 
					NÃO EXISTE INFORMAÇÃO DE FAIXAS PARA ESTE ANO.
			</td>
		</tr>
	@endif
</table>

<table>
	<tr>
		<td colspan="2" style="background-color: #01528c; text-align: center; font-weight: bold; color: #FFFFFF;"> INVESTIMENTO {{($data['cYear']-1)}} </td>
		<td style="background-color: #d9e1f2; text-align: center; font-weight: bold;"> {{ number_format($data['infoPreviousYear']['finalValue']) }} </td>
	</tr>
	<tr>
		<td colspan="2" style="background-color: #01528c; text-align: center; font-weight: bold; color: #FFFFFF;"> FAIXA ATINGIDA </td>
		<td style="background-color: #d9e1f2; text-align: center; font-weight: bold;">
			@if($data['infoPreviousYear']['finalPercentage'] <= 0)
				-
			@else
				{{ number_format( ($data['infoPreviousYear']['finalPercentage'])*100 ) }}% 
			@endif
		</td>
	</tr>
	<tr>
		<td colspan="2" style="background-color: #01528c; text-align: center; font-weight: bold; color: #FFFFFF;"> REMUNERAÇÃO ATINGIDA </td>
		<td style="background-color: #d9e1f2; text-align: center; font-weight: bold;"> 
			@if($data['infoPreviousYear']['finalBV'])
				{{ number_format($data['infoPreviousYear']['finalBV']) }} 
			@else
				-
			@endif
		</td>
	</tr>					
</table>
