<!-- WM PART PPYEAR INFO -->
@if($data['bvWMPpyear']['wmPaytv']['fromValue'] != false)
<table>
	<tr>
		<td colspan="3" style="font-weight: bold; background-color: #0f243e; color: white; text-align: center;"> {{$data['year']-2}} WM - PAY TV </td>
	</tr>
	<tr>
		<td style="font-weight: bold; background-color: #e6e6e6; text-align: center;">FROM R$</td>
		<td style="font-weight: bold; background-color: #e6e6e6; text-align: center;">TO R$</td>
		<td style="font-weight: bold; background-color: #e6e6e6; text-align: center;">%</td>		
		<td></td>
					     			
	</tr>
	@for($p = 0; $p<sizeof($data['bvWMPpyear']['wmPaytv']['fromValue']); $p++)
	<tr>
		<td style="font-weight: bold; background-color: #f9fbfd; text-align: center; ">{{number_format(($data['bvWMPpyear']['wmPaytv']['fromValue'][$p]['from_value']/$data['pRateWM']),0,',','.')}}</td>
		@if($data['bvWMPpyear']['wmPaytv']['toValue'][$p]['to_value'] == -1)
			<td style="font-weight: bold; background-color: #f9fbfd; text-align: center;">0</td>
		@else
			<td style="font-weight: bold; background-color: #f9fbfd; text-align: center;">{{number_format(($data['bvWMPpyear']['wmPaytv']['toValue'][$p]['to_value']/$data['pRateWM']),0,',','.')}}</td>
		@endif
		<td style="font-weight: bold; background-color: #f9fbfd; text-align: center;">{{number_format(($data['bvWMPpyear']['wmPaytv']['percentage'][$p]['percentage']*100),0,',','.')}}%</td>
	</tr>
	@endfor				        	
</table>
@endif					        

@if($data['bvWMPpyear']['wmDigital']['fromValue'] != false)
<table>
		<tr>
			<td colspan='3' style="font-weight: bold; background-color: #0f243e; color: white; text-align: center;"> {{$data['year']-2}} WM - DIGITAL </td>
		</tr>
		<tr>
			<td style="font-weight: bold; background-color: #e6e6e6; text-align: center;">FROM R$</td>
			<td style="font-weight: bold; background-color: #e6e6e6; text-align: center;">TO R$</td>
			<td style="font-weight: bold; background-color: #e6e6e6; text-align: center;">%</td>		
			<td></td>
						     			
		</tr>
		@for($p = 0; $p<sizeof($data['bvWMPpyear']['wmDigital']['fromValue']); $p++)
		<tr>
			<td style="font-weight: bold; background-color: #f9fbfd; text-align: center; ">{{number_format(($data['bvWMPpyear']['wmDigital']['fromValue'][$p]['from_value']/$data['pRateWM']),0,',','.')}}</td>
			@if($data['bvWMPpyear']['wmDigital']['toValue'][$p]['to_value'] == -1)
				<td style="font-weight: bold; background-color: #f9fbfd; text-align: center;">0</td>
			@else
				<td style="font-weight: bold; background-color: #f9fbfd; text-align: center;">{{number_format(($data['bvWMPpyear']['wmDigital']['toValue'][$p]['to_value']/$data['pRateWM']),0,',','.')}}</td>
			@endif
			<td style="font-weight: bold; background-color: #f9fbfd; text-align: center;">{{number_format(($data['bvWMPpyear']['wmDigital']['percentage'][$p]['percentage']*100),0,',','.')}}%</td>
		</tr>
	@endfor    					        	
</table>
@endif

<!-- DSC PART PPYEAR INFO -->
@if($data['bvDSCPpyear']['dscPaytv']['fromValue'] != false)
<table>
		<tr>
			<td colspan="3" style="font-weight: bold; background-color: #0070c0; text-align: center;"> {{$data['year']-2}} DSC - PAY TV </td>
		</tr>
		<tr>
			<td style="font-weight: bold; background-color: #e6e6e6; text-align: center;">FROM R$</td>
			<td style="font-weight: bold; background-color: #e6e6e6; text-align: center;">TO R$</td>
			<td style="font-weight: bold; background-color: #e6e6e6; text-align: center;">%</td>		
			<td></td>
						     			
		</tr>
		@for($p = 0; $p<sizeof($data['bvDSCPpyear']['dscPaytv']['fromValue']); $p++)
		<tr>
			<td style="font-weight: bold; background-color: #f9fbfd; text-align: center;">{{number_format(($data['bvDSCPpyear']['dscPaytv']['fromValue'][$p]['from_value']/$data['pRateWM']),0,',','.')}}</td>
			@if($data['bvDSCPpyear']['dscPaytv']['toValue'][$p]['to_value'] == -1)
				<td style="font-weight: bold; background-color: #f9fbfd; text-align: center;">0</td>
			@else
				<td style="font-weight: bold; background-color: #f9fbfd; text-align: center;">{{number_format(($data['bvDSCPpyear']['dscPaytv']['toValue'][$p]['to_value']/$data['pRateWM']),0,',','.')}}</td>
			@endif
			<td style="font-weight: bold; background-color: #f9fbfd; text-align: center;">{{number_format(($data['bvDSCPpyear']['dscPaytv']['percentage'][$p]['percentage']*100),0,',','.')}}%</td>
		</tr>
	@endfor    					        	
</table>
@endif


@if($data['bvDSCPpyear']['dscDigital']['fromValue'] != false)
<table>
		<tr>
			<td colspan="3" style="font-weight: bold; background-color: #0070c0; text-align: center;"> {{$data['year']-2}} DSC - DIGITAL </td>
		</tr>
		<tr>
			<td style="font-weight: bold; background-color: #e6e6e6; text-align: center;">FROM R$</td>
			<td style="font-weight: bold; background-color: #e6e6e6; text-align: center;">TO R$</td>
			<td style="font-weight: bold; background-color: #e6e6e6; text-align: center;">%</td>		
			<td></td>						     			
		</tr>
		@for($p = 0; $p<sizeof($data['bvDSCPpyear']['dscDigital']['fromValue']); $p++)
		<tr>
			<td style="font-weight: bold; background-color: #f9fbfd; text-align: center;">{{number_format(($data['bvDSCPpyear']['dscDigital']['fromValue'][$p]['from_value']/$data['pRateWM']),0,',','.')}}</td>
			@if($data['bvDSCPpyear']['dscDigital']['toValue'][$p]['to_value'] == -1)
				<td style="font-weight: bold; background-color: #f9fbfd; text-align: center;">0</td>
			@else
				<td style="font-weight: bold; background-color: #f9fbfd; text-align: center;">{{number_format(($data['bvDSCPpyear']['dscDigital']['toValue'][$p]['to_value']/$data['pRateWM']),0,',','.')}}</td>
			@endif
			<td style="font-weight: bold; background-color: #f9fbfd; text-align: center;">{{number_format(($data['bvDSCPpyear']['dscDigital']['percentage'][$p]['percentage']*100),0,',','.')}}%</td>
		</tr>
	@endfor    					        	
</table>
@endif

<!-- WM PART PYEAR INFO -->
@if($data['bvWMPyear']['wmPaytv']['fromValue'] != false)
<table>
		<tr>
			<td colspan="3" style="font-weight: bold; background-color: #0f243e; color: white; text-align: center;"> {{$data['year']-1}} WM - PAY TV </td>
		</tr>
		<tr>
			<td style="font-weight: bold; background-color: #e6e6e6; text-align: center;">FROM R$</td>
			<td style="font-weight: bold; background-color: #e6e6e6; text-align: center;">TO R$</td>
			<td style="font-weight: bold; background-color: #e6e6e6; text-align: center;">%</td>		
			<td></td>
						     			
		</tr>
		@for($p = 0; $p<sizeof($data['bvWMPyear']['wmPaytv']['fromValue']); $p++)
		<tr>
			<td style="font-weight: bold; background-color: #f9fbfd; text-align: center;">{{number_format(($data['bvWMPyear']['wmPaytv']['fromValue'][$p]['from_value']/$data['pRateWM']),0,',','.')}}</td>
			<td style="font-weight: bold; background-color: #f9fbfd; text-align: center;">{{number_format(($data['bvWMPyear']['wmPaytv']['toValue'][$p]['to_value']/$data['pRateWM']),0,',','.')}}</td>
			<td style="font-weight: bold; background-color: #f9fbfd; text-align: center;">{{number_format(($data['bvWMPyear']['wmPaytv']['percentage'][$p]['percentage']*100),0,',','.')}}%</td>
		</tr>
	@endfor    					        	
</table>
@endif


@if($data['bvWMPyear']['wmDigital']['fromValue'] != false)
<table>
		<tr>
			<td colspan="3" style="font-weight: bold; background-color: #0f243e; color: white; text-align: center;"> {{$data['year']-1}} WM - DIGITAL </td>
		</tr>
		<tr>
			<td style="font-weight: bold; background-color: #e6e6e6; text-align: center;">FROM R$</td>
			<td style="font-weight: bold; background-color: #e6e6e6; text-align: center;">TO R$</td>
			<td style="font-weight: bold; background-color: #e6e6e6; text-align: center;">%</td>		
			<td></td>
						     			
		</tr>
		@for($p = 0; $p<sizeof($data['bvWMPyear']['wmDigital']['fromValue']); $p++)
		<tr>
			<td style="font-weight: bold; background-color: #f9fbfd; text-align: center;">{{number_format(($data['bvWMPyear']['wmDigital']['fromValue'][$p]['from_value']/$data['pRateWM']),0,',','.')}}</td>
			<td style="font-weight: bold; background-color: #f9fbfd; text-align: center;">{{number_format(($data['bvWMPyear']['wmDigital']['toValue'][$p]['to_value']/$data['pRateWM']),0,',','.')}}</td>
			<td style="font-weight: bold; background-color: #f9fbfd; text-align: center;">{{number_format(($data['bvWMPyear']['wmDigital']['percentage'][$p]['percentage']*100),0,',','.')}}%</td>
		</tr>
	@endfor    					        	
</table>
@endif


<!-- DSC PART PYEAR INFO -->
@if($data['bvDSCPyear']['dscPaytv']['fromValue'] != false)
<table style="font-weight: bold; border-style:solid; border-color: black; border-width: 0px 0px 1px 0px;">
		<tr>
			<td colspan="3" style="font-weight: bold; background-color: #0070c0; text-align: center;"> {{$data['year']-1}} DSC - PAY TV </td>
		</tr>
		<tr>
			<td style="font-weight: bold; background-color: #e6e6e6; text-align: center;">FROM R$</td>
			<td style="font-weight: bold; background-color: #e6e6e6; text-align: center;">TO R$</td>
			<td style="font-weight: bold; background-color: #e6e6e6; text-align: center;">%</td>		
			<td></td>
						     			
		</tr>
		@for($d = 0; $d<sizeof($data['bvDSCPyear']['dscPaytv']['fromValue']); $d++)
		<tr>
			<td style="font-weight: bold; background-color: #f9fbfd; text-align: center;">{{number_format(($data['bvDSCPyear']['dscPaytv']['fromValue'][$d]['from_value']/$data['pRateWM']),0,',','.')}}</td>
			<td style="font-weight: bold; background-color: #f9fbfd; text-align: center;">{{number_format(($data['bvDSCPyear']['dscPaytv']['toValue'][$d]['to_value']/$data['pRateWM']),0,',','.')}}</td>
			<td style="font-weight: bold; background-color: #f9fbfd; text-align: center;">{{number_format(($data['bvDSCPyear']['dscPaytv']['percentage'][$d]['percentage']*100),0,',','.')}}%</td>
		</tr>
	@endfor 
	@if($data['bvTargetDSC'] != false)
    	<tr>
 			<td style="font-weight: bold; background-color: #0070c0; text-align: center;">COMPANY</td>
 			<td style="font-weight: bold; background-color: #0070c0; text-align: center;">TARGET</td>
 			<td style="font-weight: bold; background-color: #0070c0; text-align: center;">REAL</td>		
 		</tr>	
 		<tr>
 			<td style="font-weight: bold; background-color: #f9fbfd; text-align: center;">DSC</td>
 			<td style="font-weight: bold; background-color: #f9fbfd; text-align: center;">{{number_format(($data['bvTargetDSC'][0]['dsc_target']/$data['pRateWM']),0,',','.')}}</td>
 			<td style="font-weight: bold; background-color: #f9fbfd; text-align: center;">{{number_format(($data['realDSCPyear'][0]['netRevenue']/$data['pRateWM']),0,',','.')}}</td>		
 		</tr>				        	
 		<tr>
 			<td style="font-weight: bold; background-color: #f9fbfd; text-align: center;">SPT</td>
 			<td style="font-weight: bold; background-color: #f9fbfd; text-align: center;">{{number_format(($data['bvTargetDSC'][0]['spt_target']/$data['pRateWM']),0,',','.')}}</td>
 			<td style="font-weight: bold; background-color: #f9fbfd; text-align: center;">{{number_format(($data['realSPTPyear'][0]['netRevenue']/$data['pRateWM']),0,',','.')}}</td>		
 		</tr>	
 	@endif			        	
</table>
@endif


@if($data['bvDSCPyear']['dscDigital']['fromValue'] != false)
<table style="font-weight: bold; border-style:solid; border-color: black; border-width: 0px 0px 1px 0px;">
		<tr>
			<td colspan="3" style="font-weight: bold; background-color: #0070c0; text-align: center;"> {{$data['year']-1}} DSC - DIGITAL </td>
		</tr>
		<tr>
			<td style="font-weight: bold; background-color: #e6e6e6; text-align: center;">FROM R$</td>
			<td style="font-weight: bold; background-color: #e6e6e6; text-align: center;">TO R$</td>
			<td style="font-weight: bold; background-color: #e6e6e6; text-align: center;">%</td>		
			<td></td>
						     			
		</tr>
		@for($d = 0; $d<sizeof($data['bvDSCPyear']['dscDigital']['fromValue']); $d++)
		<tr>
			<td style="font-weight: bold; background-color: #f9fbfd; text-align: center;">{{number_format(($data['bvDSCPyear']['dscDigital']['fromValue'][$d]['from_value']/$data['pRateWM']),0,',','.')}}</td>
			<td style="font-weight: bold; background-color: #f9fbfd; text-align: center;">{{number_format(($data['bvDSCPyear']['dscDigital']['toValue'][$d]['to_value']/$data['pRateWM']),0,',','.')}}</td>
			<td style="font-weight: bold; background-color: #f9fbfd; text-align: center;">{{number_format(($data['bvDSCPyear']['dscDigital']['percentage'][$d]['percentage']*100),0,',','.')}}%</td>
		</tr>
	@endfor    					        	
</table>
@endif