function ajaxSetup(){
	$.ajaxSetup({
		headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
		type:"POST"
	});
}

function analytics(userName,userRegion,userEmail,date,hour,url,shortUrl,ipV1){
	$.ajax({
		url:"/analytics/base",
		method:"POST",
		data:{userName,userRegion,userEmail,date,hour,url,shortUrl,ipV1},
		success: function(output){
			$('#troll').html(output);
			console.log("Following");
		},
		error: function(xhr, ajaxOptions,thrownError){
			alert(xhr.status+""+thrownError);
		}
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


