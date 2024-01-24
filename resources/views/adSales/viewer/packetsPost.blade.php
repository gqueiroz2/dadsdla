@extends('layouts.mirror')
@section('title', 'Closed Packets')
@section('head')

    <?php include(resource_path('views/auth.php'));

        $intMonth = array('1','2','3','4','5','6','7','8','9','10','11','12');
        $month = array('January','February','March','April','May','June','July','August','September','October','November','December');
    ?>

@endsection
@section('content')

	<div class="container-fluid">
		<div class="row">
			<div class="col">
				<form method="POST" action="{{route('packetsPost')}}" runat="server" onsubmit="ShowLoading()">
					@csrf
					<div class="row">
						
						<div class="col">
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
                        {{--<div class="col">
                            <label class="labelLeft"><span class="bold"> Value: </span></label>
                            @if($errors->has('value'))
                                <label style="color: red;">* Required</label>
                            @endif
                            {{$render->value2()}}
                        </div>--}}
                        
                        <div class="col">
                            <label> &nbsp; </label>
                            <input type="submit" value="Generate" class="btn btn-primary" style="width: 100%;">     
                        </div>
                        

					</div>
				</form>
			</div>
		</div>

        <div id="vlau"></div>

		<div class="row justify-content-end mt-2">
			<div class="col-sm-4" style="color: #0070c0; font-size:24px">
                <span style="float: right; margin-right: 2.5%;">Packets</span>
            </div>
             <div class="col-2">
                <button type="button" id="excel" class="btn btn-primary" style="width: 100%">
                    Generate Excel
                </button>               
            </div>     
		</div>
        
         <div class="row mt-2 justify-content-end">
            <div class="col" style="width: 100%;">
                 <form method="POST" runat="server" action="{{ route('savePackets') }} " name="packetsSave">
                    @csrf
                    <input type='hidden' readonly='true' type="text" name="region" id="region" style="background-color:transparent; border:none; font-weight:bold; text-align:center;" value="{{$region[0]['id']}}">
                    <div class="row">   
                        <div class="col-10"></div>        
                        <div class="col-2">                            
                            <input type="submit" id="button" value="Save" class="btn btn-primary" style="width: 100%; float: right;">
                            <label class="labelLeft"><span class="bold"> &nbsp; </span> </label>
                        </div>    
                    </div> 
                @if($table)
                    <table id="tabelaDados" class="table-responsive" style='width: 100% zoom: 85%; table-layout: fixed;' >
                        <tr>
                            <td colspan="9"></td>
                            <td class="odd center" colspan="2" style="border-style:solid; border-color:black; border-width: 1px 1px 0px 1px;">SUBTOTAL</td>
                            <td class="odd center" style="border-style:solid; border-color:black; border-width: 1px 1px 0px 1px;">TOTAL</td>
                        </tr>
                        <tr>
                            <td colspan="9"></td>
                            <td class="odd center" style="border-style:solid; border-color:black; border-width: 1px 1px 0px 1px;">{{number_format($total['tv'])}}</td>
                            <td class="odd center" style="border-style:solid; border-color:black; border-width: 1px 1px 0px 1px;">{{number_format($total['digital'])}}</td>
                            <td class="odd center" style="border-style:solid; border-color:black; border-width: 1px 1px 0px 1px;">{{number_format($total['total'])}}</td>
                        </tr>
                        <tr class="darkBlue center">
                            <td>Register</td> 
                            <td>Holding</td>
                            <td >Cluster</td>
                            <td >Property</td>
                            <td >Client</td>
                            <td >Agency</td>
                            <td >Segment</td>
                            <td>AE 1</td>
                            <td>AE 2 </td>
                            <td >TV</td>
                            <td >Digital</td>
                            <td >Total</td>
                            <td >Start Month</td>
                            <td >End Month </td>
                            <td >Payment </td>
                            <td >Installments</td>
                            <td  >Quota</td>
                            <td style="width:3px;">Status</td>
                            <td >Notes</td>
                        </tr>
                        @for($t=0; $t<sizeof($table);$t++)
                        <tr class="even col center" style="font-size: 13px;">
                            <input type='hidden' readonly='true' type="text" name="ID-{{$t}}" id="ID-{{$t}}" style="background-color:transparent; border:none; font-weight:bold; text-align:center;" value="{{$table[$t]['packetID']}}">
                            <td style="border-style:solid; border-color:black; border-width: 0px 1px 0px 1px;"><input readonly='true' type="text" name="register-{{$t}}" id="register-{{$t}}" style=" background-color:transparent; border:none; font-weight:bold; text-align:center;" value="{{$table[$t]['register']}}"></td> 
                            <td style="border-style:solid; border-color:black; border-width: 0px 1px 0px 1px" >
                                <select name='holding-{{$t}}' id='holding-{{$t}}' style="text-align: center; font-weight:bold; font-size: 13px;" class='btn'>
                                    @for($h=0; $h<sizeof($info[0]);$h++)
                                        <option <?php if($info[0][$h]['holding'] == $table[$t]['holding']){ echo "selected";}?> value="{{$info[0][$h]['id']}}">{{$info[0][$h]['holding']}}</option>
                                    @endfor
                                </select>
                            </td>
                            <td>
                                <select name='cluster-{{$t}}' id='cluster-{{$t}}' style="text-align: center; font-weight:bold; font-size: 13px;" class='btn'>
                                    @for($c=0; $c<sizeof($info[1]);$c++)
                                        <option <?php if($info[1][$c]['cluster'] == $table[$t]['cluster']) { echo "selected";}?> value="{{$info[1][$c]['cluster']}}">{{$info[1][$c]['cluster']}}</option>
                                    @endfor
                                </select>
                            </td>
                            <td style="border-style:solid; border-color:black; border-width: 0px 1px 0px 1px">
                                <select name='project-{{$t}}' id='project-{{$t}}' style="text-align: center; font-weight:bold; font-size: 13px;" class='btn'>
                                     @for($p=0; $p<sizeof($info[3]);$p++)
                                        <option <?php if($info[3][$p]['project'] == $table[$t]['project']) { echo "selected";}?> value="{{$info[3][$p]['project']}}">{{$info[3][$p]['project']}}</option>
                                    @endfor                                        
                                </select>
                            </td>
                            <td style="border-style:solid; border-color:black; border-width: 0px 1px 0px 1px" style="width:3% !important"><input type="text" name="client-{{$t}}" id="client-{{$t}}" style=" background-color:transparent; border:none; font-weight:bold; text-align:center;" value="{{$table[$t]['client']}}"></td>
                            <td style="border-style:solid; border-color:black; border-width: 0px 1px 0px 1px" style="width:3% !important"><input type="text" name="agency-{{$t}}" id="agency-{{$t}}" style=" background-color:transparent; border:none; font-weight:bold; text-align:center;" value="{{$table[$t]['agency']}}"></td>
                            <td style="border-style:solid; border-color:black; border-width: 0px 1px 0px 1px"><input type="text" name="segment-{{$t}}" id="segment-{{$t}}" style=" background-color:transparent; border:none; font-weight:bold; text-align:center;" value="{{$table[$t]['segment']}}"></td>
                            <td style="border-style:solid; border-color:black; border-width: 0px 1px 0px 1px">
                                <select name='ae1-{{$t}}' id='ae1-{{$t}}' style="text-align: center; font-weight:bold; font-size: 13px;" class='btn'>
                                @for($s=0; $s<sizeof($rep);$s++)
                                    <option  <?php if($rep[$s]['salesRep'] == $table[$t]['primary_ae']) { echo "selected";}?> value="{{$rep[$s]['id']}}">{{$rep[$s]['salesRep']}}</option>
                                @endfor
                            </select></td>
                            <td style="border-style:solid; border-color:black; border-width: 0px 1px 0px 1px">
                                <select name='ae2-{{$t}}' id='ae2-{{$t}}' style="text-align: center; font-weight:bold; font-size: 13px;" class='btn'>
                                @for($ss=0; $ss<sizeof($rep);$ss++)
                                    <option  <?php if($rep[$ss]['salesRep'] == $table[$t]['second_ae']) { echo "selected";}?> value="{{$rep[$ss]['id']}}">{{$rep[$ss]['salesRep']}}</option>
                                @endfor
                            </select></td>
                            <td style="border-style:solid; border-color:black; border-width: 0px 1px 0px 1px"><input placeholder="0" pattern="^\$\d{3.3}(.\d{3})*(\,\d+)?" data-type="currency" type="text" name="tv-{{$t}}" id="tv-{{$t}}" style=" background-color:transparent; border:none; font-weight:bold; text-align:center;" value="{{number_format($table[$t]['tv_value'],0,',','.')}}"></td>
                            <td style="border-style:solid; border-color:black; border-width: 0px 1px 0px 1px"><input placeholder="0" pattern="^\$\d{3.3}(.\d{3})*(\,\d+)?" data-type="currency" type="text" name="digital-{{$t}}" id="digital-{{$t}}" style=" background-color:transparent; border:none; font-weight:bold; text-align:center;" value="{{number_format($table[$t]['digital_value'],0,',','.')}}"></td>
                            <td style="border-style:solid; border-color:black; border-width: 0px 1px 0px 1px"><input readonly='true' type="text" name="total-{{$t}}" id="total-{{$t}}" style=" background-color:transparent; border:none; font-weight:bold; text-align:center;" value="{{number_format($totalPerPacket[$t])}}"></td>
                            <td style="border-style:solid; border-color:black; border-width: 0px 1px 0px 1px" style="width:2% !important">
                                <select  name="startMonth-{{$t}}" id="startMonth-{{$t}}" style="text-align: center; font-weight:bold; font-size: 13px;" class='btn'>
                                      @for($m=0; $m<sizeof($intMonth);$m++)
                                        <option <?php if($intMonth[$m] == $table[$t]['start_month']) { echo "selected";}?> value="{{$intMonth[$m]}}">{{$month[$m]}}</option>
                                    @endfor                                        
                                </select>
                            </td>
                            <td style="border-style:solid; border-color:black; border-width: 0px 1px 0px 1px" style="width:2% !important">
                                <select  name="endMonth-{{$t}}" id="endMonth-{{$t}}" style="text-align: center; font-weight:bold; font-size: 13px;" class='btn'>
                                      @for($m=0; $m<sizeof($intMonth);$m++)
                                        <option <?php if($intMonth[$m] == $table[$t]['end_month']) { echo "selected";}?> value="{{$intMonth[$m]}}">{{$month[$m]}}</option>
                                    @endfor                                        
                                </select></td>
                            <td style="border-style:solid; border-color:black; border-width: 0px 1px 0px 1px"><input  type="text" name="payment-{{$t}}" id="payment-{{$t}}" style=" background-color:transparent; border:none; font-weight:bold; text-align:center;" value="{{$table[$t]['payment']}}"> </td>
                            <td style="border-style:solid; border-color:black; border-width: 0px 1px 0px 1px"><input type="text" name="installments-{{$t}}" id="installments-{{$t}}" style=" background-color:transparent; border:none; font-weight:bold; text-align:center;" value="{{$table[$t]['installments']}}"></td>
                            <td style="border-style:solid; border-color:black; border-width: 0px 1px 0px 1px"> 
                                <select name='quota-{{$t}}' id='quota-{{$t}}' style="text-align: center; font-weight:bold; font-size: 13px;" class='btn'>
                                @for($q=0; $q<sizeof($info[2]);$q++)
                                    <option <?php if($info[2][$q]['quota'] == $table[$t]['quota']) { echo "selected";}?>value="{{$info[2][$q]['quota']}}">{{$info[2][$q]['quota']}}</option>
                                @endfor
                            </select></td>
                            <td style="border-style:solid; border-color:black; border-width: 0px 1px 0px 1px"><input readonly='true' type="text" name="status-{{$t}}" id="status-{{$t}}" style=" background-color:transparent; border:none; font-weight:bold; text-align:center;" value="{{$table[$t]['status']}}"></td>
                            <td style="border-style:solid; border-color:black; border-width: 0px 1px 0px 1px" type="text" maxlength="300" name="notes"><input type="text" name="notes-{{$t}}" id="notes-{{$t}}" style=" background-color:transparent; border:none; font-weight:bold; text-align:center;" value="{{$table[$t]['notes']}}"></td>
                        </tr>
                        @endfor
                        <tr>
                            <td colspan='19' style="border-style:solid; border-color:black; border-width: 1px 0px 0px 0px;"></td>
                        </tr>               
                        
                    </table>
                @else
                <tr>
                    <td class="center">Insert new informations below</td>    
                </tr>                
                @endif        
                    <table style='width: 100%; zoom: 85%;font-size: 16px;'>
                        <tr class="center">
                            <td style="width: 7% !important; background-color: white;"> &nbsp; </td>
                        </tr>
                    </table>

                     <div class='col'>
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalExemplo" style="width: 100%">
                          Add Packet
                        </button>
                    </div>
                    <!-- Modal to insert a new client -->
                    <div class="modal fade" id="modalExemplo" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">New Packet</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                                     <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="row justify-content-center">          
                                        <div class="col">       
                                            <div class="form-group">
                                                <label>Register</label>
                                                <input type="text" name="newRegister" id="newRegister" style="width: 100%; background-color:transparent; border:solid; font-weight:bold; text-align:center; border-width: 1px;" value="Actuals"><br>
                                                <label>Holding</label> 
                                                <select name='newHolding' id='newHolding' style="width: 100%;">
                                                    @for($h=0; $h<sizeof($info[0]);$h++)
                                                        <option value="{{$info[0][$h]['id']}}">{{$info[0][$h]['holding']}}</option>
                                                    @endfor
                                                </select><br>
                                                <label>Cluster</label>                                                
                                                    <select name='newCluster' id='newCluster' style="width: 100%;">
                                                        @for($c=0; $c<sizeof($info[1]);$c++)
                                                            <option value="{{$info[1][$c]['cluster']}}">{{$info[1][$c]['cluster']}}</option>
                                                        @endfor
                                                    </select><br>
                                                <label>Project</label><br>
                                                        <select name='newProject' id='newProject' style="width: 100%; ">
                                                        @for($p=0; $p<sizeof($info[3]);$p++)
                                                            <option value="{{$info[3][$p]['project']}}">{{$info[3][$p]['project']}}</option>
                                                        @endfor
                                                    </select><br>
                                                <label>Client</label>
                                                <input type="text" name="newClient" id="newClient" style="width: 100%; background-color:transparent; border:solid; font-weight:bold; text-align:center; border-width: 1px;" value=""><br>
                                                <label>Agency</label>
                                                <input type="text" name="newAgency" id="newAgency" style="width: 100%; background-color:transparent; border:solid; font-weight:bold; text-align:center; border-width: 1px;" value=""><br>
                                                <label>Segment</label>
                                               <input type="text" name="newSegment" id="newSegment" style="width: 100%; background-color:transparent; border:solid; font-weight:bold; text-align:center; border-width: 1px;" value=""><br>
                                                <label>Ae 1</label>
                                                    <select name='newAe1' id='newAe1' style="width: 100%; ">
                                                        @for($s=0; $s<sizeof($rep);$s++)
                                                            <option value="{{$rep[$s]['id']}}">{{$rep[$s]['salesRep']}}</option>
                                                        @endfor
                                                    </select><br>
                                                <label>AE 2</label>
                                                    <select name='newAe2' id='newAe2' style="width: 100%; ">
                                                        @for($ss=0; $ss<sizeof($rep);$ss++)
                                                            <option value="{{$rep[$ss]['id']}}">{{$rep[$ss]['salesRep']}}</option>
                                                        @endfor
                                                    </select> <br>
                                                <label>TV Values</label>
                                                <input  type="text" name="newTv" id="newTv" style="width: 100%; background-color:transparent; border:solid; font-weight:bold; text-align:center; border-width: 1px;" value=""><br>
                                                <label>Digital Values</label>
                                                <input  type="text" name="newDigital" id="newDigital" style="width: 100%; background-color:transparent; border:solid; font-weight:bold; text-align:center; border-width: 1px;" value=""><br>
                                                <label>First Month</label>
                                                    <select name='newFirstMonth' id='newFirstMonth' style="width: 100%; ">
                                                        @for($m=0; $m<sizeof($intMonth);$m++)
                                                            <option value="{{$intMonth[$m]}}">{{$month[$m]}}</option>
                                                        @endfor
                                                    </select><br>
                                                <label>Last Month</label>
                                                    <select name='newEndMonth' id='newEndMonth' style="width: 100%; ">
                                                        @for($m=0; $m<sizeof($intMonth);$m++)
                                                            <option value="{{$intMonth[$m]}}">{{$month[$m]}}</option>
                                                        @endfor
                                                    </select><br>
                                                <label>Payment</label>
                                                <input placeholder="Payment" type="text" name="newPayment" id="newPayment" style="width: 100%; background-color:transparent; border:solid; font-weight:bold; text-align:center; border-width: 1px;" value=""><br>
                                                <label>Installments</label>
                                                <input placeholder="Installments" type="text" name="newInstallments" id="newInstallments" style="width: 100%; background-color:transparent; border:solid; font-weight:bold; text-align:center; border-width: 1px;" value=""><br>
                                                <label>Quota</label>
                                                    <select name='newQuota' id='newQuota' style="width: 100%; ">
                                                        @for($q=0; $q<sizeof($info[2]);$q++)
                                                            <option value="{{$info[2][$q]['quota']}}">{{$info[2][$q]['quota']}}</option>
                                                        @endfor
                                                    </select><br>
                                                <label>Status</label>
                                                <input readonly='true' type="text" name="newStatus" id="newStatus" value="5 - Closed" style="width: 100%; background-color:transparent; border:solid; font-weight:bold; text-align:center; border-width: 1px;"><br>
                                                <label>Note</label>
                                                <input type="text" maxlength="300" name="newNotes" id="newNotes" style="width: 100%; background-color:transparent; border:solid; font-weight:bold; text-align:center; border-width: 1px;" value="">
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
                    <table style='width: 100%; zoom: 85%;font-size: 16px;'>
                        <tr class="center">
                            <td style="width: 7% !important; background-color: white;"> &nbsp; </td>
                        </tr>
                    </table>
                </form>
            </div>
        </div>
	</div>

<!-- javascript to make the excel export -->
<script type="text/javascript">
            
    $(document).ready(function(){

        ajaxSetup();

        $('#excel').click(function(event){

            var region = "<?php echo $region[0]['id']; ?>";
            var rep = "<?php echo base64_encode(json_encode($rep)); ?>";

            var div = document.createElement('div');
            var img = document.createElement('img');
            img.src = '/loading_excel.gif';
            div.innerHTML ="Generating File...</br>";
            div.style.cssText = 'position: absolute; left: 0px; top:0px;  margin:0px;        width: 100%;        height: 100%;        display:block;        z-index: 99999;        opacity: 0.9;        -moz-opacity: 0;        filter: alpha(opacity = 45);        background: white;    background-repeat: no-repeat;        background-position:50% 50%;        text-align: center;        overflow: hidden;   font-size:30px;     font-weight: bold;        color: black;        padding-top: 20%';
            div.appendChild(img);
            document.body.appendChild(div);

            var typeExport = $("#excel").val();

            var title = "<?php echo $titleExcel; ?>";
            var auxTitle = "<?php echo $title; ?>";
                
            $.ajax({
                xhrFields: {
                    responseType: 'blob',
                },
                url: "/generate/excel/viewer/vPackets",
                type: "POST",
                data: {region,rep, typeExport, auxTitle,title},
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

   

</script>

<script type="text/javascript">
    
    $('#newCluster').change(function(){
    
    var cluster = $("#newCluster").val();
        if (cluster != "") {

          $.ajax({
            url:"/ajax/adsales/getPackets",
            method:"POST",
            data:{cluster},
            success: function(output){
              $('#newProject').html(output).selectpicker('refresh');
              //$('#vlau ').html(output).selectpicker('refresh');
            },
            error: function(xhr, ajaxOptions,thrownError){
                alert(xhr.status+" "+thrownError);
            }
          });  
        }else{
          var option = "<option> Select Cluster </option>";
          $('#newProject').empty().append(option).selectpicker('refresh');
        }
    });


    @for($t=0; $t<sizeof($table);$t++)
       $('#cluster-'+{{$t}}).change(function(){
        
        var cluster = $("#cluster-"+{{$t}}).val();
        if (cluster != "") {

          $.ajax({
            url:"/ajax/adsales/getPackets",
            method:"POST",
            data:{cluster},
            success: function(output){                 
                $('#project-'+{{$t}}).html(output).selectpicker('refresh');
                //$('#vlau ').html(output).selectpicker('refresh');
            },
            error: function(xhr, ajaxOptions,thrownError){
                alert(xhr.status+" "+thrownError);
            }
          });  
        }else{
          var option = "<option> Select Cluster </option>";
          $('#project-'+{{$t}}).empty().append(option).selectpicker('refresh');
        }
      });
    @endfor     
</script>

@endsection