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

      var currentTime = new Date();

      var year = currentTime.getFullYear();

       $.ajax({
        url:"/ajax/dashboards/resume-agencyGroup",
        method:"POST",
        data:{regionID},
        success: function(output){
          $('#agencyGroup').html(output).selectpicker('refresh');
          //$('#vlau ').html(output).selectpicker('refresh');
        },
        error: function(xhr, ajaxOptions,thrownError){
            alert(xhr.status+" "+thrownError);
        }
      });  
           
		}else{
      var option = "<option> Select Region </option>";
      $('#type').empty().append(option);
      $('#currency').empty().append(option);
    }

	});

});

function handleNumber(number){
  number = number.replaceAll(".","");
  number = parseFloat(number);
  return number;
}

function Comma(x) { //function to add commas to textboxes
    if(x == Infinity || isNaN(x)){
      return 0
    } else {
      return x
    }

}
