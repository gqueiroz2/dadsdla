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
        		$('#salesRep').html(output);                		
        	},
        	error: function(xhr, ajaxOptions,thrownError){
        		alert(xhr.status+" "+thrownError);
    			}
    		});
      }
    });
  });

