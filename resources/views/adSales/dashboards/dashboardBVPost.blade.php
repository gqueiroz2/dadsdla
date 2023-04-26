@extends('layouts.mirror')
@section('title', 'Dashboards BV')
@section('head')	
	<script src="/js/dashboards-bv.js"></script>
    <?php include(resource_path('views/auth.php')); ?>
@endsection

@section('content')
	<div class="container-fluid">
		<div class="row justify-content-end">
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
						<div class="col" style='margin-right: 13px;'>
							<label class="labelLeft"><span class="bold"> &nbsp; </span> </label>
							<input type="submit" value="Generate" class="btn btn-primary" style="width: 100%;">							
						</div>
					</div>
				</form>
			</div>
		</div>

		<div class="container-fluid">
        	<div class="row justify-content-end mt-3">
	            <div class="col-2" style="color: #0070c0;font-size: 25px;">
	                AVB - Control Panel
	            </div>
	            <div class="col-2">
	                <button type="button" id="excel" class="btn btn-primary" style="width: 100%">
	                    Generate Excel
	                </button>
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
				<div class="row">
					<div class="col"> 				       			
						<table id='table' style='width: 100%; zoom: 85%;'>
							<tr>
								<td class="col medBlue center" style="font-size:16px; width:3%; border-style:solid; border-color:black; border-width: 1px 1px 1px 1px;">UPDATED DATE</td>
								<td class="col oddGrey center" style="font-size:14px; width:3%; border-style:solid; border-color:black; border-width: 1px 1px 1px 1px;">{{$updateInfo[0]['updateDate']}}</td>
							</tr>
							<tr>
								<td class="col medBlue center" style="font-size:16px; width:3%; border-style:solid; border-color:black; border-width: 1px 1px 1px 1px;">SALES REP</td>
								<td class="col oddGrey center" style="font-size:14px; width:3%; border-style:solid; border-color:black; border-width: 1px 1px 1px 1px;">{{$updateInfo[0]['salesRep']}}</td>
							</tr>
							<tr>
								<th class='newBlue center' colspan='10' style='font-size:22px; width:100%; border-style:solid; border-color:black; border-width: 1px 1px 1px 1px;'> CONTROL PANEL - {{strtoupper($agencyGroupName)}}</th>
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
									<td class="{{$color[$b]}} " style="border-style:solid; border-color:black; border-width: 0px 1px 0px 0px;"><input readonly='true' type="text" name="real-{{$b}}" id="real-{{$b}}" style="background-color:transparent; border:none; font-weight:bold; text-align:center;" value="{{number_format($bvTest[$b][$year],0,',','.')}}"></td>
									<td class="{{$color[$b]}} "  style="border-style:solid; border-color:black; border-width: 0px 1px 0px 0px;"><input placeholder="0" pattern="^\$\d{3.3}(.\d{3})*(\,\d+)?" data-type="currency" type="text" name="forecast-{{$b}}" id="forecast-{{$b}}" style="background-color:transparent; border:none; font-weight:bold; text-align:center;" value="{{number_format($bvTest[$b]['prev'],0,',','.')}}"></td>
									<td class="{{$color[$b]}} " style="border-style:solid; border-color:black; border-width: 0px 1px 0px 0px;"><input pattern="^\$\d{3.3}(.\d{3})*(\,\d+)?" data-type="currency" readonly='true' type="text" name="forecast-total-{{$b}}" id="forecast-total-{{$b}}" style="background-color:transparent; border:none; font-weight:bold; text-align:center;" value="{{number_format($bvTest[$b]['prevActualSum'],0,',','.')}}"></td>
									<td class="{{$color[$b]}} " style="border-style:solid; border-color:black; border-width: 0px 1px 0px 0px;"><input placeholder="0" pattern="^\$\d{3.3}(.\d{3})*(\,\d+)?" data-type="currency" type="text" name="forecast-spt-{{$b}}" id="forecast-spt-{{$b}}" style="background-color:transparent; border:none; font-weight:bold; text-align:center;" value={{number_format($bvTest[$b]['sptPrev'],0,',','.')}}></td>
									<td class="{{$color[$b]}}" style="border-style:solid; border-color:black; border-width: 0px 1px 0px 0px;">{{$bvTest[$b]['variation']}}%</td>
									<td class="{{$color[$b]}}" style="border-style:solid; border-color:black; border-width: 0px 1px 0px 0px;"><input type="text" maxlength="300" name="status-{{$b}}" id="status-{{$b}}" style="width: 100%; background-color:transparent; border:none; font-weight:bold;" value="{{$bvTest[$b]['status']}}"></td>
								</tr>
							@endfor
							<tr style='font-size:16px;'>
								<td class="smBlue center" style="border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;">TOTAL</td>
								<td class="smBlue center" style="border-style:solid; border-color:black; border-width: 0px 1px 0px 0px;"></td>
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
				         <div class='col'>
			                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalExemplo" style="width: 100%">
			                  Add Client to Agency Group
			                </button>
			            </div>
		            <!-- Modal to insert a new client -->
			            <div class="modal fade" id="modalExemplo" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
			              <div class="modal-dialog" role="document">
			                <div class="modal-content">
			                  <div class="modal-header">
			                    <h5 class="modal-title" id="exampleModalLabel">New Client</h5>
				                    <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
				                      <span aria-hidden="true">&times;</span>
				                    </button>
			                  	</div>
			                  <div class="modal-body">
			                  	<div class="row justify-content-center">          
							 		<div class="col">		
										<div class="form-group">
											<label><b> client: </b></label> 
											 <select class='selectpicker' id='client' name='client[]' data-selected-text-format='count' data-width='100%' class='form-control' data-live-search='true'>
									            <option selected='true' value="">Select a Client</option>
									            @for ($s=0; $s < sizeof($list); $s++)
									                <option value='{{$list[$s]['id']}},{{$list[$s]['aID']}}'> {{$list[$s]["client"]}} - {{$list[$s]["agency"]}} </option> 
                        						@endfor
									        </select>												
										</div>
									</div>
								</div>
			                  </div>
			                  <div class="modal-footer">
			                  	 <button type="submit" class="btn btn-primary" style="width: 100%;">Submit</button>
			                  </div>
			                </div>
			              </div>
			            </div>
					</div>
				</div>
			</div>
		</form>
	<div id="vlau"></div>
	<div id="vlau1"></div>


<!-- javascript to be able to edit the front and make calculations of numbers -->
<script type="text/javascript">
	$("input[data-type='currency']").on({
	    keyup: function() {
	      formatCurrency($(this));
	    },
	    blur: function() { 
	      formatCurrency($(this), "blur");
	    }
	});


	function formatNumber(n) {
	  // format number 1000000 to 1,234,567
	  return n.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ".")
	}


	function formatCurrency(input, blur) {
	  // appends $ to value, validates decimal side
	  // and puts cursor back in right position.
	  
	  // get input value
	  var input_val = input.val();
	  
	  // don't validate empty input
	  if (input_val === "") { return; }
	  
	  // original length
	  var original_len = input_val.length;

	  // initial caret position 
	  var caret_pos = input.prop("selectionStart");
	    
	  // check for decimal
	  if (input_val.indexOf(",") >= 0) {

	    // get position of first decimal
	    // this prevents multiple decimals from
	    // being entered
	    var decimal_pos = input_val.indexOf(",");

	    // split number by decimal point
	    var left_side = input_val.substring(0, decimal_pos);
	    var right_side = input_val.substring(decimal_pos);

	    // add commas to left side of number
	    left_side = formatNumber(left_side);

	    // validate right side
	    right_side = formatNumber(right_side);
	    
	    // On blur make sure 2 numbers after decimal
	    if (blur === "blur") {
	      right_side += "00";
	    }
	    
	    // Limit decimal to only 2 digits
	    right_side = right_side.substring(0, 2);

	    // join number by .
	    input_val =  left_side + "," + right_side;

	  } else {
	    // no decimal entered
	    // add commas to number
	    // remove all non-digits
	    input_val = formatNumber(input_val);
	    input_val = input_val;
	    
	  /*  // final formatting
	    if (blur === "blur") {
	      input_val += ",00";
	    }*/
	  }
	  
	  // send updated string to input
	  input.val(input_val);

	  // put caret back in the right position
	  var updated_len = input_val.length;
	  caret_pos = updated_len - original_len + caret_pos;
	  input[0].setSelectionRange(caret_pos, caret_pos);
	}



	$(document).ready(function () {    
        $('.numberonly').keypress(function (e) {    
            var charCode = (e.which) ? e.which : event.keyCode    
            if (String.fromCharCode(charCode).match('/\B(?=(\d{3})+(?!\d))/g, "."'))  
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
	    	$("#forecast-total-" + {{$i}}).val(forecastC);

		@endfor

	};	

</script>

<!-- javascript to make the excel export -->
<script type="text/javascript">
            
    $(document).ready(function(){

        ajaxSetup();

        $('#excel').click(function(event){

            var agencyGroup = "<?php echo $agencyGroup; ?>";
            var currency = "<?php echo $currency; ?>";
            var value = "<?php echo $value; ?>";
            var salesRep = "<?php echo $salesRep; ?>";

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
                url: "/generate/excel/dashboard/dashBV",
                type: "POST",
                data: {agencyGroup,currency,value,salesRep,title, typeExport, auxTitle},
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



    

    