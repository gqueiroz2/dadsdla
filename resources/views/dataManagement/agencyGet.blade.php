@extends('layouts.mirror')

@section('title', '@')

@section('content')

	<div class="container-fluid">
		<div class="row justify-content-center">
<<<<<<< HEAD
			<div class="col col-sm-6">
				<div class="card">
					<div class="card-header">
						<center><h4>Data Management - <b> Agency</b></h4></center>
					</div>
					<div class="card-body">
						<div class="container-fluid">
							<div>
								<div class="row justify-content-center">
									<div class="col">
										<h5>Edit / Management Agencys</h5>
									</div>
								</div>
								<div class="alert alert-warning">
									There is no <strong> Agency </strong> to manage yet.
								</div>
								<div class="row justify-content-center">
									<div class="col-lg">
										<form style="width: 100%" enctype="multipart/form-data" method="post" action="{{ route('dataManagementAddAgency') }}">
											@csrf
											<div class="input-group-lg">
											  <div class="custom-file">
											    <input type="file" class="input-control-file" id="planilha">
											    <label class="custom-file-label" for="planilha" id="file">Choose file</label>
											  </div>
											  <div class="col">&nbsp;</div>
											  <button style="width: 100%" type="submit" formmethod="post" class="btn btn-primary" id="">Upload</button>
											</div>
										</form>
									</div>
=======
			<div class="col-sm-6">
				<div class="card">
					<div class="card-header">
						<center><h4> Data Management - <b> Agency </b> </h4></center>
					</div>
					<div class="card-body">
						<div class="container-fluid">
							<div class="row justify-content-center">
								<div class="col">
									<h5> Add a Excel File </h5>
								</div>
							</div>

							<div class="row justify-content-center">
								<div class="col">
									@if(session('error'))
										<div class="alert alert-danger">
  											{{ session('error') }}
										</div>
									@endif

									@if(session('response'))
										<div class="alert alert-info">
  											{{ session('response') }}
										</div>
									@endif
								</div>
							</div>
							<form action="{{ route('fileUploadAgency') }}" method="POST" enctype="multipart/form-data">
							@csrf
							 	<div class="row justify-content-center">          
							 		<div class="col">		
										<div class="form-group">
								        	<label for="exampleInputFile">File Upload</label>
								        	<div class="custom-file">                
												<input type="file" class="input-control-file" id="file" name="file"0>                
												<label class="custom-file-label" for="file">Choose file</label>              
											</div>  
								    	</div>
								    </div>
								</div>
								<div class="row justify-content-end">          
							 		<div class="col col-sm-6">		
								    	<button type="submit" class="btn btn-primary" style="width: 100%;">Submit</button>
									</form>
>>>>>>> f10e9e681cc259cddb31ad0aa752f7190c3cad5a
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

<<<<<<< HEAD
	<div id="teste"></div>

	<script type="text/javascript">
		$(document).ready(function(){
			$('#planilha').change(function(){
				var planilha = $(this).val().split('\\').pop();
				$('#file').html(planilha);
			});
		});
	</script>

@endsection
=======
@endsection
>>>>>>> f10e9e681cc259cddb31ad0aa752f7190c3cad5a
