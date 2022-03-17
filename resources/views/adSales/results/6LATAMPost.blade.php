@extends('layouts.mirror')
@section('title', 'LATAM Results')
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
						{{$render->value2()}}
					</div>

					<div class="row justify-content-center">          
						<div class="col">       
							<div class="form-group">
								<label><b> Date: </b></label> 
								@if($errors->has('log'))
									<label style="color: red;">* Required</label>
								@endif
								<input type="date" class="form-control" name="log" value="{{date("m/d/Y")}}">
							</div>
						</div>
					</div>  

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

				<table style='width: 100%; zoom: 85%;'>
					<tr class="center">
				        <td class='grey center' style="width: 100% !important; font-size: 22px;" colspan="13"> Discovery + Sony ( {{$currencyName}} / {{strtoupper($value)}} )</td>
				    </tr>
				    <tr>
				    	<td class='grey center' style="width: 7% !important; font-size: 22px; border-style:solid; border-color:black; border-width: 1px 0px 1px 1px;"> LOG </td>
				    	<td class="grey center" style="width: 7% !important; font-size: 18px; border-style:solid; border-color:black; border-width: 1px 1px 1px 0px;"> {{$day}}/{{$month}} </td>
				    	<td class="grey center" style="width: 7% !important; font-size: 18px; border-style:solid; border-color:black; border-width: 1px 1px 1px 1px;" colspan="3"> {{$cYear}}</td>
				    	<td class="grey center" style="width: 7% !important; font-size: 18px; border-style:solid; border-color:black; border-width: 1px 1px 1px 1px;" colspan="2"> {{$pYear}}</td>
				    	<td class="grey center" style="width: 7% !important; font-size: 18px; border-style:solid; border-color:black; border-width: 1px 1px 1px 1px;" colspan="1"> {{$ppYear}}</td>
				    	<td class="grey center" style="width: 7% !important; font-size: 18px; border-style:solid; border-color:black; border-width: 1px 1px 1px 1px;" colspan="5"> VAR % </td>
				    </tr>				   
				    <tr>
				    	<td class="lightGrey center" style="width: 7% !important; font-size: 18px; border-style:solid; border-color:black; border-width: 1px;"> MONTH </td>
				    	<td class="lightGrey center" style="width: 7% !important; font-size: 18px; border-style:solid; border-color:black; border-width: 1px;"> PLATAFORM </td>
				    	<td class="smBlue center" style="width: 7% !important; font-size: 18px;"> CMAPS </td>
				    	<td class="smBlue center" style="width: 7% !important; font-size: 18px;"> PLAN </td>
				    	<td class="smBlue center" style="width: 7% !important; font-size: 18px; "> FCAST </td>
				    	<td class="smBlue center" style="width: 7% !important; font-size: 18px;"> SCREENSHOT </td>
				    	<td class="smBlue center" style="width: 7% !important; font-size: 18px;"> SAP </td>
				    	<td class="smBlue center" style="width: 7% !important; font-size: 18px;"> SAP </td>
				    	<td class="smBlue center" style="width: 7% !important; font-size: 18px;"> Plan {{$cYear}} (%) </td>
				    	<td class="smBlue center" style="width: 7% !important; font-size: 18px;"> Fcst {{$cYear}} (%) </td>
				    	<td class="smBlue center" style="width: 7% !important; font-size: 18px;"> SS {{$cYear}} (%) </td>
				    	<td class="smBlue center" style="width: 7% !important; font-size: 18px;"> Sap {{$pYear}} (%) </td>
				    	<td class="smBlue center" style="width: 7% !important; font-size: 18px; border-style:solid; border-color:black; border-width: 0px 1px 0px 0px;"> Sap {{$ppYear}} (%) </td>
				    </tr>
				    @for($m = 0; $m < 3; $m++)
						 <tr>
						 	<td class="oddGrey center" style="width: 7% !important;  font-size: 16px;  border-style:solid; border-color:black; border-width: 1px;" rowspan="4"> {{strtoupper($month + $m)}} </td>
					    	<tr>
					    		<td class="odd center" style="width: 7% !important;  font-size: 18px; border-style:solid; border-color:black; border-width: 1px 1px 0px 1px;"> TV </td>
					    		<td class="odd center" style="width: 7% !important;  font-size: 18px; border-style:solid; border-color:black; border-width: 1px 0px 0px 0px;"> 0 </td>
					    		<td class="odd center" style="width: 7% !important;  font-size: 18px; border-style:solid; border-color:black; border-width: 1px 0px 0px 0px;"> 0 </td>
					    		<td class="odd center" style="width: 7% !important;  font-size: 18px; border-style:solid; border-color:black; border-width: 1px 1px 0px 0px;"> 0 </td>
					    		<td class="odd center" style="width: 7% !important;  font-size: 18px; border-style:solid; border-color:black; border-width: 1px 0px 0px 0px;"> 0 </td>
					    		<td class="odd center" style="width: 7% !important;  font-size: 18px; border-style:solid; border-color:black; border-width: 1px 1px 0px 0px;"> 0 </td>
					    		<td class="odd center" style="width: 7% !important;  font-size: 18px; border-style:solid; border-color:black; border-width: 1px 1px 0px 0px;"> 0 </td>
					    		<td class="odd center" style="width: 7% !important;  font-size: 18px; border-style:solid; border-color:black; border-width: 1px 0px 0px 0px;"> % </td>
					    		<td class="odd center" style="width: 7% !important;  font-size: 18px; border-style:solid; border-color:black; border-width: 1px 0px 0px 0px;"> % </td>
					    		<td class="odd center" style="width: 7% !important;  font-size: 18px; border-style:solid; border-color:black; border-width: 1px 0px 0px 0px;"> % </td>
					    		<td class="odd center" style="width: 7% !important;  font-size: 18px; border-style:solid; border-color:black; border-width: 1px 0px 0px 0px;"> % </td>
					    		<td class="odd center" style="width: 7% !important;  font-size: 18px; border-style:solid; border-color:black; border-width: 1px 1px 0px 0px;"> % </td>
					    	</tr>
					    	<tr>
					    		<td class="even center" style="width: 7% !important;  font-size: 18px; border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;"> ONL </td>
					    		<td class="even center" style="width: 7% !important;  font-size: 18px;"> 0 </td>
					    		<td class="even center" style="width: 7% !important;  font-size: 18px;"> 0 </td>
					    		<td class="even center" style="width: 7% !important;  font-size: 18px; border-style: solid; border-color:black; border-width: 0px 1px 0px 0px;"> 0 </td>
					    		<td class="even center" style="width: 7% !important;  font-size: 18px;"> 0 </td>
					    		<td class="even center" style="width: 7% !important;  font-size: 18px; border-style: solid; border-color:black; border-width: 0px 1px 0px 0px;"> 0 </td>
					    		<td class="even center" style="width: 7% !important;  font-size: 18px; border-style: solid; border-color:black; border-width: 0px 1px 0px 0px;"> 0 </td>
					    		<td class="even center" style="width: 7% !important;  font-size: 18px;"> % </td>
					    		<td class="even center" style="width: 7% !important;  font-size: 18px;"> % </td>
					    		<td class="even center" style="width: 7% !important;  font-size: 18px;"> % </td>
					    		<td class="even center" style="width: 7% !important;  font-size: 18px;"> % </td>
					    		<td class="even center" style="width: 7% !important;  font-size: 18px; border-style:solid; border-color:black; border-width: 0px 1px 0px 0px;"> % </td>	
					    	</tr>				    	
					    	<tr>
					    		<td class="odd center" style="width: 7% !important;  font-size: 18px; border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;"> TOTAL</td>
					    		<td class="odd center" style="width: 7% !important;  font-size: 18px; "> 0 </td>
					    		<td class="odd center" style="width: 7% !important;  font-size: 18px; "> 0 </td>
					    		<td class="odd center" style="width: 7% !important;  font-size: 18px; border-style: solid; border-color:black; border-width: 0px 1px 0px 0px;"> 0 </td>
					    		<td class="odd center" style="width: 7% !important;  font-size: 18px; "> 0 </td>
					    		<td class="odd center" style="width: 7% !important;  font-size: 18px; border-style: solid; border-color:black; border-width: 0px 1px 0px 0px;"> 0 </td>
					    		<td class="odd center" style="width: 7% !important;  font-size: 18px; border-style: solid; border-color:black; border-width: 0px 1px 0px 0px;"> 0 </td>
					    		<td class="odd center" style="width: 7% !important;  font-size: 18px; "> % </td>
					    		<td class="odd center" style="width: 7% !important;  font-size: 18px; "> % </td>
					    		<td class="odd center" style="width: 7% !important;  font-size: 18px; "> % </td>
					    		<td class="odd center" style="width: 7% !important;  font-size: 18px; "> % </td>
					    		<td class="odd center" style="width: 7% !important;  font-size: 18px; border-style:solid; border-color:black; border-width: 0px 1px 0px 0px;"> % </td>
					    	</tr>				    	
					    </tr>
					@endfor
					<tr>
				    	<td class="oddGrey center" style="width: 7% !important;  font-size: 16px; border-style:solid; border-color:black; border-width: 1px;" rowspan="4"> YTD (JAN-{{strtoupper($month)}}) </td>
				    	<tr>
				    		<td class="odd center" style="width: 7% !important;  font-size: 18px; border-style:solid; border-color:black; border-width: 1px 1px 0px 1px;"> TV </td>
					    		<td class="odd center" style="width: 7% !important;  font-size: 18px; border-style:solid; border-color:black; border-width: 1px 0px 0px 0px;"> 0 </td>
					    		<td class="odd center" style="width: 7% !important;  font-size: 18px; border-style:solid; border-color:black; border-width: 1px 0px 0px 0px;"> 0 </td>
					    		<td class="odd center" style="width: 7% !important;  font-size: 18px; border-style:solid; border-color:black; border-width: 1px 1px 0px 0px;"> 0 </td>
					    		<td class="odd center" style="width: 7% !important;  font-size: 18px; border-style:solid; border-color:black; border-width: 1px 0px 0px 0px;"> 0 </td>
					    		<td class="odd center" style="width: 7% !important;  font-size: 18px; border-style:solid; border-color:black; border-width: 1px 1px 0px 0px;"> 0 </td>
					    		<td class="odd center" style="width: 7% !important;  font-size: 18px; border-style:solid; border-color:black; border-width: 1px 1px 0px 0px;"> 0 </td>
					    		<td class="odd center" style="width: 7% !important;  font-size: 18px; border-style:solid; border-color:black; border-width: 1px 0px 0px 0px;"> % </td>
					    		<td class="odd center" style="width: 7% !important;  font-size: 18px; border-style:solid; border-color:black; border-width: 1px 0px 0px 0px;"> % </td>
					    		<td class="odd center" style="width: 7% !important;  font-size: 18px; border-style:solid; border-color:black; border-width: 1px 0px 0px 0px;"> % </td>
					    		<td class="odd center" style="width: 7% !important;  font-size: 18px; border-style:solid; border-color:black; border-width: 1px 0px 0px 0px;"> % </td>
					    		<td class="odd center" style="width: 7% !important;  font-size: 18px; border-style:solid; border-color:black; border-width: 1px 1px 0px 0px;"> % </td>
					    	</tr>
					    	<tr>
					    		<td class="even center" style="width: 7% !important;  font-size: 18px; border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;"> ONL </td>
					    		<td class="even center" style="width: 7% !important;  font-size: 18px;"> 0 </td>
					    		<td class="even center" style="width: 7% !important;  font-size: 18px;"> 0 </td>
					    		<td class="even center" style="width: 7% !important;  font-size: 18px; border-style:solid; border-color:black; border-width: 0px 1px 0px 0px;"> 0 </td>
					    		<td class="even center" style="width: 7% !important;  font-size: 18px;"> 0 </td>
					    		<td class="even center" style="width: 7% !important;  font-size: 18px; border-style: solid; border-color:black; border-width: 0px 1px 0px 0px;"> 0 </td>
					    		<td class="even center" style="width: 7% !important;  font-size: 18px; border-style: solid; border-color:black; border-width: 0px 1px 0px 0px;"> 0 </td>
					    		<td class="even center" style="width: 7% !important;  font-size: 18px;"> % </td>
					    		<td class="even center" style="width: 7% !important;  font-size: 18px;"> % </td>
					    		<td class="even center" style="width: 7% !important;  font-size: 18px;"> % </td>
					    		<td class="even center" style="width: 7% !important;  font-size: 18px;"> % </td>
					    		<td class="even center" style="width: 7% !important;  font-size: 18px; border-style:solid; border-color:black; border-width: 0px 1px 0px 0px;"> % </td>	
					    	</tr>				    	
					    	<tr>
					    		<td class="odd center" style="width: 7% !important;  font-size: 18px; border-style:solid; border-color:black; border-width: 0px 1px 1px 1px;"> TOTAL</td>
					    		<td class="odd center" style="width: 7% !important;  font-size: 18px; border-style:solid; border-color:black; border-width: 0px 0px 1px 0px; "> 0 </td>
					    		<td class="odd center" style="width: 7% !important;  font-size: 18px; border-style:solid; border-color:black; border-width: 0px 0px 1px 0px; "> 0 </td>
					    		<td class="odd center" style="width: 7% !important;  font-size: 18px; border-style:solid; border-color:black; border-width: 0px 1px 1px 0px; "> 0 </td>
					    		<td class="odd center" style="width: 7% !important;  font-size: 18px; border-style:solid; border-color:black; border-width: 0px 0px 1px 0px; "> 0 </td>
					    		<td class="odd center" style="width: 7% !important;  font-size: 18px; border-style:solid; border-color:black; border-width: 0px 1px 1px 0px; "> 0 </td>
					    		<td class="odd center" style="width: 7% !important;  font-size: 18px; border-style:solid; border-color:black; border-width: 0px 1px 1px 0px; "> 0 </td>
					    		<td class="odd center" style="width: 7% !important;  font-size: 18px; border-style:solid; border-color:black; border-width: 0px 0px 1px 0px; "> % </td>
					    		<td class="odd center" style="width: 7% !important;  font-size: 18px; border-style:solid; border-color:black; border-width: 0px 0px 1px 0px; "> % </td>
					    		<td class="odd center" style="width: 7% !important;  font-size: 18px; border-style:solid; border-color:black; border-width: 0px 0px 1px 0px; "> % </td>
					    		<td class="odd center" style="width: 7% !important;  font-size: 18px; border-style:solid; border-color:black; border-width: 0px 0px 1px 0px; "> % </td>
					    		<td class="odd center" style="width: 7% !important;  font-size: 18px; border-style:solid; border-color:black; border-width: 0px 1px 1px 0px;"> % </td>
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
				        <td class='dc center' style="width: 100% !important; font-size: 22px;" colspan="13"> Discovery ( {{$currencyName}} / {{strtoupper($value)}} )</td>
				    </tr>
				    <tr>
				    	<td class='grey center' style="width: 7% !important; font-size: 22px; border-style:solid; border-color:black; border-width: 1px 0px 1px 1px;"> LOG </td>
				    	<td class="grey center" style="width: 7% !important; font-size: 18px; border-style:solid; border-color:black; border-width: 1px 1px 1px 0px;"> {{$day}}/{{$month}} </td>
				    	<td class="grey center" style="width: 7% !important; font-size: 18px; border-style:solid; border-color:black; border-width: 1px 1px 1px 1px;" colspan="3"> {{$cYear}}</td>
				    	<td class="grey center" style="width: 7% !important; font-size: 18px; border-style:solid; border-color:black; border-width: 1px 1px 1px 1px;" colspan="2"> {{$pYear}}</td>
				    	<td class="grey center" style="width: 7% !important; font-size: 18px; border-style:solid; border-color:black; border-width: 1px 1px 1px 1px;" colspan="1"> {{$ppYear}}</td>
				    	<td class="grey center" style="width: 7% !important; font-size: 18px; border-style:solid; border-color:black; border-width: 1px 1px 1px 1px;" colspan="5"> VAR % </td>
				    </tr>				   
				    <tr>
				    	<td class="lightGrey center" style="width: 7% !important; font-size: 18px; border-style:solid; border-color:black; border-width: 1px;"> MONTH </td>
				    	<td class="lightGrey center" style="width: 7% !important; font-size: 18px; border-style:solid; border-color:black; border-width: 1px;"> PLATAFORM </td>
				    	<td class="smBlue center" style="width: 7% !important; font-size: 18px;"> CMAPS </td>
				    	<td class="smBlue center" style="width: 7% !important; font-size: 18px;"> PLAN </td>
				    	<td class="smBlue center" style="width: 7% !important; font-size: 18px; "> FCAST </td>
				    	<td class="smBlue center" style="width: 7% !important; font-size: 18px;"> SCREENSHOT </td>
				    	<td class="smBlue center" style="width: 7% !important; font-size: 18px;"> SAP </td>
				    	<td class="smBlue center" style="width: 7% !important; font-size: 18px;"> SAP </td>
				    	<td class="smBlue center" style="width: 7% !important; font-size: 18px;"> Plan {{$cYear}} (%) </td>
				    	<td class="smBlue center" style="width: 7% !important; font-size: 18px;"> Fcst {{$cYear}} (%) </td>
				    	<td class="smBlue center" style="width: 7% !important; font-size: 18px;"> SS {{$cYear}} (%) </td>
				    	<td class="smBlue center" style="width: 7% !important; font-size: 18px;"> Sap {{$pYear}} (%) </td>
				    	<td class="smBlue center" style="width: 7% !important; font-size: 18px; border-style:solid; border-color:black; border-width: 0px 1px 0px 0px;"> Sap {{$ppYear}} (%) </td>
				    </tr>
				    @for($m = 0; $m < 3; $m++)
						 <tr>
						 	<td class="oddGrey center" style="width: 7% !important;  font-size: 16px;  border-style:solid; border-color:black; border-width: 1px;" rowspan="4"> {{strtoupper($month + $m)}} </td>
					    	<tr>
					    		<td class="odd center" style="width: 7% !important;  font-size: 18px; border-style:solid; border-color:black; border-width: 1px 1px 0px 1px;"> TV </td>
					    		<td class="odd center" style="width: 7% !important;  font-size: 18px; border-style:solid; border-color:black; border-width: 1px 0px 0px 0px;"> 0 </td>
					    		<td class="odd center" style="width: 7% !important;  font-size: 18px; border-style:solid; border-color:black; border-width: 1px 0px 0px 0px;"> 0 </td>
					    		<td class="odd center" style="width: 7% !important;  font-size: 18px; border-style:solid; border-color:black; border-width: 1px 1px 0px 0px;"> 0 </td>
					    		<td class="odd center" style="width: 7% !important;  font-size: 18px; border-style:solid; border-color:black; border-width: 1px 0px 0px 0px;"> 0 </td>
					    		<td class="odd center" style="width: 7% !important;  font-size: 18px; border-style:solid; border-color:black; border-width: 1px 1px 0px 0px;"> 0 </td>
					    		<td class="odd center" style="width: 7% !important;  font-size: 18px; border-style:solid; border-color:black; border-width: 1px 1px 0px 0px;"> 0 </td>
					    		<td class="odd center" style="width: 7% !important;  font-size: 18px; border-style:solid; border-color:black; border-width: 1px 0px 0px 0px;"> % </td>
					    		<td class="odd center" style="width: 7% !important;  font-size: 18px; border-style:solid; border-color:black; border-width: 1px 0px 0px 0px;"> % </td>
					    		<td class="odd center" style="width: 7% !important;  font-size: 18px; border-style:solid; border-color:black; border-width: 1px 0px 0px 0px;"> % </td>
					    		<td class="odd center" style="width: 7% !important;  font-size: 18px; border-style:solid; border-color:black; border-width: 1px 0px 0px 0px;"> % </td>
					    		<td class="odd center" style="width: 7% !important;  font-size: 18px; border-style:solid; border-color:black; border-width: 1px 1px 0px 0px;"> % </td>
					    	</tr>
					    	<tr>
					    		<td class="even center" style="width: 7% !important;  font-size: 18px; border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;"> ONL </td>
					    		<td class="even center" style="width: 7% !important;  font-size: 18px;"> 0 </td>
					    		<td class="even center" style="width: 7% !important;  font-size: 18px;"> 0 </td>
					    		<td class="even center" style="width: 7% !important;  font-size: 18px; border-style: solid; border-color:black; border-width: 0px 1px 0px 0px;"> 0 </td>
					    		<td class="even center" style="width: 7% !important;  font-size: 18px;"> 0 </td>
					    		<td class="even center" style="width: 7% !important;  font-size: 18px; border-style: solid; border-color:black; border-width: 0px 1px 0px 0px;"> 0 </td>
					    		<td class="even center" style="width: 7% !important;  font-size: 18px; border-style: solid; border-color:black; border-width: 0px 1px 0px 0px;"> 0 </td>
					    		<td class="even center" style="width: 7% !important;  font-size: 18px;"> % </td>
					    		<td class="even center" style="width: 7% !important;  font-size: 18px;"> % </td>
					    		<td class="even center" style="width: 7% !important;  font-size: 18px;"> % </td>
					    		<td class="even center" style="width: 7% !important;  font-size: 18px;"> % </td>
					    		<td class="even center" style="width: 7% !important;  font-size: 18px; border-style:solid; border-color:black; border-width: 0px 1px 0px 0px;"> % </td>	
					    	</tr>				    	
					    	<tr>
					    		<td class="odd center" style="width: 7% !important;  font-size: 18px; border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;"> TOTAL</td>
					    		<td class="odd center" style="width: 7% !important;  font-size: 18px; "> 0 </td>
					    		<td class="odd center" style="width: 7% !important;  font-size: 18px; "> 0 </td>
					    		<td class="odd center" style="width: 7% !important;  font-size: 18px; border-style: solid; border-color:black; border-width: 0px 1px 0px 0px;"> 0 </td>
					    		<td class="odd center" style="width: 7% !important;  font-size: 18px; "> 0 </td>
					    		<td class="odd center" style="width: 7% !important;  font-size: 18px; border-style: solid; border-color:black; border-width: 0px 1px 0px 0px;"> 0 </td>
					    		<td class="odd center" style="width: 7% !important;  font-size: 18px; border-style: solid; border-color:black; border-width: 0px 1px 0px 0px;"> 0 </td>
					    		<td class="odd center" style="width: 7% !important;  font-size: 18px; "> % </td>
					    		<td class="odd center" style="width: 7% !important;  font-size: 18px; "> % </td>
					    		<td class="odd center" style="width: 7% !important;  font-size: 18px; "> % </td>
					    		<td class="odd center" style="width: 7% !important;  font-size: 18px; "> % </td>
					    		<td class="odd center" style="width: 7% !important;  font-size: 18px; border-style:solid; border-color:black; border-width: 0px 1px 0px 0px;"> % </td>
					    	</tr>				    	
					    </tr>
					@endfor
					<tr>
				    	<td class="oddGrey center" style="width: 7% !important;  font-size: 16px; border-style:solid; border-color:black; border-width: 1px;" rowspan="4"> YTD (JAN-{{strtoupper($month)}}) </td>
				    	<tr>
				    		<td class="odd center" style="width: 7% !important;  font-size: 18px; border-style:solid; border-color:black; border-width: 1px 1px 0px 1px;"> TV </td>
					    		<td class="odd center" style="width: 7% !important;  font-size: 18px; border-style:solid; border-color:black; border-width: 1px 0px 0px 0px;"> 0 </td>
					    		<td class="odd center" style="width: 7% !important;  font-size: 18px; border-style:solid; border-color:black; border-width: 1px 0px 0px 0px;"> 0 </td>
					    		<td class="odd center" style="width: 7% !important;  font-size: 18px; border-style:solid; border-color:black; border-width: 1px 1px 0px 0px;"> 0 </td>
					    		<td class="odd center" style="width: 7% !important;  font-size: 18px; border-style:solid; border-color:black; border-width: 1px 0px 0px 0px;"> 0 </td>
					    		<td class="odd center" style="width: 7% !important;  font-size: 18px; border-style:solid; border-color:black; border-width: 1px 1px 0px 0px;"> 0 </td>
					    		<td class="odd center" style="width: 7% !important;  font-size: 18px; border-style:solid; border-color:black; border-width: 1px 1px 0px 0px;"> 0 </td>
					    		<td class="odd center" style="width: 7% !important;  font-size: 18px; border-style:solid; border-color:black; border-width: 1px 0px 0px 0px;"> % </td>
					    		<td class="odd center" style="width: 7% !important;  font-size: 18px; border-style:solid; border-color:black; border-width: 1px 0px 0px 0px;"> % </td>
					    		<td class="odd center" style="width: 7% !important;  font-size: 18px; border-style:solid; border-color:black; border-width: 1px 0px 0px 0px;"> % </td>
					    		<td class="odd center" style="width: 7% !important;  font-size: 18px; border-style:solid; border-color:black; border-width: 1px 0px 0px 0px;"> % </td>
					    		<td class="odd center" style="width: 7% !important;  font-size: 18px; border-style:solid; border-color:black; border-width: 1px 1px 0px 0px;"> % </td>
					    	</tr>
					    	<tr>
					    		<td class="even center" style="width: 7% !important;  font-size: 18px; border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;"> ONL </td>
					    		<td class="even center" style="width: 7% !important;  font-size: 18px;"> 0 </td>
					    		<td class="even center" style="width: 7% !important;  font-size: 18px;"> 0 </td>
					    		<td class="even center" style="width: 7% !important;  font-size: 18px; border-style:solid; border-color:black; border-width: 0px 1px 0px 0px;"> 0 </td>
					    		<td class="even center" style="width: 7% !important;  font-size: 18px;"> 0 </td>
					    		<td class="even center" style="width: 7% !important;  font-size: 18px; border-style: solid; border-color:black; border-width: 0px 1px 0px 0px;"> 0 </td>
					    		<td class="even center" style="width: 7% !important;  font-size: 18px; border-style: solid; border-color:black; border-width: 0px 1px 0px 0px;"> 0 </td>
					    		<td class="even center" style="width: 7% !important;  font-size: 18px;"> % </td>
					    		<td class="even center" style="width: 7% !important;  font-size: 18px;"> % </td>
					    		<td class="even center" style="width: 7% !important;  font-size: 18px;"> % </td>
					    		<td class="even center" style="width: 7% !important;  font-size: 18px;"> % </td>
					    		<td class="even center" style="width: 7% !important;  font-size: 18px; border-style:solid; border-color:black; border-width: 0px 1px 0px 0px;"> % </td>	
					    	</tr>				    	
					    	<tr>
					    		<td class="odd center" style="width: 7% !important;  font-size: 18px; border-style:solid; border-color:black; border-width: 0px 1px 1px 1px;"> TOTAL</td>
					    		<td class="odd center" style="width: 7% !important;  font-size: 18px; border-style:solid; border-color:black; border-width: 0px 0px 1px 0px; "> 0 </td>
					    		<td class="odd center" style="width: 7% !important;  font-size: 18px; border-style:solid; border-color:black; border-width: 0px 0px 1px 0px; "> 0 </td>
					    		<td class="odd center" style="width: 7% !important;  font-size: 18px; border-style:solid; border-color:black; border-width: 0px 1px 1px 0px; "> 0 </td>
					    		<td class="odd center" style="width: 7% !important;  font-size: 18px; border-style:solid; border-color:black; border-width: 0px 0px 1px 0px; "> 0 </td>
					    		<td class="odd center" style="width: 7% !important;  font-size: 18px; border-style:solid; border-color:black; border-width: 0px 1px 1px 0px; "> 0 </td>
					    		<td class="odd center" style="width: 7% !important;  font-size: 18px; border-style:solid; border-color:black; border-width: 0px 1px 1px 0px; "> 0 </td>
					    		<td class="odd center" style="width: 7% !important;  font-size: 18px; border-style:solid; border-color:black; border-width: 0px 0px 1px 0px; "> % </td>
					    		<td class="odd center" style="width: 7% !important;  font-size: 18px; border-style:solid; border-color:black; border-width: 0px 0px 1px 0px; "> % </td>
					    		<td class="odd center" style="width: 7% !important;  font-size: 18px; border-style:solid; border-color:black; border-width: 0px 0px 1px 0px; "> % </td>
					    		<td class="odd center" style="width: 7% !important;  font-size: 18px; border-style:solid; border-color:black; border-width: 0px 0px 1px 0px; "> % </td>
					    		<td class="odd center" style="width: 7% !important;  font-size: 18px; border-style:solid; border-color:black; border-width: 0px 1px 1px 0px;"> % </td>
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
				        <td class='sony center' style="width: 100% !important; font-size: 22px;" colspan="13"> Sony ( {{$currencyName}} / {{strtoupper($value)}} )</td>
				    </tr>
				    <tr>
				    	<td class='grey center' style="width: 7% !important; font-size: 22px; border-style:solid; border-color:black; border-width: 1px 0px 1px 1px;"> LOG </td>
				    	<td class="grey center" style="width: 7% !important; font-size: 18px; border-style:solid; border-color:black; border-width: 1px 1px 1px 0px;"> {{$day}}/{{$month}} </td>
				    	<td class="grey center" style="width: 7% !important; font-size: 18px; border-style:solid; border-color:black; border-width: 1px 1px 1px 1px;" colspan="3"> {{$cYear}}</td>
				    	<td class="grey center" style="width: 7% !important; font-size: 18px; border-style:solid; border-color:black; border-width: 1px 1px 1px 1px;" colspan="2"> {{$pYear}}</td>
				    	<td class="grey center" style="width: 7% !important; font-size: 18px; border-style:solid; border-color:black; border-width: 1px 1px 1px 1px;" colspan="1"> {{$ppYear}}</td>
				    	<td class="grey center" style="width: 7% !important; font-size: 18px; border-style:solid; border-color:black; border-width: 1px 1px 1px 1px;" colspan="5"> VAR % </td>
				    </tr>				   
				    <tr>
				    	<td class="lightGrey center" style="width: 7% !important; font-size: 18px; border-style:solid; border-color:black; border-width: 1px;"> MONTH </td>
				    	<td class="lightGrey center" style="width: 7% !important; font-size: 18px; border-style:solid; border-color:black; border-width: 1px;"> PLATAFORM </td>
				    	<td class="smBlue center" style="width: 7% !important; font-size: 18px;"> CMAPS </td>
				    	<td class="smBlue center" style="width: 7% !important; font-size: 18px;"> PLAN </td>
				    	<td class="smBlue center" style="width: 7% !important; font-size: 18px; "> FCAST </td>
				    	<td class="smBlue center" style="width: 7% !important; font-size: 18px;"> SCREENSHOT </td>
				    	<td class="smBlue center" style="width: 7% !important; font-size: 18px;"> SAP </td>
				    	<td class="smBlue center" style="width: 7% !important; font-size: 18px;"> SAP </td>
				    	<td class="smBlue center" style="width: 7% !important; font-size: 18px;"> Plan {{$cYear}} (%) </td>
				    	<td class="smBlue center" style="width: 7% !important; font-size: 18px;"> Fcst {{$cYear}} (%) </td>
				    	<td class="smBlue center" style="width: 7% !important; font-size: 18px;"> SS {{$cYear}} (%) </td>
				    	<td class="smBlue center" style="width: 7% !important; font-size: 18px;"> Sap {{$pYear}} (%) </td>
				    	<td class="smBlue center" style="width: 7% !important; font-size: 18px; border-style:solid; border-color:black; border-width: 0px 1px 0px 0px;"> Sap {{$ppYear}} (%) </td>
				    </tr>
				    @for($m = 0; $m < 3; $m++)
						 <tr>
						 	<td class="oddGrey center" style="width: 7% !important;  font-size: 16px;  border-style:solid; border-color:black; border-width: 1px;" rowspan="4"> {{strtoupper($month + $m)}} </td>
					    	<tr>
					    		<td class="odd center" style="width: 7% !important;  font-size: 18px; border-style:solid; border-color:black; border-width: 1px 1px 0px 1px;"> TV </td>
					    		<td class="odd center" style="width: 7% !important;  font-size: 18px; border-style:solid; border-color:black; border-width: 1px 0px 0px 0px;"> 0 </td>
					    		<td class="odd center" style="width: 7% !important;  font-size: 18px; border-style:solid; border-color:black; border-width: 1px 0px 0px 0px;"> 0 </td>
					    		<td class="odd center" style="width: 7% !important;  font-size: 18px; border-style:solid; border-color:black; border-width: 1px 1px 0px 0px;"> 0 </td>
					    		<td class="odd center" style="width: 7% !important;  font-size: 18px; border-style:solid; border-color:black; border-width: 1px 0px 0px 0px;"> 0 </td>
					    		<td class="odd center" style="width: 7% !important;  font-size: 18px; border-style:solid; border-color:black; border-width: 1px 1px 0px 0px;"> 0 </td>
					    		<td class="odd center" style="width: 7% !important;  font-size: 18px; border-style:solid; border-color:black; border-width: 1px 1px 0px 0px;"> 0 </td>
					    		<td class="odd center" style="width: 7% !important;  font-size: 18px; border-style:solid; border-color:black; border-width: 1px 0px 0px 0px;"> % </td>
					    		<td class="odd center" style="width: 7% !important;  font-size: 18px; border-style:solid; border-color:black; border-width: 1px 0px 0px 0px;"> % </td>
					    		<td class="odd center" style="width: 7% !important;  font-size: 18px; border-style:solid; border-color:black; border-width: 1px 0px 0px 0px;"> % </td>
					    		<td class="odd center" style="width: 7% !important;  font-size: 18px; border-style:solid; border-color:black; border-width: 1px 0px 0px 0px;"> % </td>
					    		<td class="odd center" style="width: 7% !important;  font-size: 18px; border-style:solid; border-color:black; border-width: 1px 1px 0px 0px;"> % </td>
					    	</tr>
					    	<tr>
					    		<td class="even center" style="width: 7% !important;  font-size: 18px; border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;"> ONL </td>
					    		<td class="even center" style="width: 7% !important;  font-size: 18px;"> 0 </td>
					    		<td class="even center" style="width: 7% !important;  font-size: 18px;"> 0 </td>
					    		<td class="even center" style="width: 7% !important;  font-size: 18px; border-style: solid; border-color:black; border-width: 0px 1px 0px 0px;"> 0 </td>
					    		<td class="even center" style="width: 7% !important;  font-size: 18px;"> 0 </td>
					    		<td class="even center" style="width: 7% !important;  font-size: 18px; border-style: solid; border-color:black; border-width: 0px 1px 0px 0px;"> 0 </td>
					    		<td class="even center" style="width: 7% !important;  font-size: 18px; border-style: solid; border-color:black; border-width: 0px 1px 0px 0px;"> 0 </td>
					    		<td class="even center" style="width: 7% !important;  font-size: 18px;"> % </td>
					    		<td class="even center" style="width: 7% !important;  font-size: 18px;"> % </td>
					    		<td class="even center" style="width: 7% !important;  font-size: 18px;"> % </td>
					    		<td class="even center" style="width: 7% !important;  font-size: 18px;"> % </td>
					    		<td class="even center" style="width: 7% !important;  font-size: 18px; border-style:solid; border-color:black; border-width: 0px 1px 0px 0px;"> % </td>	
					    	</tr>				    	
					    	<tr>
					    		<td class="odd center" style="width: 7% !important;  font-size: 18px; border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;"> TOTAL</td>
					    		<td class="odd center" style="width: 7% !important;  font-size: 18px; "> 0 </td>
					    		<td class="odd center" style="width: 7% !important;  font-size: 18px; "> 0 </td>
					    		<td class="odd center" style="width: 7% !important;  font-size: 18px; border-style: solid; border-color:black; border-width: 0px 1px 0px 0px;"> 0 </td>
					    		<td class="odd center" style="width: 7% !important;  font-size: 18px; "> 0 </td>
					    		<td class="odd center" style="width: 7% !important;  font-size: 18px; border-style: solid; border-color:black; border-width: 0px 1px 0px 0px;"> 0 </td>
					    		<td class="odd center" style="width: 7% !important;  font-size: 18px; border-style: solid; border-color:black; border-width: 0px 1px 0px 0px;"> 0 </td>
					    		<td class="odd center" style="width: 7% !important;  font-size: 18px; "> % </td>
					    		<td class="odd center" style="width: 7% !important;  font-size: 18px; "> % </td>
					    		<td class="odd center" style="width: 7% !important;  font-size: 18px; "> % </td>
					    		<td class="odd center" style="width: 7% !important;  font-size: 18px; "> % </td>
					    		<td class="odd center" style="width: 7% !important;  font-size: 18px; border-style:solid; border-color:black; border-width: 0px 1px 0px 0px;"> % </td>
					    	</tr>				    	
					    </tr>
					@endfor
					<tr>
				    	<td class="oddGrey center" style="width: 7% !important;  font-size: 16px; border-style:solid; border-color:black; border-width: 1px;" rowspan="4"> YTD (JAN-{{strtoupper($month)}}) </td>
				    	<tr>
				    		<td class="odd center" style="width: 7% !important;  font-size: 18px; border-style:solid; border-color:black; border-width: 1px 1px 0px 1px;"> TV </td>
					    		<td class="odd center" style="width: 7% !important;  font-size: 18px; border-style:solid; border-color:black; border-width: 1px 0px 0px 0px;"> 0 </td>
					    		<td class="odd center" style="width: 7% !important;  font-size: 18px; border-style:solid; border-color:black; border-width: 1px 0px 0px 0px;"> 0 </td>
					    		<td class="odd center" style="width: 7% !important;  font-size: 18px; border-style:solid; border-color:black; border-width: 1px 1px 0px 0px;"> 0 </td>
					    		<td class="odd center" style="width: 7% !important;  font-size: 18px; border-style:solid; border-color:black; border-width: 1px 0px 0px 0px;"> 0 </td>
					    		<td class="odd center" style="width: 7% !important;  font-size: 18px; border-style:solid; border-color:black; border-width: 1px 1px 0px 0px;"> 0 </td>
					    		<td class="odd center" style="width: 7% !important;  font-size: 18px; border-style:solid; border-color:black; border-width: 1px 1px 0px 0px;"> 0 </td>
					    		<td class="odd center" style="width: 7% !important;  font-size: 18px; border-style:solid; border-color:black; border-width: 1px 0px 0px 0px;"> % </td>
					    		<td class="odd center" style="width: 7% !important;  font-size: 18px; border-style:solid; border-color:black; border-width: 1px 0px 0px 0px;"> % </td>
					    		<td class="odd center" style="width: 7% !important;  font-size: 18px; border-style:solid; border-color:black; border-width: 1px 0px 0px 0px;"> % </td>
					    		<td class="odd center" style="width: 7% !important;  font-size: 18px; border-style:solid; border-color:black; border-width: 1px 0px 0px 0px;"> % </td>
					    		<td class="odd center" style="width: 7% !important;  font-size: 18px; border-style:solid; border-color:black; border-width: 1px 1px 0px 0px;"> % </td>
					    	</tr>
					    	<tr>
					    		<td class="even center" style="width: 7% !important;  font-size: 18px; border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;"> ONL </td>
					    		<td class="even center" style="width: 7% !important;  font-size: 18px;"> 0 </td>
					    		<td class="even center" style="width: 7% !important;  font-size: 18px;"> 0 </td>
					    		<td class="even center" style="width: 7% !important;  font-size: 18px; border-style:solid; border-color:black; border-width: 0px 1px 0px 0px;"> 0 </td>
					    		<td class="even center" style="width: 7% !important;  font-size: 18px;"> 0 </td>
					    		<td class="even center" style="width: 7% !important;  font-size: 18px; border-style: solid; border-color:black; border-width: 0px 1px 0px 0px;"> 0 </td>
					    		<td class="even center" style="width: 7% !important;  font-size: 18px; border-style: solid; border-color:black; border-width: 0px 1px 0px 0px;"> 0 </td>
					    		<td class="even center" style="width: 7% !important;  font-size: 18px;"> % </td>
					    		<td class="even center" style="width: 7% !important;  font-size: 18px;"> % </td>
					    		<td class="even center" style="width: 7% !important;  font-size: 18px;"> % </td>
					    		<td class="even center" style="width: 7% !important;  font-size: 18px;"> % </td>
					    		<td class="even center" style="width: 7% !important;  font-size: 18px; border-style:solid; border-color:black; border-width: 0px 1px 0px 0px;"> % </td>	
					    	</tr>				    	
					    	<tr>
					    		<td class="odd center" style="width: 7% !important;  font-size: 18px; border-style:solid; border-color:black; border-width: 0px 1px 1px 1px;"> TOTAL</td>
					    		<td class="odd center" style="width: 7% !important;  font-size: 18px; border-style:solid; border-color:black; border-width: 0px 0px 1px 0px; "> 0 </td>
					    		<td class="odd center" style="width: 7% !important;  font-size: 18px; border-style:solid; border-color:black; border-width: 0px 0px 1px 0px; "> 0 </td>
					    		<td class="odd center" style="width: 7% !important;  font-size: 18px; border-style:solid; border-color:black; border-width: 0px 1px 1px 0px; "> 0 </td>
					    		<td class="odd center" style="width: 7% !important;  font-size: 18px; border-style:solid; border-color:black; border-width: 0px 0px 1px 0px; "> 0 </td>
					    		<td class="odd center" style="width: 7% !important;  font-size: 18px; border-style:solid; border-color:black; border-width: 0px 1px 1px 0px; "> 0 </td>
					    		<td class="odd center" style="width: 7% !important;  font-size: 18px; border-style:solid; border-color:black; border-width: 0px 1px 1px 0px; "> 0 </td>
					    		<td class="odd center" style="width: 7% !important;  font-size: 18px; border-style:solid; border-color:black; border-width: 0px 0px 1px 0px; "> % </td>
					    		<td class="odd center" style="width: 7% !important;  font-size: 18px; border-style:solid; border-color:black; border-width: 0px 0px 1px 0px; "> % </td>
					    		<td class="odd center" style="width: 7% !important;  font-size: 18px; border-style:solid; border-color:black; border-width: 0px 0px 1px 0px; "> % </td>
					    		<td class="odd center" style="width: 7% !important;  font-size: 18px; border-style:solid; border-color:black; border-width: 0px 0px 1px 0px; "> % </td>
					    		<td class="odd center" style="width: 7% !important;  font-size: 18px; border-style:solid; border-color:black; border-width: 0px 1px 1px 0px;"> % </td>
					    	</tr>
				    	</tr>
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

</div>

<div id="vlau"></div>
	
@endsection