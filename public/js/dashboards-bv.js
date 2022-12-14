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
        url:"/ajax/dashboards/salesRepByRegionFiltered",
        method:"POST",
        data:{regionID,year},
        success: function(output){
          $('#salesRep').html(output);
        },
        error: function(xhr, ajaxOptions,thrownError){
          alert(xhr.status+" "+thrownError);
        }
      });    
           
		}else{
      var option = "<option> Select Region </option>";
      $('#type').empty().append(option);
      $('#currency').empty().append(option);
      $('#salesRep').empty().append(option).selectpicker('refresh');
    }

	});

  var regionID = $(this).val();

  $('#salesRep').change(function(){
    
    var salesRep = $(this).val();
    if (salesRep != "") {

      $.ajax({
        url:"/ajax/dashboards/BV-agencyGroup",
        method:"POST",
        data:{regionID,salesRep},
        success: function(output){
          $('#agencyGroup').html(output).selectpicker('refresh');
          //$('#vlau ').html(output).selectpicker('refresh');
        },
        error: function(xhr, ajaxOptions,thrownError){
            alert(xhr.status+" "+thrownError);
        }
      });  
    }else{
      var option = "<option> Select Sales Rep </option>";
      $('#agencyGroup').empty().append(option).selectpicker('refresh');
    }
  });   

});

function handleNumber(number){
  number = number.replaceAll(",","");
  number = parseFloat(number);
  return number;
}

  function Comma(x) { //function to add commas to textboxes
      /*Num += '';
      console.log(Num);
      x = Num.split('.');
      x1 = x[0];
      x2 = x.length > 1 ? ',' + x[1] : '';
      console.log(x1);
      console.log(x2);
      if(x2){
        console.log('if');
        if(x2[2]){
          x2 = x2[0]+x2[1]+x2[2];
        }else{
          x2 = x2[0]+x2[1]+0;
        }
      }else{
        x2 = ',00';
      }

      var rgx = /(\d+)(\d{3})/;
      while (rgx.test(x1))
          x1 = x1.replace(rgx, '$1' + '.' + '$2');
      return x1 + x2;
      */
      if(x == Infinity){
        return 0
      } else {
        return x
      }

  }

//$('#typeName').removeAttr("style").html(output+":");
