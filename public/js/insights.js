-$(document).ready(function(){
	$('#region').change(function(){

		var regionID = $(this).val();

    ajaxSetup();

		if (regionID != "") {
      
     /* $('#sourceDataBase').change(function(){
        var source = $(this).val();
        
        var sourceDataBase = $('#sourceDataBase').val();
        if(sourceDataBase == "CMAPS" || sourceDataBase == "SF"){
          if(sourceDataBase == "CMAPS"){
            $('#especificNumberName').html("PI:");
          }else{
            $('#especificNumberName').html("OPPID:");
          }

          $('#especificNumber').val("");
          $('#especificNumberCol').css("display", "block");
          $('#especificNumber').css("display", "block");

        }else{
          $('#especificNumber').val("0");
          $('#especificNumberCol').css("display", "none");
          $('#especificNumber').css("display", "none");
        }
      });*/

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
        url:"/ajax/adsales/clientByRegionInsights",
        method:"POST",
        data:{regionID},
        success: function(output){
          $('#client').html(output).selectpicker("refresh");
        },
        error: function(xhr, ajaxOptions,thrownError){
          alert(xhr.status+" "+thrownError);
        }
      });

      /*
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
      $('#currency').empty().append(option);
      $('#client').empty().append(option);
    }

	});

  

});