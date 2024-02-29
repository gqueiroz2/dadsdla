@extends('layouts.mirror')
@section('title', 'Monthly Report')
@section('head')	
    <?php include(resource_path('views/auth.php')); ?>
    <script src="/js/pandr.js"></script>
@endsection
@section('content')
	

	<form method="POST" action="{{ route('forecastByAEPost') }}" runat="server"  onsubmit="ShowLoading()" onkeydown="return event.key != 'Enter';">
        @csrf
        <div class="container-fluid">       
            <div class="row">
                <div class="col" style="display:none;">
                    <label class='labelLeft'><span class="bold">Region:</span></label>
                    @if($errors->has('region'))
                        <label style="color: red;">* Required</label>
                    @endif
                        {{$render->regionFiltered($region, $regionID, $special )}}                   
                </div>
                <div class="col">
                    <label class='labelLeft'><span class="bold">Month:</span></label>
                    @if($errors->has('year'))
                        <label style="color: red;">* Required</label>
                    @endif
                    {{$render->month($months)}}
                </div>
                <div class="col" style="display:none;">
                    <label class='labelLeft'><span class="bold">Year:</span></label>
                    @if($errors->has('year'))
                        <label style="color: red;">* Required</label>
                    @endif
                    {{$render->year()}}
                </div>
                <div class="col">
                    <label class='labelLeft'><span class="bold">Sales Rep:</span></label>
                    @if($errors->has('salesRep'))
                        <label style="color: red;">* Required</label>
                    @endif
                    {{$render->salesRep2()}}
                </div>
                <div class="col" style="display: none;">
                    <label class='labelLeft'><span class="bold">Currency:</span></label>
                    @if($errors->has('currency'))
                        <label style="color: red;">* Required</label>
                    @endif
                    {{$render->currency($currency)}}
                </div>  
                <div class="col" style="display: none;">
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
	<div class="container-fluid">
		<div class="row justify-content-end mt-2">
			<div class="col-3" style="color: #0070c0;font-size: 25px;">
				Monthly Forecast
			</div>
		</div>
	</div>
	<div id="vlau">
		
	</div>

@endsection