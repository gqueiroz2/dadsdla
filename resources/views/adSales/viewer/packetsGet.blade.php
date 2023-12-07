@extends('layouts.mirror')
@section('title', 'Closed Packets')
@section('head')

    <?php include(resource_path('views/auth.php'));?>

@endsection
@section('content')

	<div class="container-fluid">
		<div class="row">
			<div class="col">
				<form method="POST" action="{{route('packetsPost')}}" runat="server" onsubmit="ShowLoading()">
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

		<div class="row justify-content-end mt-2">
			<div class="col-sm-4" style="color: #0070c0; font-size:24px">
                <span style="float: right; margin-right: 2.5%;">Packets</span>
            </div>
		</div>

	</div>


@endsection