@extends('layouts.mirror')
@section('title', 'Pacing Office')
@section('head')		
	<?php include(resource_path('views/auth.php')); ?>
@endsection
@section('content')
<div class="container-fluid">
	<div class="row">
		<div class="col">
			<form method="POST" action="{{ route('consolidateResultsPostOffice') }}" runat="server"  onsubmit="ShowLoading()">
				@csrf
				<div class="row">
					<div class="col-sm">
						<label class="labelLeft"><span class="bold"> Region: </span></label>
						@if($errors->has('region'))
							<label style="color: red;">* Required</label>
						@endif						
						@if($regionName == 'Brazil')
							{{$render->regionFiltered($region, $regionID, $special)}}																			
						@else
							{{$render->regionOffice($region, $regionName)}}														
						@endif													
					</div>	

					<div class="col-sm">
						<label class="labelLeft"><span class="bold"> Company: </span></label>
						@if($errors->has('company'))
							<label style="color: red;">* Required</label>
						@endif
						{{$render->company()}}
					</div>												

					<div class="col-sm">
						<label class="labelLeft"><span class="bold"> Currency: </span></label>
						@if($errors->has('currency'))
							<label style="color: red;">* Required</label>
						@endif
						@if($regionName == 'Brazil')
							{{$render->currencyOffice()}}																			
						@else
							{{$render->currencyUSD($region, $regionName)}}														
						@endif							
					</div>

					<div class="col-sm">
						<label class="labelLeft"><span class="bold"> Value: </span></label>
						@if($errors->has('value'))
							<label style="color: red;">* Required</label>
						@endif
						{{$render->value4()}}
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
		<div class="col-7"></div>
		<div class="col-sm" style="color: #0070c0;font-size: 24px; margin-right: 27px;">
			<span style="float: right;"> Pacing Office </span>
		</div>

		<div class="col-sm">
            <button type="button" id="excel" class="btn btn-primary" style="width: 100%">
                Generate Excel
            </button>               
        </div>   
	</div> 
</div>	

	<div class="row justify-content-end mt-2"></div>
	</div>
	
	<?php
		$month = array("January","February","March","April","May","June","July","August","September","October","November","December");
		$quarter = array("Q1","Q2","Q3","Q4");
		//var_dump($userRegionExcel);
	?>


	<?php $brandID = false; ?>

	<div class="container-fluid">
		<div class="row">
			<div class="col"> 
				<div class="container-fluid" style='width: 100%; zoom: 85%;font-size: 16px;'>
					<div class="row">
						<div class="col lightBlue">
							<center>
								<span style='font-size:24px;'> 										
									DLA - Consolidate - ({{$currencyS}}/{{strtoupper($value)}}) - {{$companyView}}
								</span>
							</center>
						</div>
					</div>

					<div class="row sticky-top" style= "z-index: 1 !important;">
						<table style='width: 100%; zoom: 85%;font-size: 16px;'>
							<tr class="center">
				        		<td style="width: 7% !important; background-color: white;"> &nbsp; </td>
				        	</tr>
				        </table>
						
						<table style='width: 100%; zoom: 85%; font-size: 16px;'>
							<tr class="center">
				        		<td class='darkBlue center' style="width: 7% !important;"> WBD </td>
					        	@for($m=0; $m < sizeof($month); $m++)
					        		<td class='lightGrey center' style="width: 4%;"> {{ $month[$m] }} </td>
					        	@endfor
					        		<td class='darkBlue center' style="width: 4%;"> Total </td>
			        		</tr>
						
							<tr class="center">
				        		<td class="smBlue" style="width: 7% !important;"> {{ $years[1] }} Ad Sales </td>
					        	@for($d=0; $d < sizeof($mtxDN['previousAdSales']); $d++)
					        		@if($d == 12)
					        			<?php $clr = 'smBlue'; ?>
					        		@else
					        			<?php $clr = 'medBlue'; ?>
					        		@endif

					        		<td class="{{$clr}}" style="width: 4%;"> {{ number_format($mtxDN['previousAdSales'][$d]) }} </td>
					        	@endfor
				        	</tr>

				        	<!--<tr class="center">
				        		<td class="smBlue" style="width: 7% !important;"> {{ $years[1] }} SAP </td>
					        	@for($d=0; $d < sizeof($mtxDN['previousAdSales']); $d++)
					        		@if($d == 12)
					        			<?php $clr = 'smBlue'; ?>
					        		@else
					        			<?php $clr = 'rcBlue'; ?>
					        		@endif
					        		<td class="{{$clr}}" style="width: 4%;"> {{ number_format($mtxDN['previousSAP'][$d]) }} </td>
					        	@endfor
				        	</tr>-->

				        	<tr class="center">
				        		<td class="smBlue" style="width: 7% !important;"> {{ $years[0] }} Target </td>
					        	@for($d=0; $d < sizeof($mtxDN['previousAdSales']); $d++)
					        		@if($d == 12)
					        			<?php $clr = 'smBlue'; ?>
					        		@else
					        			<?php $clr = 'medBlue'; ?>
					        		@endif
					        		<td class="{{$clr}}" style="width: 4%;"> {{ number_format($mtxDN['currentTarget'][$d]) }} </td>
					        	@endfor
				        	</tr>
				        	
				        	<tr class="center">
				        		<td class="smBlue" style="width: 7% !important;"> {{ $years[0] }} Corporate </td>
					        	@for($d=0; $d < sizeof($mtxDN['previousAdSales']); $d++)
					        		@if($d == 12)
					        			<?php $clr = 'smBlue'; ?>
					        		@else
					        			<?php $clr = 'rcBlue'; ?>
					        		@endif
					        		<td class="{{$clr}}" style="width: 4%;"> {{ number_format($mtxDN['currentCorporate'][$d]) }} </td>
					        	@endfor
				        	</tr>

				        	<tr class="center">
				        		<td class="smBlue" style="width: 7% !important;"> {{ $years[0] }} Ad Sales </td>
					        	@for($d=0; $d < sizeof($mtxDN['previousAdSales']); $d++)
					        		@if($d == 12)
					        			<?php $clr = 'smBlue'; ?>
					        		@else
					        			<?php $clr = 'medBlue'; ?>
					        		@endif
					        		<td class="{{$clr}}" style="width: 4%;"> {{ number_format($mtxDN['currentAdSales'][$d]) }} </td>
					        	@endfor
				        	</tr>

				        	<!--<tr class="center">
				        		<td class="smBlue" style="width: 7% !important;"> {{ $years[0] }} SAP </td>
					        	@for($d=0; $d < sizeof($mtxDN['previousAdSales']); $d++)
					        		@if($d == 12)
					        			<?php $clr = 'smBlue'; ?>
					        		@else
					        			<?php $clr = 'rcBlue'; ?>
					        		@endif
					        		<td class="{{$clr}}" style="width: 4%;"> {{ number_format($mtxDN['currentSAP'][$d]) }} </td>
					        	@endfor
				        	</tr>-->

				        	<tr class="center">
				        		<td class="smBlue" style="width: 7% !important;"> %({{ $years[0] }}F - {{ $years[1] }}) </td>
					        	@for($d=0; $d < sizeof($mtxDN['previousAdSales']); $d++)
					        		@if($d == 12)
					        			<?php $clr = 'smBlue'; ?>
					        		@else
					        			<?php $clr = 'medBlue'; ?>
					        		@endif
					        		<?php
					        			if($mtxDN['previousAdSales'][$d] > 0){
					        				$temp = ($mtxDN['currentCorporate'][$d]/$mtxDN['previousAdSales'][$d])*100;
					        			}else{
					        				$temp = 0.0;
					        			}
					        		?>
					        		<td class="{{$clr}}" style="width: 4%;"> {{ number_format($temp) }}% </td>
					        	@endfor
				        	</tr>

				        	<tr class="center">
				        		<td class="smBlue" style="width: 7% !important;"> %({{ $years[0] }}F - Target) </td>
					        	@for($d=0; $d < sizeof($mtxDN['previousAdSales']); $d++)
					        		@if($d == 12)
					        			<?php $clr = 'smBlue'; ?>
					        		@else
					        			<?php $clr = 'rcBlue'; ?>
					        		@endif
					        		<?php
					        			if($mtxDN['currentTarget'][$d] > 0){
					        				$temp = ($mtxDN['currentCorporate'][$d]/$mtxDN['currentTarget'][$d])*100;
					        			}else{
					        				$temp = 0.0;
					        			}
					        		?>
					        		<td class="{{$clr}}" style="width: 4%;"> {{ number_format($temp) }}% </td>
					        	@endfor
				        	</tr>
				        </table>

				        <table style='width: 100%; zoom: 85%;font-size: 16px;'>
							<tr class="center">
				        		<td style="width: 7% !important; background-color: white;"> &nbsp; </td>
				        	</tr>
				        </table>
			        </div>

			        @for ($c=0; $c < sizeof($company); $c++)
					    <div class="row mt-2">
					    	<table style='width: 100%; zoom: 85%;font-size: 16px;'>
					        	<tr class="center">
					        		<td class='lightBlue center' style="width: 7% !important;"> {{ strtoupper($company[$c]) }} </td>
						        	@for($m=0; $m < sizeof($month); $m++)
						        		<td class='lightGrey center' style="width: 4%;"> {{ $month[$m] }} </td>
						        	@endfor
						        		<td class='darkBlue center' style="width: 4%;"> Total </td>						        		
					        	</tr>

					        	<tr class="center">
					        		<td class="medBlue" style="width: 7% !important;"> {{ $years[1] }} Ad Sales </td>
						        	@for($z=0; $z < sizeof($mtx['previousCompany'][0]); $z++)
						        		@if($z == 12)
						        			<?php $clr = 'smBlue'; ?>
						        		@else
						        			<?php $clr = 'medBlue'; ?>
						        		@endif
						        		<td class="{{$clr}}" style="width: 4%;"> {{ number_format($mtx['previousCompany'][0][$z][$c]) }} </td>
						        	@endfor
						        	<td class="smBlue" style="width: 4%;"> {{ number_format($totalsCompany['previousCompany'][$c]) }} </td>
					        	</tr>
					        	
					        	<tr class="center">
					        		<td class="medBlue" style="width: 7% !important;"> {{ $years[0] }} Target </td>
						        	@for($t=0; $t < sizeof($mtx['previousCompany'][0]); $t++)
						        		@if($t == 12)
						        			<?php $clr = 'smBlue'; ?>
						        		@else
						        			<?php $clr = 'medBlue'; ?>
						        		@endif
						        		<td class="{{$clr}}" style="width: 4%;"> {{ number_format($mtx['currentTargetCompany'][0][$t][$c]) }} </td>
						        	@endfor
						        	<td class="smBlue" style="width: 4%;"> {{ number_format($totalsCompany['currentTargetCompany'][$c]) }} </td>
					        	</tr>

					        	<tr class="center">
					        		<td class="medBlue" style="width: 7% !important;"> {{ $years[0] }} Corporate </td>
						        	@for($z=0; $z < sizeof($mtx['previousCompany'][0]); $z++)
						        		@if($z == 12)
						        			<?php $clr = 'smBlue'; ?>
						        		@else
						        			<?php $clr = 'medBlue'; ?>
						        		@endif						        		
						        		<td class="{{$clr}}" style="width: 4%;"> {{ number_format($mtx['currentCorporateCompany'][0][$z][$c]) }} </td>
						        	@endfor
						        	<td class="smBlue" style="width: 4%;"> {{ number_format($totalsCompany['currentCorporateCompany'][$c]) }} </td>
					        	</tr>

					        	<tr class="center">
					        		<td class="medBlue" style="width: 7% !important;"> {{ $years[0] }} Ad Sales </td>
						        	@for($z=0; $z < sizeof($mtx['previousCompany'][0]); $z++)
						        		@if($z == 12)
						        			<?php $clr = 'smBlue'; ?>
						        		@else
						        			<?php $clr = 'medBlue'; ?>
						        		@endif
						        		<td class="{{$clr}}" style="width: 4%;"> {{ number_format($mtx['currentCompany'][0][$z][$c]) }} </td>
						        	@endfor
						        	<td class="smBlue" style="width: 4%;"> {{ number_format($totalsCompany['currentCompany'][$c]) }} </td>
					        	</tr>
					        	
					        	<tr class="center">
					        		<td class="medBlue" style="width: 7% !important;"> %({{ $years[0] }}F - {{ $years[1] }}) </td>
						        	@for($z=0; $z < sizeof($mtx['previousCompany'][0]); $z++)
						        		@if($z == 12)
						        			<?php $clr = 'smBlue'; ?>
						        		@else
						        			<?php $clr = 'medBlue'; ?>
						        		@endif
						        		<?php
						        			if($mtx['previousCompany'][0][$z][$c] > 0){
						        				$temp = ($mtx['currentCorporateCompany'][0][$z][$c]/$mtx['previousCompany'][0][$z][$c])*100;
						        				
						        			}else{
						        				$temp = 0.0;
						        			}

						        			if($totalsCompany['previousCompany'][$c] > 0){
						        				$temp2 = ($totalsCompany['currentCorporateCompany'][$c]/$totalsCompany['previousCompany'][$c])*100;
						        				
						        			}else{
						        				$temp2 = 0.0;
						        			}

						        		?>
						        		<td class="{{$clr}}" style="width: 4%;"> {{ number_format($temp) }}% </td>
						        	@endfor
						        	<td class="smBlue" style="width: 4%;"> {{ number_format($temp2) }} %</td>
					        	</tr>

					        	<tr class="center">
					        		<td class="medBlue" style="width: 7% !important;"> %({{ $years[0] }}F - Target) </td>
						        	@for($z=0; $z < sizeof($mtx['previousCompany'][0]); $z++)
						        		@if($z == 12)
						        			<?php $clr = 'smBlue'; ?>
						        		@else
						        			<?php $clr = 'rcBlue'; ?>
						        		@endif
						        		<?php
						        			if($mtx['currentTargetCompany'][0][$z][$c] > 0){
						        				$temp = ($mtx['currentCorporateCompany'][0][$z][$c]/$mtx['currentTargetCompany'][0][$z][$c])*100;
						        			}else{
						        				$temp = 0.0;
						        			}

						        			if($totalsCompany['currentTargetCompany'][$c] > 0){
						        				$temp2 = ($totalsCompany['currentCorporateCompany'][$c]/$totalsCompany['currentTargetCompany'][$c])*100;
						        				
						        			}else{
						        				$temp2 = 0.0;
						        			}
						        		?>
						        		<td class="{{$clr}}" style="width: 4%;"> {{ number_format($temp) }}% </td>
						        	@endfor
						        	<td class="smBlue" style="width: 4%;"> {{ number_format($temp2) }} %</td>
					        	</tr>
					        	
					        </table>	
					    </div>	        	
		        	@endfor		        	
		        </div>

					
        	</div>
        </div>			
	</div>

</div>

<div id="vlau"></div>

<script type="text/javascript">
        $(document).ready(function(){

            ajaxSetup();

            $('#excel').click(function(event){
                var regionExcel = "<?php echo base64_encode(json_encode($regionExcel)); ?>";
                var valueExcel = "<?php echo $valueExcel; ?>";
                var currencyExcel = "<?php echo $currencyExcel; ?>";
                var userRegionExcel = "<?php echo $userRegionExcel; ?>";
                var companyExcel = "<?php echo base64_encode(json_encode($companyExcel)); ?>";
                var companyViewExcel = "<?php echo $companyViewExcel?>";

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
                        url: "/generate/excel/results/consolidateOffice",
                        type: "POST",
                        data: {title, typeExport, regionExcel,valueExcel,currencyExcel,auxTitle, userRegionExcel,companyExcel,companyViewExcel},
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