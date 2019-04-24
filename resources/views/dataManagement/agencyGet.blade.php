@extends('layouts.mirror')

@section('title', '@')

@section('content')

	<div class="container-fluid">
		<div class="row justify-content-center">
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
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

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