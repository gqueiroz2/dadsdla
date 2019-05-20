@extends('layouts.mirror')

@section('title', '@')

@section('head')
	<script src="/js/views/dataManagement_userGet.js"></script>
    <?php include(resource_path('views/auth.php')); ?>
@endsection

@section('content')

@if($userLevel == 'SU')

	<div class="container-fluid">
		<div class="row justify-content-center">
			<div class="col-sm-6">
				<div class="card" style="margin-bottom: 5%;">
					<div class="card-header">
						<center><h4> Data Management - <b> User </b> </h4></center>
					</div>
					<div class="card-body">
						<div class="container-fluid">
							<div class="row justify-content-center">
								<div class="col">
									<h5> Edit / Management User </h5>
								</div>
							</div>
							
							@if($user)
								{{-- $render->userEdit($user) --}}
								<div class='row mt-1'>
									<div class="col">
										<form method="POST" action="{{route('dataManagementUserEditFilter')}}">
											@csrf
											<input type="submit" class="btn btn-primary mt-2" value="Edit/Delete" style="width: 100%;">
										</form>
									</div>
								</div>
							@else
								<div class="alert alert-warning">
  									There is no <strong> User </strong> to manage yet.
								</div>
							@endif
							<hr>
							<div class="row justify-content-center">
								<div class="col">
									<h5> Add a User </h5>
								</div>
							</div>
							<div class="row justify-content-center">
								<div class="col">
									@if(session('errorAddUser'))
										<div class="alert alert-danger">
  											{{ session('errorAddUser') }}
										</div>
									@endif

									@if(session('addUser'))
										<div class="alert alert-info">
  											{{ session('addUser') }}
										</div>
									@endif
								</div>
							</div>
							<div id="vlau"></div>
							<form method="POST" action="{{ route('dataManagementUserAdd') }}">
							@csrf

								<div class="row justify-content-center">
									<div class="col">
										<label> Name: </label>
										<input type="text" name="name" class="form-control">
									</div>								

									<div class="col">
										<label> E-mail: </label>
										<input type="text" name="email" class="form-control">
									</div>	
								</div>

								<div class="row justify-content-center">
									<div class="col">
										<label> Password: </label>
										<input type="password" name="password" class="form-control">
									</div>	

									<div class="col">
										<label> Status: </label>
										<select class="form-control" name="status">
											<option value=""> Select </option>
											<option value="1"> Enabled </option>
											<option value="0"> Disabled </option>
										</select>
									</div>							
								</div>

								<div class="row justify-content-center">
									<div class="col">
										<label> Region: </label>
										<select class="form-control" name="region" id="user_region">
											@if($region)
												<option value=""> Select a Region </option>
												@for($r = 0; $r < sizeof($region);$r++)
													<option value="{{ $region[$r]["id"] }}"> 
														{{ $region[$r]["name"] }} 
													</option>
												@endfor		
											@else
												<option value=""> There is no regions created yet. </option>
											@endif
										</select>
									</div>

									<div class="col">
										<label> User Type: </label>
										<select class="form-control" name="userType">
											@if($userType)
												<option value=""> Select a Level </option>
												@for($u = 0; $u < sizeof($userType);$u++)
													<option value="{{ $userType[$u]["id"] }}"> 
														{{ $userType[$u]["level"] }} 
													</option>
												@endfor		
											@else
												<option value=""> There is no User Type created yet. </option>
											@endif
										</select>
									</div>									
								</div>

								<div class="row justify-content-center">
									<div class="col">
										<label> Sub-Level Bool: </label>
										<select class="form-control" name="subLevelBool" id="user_sub_level_bool" readonly="true" >
											<option value=""> Select a Region </option>
											<option value="1"> Yes </option>
											<option value="0"> No </option>
										</select>
									</div>

									<div class="col">
										<label> Sub-Level Group: </label>
										<select class="form-control" name="subLevelGroup" id="user_sub_level_group" readonly="true">
											<option value=""> Select Region and Sub-Level Bool </option>
										</select>
									</div>

									
								</div>
								<div class="row justify-content-end mt-1">
									<div class="col col-sm-3">
										<input type="submit" class="btn btn-primary" value="Add User" style="width:100%;">
									</div>
								</div>
							</form>

							<hr><br><hr>

							<div class="row justify-content-center">
								<div class="col">
									<h5> Edit / Management User Type </h5>
								</div>
							</div>
							
							@if($userType)
								{{-- $render->userTypeEdit($userType) --}}
								<div class='row mt-1'>
									<div class="col">
										<form method="GET" action="{{route('dataManagementUserTypeEditGet')}}">
											<input type="submit" class="btn btn-primary mt-2" value="Edit/Delete" style="width: 100%;">
										</form>
									</div>
								</div>
							@else
								<div class="alert alert-warning">
  									There is no <strong> User </strong> to manage yet.
								</div>
							@endif
							<hr>
							<div class="row justify-content-center">
								<div class="col">
									<h5> Add a User Type </h5>
								</div>
							</div>
							<div class="row justify-content-center">
								<div class="col">
									@if(session('errorUserType'))
										<div class="alert alert-danger">
  											{{ session('error') }}
										</div>
									@endif

									@if(session('addUserType'))
										<div class="alert alert-info">
  											{{ session('response') }}
										</div>
									@endif
								</div>
							</div>
							<form method="POST" action="{{ route('dataManagementUserTypeAdd') }}">
							@csrf
								
								<div class="row justify-content-center">
									<div class="col">
										<label> Name: </label>
										<input type="text" name="name" class="form-control">
									</div>								

									<div class="col">
										<label> Level: </label>
										<input type="text" name="level" class="form-control">
									</div>

									
								</div>
								<div class="row justify-content-end mt-1">
									<div class="col col-sm-3">
										<input type="submit" class="btn btn-primary" value="Add User Type" style="width:100%;">
									</div>
								</div>
							</form>

						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
@else
@endif
@endsection
