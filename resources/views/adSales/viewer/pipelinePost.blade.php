@extends('layouts.mirror')
@section('title', 'Pipelines')
@section('head')
    <script src="/js/pipeline.js"></script>
    <?php include(resource_path('views/auth.php'));

        $intMonth = array('1','2','3','4','5','6','7','8','9','10','11','12');
        $month = array('Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec');
        $status = array('0 - Exploração','1 - Proposta Submetida','2 - Proposta em Análise','3 - Proposta em Negociação','4 - Aprovação','5 - Fechado','6 - Negado/Perdido');

    ?>

@endsection
@section('content')

	<div class="container-fluid">
		<div class="row">
			<div class="col">
				<form method="POST" action="{{route('pipelinePost')}}" runat="server" onsubmit="ShowLoading()">
					@csrf
					<div class="row">
                        <div class="col" style="display:none;">
                            <label class="labelLeft"><span class="bold"> Region: </span></label>

                            @if($errors->has('region'))
                                <label style="color: red;">* Required</label>
                            @endif
                                {{$render->regionFiltered($region, $regionID, $special)}}                           
                        </div>
                        
                        <div class="col" style="display:none;">
                            <label class="labelLeft"><span class="bold"> Year: </span></label>
                            @if($errors->has('year'))
                                <label style="color: red;">* Required</label>
                            @endif
                            {{$render->year($regionID)}}                    
                        </div> 
                        <div class="col">
                            <label class='labelLeft'><span class="bold">Manager:</span></label>
                            @if($errors->has('manager'))
                                <label style="color: red;">* Required</label>
                            @endif
                                {{$render->director()}}
                        </div>

                         <div class="col">
                            <label class='labelLeft'><span class="bold">Sales Rep:</span></label>
                            @if($errors->has('salesRep'))
                                <label style="color: red;">* Required</label>
                            @endif
                                {{$render->salesRep()}}
                        </div>

                         <div class="col">
                            <label class='labelLeft'><span class="bold">Property:</span></label>
                            @if($errors->has('salesRep'))
                                <label style="color: red;">* Required</label>
                            @endif
                                {{$render->properties()}}
                        </div>

                        <div class="col">
                            <label class='labelLeft'><span class="bold">Status:</span></label>
                            <select class='selectpicker' id='status' name='status[]' multiple='true' multiple data-actions-box='true' data-selected-text-format='count' data-width='100%' class='form-control' data-live-search='true' required>
                                @for($i=0; $i<sizeof($status);$i++)
                                    <option value="{{$status[$i]}}" selected='true'>{{$status[$i]}}</option>
                                @endfor
                            </select><br>
                        </div>

                        <div class="col">
                            <label class='labelLeft'><span class="bold">Agency:</span></label>
                            @if($errors->has('agency'))
                                <label style="color: red;">* Required</label>
                            @endif
                            {{$render->AgencyForm()}}
                        </div>

                        <div class="col">
                            <label class='labelLeft'><span class="bold">Client:</span></label>
                            @if($errors->has('client'))
                                <label style="color: red;">* Required</label>
                            @endif
                            {{$render->ClientForm()}}

                            <input type="hidden" name="sizeOfClient" id="sizeOfClient" value="">
                        </div>  
                        
                        <div class="col">
                            <label> &nbsp; </label>
                            <input type="submit" value="Generate" class="btn btn-primary" style="width: 100%;">     
                        </div>
					</div>
				</form>
			</div>
		</div>
    </div>
        <div id="vlau"></div>

		<div class="row justify-content-end mt-2">
			<div class="col-sm-4" style="color: #0070c0; font-size:24px">
                <span style="float: right; margin-right: 2.5%;">Pipelines</span>
            </div>
            <div class="col-2">
                <button type="button" id="excel" class="btn btn-primary" style="width: 100%">
                    Generate Excel
                </button>               
            </div> 
		</div>

		<div class="row mt-2 justify-content-end">
            <div class="col">
                <form method="POST" runat="server" action="{{ route('savePipeline') }} " name="pipelineSave">
                    @csrf
                    <div class="row">    
                        <div class="col-10"></div>        
                        <div class="col-2">                             
                            <input type="submit" id="button" value="Save" class="btn btn-primary" style="width: 100%">
                            <label class="labelLeft"><span class="bold"> &nbsp; </span> </label>
                        </div>    
                    </div> 
                    <input type='hidden' readonly='true' type="text" name="region" id="region" style="background-color:transparent; border:none; font-weight:bold; text-align:center;" value="{{$region[0]['id']}}">
                    <input type='hidden' readonly='true' type="text" name="agencyString" id="agencyString" style="background-color:transparent; border:none; font-weight:bold; text-align:center;" value="{{$agencyString}}">
                    <input type='hidden' readonly='true' type="text" name="clientString" id="clientString" style="background-color:transparent; border:none; font-weight:bold; text-align:center;" value="{{$clientString}}">
                    <input type='hidden' readonly='true' type="text" name="propString" id="propString" style="background-color:transparent; border:none; font-weight:bold; text-align:center;" value="{{$propString}}">                  
                    <input type='hidden' readonly='true' type="text" name="managerString" id="managerString" style="background-color:transparent; border:none; font-weight:bold; text-align:center;" value="{{$managerString}}">
                    <input type='hidden' readonly='true' type="text" name="statusString" id="statusString" style="background-color:transparent; border:none; font-weight:bold; text-align:center;" value="{{$statusString}}">
                    <input type='hidden' readonly='true' type="text" name="salesRepString" id="salesRepString" style="background-color:transparent; border:none; font-weight:bold; text-align:center;" value="{{$salesRepString}}">

                @if($table)
                    <table class="table-responsive-sm">
                        <tr>
                            <td colspan="7"></td>
                            <td class="odd center" colspan="2" style="border-style:solid; border-color:black; border-width: 1px 1px 0px 1px;">SUBTOTAL</td>
                            <td class="odd center" style="border-style:solid; border-color:black; border-width: 1px 1px 0px 1px;">TOTAL</td>
                        </tr>
                        <tr>
                            <td colspan="7"></td>
                            <td class="odd center" style="border-style:solid; border-color:black; border-width: 1px 1px 0px 1px;">{{number_format($total['tv'],0,',','.')}}</td>
                            <td class="odd center" style="border-style:solid; border-color:black; border-width: 1px 1px 0px 1px;">{{number_format($total['digital'],0,',','.')}}</td>
                            <td class="odd center" style="border-style:solid; border-color:black; border-width: 1px 1px 0px 1px;">{{number_format($total['total'],0,',','.')}}</td>
                        </tr>
                        <tr class="darkBlue center" style="font-size: 14px; border-style:solid; border-color:black; border-width: 1px 1px 0px 1px;">
                            <td style="width:3% !important;">CLUSTER</td>
                            <td style="width:5% !important;">PROPERTY</td>
                            <td style="width:3% !important;">CLIENT</td>
                            <td style="width:3% !important;">AGENCY</td>
                            <td style="width:3% !important;" >AE 1</td>
                            <td style="width:3% !important;" >AE 2</td>
                            <td style="width:2% !important;" >MNG </td>
                            <td style="width:3% !important;" >TV</td>
                            <td style="width:3% !important;" >DIGITAL</td>
                            <td style="width:3% !important;" >TOTAL</td>
                            <td style="width:1% !important;" >START</td>
                            <td style="width:1% !important;" >END</td>
                            <td style="width:3% !important;" >QUOTA</td>
                            <td style="width:5% !important;" >STATUS</td>
                            <td style="width:6% !important;" >NOTES</td>
                            <td style="width:2% !important;" ></td>
                        </tr>
                        @for($t=0; $t<sizeof($table);$t++)
                            <input type='hidden' readonly='true' type="text" name="pipeline-{{$t}}" id="pipeline-{{$t}}" style="background-color:transparent; border:none; font-weight:bold; text-align:center;" value="{{$table[$t]['packetID']}},{{$table[$t]['cluster']}},{{$table[$t]['project']}},{{$table[$t]['cID']}},{{$table[$t]['aID']}},{{$table[$t]['primary_ae_id']}},{{$table[$t]['primary_ae']}},{{$table[$t]['second_ae_id']}},{{$table[$t]['second_ae']}},{{$table[$t]['manager']}},{{$table[$t]['tv_value']}},{{$table[$t]['digital_value']}},{{$table[$t]['start_month']}},{{$table[$t]['end_month']}},{{$table[$t]['quota']}},{{$table[$t]['status']}},{{$table[$t]['notes']}}">
                            <tr class="even center" style="font-size: 13px;">
                                <td style="border-style:solid; border-color:grey; border-width: 0px 1px 1px 1px;" type="text"><span name="cluster-{{$t}}" id="cluster-{{$t}}">{{$table[$t]['cluster']}}</span></td>
                                <td style="border-style:solid; border-color:black; border-width: 0px 1px 1px 1px ; width:3%;" type="text" name="project-{{$t}}" id="project-{{$t}}">{{$table[$t]['project']}}</td>
                                <td style=" font-size: 13px; font-weight:bold; border-style:solid; border-color:black; border-width: 0px 1px 1px 1px" type="text" name="client-{{$t}}" id="client-{{$t}}" value="{{$table[$t]['cID']}}">{{ucfirst($table[$t]['client'])}}</td>
                                <td style="font-size: 13px; font-weight:bold; border-style:solid; border-color:black; border-width: 0px 1px 1px 1px" type="text" name="agency-{{$t}}" id="agency-{{$t}}" value="{{$table[$t]['aID']}}">{{ucfirst($table[$t]['agency'])}}</td>
                                <td style="border-style:solid; border-color:black; border-width: 0px 1px 1px 1px" type="text" name="ae1-{{$t}}" id="ae1-{{$t}}" value="{{$table[$t]['primary_ae_id']}}">{{$table[$t]['primary_ae']}}</td>
                                <td style="border-style:solid; border-color:black; border-width: 0px 1px 1px 1px" type="text" name="ae2-{{$t}}" id="ae2-{{$t}}" value="{{$table[$t]['second_ae_id']}}">{{$table[$t]['second_ae']}}</td>
                                <td style="border-style:solid; border-color:black; border-width: 0px 1px 1px 1px" style="width:2% !important" type="text" name="manager-{{$t}}" id="manager-{{$t}}">{{$table[$t]['manager']}}</td>
                                <td style="border-style:solid; border-color:black; border-width: 0px 1px 1px 1px"><input readonly='true' placeholder="0" pattern="^\$\d{3.3}(.\d{3})*(\,\d+)?" data-type="currency"  type="text" name="tv-{{$t}}" id="tv-{{$t}}" style="width: 100px; font-size: 13px; background-color:transparent; border:none; font-weight:bold; text-align:center;" value="{{number_format($table[$t]['tv_value'],0,',','.')}}"></td>
                                <td style="border-style:solid; border-color:black; border-width: 0px 1px 1px 1px"><input readonly='true' placeholder="0" pattern="^\$\d{3.3}(.\d{3})*(\,\d+)?" data-type="currency"  type="text" name="digital-{{$t}}" id="digital-{{$t}}" style="width: 100px; font-size: 13px; background-color:transparent; border:none; font-weight:bold; text-align:center;" value="{{number_format($table[$t]['digital_value'],0,',','.')}}"></td>
                                <td style="border-style:solid; border-color:black; border-width: 0px 1px 1px 1px" type="text" name="total-{{$t}}" id="total-{{$t}}" style="width: 100px; font-size: 13px; background-color:transparent; border:none; font-weight:bold; text-align:center;">{{number_format($totalPerPacket[$t],0,',','.')}}</td>
                               <td style="border-style:solid; border-color:black; border-width: 0px 1px 1px 1px" style="width:2% !important">
                                    <select name="startMonth-{{$t}}" id="startMonth-{{$t}}" style="-webkit-appearance: none; font-size: 13px; width: 70px; text-align: center; font-weight:bold; background-color:transparent; border:none; font-weight:bold; text-align:center;">
                                          @for($m=0; $m<sizeof($intMonth);$m++)
                                            <option <?php if($intMonth[$m] == $table[$t]['start_month']) { echo "selected";}?> value="{{$intMonth[$m]}}">{{$month[$m]}}</option>
                                        @endfor                                        
                                    </select>
                                </td>
                                <td style="border-style:solid; border-color:black; border-width: 0px 1px 1px 1px" style="width:2% !important">
                                    <select name="endMonth-{{$t}}" id="endMonth-{{$t}}" style="-webkit-appearance: none; width: 70px; text-align: center; font-weight:bold; font-size: 13px; background-color:transparent; border:none; font-weight:bold; text-align:center;">
                                          @for($m=0; $m<sizeof($intMonth);$m++)
                                            <option <?php if($intMonth[$m] == $table[$t]['end_month']) { echo "selected";}?> value="{{$intMonth[$m]}}">{{$month[$m]}}</option>
                                        @endfor                                        
                                    </select></td>
                                <td style="border-style:solid; border-color:black; border-width: 0px 1px 1px 1px" type="text" name="quota-{{$t}}" id="quota-{{$t}}">{{$table[$t]['quota']}}</td>
                                <td style="border-style:solid; border-color:black; border-width: 0px 1px 1px 1px" type="text" name="status-{{$t}}" id="status-{{$t}}">{{$table[$t]['status']}}</td>
                                <td style="border-style:solid; border-color:black; border-width: 0px 1px 1px 1px" type="text" maxlength="300" name="notes" type="text" > <input type="text" maxlength="300" name="notes-{{$t}}" id="notes-{{$t}}" class="form-control" style="font-size: 13px; width: 100%; background-color:transparent; border:none; font-weight:bold; text-align:center; border-width: 1px; border-color: grey;" readonly='true' value="{{$table[$t]['notes']}}"></td> 
                                <td>
                                    <button type="button" class="btn btn-warning btn-sm edit" data-toggle="modal" data-target="#modalEditar-{{$t}}" onclick="edit({{$t}})"><span class="glyphicon glyphicon-edit">Edit</span></button>
                                </td>                              
                            </tr>        
                        @endfor
                        <tr>
                            <td colspan='19' style="border-style:solid; border-color:black; border-width: 1px 0px 0px 0px;"></td>
                        </tr>               
                        
                    </table>

                    <!-- Modal to edit a line  -->
                        <div class="modal fade" id="modalEditar">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">                               
                                        <h4 class="modal-title">Editar Registro</h4>
                                        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span></button>
                                    </div>
                                  <div class="modal-body">
                                    <input type='hidden' readonly='true' type="text" name="editID" id="editID" style="background-color:transparent; border:none; font-weight:bold; text-align:center;" value="">
                                    <label>Cluster</label>                                                
                                        <select class='selectpicker' id='editCluster' name='editCluster[]' data-selected-text-format='count' data-width='100%' class='form-control' data-live-search='true'>
                                            <option value=''> Select </option>
                                            @for($c=0; $c<sizeof($info[1]);$c++)
                                                <option value="{{$info[1][$c]['cluster']}}">{{$info[1][$c]['cluster']}}</option>
                                            @endfor
                                        </select><br>
                                    <label>Property</label>
                                        <select class='selectpicker' id='editProject' name='editProject[]' data-selected-text-format='count' data-width='100%' class='form-control' data-live-search='true'>
                                            <option value=''> Select </option>
                                            @for($p=0; $p<sizeof($info[3]);$p++)
                                                <option value="{{$info[3][$p]['project']}}">{{$info[3][$p]['project']}}</option>
                                            @endfor
                                        </select><br>
                                    <label>Client</label>
                                        <select class='selectpicker' id='editClient' name='editClient[]' data-selected-text-format='count' data-width='100%' class='form-control' data-live-search='true'>
                                            <option value='0' selected='true'> Select </option>
                                            @for($x=0; $x<sizeof($info[5]);$x++)
                                                <option  value="{{$info[5][$x]['clientId']}}">{{$info[5][$x]['client']}}</option>
                                            @endfor
                                        </select><br>
                                    <label>Agency</label>
                                        <select class='selectpicker' id='editAgency' name='editAgency[]' data-selected-text-format='count' data-width='100%' class='form-control' data-live-search='true'>
                                        <option value='578'> Select </option>
                                            @for($z=0; $z<sizeof($info[6]);$z++)
                                                <option value="{{$info[6][$z]['aID']}}">{{$info[6][$z]['agency']}}</option>
                                            @endfor
                                        </select><br>                                               
                                    <label>Ae 1</label>
                                         <select class='selectpicker' id='editAe1' name='editAe1[]' data-selected-text-format='count' data-width='100%' class='form-control' data-live-search='true'>
                                            <option value=''> Select </option>
                                            @for($s=0; $s<sizeof($rep);$s++)
                                                <option   value="{{$rep[$s]['id']}}">{{$rep[$s]['salesRep']}}</option>
                                            @endfor
                                        </select><br>
                                    <label>Ae 2</label>
                                        <select class='selectpicker' id='editAe2' name='editAe2[]' data-selected-text-format='count' data-width='100%' class='form-control' data-live-search='true'>
                                            <option value='289'> Select </option>
                                            @for($ss=0; $ss<sizeof($rep2);$ss++)
                                                <option  value="{{$rep2[$ss]['id']}}">{{$rep2[$ss]['salesRep']}}</option>
                                            @endfor
                                        </select> <br>
                                     <label>Manager</label>
                                     <select class='selectpicker' id='editManager' name='editManager[]' data-selected-text-format='count' data-width='100%' class='form-control' data-live-search='true'>
                                        <option value=''> Select </option>
                                            @for($l=0; $l<sizeof($info[7]);$l++)
                                                <option  value="{{$info[7][$l]}}">{{$info[7][$l]}}</option>
                                            @endfor
                                        </select><br>
                                    <label>TV Values</label>
                                    <input  type="text" name="editTv" id="editTv" class="form-control" style="width: 100%; background-color:transparent; border:solid; font-weight:bold; text-align:center; border-width: 1px; border-color: grey;" placeholder="0"  value=""><br>
                                    <label>Digital Values</label>
                                    <input  type="text" name="editDigital" id="editDigital" class="form-control" style="width: 100%; background-color:transparent; border:solid; font-weight:bold; text-align:center; border-width: 1px; border-color: grey;" placeholder="0"  value=""><br>
                                     <label>First Month</label>
                                        <select class='selectpicker' id='editFirstMonth' name='editFirstMonth[]' data-selected-text-format='count' data-width='100%' class='form-control' data-live-search='true'>
                                            <option value=''> Select </option>
                                            @for($m=0; $m<sizeof($intMonth);$m++)
                                                <option  value="{{$intMonth[$m]}}">{{$month[$m]}}</option>
                                            @endfor
                                        </select><br>
                                    <label>Last Month</label>
                                        <select class='selectpicker' id='editEndMonth' name='editEndMonth[]' data-selected-text-format='count' data-width='100%' class='form-control' data-live-search='true'>
                                            <option value=''> Select </option>
                                            @for($m=0; $m<sizeof($intMonth);$m++)
                                                <option value="{{$intMonth[$m]}}">{{$month[$m]}}</option>
                                            @endfor
                                        </select><br>
                                    <label>Quota</label>
                                        <select class='selectpicker' id='editQuota' name='editQuota[]' data-selected-text-format='count' data-width='100%' class='form-control' data-live-search='true'>
                                            <option value=''> Select </option>
                                            @for($q=0; $q<sizeof($info[2]);$q++)
                                                <option  value="{{$info[2][$q]['quota']}}">{{$info[2][$q]['quota']}}</option>
                                            @endfor
                                        </select><br>
                                    <label>Status</label>
                                    <select class='selectpicker' id='editStatus' name='editStatus[]' data-selected-text-format='count' data-width='100%' data-live-search='true'>
                                        <option value='0 - Exploração'> Select </option>
                                            @for($v=0; $v<sizeof($info[4]);$v++)
                                                <option  value="{{$info[4][$v]}}">{{$info[4][$v]}}</option>
                                            @endfor
                                        </select><br>
                                    <label>Note</label>
                                    <input type="text" maxlength="300" name="editNotes" id="editNotes" class="form-control" style="width: 100%; background-color:transparent; border:solid; font-weight:bold; text-align:center; border-width: 1px; border-color: grey;" value="">
                                  </div>
                                  <div class="modal-footer">
                                    <button type="submit" class="btn btn-primary" style="width: 100%;">Edit</button>
                                  </div>
                                </div>
                            </div>
                        </div>
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
                          Add Pipeline
                        </button>
                    </div>
                    <!-- Modal to insert a new client -->
                    <div class="modal fade" id="modalExemplo" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">New Pipeline</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                                     <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="row justify-content-center">          
                                        <div class="col">       
                                            <div class="form-group">
                                                <label>Register</label>
                                                <input type="text" name="newRegister" id="newRegister" class='form-control' readonly='true' style="width: 100%; background-color:transparent; border:solid; font-weight:bold; text-align:center; border-width: 1px; border-color: grey;" value="FORECAST"><br>
                                                <label>Cluster</label>                                                
                                                    <select class='selectpicker' id='newCluster' name='newCluster[]' data-selected-text-format='count' data-width='100%' class='form-control' data-live-search='true'>
                                                        <option value=''> Select </option>
                                                        @for($c=0; $c<sizeof($info[1]);$c++)
                                                            <option value="{{$info[1][$c]['cluster']}}">{{$info[1][$c]['cluster']}}</option>
                                                        @endfor
                                                    </select><br>
                                                <label>Property</label>
                                                    <select class='selectpicker' id='newProject' name='newProject[]' data-selected-text-format='count' data-width='100%' class='form-control' data-live-search='true'>
                                                        <option value=''> Select </option>
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
                                                    <option value='578'> Select </option>
                                                        @for($z=0; $z<sizeof($info[6]);$z++)
                                                            <option value="{{$info[6][$z]['aID']}}">{{$info[6][$z]['agency']}}</option>
                                                        @endfor
                                                    </select><br>                                               
                                                <label>Ae 1</label>
                                                     <select class='selectpicker' id='newAe1' name='newAe1[]' data-selected-text-format='count' data-width='100%' class='form-control' data-live-search='true'>
                                                        <option value=''> Select </option>
                                                        @for($s=0; $s<sizeof($rep);$s++)
                                                            <option value="{{$rep[$s]['id']}}">{{$rep[$s]['salesRep']}}</option>
                                                        @endfor
                                                    </select><br>
                                                <label>AE 2</label>
                                                    <select class='selectpicker' id='newAe2' name='newAe2[]' data-selected-text-format='count' data-width='100%' class='form-control' data-live-search='true'>
                                                        <option value='289'> Select </option>
                                                        @for($ss=0; $ss<sizeof($rep2);$ss++)
                                                            <option value="{{$rep2[$ss]['id']}}">{{$rep2[$ss]['salesRep']}}</option>
                                                        @endfor
                                                    </select> <br>
                                                 <label>Manager</label>
                                                 <select class='selectpicker' id='newManager' name='newManager[]' data-selected-text-format='count' data-width='100%' class='form-control' data-live-search='true'>
                                                    <option value='BP'> Select </option>
                                                        @for($l=0; $l<sizeof($info[7]);$l++)
                                                            <option value="{{$info[7][$l]}}">{{$info[7][$l]}}</option>
                                                        @endfor
                                                    </select><br>
                                                <label>TV Values</label>
                                                <input  type="text" name="newTv" id="newTv" class="form-control" style="width: 100%; background-color:transparent; border:solid; font-weight:bold; text-align:center; border-width: 1px; border-color: grey;" placeholder="0" pattern="^\$\d{3.3}(.\d{3})*(\,\d+)?" data-type="currency" value=""><br>
                                                <label>Digital Values</label>
                                                <input  type="text" name="newDigital" id="newDigital" class="form-control" style="width: 100%; background-color:transparent; border:solid; font-weight:bold; text-align:center; border-width: 1px; border-color: grey;" placeholder="0" pattern="^\$\d{3.3}(.\d{3})*(\,\d+)?" data-type="currency" value=""><br>
                                                 <label>First Month</label>
                                                    <select class='selectpicker' id='newFirstMonth' name='newFirstMonth[]' data-selected-text-format='count' data-width='100%' class='form-control' data-live-search='true'>
                                                        <option value=''> Select </option>
                                                        @for($m=0; $m<sizeof($intMonth);$m++)
                                                            <option value="{{$intMonth[$m]}}">{{$month[$m]}}</option>
                                                        @endfor
                                                    </select><br>
                                                <label>Last Month</label>
                                                    <select class='selectpicker' id='newEndtMonth' name='newEndMonth[]' data-selected-text-format='count' data-width='100%' class='form-control' data-live-search='true'>
                                                        <option value=''> Select </option>
                                                        @for($m=0; $m<sizeof($intMonth);$m++)
                                                            <option value="{{$intMonth[$m]}}">{{$month[$m]}}</option>
                                                        @endfor
                                                    </select><br>
                                                <label>Quota</label>
                                                    <select class='selectpicker' id='newQuota' name='newQuota' data-selected-text-format='count' data-width='100%' class='form-control' data-live-search='true'>
                                                        <option value=''> Select </option>
                                                        @for($q=0; $q<sizeof($info[2]);$q++)
                                                            <option value="{{$info[2][$q]['quota']}}">{{$info[2][$q]['quota']}}</option>
                                                        @endfor
                                                    </select><br>
                                                <label>Status</label>
                                                <select class='selectpicker' id='newStatus' name='newStatus' data-selected-text-format='count' data-width='100%'  data-live-search='true'>
                                                    <option value='0 - Exploração'> Select </option>
                                                        @for($v=0; $v<sizeof($info[4]);$v++)
                                                            <option value="{{$info[4][$v]}}">{{$info[4][$v]}}</option>
                                                        @endfor
                                                    </select><br>
                                                <label>Note</label>
                                                <input type="text" maxlength="300" name="newNotes" id="newNotes" class="form-control" style="width: 100%; background-color:transparent; border:solid; font-weight:bold; text-align:center; border-width: 1px; border-color: grey;" value="">
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


<script type="text/javascript">
    function edit(id){


        var pipeline = $("#pipeline-" + id).val();
        const dados = pipeline.split(",");

        //console.log(dados);
       
        const editModal = new bootstrap.Modal(document.getElementById("modalEditar"));
        editModal.show();

        document.getElementById("editID").value = dados[0];
        document.getElementById("editCluster").value = dados[1];
        document.getElementById("editProject").value = dados[2];
        document.getElementById("editClient").value = dados[3];
        document.getElementById("editAgency").value = dados[4];
        document.getElementById("editAe1").value = dados[5];
        document.getElementById("editAe2").value = dados[7];
        document.getElementById("editManager").value = dados[9];
        document.getElementById("editTv").value = dados[10].replace(/\.00000$/,'');
        document.getElementById("editDigital").value = dados[11].replace(/\.00000$/,'');
        document.getElementById("editFirstMonth").value = dados[12];
        document.getElementById("editEndMonth").value = dados[13];
        document.getElementById("editQuota").value = dados[14];
        document.getElementById("editStatus").value = dados[15];
        document.getElementById("editNotes").value = dados[16];

    }

</script>
<!-- javascript to make the excel export -->
<script type="text/javascript">
            
    $(document).ready(function(){

        ajaxSetup();

        $('#excel').click(function(event){

            var region = "<?php echo $region[0]['id']; ?>";
            var clientString = "<?php echo $clientString; ?>";
            var agencyString = "<?php echo $agencyString; ?>";
            var propString = "<?php echo $propString; ?>";
            var managerString = "<?php echo $managerString; ?>";
            var statusString = "<?php echo $statusString; ?>";
            var salesRepString = "<?php echo $salesRepString ?>";
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
                url: "/generate/excel/viewer/vPipeline",
                type: "POST",
                data: {region,rep, typeExport, auxTitle,title,clientString,agencyString,propString,managerString,statusString,salesRepString},
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

    $('#newAe1').change(function(){
    
    var salesRep = $("#newAe1").val();
        if (salesRep != "") {

          $.ajax({
            url:"/ajax/adsales/getManager",
            method:"POST",
            data:{salesRep},
            success: function(output){
              $('#newManager').html(output).selectpicker('refresh');
              //$('#vlau ').html(output).selectpicker('refresh');
            },
            error: function(xhr, ajaxOptions,thrownError){
                alert(xhr.status+" "+thrownError);
            }
          });  
        }else{
          var option = "<option> Select Rep 1 </option>";
          $('#newManager').empty().append(option).selectpicker('refresh');
        }
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
            if (String.fromCharCode(charCode).match('/\B(?=(\d{3})+(?!\d))/g, ""'))  
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
    // função para desabilitar a tecla F5.
    window.onkeydown = function (e) {
        if (e.keyCode === 116) {
            alert("Função não permitida para evitar duplicidades indevidas!");
            e.keyCode = 0;
            e.returnValue = false;
            return false;
        }
    }


</script>
@endsection