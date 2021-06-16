$(document).ready(function(){
	$('#region').change(function(){
		var regionID = $(this).val();
    ajaxSetup();
		if (regionID != ""){  		
      $.ajax({
        url:"/ajax/typeConsolidate",
        method:"POST",        
          success: function(output){
            $('#type').html(output);
          },
          error: function(xhr, ajaxOptions,thrownError){
            alert(xhr.status+" "+thrownError);
        }
      });   

      var option = "<option> Select Type </option>";
      $('#typeSelect').empty().append(option);

       

      $.ajax({ 
        url:"/ajax/adsales/yearByRegion",
        method:"POST",
        data:{regionID},
        success: function(output){
          $('#year').html(output);
          var year = $('#year').val();
          if(year == ""){
            var option = "<option> Select Year </option>";
            $('#firstPos').empty().append(option);
            $('#secondPos').empty().append(option);
            $('#thirdPos').empty().append(option);
          }else{
            $.ajax({
              url:"/ajax/adsales/thirdPosByRegion",
              method:"POST",
              data:{regionID, year},
                success: function(output){
                  $('#thirdPos').html(output);
                  var form = $('#thirdPos').val();
                  $.ajax({
                    url:"/ajax/adsales/firstPosByRegion",
                    method:"POST",
                    data:{year,form},
                      success: function(output){
                        $('#firstPos').html(output);
                      },
                      error: function(xhr, ajaxOptions,thrownError){
                        alert(xhr.status+" "+thrownError);
                    }
                  });

                  var source = $('#thirdPos').val();

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

		}else{
      var option = "<option> Select Region </option>";
      $('#year').empty().append(option);
      $('#currency').empty().append(option);
      $('#firstPos').empty().append(option);
      $('#secondPos').empty().append(option);
      $('#thirdPos').empty().append(option);
      $('#typeSelect').empty().append("<option selected='true'>Select Region</option>").selectpicker('refresh');
      $('#value').empty().append("<option>Select Source</option>");
    }

	});

  $('#thirdPos').change(function(){
    var source = $('#thirdPos').val();
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

  $('#type').change(function(){
    var type = $(this).val();
    var region = $('#region').val();
    $.ajax({
        url:"/ajax/typeSelectConsolidate",
        method:"POST", 
        data:{type,region},       
          success: function(output){
            $('#typeSelect').html(output).selectpicker('refresh');

            if(type == 'agencyGroup'){
              typeShow = 'Agency Group';
            }else if(type == 'ae'){
              typeShow = 'Account Executive';
            }else{
              typeShow = type.charAt(0).toUpperCase() + type.slice(1);
            }

            $('#typeSelectLabel').html(typeShow);
            $('#typeSelectLabel').css("color", "black");

          },
          error: function(xhr, ajaxOptions,thrownError){
            alert(xhr.status+" "+thrownError);
        }
      });  
    console.log(type);
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
              var form = $('#thirdPos').val();
              $.ajax({
                url:"/ajax/adsales/firstPosByRegion",
                method:"POST",
                data:{year,form},
                  success: function(output){
                    $('#firstPos').html(output);
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
			}else{
        var option = "<option> Select Region </option>";
      }
		}else{
      var option = "<option> Select Year </option>";
      $('#firstPos').empty().append(option);
      $('#secondPos').empty().append(option);
      $('#thirdPos').empty().append(option);
    }
	});
});