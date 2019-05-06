$(document).ready(function(){
	$('#region').change(function(){
		var regionID = $(this).val();
		ajaxSetup();
		if (regionID != "") {
			$.ajax({
    			url:"/ajax/adsales/currencyByRegion",
    			method:"POST",
    			data:{regionID},
          		success: function(output){
            		$('#currency').html(output);
          		},
          		error: function(xhr, ajaxOptions,thrownError){
            		alert(xhr.status+" "+thrownError);
      			}
      		});
			var year = $('#year').val();
			if(year != ""){
				$.ajax({
        			url:"/ajax/adsales/thirdPosByRegion",
        			method:"POST",
        			data:{regionID, year},
              		success: function(output){
                		$('#thirdPos').html(output);
              		},
              		error: function(xhr, ajaxOptions,thrownError){
                		alert(xhr.status+" "+thrownError);
          			}
          		});
          		$.ajax({
        			url:"/ajax/adsales/secondPosByRegion",
        			method:"POST",
        			data:{year},
              		success: function(output){
                		$('#secondPos').html(output);
              		},
              		error: function(xhr, ajaxOptions,thrownError){
                		alert(xhr.status+" "+thrownError);
          			}
          		});
			}			
		}
	});

	$('#year').click(function(){
		var year = $(this).val();
		if (year != "") {
			var regionID = $('#region').val();
			ajaxSetup();
			if (regionID != "") {				
				$.ajax({
        			url:"/ajax/adsales/thirdPosByRegion",
        			method:"POST",
        			data:{regionID, year},
              		success: function(output){
                		$('#thirdPos').html(output);
              		},
              		error: function(xhr, ajaxOptions,thrownError){
                		alert(xhr.status+" "+thrownError);
          			}
          		});
          		$.ajax({
        			url:"/ajax/adsales/secondPosByRegion",
        			method:"POST",
        			data:{year},
              		success: function(output){
                		$('#secondPos').html(output);
              		},
              		error: function(xhr, ajaxOptions,thrownError){
                		alert(xhr.status+" "+thrownError);
          			}
          		});
			}
		}
	});
});