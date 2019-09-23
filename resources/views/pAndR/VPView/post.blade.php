@extends('layouts.mirror')
@section('title', 'VP Report')
@section('head')	
    <?php include(resource_path('views/auth.php')); ?>
    <script src="/js/pandr.js"></script>
    <style>
    	.temporario{
    		display:block;
    		float: left;
    		clear: left;
    		width: 100%;
    	}

    	#myInput {
		  background-position: 10px 12px; /* Position the search icon */
		  background-repeat: no-repeat; /* Do not repeat the icon image */
		  width: 100%; /* Full-width */
		  font-size: 16px; /* Increase font-size */
		  border: 1px solid #ddd; /* Add a grey border */
		  margin-bottom: 12px; /* Add some space below the input */
		  text-align: center;
		}
    </style>
@endsection
@section('content')
	<div class="container-fluid">
		<div class="row justify-content-end mt-2">
			<div class="col-3" style="color: #0070c0;font-size: 25px;">
				VP Report
			</div>
		</div>
	</div>

	<form method="POST" action="{{ route('VPPost') }}" runat="server"  onsubmit="ShowLoading()">
		@csrf
		<div class="container-fluid">		
			<div class="row">
				<div class="col">
					<label class='labelLeft'><span class="bold">Region:</span></label>
					@if($errors->has('region'))
						<label style="color: red;">* Required</label>
					@endif
					@if($userLevel == 'L0' || $userLevel == 'SU')
						{{$render->region($region)}}							
					@else
						{{$render->regionFiltered($region, $regionID, $special)}}
					@endif
				</div>
				<div class="col">
					<label class='labelLeft'><span class="bold">Year:</span></label>
					@if($errors->has('year'))
						<label style="color: red;">* Required</label>
					@endif
					{{$render->year()}}
				</div>
				<div class="col">
					<label class='labelLeft'><span class="bold">Currency:</span></label>
					@if($errors->has('currency'))
						<label style="color: red;">* Required</label>
					@endif
					{{$render->currency($currency)}}
				</div>	
				<div class="col">
					<label class="labelLeft"><span class="bold"> Value: </span></label>
						@if($errors->has('value'))
							<label style="color: red;">* Required</label>
						@endif
						{{$render->value2()}}
				</div>
				<div class="col">
					<label class='labelLeft'> &nbsp; </label>
					<input style="width: 100%;" type="submit" value="Generate" class="btn btn-primary">		
				</div>			
			</div>
		</div>
	</form>
	<br>
	<div class="container-fluid">
		<div class="row justify-content-end">
			<div class="col"></div>
			<div class="col"></div>
			<div class="col"></div>
			<div class="col"></div>				
			<div class="col">				
				<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#aeSubmissions" style="width: 100%;">
				   AE Submissions
				</button>
			</div>
		</div>

		<form method="POST" action="{{ route('VPSave') }}" runat="server"  onsubmit="ShowLoading()">
			@csrf
			<div class="row justify-content-end">
				<div class="col-2">
					<label> &nbsp;</label>
					<div class="btn-group btn-group-toggle" data-toggle="buttons" style="width: 100%;">
						<label class="btn alert-primary active">
						    <input type="radio" name="options" value='save' id="option1" autocomplete="off" checked> Save
						</label>
						<label class="btn alert-success">
							<input type="radio" name="options" value='submit' id="option2" autocomplete="off"> Submit
						</label>
					</div>
				</div>
				<div class="col-2">
					<label> &nbsp; </label>
					<input type="submit" id="button" value="Save" class="btn btn-primary" style="width: 100%">		
				</div>	
			</div>


			<div class="row justify-content-center mt-2">
				@if($forRender)
						<div class="col" style="width: 100%; padding-right: 2%;">
							<center>
								{{$render->VP1($forRender)}}
							</center>
						</div>
				@else
					<div class="col-8" style="width: 100%; padding-right: 2%;">
						<div style="min-height: 100px;" class="alert alert-warning" role="alert">
							<span style="font-size:22px;">
								<center>
								There is no submissions of Forecast from AE yet!
								</center>
							</span>
						</div>
					</div>
				@endif
					
				
			</div>
		</div>
	</form>

	<!-- Modal -->
	<div class="modal fade" id="aeSubmissions" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	  	<div class="modal-dialog" role="document">
	    	<div class="modal-content">
	      		<div class="modal-header">
	        		<h5 class="modal-title" id="exampleModalLabel">AE Submissions</h5>
	        		<button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
	          			<span aria-hidden="true">&times;</span>
	        		</button>
	     		</div>
	      		<div class="modal-body">
	        		<table style="width: 100%;">
		        		<tr>
		        			<td class="lightBlue"><center> Sales Rep </center></td>
		        			<td class="lightBlue"><center> Submitted Date </center></td>
		        			<td class="lightBlue"><center> Submitted Time </center></td>
		        		</tr>
		        		<?php
		        			if(isset($salesRepListOfSubmit) && $salesRepListOfSubmit){
								for ($s=0; $s < sizeof($salesRepListOfSubmit); $s++) { 
									
									if($s%2==0){
										$clr = "odd";
									}else{
										$clr = "even";
									}

									echo "<tr>";
											echo "<td class='$clr'><center>"
														.$salesRepListOfSubmit[$s]['salesRepName'].
												 "</center></td>";
											echo "<td class='$clr'><center>"
														.$base->formatData("aaaa-mm-dd","dd/mm/aaaa",$salesRepListOfSubmit[$s]['lastModifyDate']).
												 "</center></td>";
											echo "<td class='$clr'><center>"
														.$base->formatHour("hh:mm:ss","hh:mm",$salesRepListOfSubmit[$s]['lastModifyTime']).
												 "</center></td>";
									echo "</tr>";


								}
							}
						?>
					</table>
	      		</div>
	      		<div class="modal-footer">
	        		<button type="button" class="btn btn-primary" data-dismiss="modal">Fechar</button>
	      		</div>
	    	</div>
	  	</div>
	</div>

	@if($forRender)
		<script>

			function myFunc(){
				var input, filter, table1, table2, tr1, tr2, td1, td2, i, txtValue;

				input = document.getElementById("myInput");
				filter = input.value.toUpperCase();
				table1 = document.getElementById("table1");
				table2 = document.getElementById("table2");
				tr1 = table1.getElementsByTagName("tr");
				tr2 = table2.getElementsByTagName("tr");

				for (i = 0; i < tr1.length; i++) {
					td1 = tr1[i].getElementsByTagName("td")[0];
					td2 = tr2[i].getElementsByTagName("td")[0];
					if (td1 && td2) {
						txtValue = td1.textContent || td1.innerText;
						if (txtValue.toUpperCase().indexOf(filter)>-1) {
							tr1[i].style.display = "";
							tr2[i].style.display = "";
						}else{
							tr1[i].style.display = "none";
							tr2[i].style.display = "none";
						}
					}
				}
			}

			$(document).ready(function(){
				$("input[type=radio][name=options]").change(function(){
					if (this.value == 'save') {
						$("#button").val("Save");
					}else{
						$("#button").val("Submit");
					}
				});

				@for($c=0;$c< sizeof($client);$c++)
					$("#child-"+{{$c}}).css("height",$("#parent-"+{{$c}}).css("height"));
					
					$("#clientRF-Fy-"+{{$c}}).change(function(){
						if ($(this).val() == "") {
							$(this).val(0);
						}

						var temp = handleNumber($(this).val());

						$(this).val(Comma(temp));

						var temp2 = parseFloat(0);

						@for($c2=0;$c2<sizeof($client);$c2++)
							temp2 += handleNumber($("#clientRF-Fy-"+{{$c2}}).val());
						@endfor

						temp2 = Comma(temp2);

						$("#RF-Total-Fy").val(temp2);

						temp2 = handleNumber(temp2);

						var tmp1;
						var tmp2;

						@for($c2=0;$c2<sizeof($client);$c2++)
							tmp1 = handleNumber($("#closed-Fy-"+{{$c2}}).val());
							tmp2 = handleNumber($("#booking-Fy-"+{{$c2}}).val());
							if (tmp1 > tmp2) {
								temp2 += tmp1;
							}else{
								temp2 += tmp2;
							}
						@endfor

						temp2 = Comma(temp2);

						$("#TotalCy-Fy").val(temp2);


					});
					$("#clientRF-Cm-"+{{$c}}).change(function(){
						if ($(this).val() == "") {
							$(this).val(0);
						}

						var temp = handleNumber($(this).val());

						$(this).val(Comma(temp));

						var temp2 = parseFloat(0);
						
						@for($c2=0;$c2<sizeof($client);$c2++)
							temp2 += handleNumber($("#clientRF-Cm-"+{{$c2}}).val());
						@endfor

						temp2 = Comma(temp2);

						$("#RF-Total-Cm").val(temp2);
					});
				@endfor
			});

			function handleNumber(number){
				console.log(number);
				if(number == null){
					number = 0.0;
				}else{
					for (var i = 0; i < number.length/3; i++) {
						number = number.replace(",","");
					}

					number = parseFloat(number);
				}
				
				return number;
			}

		  	function Comma(Num) { //function to add commas to textboxes
		        Num += '';
		        Num = Num.replace(',', ''); Num = Num.replace(',', ''); Num = Num.replace(',', '');
		        Num = Num.replace(',', ''); Num = Num.replace(',', ''); Num = Num.replace(',', '');
		        x = Num.split('.');
		        x1 = x[0];
		        x2 = x.length > 1 ? '.' + x[1] : '';
		        var rgx = /(\d+)(\d{3})/;
		        while (rgx.test(x1))
		            x1 = x1.replace(rgx, '$1' + ',' + '$2');
		        return x1 + x2;
		    }

		    $('.linked').scroll(function(){

	    		$('.linked').scrollLeft($(this).scrollLeft());
			});

			

		</script>
	@endif

@endsection