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
$(document).ready(function(){      
	$('#region').change(function(){

		var regionID = $(this).val();                		

		if(regionID != ""){        			
			ajaxSetup();
  			
			$.ajax({
  			url:"/ajaxResults/salesRepGroupByRegion",
  			method:"POST",
  			data:{regionID},
        		success: function(output){
          		$('#salesRepGroup').html(output);                		
        		},
        		error: function(xhr, ajaxOptions,thrownError){
          		alert(xhr.status+" "+thrownError);
    		    }
    	});

      $.ajax({
        url:"/ajaxResults/currencyByRegion",
        method:"POST",
        data:{regionID},
            success: function(output){
              $('#currency').html(output);                   
            },
            error: function(xhr, ajaxOptions,thrownError){
              alert(xhr.status+" "+thrownError);
            }
      });

    }
  });

	$('#salesRepGroup').change(function(){
		var regionID = $("#region").val();                		
		var salesRepGroupID = $(this).val();   

		if(regionID != ""){        			

			ajaxSetup();
  			$.ajax({
    			url:"/ajaxResults/salesRepBySalesRepGroup",
    			method:"POST",
    			data:{regionID,salesRepGroupID},
          		success: function(output){
            		//$('#vlau').html(output);                
            		$('#salesRep').html(output);                		
          		},
          		error: function(xhr, ajaxOptions,thrownError){
            		alert(xhr.status+" "+thrownError);
      			}
      		});
      	}
    });

});

