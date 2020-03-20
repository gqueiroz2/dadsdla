$(document).ready(function(){
	$('#region').change(function(){
		var regionID = $(this).val();

    ajaxSetup();
		if (regionID != "") {

      var bool = true;
      $.ajax({
  			url:"/ajaxRanking/typeByRegion",
  			method:"POST",
  			data:{regionID, bool},
    		success: function(output){
      		$('#type').html(output);
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
      var option2 = "<option> Select Type </option>";
      $('#type').empty().append(option);
      $('#typeName').html("Select the previous field:").css("color", "red");
      $('#type2').empty().selectpicker('refresh');
      $('#nPos').empty().append(option2);
      $('#firstPos').empty().append(option);
      $('#secondPos').empty().append(option);
      $('#thirdPos').empty().append(option);
      $('#currency').empty().append(option);
    }

	});
});


      