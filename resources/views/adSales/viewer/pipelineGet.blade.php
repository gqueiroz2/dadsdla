@extends('layouts.mirror')
@section('title', 'Pipelines')
@section('head')
	 <script src="/js/pipeline.js"></script>
    <?php include(resource_path('views/auth.php'));
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
							<label class="labelLeft" ><span class="bold"> Region: </span></label>

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
                <span style="float: right; margin-right: 2.5%;">Pipeline</span>
            </div>
		</div>
		<div>
     		<img src="/pipeline_warning.png"  style="display: block; margin: 0 auto;" width="auto" height="auto">
     	</div>

	</div>



@endsection