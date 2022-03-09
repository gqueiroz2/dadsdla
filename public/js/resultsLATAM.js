$(document).ready(function(){
	$('#region').change(function(){
		var regionID = $(this).val();
    ajaxSetup();
		if (regionID != ""){  		
      
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
      $('#value').empty().append("<option>Select Source</option>");
    }

	});
});