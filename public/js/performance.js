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
      		$('#salesRepGroup').html(output).selectpicker('refresh');
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
                  $('#salesRep').html(output).selectpicker('refresh');
                },
                error: function(xhr, ajaxOptions,thrownError){
                  alert(xhr.status+" "+thrownError);
                }
              });
            }else{
              $('#salesRep').selectpicker('deselectAll').selectpicker('refresh');
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
          var year = $('#year').val();

        },
        error: function(xhr, ajaxOptions,thrownError){
          alert(xhr.status+" "+thrownError);
        }
      });

      $.ajax({
        url:"/ajax/adsales/tierByRegion",
        method:"POST",
        data:{regionID},
        success: function(output){
          $('#tier').html(output).selectpicker('refresh');

          var tiers = $('#tier').val();

          $.ajax({
            url:"/ajax/adsales/brandsByTier",
            method:"POST",
            data:{tiers},
            success: function(output){
              $('#brand').html(output).selectpicker('refresh');
            },
            error: function(xhr, ajaxOptions,thrownError){
                alert(xhr.status+" "+thrownError);
            }
          });    
        },
        error: function (xhr, ajaxOptions,thrownError) {
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
      $('#year').html(option);
      $('#tier').selectpicker('deselectAll').selectpicker('refresh');
      $('#salesRepGroup').selectpicker('deselectAll').selectpicker('refresh');
      $('#currency').html(option);
      $('#source').html(option);
      $('#salesRep').selectpicker('deselectAll').selectpicker('refresh');

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
      		$('#salesRep').html(output).selectpicker("refresh");                		
      	},
      	error: function(xhr, ajaxOptions,thrownError){
      		alert(xhr.status+" "+thrownError);
  			}
  		});
    }else{
      $('#salesRep').selectpicker('deselectAll').selectpicker('refresh');
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
          $('#salesRep').html(output).selectpicker('refresh');                    
        },
        error: function(xhr, ajaxOptions,thrownError){
          alert(xhr.status+" "+thrownError);
        }
      });
    }else{
      $('#salesRep').selectpicker('deselectAll').selectpicker('refresh');
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
          $('#salesRep').html(output).selectpicker('refresh');                    
        },
        error: function(xhr, ajaxOptions,thrownError){
          alert(xhr.status+" "+thrownError);
        }
      });
    }else{
      $('#salesRep').selectpicker('deselectAll').selectpicker('refresh');
    }

  });

  $('#tier').change(function(){

    var tiers = $(this).val();

    ajaxSetup();

    $.ajax({
      url:"/ajax/adsales/brandsByTier",
      method:"POST",
      data:{tiers},
      success: function(output){
        $('#brand').html(output).selectpicker('refresh');
      },
      error: function(xhr, ajaxOptions,thrownError){
          alert(xhr.status+" "+thrownError);
      }
    });    
  });
});

