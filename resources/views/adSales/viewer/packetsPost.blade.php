@extends('layouts.mirror')
@section('title', 'Closed Packets')
@section('head')

    <?php include(resource_path('views/auth.php'));

        $intMonth = array('1','2','3','4','5','6','7','8','9','10','11','12');
        $month = array('Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec');
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
                    <table class="table-responsive" style='width: 100% zoom: 85%;' >
                        <tr>
                            <td colspan="8"></td>
                            <td class="dc center" colspan="2" style="border-style:solid; border-color:black; border-width: 1px 1px 0px 1px;">DSC</td>
                            <td class="sony center" colspan='2' style="border-style:solid; border-color:black; border-width: 1px 1px 0px 1px;">SPT</td>
                            <td class="dn center" colspan='3' style="border-style:solid; border-color:black; border-width: 1px 1px 0px 1px;">WM</td>
                            <td class="grey center" colspan='3' style="border-style:solid; border-color:black; border-width: 1px 1px 0px 1px;">WBD</td>
                        </tr>
                        <tr>
                            <td colspan="8"></td>
                            <td class="even center" style="border-style:solid; border-color:black; border-width: 1px 1px 0px 1px;">{{number_format($total['dsc_tv'])}}</td>
                            <td class="even center" style="border-style:solid; border-color:black; border-width: 1px 1px 0px 1px;">{{number_format($total['dsc_digital'])}}</td>
                            <td class="even center" style="border-style:solid; border-color:black; border-width: 1px 1px 0px 1px;">{{number_format($total['spt_tv'])}}</td>
                            <td class="even center" style="border-style:solid; border-color:black; border-width: 1px 1px 0px 1px;">{{number_format($total['spt_digital'])}}</td>
                            <td class="even center" style="border-style:solid; border-color:black; border-width: 1px 1px 0px 1px;">{{number_format($total['wm_tv'])}}</td>
                            <td class="even center" style="border-style:solid; border-color:black; border-width: 1px 1px 0px 1px;">{{number_format($total['wm_digital'])}}</td>
                            <td class="even center" style="border-style:solid; border-color:black; border-width: 1px 1px 0px 1px;">{{number_format($total['wbd_max'])}}</td>
                            <td class="even center" style="border-style:solid; border-color:black; border-width: 1px 1px 0px 1px;">{{number_format($total['total_tv'])}}</td>
                            <td class="even center" style="border-style:solid; border-color:black; border-width: 1px 1px 0px 1px;">{{number_format($total['total_digital'])}}</td>
                            <td class="even center" style="border-style:solid; border-color:black; border-width: 1px 1px 0px 1px;">{{number_format($total['total'])}}</td>
                            
                        </tr>
                        <tr class="smBlue col center">
                            <td >CLUSTER</td>
                            <td >PROPERTY</td>
                            <td >CLIENT</td>
                            <td >AGENCY</td>
                            <td >PRODUCT</td>
                            <td>SEGMENT</td>
                            <td>AE 1</td>
                            <td>AE 2 </td>                            
                            <td class='dc' >TV</td>
                            <td class="dc" >DIG</td>
                            <td class="sony" >TV</td>
                            <td class="sony" >DIG</td>
                            <td class='dn' >TV</td>
                            <td class="dn" >DIG</td>
                            <td class='dn' >WM MAX</td>
                            <td class='grey' >TV</td>
                            <td class="grey">DIG</td>
                            <td class='grey'>TOTAL</td>
                            <td >PAYMENT </td>
                            <td >INST</td>
                            <td >START</td>
                            <td >END</td>
                            <td>MONTHS</td>
                            <td>QUOTA</td>
                            <td>LETTER</td>
                            <td >NOTES</td>
                            <td></td>
                        </tr>
                        @for($t=0; $t<sizeof($table);$t++)
                        <tr class="even center" style="font-size: 13px; border-style:solid; border-color: lightgrey; border-width: 0px 1px 0px 1px;">
                            <input type='hidden' readonly='true' type="text" name="ID-{{$t}}" id="ID-{{$t}}" style="background-color:transparent; border:none; font-weight:bold; text-align:center;" value="{{$table[$t]['packetID']}}">
                            <input  type="hidden" name="register-{{$t}}" id="register-{{$t}}" style="width: 100%; font-size: 13px; background-color:transparent; border:none; font-weight:bold; text-align:center;" value="{{$table[$t]['register']}}">
                            <td style="border-style:solid; border-color: lightgrey; border-width: 0px 1px 0px 1px;" ><input  type="text" name="cluster-{{$t}}" id="cluster-{{$t}}" style="width: 100%; font-size: 13px; background-color:transparent; border:none; font-weight:bold; text-align:center;" value="{{$table[$t]['cluster']}}"></td>
                            <td style="border-style:solid; border-color: lightgrey; border-width: 0px 1px 0px 1px;" ><input  type="text" name="project-{{$t}}" id="project-{{$t}}" style="width: 100%; font-size: 13px; background-color:transparent; border:none; font-weight:bold; text-align:center;" value="{{$table[$t]['project']}}"></td>
                            <td style="border-style:solid; border-color: lightgrey; border-width: 0px 1px 0px 1px;" ><input  type="text" name="client-{{$t}}" id="client-{{$t}}" style="width: 100%; font-size: 13px; background-color:transparent; border:none; font-weight:bold; text-align:center;" value="{{$table[$t]['client']}}"></td>
                            <td style="border-style:solid; border-color: lightgrey; border-width: 0px 1px 0px 1px;" ><input  type="text" name="agency-{{$t}}" id="agency-{{$t}}" style="width: 100%; font-size: 13px; background-color:transparent; border:none; font-weight:bold; text-align:center;" value="{{$table[$t]['agency']}}"></td>
                            <td style="border-style:solid; border-color: lightgrey; border-width: 0px 1px 0px 1px;" ><input  type="text" name="product-{{$t}}" id="product-{{$t}}" style="width: 100%; font-size: 13px; background-color:transparent; border:none; font-weight:bold; text-align:center;" value="{{$table[$t]['product']}}"></td>
                            <td style="border-style:solid; border-color: lightgrey; border-width: 0px 1px 0px 1px;"><input  type="text" name="segment-{{$t}}" id="segment-{{$t}}" style="width: 100%; font-size: 13px; background-color:transparent; border:none; font-weight:bold; text-align:center;" value="{{$table[$t]['segment']}}"></td>
                            <td style="border-style:solid; border-color: lightgrey; border-width: 0px 1px 0px 1px;"><input  type="text" name="ae1-{{$t}}" id="ae1-{{$t}}" style="width: 100%; font-size: 13px; background-color:transparent; border:none; font-weight:bold; text-align:center;" value="{{$table[$t]['primary_ae']}}"></td>
                            <td style="border-style:solid; border-color: lightgrey; border-width: 0px 1px 0px 1px;"><input  type="text" name="ae2-{{$t}}" id="ae2-{{$t}}" style="width: 100%; font-size: 13px; background-color:transparent; border:none; font-weight:bold; text-align:center;" value="{{$table[$t]['second_ae']}}"></td>                  
                            <td style="border-style:solid; border-color: lightgrey; border-width: 0px 1px 0px 1px;"><input placeholder="0" pattern="^\$\d{3.3}(.\d{3})*(\,\d+)?" data-type="currency"  type="text" name="dsc_tv-{{$t}}" id="dsc_tv-{{$t}}" style="width: 100%; font-size: 13px; background-color:transparent; border:none; font-weight:bold; text-align:center;" value="{{number_format($table[$t]['dsc_tv'],0,',','.')}}"></td>
                            <td style="border-style:solid; border-color: lightgrey; border-width: 0px 1px 0px 1px;"><input placeholder="0" pattern="^\$\d{3.3}(.\d{3})*(\,\d+)?" data-type="currency"  type="text" name="dsc_digital-{{$t}}" id="dsc_digital-{{$t}}" style="width: 100%; font-size: 13px; background-color:transparent; border:none; font-weight:bold; text-align:center;" value="{{number_format($table[$t]['dsc_digital'],0,',','.')}}"></td>
                            <td style="border-style:solid; border-color: lightgrey; border-width: 0px 1px 0px 1px;"><input placeholder="0" pattern="^\$\d{3.3}(.\d{3})*(\,\d+)?" data-type="currency"  type="text" name="spt_tv-{{$t}}" id="spt_tv-{{$t}}" style="width: 100%; font-size: 13px; background-color:transparent; border:none; font-weight:bold; text-align:center;" value="{{number_format($table[$t]['spt_tv'],0,',','.')}}"></td>
                            <td style="border-style:solid; border-color: lightgrey; border-width: 0px 1px 0px 1px;"><input placeholder="0" pattern="^\$\d{3.3}(.\d{3})*(\,\d+)?" data-type="currency"  type="text" name="spt_digital-{{$t}}" id="spt_digital-{{$t}}" style="width: 100%; font-size: 13px; background-color:transparent; border:none; font-weight:bold; text-align:center;" value="{{number_format($table[$t]['spt_digital'],0,',','.')}}"></td>
                            <td style="border-style:solid; border-color: lightgrey; border-width: 0px 1px 0px 1px;"><input placeholder="0" pattern="^\$\d{3.3}(.\d{3})*(\,\d+)?" data-type="currency"  type="text" name="wm_tv-{{$t}}" id="wm_tv-{{$t}}" style="width: 100%; font-size: 13px; background-color:transparent; border:none; font-weight:bold; text-align:center;" value="{{number_format($table[$t]['wm_tv'],0,',','.')}}"></td>
                            <td style="border-style:solid; border-color: lightgrey; border-width: 0px 1px 0px 1px;"><input placeholder="0" pattern="^\$\d{3.3}(.\d{3})*(\,\d+)?" data-type="currency"  type="text" name="wm_digital-{{$t}}" id="wm_digital-{{$t}}" style="width: 100%; font-size: 13px; background-color:transparent; border:none; font-weight:bold; text-align:center;" value="{{number_format($table[$t]['wm_digital'],0,',','.')}}"></td>
                            <td style="border-style:solid; border-color: lightgrey; border-width: 0px 1px 0px 1px;"><input placeholder="0" pattern="^\$\d{3.3}(.\d{3})*(\,\d+)?" data-type="currency"  type="text" name="wbd_max-{{$t}}" id="wbd_max-{{$t}}" style="width: 100%; font-size: 13px; background-color:transparent; border:none; font-weight:bold; text-align:center;" value="{{number_format($table[$t]['wbd_max'],0,',','.')}}"></td>
                            <td style="border-style:solid; border-color: lightgrey; border-width: 0px 1px 0px 1px;">{{number_format($totalPerPacket['tv'][$t],0,',','.')}}</td>
                            <td style="border-style:solid; border-color: lightgrey; border-width: 0px 1px 0px 1px;">{{number_format($totalPerPacket['digital'][$t],0,',','.')}}</td>
                            <td style="border-style:solid; border-color: lightgrey; border-width: 0px 1px 0px 1px;">{{number_format($totalPerPacket['wbd'][$t],0,',','.')}}</td>
                            <td style="border-style:solid; border-color: lightgrey; border-width: 0px 1px 0px 1px;"><input  type="text" name="payment-{{$t}}" id="payment-{{$t}}" style="width: 100%; font-size: 13px; background-color:transparent; border:none; font-weight:bold; text-align:center;" value="{{$table[$t]['payment']}}"> </td>
                            <td style="border-style:solid; border-color: lightgrey; border-width: 0px 1px 0px 1px;"><input  type="text" name="installments-{{$t}}" id="installments-{{$t}}" style="width: 100%; font-size: 13px; background-color:transparent; border:none; font-weight:bold; text-align:center;" value="{{$table[$t]['installments']}}"></td>
                            <td style="border-style:solid; border-color:black; border-width: 0px 1px 0px 1px" style="width:2% !important">
                                    <select name="startMonth-{{$t}}" id="startMonth-{{$t}}" style="-webkit-appearance: none; font-size: 13px; width: 70px; text-align: center; font-weight:bold; background-color:transparent; border:none; font-weight:bold; text-align:center;">
                                          @for($m=0; $m<sizeof($intMonth);$m++)
                                            <option <?php if($intMonth[$m] == $table[$t]['start_month']) { echo "selected";}?> value="{{$intMonth[$m]}}">{{$month[$m]}}</option>
                                        @endfor                                        
                                    </select>
                                </td>
                                <td style="border-style:solid; border-color:black; border-width: 0px 1px 0px 1px" style="width:2% !important">
                                    <select name="endMonth-{{$t}}" id="endMonth-{{$t}}" style="-webkit-appearance: none; width: 70px; text-align: center; font-weight:bold; font-size: 13px; background-color:transparent; border:none; font-weight:bold; text-align:center;">
                                          @for($m=0; $m<sizeof($intMonth);$m++)
                                            <option <?php if($intMonth[$m] == $table[$t]['end_month']) { echo "selected";}?> value="{{$intMonth[$m]}}">{{$month[$m]}}</option>
                                        @endfor                                        
                                    </select></td>
                            <td style="border-style:solid; border-color: lightgrey; border-width: 0px 1px 0px 1px;">{{$table[$t]['end_month'] - $table[$t]['start_month']}}</td>
                            <td style="border-style:solid; border-color: lightgrey; border-width: 0px 1px 0px 1px;"><input  type="text" name="quota-{{$t}}" id="quota-{{$t}}" style="width: 100%; font-size: 13px; background-color:transparent; border:none; font-weight:bold; text-align:center;" value="{{$table[$t]['quota']}}"></td>
                            <td style="border-style:solid; border-color: lightgrey; border-width: 0px 1px 0px 1px;"><input  type="text" name="letter-{{$t}}" id="letter-{{$t}}" style="width: 100%; font-size: 13px; background-color:transparent; border:none; font-weight:bold; text-align:center;" value="{{$table[$t]['letter']}}"></td>
                            <td style="border-style:solid; border-color: lightgrey; border-width: 0px 1px 0px 1px;"><input  type="text" name="notes-{{$t}}" id="notes-{{$t}}" style="width: 100%; font-size: 13px; background-color:transparent; border:none; font-weight:bold; text-align:center;" value="{{$table[$t]['notes']}}"></td>
                            <td style="width:1% !important;"><button class="btn btn-primary" class="click-trigger">Edit</button></td>
                        </tr>  
                        @endfor
                        <tr>
                            <td colspan='28' style="border-style:solid; border-color:black; border-width: 1px 0px 0px 0px;"></td>
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
                                                <label style="font-weight:bold; text-align:center;">Register</label>
                                                <input type="text" name="newRegister" id="newRegister" style="width: 100%; background-color:transparent; border:solid; font-weight:bold; text-align:center; border-width: 1px;" value="Actuals"><br>                                                
                                                <label style="font-weight:bold; text-align:center;">Cluster</label>                                                
                                                    <select name='newCluster' id='newCluster' style="width: 100%;">
                                                        @for($c=0; $c<sizeof($info[1]);$c++)
                                                            <option value="{{$info[1][$c]['cluster']}}">{{$info[1][$c]['cluster']}}</option>
                                                        @endfor
                                                    </select><br>
                                                <label style="font-weight:bold; text-align:center;">Project</label><br>
                                                        <select name='newProject' id='newProject' style="width: 100%; ">
                                                        @for($p=0; $p<sizeof($info[3]);$p++)
                                                            <option value="{{$info[3][$p]['project']}}">{{$info[3][$p]['project']}}</option>
                                                        @endfor
                                                    </select><br>
                                                <label>Client</label>
                                                    <select class='selectpicker' id='newClient' name='newClient[]' data-selected-text-format='count' data-width='100%' class='form-control' data-live-search='true'>
                                                        <option value='0' selected='true'> Select </option>
                                                        @for($x=0; $x<sizeof($info[5]);$x++)
                                                            <option value="{{$info[5][$x]['clientId']}}">{{$info[5][$x]['client']}}</option>
                                                        @endfor
                                                    </select><br>
                                                <label>Agency</label>
                                                 <select class='selectpicker' id='newAgency' name='newAgency[]' data-selected-text-format='count' data-width='100%' class='form-control' data-live-search='true'>
                                                    <option value=''> Select </option>
                                                        @for($z=0; $z<sizeof($info[6]);$z++)
                                                            <option value="{{$info[6][$z]['aID']}}">{{$info[6][$z]['agency']}}</option>
                                                        @endfor
                                                    </select><br>    
                                                 <label style="font-weight:bold; text-align:center;">Product</label>
                                                <input type="text" name="newProduct" id="newProduct" style="width: 100%; background-color:transparent; border:solid; font-weight:bold; text-align:center; border-width: 1px;" value=""><br>
                                                <label style="font-weight:bold; text-align:center;">Segment</label>
                                               <input type="text" name="newSegment" id="newSegment" style="width: 100%; background-color:transparent; border:solid; font-weight:bold; text-align:center; border-width: 1px;" value=""><br>
                                                <label style="font-weight:bold; text-align:center;">Ae 1</label>
                                                    <select name='newAe1' id='newAe1' style="width: 100%; ">
                                                        @for($s=0; $s<sizeof($rep);$s++)
                                                            <option value="{{$rep[$s]['id']}}">{{$rep[$s]['salesRep']}}</option>
                                                        @endfor
                                                    </select><br>
                                                <label style="font-weight:bold; text-align:center;">AE 2</label>
                                                    <select name='newAe2' id='newAe2' style="width: 100%; ">
                                                        @for($ss=0; $ss<sizeof($rep2);$ss++)
                                                            <option value="{{$rep2[$ss]['id']}}">{{$rep2[$ss]['salesRep']}}</option>
                                                        @endfor
                                                    </select> <br>
                                                <label style="font-weight:bold; text-align:center;">Dsc Tv Value</label>
                                                <input  type="text" name="new_dsc_tv" id="new_dsc_tv" style="width: 100%; background-color:transparent; border:solid; font-weight:bold; text-align:center; border-width: 1px;" value=""><br>
                                                <label style="font-weight:bold; text-align:center;">Dsc Digital Value</label>
                                                <input  type="text" name="new_dsc_digital" id="new_dsc_digital" style="width: 100%; background-color:transparent; border:solid; font-weight:bold; text-align:center; border-width: 1px;" value=""><br>
                                                 <label style="font-weight:bold; text-align:center;">Wm Tv Value</label>
                                                <input  type="text" name="new_wm_tv" id="new_wm_tv" style="width: 100%; background-color:transparent; border:solid; font-weight:bold; text-align:center; border-width: 1px;" value=""><br>
                                                <label style="font-weight:bold; text-align:center;">Wm Digital Value</label>
                                                <input  type="text" name="new_wm_digital" id="new_wm_digital" style="width: 100%; background-color:transparent; border:solid; font-weight:bold; text-align:center; border-width: 1px;" value=""><br>
                                                <label style="font-weight:bold; text-align:center;">Wbd Max Value</label>
                                                <input  type="text" name="new_wbd_max" id="new_wbd_max" style="width: 100%; background-color:transparent; border:solid; font-weight:bold; text-align:center; border-width: 1px;" value=""><br>
                                                <label style="font-weight:bold; text-align:center;">Spt Tv Value</label>
                                                <input  type="text" name="new_spt_tv" id="new_spt_tv" style="width: 100%; background-color:transparent; border:solid; font-weight:bold; text-align:center; border-width: 1px;" value=""><br>
                                                <label style="font-weight:bold; text-align:center;">Spt Digital Value</label>
                                                <input  type="text" name="new_spt_digital" id="new_spt_digital" style="width: 100%; background-color:transparent; border:solid; font-weight:bold; text-align:center; border-width: 1px;" value=""><br>                                              
                                                <label style="font-weight:bold; text-align:center;">First Month</label>
                                                    <select name='newFirstMonth' id='newFirstMonth' style="width: 100%; ">
                                                        @for($m=0; $m<sizeof($intMonth);$m++)
                                                            <option value="{{$intMonth[$m]}}">{{$month[$m]}}</option>
                                                        @endfor
                                                    </select><br>
                                                <label style="font-weight:bold; text-align:center;">Last Month</label>
                                                    <select name='newEndMonth' id='newEndMonth' style="width: 100%; ">
                                                        @for($m=0; $m<sizeof($intMonth);$m++)
                                                            <option value="{{$intMonth[$m]}}">{{$month[$m]}}</option>
                                                        @endfor
                                                    </select><br>
                                                <label style="font-weight:bold; text-align:center;">Payment</label>
                                                <select name='newPayment' id='newPayment' style="width: 100%; ">
                                                        @for($b=0; $b<sizeof($info[4]);$b++)
                                                            <option value="{{$info[4][$b]}}">{{$info[4][$b]}}</option>
                                                        @endfor
                                                    </select><br>
                                                <label style="font-weight:bold; text-align:center;">Installments</label>
                                                <input placeholder="Installments" type="text" name="newInstallments" id="newInstallments" style="width: 100%; background-color:transparent; border:solid; font-weight:bold; text-align:center; border-width: 1px;" value=""><br>
                                                <label style="font-weight:bold; text-align:center;">Quota</label>
                                                    <select name='newQuota' id='newQuota' style="width: 100%; ">
                                                        @for($q=0; $q<sizeof($info[2]);$q++)
                                                            <option value="{{$info[2][$q]['quota']}}">{{$info[2][$q]['quota']}}</option>
                                                        @endfor
                                                    </select><br>
                                                <label style="font-weight:bold; text-align:center;">Letter Of Agreement</label>
                                                <select name='newLetter' id='newLetter' style="width: 100%; ">
                                                        @for($l=0; $l<sizeof($info[0]);$l++)
                                                            <option value="{{$info[0][$l]}}">{{$info[0][$l]}}</option>
                                                        @endfor
                                                    </select><br>
                                                <label style="font-weight:bold; text-align:center;">Note</label>
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


    @if($table != null)
        @for($t=0; $t<sizeof($table);$t++)

            jQuery(document).ready(function () {    
                var widthOfSelect = $("#select-"+{{$t}}).width();
                widthOfSelect = widthOfSelect - 13;
                //alert(widthOfSelect);
                jQuery('#select-'+{{$t}}).wrap("<div id='sss' style='text-align:left; width: "+widthOfSelect+"px; overflow: hidden; width=25;'></div>");
            });

             jQuery(document).ready(function () {    
                var widthOfSelect = $("#select-"+{{$t}}).width();
                widthOfSelect = widthOfSelect - 13;
                //alert(widthOfSelect);
                jQuery('#select-'+{{$t}}).wrap("<div id='sss' style='text-align:left; width: "+widthOfSelect+"px; overflow: hidden; width=25;'></div>");
            });


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
    @endif
</script>

@endsection