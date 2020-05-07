$(document).ready(function(){
	
  const capitalize = (s) => {
    if (typeof s !== 'string') return ''
    return s.charAt(0).toUpperCase() + s.slice(1)
  }

  $('#region').change(function(){
		var regionID = $(this).val();

    ajaxSetup();
		if (regionID != "") {
      $('#baseFilter').html("<option> Select Type </option>").selectpicker('refresh');
      $('#labelBaseFilter').html("Select Type").css("color", "red");
      
      var bool = "false";
      $.ajax({
  			url:"/ajax/dashboards/typeByRegionBV",
  			method:"POST",
  			data:{regionID, bool},
    		success: function(output){
      		$('#type').html(output);
    		},
    		error: function(xhr, ajaxOptions,thrownError){
      		alert(xhr.status+" "+thrownError);
    		}
    	});

      $('#secondaryFilter').html("<option selected='true'> Select Type </option>").selectpicker('refresh');

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

      $('#type').change(function(){       
        var type = $(this).val();
        if (type != "") {
          var region = $("#region").val();
          $.ajax({
            url:"/ajax/dashboards/BV-BaseFilter",
            method:"POST",
            data:{type,region},
            success: function(output){
              $('#baseFilter').html(output).selectpicker('refresh');
            },
            error: function(xhr, ajaxOptions,thrownError){
                alert(xhr.status+" "+thrownError);
            }
          });
          $.ajax({
            url:"/ajax/dashboards/Overview-BaseFilterTitle",
            method:"POST",
            data:{type,region},
            success: function(output){
              $('#labelBaseFilter').html(output).css("color", "black");
            },
            error: function(xhr, ajaxOptions,thrownError){
                alert(xhr.status+" "+thrownError);
            }
          });
        }else{
          $('#baseFilter').empty().selectpicker('refresh');
          $('#baseFilter').html("<option> Select Type </option>").selectpicker('refresh');
          $('#labelBaseFilter').html("Select Type").css("color", "red");
        }
      });
		}else{
      var option = "<option> Select Region </option>";
      $('#type').empty().append(option);
      $('#currency').empty().append(option);
      $('#baseFilter').empty().selectpicker('refresh');
      $('#baseFilter').html("<option> Select Region </option>").selectpicker('refresh');
      $('#labelBaseFilter').html("Select Region").css("color", "red");
    }

	});
});



//$('#typeName').removeAttr("style").html(output+":");
