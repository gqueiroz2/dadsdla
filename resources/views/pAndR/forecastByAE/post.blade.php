@extends('layouts.mirror')
@section('title', 'AE Report')
@section('head')	
    <?php include(resource_path('views/auth.php')); ?>
    <script src="/js/pandr.js"></script>
@endsection
@section('content')
	

	<form method="POST" action="{{ route('forecastByAEPost') }}" runat="server"  onsubmit="ShowLoading()">
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
						{{$render->regionFiltered($region, $regionID, $special )}}
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
					<label class='labelLeft'><span class="bold">Sales Rep:</span></label>
					@if($errors->has('salesRep'))
						<label style="color: red;">* Required</label>
					@endif
					{{$render->salesRep2()}}
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
			<br>
			<div class="row">
				<center style="width: 100%;">
					<div class="col-3">
						@if($typeMsg == "Success")
							<div class="alert alert-info">
								{{$msg}}
							</div>
						@elseif($typeMsg == "Error")
							<div class="alert alert-danger">
								{{$msg}}
							</div>
						@endif
					</div>
				</center>
			</div>
		</div>
	</form>
	<div class="container-fluid">
		<div class="row justify-content-end mt-2">
			<div class="col-3" style="color: #0070c0;font-size: 25px;">
				Forecast by AE
			</div>
		</div>
	</div>

	<br>
    <div class="container-fluid" id="body">
        <form method="POST" action="" runat="server"  onsubmit="ShowLoading()">
        @csrf
            <div class="row justify-content-end">             
                <div class="col" >
                    <div class="container-fluid">
                        <div class="row justify-content-end">
                            <div class="col-2">
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
                                <input type="submit" id="button" value="Save" class="btn btn-primary" style="width: 100%">      
                            </div>
                        </div>
                    </div>
                </div>  
            </div>
            <div class="row mt-2 justify-content-end">
                <div class="col" style="width: 100%;">
                    <center>
                        {{$render->loadForecast($forRender)}}
                    </center>
                </div>
            </div>

        </form>
    </div>

	<div id="vlau">
		
	</div>

@endsection