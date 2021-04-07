@extends('layouts.mirror')
@section('title', 'Brand Viewer')
@section('head')	
    <?php include(resource_path('views/auth.php')); 
    $month = array('Jan','Feb','Mar','Q1','Apr','May','Jun','Q2','Jul','Aug','Sep','Q3','Oct','Nov','Dec','Q4');?>
    <script src="/js/pandr.js"></script>
    <style type="text/css">
        ::-webkit-scrollbar{
            height: 15px;
        }
        ::-webkit-scrollbar-track {
            background: #d9d9d9; 
        }
        ::-webkit-scrollbar-thumb {
            background: #666666;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #4d4d4d; 
        }


        #loading {
            position: absolute;
            left: 0px;
            top:0px;
            margin:0px;
            width: 100%;
            height: 105%;
            display:block;
            z-index: 99999;
            opacity: 0.9;
            -moz-opacity: 0;
            filter: alpha(opacity = 45);
            background: white;
            background-image: url("/loading.gif");
            background-repeat: no-repeat;
            background-position:50% 50%;
            text-align: center;
            overflow: hidden;
            font-size:30px;
            font-weight: bold;
            color: black;
            padding-top: 20%;
        }

    </style>
@endsection
@section('content')
	

	<form method="POST" action="{{ route('byBrandPost') }}" runat="server"  onsubmit="ShowLoading()">
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
		</div>
	</form>
	<div class="container-fluid">
		<div class="row justify-content-end mt-2">
			<div class="col-3" style="color: #0070c0;font-size: 25px;">
				By Brand Viewer
			</div>
		</div>
	</div>

	<div class="row mt-2 justify-content-end">
                <div class="col" style="width: 100%;">
                    <center>
                    	 {{$render->byBrand($forRender,$brands)}}
                    </center>
                </div>
            </div>
	<div id="vlau">
		
	</div>

@endsection