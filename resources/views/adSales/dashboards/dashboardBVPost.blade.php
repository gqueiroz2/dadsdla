@extends('layouts.mirror')
@section('title', 'Dashboards Overview')
@section('head')	
	<script src="/js/dashboards-bv.js"></script>
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
							<label class='labelLeft'><span class="bold">Sales Rep:</span></label>
							@if ($errors->has('salesRep'))
								<label style="color: red;">* Required</label>
							@endif
							{{ $render->salesRep2()}}
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
		<form method="POST" runat="server" name="tableForm" onkeyup="calculate()"> 
			 <div class="col-2">
                <input type="submit" id="button" value="Save" class="btn btn-primary"
                    style="width: 100%">
            </div>
			
			<div class="container-fluid" id="body">
				<div class="row">
					<div class="col"> 
						<table class="table-responsive" style='width: 100%; zoom: 85%;font-size: 16px;'>
							<tr class="center">
				        		<td style="width: 7% !important; background-color: white;"> &nbsp; </td>
				        	</tr>
				        </table>
				            
				       		@csrf 	
							<table class="table-responsive" style='width: 100%; zoom: 85%; border-style:solid; border-color:black; border-width: 1px 1px 1px 1px;'>
								<tr>
									<th class='newBlue center' colspan='9' style='font-size:22px; width:100%;'> Control Panel - {{$agencyGroupName}}</th>
								</tr>
								<tr class="medBlue center" style="font-size:16px; border-style:solid; border-color:black; border-width: 1px 1px 1px 1px;">
									<td class="col" style="width:12%; border-style:solid; border-color:black; border-width: 0px 1px 0px 0px;">Client</td>
									<td class="col" style="width:6%; border-style:solid; border-color:black; border-width: 0px 1px 0px 0px;">{{$year-2}}</td>
									<td class="col" style="width:6%; border-style:solid; border-color:black; border-width: 0px 1px 0px 0px;">{{$year-1}}</td>
									<td class="col" style="width:6%; border-style:solid; border-color:black; border-width: 0px 1px 0px 0px;">{{$year}}</td>
									<td class="col oddGrey" style="width:6%; border-style:solid; border-color:black; border-width: 0px 1px 0px 0px;">Forecast {{$year}}</td>
									<td class="col" style="width:6%; border-style:solid; border-color:black; border-width: 0px 1px 0px 0px;">Total {{$year}}</td>
									<td class="col oddGrey" style="width:6%; border-style:solid; border-color:black; border-width: 0px 1px 0px 0px;">Forecast SPT {{$year}}</td>
									<td class="col" style="width:5%; border-style:solid; border-color:black; border-width: 0px 1px 0px 0px;">Percentage</td>
									<td class="col" style="width:14%; border-style:solid; border-color:black; border-width: 0px 1px 0px 0px;">Status</td>
								</tr>
								@for($b = 0; $b < sizeof($bvTest) ; $b++)						
									<tr class='center' style='font-size:16px;'>
										<td class="even" style="border-style:solid; border-color:black; border-width: 0px 1px 0px 0px;">{{$bvTest[$b]['client']}}</td>
										<td class="even" style="border-style:solid; border-color:black; border-width: 0px 1px 0px 0px;">{{number_format($bvTest[$b][$year-2],0,',','.')}}</td>
										<td class="even" style="border-style:solid; border-color:black; border-width: 0px 1px 0px 0px;">{{number_format($bvTest[$b][$year-1],0,',','.')}}</td>
										<td class="even numberonly" style="border-style:solid; border-color:black; border-width: 0px 1px 0px 0px;"><input readonly='true' type="text" name="real-{{$b}}" id="real-{{$b}}" style="background-color:transparent; border:none; font-weight:bold; text-align:center;" value="{{number_format($bvTest[$b][$year],0,',','.')}}"></td>
										<td class="even numberonly" style="border-style:solid; border-color:black; border-width: 0px 1px 0px 0px;"><input type="text" name="forecast-{{$b}}" id="forecast-{{$b}}" style="background-color:transparent; border:none; font-weight:bold; text-align:center;" value="{{number_format($bvTest[$b]['prev'],0,',','.')}}"></td>
										<td class="even numberonly" style="border-style:solid; border-color:black; border-width: 0px 1px 0px 0px;"><input readonly='true' type="text" name="forecast-total-{{$b}}" id="forecast-total-{{$b}}" style="background-color:transparent; border:none; font-weight:bold; text-align:center;" value="{{number_format($bvTest[$b]['prevActualSum'],0,',','.')}}"></td>
										<td class="even numberonly" style="border-style:solid; border-color:black; border-width: 0px 1px 0px 0px;"><input type="text" name="forecast-spt-{{$b}}" id="forecast-spt-{{$b}}" style="background-color:transparent; border:none; font-weight:bold; text-align:center;" value="0"></td>
										<td class="even" style="border-style:solid; border-color:black; border-width: 0px 1px 0px 0px;">{{$bvTest[$b]['variation']}}%</td>
										<td class="even" style="border-style:solid; border-color:black; border-width: 0px 1px 0px 0px;"><input type="text" name="status-{{$b}}" id="status-{{$b}}" style="width: 100%; background-color:transparent; border:none; font-weight:bold;" value="{{$bvTest[$b]['status']}}"></td>
									</tr>
								@endfor
								<tr>
									<td class="smBlue center" style="border-style:solid; border-color:black; border-width: 0px 1px 0px 0px;">TOTAL</td>
									<td class="smBlue center" style="border-style:solid; border-color:black; border-width: 0px 1px 0px 0px;"></td>
									<td class="smBlue center" style="border-style:solid; border-color:black; border-width: 0px 1px 0px 0px;"></td>
									<td class="smBlue center" style="border-style:solid; border-color:black; border-width: 0px 1px 0px 0px;"></td>
									<td class="smBlue center" style="border-style:solid; border-color:black; border-width: 0px 1px 0px 0px;"></td>
									<td class="smBlue center" style="border-style:solid; border-color:black; border-width: 0px 1px 0px 0px;"></td>
									<td class="smBlue center" style="border-style:solid; border-color:black; border-width: 0px 1px 0px 0px;"></td>
									<td class="smBlue center" style="border-style:solid; border-color:black; border-width: 0px 1px 0px 0px;"></td>
									<td class="smBlue center" style="border-style:solid; border-color:black; border-width: 0px 1px 0px 0px;"></td>
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
	<div id="vlau"></div>
	<div id="vlau1"></div>

<script type="text/javascript">

	$(document).ready(function () {    
        $('.numberonly').keypress(function (e) {    
            var charCode = (e.which) ? e.which : event.keyCode    
            if (String.fromCharCode(charCode).match(/[^0-9]/g))    
                return false;                        
        });    
    });

	$(window).keydown(function(event) {
        if (event.keyCode == 13) {
            event.preventDefault();
            return false;
        }
    });

	window.calculate = function () {

		@for ($i = 0; $i < sizeof($bvTest) ; $i++) 

			
			var forecastC = Comma(
                            handleNumber($('#forecast-' + {{$i}}).val()) + 
                            handleNumber($('#real-' + {{$i}}).val())
                            );
	    	new Intl.NumberFormat('pt-BR').format(forecastC)
	    	$("#forecast-total-" + {{$i}}).val(forecastC);

		@endfor

	};	

</script>
@endsection



    

    