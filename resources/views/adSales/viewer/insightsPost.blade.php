@extends('layouts.mirror')
@section('title', 'Insights Viewer')
@section('head')

    <script src="/js/insights.js"></script>

    <?php include(resource_path('views/auth.php'));?>

@endsection
@section('content')

	<div class="container-fluid">
		<div class="row">
			<div class="col">
				<form method="POST" action="{{route('insightsPost')}}" runat="server" onsubmit="ShowLoading()">
					@csrf
    				<div class="container-fluid">
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

                            <div class="col">
                                <label class='labelLeft'><span class="bold">Client:</span></label>
                                @if($errors->has('client'))
                                    <label style="color: red;">* Required</label>
                                @endif
                                {{$render->ClientForm()}}
                            </div>
                            
                            <div class="col">
                                <label class='labelLeft'><span class="bold">Months:</span></label>
                                @if($errors->has('month'))
                                    <label style="color: red;">* Required</label>
                                @endif
                                {{$render->months()}}
                            </div>
                            
                            <div class="col">
                                <label class="labelLeft"><span class="bold"> Brand: </span></label>
                                @if($errors->has('brand'))
                                    <label style="color: red;">* Required</label>
                                @endif
                                {{$render->brand($brand)}}
                            </div>

                            <div class="col">
                                <label class='labelLeft'><span class="bold">Sales Rep:</span></label>
                                @if($errors->has('salesRep'))
                                    <label style="color: red;">* Required</label>
                                @endif
                                {{$render->salesRep()}}
                            </div>                        

                            <div class="col">
                                <label class="labelLeft"><span class="bold"> Currency: </span></label>
                                @if($errors->has('currency'))
                                    <label style="color: red;">* Required</label>
                                @endif
                                {{$render->currency($currencies)}}
                            </div>

                            <div class="col">
                                <label class="labelLeft"><span class="bold"> Value: </span></label>
                                @if($errors->has('value'))
                                    <label style="color: red;">* Required</label>
                                @endif
                                {{$render->value2()}}
                            </div>
                            
                            <div class="col">
                                <label> &nbsp; </label>
                                <input type="submit" value="Generate" class="btn btn-primary" style="width: 100%;">     
                            </div>

    					</div>
                    </div>
				</form>
			</div>
		</div>

		<div class="row justify-content-end mt-2">
            <div class="col-9"></div>            

			<div class="col" style="color: #0070c0; font-size:22px">
                <span style="float: right; margin-right: 2.5%;">Insights</span>
            </div>

            
            <div class="col">
                <button class="btn btn-primary" type="button" id="excel" style="width: 100%">
                    Generate Excel
                </button>
            </div>
        </div>
	</div>

    <div class="container-fluid">
            <div class="row mt-4">
                <div class="col table-responsive">
                    <table class="table" style="width: 100%;">
                        <tr>
                            @for($h=0;$h<sizeof($header);$h++)
                                <th> {{$header[$h]}} </th>
                            @endfor
                        </tr>
                        @if($mtx)
                            @for($m = 0;$m < sizeof($mtx);$m++)

                            @endfor
                        @endif
                    </table>
                </div>
            </div>

        </div>

    <div id="vlau"></div>

    

@endsection