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

		<div class="row justify-content-end mt-2">
			<div class="col-sm" style="color: #0070c0; font-size: 22px;">
				<div style="float: right;"> RESUME </div>
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
                           <!-- <div class="col-2">
								<label class="labelLeft"><span class="bold"> &nbsp; </span> </label>
								<input type="submit" id="button" value="Save" class="btn btn-primary" style="width: 100%">
							</div>-->

							<div class='col-3'>
								<label class="labelLeft"><span class="bold"> &nbsp; </span> </label>
						        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalExemplo" style="width: 100%">
						          PAY TV {{$year-1}}
						        </button>
						    </div>
						</div>
					</div>
				</div>	
			</div>
		
		    <!-- Modal -->
		    <div class="modal fade" id="modalExemplo" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		      <div class="modal-dialog" role="document">
		        <div class="modal-content">
		          <div class="modal-header">
		            <h5 class="modal-title" id="exampleModalLabel"></h5>
		            <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
		              <span aria-hidden="true">&times;</span>
		            </button>
		          </div>
		          <div class="modal-body">
		          	<table style='width: 100%; border-style:solid; border-color: black; border-width: 1px 1px 1px 1px;'>
		          	<tr class="center" style="border-style:solid; border-color: black; border-width: 0px 0px 1px 0px;">
			       		<td style='width: 50%; ' class="newBlue col">PAY TV {{$year-1}}</td>
			    	   	<td style='width: 50%;' class="medBlue col">SOA</td>
			   		</tr>
		           	@for($p = 0; $p<sizeof($payTv); $p++)
		           	<tr class="center">
		           		@if($payTv[$p]['station'] == 'WBD')
			           		<td style='width: 50%;' class="medBlue col">{{$payTv[$p]['station']}}</td>
			           		<td style='width: 50%;' class="medBlue col">{{number_format(($payTv[$p]['percentage']*100),0,',','.')}}%</td>
		           		@else
		           			<td style='width: 50%;' class="even col">{{$payTv[$p]['station']}}</td>
		           			<td style='width: 50%;' class="even col">{{number_format(($payTv[$p]['percentage']*100),0,',','.')}}%</td>
		           		@endif
		           	</tr>	
		           	@endfor
		           </table>
		          </div>
		          <div class="modal-footer">
		            <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
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

				        <table style='width: 100%; zoom: 85%;font-size: 16px;'>
				        	<tr class="center">
				        		<td style="width: 7% !important; background-color: white;"> &nbsp; </td>
				        	</tr>
				        </table>

						<!-- forecast table-->

						<table id='table' style='width: 100%; zoom: 85%;'>
							
							<tr>
								<th class='newBlue center' colspan='10' style='font-size:22px; width:100%; border-style:solid; border-color:black; border-width: 1px 1px 1px 1px;'> {{$agencyGroupName}}</th>
							</tr>
							<tr class="medBlue center" style="font-size:16px; border-style:solid; border-color:black; border-width: 1px 1px 1px 1px;">
								<td class="col" style="width:12%; border-style:solid; border-color:black; border-width: 0px 1px 0px 0px;">CLIENT</td>
								<td class="col" style="width:12%; border-style:solid; border-color:black; border-width: 0px 1px 0px 0px;">AGENCY</td>
								<td class="col" style="width:6%; border-style:solid; border-color:black; border-width: 0px 1px 0px 0px;">{{$year-2}}</td>
								<td class="col" style="width:6%; border-style:solid; border-color:black; border-width: 0px 1px 0px 0px;">{{$year-1}}</td>
								<td class="col" style="width:6%; border-style:solid; border-color:black; border-width: 0px 1px 0px 0px;">{{$year}}</td>
								<td class="col oddGrey" style="width:6%; border-style:solid; border-color:black; border-width: 0px 1px 0px 0px;">FORECAST {{$year}}</td>
								<td class="col" style="width:6%; border-style:solid; border-color:black; border-width: 0px 1px 0px 0px;">TOTAL {{$year}}</td>
								<td class="col oddGrey" style="width:6%; border-style:solid; border-color:black; border-width: 0px 1px 0px 0px;">FORECAST SPT {{$year}}</td>
								<td class="col" style="width:5%; border-style:solid; border-color:black; border-width: 0px 1px 0px 0px;">PERCENTAGE</td>
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
							</tr>		
						</table>						

						<table style='width: 100%; zoom: 85%;font-size: 16px;'>
							<tr class="center">
				        		<td style="width: 7% !important; background-color: white;"> &nbsp; </td>
				        	</tr>
				        </table>

				        <!-- net investment table-->

				        <table style='width: 100%; zoom: 85%; font-size: 16px;'>
				        	<tr>
				        		<th class='newBlue center' colspan="2" style="font-size: 16px; border-style:solid; border-color: black; border-width: 1px 1px 1px 1px;">NET INVESTMENT</th>
				        	</tr>
				        	<tr class="medBlue center" >
								<td class="col" style="width: 5%; border-style:solid; border-color: black; border-width: 1px 1px 1px 1px;">YEAR</td>
				        		@for($x = 0; $x<sizeof($liquid); $x++)
				        			<td class="col" style="width: 3% !important; border-style:solid; border-color: black; border-width: 1px 1px 1px 1px;">{{$liquid[$x]['brand']}}</td>
				        		@endfor
				        		<td class="col" style="width: 10% !important; border-style:solid; border-color: black; border-width: 1px 1px 1px 1px;">TOTAL</td>
				        	</tr>
				        	<tr class="even center" >
				        		<td  style="border-style:solid; border-color: black; border-width: 0px 1px 0px 1px;">{{$year-1}}</td>	       		
				        		@for($x = 0; $x<sizeof($liquid); $x++)
				        			<td style="border-style:solid; border-color: black; border-width: 0px 1px 0px 1px;">{{number_format(($liquid[$x]['liquidPyear']/$pRateWM),0,',','.')}}</td>	      			
				        		@endfor
				        		<td style="border-style:solid; border-color: black; border-width: 0px 1px 0px 1px;">{{number_format(($totalYearInvest['totalPYear']/$pRateWM),0,',','.')}}</td>
				        	</tr>
				        	<tr class="even center">
				        		<td style="border-style:solid; border-color: black; border-width: 0px 1px 0px 1px;">{{$year-2}}</td>
				        		@for($x = 0; $x<sizeof($liquid); $x++)
									<td style="border-style:solid; border-color: black; border-width: 0px 1px 0px 1px;">{{number_format(($liquid[$x]['liquidPpyear']/$pRateWM),0,',','.')}}</td>
								@endfor
								<td style="border-style:solid; border-color: black; border-width: 0px 1px 0px 1px;">{{number_format(($totalYearInvest['totalPpYear']/$pRateWM),0,',','.')}}</td>
				        	</tr>
				        	<tr class="even center">
				        		<td style="border-style:solid; border-color: black; border-width: 0px 1px 1px 1px;">{{$year-3}}</td>
				        		@for($x = 0; $x<sizeof($liquid); $x++)
									<td style="border-style:solid; border-color: black; border-width: 0px 1px 1px 1px;">{{number_format(($liquid[$x]['liquidPppyear']/$pRateWM),0,',','.')}}</td>
								@endfor
								<td style="border-style:solid; border-color: black; border-width: 0px 1px 0px 1px;">{{number_format(($totalYearInvest['totalPppYear']/$pRateWM),0,',','.')}}</td>
				        	</tr>
				        	<tr>
				        		<td class="smBlue center" style="border-style:solid; border-color:black; border-width: 1px 1px 0px 1px;">TOTAL</td>
				        		@for($x = 0; $x<sizeof($liquid); $x++)
				        			<td class="smBlue center" style="border-style:solid; border-color: black; border-width: 1px 1px 1px 1px;">{{number_format(($investTotalBrand[$x]/$pRateWM),0,',','.')}}</td>
				        		@endfor
				        		<td class="smBlue center" style="border-style:solid; border-color: black; border-width: 1px 1px 1px 1px;">{{number_format(($totalYearInvest['all']/$pRateWM),0,',','.')}}</td>
				        	</tr>
				        </table>

				        <table style='width: 100%; zoom: 85%;font-size: 16px;'>
							<tr class="center">
				        		<td style="width: 7% !important; background-color: white;"> &nbsp; </td>
				        	</tr>
				        </table>
				     	

					</div>
				</div>
			</div>
		</form>

@endsection



    

    