@extends('layouts.mirror')

@section('title', '@')

@section('head')
	<style type="text/css">		
		.button:focus{    
    		color:white;
		}
	</style>
    <?php include(resource_path('views/auth.php')); ?>
@endsection
@section('content')
@if($userLevel == 'SU')
	<div class="container-fluid">
		<div class="row justify-content-center">
			<div class="col-sm-6">
				<div class="card mt-5">
					<div class="card-header">
						<center>
							<span style="font-size: 16px; font-weight: bold;"> Sales Management  </span>	
						</center>
					</div>
					<div class="card-body">
						<div class="container-fluid">
							<div class="row mt-2 justify-content-center">
								<div class="col">
									<form method="POST" action="{{ route('salesManagementCustomReportV1Post') }}" runat="server"  onsubmit="ShowLoading()">
										@csrf

										<div class="col-sm">
											<label> &nbsp; </label>
											<input type="submit" value="Generate" class="btn btn-primary" style="width: 100%;">		
										</div>
									</form>
									<div class="row mt-2"></div>
									<div class='col'>
										<button class="btn btn-primary" style="width: 100%;" id="crm"> 
											<a style="color: white">
												Custom Report V1
											</a>
										</button>
									</div>
								</div>
							</div>						
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
		
@else
@endif

<script type="text/javascript">
	$(document).ready(function(){

	    ajaxSetup();

	    $('#crm').click(function(event){
	        
	        var div = document.createElement('div');
	        var img = document.createElement('img');
	        img.src = '/loading_excel.gif';
	        div.innerHTML ="Generating File...</br>";
	        div.style.cssText = 'position: absolute; left: 0px; top:0px;  margin:0px;        width: 100%;        height: 100%;        display:block;        z-index: 99999;        opacity: 0.9;        -moz-opacity: 0;        filter: alpha(opacity = 45);        background: white;    background-repeat: no-repeat;        background-position:50% 50%;        text-align: center;        overflow: hidden;   font-size:30px;     font-weight: bold;        color: black;        padding-top: 20%';
	        div.appendChild(img);
	        document.body.appendChild(div);

	        var typeExport = $("#crm").val();
	            
            $.ajax({
                xhrFields: {
                    responseType: 'blob',
                },
                url: "/generate/excel/salesManagement/customReport",
                type: "POST",
                data: {typeExport},
                /*success: function(output){
                    $("#vlau").html(output);
                },*/
                success: function(result,status,xhr){
                    var disposition = xhr.getResponseHeader('content-disposition');
                    var matches = /"([^"]*)"/.exec(disposition);
                    var filename = (matches != null && matches[1] ? matches[1] : title);

                    //download
                    var blob = new Blob([result], {
                        type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
                    });
                    var link = document.createElement('a');
                    link.href = window.URL.createObjectURL(blob);
                    link.download = filename;

                    document.body.appendChild(link);

                    link.click();
                    document.body.removeChild(link);
                    document.body.removeChild(div);
                },
                error: function(xhr, ajaxOptions, thrownError){
                    document.body.removeChild(div);
                    alert(xhr.status+" "+thrownError);
                }
	       });                    
	    });
	});
</script>

@endsection
