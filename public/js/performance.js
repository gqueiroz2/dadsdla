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
            setTimeout(function(){
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
            },60);
            
      		},
      		error: function(xhr, ajaxOptions,thrownError){
        		alert(xhr.status+" "+thrownError);
  		    }


    	  });


        $.ajax({
          url:"/ajax/adsales/yearByRegion",
          method:"POST",
          data:{regionID},
          success: function(output){
            $('#year').html(output);
          },
          error: function(xhr, ajaxOptions,thrownError){
            alert(xhr.status+" "+thrownError);
          }
        });

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

        $.ajax({
          url:"/ajaxResults/sourceByRegion",
          method:"POST",
          data:{regionID},
          success: function(output){
            $('#source').html(output);                   
            var source = $('#source').val();
            $.ajax({
              url:"/ajaxResults/valueBySource",
              method:"POST",
              data:{source},
                success: function(output){
                  $('#value').html(output);
                },
                error: function(xhr, ajaxOptions,thrownError){
                  alert(xhr.status+" "+thrownError);
              }
            });
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

    $('#source').change(function(){
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

      var source = $(this).val();
      $.ajax({
        url:"/ajaxResults/valueBySource",
        method:"POST",
        data:{source},
          success: function(output){
            $('#value').html(output);
          },
          error: function(xhr, ajaxOptions,thrownError){
            alert(xhr.status+" "+thrownError);
        }
      });


    });

    $('#year').change(function(){
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

    });
  });

