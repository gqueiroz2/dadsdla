@extends('layouts.mirror')
@section('title', 'VP Report')
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
    </style>
@endsection
@section('content')
	<div class="container-fluid">
		<div class="row justify-content-end mt-2">
			<div class="col-3" style="color: #0070c0;font-size: 25px;">
				VP Report
			</div>
		</div>
	</div>

	<form method="POST" action="{{ route('VPPost') }}" runat="server"  onsubmit="ShowLoading()">
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
					<label class='labelLeft'><span class="bold">Currency:</span></label>
					@if($errors->has('currency'))
						<label style="color: red;">* Required</label>
					@endif
					{{$render->currency($currency)}}
				</div>	
				<div class="col">
					<label class='labelLeft'> &nbsp; </label>
					<input style="width: 100%;" type="submit" value="Generate" class="btn btn-primary">		
				</div>			
			</div>
		</div>
	</form>
	<br>
	<div class="container-fluid">
		<div class="row">
			<div class="col" style="width: 100%; padding-right: 2%;">
				<center>
					{{$render->VP1($forRender)}}
				</center>
			</div>
		</div>
	</div>

	<script>
		$(document).ready(function(){
			@for($c=0;$c< sizeof($client);$c++)
				$("#child-"+{{$c}}).css("height",$("#parent-"+{{$c}}).css("height"));
				
				$("#clientRF-Fy-"+{{$c}}).change(function(){
					if ($(this).val() == "") {
						$(this).val(0);
					}

					var temp = handleNumber($(this).val());

					$(this).val(Comma(temp));

					var temp2 = parseFloat(0);

					@for($c2=0;$c2<100;$c2++)
						temp2 += handleNumber($("#clientRF-Fy-"+{{$c2}}).val());
					@endfor

					temp2 = Comma(temp2);

					$("#RF-Total-Fy").val(temp2);

				});
				$("#clientRF-Cm-"+{{$c}}).change(function(){
					if ($(this).val() == "") {
						$(this).val(0);
					}

					var temp = handleNumber($(this).val());

					$(this).val(Comma(temp));

					var temp2 = parseFloat(0);
					
					@for($c2=0;$c2<100;$c2++)
						temp2 += handleNumber($("#clientRF-Cm-"+{{$c2}}).val());
					@endfor

					temp2 = Comma(temp2);

					$("#RF-Total-Cm").val(temp2);
				});
			@endfor
		});

		function handleNumber(number){

			for (var i = 0; i < number.length/3; i++) {
				number = number.replace(",","");
			}

			number = parseFloat(number);
			
			return number;
		}

	  	function Comma(Num) { //function to add commas to textboxes
	        Num += '';
	        Num = Num.replace(',', ''); Num = Num.replace(',', ''); Num = Num.replace(',', '');
	        Num = Num.replace(',', ''); Num = Num.replace(',', ''); Num = Num.replace(',', '');
	        x = Num.split('.');
	        x1 = x[0];
	        x2 = x.length > 1 ? '.' + x[1] : '';
	        var rgx = /(\d+)(\d{3})/;
	        while (rgx.test(x1))
	            x1 = x1.replace(rgx, '$1' + ',' + '$2');
	        return x1 + x2;
	    }

	    $('.linked').scroll(function(){

    		$('.linked').scrollLeft($(this).scrollLeft());
		});

		$('.linked2').scroll(function(){

    		$('.linked2').scrollTop($(this).scrollTop());
		});

	</script>


@endsection