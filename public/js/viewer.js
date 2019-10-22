$(document).ready(function(){
	$('#region').change(function(){

		var regionID = $(this).val();

    ajaxSetup();

		if (regionID != "") {
      $('#brand').empty().html("<option value='' selected='true'> Select Source </option>").selectpicker('refresh');
      $.ajax({ 
        url:"/ajax/adsales/yearByRegion",
        method:"POST",
        data:{regionID},
        success: function(output){
          $('#year').html(output);
          var year = $('#year').val();
          if(year == ""){
            var option = "<option> Select Year </option>";           
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
      /*
          XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX
          XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX
          XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX
          XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX
          XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX
      */
      $('#sourceDataBase').change(function(){
        var source = $(this).val();
        if(source != ""){
          $.ajax({
            url:"/ajax/adsales/brandBySource",
            method:"POST",
            data:{source},
              success: function(output){
                $('#brand').html(output).selectpicker('refresh');
              },
              error: function(xhr, ajaxOptions,thrownError){
                alert(xhr.status+" "+thrownError);
            }
          });
        }else{
          $('#brand').empty().html("<option value='' selected='true'> Select Source </option>").selectpicker('refresh');
        }


        var sourceDataBase = $('#sourceDataBase').val();
        if(sourceDataBase == "CMAPS"){
          $('#piNumber').val("");
          $('#piNumberCol').css("display", "block");
          $('#piNumber').css("display", "block");

        }else{
          $('#piNumber').val("0");
          $('#piNumberCol').css("display", "none");
          $('#piNumber').css("display", "none");
        }
      });


      $.ajax({
        url:"/ajax/adsales/newSalesRepByRegion",
        method:"POST",
        data:{regionID},
        success: function(output){
          $('#salesRep').html(output).selectpicker("refresh");
        },
        error: function(xhr, ajaxOptions,thrownError){
          alert(xhr.status+" "+thrownError);
        }
      });

      $.ajax({
        url:"/ajax/adsales/agencyByRegion",
        method:"POST",
        data:{regionID},
        success: function(output){
          $('#agency').html(output).selectpicker("refresh");
        },
        error: function(xhr, ajaxOptions,thrownError){
          alert(xhr.status+" "+thrownError);
        }
      });

      $.ajax({
        url:"/ajax/adsales/clientByRegion",
        method:"POST",
        data:{regionID},
        success: function(output){
          $('#client').html(output).selectpicker("refresh");
        },
        error: function(xhr, ajaxOptions,thrownError){
          alert(xhr.status+" "+thrownError);
        }
      });

      $.ajax({
        url:"/ajax/adsales/sourceByRegion",
        method:"POST",
        data:{regionID},
        success: function(output){
          $('#sourceDataBase').html(output).selectpicker("refresh");
        },
        error: function(xhr, ajaxOptions,thrownError){
          alert(xhr.status+" "+thrownError);
        }
      });

      /*
          XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX
          XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX
          XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX
          XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX
          XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX
      */

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
      $('#brand').empty().html("<option value='' selected='true'> Select Region </option>").selectpicker('refresh');
      $('#sourceDataBase').empty().html("<option value='' selected='true'> Select Region </option>").selectpicker('refresh');
      $('#currency').empty().append(option);
      $('#firstPos').empty().append(option);
      $('#secondPos').empty().append(option);
      $('#thirdPos').empty().append(option);
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