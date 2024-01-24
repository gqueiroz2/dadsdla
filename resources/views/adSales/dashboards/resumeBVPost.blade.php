@extends('layouts.mirror')
@section('title', 'Dashboards BV')
@section('head')	
	<script src="/js/dashboards-resume.js"></script>
    <?php include(resource_path('views/auth.php')); ?>
@endsection

@section('content')
	<div class="container-fluid">
		<div class="row">
			<div class="col">
				<form method="POST" action="{{ route('resumeBVPost') }}" runat="server" onsubmit="ShowLoading()">
					@csrf
					<div class="row">
						<div class="col">
							<label class="labelLeft bold"> Region: </label>
							@if($errors->has('region'))
								<label style="color: red;">* Required</label>
							@endif
							{{$render->region($salesRegion)}}							
						</div>
						<div class="col">
							<label class="labelLeft bold" > Agency Group </label>
							@if($errors->has('agencyGroup'))
								<label style="color: red;">* Required</label>
							@endif
							{{$render->agencyGroupForm()}}
						</div>						
						<div class="col">
							<label class="labelLeft bold"> Currency: </label>
							@if($errors->has('currency'))
								<label style="color: red;">* Required</label>
							@endif
							{{$render->currency()}}
						</div>
						<div class="col">
							<label class="labelLeft bold"> Value: </label>
							@if($errors->has('value'))
								<label style="color: red;">* Required</label>
							@endif
							{{$render->valueNet()}}
						</div>
						<div class="col">
							<label class="labelLeft"><span class="bold"> &nbsp; </span> </label>
							<input type="submit" value="Generate" class="btn btn-primary" style="width: 100%;">
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>

	<form method="POST" runat="server" name="tableForm" onkeyup="calculate()" action="{{ route('bvSaveForecast') }}"> 
			@csrf 
			<!-- information to save de forecast -->
			<input type='hidden' readonly='true' type="text" name="currency" id="currency" style="background-color:transparent; border:none; font-weight:bold; text-align:center;" value="{{$currency}}">
			<input type='hidden' readonly='true' type="text" name="value" id="value" style="background-color:transparent; border:none; font-weight:bold; text-align:center;" value="{{$value}}">
			<input type='hidden' readonly='true' type="text" name="salesRep" id="salesRep" style="background-color:transparent; border:none; font-weight:bold; text-align:center;" value="{{$salesRep}}">
			<input type='hidden' readonly='true' type="text" name="agencyGroup" id="agencyGroup" style="background-color:transparent; border:none; font-weight:bold; text-align:center;" value="{{$agencyGroup}}">
			<!-- end of forecast info -->

		  	<div class="row justify-content-end">
                <div class="col">
                    <div class="container-fluid">
                        <div class="row justify-content-end">
                        	
                        	<div class="col-2" style="color: #0070c0; font-size: 22px;">
                        		<br>
								<div style="float: right;"> RESUME - {{$currencyName}}</div>
							</div>		

                            <div class="col-2" style="margin-left: 27px;">
                            	<label class="labelLeft"><span class="bold"> &nbsp; </span> </label>
				                <button type="button" id="excel" class="btn btn-primary" style="width: 100%">
				                    Generate Excel
				                </button>
				            </div>
						</div>
					</div>
				</div>	
			</div>
				
			<div class="container-fluid" id="body">
				<div class="row ">
					<div class="col"> 	
						
						<table style='width: 100%; zoom: 85%;font-size: 16px;'>
				        	<tr class="center">
				        		<td style="width: 7% !important; background-color: white;"> &nbsp; </td>
				        	</tr>
				        	
				        </table>

						<div class="wrap" >

							<!-- WM PART PPYEAR INFO -->
							@if($bvWMPpyear['wmPaytv']['fromValue'] != false)
							<table style="border-style:solid; border-color: black; border-width: 0px 0px 1px 0px;">
					     		
					     		<tr>
					     			<td class='dn center' colspan="3" style="height: 20px !important; font-size: 14px; border-style:solid; border-color: black; border-width: 1px 1px 1px 1px;"> {{$year-2}} WM - PAY TV </td>
					     		</tr>
					     		<tr class='center' style="height: 20px !important;">
					     			<td class='oddGrey'style="font-size: 14px; border-style:solid; border-color: black; border-width: 1px 1px 1px 1px;">FROM R$</td>
					     			<td class='oddGrey'style="font-size: 14px; border-style:solid; border-color: black; border-width: 1px 1px 1px 1px;">TO R$</td>
					     			<td class='oddGrey'style="font-size: 14px; border-style:solid; border-color: black; border-width: 1px 1px 1px 1px;">%</td>		
					     			<td></td>
					     						     			
					     		</tr>
					     		@for($p = 0; $p<sizeof($bvWMPpyear['wmPaytv']['fromValue']); $p++)
					        		<tr class="even center">
				        				<td class="col" style="border-style:solid; border-color: black; border-width: 0px 1px 0px 1px;">{{number_format(($bvWMPpyear['wmPaytv']['fromValue'][$p]['from_value']/$pRateWM),0,',','.')}}</td>
					        			@if($bvWMPpyear['wmPaytv']['toValue'][$p]['to_value'] == -1)
					        				<td class="col" style="border-style:solid; border-color: black; border-width: 0px 1px 0px 1px;">0</td>
					        			@else
					        				<td class="col" style="border-style:solid; border-color: black; border-width: 0px 1px 0px 1px;">{{number_format(($bvWMPpyear['wmPaytv']['toValue'][$p]['to_value']/$pRateWM),0,',','.')}}</td>
					        			@endif
					        			<td class="col" style="border-style:solid; border-color: black; border-width: 0px 1px 0px 1px;">{{number_format(($bvWMPpyear['wmPaytv']['percentage'][$p]['percentage']*100),0,',','.')}}%</td>
					        		</tr>
					        	@endfor 					        	
					        </table>
					        @endif					        

					        @if($bvWMPpyear['wmDigital']['fromValue'] != false)
							<table style="border-style:solid; border-color: black; border-width: 0px 0px 1px 0px;">
					     		<tr>
					     			<td class='dn center' colspan="3" style="height: 20px !important; font-size: 14px; border-style:solid; border-color: black; border-width: 1px 1px 1px 1px;"> {{$year-2}} WM - DIGITAL </td>
					     		</tr>
					     		<tr class='center' style="height: 20px !important;">
					     			<td class='oddGrey'style="font-size: 14px; border-style:solid; border-color: black; border-width: 1px 1px 1px 1px;">FROM R$</td>
					     			<td class='oddGrey'style="font-size: 14px; border-style:solid; border-color: black; border-width: 1px 1px 1px 1px;">TO R$</td>
					     			<td class='oddGrey'style="font-size: 14px; border-style:solid; border-color: black; border-width: 1px 1px 1px 1px;">%</td>		
					     			<td></td>
					     						     			
					     		</tr>
					     		@for($p = 0; $p<sizeof($bvWMPpyear['wmDigital']['fromValue']); $p++)
					        		<tr class="even center">
				        				<td class="col" style="border-style:solid; border-color: black; border-width: 0px 1px 0px 1px;">{{number_format(($bvWMPpyear['wmDigital']['fromValue'][$p]['from_value']/$pRateWM),0,',','.')}}</td>
					        			@if($bvWMPpyear['wmDigital']['toValue'][$p]['to_value'] == -1)
					        				<td class="col" style="border-style:solid; border-color: black; border-width: 0px 1px 0px 1px;">0</td>
					        			@else
					        				<td class="col" style="border-style:solid; border-color: black; border-width: 0px 1px 0px 1px;">{{number_format(($bvWMPpyear['wmDigital']['toValue'][$p]['to_value']/$pRateWM),0,',','.')}}</td>
					        			@endif
					        			<td class="col" style="border-style:solid; border-color: black; border-width: 0px 1px 0px 1px;">{{number_format(($bvWMPpyear['wmDigital']['percentage'][$p]['percentage']*100),0,',','.')}}%</td>
					        		</tr>
					        	@endfor    					        	
					        </table>
					        @endif

					        <!-- DSC PART PPYEAR INFO -->
					        @if($bvDSCPpyear['dscPaytv']['fromValue'] != false)
							<table style="border-style:solid; border-color: black; border-width: 0px 0px 1px 0px;">
					     		<tr>
					     			<td class='dc center' colspan="3" style="height: 20px !important; font-size: 14px; border-style:solid; border-color: black; border-width: 1px 1px 1px 1px;"> {{$year-2}} DSC - PAY TV </td>
					     		</tr>
					     		<tr class='center' style="height: 20px !important;">
					     			<td class='oddGrey'style="font-size: 14px; border-style:solid; border-color: black; border-width: 1px 1px 1px 1px;">FROM R$</td>
					     			<td class='oddGrey'style="font-size: 14px; border-style:solid; border-color: black; border-width: 1px 1px 1px 1px;">TO R$</td>
					     			<td class='oddGrey'style="font-size: 14px; border-style:solid; border-color: black; border-width: 1px 1px 1px 1px;">%</td>		
					     			<td></td>
					     						     			
					     		</tr>
					     		@for($p = 0; $p<sizeof($bvDSCPpyear['dscPaytv']['fromValue']); $p++)
					        		<tr class="even center">
				        				<td class="col" style="border-style:solid; border-color: black; border-width: 0px 1px 0px 1px;">{{number_format(($bvDSCPpyear['dscPaytv']['fromValue'][$p]['from_value']/$pRateWM),0,',','.')}}</td>
					        			@if($bvDSCPpyear['dscPaytv']['toValue'][$p]['to_value'] == -1)
					        				<td class="col" style="border-style:solid; border-color: black; border-width: 0px 1px 0px 1px;">0</td>
					        			@else
					        				<td class="col" style="border-style:solid; border-color: black; border-width: 0px 1px 0px 1px;">{{number_format(($bvDSCPpyear['dscPaytv']['toValue'][$p]['to_value']/$pRateWM),0,',','.')}}</td>
					        			@endif
					        			<td class="col" style="border-style:solid; border-color: black; border-width: 0px 1px 0px 1px;">{{number_format(($bvDSCPpyear['dscPaytv']['percentage'][$p]['percentage']*100),0,',','.')}}%</td>
					        		</tr>
					        	@endfor    					        	
					        </table>
					        @endif


					        @if($bvDSCPpyear['dscDigital']['fromValue'] != false)
							<table style="border-style:solid; border-color: black; border-width: 0px 0px 1px 0px;">
					     		<tr>
					     			<td class='dc center' colspan="3" style="height: 20px !important; font-size: 14px; border-style:solid; border-color: black; border-width: 1px 1px 1px 1px;"> {{$year-2}} DSC - DIGITAL </td>
					     		</tr>
					     		<tr class='center' style="height: 20px !important;">
					     			<td class='oddGrey'style="font-size: 14px; border-style:solid; border-color: black; border-width: 1px 1px 1px 1px;">FROM R$</td>
					     			<td class='oddGrey'style="font-size: 14px; border-style:solid; border-color: black; border-width: 1px 1px 1px 1px;">TO R$</td>
					     			<td class='oddGrey'style="font-size: 14px; border-style:solid; border-color: black; border-width: 1px 1px 1px 1px;">%</td>		
					     			<td></td>
					     						     			
					     		</tr>
					     		@for($p = 0; $p<sizeof($bvDSCPpyear['dscDigital']['fromValue']); $p++)
					        		<tr class="even center">
				        				<td class="col" style="border-style:solid; border-color: black; border-width: 0px 1px 0px 1px;">{{number_format(($bvDSCPpyear['dscDigital']['fromValue'][$p]['from_value']/$pRateWM),0,',','.')}}</td>
					        			@if($bvDSCPpyear['dscDigital']['toValue'][$p]['to_value'] == -1)
					        				<td class="col" style="border-style:solid; border-color: black; border-width: 0px 1px 0px 1px;">0</td>
					        			@else
					        				<td class="col" style="border-style:solid; border-color: black; border-width: 0px 1px 0px 1px;">{{number_format(($bvDSCPpyear['dscDigital']['toValue'][$p]['to_value']/$pRateWM),0,',','.')}}</td>
					        			@endif
					        			<td class="col" style="border-style:solid; border-color: black; border-width: 0px 1px 0px 1px;">{{number_format(($bvDSCPpyear['dscDigital']['percentage'][$p]['percentage']*100),0,',','.')}}%</td>
					        		</tr>
					        	@endfor    					        	
					        </table>
					        @endif

					        <!-- WM PART PYEAR INFO -->
							@if($bvWMPyear['wmPaytv']['fromValue'] != false)
							<table style="border-style:solid; border-color: black; border-width: 0px 0px 1px 0px;">
					     		<tr>
					     			<td class='dn center' colspan="3" style="height: 20px !important; font-size: 14px; border-style:solid; border-color: black; border-width: 1px 1px 1px 1px;"> {{$year-1}} WM - PAY TV </td>
					     		</tr>
					     		<tr class='center' style="height: 20px !important;">
					     			<td class='oddGrey'style="font-size: 14px; border-style:solid; border-color: black; border-width: 1px 1px 1px 1px;">FROM R$</td>
					     			<td class='oddGrey'style="font-size: 14px; border-style:solid; border-color: black; border-width: 1px 1px 1px 1px;">TO R$</td>
					     			<td class='oddGrey'style="font-size: 14px; border-style:solid; border-color: black; border-width: 1px 1px 1px 1px;">%</td>		
					     			<td></td>
					     						     			
					     		</tr>
					     		@for($p = 0; $p<sizeof($bvWMPyear['wmPaytv']['fromValue']); $p++)
					        		<tr class="even center">
				        				<td class="col" style="border-style:solid; border-color: black; border-width: 0px 1px 0px 1px;">{{number_format(($bvWMPyear['wmPaytv']['fromValue'][$p]['from_value']/$pRateWM),0,',','.')}}</td>
				        				<td class="col" style="border-style:solid; border-color: black; border-width: 0px 1px 0px 1px;">{{number_format(($bvWMPyear['wmPaytv']['toValue'][$p]['to_value']/$pRateWM),0,',','.')}}</td>
					        			<td class="col" style="border-style:solid; border-color: black; border-width: 0px 1px 0px 1px;">{{number_format(($bvWMPyear['wmPaytv']['percentage'][$p]['percentage']*100),0,',','.')}}%</td>
					        		</tr>
					        	@endfor    					        	
					        </table>
					        @endif

					        
					        @if($bvWMPyear['wmDigital']['fromValue'] != false)
							<table style="border-style:solid; border-color: black; border-width: 0px 0px 1px 0px;">
					     		<tr>
					     			<td class='dn center' colspan="3" style="height: 20px !important; font-size: 14px; border-style:solid; border-color: black; border-width: 1px 1px 1px 1px;"> {{$year-1}} WM - DIGITAL </td>
					     		</tr>
					     		<tr class='center' style="height: 20px !important;">
					     			<td class='oddGrey'style="font-size: 14px; border-style:solid; border-color: black; border-width: 1px 1px 1px 1px;">FROM R$</td>
					     			<td class='oddGrey'style="font-size: 14px; border-style:solid; border-color: black; border-width: 1px 1px 1px 1px;">TO R$</td>
					     			<td class='oddGrey'style="font-size: 14px; border-style:solid; border-color: black; border-width: 1px 1px 1px 1px;">%</td>		
					     			<td></td>
					     						     			
					     		</tr>
					     		@for($p = 0; $p<sizeof($bvWMPyear['wmDigital']['fromValue']); $p++)
					        		<tr class="even center">
				        				<td class="col" style="border-style:solid; border-color: black; border-width: 0px 1px 0px 1px;">{{number_format(($bvWMPyear['wmDigital']['fromValue'][$p]['from_value']/$pRateWM),0,',','.')}}</td>
				        				<td class="col" style="border-style:solid; border-color: black; border-width: 0px 1px 0px 1px;">{{number_format(($bvWMPyear['wmDigital']['toValue'][$p]['to_value']/$pRateWM),0,',','.')}}</td>
					        			<td class="col" style="border-style:solid; border-color: black; border-width: 0px 1px 0px 1px;">{{number_format(($bvWMPyear['wmDigital']['percentage'][$p]['percentage']*100),0,',','.')}}%</td>
					        		</tr>
					        	@endfor    					        	
					        </table>
					        @endif


					        <!-- DSC PART PYEAR INFO -->
					        @if($bvDSCPyear['dscPaytv']['fromValue'] != false)
							<table style="border-style:solid; border-color: black; border-width: 0px 0px 1px 0px;">
					     		<tr>
					     			<td class='dc center' colspan="3" style="height: 20px !important; font-size: 14px; border-style:solid; border-color: black; border-width: 1px 1px 1px 1px;"> {{$year-1}} DSC - PAY TV </td>
					     		</tr>
					     		<tr class='center' style="height: 20px !important;">
					     			<td class='oddGrey'style="font-size: 14px; border-style:solid; border-color: black; border-width: 1px 1px 1px 1px;">FROM R$</td>
					     			<td class='oddGrey'style="font-size: 14px; border-style:solid; border-color: black; border-width: 1px 1px 1px 1px;">TO R$</td>
					     			<td class='oddGrey'style="font-size: 14px; border-style:solid; border-color: black; border-width: 1px 1px 1px 1px;">%</td>		
					     			<td></td>
					     						     			
					     		</tr>
					     		@for($d = 0; $d<sizeof($bvDSCPyear['dscPaytv']['fromValue']); $d++)
					        		<tr class="even center">
				        				<td class="col" style="border-style:solid; border-color: grey; border-width: 0px 1px 0px 1px;">{{number_format(($bvDSCPyear['dscPaytv']['fromValue'][$d]['from_value']/$pRateWM),0,',','.')}}</td>
				        				<td class="col" style="border-style:solid; border-color: grey; border-width: 0px 1px 0px 1px;">{{number_format(($bvDSCPyear['dscPaytv']['toValue'][$d]['to_value']/$pRateWM),0,',','.')}}</td>
				        				<td class="col" style="border-style:solid; border-color: grey; border-width: 0px 1px 0px 1px;">{{number_format(($bvDSCPyear['dscPaytv']['percentage'][$d]['percentage']*100),0,',','.')}}%</td>
					        		</tr>
					        	@endfor 
					        	@if($bvTargetDSC != false)
						        	<tr class='center'>
						     			<td class='dc'style="font-size: 14px; border-style:solid; border-color: black; border-width: 1px 1px 1px 1px;">COMPANY</td>
						     			<td class='dc'style="font-size: 14px; border-style:solid; border-color: black; border-width: 1px 1px 1px 1px;">TARGET</td>
						     			<td class='dc'style="font-size: 14px; border-style:solid; border-color: black; border-width: 1px 1px 1px 1px;">REAL</td>		
						     		</tr>	
						     		<tr class='center'>
						     			<td class='even'style="font-size: 14px; border-style:solid; border-color: black; border-width: 1px 1px 1px 1px;">DSC</td>
						     			<td class='even'style="font-size: 14px; border-style:solid; border-color: black; border-width: 1px 1px 1px 1px;">{{number_format(($bvTargetDSC[0]['dsc_target']/$pRateWM),0,',','.')}}</td>
						     			<td class='even'style="font-size: 14px; border-style:solid; border-color: black; border-width: 1px 1px 1px 1px;">{{number_format(($realDSCPyear[0]['netRevenue']/$pRateWM),0,',','.')}}</td>		
						     		</tr>				        	
						     		<tr class='center'>
						     			<td class='even'style="font-size: 14px; border-style:solid; border-color: black; border-width: 1px 1px 1px 1px;">SPT</td>
						     			<td class='even'style="font-size: 14px; border-style:solid; border-color: black; border-width: 1px 1px 1px 1px;">{{number_format(($bvTargetDSC[0]['spt_target']/$pRateWM),0,',','.')}}</td>
						     			<td class='even'style="font-size: 14px; border-style:solid; border-color: black; border-width: 1px 1px 1px 1px;">{{number_format(($realSPTPyear[0]['netRevenue']/$pRateWM),0,',','.')}}</td>		
						     		</tr>	
						     	@endif			        	
					        </table>
					        @endif


					        @if($bvDSCPyear['dscDigital']['fromValue'] != false)
							<table style="border-style:solid; border-color: black; border-width: 0px 0px 1px 0px;">
					     		<tr>
					     			<td class='dc center' colspan="3" style="height: 20px !important; font-size: 14px; border-style:solid; border-color: black; border-width: 1px 1px 1px 1px;"> {{$year-1}} DSC - DIGITAL </td>
					     		</tr>
					     		<tr class='center' style="height: 20px !important;">
					     			<td class='oddGrey'style="font-size: 14px; border-style:solid; border-color: black; border-width: 1px 1px 1px 1px;">FROM R$</td>
					     			<td class='oddGrey'style="font-size: 14px; border-style:solid; border-color: black; border-width: 1px 1px 1px 1px;">TO R$</td>
					     			<td class='oddGrey'style="font-size: 14px; border-style:solid; border-color: black; border-width: 1px 1px 1px 1px;">%</td>		
					     			<td></td>
					     						     			
					     		</tr>
					     		@for($d = 0; $d<sizeof($bvDSCPyear['dscDigital']['fromValue']); $d++)
					        		<tr class="even center">
				        				<td class="col" style="border-style:solid; border-color: black; border-width: 0px 1px 0px 1px;">{{number_format(($bvDSCPyear['dscDigital']['fromValue'][$d]['from_value']/$pRateWM),0,',','.')}}</td>
					        			<td class="col" style="border-style:solid; border-color: black; border-width: 0px 1px 0px 1px;">{{number_format(($bvDSCPyear['dscDigital']['toValue'][$d]['to_value']/$pRateWM),0,',','.')}}</td>
					        			<td class="col" style="border-style:solid; border-color: black; border-width: 0px 1px 0px 1px;">{{number_format(($bvDSCPyear['dscDigital']['percentage'][$d]['percentage']*100),0,',','.')}}%</td>
					        		</tr>
					        	@endfor    					        	
					        </table>
					        @endif
				        	
				        </div>	

				        @if($payTv[0]['station'] != false)
					        <!-- pay tv table -->
					        <table style='max-width: 15%; width: auto; border-style:solid; border-color: black; border-width: 1px 1px 1px 1px; float: right;'>
					          	<tr class="center" style="border-style:solid; border-color: black; border-width: 0px 0px 1px 0px;">
						       		<td style='width: 10%;' class="newBlue col">PAY TV {{$year-1}}</td>
						    	   	<td style='width: 5%;' class="medBlue col">SOA</td>
						   		</tr>
					           	@for($p = 0; $p<sizeof($payTv); $p++)
					           	<tr class="center">
					           		@if($payTv[$p]['station'] == 'WBD')
						           		<td style='width: 10%;' class="medBlue col">{{$payTv[$p]['station']}}</td>
						           		<td style='width: 5%;' class="medBlue col">{{number_format(($payTv[$p]['percentage']*100),0,',','.')}}%</td>
					           		@else
					           			<td style='width: 10%;' class="even col">{{$payTv[$p]['station']}}</td>
					           			<td style='width: 5%;' class="even col">{{number_format(($payTv[$p]['percentage']*100),0,',','.')}}%</td>
					           		@endif
					           	</tr>	
					           	@endfor
					        </table>			        
					    @endif
				        <table style='width: 100%; zoom: 85%;font-size: 16px;'>
				        	<tr class="center">
				        		<td style="width: 7% !important; background-color: white;"> &nbsp; </td>
				        	</tr>
				        </table>

						<!-- forecast table-->

						<table class="table-responsive" id='table' style='width: 100%; zoom: 85%;'>							
							<tr>
								<th class='newBlue center' colspan='11' style='font-size:22px; width:100%; border-style:solid; border-color:black; border-width: 1px 1px 1px 1px;'> {{$agencyGroupName}}</th>
							</tr>
							<tr class="medBlue center" style="font-size:16px; border-style:solid; border-color:black; border-width: 1px 1px 1px 1px;">
								<td class="col" style="width:10%; border-style:solid; border-color:black; border-width: 0px 1px 0px 0px;">CLIENT</td>
								<td class="col" style="width:10%; border-style:solid; border-color:black; border-width: 0px 1px 0px 0px;">AGENCY</td>
								<td class="col" style="width:6%; border-style:solid; border-color:black; border-width: 0px 1px 0px 0px;">{{$year-2}}</td>
								<td class="col" style="width:6%; border-style:solid; border-color:black; border-width: 0px 1px 0px 0px;">{{$year-1}}</td>
								<td class="col" style="width:6%; border-style:solid; border-color:black; border-width: 0px 1px 0px 0px;">{{$year}}</td>
								<td class="col oddGrey" style="width:5%; border-style:solid; border-color:black; border-width: 0px 1px 0px 0px;">FORECAST {{$year}}</td>
								<td class="col" style="width:5%; border-style:solid; border-color:black; border-width: 0px 1px 0px 0px;">TOTAL {{$year}}</td>
								<td class="col oddGrey" style="width:5%; border-style:solid; border-color:black; border-width: 0px 1px 0px 0px;">FORECAST SPT {{$year}}</td>
								<td class="col" style="width:5%; border-style:solid; border-color:black; border-width: 0px 1px 0px 0px;">PERCENTAGE</td>
								<td class="col" style="width:5%; border-style:solid; border-color:black; border-width: 0px 1px 0px 0px;">SALES REP</td>
								<td class="col oddGrey" style="width:14%; border-style:solid; border-color:black; border-width: 0px 1px 0px 0px;">STATUS</td>
							</tr>
							@for($b = 0; $b < sizeof($bvTest) ; $b++)	
								<input type='hidden' readonly='true' type="text" name="clientID-{{$b}}" id="clientID-{{$b}}" style="background-color:transparent; border:none; font-weight:bold; text-align:center;" value="{{$bvTest[$b]['clientId']}}">
								<input type='hidden' readonly='true' type="text" name="agencyID-{{$b}}" id="agencyID-{{$b}}" style="background-color:transparent; border:none; font-weight:bold; text-align:center;" value="{{$bvTest[$b]['agencyId']}}">
								<tr class='center' style='font-size:16px;'>
									<td class='{{$color[$b]}}' style="border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;"><input readonly='true' type="text" name="client-{{$b}}" id="client-{{$b}}" style=" background-color:transparent; border:none; font-weight:bold; text-align:center;" value="{{$bvTest[$b]['client']}}"></td>
									<td class='{{$color[$b]}}' style="border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;"><input readonly='true' type="text" name="agency-{{$b}}" id="agency-{{$b}}" style=" background-color:transparent; border:none; font-weight:bold; text-align:center;" value="{{$bvTest[$b]['agency']}}"></td>
									<td class="{{$color[$b]}}" style="border-style:solid; border-color:black; border-width: 0px 1px 0px 0px;"(>{{number_format($bvTest[$b][$year-2],0,',','.')}}</td>
									<td class="{{$color[$b]}}" style="border-style:solid; border-color:black; border-width: 0px 1px 0px 0px;">{{number_format($bvTest[$b][$year-1],0,',','.')}}</td>
									<td class="{{$color[$b]}} numberonly" style="border-style:solid; border-color:black; border-width: 0px 1px 0px 0px;"><input readonly='true' type="text" name="real-{{$b}}" id="real-{{$b}}" style="background-color:transparent; border:none; font-weight:bold; text-align:center;" value="{{number_format($bvTest[$b][$year],0,',','.')}}"></td>
									<td class="{{$color[$b]}} numberonly" style="border-style:solid; border-color:black; border-width: 0px 1px 0px 0px;"><input type="text" name="forecast-{{$b}}" id="forecast-{{$b}}" style="background-color:transparent; border:none; font-weight:bold; text-align:center;" value="{{number_format($bvTest[$b]['prev'],0,',','.')}}"></td>
									<td class="{{$color[$b]}} numberonly" style="border-style:solid; border-color:black; border-width: 0px 1px 0px 0px;"><input readonly='true' type="text" name="forecast-total-{{$b}}" id="forecast-total-{{$b}}" style="background-color:transparent; border:none; font-weight:bold; text-align:center;" value="{{number_format($bvTest[$b]['prevActualSum'],0,',','.')}}"></td>
									<td class="{{$color[$b]}} numberonly" style="border-style:solid; border-color:black; border-width: 0px 1px 0px 0px;"><input type="text" name="forecast-spt-{{$b}}" id="forecast-spt-{{$b}}" style="background-color:transparent; border:none; font-weight:bold; text-align:center;" value={{number_format($bvTest[$b]['sptPrev'],0,',','.')}}></td>
									<td class="{{$color[$b]}}" style="border-style:solid; border-color:black; border-width: 0px 1px 0px 0px;">{{$bvTest[$b]['variation']}}%</td>
									<td class="{{$color[$b]}}" style="border-style:solid; border-color:black; border-width: 0px 1px 0px 0px;">{{$bvTest[$b]['salesRep']}}</td>									
									<td class="{{$color[$b]}}" style="border-style:solid; border-color:black; border-width: 0px 1px 0px 0px;"><input type="text" maxlength="100" name="status-{{$b}}" id="status-{{$b}}" style="width: 100%; background-color:transparent; border:none; font-weight:bold;" value="{{$bvTest[$b]['status']}}"></td>
								</tr>
							@endfor
							<tr style='font-size:16px;'>
								<td class="smBlue center" style="border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;">TOTAL</td>
								<td class="smBlue center" style="border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;"></td>
								<td class="smBlue center" style="border-style:solid; border-color:black; border-width: 0px 1px 0px 0px;">{{number_format($total[$year-2],0,',','.')}}</td>
								<td class="smBlue center" style="border-style:solid; border-color:black; border-width: 0px 1px 0px 0px;" name="total-pYear" id="total-pYear">{{number_format($total[$year-1],0,',','.')}}</td>
								<td class="smBlue center" style="border-style:solid; border-color:black; border-width: 0px 1px 0px 0px;">{{number_format($total[$year],0,',','.')}}</td>
								<td class="smBlue center" style="border-style:solid; border-color:black; border-width: 0px 1px 0px 0px;" name="total-forecast" id="total-forecast">{{number_format($total['prev'],0,',','.')}}</td>
								<td class="smBlue center" style="border-style:solid; border-color:black; border-width: 0px 1px 0px 0px;" name="total-actual" id="total-actual">{{number_format($total['prevActualSum'],0,',','.')}}</td>
								<td class="smBlue center" style="border-style:solid; border-color:black; border-width: 0px 1px 0px 0px;" name="total-forecast-spt" id="total-forecast-spt">{{number_format($total['sptPrev'],0,',','.')}}</td>
								<td class="smBlue center" style="border-style:solid; border-color:black; border-width: 0px 1px 0px 0px;" name="total-var" id="total-var">{{$total['variation']}}%</td>
								<td class="smBlue center" style="border-style:solid; border-color:black; border-width: 0px 1px 0px 0px;"></td>
								<td class="smBlue center" style="border-style:solid; border-color:black; border-width: 0px 1px 0px 0px;"></td>
							</tr>		
						</table>						

						<table style='width: 100%; zoom: 85%;font-size: 16px;'>
							<tr class="center">
				        		<td style="width: 7% !important; background-color: white;"> &nbsp; </td>
				        	</tr>
				        </table>

				        <table style='width: 100%; zoom: 85%;font-size: 20px;'>
							<tr class="smBlue center">
				        		<td style="width: 7% !important;"> Historical Investment </td>
				        	</tr>
				        </table>

				        <table style='width: 100%; zoom: 85%;font-size: 16px;'>
							<tr class="center">
				        		<td style="width: 7% !important; background-color: white;"> &nbsp; </td>
				        	</tr>
				        </table>    
				      	
				      	<table  style='table-layout: fixed; width: 100%; zoom: 85%; border-style:solid; border-color:black; border-width: 1px 1px 1px 1px;'>							
							<tr>
								<th class='newBlue center' colspan='8' style='font-size:22px; width:100%; border-style:solid; border-color:black; border-width: 1px 1px 1px 1px;'> {{$year-1}}</th>
							</tr>
							<tr class="medBlue center" style="font-size:16px; border-style:solid; border-color:black; border-width: 1px 1px 1px 1px;">
								<td class="col" style="width:12.5%; border-style:solid; border-color:black; border-width: 0px 1px 0px 0px;">CLIENT</td>
								<td class="col" style="width:12.5%; border-style:solid; border-color:black; border-width: 0px 1px 0px 0px;">AGENCY</td>
								<td class="col" style="width:12.5%; border-style:solid; border-color:black; border-width: 0px 1px 0px 0px;">GE</td>
								<td class="col" style="width:12.5%; border-style:solid; border-color:black; border-width: 0px 1px 0px 0px;">SPORTS</td>
								<td class="col" style="width:12.5%; border-style:solid; border-color:black; border-width: 0px 1px 0px 0px;">NEWS</td>
								<td class="col" style="width:12.5%; border-style:solid; border-color:black; border-width: 0px 1px 0px 0px;">DIGITAL</td>
								<td class="col smBlue" style="width:12.5%; border-style:solid; border-color:black; border-width: 0px 1px 0px 0px;">TOTAL</td>
								<td class="col sony" style="width:12.5%; border-style:solid; border-color:black; border-width: 0px 1px 0px 0px;">SPT</td>								
							</tr>
							@for($h = 0; $h < sizeof($historyPyear) ; $h++)	
							<tr class="even center" style="font-size:16px; border-style:solid; border-color:black; border-width: 0px 0px 0px 1px;">
								<td class="col" style="width:12.5%; border-style:solid; border-color:black; border-width: 0px 1px 0px 0px;">{{$historyPyear[$h]['clientName']}}</td>
								<td class="col" style="width:12.5%; border-style:solid; border-color:black; border-width: 0px 1px 0px 0px;">{{$historyPyear[$h]['agencyName']}}</td>
								<td class="col" style="width:12.5%; border-style:solid; border-color:black; border-width: 0px 1px 0px 0px;">{{number_format($historyPyear[$h]['geCluster'][0]['netRevenue']/$pRateWM)}}</td>
								<td class="col" style="width:12.5%; border-style:solid; border-color:black; border-width: 0px 1px 0px 0px;">{{number_format($historyPyear[$h]['sportsCluster'][0]['netRevenue']/$pRateWM)}}</td>
								<td class="col" style="width:12.5%; border-style:solid; border-color:black; border-width: 0px 1px 0px 0px;">{{number_format($historyPyear[$h]['newsCluster'][0]['netRevenue']/$pRateWM)}}</td>
								<td class="col" style="width:12.5%; border-style:solid; border-color:black; border-width: 0px 1px 0px 0px;">{{number_format($historyPyear[$h]['digitalCluster'][0]['netRevenue']/$pRateWM)}}</td>
								<td class="col smBlue" style="width:12.5%; border-style:solid; border-color:black; border-width: 0px 1px 0px 0px;">{{number_format($totalByClientPyear[$h]/$pRateWM)}}</td>
								<td class="col " style="width:12.5%; border-style:solid; border-color:black; border-width: 0px 1px 0px 0px;">{{number_format($historyPyear[$h]['sptCluster'][0]['netRevenue']/$pRateWM)}}</td>								
							</tr>
							@endfor
							<tr class ='smBlue center' style="font-size:16px;">
								<td class="col" style="width:12.5%; border-style:solid; border-color:black; border-width: 0px 1px 0px 0px;">TOTAL</td>
								<td class="col" style="width:12.5%; border-style:solid; border-color:black; border-width: 0px 1px 0px 0px;"></td>
								<td class="col" style="width:12.5%; border-style:solid; border-color:black; border-width: 0px 1px 0px 0px;">{{number_format($totalClusterPyear['geCluster']/$pRateWM)}}</td>
								<td class="col" style="width:12.5%; border-style:solid; border-color:black; border-width: 0px 1px 0px 0px;">{{number_format($totalClusterPyear['sportsCluster']/$pRateWM)}}</td>
								<td class="col" style="width:12.5%; border-style:solid; border-color:black; border-width: 0px 1px 0px 0px;">{{number_format($totalClusterPyear['newsCluster']/$pRateWM)}}</td>
								<td class="col" style="width:12.5%; border-style:solid; border-color:black; border-width: 0px 1px 0px 0px;">{{number_format($totalClusterPyear['digitalCluster']/$pRateWM)}}</td>
								<td class="col smBlue" style="width:12.5%; border-style:solid; border-color:black; border-width: 1px 1px 0px 0px;">{{number_format($totalClusterPyear['totalCluster']/$pRateWM)}}</td>
								<td class="col sony" style="width:12.5%; border-style:solid; border-color:black; border-width: 0px 1px 0px 0px;">{{number_format($totalClusterPyear['sptCluster']/$pRateWM)}}</td>
							</tr>
						</table>

						<table style='width: 100%; zoom: 85%;font-size: 16px;'>
							<tr class="center">
				        		<td style="width: 7% !important; background-color: white;"> &nbsp; </td>
				        	</tr>
				        </table>

						<table style='table-layout: fixed; width: 100%; zoom: 85%;  border-style:solid; border-color:black; border-width: 1px 1px 1px 1px;'>							
							<tr>
								<th class='newBlue center' colspan='8' style='font-size:22px; width:100%; border-style:solid; border-color:black; border-width: 1px 1px 1px 1px;'> {{$year-2}}</th>
							</tr>
							<tr class="medBlue center" style="font-size:16px; border-style:solid; border-color:black; border-width: 1px 1px 1px 1px;">
								<td class="col" style="width:12.5%; border-style:solid; border-color:black; border-width: 0px 1px 0px 0px;">CLIENT</td>
								<td class="col" style="width:12.5%; border-style:solid; border-color:black; border-width: 0px 1px 0px 0px;">AGENCY</td>
								<td class="col" style="width:12.5%; border-style:solid; border-color:black; border-width: 0px 1px 0px 0px;">GE</td>
								<td class="col" style="width:12.5%; border-style:solid; border-color:black; border-width: 0px 1px 0px 0px;">SPORTS</td>
								<td class="col" style="width:12.5%; border-style:solid; border-color:black; border-width: 0px 1px 0px 0px;">NEWS</td>
								<td class="col" style="width:12.5%; border-style:solid; border-color:black; border-width: 0px 1px 0px 0px;">DIGITAL</td>
								<td class="col smBlue" style="width:12.5%; border-style:solid; border-color:black; border-width: 0px 1px 0px 0px;">TOTAL</td>
								<td class="col sony" style="width:12.5%; border-style:solid; border-color:black; border-width: 0px 1px 0px 0px;">SPT</td>								
							</tr>
							@for($h1 = 0; $h1 < sizeof($historyPpyear) ; $h1++)	
							<tr class="even center" style="font-size:16px; border-style:solid; border-color:black; border-width: 0px 0px 0px 1px;">
								<td class="col" style="width:12.5%; border-style:solid; border-color:black; border-width: 0px 1px 0px 0px;">{{$historyPpyear[$h1]['clientName']}}</td>
								<td class="col" style="width:12.5%; border-style:solid; border-color:black; border-width: 0px 1px 0px 0px;">{{$historyPpyear[$h1]['agencyName']}}</td>
								<td class="col" style="width:12.5%; border-style:solid; border-color:black; border-width: 0px 1px 0px 0px;">{{number_format($historyPpyear[$h1]['geCluster'][0]['netRevenue']/$pRateWM)}}</td>
								<td class="col" style="width:12.5%; border-style:solid; border-color:black; border-width: 0px 1px 0px 0px;">{{number_format($historyPpyear[$h1]['sportsCluster'][0]['netRevenue']/$pRateWM)}}</td>
								<td class="col" style="width:12.5%; border-style:solid; border-color:black; border-width: 0px 1px 0px 0px;">{{number_format($historyPpyear[$h1]['newsCluster'][0]['netRevenue']/$pRateWM)}}</td>
								<td class="col" style="width:12.5%; border-style:solid; border-color:black; border-width: 0px 1px 0px 0px;">{{number_format($historyPpyear[$h1]['digitalCluster'][0]['netRevenue']/$pRateWM)}}</td>
								<td class="col smBlue" style="width:12.5%; border-style:solid; border-color:black; border-width: 0px 1px 0px 0px;">{{number_format($totalByClientPpyear[$h1]/$pRateWM)}}</td>
								<td class="col " style="width:12.5%; border-style:solid; border-color:black; border-width: 0px 1px 0px 0px;">{{number_format($historyPpyear[$h1]['sptCluster'][0]['netRevenue']/$pRateWM)}}</td>								
							</tr>
							@endfor
							<tr class ='smBlue center' style="font-size:16px;">
								<td class="col" style="width:12.5%; border-style:solid; border-color:black; border-width: 0px 1px 0px 0px;">TOTAL</td>
								<td class="col" style="width:12.5%; border-style:solid; border-color:black; border-width: 0px 1px 0px 0px;"></td>
								<td class="col" style="width:12.5%; border-style:solid; border-color:black; border-width: 0px 1px 0px 0px;">{{number_format($totalClusterPpyear['geCluster']/$pRateWM)}}</td>
								<td class="col" style="width:12.5%; border-style:solid; border-color:black; border-width: 0px 1px 0px 0px;">{{number_format($totalClusterPpyear['sportsCluster']/$pRateWM)}}</td>
								<td class="col" style="width:12.5%; border-style:solid; border-color:black; border-width: 0px 1px 0px 0px;">{{number_format($totalClusterPpyear['newsCluster']/$pRateWM)}}</td>
								<td class="col" style="width:12.5%; border-style:solid; border-color:black; border-width: 0px 1px 0px 0px;">{{number_format($totalClusterPpyear['digitalCluster']/$pRateWM)}}</td>
								<td class="col smBlue" style="width:12.5%; border-style:solid; border-color:black; border-width: 1px 1px 0px 0px;">{{number_format($totalClusterPpyear['totalCluster']/$pRateWM)}}</td>
								<td class="col sony" style="width:12.5%; border-style:solid; border-color:black; border-width: 0px 1px 0px 0px;">{{number_format($totalClusterPpyear['sptCluster']/$pRateWM)}}</td>
							</tr>
						</table>  
					</div>
				</div>
			</div>
		</form>

<!-- javascript to make the excel export -->
<script type="text/javascript">
            
    $(document).ready(function(){

        ajaxSetup();

        $('#excel').click(function(event){

            var agencyGroup = "<?php echo $agencyGroup; ?>";
            var currency = "<?php echo $currency; ?>";
            var value = "<?php echo $value; ?>";

            var div = document.createElement('div');
            var img = document.createElement('img');
            img.src = '/loading_excel.gif';
            div.innerHTML ="Generating File...</br>";
            div.style.cssText = 'position: absolute; left: 0px; top:0px;  margin:0px;        width: 100%;        height: 100%;        display:block;        z-index: 99999;        opacity: 0.9;        -moz-opacity: 0;        filter: alpha(opacity = 45);        background: white;    background-repeat: no-repeat;        background-position:50% 50%;        text-align: center;        overflow: hidden;   font-size:30px;     font-weight: bold;        color: black;        padding-top: 20%';
            div.appendChild(img);
            document.body.appendChild(div);

            var typeExport = $("#excel").val();

            var title = "<?php echo $titleExcel; ?>";
            var auxTitle = "<?php echo $titleExcel; ?>";
                
            $.ajax({
                xhrFields: {
                    responseType: 'blob',
                },
                url: "/generate/excel/dashboard/dashResume",
                type: "POST",
                data: {agencyGroup,currency,value,title, typeExport, auxTitle},
                /*success: function(output){
                    $("#vlau").html(output);
                },*/
                success: function(result,status,xhr){
                    var disposition = xhr.getResponseHeader('content-disposition');
                    var matches = /"([^"]*)"/.exec(disposition);
                    var filename = (matches != null && matches[1] ? matches[1] : title);

                    //download
                    var blob = new Blob([result], {
                        type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
                    });
                    var link = document.createElement('a');
                    link.href = window.URL.createObjectURL(blob);
                    link.download = filename;

                    document.body.appendChild(link);

                    link.click();
                    document.body.removeChild(link);
                    document.body.removeChild(div);
                },
                error: function(xhr, ajaxOptions, thrownError){
                    document.body.removeChild(div);
                    alert(xhr.status+" "+thrownError);
                }
            });                    
        });
    });
</script>
@endsection



    

    