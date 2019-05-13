  $(document).ready(function(){      
    $('#region').change(function(){
		  var regionID = $(this).val();

		  if(regionID != ""){        			
			  ajaxSetup();
			  
        var option = "<option> Select Sales Group </option>";
        $('#salesRep').html(option);

        $.ajax({
    			url:"/ajaxResults/salesRepGroupByRegion",
  			  method:"POST",
  			  data:{regionID},
      		success: function(output){
        		$('#salesRepGroup').html(output);
            var regionID = $("#region").val();                    
            var salesRepGroupID = $('#salesRepGroup').val();
            var year = $("#year").val();
            var source = $("#source").val();
            if(regionID != "" && salesRepGroupID != ""){              
              ajaxSetup();
              $.ajax({
                url:"/ajaxResults/salesRepBySalesRepGroup",
                method:"POST",
                data:{regionID,salesRepGroupID,year,source},
                success: function(output){
                  $('#salesRep').html(output);                    
                },
                error: function(xhr, ajaxOptions,thrownError){
                  alert(xhr.status+" "+thrownError);
                }
              });
            }else{
              var option = "<option> Select Sales Group </option>";
              $('#salesRep').html(option);
            }
            
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

        $.ajax({
          url:"/ajaxResults/sourceByRegion",
          method:"POST",
          data:{regionID},
          success: function(output){
            $('#source').html(output);                   
          },
          error: function(xhr, ajaxOptions,thrownError){
            alert(xhr.status+" "+thrownError);
          }



        });


      }else{
        var option = "<option> Select Region </option>";
        $('#salesRepGroup').html(option);
        $('#currency').html(option);
        $('#source').html(option);
        $('#salesRep').html(option);

      }
    });

	  $('#salesRepGroup').change(function(){
		  var regionID = $("#region").val();                		
		  var salesRepGroupID = $(this).val();
      var year = $("#year").val();
      var source = $("#source").val();
   		if(regionID != "" && salesRepGroupID != ""){        			
		 	  ajaxSetup();
			  $.ajax({
  			  url:"/ajaxResults/salesRepBySalesRepGroup",
  		  	method:"POST",
  			  data:{regionID,salesRepGroupID,year,source},
        	success: function(output){
        		$('#salesRep').html(output);                		
        	},
        	error: function(xhr, ajaxOptions,thrownError){
        		alert(xhr.status+" "+thrownError);
    			}
    		});
      }else{
        var option = "<option> Select Sales Group </option>";
        $('#salesRep').html(option);
      }
      
    });
  });

