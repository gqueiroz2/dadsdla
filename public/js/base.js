function ajaxSetup(){
	$.ajaxSetup({
		headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
		type:"POST"
	});
}

function analytics(userName,userRegion,userEmail,date,hour,url,shortUrl,ipV1){
	$.ajaxSetup({
		headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
		type:"POST"
	});

	$.ajax({
		url:"/analytics/base",
		method:"POST",
		data:{userName,userRegion,userEmail,date,hour,url,shortUrl,ipV1},
		success: function(output){
			$('#troll').html(output);/*
			console.log("Following");
			console.log(date);
			console.log(hour);*/
		},
		error: function(xhr, ajaxOptions,thrownError){
			alert(xhr.status+""+thrownError);
		}
	});
}

function getSubLevelGroupByRegion(regionID){
	$.ajaxSetup({
		headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
		type:"POST"
	});
	
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


