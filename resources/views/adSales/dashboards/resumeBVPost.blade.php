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
				<form method="POST" action="{{ route('dashboardBVPost') }}" runat="server" onsubmit="ShowLoading()">
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
				<div style="float: right;"> BV </div>
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
	                            <div class="col-2">
									<label class="labelLeft"><span class="bold"> &nbsp; </span> </label>
									<input type="submit" id="button" value="Save" class="btn btn-primary" style="width: 100%">
								</div>
							</div>
						</div>
					</div>	
				</div>
			<div class="container-fluid" id="body">
				<div class="row ">
					<div class="col"> 				       			

						<!-- forecast table-->

						<table id='table' style='width: 100%; zoom: 85%;'>
							<tr>
								<td class="col medBlue center" style="font-size:16px; width:3%; border-style:solid; border-color:black; border-width: 1px 1px 1px 1px;">Updated date</td>
								<td class="col oddGrey center" style="font-size:14px; width:3%; border-style:solid; border-color:black; border-width: 1px 1px 1px 1px;">{{$updateInfo[0]['updateDate']}}</td>
							</tr>
							<tr>
								<td class="col medBlue center" style="font-size:16px; width:3%; border-style:solid; border-color:black; border-width: 1px 1px 1px 1px;">SALES REP</td>
								<td class="col oddGrey center" style="font-size:14px; width:3%; border-style:solid; border-color:black; border-width: 1px 1px 1px 1px;">{{$updateInfo[0]['salesRep']}}</td>
							</tr>
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
									<td class="{{$color[$b]}}" style="border-style:solid; border-color:black; border-width: 0px 1px 0px 0px;">{{number_format($bvTest[$b][$year-2],0,',','.')}}</td>
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
								<td class="col" style="width: 10%; border-style:solid; border-color: black; border-width: 1px 1px 1px 1px;">YEAR</td>
				        		@for($x = 0; $x<sizeof($brand); $x++)
				        			<td class="col" style="width: 15% !important; border-style:solid; border-color: black; border-width: 1px 1px 1px 1px;">{{$brand[$x]['name']}}</td>
				        		@endfor
				        		<td class="col" style="border-style:solid; border-color: black; border-width: 1px 1px 1px 1px;">TOTAL</td>
				        	</tr>
				        	<tr class="even center" >
				        		<td  style="width: 10%; border-style:solid; border-color: black; border-width: 0px 1px 0px 1px;">{{$year-1}}</td>	       		
				        		@for($x = 0; $x<sizeof($brand); $x++)
				        			<td style="width: 15%; border-style:solid; border-color: black; border-width: 0px 1px 0px 1px;">{{number_format($liquid[$x]['liquidPyear'],0,',','.')}}</td>	      			
				        		@endfor
				        		<td style="width: 10%; border-style:solid; border-color: black; border-width: 0px 1px 0px 1px;">{{number_format($totalYearInvest['totalPYear'],0,',','.')}}</td>
				        	</tr>
				        	<tr class="even center">
				        		<td style="width: 10%; border-style:solid; border-color: black; border-width: 0px 1px 0px 1px;">{{$year-2}}</td>
				        		@for($x = 0; $x<sizeof($brand); $x++)
									<td style="width: 15%; border-style:solid; border-color: black; border-width: 0px 1px 0px 1px;">{{number_format($liquid[$x]['liquidPpyear'],0,',','.')}}</td>
								@endfor
								<td style="width: 10%; border-style:solid; border-color: black; border-width: 0px 1px 0px 1px;">{{number_format($totalYearInvest['totalPpYear'],0,',','.')}}</td>
				        	</tr>
				        	<tr class="even center">
				        		<td style="width: 10%; border-style:solid; border-color: black; border-width: 0px 1px 1px 1px;">{{$year-3}}</td>
				        		@for($x = 0; $x<sizeof($brand); $x++)
									<td style="width: 15%; border-style:solid; border-color: black; border-width: 0px 1px 1px 1px;">{{number_format($liquid[$x]['liquidPppyear'],0,',','.')}}</td>
								@endfor
								<td style="width: 10%; border-style:solid; border-color: black; border-width: 0px 1px 0px 1px;">{{number_format($totalYearInvest['totalPppYear'],0,',','.')}}</td>
				        	</tr>
				        	<tr>
				        		<td class="smBlue center" style="width: 10%; border-style:solid; border-color:black; border-width: 1px 1px 0px 1px;">TOTAL</td>
				        		@for($x = 0; $x<sizeof($brand); $x++)
				        			<td class="smBlue center" style="width: 15%; border-style:solid; border-color: black; border-width: 1px 1px 1px 1px;">{{number_format($investTotalBrand[$x],0,',','.')}}</td>
				        		@endfor
				        		<td class="smBlue center" style="width: 10%; border-style:solid; border-color: black; border-width: 1px 1px 1px 1px;">{{number_format($totalYearInvest['all'],0,',','.')}}</td>
				        	</tr>
				        </table>

				        <table style='width: 100%; zoom: 85%;font-size: 16px;'>
							<tr class="center">
				        		<td style="width: 7% !important; background-color: white;"> &nbsp; </td>
				        	</tr>
				        </table>

				        <!-- 3 years ago historic table-->

				        <table style='width: 100%; zoom: 85%; font-size: 16px;'>
				        	<tr>
				        		<th class='newBlue center' colspan="2" style="font-size: 16px; border-style:solid; border-color: black; border-width: 1px 1px 1px 1px;">{{$year-3}}</th>
				        	</tr>
							<tr class="medBlue center" >
								<td class="col" style="width: 10%; border-style:solid; border-color: black; border-width: 1px 1px 1px 1px;">CLIENT</td>
								<td class="col" style="width: 15%; border-style:solid; border-color: black; border-width: 1px 1px 1px 1px;">AGENCY</td>
				        		@for($x = 0; $x<sizeof($brand); $x++)
				        			<td class="col" style="width: 15%; border-style:solid; border-color: black; border-width: 1px 1px 1px 1px;">{{$brand[$x]['name']}}</td>
				        		@endfor
				        		<td class="col" style="border-style:solid; border-color: black; border-width: 1px 1px 1px 1px;">TOTAL</td>
				        	</tr>
				        	@for($b = 0; $b < sizeof($clientsByPppYear) ; $b++)	
								<tr class='center' style='font-size:16px;'>
									<td class='{{$color[$b]}}' style="width: 10%; border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;">{{$clientsByAE[$b]['clientName']}}</td>
									<td class='{{$color[$b]}}' style="width: 10%; border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;">{{$clientsByAE[$b]['agencyName']}}</td>
									@for ($x=0; $x <sizeof($brand) ; $x++) 
     									<td style="width: 15%; border-style:solid; border-color: black; border-width: 0px 1px 0px 1px;">{{number_format($tableBrandPppyear[$b][$x]['SUM(net_value)'],0,',','.')}}</td>	
     								@endfor
								</tr>
							@endfor
							<tr class="smBlue center">
								<td  style="width: 10%; border-style:solid; border-color:black; border-width: 1px 1px 0px 1px;">TOTAL</td>
							
							</tr>

				        </table>

				        <table style='width: 100%; zoom: 85%;font-size: 16px;'>
							<tr class="center">
				        		<td style="width: 7% !important; background-color: white;"> &nbsp; </td>
				        	</tr>
				        </table>

				        <!-- 2 years ago historic table-->

				        <table style='width: 100%; zoom: 85%; font-size: 16px;'>
				        	<tr>
				        		<th class='newBlue center' colspan="2" style="font-size: 16px; border-style:solid; border-color: black; border-width: 1px 1px 1px 1px;">{{$year-2}}</th>
				        	</tr>
							<tr class="medBlue center" >
								<td class="col" style="width: 10%; border-style:solid; border-color: black; border-width: 1px 1px 1px 1px;">CLIENT</td>
			        			<td class="col" style="width: 15%; border-style:solid; border-color: black; border-width: 1px 1px 1px 1px;">AGENCY</td>
				        		@for($x = 0; $x<sizeof($brand); $x++)
				        			<td class="col" style="width: 15%; border-style:solid; border-color: black; border-width: 1px 1px 1px 1px;">{{$brand[$x]['name']}}</td>
				        		@endfor
				        		<td class="col" style="border-style:solid; border-color: black; border-width: 1px 1px 1px 1px;">TOTAL</td>
				        	</tr>
				        	@for($b = 0; $b < sizeof($clientsByPpYear) ; $b++)	
								<tr class='center' style='font-size:16px;'>
									<td class='{{$color[$b]}}' style="width: 10%; border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;">{{$clientsByAE[$b]['clientName']}}</td>
									<td class='{{$color[$b]}}' style="width: 10%; border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;">{{$clientsByAE[$b]['agencyName']}}</td>
									@for ($x=0; $x <sizeof($brand) ; $x++) 
     									<td style="width: 15%; border-style:solid; border-color: black; border-width: 0px 1px 0px 1px;">{{number_format($tableBrandPpyear[$b][$x]['SUM(net_value)'],0,',','.')}}</td>	
     								@endfor
								</tr>
							@endfor
							<tr>
								<td class="smBlue center" style="width: 10%; border-style:solid; border-color:black; border-width: 1px 1px 0px 1px;">TOTAL</td>
							</tr>

				        </table>

				        <table style='width: 100%; zoom: 85%;font-size: 16px;'>
							<tr class="center">
				        		<td style="width: 7% !important; background-color: white;"> &nbsp; </td>
				        	</tr>
				        </table>

				        <!-- 1 year ago historic table-->

				        <table style='width: 100%; zoom: 85%; font-size: 16px;'>
				        	<tr>
				        		<th class='newBlue center' colspan="2" style="font-size: 16px; border-style:solid; border-color: black; border-width: 1px 1px 1px 1px;">{{$year-1}}</th>
				        	</tr>
							<tr class="medBlue center" >
								<td class="col" style="width: 10%; border-style:solid; border-color: black; border-width: 1px 1px 1px 1px;">CLIENT</td>
								<td class="col" style="width: 15%; border-style:solid; border-color: black; border-width: 1px 1px 1px 1px;">AGENCY</td>
				        		@for($x = 0; $x<sizeof($brand); $x++)
				        			<td class="col" style="width: 15%; border-style:solid; border-color: black; border-width: 1px 1px 1px 1px;">{{$brand[$x]['name']}}</td>				        			
				        		@endfor
				        		<td class="col" style="border-style:solid; border-color: black; border-width: 1px 1px 1px 1px;">TOTAL</td>
				        	</tr>
				        	@for($b = 0; $b < sizeof($clientsByPYear) ; $b++)	
								<tr class='center' style='font-size:16px;'>
									<td class='{{$color[$b]}}' style="width: 10%; border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;">{{$clientsByAE[$b]['clientName']}}</td>
									<td class='{{$color[$b]}}' style="width: 10%; border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;">{{$clientsByAE[$b]['agencyName']}}</td>
									@for ($c=0; $c <sizeof($brand) ; $c++) 
     									<td style="width: 15%; border-style:solid; border-color: black; border-width: 0px 1px 0px 1px;">{{number_format($tableBrandPyear[$b][$c]['SUM(net_value)'],0,',','.')}}</td>	
     								@endfor
								</tr>
							@endfor
							<tr>
								<td class="smBlue center" style="width: 10%; border-style:solid; border-color:black; border-width: 1px 1px 0px 1px;">TOTAL</td>
								<td class="smBlue center" style="width: 10%; border-style:solid; border-color:black; border-width: 1px 1px 0px 1px;"></td>
								@for($x = 0; $x < sizeof($brand); $x++)
									<td class="smBlue center" style="width: 10%; border-style:solid; border-color:black; border-width: 1px 1px 0px 1px;">{{number_format($totalBrandPyear[$x],0,',','.')}}</td>
								@endfor
							</tr>

				        </table>

					</div>
				</div>
			</div>
		</form>

@endsection



    

    