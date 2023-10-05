@extends('layouts.mirror')
@section('title', 'Daily Results')
@section('head')	
	<script src="/js/resultsLATAM.js"></script>
	<?php include(resource_path('views/auth.php')); ?>
@endsection
@section('content')
<div class="container-fluid">
	<div class="row">
		<div class="col">
			<form method="POST" action="{{ route('resultsLATAMPost') }}" runat="server" onsubmit="ShowLoading()">
				@csrf
				<div class="row">
					<div class="col-sm">
						<label class="labelLeft"><span class="bold"> Region: </span></label>
						@if($errors->has('region'))
							<label style="color: red;">* Required</label>
						@endif
						@if($userLevel == 'L0' || $userLevel == 'SU')
							{{$render->region($region)}}							
						@else
							{{$render->regionFiltered($region, $regionID, $special)}}
						@endif							
					</div>

					<div class="col-sm">
						<label class="labelLeft"><span class="bold"> Currency: </span></label>
						@if($errors->has('currency'))
							<label style="color: red;">* Required</label>
						@endif
						{{$render->currency()}}
					</div>

					<div class="col-sm">
						<label class="labelLeft"><span class="bold"> Value: </span></label>
						@if($errors->has('value'))
							<label style="color: red;">* Required</label>
						@endif
						{{$render->value4()}}
					</div>

					<!--<div class="row justify-content-center">          
						<div class="col">       
							<div class="form-group">
								<label><b> Date: </b></label> 
								@if($errors->has('log'))
									<label style="color: red;">* Required</label>
								@endif
								<input type="date" class="form-control" name="log" value="{{date("m/d/Y")}}">
							</div>
						</div>
					</div>  -->

					<div class="col-sm">
						<label class="labelLeft"><span class="bold"> &nbsp; </span> </label>
						<input type="submit" value="Generate" class="btn btn-primary" style="width: 100%;">
					</div>
				</div>
			</form>
		</div>
	</div>

	<div class="row justify-content-end mt-2">
		<div class="col-sm" style="color: #0070c0;font-size: 22px;">
			<span style="float: right;"> Daily Results </span>
		</div>

		<div class="col-3">
            <button type="button" id="excel" class="btn btn-primary" style="width: 100%">
                Generate Excel
            </button>               
        </div> 
	</div>

	<div class="container-fluid">
		<div class="row">
			<div class="col"> 
				<table style='width: 100%; zoom: 85%;font-size: 16px;'>
					<tr class="center">
		        		<td style="width: 7% !important; background-color: white;"> &nbsp; </td>
		        	</tr>
		        </table>
		        @if($realDate != null)
					<table style='width: 100%; zoom: 85%;'>
						<tr class="center">
					        <td class='grey center' style="width: 100% !important; font-size: 22px; border-style:solid; border-color:black; border-width: 1px 1px 0px 1px;" colspan="13"> WBD ( {{$currencyName}} / {{strtoupper($value)}} )</td>
					    </tr>
					    <tr>
					    	<td class='grey center' style="width: 7% !important; font-size: 16px; border-style:solid; border-color:black; border-width: 1px 0px 1px 1px;"> LOG </td>
					    	<td class="grey center" style="width: 7% !important; font-size: 16px; border-style:solid; border-color:black; border-width: 1px 1px 1px 0px;"> {{$realDate}} </td>
					    	<td class="grey center" style="width: 7% !important; font-size: 16px; border-style:solid; border-color:black; border-width: 1px 1px 1px 1px;" colspan="3"> {{$cYear}}</td>
					    	<td class="grey center" style="width: 7% !important; font-size: 16px; border-style:solid; border-color:black; border-width: 1px 1px 1px 1px;" colspan="2"> {{$pYear}}</td>
					    	<td class="grey center" style="width: 7% !important; font-size: 16px; border-style:solid; border-color:black; border-width: 1px 1px 1px 1px;" colspan="1"> {{$ppYear}}</td>
					    	<td class="grey center" style="width: 7% !important; font-size: 16px; border-style:solid; border-color:black; border-width: 1px 1px 1px 1px;" colspan="5"> {{$cYear}} VAR (%) </td>
					    </tr>				   
					    <tr>
					    	<td class="lightGrey center" style="width: 7% !important; font-size: 16px; border-style:solid; border-color:black; border-width: 1px;"> MONTH </td>
					    	<td class="lightGrey center" style="width: 7% !important; font-size: 16px; border-style:solid; border-color:black; border-width: 1px;"> PLATAFORM </td>
					    	<td class="smBlue center" style="width: 7% !important; font-size: 16px;"> BKGS </td>					    	
					    	<td class="smBlue center" style="width: 7% !important; font-size: 16px;"> PLAN </td>
					    	<td class="smBlue center" style="width: 7% !important; font-size: 16px; border-style:solid; border-color:black; border-width: 0px 1px 0px 0px;"> FCST </td>
					    	<td class="smBlue center" style="width: 7% !important; font-size: 16px;"> SNAPSHOT </td>
					    	<td class="smBlue center" style="width: 7% !important; font-size: 16px;"> BKGS </td>
					    	<td class="smBlue center" style="width: 7% !important; font-size: 16px; border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;"> BKGS </td>
					    	<td class="smBlue center" style="width: 3% !important; font-size: 16px;"> PLAN </td>
					    	<td class="smBlue center" style="width: 3% !important; font-size: 16px;"> FCST </td>
					    	<td class="smBlue center" style="width: 3% !important; font-size: 16px;"> SNAPSHOT </td>
					    	<td class="smBlue center" style="width: 3% !important; font-size: 16px;"> BKGS {{$pYear}} </td>
					    	<td class="smBlue center" style="width: 3% !important; font-size: 16px; border-style:solid; border-color:black; border-width: 0px 1px 0px 0px;"> BKGS {{$ppYear}} </td>
					    </tr>
					    @for($m = 0; $m < 3; $m++)
					    	<div style="display: none;"> {{ $monthForm = $base->intToMonth(array($month + $m))[0]}}</div>	
							 <tr>
							 	<td class="oddGrey center" style="width: 7% !important;  font-size: 16px;  border-style:solid; border-color:black; border-width: 1px;" rowspan="4"> {{$monthForm}} </td>
						    	<tr>
						    		<td class="odd center" style="width: 7% !important;  font-size: 16px; border-style:solid; border-color:black; border-width: 1px 1px 0px 1px;"> TV </td>
						    		<td class="odd center" style="width: 7% !important;  font-size: 16px; border-style:solid; border-color:black; border-width: 1px 0px 0px 0px;"> {{number_format(($total[$m][0]['currentYTD']),0,',','.')}} </td>
						    		<td class="odd center" style="width: 7% !important;  font-size: 16px; border-style:solid; border-color:black; border-width: 1px 0px 0px 0px;"> {{number_format($total[$m][0]['currentPlan'],0,',','.')}} </td>
						    		<td class="odd center" style="width: 7% !important;  font-size: 16px; border-style:solid; border-color:black; border-width: 1px 1px 0px 0px;"> {{number_format($total[$m][0]['currentFcst'],0,',','.')}} </td>
						    		<td class="odd center" style="width: 7% !important;  font-size: 16px; border-style:solid; border-color:black; border-width: 1px 0px 0px 0px;"> {{number_format($total[$m][0]['previousSS'],0,',','.')}} </td>
						    		<td class="odd center" style="width: 7% !important;  font-size: 16px; border-style:solid; border-color:black; border-width: 1px 1px 0px 0px;"> {{number_format($total[$m][0]['previousSap'],0,',','.')}} </td>
						    		<td class="odd center" style="width: 7% !important;  font-size: 16px; border-style:solid; border-color:black; border-width: 1px 1px 0px 0px;"> {{number_format($total[$m][0]['pPSap'],0,',','.')}} </td>
						    		<td class="odd center" style="width: 3% !important;  font-size: 16px; border-style:solid; border-color:black; border-width: 1px 0px 0px 0px;"> {{number_format($total[$m][0]['currentPlanPercent'],0,',','.')}}% </td>
						    		<td class="odd center" style="width: 3% !important;  font-size: 16px; border-style:solid; border-color:black; border-width: 1px 0px 0px 0px;"> {{number_format($total[$m][0]['currentFcstPercent'],0,',','.')}}% </td>
						    		<td class="odd center" style="width: 3% !important;  font-size: 16px; border-style:solid; border-color:black; border-width: 1px 0px 0px 0px;"> {{number_format($total[$m][0]['ssPercent'],0,',','.')}}% </td>
						    		<td class="odd center" style="width: 3% !important;  font-size: 16px; border-style:solid; border-color:black; border-width: 1px 0px 0px 0px;"> {{number_format($total[$m][0]['pSapPercent'],0,',','.')}}% </td>
						    		<td class="odd center" style="width: 3% !important;  font-size: 16px; border-style:solid; border-color:black; border-width: 1px 1px 0px 0px;"> {{number_format($total[$m][0]['ppSapPercent'],0,',','.')}}% </td>
						    	</tr>
						    	<tr>
						    		<td class="even center" style="width: 7% !important;  font-size: 16px; border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;"> ONL </td>
						    		<td class="even center" style="width: 7% !important;  font-size: 16px;"> {{number_format(($total[$m][1]['currentYTD']),0,',','.')}} </td>
						    		<td class="even center" style="width: 7% !important;  font-size: 16px;"> {{number_format($total[$m][1]['currentPlan'],0,',','.')}} </td>
						    		<td class="even center" style="width: 7% !important;  font-size: 16px; border-style: solid; border-color:black; border-width: 0px 1px 0px 0px;">{{number_format($total[$m][1]['currentFcst'],0,',','.')}} </td>
						    		<td class="even center" style="width: 7% !important;  font-size: 16px;"> {{number_format($total[$m][1]['previousSS'],0,',','.')}}</td>
						    		<td class="even center" style="width: 7% !important;  font-size: 16px; border-style: solid; border-color:black; border-width: 0px 1px 0px 0px;">{{number_format($total[$m][1]['previousSap'],0,',','.')}} </td>
						    		<td class="even center" style="width: 7% !important;  font-size: 16px; border-style: solid; border-color:black; border-width: 0px 1px 0px 0px;">{{number_format($total[$m][1]['pPSap'],0,',','.')}} </td>
						    		<td class="even center" style="width: 3% !important;  font-size: 16px;">{{number_format($total[$m][1]['currentPlanPercent'],0,',','.')}}% </td>
						    		<td class="even center" style="width: 3% !important;  font-size: 16px;">{{number_format($total[$m][1]['currentFcstPercent'],0,',','.')}}% </td>
						    		<td class="even center" style="width: 3% !important;  font-size: 16px;">{{number_format($total[$m][1]['ssPercent'],0,',','.')}}% </td>
						    		<td class="even center" style="width: 3% !important;  font-size: 16px;">{{number_format($total[$m][1]['pSapPercent'],0,',','.')}}% </td>
						    		<td class="even center" style="width: 3% !important;  font-size: 16px; border-style:solid; border-color:black; border-width: 0px 1px 0px 0px;">{{number_format($total[$m][1]['ppSapPercent'],0,',','.')}}% </td>	
						    	</tr>				    	
						    	<tr>
						    		<td class="odd center" style="width: 7% !important;  font-size: 16px; border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;"> TOTAL</td>
						    		<td class="odd center" style="width: 7% !important;  font-size: 16px; "> {{number_format(($total[$m][2]['currentYTD']),0,',','.')}} </td>
						    		<td class="odd center" style="width: 7% !important;  font-size: 16px; "> {{number_format($total[$m][2]['currentPlan'],0,',','.')}} </td>
						    		<td class="odd center" style="width: 7% !important;  font-size: 16px; border-style: solid; border-color:black; border-width: 0px 1px 0px 0px;"> {{number_format($total[$m][2]['currentFcst'],0,',','.')}} </td>
						    		<td class="odd center" style="width: 7% !important;  font-size: 16px; "> {{number_format($total[$m][2]['previousSS'],0,',','.')}} </td>
						    		<td class="odd center" style="width: 7% !important;  font-size: 16px; border-style: solid; border-color:black; border-width: 0px 1px 0px 0px;"> {{number_format($total[$m][2]['previousSap'],0,',','.')}} </td>
						    		<td class="odd center" style="width: 7% !important;  font-size: 16px; border-style: solid; border-color:black; border-width: 0px 1px 0px 0px;"> {{number_format($total[$m][2]['pPSap'],0,',','.')}} </td>
						    		<td class="odd center" style="width: 3% !important;  font-size: 16px; "> {{number_format($total[$m][2]['currentPlanPercent'],0,',','.')}}% </td>
						    		<td class="odd center" style="width: 3% !important;  font-size: 16px; "> {{number_format($total[$m][2]['currentFcstPercent'],0,',','.')}}% </td>
						    		<td class="odd center" style="width: 3% !important;  font-size: 16px; "> {{number_format($total[$m][2]['ssPercent'],0,',','.')}}% </td>
						    		<td class="odd center" style="width: 3% !important;  font-size: 16px; "> {{number_format($total[$m][2]['pSapPercent'],0,',','.')}}% </td>
						    		<td class="odd center" style="width: 3% !important;  font-size: 16px; border-style:solid; border-color:black; border-width: 0px 1px 0px 0px;"> {{number_format($total[$m][2]['ppSapPercent'],0,',','.')}}% </td>
						    	</tr>				    	
						    </tr>
						@endfor
						<tr>
					    	<td class="oddGrey center" style="width: 7% !important;  font-size: 16px; border-style:solid; border-color:black; border-width: 1px;" rowspan="4"> (JAN-{{$actualMonth}})  </td>
					    	<tr>
					    		<td class="odd center" style="width: 7% !important;  font-size: 16px; border-style:solid; border-color:black; border-width: 1px 1px 0px 1px;"> TV </td>
						    		<td class="odd center" style="width: 7% !important;  font-size: 16px; border-style:solid; border-color:black; border-width: 1px 0px 0px 0px;"> {{number_format(($total[3][0]['currentYTD']),0,',','.')}} </td>
						    		<td class="odd center" style="width: 7% !important;  font-size: 16px; border-style:solid; border-color:black; border-width: 1px 0px 0px 0px;"> {{number_format($total[3][0]['currentPlan'],0,',','.')}} </td>
						    		<td class="odd center" style="width: 7% !important;  font-size: 16px; border-style:solid; border-color:black; border-width: 1px 1px 0px 0px;"> {{number_format($total[3][0]['currentFcst'],0,',','.')}} </td>
						    		<td class="odd center" style="width: 7% !important;  font-size: 16px; border-style:solid; border-color:black; border-width: 1px 0px 0px 0px;"> {{number_format($total[3][0]['previousSS'],0,',','.')}} </td>
						    		<td class="odd center" style="width: 7% !important;  font-size: 16px; border-style:solid; border-color:black; border-width: 1px 1px 0px 0px;"> {{number_format($total[3][0]['previousSap'],0,',','.')}} </td>
						    		<td class="odd center" style="width: 7% !important;  font-size: 16px; border-style:solid; border-color:black; border-width: 1px 1px 0px 0px;"> {{number_format($total[3][0]['pPSap'],0,',','.')}} </td>
						    		<td class="odd center" style="width: 3% !important;  font-size: 16px; border-style:solid; border-color:black; border-width: 1px 0px 0px 0px;"> {{number_format($total[3][0]['currentPlanPercent'],0,',','.')}}% </td>
						    		<td class="odd center" style="width: 3% !important;  font-size: 16px; border-style:solid; border-color:black; border-width: 1px 0px 0px 0px;"> {{number_format($total[3][0]['currentFcstPercent'],0,',','.')}}% </td>
						    		<td class="odd center" style="width: 3% !important;  font-size: 16px; border-style:solid; border-color:black; border-width: 1px 0px 0px 0px;"> {{number_format($total[3][0]['ssPercent'],0,',','.')}}% </td>
						    		<td class="odd center" style="width: 3% !important;  font-size: 16px; border-style:solid; border-color:black; border-width: 1px 0px 0px 0px;"> {{number_format($total[3][0]['pSapPercent'],0,',','.')}}% </td>
						    		<td class="odd center" style="width: 3% !important;  font-size: 16px; border-style:solid; border-color:black; border-width: 1px 1px 0px 0px;"> {{number_format($total[3][0]['ppSapPercent'],0,',','.')}}% </td>
						    	</tr>
						    	<tr>
						    		<td class="even center" style="width: 7% !important;  font-size: 16px; border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;"> ONL </td>
						    		<td class="even center" style="width: 7% !important;  font-size: 16px;"> {{number_format(($total[3][1]['currentYTD']),0,',','.')}} </td>
						    		<td class="even center" style="width: 7% !important;  font-size: 16px;"> {{number_format($total[3][1]['currentPlan'],0,',','.')}} </td>
						    		<td class="even center" style="width: 7% !important;  font-size: 16px; border-style: solid; border-color:black; border-width: 0px 1px 0px 0px;">{{number_format($total[3][1]['currentFcst'],0,',','.')}} </td>
						    		<td class="even center" style="width: 7% !important;  font-size: 16px;"> {{number_format($total[3][1]['previousSS'],0,',','.')}}</td>
						    		<td class="even center" style="width: 7% !important;  font-size: 16px; border-style: solid; border-color:black; border-width: 0px 1px 0px 0px;">{{number_format($total[3][1]['previousSap'],0,',','.')}} </td>
						    		<td class="even center" style="width: 7% !important;  font-size: 16px; border-style: solid; border-color:black; border-width: 0px 1px 0px 0px;">{{number_format($total[3][1]['pPSap'],0,',','.')}} </td>
						    		<td class="even center" style="width: 3% !important;  font-size: 16px;">{{number_format($total[3][1]['currentPlanPercent'],0,',','.')}}% </td>
						    		<td class="even center" style="width: 3% !important;  font-size: 16px;">{{number_format($total[3][1]['currentFcstPercent'],0,',','.')}}% </td>
						    		<td class="even center" style="width: 3% !important;  font-size: 16px;">{{number_format($total[3][1]['ssPercent'],0,',','.')}}% </td>
						    		<td class="even center" style="width: 3% !important;  font-size: 16px;">{{number_format($total[3][1]['pSapPercent'],0,',','.')}}% </td>
						    		<td class="even center" style="width: 3% !important;  font-size: 16px; border-style:solid; border-color:black; border-width: 0px 1px 0px 0px;">{{number_format($total[3][1]['ppSapPercent'],0,',','.')}}% </td>
						    	</tr>				    	
						    	<tr>
						    		<td class="odd center" style="width: 7% !important;  font-size: 16px; border-style:solid; border-color:black; border-width: 0px 1px 1px 1px;"> TOTAL</td>
						    		<td class="odd center" style="width: 7% !important;  font-size: 16px; border-style:solid; border-color:black; border-width: 0px 0px 1px 0px;"> {{number_format(($total[3][2]['currentYTD']),0,',','.')}} </td>
						    		<td class="odd center" style="width: 7% !important;  font-size: 16px; border-style:solid; border-color:black; border-width: 0px 0px 1px 0px;"> {{number_format($total[3][2]['currentPlan'],0,',','.')}} </td>
						    		<td class="odd center" style="width: 7% !important;  font-size: 16px; border-style: solid; border-color:black; border-width: 0px 1px 1px 0px;"> {{number_format($total[3][2]['currentFcst'],0,',','.')}} </td>
						    		<td class="odd center" style="width: 7% !important;  font-size: 16px; border-style:solid; border-color:black; border-width: 0px 0px 1px 0px;"> {{number_format($total[3][2]['previousSS'],0,',','.')}} </td>
						    		<td class="odd center" style="width: 7% !important;  font-size: 16px; border-style: solid; border-color:black; border-width: 0px 1px 1px 0px;"> {{number_format($total[3][2]['previousSap'],0,',','.')}} </td>
						    		<td class="odd center" style="width: 7% !important;  font-size: 16px; border-style: solid; border-color:black; border-width: 0px 1px 1px 0px;"> {{number_format($total[3][2]['pPSap'],0,',','.')}} </td>
						    		<td class="odd center" style="width: 3% !important;  font-size: 16px; border-style:solid; border-color:black; border-width: 0px 0px 1px 0px;"> {{number_format($total[3][2]['currentPlanPercent'],0,',','.')}}% </td>
						    		<td class="odd center" style="width: 3% !important;  font-size: 16px; border-style:solid; border-color:black; border-width: 0px 0px 1px 0px;"> {{number_format($total[3][2]['currentFcstPercent'],0,',','.')}}% </td>
						    		<td class="odd center" style="width: 3% !important;  font-size: 16px; border-style:solid; border-color:black; border-width: 0px 0px 1px 0px;"> {{number_format($total[3][2]['ssPercent'],0,',','.')}}% </td>
						    		<td class="odd center" style="width: 3% !important;  font-size: 16px; border-style:solid; border-color:black; border-width: 0px 0px 1px 0px;"> {{number_format($total[3][2]['pSapPercent'],0,',','.')}}% </td>
						    		<td class="odd center" style="width: 3% !important;  font-size: 16px; border-style:solid; border-color:black; border-width: 0px 1px 1px 0px;"> {{number_format($total[3][2]['ppSapPercent'],0,',','.')}}% </td>
						    	</tr>
					    	</tr>
					    </tr>
					</table>

					<table style='width: 100%; zoom: 85%;font-size: 16px;'>
						<tr class="center">
			        		<td style="width: 7% !important; background-color: white;"> &nbsp; </td>
			        	</tr>
			        </table>

					<table style='width: 100%; zoom: 85%;'>
						<tr class="center">
					        <td class='dc center' style="width: 100% !important; font-size: 22px; border-style:solid; border-color:black; border-width: 1px 1px 0px 1px;" colspan="13"> DISCOVERY ( {{$currencyName}} / {{strtoupper($value)}} )</td>
					    </tr>
					    <tr>
					    	<td class='grey center' style="width: 7% !important; font-size: 16px; border-style:solid; border-color:black; border-width: 1px 0px 1px 1px;"> LOG </td>
					    	<td class="grey center" style="width: 7% !important; font-size: 16px; border-style:solid; border-color:black; border-width: 1px 1px 1px 0px;"> {{$realDate}} </td>
					    	<td class="grey center" style="width: 7% !important; font-size: 16px; border-style:solid; border-color:black; border-width: 1px 1px 1px 1px;" colspan="3"> {{$cYear}}</td>
					    	<td class="grey center" style="width: 7% !important; font-size: 16px; border-style:solid; border-color:black; border-width: 1px 1px 1px 1px;" colspan="2"> {{$pYear}}</td>
					    	<td class="grey center" style="width: 7% !important; font-size: 16px; border-style:solid; border-color:black; border-width: 1px 1px 1px 1px;" colspan="1"> {{$ppYear}}</td>
					    	<td class="grey center" style="width: 7% !important; font-size: 16px; border-style:solid; border-color:black; border-width: 1px 1px 1px 1px;" colspan="5"> {{$cYear}} VAR (%) </td>
					    </tr>				   
					    <tr>
					    	<td class="lightGrey center" style="width: 7% !important; font-size: 16px; border-style:solid; border-color:black; border-width: 1px;"> MONTH </td>
					    	<td class="lightGrey center" style="width: 7% !important; font-size: 16px; border-style:solid; border-color:black; border-width: 1px;"> PLATAFORM </td>
					    	@if($regionID == "1")
					    		<td class="smBlue center" style="width: 7% !important; font-size: 16px;"> BKGS </td>
					    	@else
					    	   	<td class="smBlue center" style="width: 7% !important; font-size: 16px;"> YTD </td>
					    	@endif
					    	<td class="smBlue center" style="width: 7% !important; font-size: 16px;"> PLAN </td>
					    	<td class="smBlue center" style="width: 7% !important; font-size: 16px; border-style:solid; border-color:black; border-width: 0px 1px 0px 0px;"> FCST </td>
					    	<td class="smBlue center" style="width: 7% !important; font-size: 16px;"> SNAPSHOT </td>
					    	<td class="smBlue center" style="width: 7% !important; font-size: 16px;"> BKGS </td>
					    	<td class="smBlue center" style="width: 7% !important; font-size: 16px; border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;"> BKGS </td>
					    	<td class="smBlue center" style="width: 3% !important; font-size: 16px;"> PLAN </td>
					    	<td class="smBlue center" style="width: 3% !important; font-size: 16px;"> FCST </td>
					    	<td class="smBlue center" style="width: 3% !important; font-size: 16px;"> SNAPSHOT </td>
					    	<td class="smBlue center" style="width: 3% !important; font-size: 16px;"> BKGS {{$pYear}} </td>
					    	<td class="smBlue center" style="width: 3% !important; font-size: 16px; border-style:solid; border-color:black; border-width: 0px 1px 0px 0px;"> BKGS {{$ppYear}} </td>
					    </tr>
					    @for($m = 0; $m < 3; $m++)
					    <div style="display: none;"> {{ $monthForm = $base->intToMonth(array($month + $m))[0]}}</div>	
							 <tr>
							 	<td class="oddGrey center" style="width: 7% !important;  font-size: 16px;  border-style:solid; border-color:black; border-width: 1px;" rowspan="4"> {{$monthForm}} </td>
						    	<tr>
						    		<td class="odd center" style="width: 7% !important;  font-size: 16px; border-style:solid; border-color:black; border-width: 1px 1px 0px 1px;"> TV </td>
						    		<td class="odd center" style="width: 7% !important;  font-size: 16px; border-style:solid; border-color:black; border-width: 1px 0px 0px 0px;"> {{number_format($disc[$m][0]['currentYTD'],0,',','.')}} </td>
						    		<td class="odd center" style="width: 7% !important;  font-size: 16px; border-style:solid; border-color:black; border-width: 1px 0px 0px 0px;"> {{number_format($disc[$m][0]['currentPlan'],0,',','.')}} </td>
						    		<td class="odd center" style="width: 7% !important;  font-size: 16px; border-style:solid; border-color:black; border-width: 1px 1px 0px 0px;"> {{number_format($disc[$m][0]['currentFcst'],0,',','.')}} </td>
						    		<td class="odd center" style="width: 7% !important;  font-size: 16px; border-style:solid; border-color:black; border-width: 1px 0px 0px 0px;"> {{number_format($disc[$m][0]['previousSS'],0,',','.')}} </td>
						    		<td class="odd center" style="width: 7% !important;  font-size: 16px; border-style:solid; border-color:black; border-width: 1px 1px 0px 0px;"> {{number_format($disc[$m][0]['previousSap'],0,',','.')}} </td>
						    		<td class="odd center" style="width: 7% !important;  font-size: 16px; border-style:solid; border-color:black; border-width: 1px 1px 0px 0px;"> {{number_format($disc[$m][0]['pPSap'],0,',','.')}} </td>
						    		<td class="odd center" style="width: 3% !important;  font-size: 16px; border-style:solid; border-color:black; border-width: 1px 0px 0px 0px;"> {{number_format($disc[$m][0]['currentPlanPercent'],0,',','.')}}% </td>
						    		<td class="odd center" style="width: 3% !important;  font-size: 16px; border-style:solid; border-color:black; border-width: 1px 0px 0px 0px;"> {{number_format($disc[$m][0]['currentFcstPercent'],0,',','.')}}% </td>
						    		<td class="odd center" style="width: 3% !important;  font-size: 16px; border-style:solid; border-color:black; border-width: 1px 0px 0px 0px;"> {{number_format($disc[$m][0]['ssPercent'],0,',','.')}}% </td>
						    		<td class="odd center" style="width: 3% !important;  font-size: 16px; border-style:solid; border-color:black; border-width: 1px 0px 0px 0px;"> {{number_format($disc[$m][0]['pSapPercent'],0,',','.')}}% </td>
						    		<td class="odd center" style="width: 3% !important;  font-size: 16px; border-style:solid; border-color:black; border-width: 1px 1px 0px 0px;"> {{number_format($disc[$m][0]['ppSapPercent'],0,',','.')}}% </td>
						    	</tr>
						    	<tr>
						    		<td class="even center" style="width: 7% !important;  font-size: 16px; border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;"> ONL </td>
						    		<td class="even center" style="width: 7% !important;  font-size: 16px;"> {{number_format($disc[$m][1]['currentYTD'],0,',','.')}} </td>
						    		<td class="even center" style="width: 7% !important;  font-size: 16px;"> {{number_format($disc[$m][1]['currentPlan'],0,',','.')}} </td>
						    		<td class="even center" style="width: 7% !important;  font-size: 16px; border-style: solid; border-color:black; border-width: 0px 1px 0px 0px;">{{number_format($disc[$m][1]['currentFcst'],0,',','.')}} </td>
						    		<td class="even center" style="width: 7% !important;  font-size: 16px;"> {{number_format($disc[$m][1]['previousSS'],0,',','.')}}</td>
						    		<td class="even center" style="width: 7% !important;  font-size: 16px; border-style: solid; border-color:black; border-width: 0px 1px 0px 0px;">{{number_format($disc[$m][1]['previousSap'],0,',','.')}} </td>
						    		<td class="even center" style="width: 7% !important;  font-size: 16px; border-style: solid; border-color:black; border-width: 0px 1px 0px 0px;">{{number_format($disc[$m][1]['pPSap'],0,',','.')}} </td>
						    		<td class="even center" style="width: 3% !important;  font-size: 16px;">{{number_format($disc[$m][1]['currentPlanPercent'],0,',','.')}}% </td>
						    		<td class="even center" style="width: 3% !important;  font-size: 16px;">{{number_format($disc[$m][1]['currentFcstPercent'],0,',','.')}}% </td>
						    		<td class="even center" style="width: 3% !important;  font-size: 16px;">{{number_format($disc[$m][1]['ssPercent'],0,',','.')}}% </td>
						    		<td class="even center" style="width: 3% !important;  font-size: 16px;">{{number_format($disc[$m][1]['pSapPercent'],0,',','.')}}% </td>
						    		<td class="even center" style="width: 3% !important;  font-size: 16px; border-style:solid; border-color:black; border-width: 0px 1px 0px 0px;">{{number_format($disc[$m][1]['ppSapPercent'],0,',','.')}}% </td>	
						    	</tr>				    	
						    	<tr>
						    		<td class="odd center" style="width: 7% !important;  font-size: 16px; border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;"> TOTAL</td>
						    		<td class="odd center" style="width: 7% !important;  font-size: 16px; "> {{number_format($disc[$m][2]['currentYTD'],0,',','.')}} </td>
						    		<td class="odd center" style="width: 7% !important;  font-size: 16px; "> {{number_format($disc[$m][2]['currentPlan'],0,',','.')}} </td>
						    		<td class="odd center" style="width: 7% !important;  font-size: 16px; border-style: solid; border-color:black; border-width: 0px 1px 0px 0px;"> {{number_format($disc[$m][2]['currentFcst'],0,',','.')}} </td>
						    		<td class="odd center" style="width: 7% !important;  font-size: 16px; "> {{number_format($disc[$m][2]['previousSS'],0,',','.')}} </td>
						    		<td class="odd center" style="width: 7% !important;  font-size: 16px; border-style: solid; border-color:black; border-width: 0px 1px 0px 0px;"> {{number_format($disc[$m][2]['previousSap'],0,',','.')}} </td>
						    		<td class="odd center" style="width: 7% !important;  font-size: 16px; border-style: solid; border-color:black; border-width: 0px 1px 0px 0px;"> {{number_format($disc[$m][2]['pPSap'],0,',','.')}} </td>
						    		<td class="odd center" style="width: 3% !important;  font-size: 16px; "> {{number_format($disc[$m][2]['currentPlanPercent'],0,',','.')}}% </td>
						    		<td class="odd center" style="width: 3% !important;  font-size: 16px; "> {{number_format($disc[$m][2]['currentFcstPercent'],0,',','.')}}% </td>
						    		<td class="odd center" style="width: 3% !important;  font-size: 16px; "> {{number_format($disc[$m][2]['ssPercent'],0,',','.')}}% </td>
						    		<td class="odd center" style="width: 3% !important;  font-size: 16px; "> {{number_format($disc[$m][2]['pSapPercent'],0,',','.')}}% </td>
						    		<td class="odd center" style="width: 3% !important;  font-size: 16px; border-style:solid; border-color:black; border-width: 0px 1px 0px 0px;"> {{number_format($disc[$m][2]['ppSapPercent'],0,',','.')}}% </td>
						    	</tr>				    	
						    </tr>
						@endfor
						<tr>
					    	<td class="oddGrey center" style="width: 7% !important;  font-size: 16px; border-style:solid; border-color:black; border-width: 1px;" rowspan="4"> (JAN-{{$actualMonth}}) </td>
					    	<tr>
					    		<td class="odd center" style="width: 7% !important;  font-size: 16px; border-style:solid; border-color:black; border-width: 1px 1px 0px 1px;"> TV </td>
						    		<td class="odd center" style="width: 7% !important;  font-size: 16px; border-style:solid; border-color:black; border-width: 1px 0px 0px 0px;"> {{number_format($disc[3][0]['currentYTD'],0,',','.')}} </td>
						    		<td class="odd center" style="width: 7% !important;  font-size: 16px; border-style:solid; border-color:black; border-width: 1px 0px 0px 0px;"> {{number_format($disc[3][0]['currentPlan'],0,',','.')}} </td>
						    		<td class="odd center" style="width: 7% !important;  font-size: 16px; border-style:solid; border-color:black; border-width: 1px 1px 0px 0px;"> {{number_format($disc[3][0]['currentFcst'],0,',','.')}} </td>
						    		<td class="odd center" style="width: 7% !important;  font-size: 16px; border-style:solid; border-color:black; border-width: 1px 0px 0px 0px;"> {{number_format($disc[3][0]['previousSS'],0,',','.')}} </td>
						    		<td class="odd center" style="width: 7% !important;  font-size: 16px; border-style:solid; border-color:black; border-width: 1px 1px 0px 0px;"> {{number_format($disc[3][0]['previousSap'],0,',','.')}} </td>
						    		<td class="odd center" style="width: 7% !important;  font-size: 16px; border-style:solid; border-color:black; border-width: 1px 1px 0px 0px;"> {{number_format($disc[3][0]['pPSap'],0,',','.')}} </td>
						    		<td class="odd center" style="width: 3% !important;  font-size: 16px; border-style:solid; border-color:black; border-width: 1px 0px 0px 0px;"> {{number_format($disc[3][0]['currentPlanPercent'],0,',','.')}}% </td>
						    		<td class="odd center" style="width: 3% !important;  font-size: 16px; border-style:solid; border-color:black; border-width: 1px 0px 0px 0px;"> {{number_format($disc[3][0]['currentFcstPercent'],0,',','.')}}% </td>
						    		<td class="odd center" style="width: 3% !important;  font-size: 16px; border-style:solid; border-color:black; border-width: 1px 0px 0px 0px;"> {{number_format($disc[3][0]['ssPercent'],0,',','.')}}% </td>
						    		<td class="odd center" style="width: 3% !important;  font-size: 16px; border-style:solid; border-color:black; border-width: 1px 0px 0px 0px;"> {{number_format($disc[3][0]['pSapPercent'],0,',','.')}}% </td>
						    		<td class="odd center" style="width: 3% !important;  font-size: 16px; border-style:solid; border-color:black; border-width: 1px 1px 0px 0px;"> {{number_format($disc[3][0]['ppSapPercent'],0,',','.')}}% </td>
						    	</tr>
						    	<tr>
						    		<td class="even center" style="width: 7% !important;  font-size: 16px; border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;"> ONL </td>
						    		<td class="even center" style="width: 7% !important;  font-size: 16px;"> {{number_format($disc[3][1]['currentYTD'],0,',','.')}} </td>
						    		<td class="even center" style="width: 7% !important;  font-size: 16px;"> {{number_format($disc[3][1]['currentPlan'],0,',','.')}} </td>
						    		<td class="even center" style="width: 7% !important;  font-size: 16px; border-style: solid; border-color:black; border-width: 0px 1px 0px 0px;">{{number_format($disc[3][1]['currentFcst'],0,',','.')}} </td>
						    		<td class="even center" style="width: 7% !important;  font-size: 16px;"> {{number_format($disc[3][1]['previousSS'],0,',','.')}}</td>
						    		<td class="even center" style="width: 7% !important;  font-size: 16px; border-style: solid; border-color:black; border-width: 0px 1px 0px 0px;">{{number_format($disc[3][1]['previousSap'],0,',','.')}} </td>
						    		<td class="even center" style="width: 7% !important;  font-size: 16px; border-style: solid; border-color:black; border-width: 0px 1px 0px 0px;">{{number_format($disc[3][1]['pPSap'],0,',','.')}} </td>
						    		<td class="even center" style="width: 3% !important;  font-size: 16px;">{{number_format($disc[3][1]['currentPlanPercent'],0,',','.')}}% </td>
						    		<td class="even center" style="width: 3% !important;  font-size: 16px;">{{number_format($disc[3][1]['currentFcstPercent'],0,',','.')}}% </td>
						    		<td class="even center" style="width: 3% !important;  font-size: 16px;">{{number_format($disc[3][1]['ssPercent'],0,',','.')}}% </td>
						    		<td class="even center" style="width: 3% !important;  font-size: 16px;">{{number_format($disc[3][1]['pSapPercent'],0,',','.')}}% </td>
						    		<td class="even center" style="width: 3% !important;  font-size: 16px; border-style:solid; border-color:black; border-width: 0px 1px 0px 0px;">{{number_format($disc[3][1]['ppSapPercent'],0,',','.')}}% </td>
						    	</tr>				    	
						    	<tr>
						    		<td class="odd center" style="width: 7% !important;  font-size: 16px; border-style:solid; border-color:black; border-width: 0px 1px 1px 1px;"> TOTAL</td>
						    		<td class="odd center" style="width: 7% !important;  font-size: 16px; border-style:solid; border-color:black; border-width: 0px 0px 1px 0px;"> {{number_format($disc[3][2]['currentYTD'],0,',','.')}} </td>
						    		<td class="odd center" style="width: 7% !important;  font-size: 16px; border-style:solid; border-color:black; border-width: 0px 0px 1px 0px;"> {{number_format($disc[3][2]['currentPlan'],0,',','.')}} </td>
						    		<td class="odd center" style="width: 7% !important;  font-size: 16px; border-style: solid; border-color:black; border-width: 0px 1px 1px 0px;"> {{number_format($disc[3][2]['currentFcst'],0,',','.')}} </td>
						    		<td class="odd center" style="width: 7% !important;  font-size: 16px; border-style:solid; border-color:black; border-width: 0px 0px 1px 0px;"> {{number_format($disc[3][2]['previousSS'],0,',','.')}} </td>
						    		<td class="odd center" style="width: 7% !important;  font-size: 16px; border-style: solid; border-color:black; border-width: 0px 1px 1px 0px;"> {{number_format($disc[3][2]['previousSap'],0,',','.')}} </td>
						    		<td class="odd center" style="width: 7% !important;  font-size: 16px; border-style: solid; border-color:black; border-width: 0px 1px 1px 0px;"> {{number_format($disc[3][2]['pPSap'],0,',','.')}} </td>
						    		<td class="odd center" style="width: 3% !important;  font-size: 16px; border-style:solid; border-color:black; border-width: 0px 0px 1px 0px;"> {{number_format($disc[3][2]['currentPlanPercent'],0,',','.')}}% </td>
						    		<td class="odd center" style="width: 3% !important;  font-size: 16px; border-style:solid; border-color:black; border-width: 0px 0px 1px 0px;"> {{number_format($disc[3][2]['currentFcstPercent'],0,',','.')}}% </td>
						    		<td class="odd center" style="width: 3% !important;  font-size: 16px; border-style:solid; border-color:black; border-width: 0px 0px 1px 0px;"> {{number_format($disc[3][2]['ssPercent'],0,',','.')}}% </td>
						    		<td class="odd center" style="width: 3% !important;  font-size: 16px; border-style:solid; border-color:black; border-width: 0px 0px 1px 0px;"> {{number_format($disc[3][2]['pSapPercent'],0,',','.')}}% </td>
						    		<td class="odd center" style="width: 3% !important;  font-size: 16px; border-style:solid; border-color:black; border-width: 0px 1px 1px 0px;"> {{number_format($disc[3][2]['ppSapPercent'],0,',','.')}}% </td>
						    	</tr>
					    	</tr>
					    </tr>
					</table>

					<table style='width: 100%; zoom: 85%;font-size: 16px;'>
						<tr class="center">
			        		<td style="width: 7% !important; background-color: white;"> &nbsp; </td>
			        	</tr>
			        </table>

					<table style='width: 100%; zoom: 85%;'>
						<tr class="center">
					        <td class='sony center' style="width: 100% !important; font-size: 22px; border-style:solid; border-color:black; border-width: 1px 1px 0px 1px;" colspan="13"> SPT ( {{$currencyName}} / {{strtoupper($value)}} )</td>
					    </tr>
					    <tr>
					    	<td class='grey center' style="width: 7% !important; font-size: 16px; border-style:solid; border-color:black; border-width: 1px 0px 1px 1px;"> LOG </td>
					    	<td class="grey center" style="width: 7% !important; font-size: 16px; border-style:solid; border-color:black; border-width: 1px 1px 1px 0px;"> {{$realDate}} </td>
					    	<td class="grey center" style="width: 7% !important; font-size: 16px; border-style:solid; border-color:black; border-width: 1px 1px 1px 1px;" colspan="3"> {{$cYear}}</td>
					    	<td class="grey center" style="width: 7% !important; font-size: 16px; border-style:solid; border-color:black; border-width: 1px 1px 1px 1px;" colspan="2"> {{$pYear}}</td>
					    	<td class="grey center" style="width: 7% !important; font-size: 16px; border-style:solid; border-color:black; border-width: 1px 1px 1px 1px;" colspan="1"> {{$ppYear}}</td>
					    	<td class="grey center" style="width: 7% !important; font-size: 16px; border-style:solid; border-color:black; border-width: 1px 1px 1px 1px;" colspan="5"> {{$cYear}} VAR (%) </td>
					    </tr>				   
					    <tr>
					    	<td class="lightGrey center" style="width: 7% !important; font-size: 16px; border-style:solid; border-color:black; border-width: 1px;"> MONTH </td>
					    	<td class="lightGrey center" style="width: 7% !important; font-size: 16px; border-style:solid; border-color:black; border-width: 1px;"> PLATAFORM </td>
					    	@if($regionID == "1")
					    		<td class="smBlue center" style="width: 7% !important; font-size: 16px;"> BKGS </td>
					    	@else
					    	   	<td class="smBlue center" style="width: 7% !important; font-size: 16px;"> YTD </td>
					    	@endif
					    	<td class="smBlue center" style="width: 7% !important; font-size: 16px;"> PLAN </td>
					    	<td class="smBlue center" style="width: 7% !important; font-size: 16px; border-style:solid; border-color:black; border-width: 0px 1px 0px 0px;"> FCST </td>
					    	<td class="smBlue center" style="width: 7% !important; font-size: 16px;"> SNAPSHOT </td>
					    	<td class="smBlue center" style="width: 7% !important; font-size: 16px;"> BKGS </td>
					    	<td class="smBlue center" style="width: 7% !important; font-size: 16px; border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;"> BKGS </td>
					    	<td class="smBlue center" style="width: 3% !important; font-size: 16px;"> PLAN </td>
					    	<td class="smBlue center" style="width: 3% !important; font-size: 16px;"> FCST </td>
					    	<td class="smBlue center" style="width: 3% !important; font-size: 16px;"> SNAPSHOT </td>
					    	<td class="smBlue center" style="width: 3% !important; font-size: 16px;"> BKGS {{$pYear}} </td>
					    	<td class="smBlue center" style="width: 3% !important; font-size: 16px; border-style:solid; border-color:black; border-width: 0px 1px 0px 0px;"> BKGS {{$ppYear}} </td>
					    </tr>
					    @for($m = 0; $m < 3; $m++)
					    <div style="display: none;"> {{ $monthForm = $base->intToMonth(array($month + $m))[0]}}</div>	
							 <tr>
							 	<td class="oddGrey center" style="width: 7% !important;  font-size: 16px;  border-style:solid; border-color:black; border-width: 1px;" rowspan="4"> {{$monthForm}} </td>
						    	<tr>
						    		<td class="odd center" style="width: 7% !important;  font-size: 16px; border-style:solid; border-color:black; border-width: 1px 1px 0px 1px;"> TV </td>
						    		<td class="odd center" style="width: 7% !important;  font-size: 16px; border-style:solid; border-color:black; border-width: 1px 0px 0px 0px;"> {{number_format($sony[$m][0]['currentYTD'],0,',','.')}} </td>
						    		<td class="odd center" style="width: 7% !important;  font-size: 16px; border-style:solid; border-color:black; border-width: 1px 0px 0px 0px;"> {{number_format($sony[$m][0]['currentPlan'],0,',','.')}} </td>
						    		<td class="odd center" style="width: 7% !important;  font-size: 16px; border-style:solid; border-color:black; border-width: 1px 1px 0px 0px;"> {{number_format($sony[$m][0]['currentFcst'],0,',','.')}} </td>
						    		<td class="odd center" style="width: 7% !important;  font-size: 16px; border-style:solid; border-color:black; border-width: 1px 0px 0px 0px;"> {{number_format($sony[$m][0]['previousSS'],0,',','.')}} </td>
						    		<td class="odd center" style="width: 7% !important;  font-size: 16px; border-style:solid; border-color:black; border-width: 1px 1px 0px 0px;"> {{number_format($sony[$m][0]['previousSap'],0,',','.')}} </td>
						    		<td class="odd center" style="width: 7% !important;  font-size: 16px; border-style:solid; border-color:black; border-width: 1px 1px 0px 0px;"> {{number_format($sony[$m][0]['pPSap'],0,',','.')}} </td>
						    		<td class="odd center" style="width: 3% !important;  font-size: 16px; border-style:solid; border-color:black; border-width: 1px 0px 0px 0px;"> {{number_format($sony[$m][0]['currentPlanPercent'],0,',','.')}}% </td>
						    		<td class="odd center" style="width: 3% !important;  font-size: 16px; border-style:solid; border-color:black; border-width: 1px 0px 0px 0px;"> {{number_format($sony[$m][0]['currentFcstPercent'],0,',','.')}}% </td>
						    		<td class="odd center" style="width: 3% !important;  font-size: 16px; border-style:solid; border-color:black; border-width: 1px 0px 0px 0px;"> {{number_format($sony[$m][0]['ssPercent'],0,',','.')}}% </td>
						    		<td class="odd center" style="width: 3% !important;  font-size: 16px; border-style:solid; border-color:black; border-width: 1px 0px 0px 0px;"> {{number_format($sony[$m][0]['pSapPercent'],0,',','.')}}% </td>
						    		<td class="odd center" style="width: 3% !important;  font-size: 16px; border-style:solid; border-color:black; border-width: 1px 1px 0px 0px;"> {{number_format($sony[$m][0]['ppSapPercent'],0,',','.')}}% </td>
						    	</tr>
						    	<tr>
						    		<td class="even center" style="width: 7% !important;  font-size: 16px; border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;"> ONL </td>
						    		<td class="even center" style="width: 7% !important;  font-size: 16px;"> {{number_format($sony[$m][1]['currentYTD'],0,',','.')}} </td>
						    		<td class="even center" style="width: 7% !important;  font-size: 16px;"> {{number_format($sony[$m][1]['currentPlan'],0,',','.')}} </td>
						    		<td class="even center" style="width: 7% !important;  font-size: 16px; border-style: solid; border-color:black; border-width: 0px 1px 0px 0px;">{{number_format($sony[$m][1]['currentFcst'],0,',','.')}} </td>
						    		<td class="even center" style="width: 7% !important;  font-size: 16px;"> {{number_format($sony[$m][1]['previousSS'],0,',','.')}}</td>
						    		<td class="even center" style="width: 7% !important;  font-size: 16px; border-style: solid; border-color:black; border-width: 0px 1px 0px 0px;">{{number_format($sony[$m][1]['previousSap'],0,',','.')}} </td>
						    		<td class="even center" style="width: 7% !important;  font-size: 16px; border-style: solid; border-color:black; border-width: 0px 1px 0px 0px;">{{number_format($sony[$m][1]['pPSap'],0,',','.')}} </td>
						    		<td class="even center" style="width: 3% !important;  font-size: 16px;">{{number_format($sony[$m][1]['currentPlanPercent'],0,',','.')}}% </td>
						    		<td class="even center" style="width: 3% !important;  font-size: 16px;">{{number_format($sony[$m][1]['currentFcstPercent'],0,',','.')}}% </td>
						    		<td class="even center" style="width: 3% !important;  font-size: 16px;">{{number_format($sony[$m][1]['ssPercent'],0,',','.')}}% </td>
						    		<td class="even center" style="width: 3% !important;  font-size: 16px;">{{number_format($sony[$m][1]['pSapPercent'],0,',','.')}}% </td>
						    		<td class="even center" style="width: 3% !important;  font-size: 16px; border-style:solid; border-color:black; border-width: 0px 1px 0px 0px;">{{number_format($sony[$m][1]['ppSapPercent'],0,',','.')}}% </td>	
						    	</tr>				    	
						    	<tr>
						    		<td class="odd center" style="width: 7% !important;  font-size: 16px; border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;"> TOTAL</td>
						    		<td class="odd center" style="width: 7% !important;  font-size: 16px; "> {{number_format($sony[$m][2]['currentYTD'],0,',','.')}} </td>
						    		<td class="odd center" style="width: 7% !important;  font-size: 16px; "> {{number_format($sony[$m][2]['currentPlan'],0,',','.')}} </td>
						    		<td class="odd center" style="width: 7% !important;  font-size: 16px; border-style: solid; border-color:black; border-width: 0px 1px 0px 0px;"> {{number_format($sony[$m][2]['currentFcst'],0,',','.')}} </td>
						    		<td class="odd center" style="width: 7% !important;  font-size: 16px; "> {{number_format($sony[$m][2]['previousSS'],0,',','.')}} </td>
						    		<td class="odd center" style="width: 7% !important;  font-size: 16px; border-style: solid; border-color:black; border-width: 0px 1px 0px 0px;"> {{number_format($sony[$m][2]['previousSap'],0,',','.')}} </td>
						    		<td class="odd center" style="width: 7% !important;  font-size: 16px; border-style: solid; border-color:black; border-width: 0px 1px 0px 0px;"> {{number_format($sony[$m][2]['pPSap'],0,',','.')}} </td>
						    		<td class="odd center" style="width: 3% !important;  font-size: 16px; "> {{number_format($sony[$m][2]['currentPlanPercent'],0,',','.')}}% </td>
						    		<td class="odd center" style="width: 3% !important;  font-size: 16px; "> {{number_format($sony[$m][2]['currentFcstPercent'],0,',','.')}}% </td>
						    		<td class="odd center" style="width: 3% !important;  font-size: 16px; "> {{number_format($sony[$m][2]['ssPercent'],0,',','.')}}% </td>
						    		<td class="odd center" style="width: 3% !important;  font-size: 16px; "> {{number_format($sony[$m][2]['pSapPercent'],0,',','.')}}% </td>
						    		<td class="odd center" style="width: 3% !important;  font-size: 16px; border-style:solid; border-color:black; border-width: 0px 1px 0px 0px;"> {{number_format($sony[$m][2]['ppSapPercent'],0,',','.')}}% </td>
						    	</tr>				    	
						    </tr>
						@endfor
						<tr>
					    		<td class="oddGrey center" style="width: 7% !important;  font-size: 16px; border-style:solid; border-color:black; border-width: 1px;" rowspan="4"> (JAN-{{$actualMonth}})  </td>
					    	<tr>
					    		<td class="odd center" style="width: 7% !important;  font-size: 16px; border-style:solid; border-color:black; border-width: 1px 1px 0px 1px;"> TV </td>
						    		<td class="odd center" style="width: 7% !important;  font-size: 16px; border-style:solid; border-color:black; border-width: 1px 0px 0px 0px;"> {{number_format($sony[3][0]['currentYTD'],0,',','.')}} </td>
						    		<td class="odd center" style="width: 7% !important;  font-size: 16px; border-style:solid; border-color:black; border-width: 1px 0px 0px 0px;"> {{number_format($sony[3][0]['currentPlan'],0,',','.')}} </td>
						    		<td class="odd center" style="width: 7% !important;  font-size: 16px; border-style:solid; border-color:black; border-width: 1px 1px 0px 0px;"> {{number_format($sony[3][0]['currentFcst'],0,',','.')}} </td>
						    		<td class="odd center" style="width: 7% !important;  font-size: 16px; border-style:solid; border-color:black; border-width: 1px 0px 0px 0px;"> {{number_format($sony[3][0]['previousSS'],0,',','.')}} </td>
						    		<td class="odd center" style="width: 7% !important;  font-size: 16px; border-style:solid; border-color:black; border-width: 1px 1px 0px 0px;"> {{number_format($sony[3][0]['previousSap'],0,',','.')}} </td>
						    		<td class="odd center" style="width: 7% !important;  font-size: 16px; border-style:solid; border-color:black; border-width: 1px 1px 0px 0px;"> {{number_format($sony[3][0]['pPSap'],0,',','.')}} </td>
						    		<td class="odd center" style="width: 3% !important;  font-size: 16px; border-style:solid; border-color:black; border-width: 1px 0px 0px 0px;"> {{number_format($sony[3][0]['currentPlanPercent'],0,',','.')}}% </td>
						    		<td class="odd center" style="width: 3% !important;  font-size: 16px; border-style:solid; border-color:black; border-width: 1px 0px 0px 0px;"> {{number_format($sony[3][0]['currentFcstPercent'],0,',','.')}}% </td>
						    		<td class="odd center" style="width: 3% !important;  font-size: 16px; border-style:solid; border-color:black; border-width: 1px 0px 0px 0px;"> {{number_format($sony[3][0]['ssPercent'],0,',','.')}}% </td>
						    		<td class="odd center" style="width: 3% !important;  font-size: 16px; border-style:solid; border-color:black; border-width: 1px 0px 0px 0px;"> {{number_format($sony[3][0]['pSapPercent'],0,',','.')}}% </td>
						    		<td class="odd center" style="width: 3% !important;  font-size: 16px; border-style:solid; border-color:black; border-width: 1px 1px 0px 0px;"> {{number_format($sony[3][0]['ppSapPercent'],0,',','.')}}% </td>
						    	</tr>
						    	<tr>
						    		<td class="even center" style="width: 7% !important;  font-size: 16px; border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;"> ONL </td>
						    		<td class="even center" style="width: 7% !important;  font-size: 16px;"> {{number_format($sony[3][1]['currentYTD'],0,',','.')}} </td>
						    		<td class="even center" style="width: 7% !important;  font-size: 16px;"> {{number_format($sony[3][1]['currentPlan'],0,',','.')}} </td>
						    		<td class="even center" style="width: 7% !important;  font-size: 16px; border-style: solid; border-color:black; border-width: 0px 1px 0px 0px;">{{number_format($sony[3][1]['currentFcst'],0,',','.')}} </td>
						    		<td class="even center" style="width: 7% !important;  font-size: 16px;"> {{number_format($sony[3][1]['previousSS'],0,',','.')}}</td>
						    		<td class="even center" style="width: 7% !important;  font-size: 16px; border-style: solid; border-color:black; border-width: 0px 1px 0px 0px;">{{number_format($sony[3][1]['previousSap'],0,',','.')}} </td>
						    		<td class="even center" style="width: 7% !important;  font-size: 16px; border-style: solid; border-color:black; border-width: 0px 1px 0px 0px;">{{number_format($sony[3][1]['pPSap'],0,',','.')}} </td>
						    		<td class="even center" style="width: 3% !important;  font-size: 16px;">{{number_format($sony[3][1]['currentPlanPercent'],0,',','.')}}% </td>
						    		<td class="even center" style="width: 3% !important;  font-size: 16px;">{{number_format($sony[3][1]['currentFcstPercent'],0,',','.')}}% </td>
						    		<td class="even center" style="width: 3% !important;  font-size: 16px;">{{number_format($sony[3][1]['ssPercent'],0,',','.')}}% </td>
						    		<td class="even center" style="width: 3% !important;  font-size: 16px;">{{number_format($sony[3][1]['pSapPercent'],0,',','.')}}% </td>
						    		<td class="even center" style="width: 3% !important;  font-size: 16px; border-style:solid; border-color:black; border-width: 0px 1px 0px 0px;">{{number_format($sony[3][1]['ppSapPercent'],0,',','.')}}% </td>
						    	</tr>				    	
						    	<tr>
						    		<td class="odd center" style="width: 7% !important;  font-size: 16px; border-style:solid; border-color:black; border-width: 0px 1px 1px 1px;"> TOTAL</td>
						    		<td class="odd center" style="width: 7% !important;  font-size: 16px; border-style:solid; border-color:black; border-width: 0px 0px 1px 0px;"> {{number_format($sony[3][2]['currentYTD'],0,',','.')}} </td>
						    		<td class="odd center" style="width: 7% !important;  font-size: 16px; border-style:solid; border-color:black; border-width: 0px 0px 1px 0px;"> {{number_format($sony[3][2]['currentPlan'],0,',','.')}} </td>
						    		<td class="odd center" style="width: 7% !important;  font-size: 16px; border-style: solid; border-color:black; border-width: 0px 1px 1px 0px;"> {{number_format($sony[3][2]['currentFcst'],0,',','.')}} </td>
						    		<td class="odd center" style="width: 7% !important;  font-size: 16px; border-style:solid; border-color:black; border-width: 0px 0px 1px 0px;"> {{number_format($sony[3][2]['previousSS'],0,',','.')}} </td>
						    		<td class="odd center" style="width: 7% !important;  font-size: 16px; border-style: solid; border-color:black; border-width: 0px 1px 1px 0px;"> {{number_format($sony[3][2]['previousSap'],0,',','.')}} </td>
						    		<td class="odd center" style="width: 7% !important;  font-size: 16px; border-style: solid; border-color:black; border-width: 0px 1px 1px 0px;"> {{number_format($sony[3][2]['pPSap'],0,',','.')}} </td>
						    		<td class="odd center" style="width: 3% !important;  font-size: 16px; border-style:solid; border-color:black; border-width: 0px 0px 1px 0px;"> {{number_format($sony[3][2]['currentPlanPercent'],0,',','.')}}% </td>
						    		<td class="odd center" style="width: 3% !important;  font-size: 16px; border-style:solid; border-color:black; border-width: 0px 0px 1px 0px;"> {{number_format($sony[3][2]['currentFcstPercent'],0,',','.')}}% </td>
						    		<td class="odd center" style="width: 3% !important;  font-size: 16px; border-style:solid; border-color:black; border-width: 0px 0px 1px 0px;"> {{number_format($sony[3][2]['ssPercent'],0,',','.')}}% </td>
						    		<td class="odd center" style="width: 3% !important;  font-size: 16px; border-style:solid; border-color:black; border-width: 0px 0px 1px 0px;"> {{number_format($sony[3][2]['pSapPercent'],0,',','.')}}% </td>
						    		<td class="odd center" style="width: 3% !important;  font-size: 16px; border-style:solid; border-color:black; border-width: 0px 1px 1px 0px;"> {{number_format($sony[3][2]['ppSapPercent'],0,',','.')}}% </td>
						    	</tr>
					    	</tr>
					</table>

					<table style='width: 100%; zoom: 85%;font-size: 16px;'>
						<tr class="center">
			        		<td style="width: 7% !important; background-color: white;"> &nbsp; </td>
			        	</tr>
			        </table>

					<table style='width: 100%; zoom: 85%;'>
						<tr class="center">
					        <td class='dn center' style="width: 100% !important; font-size: 22px; border-style:solid; border-color:black; border-width: 1px 1px 0px 1px;" colspan="13"> WARNER MEDIA ( {{$currencyName}} / {{strtoupper($value)}} )</td>
					    </tr>
					    <tr>
					    	<td class='grey center' style="width: 7% !important; font-size: 16px; border-style:solid; border-color:black; border-width: 1px 0px 1px 1px;"> LOG </td>
					    	<td class="grey center" style="width: 7% !important; font-size: 16px; border-style:solid; border-color:black; border-width: 1px 1px 1px 0px;"> {{$realDate}} </td>
					    	<td class="grey center" style="width: 7% !important; font-size: 16px; border-style:solid; border-color:black; border-width: 1px 1px 1px 1px;" colspan="3"> {{$cYear}}</td>
					    	<td class="grey center" style="width: 7% !important; font-size: 16px; border-style:solid; border-color:black; border-width: 1px 1px 1px 1px;" colspan="2"> {{$pYear}}</td>
					    	<td class="grey center" style="width: 7% !important; font-size: 16px; border-style:solid; border-color:black; border-width: 1px 1px 1px 1px;" colspan="1"> {{$ppYear}}</td>
					    	<td class="grey center" style="width: 7% !important; font-size: 16px; border-style:solid; border-color:black; border-width: 1px 1px 1px 1px;" colspan="5"> {{$cYear}} VAR (%) </td>
					    </tr>				   
					    <tr>
					    	<td class="lightGrey center" style="width: 7% !important; font-size: 16px; border-style:solid; border-color:black; border-width: 1px;"> MONTH </td>
					    	<td class="lightGrey center" style="width: 7% !important; font-size: 16px; border-style:solid; border-color:black; border-width: 1px;"> PLATAFORM </td>
					    	@if($regionID == "1")
					    		<td class="smBlue center" style="width: 7% !important; font-size: 16px;"> BKGS </td>
					    	@else
					    	   	<td class="smBlue center" style="width: 7% !important; font-size: 16px;"> YTD </td>
					    	@endif
					    	<td class="smBlue center" style="width: 7% !important; font-size: 16px;"> PLAN </td>
					    	<td class="smBlue center" style="width: 7% !important; font-size: 16px; border-style:solid; border-color:black; border-width: 0px 1px 0px 0px;"> FCST </td>
					    	<td class="smBlue center" style="width: 7% !important; font-size: 16px;"> SNAPSHOT </td>
					    	<td class="smBlue center" style="width: 7% !important; font-size: 16px;"> BKGS </td>
					    	<td class="smBlue center" style="width: 7% !important; font-size: 16px; border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;"> BKGS </td>
					    	<td class="smBlue center" style="width: 3% !important; font-size: 16px;"> PLAN </td>
					    	<td class="smBlue center" style="width: 3% !important; font-size: 16px;"> FCST </td>
					    	<td class="smBlue center" style="width: 3% !important; font-size: 16px;"> SNAPSHOT </td>
					    	<td class="smBlue center" style="width: 3% !important; font-size: 16px;"> BKGS {{$pYear}} </td>
					    	<td class="smBlue center" style="width: 3% !important; font-size: 16px; border-style:solid; border-color:black; border-width: 0px 1px 0px 0px;"> BKGS {{$ppYear}} </td>
					    </tr>
					    @for($m = 0; $m < 3; $m++)
					    <div style="display: none;"> {{ $monthForm = $base->intToMonth(array($month + $m))[0]}}</div>	
							 <tr>
							 	<td class="oddGrey center" style="width: 7% !important;  font-size: 16px;  border-style:solid; border-color:black; border-width: 1px;" rowspan="4"> {{$monthForm}} </td>
						    	<tr>
						    		<td class="odd center" style="width: 7% !important;  font-size: 16px; border-style:solid; border-color:black; border-width: 1px 1px 0px 1px;"> TV </td>
						    		<td class="odd center" style="width: 7% !important;  font-size: 16px; border-style:solid; border-color:black; border-width: 1px 0px 0px 0px;"> {{number_format($wm[$m][0]['currentYTD'],0,',','.')}} </td>
						    		<td class="odd center" style="width: 7% !important;  font-size: 16px; border-style:solid; border-color:black; border-width: 1px 0px 0px 0px;"> {{number_format($wm[$m][0]['currentPlan'],0,',','.')}} </td>
						    		<td class="odd center" style="width: 7% !important;  font-size: 16px; border-style:solid; border-color:black; border-width: 1px 1px 0px 0px;"> {{number_format($wm[$m][0]['currentFcst'],0,',','.')}} </td>
						    		<td class="odd center" style="width: 7% !important;  font-size: 16px; border-style:solid; border-color:black; border-width: 1px 0px 0px 0px;"> {{number_format($wm[$m][0]['previousSS'],0,',','.')}} </td>
						    		<td class="odd center" style="width: 7% !important;  font-size: 16px; border-style:solid; border-color:black; border-width: 1px 1px 0px 0px;"> {{number_format($wm[$m][0]['previousSap'],0,',','.')}} </td>
						    		<td class="odd center" style="width: 7% !important;  font-size: 16px; border-style:solid; border-color:black; border-width: 1px 1px 0px 0px;"> {{number_format($wm[$m][0]['pPSap'],0,',','.')}} </td>
						    		<td class="odd center" style="width: 3% !important;  font-size: 16px; border-style:solid; border-color:black; border-width: 1px 0px 0px 0px;"> {{number_format($wm[$m][0]['currentPlanPercent'],0,',','.')}}% </td>
						    		<td class="odd center" style="width: 3% !important;  font-size: 16px; border-style:solid; border-color:black; border-width: 1px 0px 0px 0px;"> {{number_format($wm[$m][0]['currentFcstPercent'],0,',','.')}}% </td>
						    		<td class="odd center" style="width: 3% !important;  font-size: 16px; border-style:solid; border-color:black; border-width: 1px 0px 0px 0px;"> {{number_format($wm[$m][0]['ssPercent'],0,',','.')}}% </td>
						    		<td class="odd center" style="width: 3% !important;  font-size: 16px; border-style:solid; border-color:black; border-width: 1px 0px 0px 0px;"> {{number_format($wm[$m][0]['pSapPercent'],0,',','.')}}% </td>
						    		<td class="odd center" style="width: 3% !important;  font-size: 16px; border-style:solid; border-color:black; border-width: 1px 1px 0px 0px;"> {{number_format($wm[$m][0]['ppSapPercent'],0,',','.')}}% </td>
						    	</tr>
						    	<tr>
						    		<td class="even center" style="width: 7% !important;  font-size: 16px; border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;"> ONL </td>
						    		<td class="even center" style="width: 7% !important;  font-size: 16px;"> {{number_format($wm[$m][1]['currentYTD'],0,',','.')}} </td>
						    		<td class="even center" style="width: 7% !important;  font-size: 16px;"> {{number_format($wm[$m][1]['currentPlan'],0,',','.')}} </td>
						    		<td class="even center" style="width: 7% !important;  font-size: 16px; border-style: solid; border-color:black; border-width: 0px 1px 0px 0px;">{{number_format($wm[$m][1]['currentFcst'],0,',','.')}} </td>
						    		<td class="even center" style="width: 7% !important;  font-size: 16px;"> {{number_format($wm[$m][1]['previousSS'],0,',','.')}}</td>
						    		<td class="even center" style="width: 7% !important;  font-size: 16px; border-style: solid; border-color:black; border-width: 0px 1px 0px 0px;">{{number_format($wm[$m][1]['previousSap'],0,',','.')}} </td>
						    		<td class="even center" style="width: 7% !important;  font-size: 16px; border-style: solid; border-color:black; border-width: 0px 1px 0px 0px;">{{number_format($wm[$m][1]['pPSap'],0,',','.')}} </td>
						    		<td class="even center" style="width: 3% !important;  font-size: 16px;">{{number_format($wm[$m][1]['currentPlanPercent'],0,',','.')}}% </td>
						    		<td class="even center" style="width: 3% !important;  font-size: 16px;">{{number_format($wm[$m][1]['currentFcstPercent'],0,',','.')}}% </td>
						    		<td class="even center" style="width: 3% !important;  font-size: 16px;">{{number_format($wm[$m][1]['ssPercent'],0,',','.')}}% </td>
						    		<td class="even center" style="width: 3% !important;  font-size: 16px;">{{number_format($wm[$m][1]['pSapPercent'],0,',','.')}}% </td>
						    		<td class="even center" style="width: 3% !important;  font-size: 16px; border-style:solid; border-color:black; border-width: 0px 1px 0px 0px;">{{number_format($wm[$m][1]['ppSapPercent'],0,',','.')}}% </td>	
						    	</tr>				    	
						    	<tr>
						    		<td class="odd center" style="width: 7% !important;  font-size: 16px; border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;"> TOTAL</td>
						    		<td class="odd center" style="width: 7% !important;  font-size: 16px; "> {{number_format($wm[$m][2]['currentYTD'],0,',','.')}} </td>
						    		<td class="odd center" style="width: 7% !important;  font-size: 16px; "> {{number_format($wm[$m][2]['currentPlan'],0,',','.')}} </td>
						    		<td class="odd center" style="width: 7% !important;  font-size: 16px; border-style: solid; border-color:black; border-width: 0px 1px 0px 0px;"> {{number_format($wm[$m][2]['currentFcst'],0,',','.')}} </td>
						    		<td class="odd center" style="width: 7% !important;  font-size: 16px; "> {{number_format($wm[$m][2]['previousSS'],0,',','.')}} </td>
						    		<td class="odd center" style="width: 7% !important;  font-size: 16px; border-style: solid; border-color:black; border-width: 0px 1px 0px 0px;"> {{number_format($wm[$m][2]['previousSap'],0,',','.')}} </td>
						    		<td class="odd center" style="width: 7% !important;  font-size: 16px; border-style: solid; border-color:black; border-width: 0px 1px 0px 0px;"> {{number_format($wm[$m][2]['pPSap'],0,',','.')}} </td>
						    		<td class="odd center" style="width: 3% !important;  font-size: 16px; "> {{number_format($wm[$m][2]['currentPlanPercent'],0,',','.')}}% </td>
						    		<td class="odd center" style="width: 3% !important;  font-size: 16px; "> {{number_format($wm[$m][2]['currentFcstPercent'],0,',','.')}}% </td>
						    		<td class="odd center" style="width: 3% !important;  font-size: 16px; "> {{number_format($wm[$m][2]['ssPercent'],0,',','.')}}% </td>
						    		<td class="odd center" style="width: 3% !important;  font-size: 16px; "> {{number_format($wm[$m][2]['pSapPercent'],0,',','.')}}% </td>
						    		<td class="odd center" style="width: 3% !important;  font-size: 16px; border-style:solid; border-color:black; border-width: 0px 1px 0px 0px;"> {{number_format($wm[$m][2]['ppSapPercent'],0,',','.')}}% </td>
						    	</tr>				    	
						    </tr>
						@endfor
						<tr>
					    	<td class="oddGrey center" style="width: 7% !important;  font-size: 16px; border-style:solid; border-color:black; border-width: 1px;" rowspan="4"> (JAN-{{$actualMonth}}) </td>
					    	<tr>
					    		<td class="odd center" style="width: 7% !important;  font-size: 16px; border-style:solid; border-color:black; border-width: 1px 1px 0px 1px;"> TV </td>
						    		<td class="odd center" style="width: 7% !important;  font-size: 16px; border-style:solid; border-color:black; border-width: 1px 0px 0px 0px;"> {{number_format($wm[3][0]['currentYTD'],0,',','.')}} </td>
						    		<td class="odd center" style="width: 7% !important;  font-size: 16px; border-style:solid; border-color:black; border-width: 1px 0px 0px 0px;"> {{number_format($wm[3][0]['currentPlan'],0,',','.')}} </td>
						    		<td class="odd center" style="width: 7% !important;  font-size: 16px; border-style:solid; border-color:black; border-width: 1px 1px 0px 0px;"> {{number_format($wm[3][0]['currentFcst'],0,',','.')}} </td>
						    		<td class="odd center" style="width: 7% !important;  font-size: 16px; border-style:solid; border-color:black; border-width: 1px 0px 0px 0px;"> {{number_format($wm[3][0]['previousSS'],0,',','.')}} </td>
						    		<td class="odd center" style="width: 7% !important;  font-size: 16px; border-style:solid; border-color:black; border-width: 1px 1px 0px 0px;"> {{number_format($wm[3][0]['previousSap'],0,',','.')}} </td>
						    		<td class="odd center" style="width: 7% !important;  font-size: 16px; border-style:solid; border-color:black; border-width: 1px 1px 0px 0px;"> {{number_format($wm[3][0]['pPSap'],0,',','.')}} </td>
						    		<td class="odd center" style="width: 3% !important;  font-size: 16px; border-style:solid; border-color:black; border-width: 1px 0px 0px 0px;"> {{number_format($wm[3][0]['currentPlanPercent'],0,',','.')}}% </td>
						    		<td class="odd center" style="width: 3% !important;  font-size: 16px; border-style:solid; border-color:black; border-width: 1px 0px 0px 0px;"> {{number_format($wm[3][0]['currentFcstPercent'],0,',','.')}}% </td>
						    		<td class="odd center" style="width: 3% !important;  font-size: 16px; border-style:solid; border-color:black; border-width: 1px 0px 0px 0px;"> {{number_format($wm[3][0]['ssPercent'],0,',','.')}}% </td>
						    		<td class="odd center" style="width: 3% !important;  font-size: 16px; border-style:solid; border-color:black; border-width: 1px 0px 0px 0px;"> {{number_format($wm[3][0]['pSapPercent'],0,',','.')}}% </td>
						    		<td class="odd center" style="width: 3% !important;  font-size: 16px; border-style:solid; border-color:black; border-width: 1px 1px 0px 0px;"> {{number_format($wm[3][0]['ppSapPercent'],0,',','.')}}% </td>
						    	</tr>
						    	<tr>
						    		<td class="even center" style="width: 7% !important;  font-size: 16px; border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;"> ONL </td>
						    		<td class="even center" style="width: 7% !important;  font-size: 16px;"> {{number_format($wm[3][1]['currentYTD'],0,',','.')}} </td>
						    		<td class="even center" style="width: 7% !important;  font-size: 16px;"> {{number_format($wm[3][1]['currentPlan'],0,',','.')}} </td>
						    		<td class="even center" style="width: 7% !important;  font-size: 16px; border-style: solid; border-color:black; border-width: 0px 1px 0px 0px;">{{number_format($wm[3][1]['currentFcst'],0,',','.')}} </td>
						    		<td class="even center" style="width: 7% !important;  font-size: 16px;"> {{number_format($wm[3][1]['previousSS'],0,',','.')}}</td>
						    		<td class="even center" style="width: 7% !important;  font-size: 16px; border-style: solid; border-color:black; border-width: 0px 1px 0px 0px;">{{number_format($wm[3][1]['previousSap'],0,',','.')}} </td>
						    		<td class="even center" style="width: 7% !important;  font-size: 16px; border-style: solid; border-color:black; border-width: 0px 1px 0px 0px;">{{number_format($wm[3][1]['pPSap'],0,',','.')}} </td>
						    		<td class="even center" style="width: 3% !important;  font-size: 16px;">{{number_format($wm[3][1]['currentPlanPercent'],0,',','.')}}% </td>
						    		<td class="even center" style="width: 3% !important;  font-size: 16px;">{{number_format($wm[3][1]['currentFcstPercent'],0,',','.')}}% </td>
						    		<td class="even center" style="width: 3% !important;  font-size: 16px;">{{number_format($wm[3][1]['ssPercent'],0,',','.')}}% </td>
						    		<td class="even center" style="width: 3% !important;  font-size: 16px;">{{number_format($wm[3][1]['pSapPercent'],0,',','.')}}% </td>
						    		<td class="even center" style="width: 3% !important;  font-size: 16px; border-style:solid; border-color:black; border-width: 0px 1px 0px 0px;">{{number_format($wm[3][1]['ppSapPercent'],0,',','.')}}% </td>
						    	</tr>				    	
						    	<tr>
						    		<td class="odd center" style="width: 7% !important;  font-size: 16px; border-style:solid; border-color:black; border-width: 0px 1px 1px 1px;"> TOTAL</td>
						    		<td class="odd center" style="width: 7% !important;  font-size: 16px; border-style:solid; border-color:black; border-width: 0px 0px 1px 0px;"> {{number_format($wm[3][2]['currentYTD'],0,',','.')}} </td>
						    		<td class="odd center" style="width: 7% !important;  font-size: 16px; border-style:solid; border-color:black; border-width: 0px 0px 1px 0px;"> {{number_format($wm[3][2]['currentPlan'],0,',','.')}} </td>
						    		<td class="odd center" style="width: 7% !important;  font-size: 16px; border-style: solid; border-color:black; border-width: 0px 1px 1px 0px;"> {{number_format($wm[3][2]['currentFcst'],0,',','.')}} </td>
						    		<td class="odd center" style="width: 7% !important;  font-size: 16px; border-style:solid; border-color:black; border-width: 0px 0px 1px 0px;"> {{number_format($wm[3][2]['previousSS'],0,',','.')}} </td>
						    		<td class="odd center" style="width: 7% !important;  font-size: 16px; border-style: solid; border-color:black; border-width: 0px 1px 1px 0px;"> {{number_format($wm[3][2]['previousSap'],0,',','.')}} </td>
						    		<td class="odd center" style="width: 7% !important;  font-size: 16px; border-style: solid; border-color:black; border-width: 0px 1px 1px 0px;"> {{number_format($wm[3][2]['pPSap'],0,',','.')}} </td>
						    		<td class="odd center" style="width: 3% !important;  font-size: 16px; border-style:solid; border-color:black; border-width: 0px 0px 1px 0px;"> {{number_format($wm[3][2]['currentPlanPercent'],0,',','.')}}% </td>
						    		<td class="odd center" style="width: 3% !important;  font-size: 16px; border-style:solid; border-color:black; border-width: 0px 0px 1px 0px;"> {{number_format($wm[3][2]['currentFcstPercent'],0,',','.')}}% </td>
						    		<td class="odd center" style="width: 3% !important;  font-size: 16px; border-style:solid; border-color:black; border-width: 0px 0px 1px 0px;"> {{number_format($wm[3][2]['ssPercent'],0,',','.')}}% </td>
						    		<td class="odd center" style="width: 3% !important;  font-size: 16px; border-style:solid; border-color:black; border-width: 0px 0px 1px 0px;"> {{number_format($wm[3][2]['pSapPercent'],0,',','.')}}% </td>
						    		<td class="odd center" style="width: 3% !important;  font-size: 16px; border-style:solid; border-color:black; border-width: 0px 1px 1px 0px;"> {{number_format($wm[3][2]['ppSapPercent'],0,',','.')}}% </td>
						    	</tr>
					    	</tr>
					    </tr>
					</table>

					<table style='width: 100%; zoom: 85%;font-size: 16px;'>
						<tr class="center">
			        		<td style="width: 7% !important; background-color: white;"> &nbsp; </td>
			        	</tr>
			        </table>
				@else
					<p> THERE IS NO DATA FOR THIS DATE </p>
				@endif
				<table style='width: 100%; zoom: 85%;font-size: 16px;'>
					<tr class="center">
		        		<td style="width: 7% !important; background-color: white;"> &nbsp; </td>
		        	</tr>
		        </table>
			</div>
		</div>
	</div>

</div>

<div id="vlau"></div>

<script type="text/javascript">
            
    $(document).ready(function(){

        ajaxSetup();

        $('#excel').click(function(event){

            var regionExcel = "<?php echo $regionExcel; ?>";
            var currencyExcel = "<?php echo $currencyExcel; ?>";
            var valueExcel = "<?php echo $valueExcel; ?>";
            var logExcel = "<?php echo $logExcel; ?>";

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
                url: "/generate/excel/results/daily",
                type: "POST",
                data: {regionExcel,currencyExcel,valueExcel,title, typeExport, auxTitle,logExcel},
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