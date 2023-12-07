@extends('layouts.mirror')
@section('title', 'Pipelines')
@section('head')

    <?php include(resource_path('views/auth.php'));?>
    <style media="screen">
	          *,
	    *:before,
	    *:after{
	        padding: 0;
	        margin: 0;
	        box-sizing: border-box;
	    }

	    .popup{
	        background-color: #ffffff;
	        width: 420px;
	        padding: 30px 40px;
	        position: absolute;
	        transform: translate(-50%,-50%);
	        left: 50%;
	        top: 50%;
	        border-radius: 8px;
	        font-family: "Poppins",sans-serif;
	        display: none; 
	        text-align: center;
	    }
	    .popup button{
	        display: block;
	        margin:  0 0 20px auto;
	        background-color: transparent;
	        font-size: 30px;
	        color: #ffffff;
	        border-radius: 100%;
	        width: 40px;
	        height: 40px;
	        border: none;
	        outline: none;
	        cursor: pointer;
	    }
	    .popup h2{
	      margin-top: -20px;
	    }
	    .popup p{
	        font-size: 14px;
	        text-align: justify;
	        margin: 20px 0;
	        line-height: 25px;
	    }
	    a{
	        display: block;
	        width: 150px;
	        position: relative;
	        margin: 10px auto;
	        text-align: center;
	        border-radius: 20px;
	        color: #ffffff;
	        text-decoration: none;
	        padding: 8px 0;
	    }
	</style>
@endsection
@section('content')

	<div class="container-fluid">
		<div class="row">
			<div class="col">
				<form method="POST" action="{{route('pipelinePost')}}" runat="server" onsubmit="ShowLoading()">
					@csrf
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
     	<div class="popup" style="color:black; font-size: 14px;border-style:solid; border-color:black; border-width: 1px 1px 1px 1px;">                    
            <h2>Warning</h2>
            <p>
                 pay attention to the information entered in the forecast parts
            </p>
           <button style="border-style:solid; border-color:black; border-width: 1px 1px 1px 1px; color: black;" id="close">&times;</button>
        </div>

		<div class="row justify-content-end mt-2">
			<div class="col-sm-4" style="color: #0070c0; font-size:24px">
                <span style="float: right; margin-right: 2.5%;">Pipeline</span>
            </div>
		</div>

	</div>

<script type="text/javascript">
    window.addEventListener("load", function(){
        setTimeout(
            function open(event){
                document.querySelector(".popup").style.display = "block";
            },
            2000 
        )
    });


    document.querySelector("#close").addEventListener("click", function(){
        document.querySelector(".popup").style.display = "none";
});
</script>

@endsection