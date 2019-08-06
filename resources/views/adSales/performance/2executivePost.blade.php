@extends('layouts.mirror')
@section('title', 'Executive')
@section('head')	
<script src="/js/performance.js"></script>
    <?php include(resource_path('views/auth.php')); 
    ?>
    <style>
	table{
		text-align: center;
	}
</style>
@endsection
@section('content')
	<div class="container-fluid">		
		<div class="row">
			<div class="col">
				

				<form method="POST" action="{{ route('executivePerformancePost') }}"  runat="server"  onsubmit="ShowLoading()">
					@csrf
					<div class="row justify-content-center">
						<div class="col">	
							<label class='labelLeft'><span class="bold">Region:</span></label>
							@if($errors->has('region'))
								<label style="color: red;">* Required</label>
							@endif
							@if($userLevel == 'L0' || $userLevel == 'SU')
								{{$render->region($region)}}							
							@else
								{{$render->regionFiltered($region, $regionID )}}
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
							<label class='labelLeft'><span class="bold">Tiers:</span></label>
							@if($errors->has('brand'))
								<label style="color: red;">* Required</label>
							@endif
							{{$render->tiers()}}
						</div>
						<div class="col">
							<label class='labelLeft'><span class="bold">Brands:</span></label>
							@if($errors->has('brand'))
								<label style="color: red;">* Required</label>
							@endif
							{{$render->brand($brand)}}
						</div>
						<div class="col">
							<label class='labelLeft'><span class="bold">Months:</span></label>
							@if($errors->has('month'))
								<label style="color: red;">* Required</label>
							@endif
							{{$render->months()}}
						</div>
					</div>
					<div class="row justify-content-center">
						<div class="col">
							<label class='labelLeft'><span class="bold">Sales Rep Group:</span></label>
							@if($errors->has('salesRepGroup'))
								<label style="color: red;">* Required</label>
							@endif
							{{$render->salesRepGroup($salesRepGroup)}}
						</div>
						<div class="col">
							<label class='labelLeft'><span class="bold">Sales Rep:</span></label>
							@if($errors->has('salesRep'))
								<label style="color: red;">* Required</label>
							@endif
							{{$render->salesRep()}}
						</div>
						<div class="col">
							<label class='labelLeft'><span class="bold">Currency:</span></label>
							@if($errors->has('currency'))
								<label style="color: red;">* Required</label>
							@endif
							{{$render->currency($currency)}}
						</div>
						<div class="col">
							<label class='labelLeft'><span class="bold">Value:</span></label>
							@if($errors->has('value'))
								<label style="color: red;">* Required</label>
							@endif
							{{$render->value()}}
						</div>
						<div class="col">
							<label class='labelLeft'> &nbsp; </label>
							<input style="width: 100%;" type="submit" value="Generate" class="btn btn-primary">		
						</div>
					</div>
				</form>
				<div class="row justify-content-end">
					<div class="col col-3"  style="text-align: center; margin-top: 2%;">
						<span class="reportsTitle">Executive Performance</span>
					</div>
				</div>
			</div>
		</div>
		<div class="row mt-2">
			<div class="col">
				<div class="container-fluid">
					<div class="form-group">
						<div class="form-inline" style='width:100%; margin-left: 1.2%; margin-right: auto;'>
							<div class="row" style="width: 100%;">
								<div class="col" id="type1" style=" width: 100%; margin-top: 2%; display: block;">
									<div class="container-fluid">
										{{$render->case1($mtx)}}
									</div>
								</div>
							</div>
							<div class="row" style="width: 100%;">
								<div class="col" id="type2" style=" width: 100%; margin-top: 2%; display: none;">
									<div class="container-fluid">
										{{$render->case2($mtx)}}
									</div>
								</div>
							</div>
							<div class="row" style="width: 100%;">
								<div class="col" id="type3" style=" width: 100%; margin-top: 2%; display: none;">
									<div class="container-fluid">
										{{$render->case3($mtx)}}
									</div>
								</div>
							</div>
							<div class="row" style="width: 100%;">
								<div class="col" id="type4" style=" width: 100%; margin-top: 2%; display: none;">
									<div class="container-fluid">
										{{$render->case4($mtx)}}
									</div>
								</div>
							</div>
						</div>
					</div>					
				</div>
			</div>
		</div>
	</div>

	<div id="vlau"></div>

	<script>
		var matrix = [true,true];
		
		$(document).ready(function(){   

			$(".tierClick").click(function(){
				if (matrix[0]) {
					matrix[0] = false;
				}else{
					matrix[0] = true;
				}
				loadMatrix(matrix);
			});
			
			$(".quarterClick").click(function(){
				if (matrix[1]) {
					matrix[1] = false;
				}else{
					matrix[1] = true;
				}
				loadMatrix(matrix);
			});

		});


		function loadMatrix(matrix){
			$("#type1").css("display","hidden");
			for (var i = 1; i < 5; i++) {
				$("#type"+i).css("display","none");
			}

			if(matrix[0] && matrix[1]){
				$("#type1").css("display","block");
			}else if(!matrix[0] && matrix[1]){
				$("#type2").css("display","block");
			}else if(matrix[0] && !matrix[1]){
				$("#type3").css("display","block");
			}else{
				$("#type4").css("display","block");
			}
		}

	</script>
@endsection