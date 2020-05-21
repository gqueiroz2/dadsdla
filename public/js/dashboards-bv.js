$(document).ready(function(){
	
  const capitalize = (s) => {
    if (typeof s !== 'string') return ''
    return s.charAt(0).toUpperCase() + s.slice(1)
  }

  $('#region').change(function(){
		var regionID = $(this).val();

    ajaxSetup();
		if (regionID != "") {
      var bool = "false";
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
        url:"/ajax/dashboards/BV-agencyGroup",
        method:"POST",
        data:{regionID},
        success: function(output){
          $('#agencyGroup').html(output).selectpicker('refresh');
        },
        error: function(xhr, ajaxOptions,thrownError){
            alert(xhr.status+" "+thrownError);
        }
      });
      
		}else{
      var option = "<option> Select Region </option>";
      $('#type').empty().append(option);
      $('#currency').empty().append(option);
      $('#agencyGroup').empty().append(option).selectpicker('refresh');
    }

	});
});



//$('#typeName').removeAttr("style").html(output+":");
