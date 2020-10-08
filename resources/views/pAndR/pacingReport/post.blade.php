@extends('layouts.mirror')
@section('title', 'Pacing')
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
	
	@if($userLevel == 'SU' || $userLevel == 'L0' || $userLevel == 'L1' )

	<form method="POST" action="{{ route('pacingReportPost') }}" runat="server"  onsubmit="ShowLoading()">
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
	<div class="container-fluid">
		<div class="row justify-content-end mt-2">
			<div class="col-4" style="color: #0070c0;font-size: 25px;">
				(P&R) Pacing Report - {{date('Y')}} - ({{$forRender['currency']}}/{{ strtoupper($forRender['value']) }})
			</div>
		</div>
	</div>
	<div class="container-fluid">
		<div class="row">
			<div class="col"> 
				<div class="container-fluid" style='width: 100%; zoom: 85%;font-size: 16px;'>		
					{{$render->pacingReport($brands,$forRender)}}
				</div>
			</div>
		</div>
	</div>

	<script>
		$('.linked').scroll(function(){
    		$('.linked').scrollLeft($(this).scrollLeft());
		});
	</script>

	@endif

@endsection