@extends('layouts.mirror')
@section('title', '@')
@section('head')
    <?php include(resource_path('views/auth.php')); ?>
@endsection
@section('content')

@if($userLevel == 'SU')
	<div class="container-fluid">
		<div class="row justify-content-center">
			<div class="col-sm-6">
				<div class="card">
					<div class="card-header">
						<center><h4> Data Management - <b> Chain CMAPS </b> </h4></center>
					</div>
					<div class="card-body">
						<div class="container-fluid">
							<div class='col'>
				                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalExemplo" style="width: 100%">
				                  Update Daily
				                </button>
				                <td style="width: 7% !important; background-color: white;"> &nbsp; </td>
				                 <button type="button" class="btn btn-primary" data-toggle="modalPipeline" data-target="#modalPipeline" style="width: 100%">
				                  Update Pipeline
				                </button>
				            </div>
				            <!-- Modal -->
				            <div class="modal fade" id="modalExemplo" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
				              <div class="modal-dialog" role="document">
				                <div class="modal-content">
				                  	<div class="modal-header">
				                  		<b> Add a Excel File </b>
				                  	</div>
									
				                  	<div class="modal-body">
				                  		<div class="row justify-content-center">

											<div class="col">
												<span style="font-size: 16px;">
												
												</span>
												@if($errors->has('file'))
													<span style="color: red;">* Required</span>
												@endif									
											</div>
										</div>
								
											<form action="{{ route('dailyResults') }}" runat="server"  onsubmit="ShowLoading()" method="POST" enctype="multipart/form-data">
											@csrf
											 	<div class="row justify-content-center">          
											 		<div class="col">		
														<div class="form-group">
															<input type="file" name="file">               

												    	</div>
												    </div>
												</div>

												<div class="row justify-content-center">
													<div class="col">
														@if(session('dailyError'))
															<div class="alert alert-danger">
					  											{{ session('dailyError') }}
															</div>
														@endif

														@if(session('dailyComplete'))
															<div class="alert alert-info">
					  											{{ session('dailyComplete') }}
															</div>
														@endif
													</div>
												</div>
												


												<div class="row justify-content-center">          
											 		<div class="col">		
														<div class="form-group">
															<label><b> Table: </b></label> 
															@if($errors->has('dailyResults'))
																<label style="color: red;">* Required</label>
															@endif
															{{$rC->dailyResults('pipeline')}}					
															
														</div>
													</div>
												</div>

												<div class="row justify-content-end">          
											 		<div class="col">		
												    	<button type="submit" class="btn btn-primary" style="width: 100%;">Submit</button>
													</div>
												</div>
											</form>
				               
					                    	<div class="modal-footer">
						                   		<button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
						                  </div>
						                </div>
					               </div>
				              	</div>
				            </div>

				            <!-- Modal -->
				            <div class="modal fade" id="modalPipeline" tabindex="-1" role="dialog" aria-labelledby="pipelineModalLabel" aria-hidden="true">
				              <div class="modal-dialog" role="document">
				                <div class="modal-content">
				                  	<div class="modal-header">
				                  		<b> Add a Excel File </b>
				                  	</div>
									
				                  	<div class="modal-body">
				                  		<div class="row justify-content-center">

											<div class="col">
												<span style="font-size: 16px;">
												
												</span>
												@if($errors->has('file'))
													<span style="color: red;">* Required</span>
												@endif									
											</div>
										</div>
								
											<form action="{{ route('dailyResults') }}" runat="server"  onsubmit="ShowLoading()" method="POST" enctype="multipart/form-data">
											@csrf
											 	<div class="row justify-content-center">          
											 		<div class="col">		
														<div class="form-group">
															<input type="file" name="file">               

												    	</div>
												    </div>
												</div>

												<div class="row justify-content-center">
													<div class="col">
														@if(session('dailyError'))
															<div class="alert alert-danger">
					  											{{ session('dailyError') }}
															</div>
														@endif

														@if(session('dailyComplete'))
															<div class="alert alert-info">
					  											{{ session('dailyComplete') }}
															</div>
														@endif
													</div>
												</div>
												


												<div class="row justify-content-center">          
											 		<div class="col">		
														<div class="form-group">
															<label><b> Table: </b></label> 
															@if($errors->has('dailyResults'))
																<label style="color: red;">* Required</label>
															@endif
															{{$rC->dailyResults('pipeline')}}					
															
														</div>
													</div>
												</div>

												<div class="row justify-content-end">          
											 		<div class="col">		
												    	<button type="submit" class="btn btn-primary" style="width: 100%;">Submit</button>
													</div>
												</div>
											</form>
				               
					                    	<div class="modal-footer">
						                   		<button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
						                  </div>
						                </div>
					               </div>
				              	</div>
				            </div>

				            <br><hr>
							
							<div class="row">
								<div class="col">
									<center><span style="font-size: 18px;"> TRUNCATE </span></center>
								</div>
							</div>

							<form action="{{ route('truncate') }}" runat="server"  onsubmit="ShowLoading()" method="POST" enctype="multipart/form-data">
							@csrf
								<div class="row justify-content-center">
									<div class="col">
										@if(session('truncateChainError'))
											<div class="alert alert-danger">
	  											{{ session('truncateChainError') }}
											</div>
										@endif

										@if(session('truncateChainComplete'))
											<div class="alert alert-info">
	  											{{ session('truncateChainComplete') }}
											</div>
										@endif
									</div>
								</div>
								
								<div class="row justify-content-center">          
							 		<div class="col">		
										<div class="form-group">
											<label><b> Table: </b></label> 
											@if($errors->has('tableTruncate'))
												<label style="color: red;">* Required</label>
											@endif
											{{$rC->tableCmaps("tableTruncate")}}					
										</div>
									</div>
								</div>

								<div class="row justify-content-end">          
							 		<div class="col">		
								    	<button type="submit" class="btn btn-primary" style="width: 100%;">Submit</button>
									</div>
								</div>
							</form>

							<br><hr>

							<div class="row">
								<div class="col">
									<center><span style="font-size: 18px;"> FIRST CHAIN </span></center>
								</div>
							</div>

							<div class="row justify-content-center">
								<div class="col">
									<span style="font-size: 16px;">
										<b> Add a Excel File </b>
									</span>
									@if($errors->has('file'))
										<span style="color: red;">* Required</span>
									@endif									
								</div>
							</div>

							
							<form action="{{ route('cmapsFirstC') }}" runat="server"  onsubmit="ShowLoading()" method="POST" enctype="multipart/form-data">
							@csrf
							 	<div class="row justify-content-center">          
							 		<div class="col">		
										<div class="form-group">
											<input type="file" name="file">               

								    	</div>
								    </div>
								</div>

								<div class="row justify-content-center">
									<div class="col">
										@if(session('firstChainError'))
											<div class="alert alert-danger">
	  											{{ session('firstChainError') }}
											</div>
										@endif

										@if(session('firstChainComplete'))
											<div class="alert alert-info">
	  											{{ session('firstChainComplete') }}
											</div>
										@endif
									</div>
								</div>
								


								<div class="row justify-content-center">          
							 		<div class="col">		
										<div class="form-group">
											<label><b> Table: </b></label> 
											@if($errors->has('tableFirstChain'))
												<label style="color: red;">* Required</label>
											@endif
											{{$rC->tableCmaps('tableFirstChain')}}					
											
										</div>
									</div>
								</div>

								<div class="row justify-content-end">          
							 		<div class="col">		
								    	<button type="submit" class="btn btn-primary" style="width: 100%;">Submit</button>
									</div>
								</div>
							</form>
							<br><hr>

							<div class="row">
								<div class="col">
									<center><span style="font-size: 18px;"> CHECK NEW ELEMENTS </span></center>
								</div>
							</div>

							<div class="row justify-content-end mt-2">          
						 		<div class="col">		
							    	<button id="PedingStuffByRegions" class="btn btn-primary" style="width: 100%;"> Check New Regions </button>
								</div>
							</div>
							<div id="vlau"></div>
							<form action="{{ route('checkElementsPost') }}" method="POST" runat="server"  onsubmit="ShowLoading()">
							@csrf
								<div class="row justify-content-center">          
							 		<div class="col">		
										<div class="form-group">
											<input type="hidden" name="table" value="ytd">                
								    	</div>
								    </div>
								</div>

								<div class="row justify-content-center">
									<div class="col">
										@if(session('checkElementsError'))
											<div class="alert alert-danger">
													{{ session('checkElementsError') }}
											</div>
										@endif

										@if(session('checkElementsComplete'))
											<div class="alert alert-info">
													{{ session('checkElementsComplete') }}
											</div>
										@endif
									</div>
								</div>

								<div class="row justify-content-center">          
							 		<div class="col">		
										<label><b> Region: </b></label> 
											@if($errors->has('region'))
												<label style="color: red;">* Required</label>
											@endif
										<div class="form-group">
											{{$rC->regionWI()}}					
										</div>
									</div>
								</div>

								<div class="row justify-content-center">          
							 		<div class="col">		
										<label><b> Table: </b></label> 
											@if($errors->has('table'))
												<label style="color: red;">* Required</label>
											@endif
										<div class="form-group">
											{{$rC->reportCmaps()}}					
										</div>
									</div>
								</div>

								<div class="row justify-content-end">          
							 		<div class="col">		
								    	<button type="submit" class="btn btn-primary" style="width: 100%;">Submit</button>
									</div>
								</div>
							</form>

							<br><hr>

							<div class="row">
								<div class="col">
									<center><span style="font-size: 18px;"> SECOND CHAIN </span></center>
								</div>
							</div>
							<form action="{{ route('cmapsSecondC') }}" method="POST" runat="server"  onsubmit="ShowLoading()">
							@csrf
								<div class="row justify-content-center">          
							 		<div class="col">		
										<div class="form-group">
											<input type="hidden" name="table" value="ytd">
								    	</div>
								    </div>
								</div>

								<div class="row justify-content-center">
									<div class="col">
										@if(session('secondChainError'))
											<div class="alert alert-danger">
													{{ session('secondChainError') }}
											</div>
										@endif

										@if(session('secondChainComplete'))
											<div class="alert alert-info">
													{{ session('secondChainComplete') }}
											</div>
										@endif
									</div>
								</div>

								<div class="row justify-content-center">          
							 		<div class="col">		
										<div class="form-group">
											<label><b> Table: </b></label> 
											@if($errors->has('tableSecondChain'))
												<label style="color: red;">* Required</label>
											@endif
											{{$rC->tableCmaps('tableSecondChain')}}					
										</div>
									</div>
								</div>

								<div class="row justify-content-center">          
							 		<div class="col">		
										<div class="form-group">
											<label><b> Year: </b></label> 
											@if($errors->has('year'))
												<label style="color: red;">* Required</label>
											@endif
											{{$rC->year()}}					
										</div>
									</div>
								</div>

								<div class="row justify-content-end">          
							 		<div class="col">		
								    	<button type="submit" class="btn btn-primary" style="width: 100%;">Submit</button>
									</div>
								</div>
							</form>

							<br><hr>
							<div class="row">
								<div class="col">
									<center><span style="font-size: 18px;"> THIRD CHAIN </span></center>
								</div>
							</div>
							<form action="{{ route('thirdChainCmaps') }}" method="POST" runat="server"  onsubmit="ShowLoading()">
							@csrf

								<div class="row justify-content-center">
									<div class="col">
										@if(session('thirdChainError'))
											<div class="alert alert-danger">
	  											{{ session('thirdChainError') }}
											</div>
										@endif

										@if(session('thirdChainComplete'))
											<div class="alert alert-info">
	  											{{ session('thirdChainComplete') }}
											</div>
										@endif
									</div>
								</div>

								<div class="row justify-content-center">          
							 		<div class="col">		
										<div class="form-group">
											<input type="hidden" name="table" value="ytd">                
								    	</div>
								    </div>
								</div>

								<div class="row justify-content-center">          
							 		<div class="col">		
										<div class="form-group">
											<label><b> Table: </b></label> 
											@if($errors->has('tableThirdChain'))
												<label style="color: red;">* Required</label>
											@endif
											{{$rC->tableCmaps('tableThirdChain')}}					
										</div>
									</div>
								</div>

								<div class="row justify-content-center">          
							 		<div class="col">		
										<div class="form-group">
											<label><b> Year: </b></label> 
											@if($errors->has('year'))
												<label style="color: red;">* Required</label>
											@endif
											{{$rC->year()}}					
										</div>
									</div>
								</div>

								<div class="row justify-content-end">          
							 		<div class="col">		
								    	<button type="submit" class="btn btn-primary" style="width: 100%;">Submit</button>
									</div>
								</div>
							</form>
							<br><hr>
							<div class="row">
								<div class="col">
									<center><span style="font-size: 18px;"> THIRD TO DLA </span></center>
								</div>
							</div>
							<form action="{{ route('cmapsToDLA') }}" method="POST" runat="server"  onsubmit="ShowLoading()">
							@csrf
								<div class="row justify-content-center">
									<div class="col">
										@if(session('lastChainError'))
											<div class="alert alert-danger">
	  											{{ session('lastChainError') }}
											</div>
										@endif

										@if(session('lastChainComplete'))
											<div class="alert alert-info">
	  											{{ session('lastChainComplete') }}
											</div>
										@endif
									</div>
								</div>						

								<div class="row justify-content-center">          
							 		<div class="col">		
										<div class="form-group">
											<label><b> Table: </b></label> 
											@if($errors->has('tableToDLAChain'))
												<label style="color: red;">* Required</label>
											@endif
											{{$rC->tableCmaps('tableToDLAChain')}}					
										</div>
									</div>
								</div>

								<div class="row justify-content-center">          
							 		<div class="col">		
										<div class="form-group">
											<label><b> Year(s): </b></label> 
											@if($errors->has('year'))
												<label style="color: red;">* Required</label>
											@endif
											{{$rC->yearMultiple()}}					
										</div>
									</div>
								</div>

								<div class="row justify-content-center">          
							 		<div class="col">		
										<div class="form-group">
											<input type="hidden" name="table" value="ytd">                
								    	</div>
								    </div>
								</div>								

								<div class="row justify-content-end">          
							 		<div class="col">		
								    	<button type="submit" class="btn btn-primary" style="width: 100%;">Submit</button>
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

<script type="text/javascript">

    $(document).ready(function() {

      $(".btn-success").click(function(){ 
          var html = $(".clone").html();
          $(".increment").after(html);
      });

      $("body").on("click",".btn-danger",function(){ 
          $(this).parents(".control-group").remove();
      });

    });

</script>


<script type="text/javascript">
	$(document).ready(function(){
		$('#PedingStuffByRegions').click( function() {

		    var tableToCheck = $('#tableToCheck').val();

		    ajaxSetup();
		    $.ajax({
                url:"/checkElements/PedingStuffByRegions",
                method:"POST",
                data:{tableToCheck},
                  success: function(output){
                    $('#vlau').html(output);
                  },
                  error: function(xhr, ajaxOptions,thrownError){
                    alert(xhr.status+" "+thrownError);
                }
            });
		});
	});
</script>

@endsection
