@extends('layouts.mirror')
@section('title', 'Ranking')
@section('head')	
	<script src="/js/ranking.js"></script>
    <?php include(resource_path('views/auth.php')); ?>
@endsection
@section('content')
	<div class="container-fluid">
		<div class="row">
			<div class="col">
				<form method="POST" action="{{ route('rankingPost') }}" runat="server" onsubmit="ShowLoading()">
					@csrf
					<div class="row">
						<div class="col">
							<label class="labelLeft bold"> Region: </label>
							@if($errors->has('region'))
								<label style="color: red;">* Required</label>
							@endif
							@if($userLevel == 'L0' || $userLevel == 'SU')
								{{$render->region($salesRegion)}}
							@else
								{{$render->regionFiltered($region, $regionID)}}
							@endif
						</div>
						<div class="col">
							<label class="labelLeft bold"> Type: </label>
							@if($errors->has('type'))
								<label style="color: red;">* Required</label>
							@endif
								{{$render->type()}}
						</div>
						<div class="col">
							<label class="labelLeft bold" style="color: red" id="typeName">&nbsp;</label>
							@if($errors->has('type'))
								<label style="color: red;">* Required</label>
							@endif
							{{$render->type2()}}
						</div>
						<div class="col">
							<label class="labelLeft bold">Nº of pos: </label>
							@if($errors->has('nPos'))
								<label style="color: red">* Required</label>
							@endif
								{{$render->nPos()}}
						</div>
						<div class="col">
							<label class="labelLeft bold">Months:</label>
							@if($errors->has('month'))
								<label style="color: red">* Required</label>
							@endif
								{{$render->months()}}
						</div>
						<div class="col">
							<label class="labelLeft bold"> Brand: </label>
							@if($errors->has('brands'))
								<label style="color: red">* Required</label>
							@endif
								{{$render->brand($brand)}}
						</div>
					</div>
					<div class="row">
						<div class="col">
							<label class="labelLeft bold">1º Pos:</label>
							@if($errors->has('firstPos'))
								<label style="color: red">* Required</label>
							@endif
								{{$render->positionYear("first")}}
						</div>
						<div class="col">
							<label class="labelLeft bold">2º Pos:</label>
							@if($errors->has('secondPos'))
								<label style="color: red">* Required</label>
							@endif
								{{$render->positionYear("second")}}
						</div>
						<div class="col">
							<label class="labelLeft bold">3º Pos:</label>
							@if($errors->has('thirdPos'))
								<label style="color: red">* Required</label>
							@endif
								{{$render->positionYear("third")}}
						</div>
						<div class="col">
							<label class="labelLeft bold"> Currency: </label>
							@if($errors->has('currency'))
								<label style="color: red;">* Required</label>
							@endif
							{{$render->currency()}}
						</div>
						<div class="col">
							<label class="labelLeft bold"> Value: </label>
							@if($errors->has('value'))
								<label style="color: red;">* Required</label>
							@endif
							{{$render->value2()}}
						</div>
						<div class="col">
							<label class="labelLeft"><span class="bold"> &nbsp; </span> </label>
							<input type="submit" value="Generate" class="btn btn-primary" style="width: 100%;">
						</div>
					</div>
				</form>
			</div>
		</div>
		
		<div class="row justify-content-end mt-2">
			<div class="col-sm-3" style="color: #0070c0;font-size: 22px;">
				<span style="float: right;"> 
					<?php $newType = ($type == "agencyGroup") ? "Agency group" : ucfirst($type) ?>
					{{$rName}} - {{$newType}} Ranking 
				</span>
			</div>
		</div>	

	</div>


	<div class="container-fluid">
		<div class="row mt-2 justify-content-center">
			<div class="col">
				{{$render->assemble($mtx, $names, $pRate, $value, $total, $size, $type)}}
			</div>
		</div>
	</div>


	<script type="text/javascript">
		$(document).ready(function(){
			@if ($type != "client")

				var months = <?php echo json_encode($months); ?>;
                var brands = <?php echo json_encode($brands); ?>;
                var years  = <?php echo json_encode($years); ?>;
                var type = "{{$type}}";
                var value = "{{$value}}";
                var currency = <?php echo json_encode($pRate); ?>;
                var region = "{{$region}}";

				ajaxSetup();

				@for($n = 1; $n <= $size; $n++)
					$(document).on('click', "#"+type+{{$n}}, function(){

                        var name = $(this).text();

                        if ($("#sub"+type+{{$n}}).css("display") == "none") {

                            $.ajax({
                                url: "/ajaxRanking/subRanking",
                                method: "POST",
                                data: {name, months, brands, years, type, value, currency, region},
                                success: function(output){
                                    $("#sub"+type+{{$n}}).html(output);
                                    $("#sub"+type+{{$n}}).css("display", "");
                                },
                                error: function(xhr, ajaxOptions,thrownError){
                                    alert(xhr.status+" "+thrownError);
                                }
                            });
                        }else{
                        	$("#sub"+type+{{$n}}).html(" ");
                            $("#sub"+type+{{$n}}).css("display", "none");
                        }
                    });
                @endfor
			@endif
		});
	</script>

@endsection

