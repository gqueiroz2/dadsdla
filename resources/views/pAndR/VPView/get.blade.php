@extends('layouts.mirror')
@section('title', 'Manager View')
@section('head')	
    <?php include(resource_path('views/auth.php')); ?>
    <script src="/js/pandr.js"></script>
@endsection
@section('content')
	

	@if($userLevel == 'SU' || $userLevel == 'L3' )
		<form method="POST" action="{{ route('VPPost') }}" runat="server"  onsubmit="ShowLoading()">
			@csrf
			<div class="container-fluid">		
				<div class="row">
				 	<div class="col">
                        <label class='labelLeft'><span class="bold">Manager:</span></label>
                        @if($errors->has('manager'))
                            <label style="color: red;">* Required</label>
                        @endif
                            {{$render->manager($user)}}
                    </div>
                    <div class="col">
	                    <label class='labelLeft'><span class="bold">Month:</span></label>
	                    @if($errors->has('year'))
	                        <label style="color: red;">* Required</label>
	                    @endif
	                    {{$render->month($months)}}
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
							@if(session('Success'))
								<div class="alert alert-info">
									{{session('Success')}}
								</div>
							@endif

							@if(session('Error'))
								<div class="alert alert-danger">
									{{session('Error')}}
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
					Manager View
				</div>
			</div>
		</div>
	@endif

@endsection