

function ajaxSetup(){
	$.ajaxSetup({
		headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
		type:"POST"
	});
}

function getSubLevelGroupByRegion(regionID){
	$.ajax({
	url:"/dataManagement/ajax/subLevelGroupByRegion",
	method:"POST",
	data:{regionID},
		success: function(output){
		$('#user_sub_level_group').html(output);                			                		
		},
		error: function(xhr, ajaxOptions,thrownError){
		alert(xhr.status+""+thrownError);
		}
	});
}