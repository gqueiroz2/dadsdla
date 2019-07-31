$(document).ready(function(){
	$('#region').change(function(){
		var regionID = $(this).val();

    ajaxSetup();
		if (regionID != "") {
      $.ajax({
  			url:"/ajaxRanking/typeByRegion",
  			method:"POST",
  			data:{regionID},
    		success: function(output){
      		$('#type').html(output);
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

      $('#type').change(function(){
        var type = $(this).val();

        if (type != "") {
          
          var region = $("#region").val();
          
          $.ajax({
            url:"/ajax/dashboards/Overview-BaseFilter",
            method:"POST",
            data:{type,region},
            success: function(output){
              $('#baseFilter').html(output);
            },
            error: function(xhr, ajaxOptions,thrownError){
                alert(xhr.status+" "+thrownError);
            }
          });

          $('#baseFilter').change(function(){
            var baseFilter = $(this).val();
            alert(baseFilter);
            if(baseFilter != ""){
              $.ajax({
                url:"/ajax/dashboards/Overview-SecondaryFilter",
                method:"POST",
                data:{type,region},
                success: function(output){
                  $('#vlau').html(output);
                },
                error: function(xhr, ajaxOptions,thrownError){
                    alert(xhr.status+" "+thrownError);
                }
              });
            }
          }
          

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
          $('#baseFilter').empty();
          $('#baseFilter').html("<option> Select Type </option>");
          $('#labelBaseFilter').html("Select Type").css("color", "red");
          $('#secondaryFilter').empty();
          $('#secondaryFilter').html("<option> Select Type </option>");
          $('#labelSecondaryFilter').html("Select Type").css("color", "red");
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



//$('#typeName').removeAttr("style").html(output+":");