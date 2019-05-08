$(document).ready(function(){
  $('#region').change(function(){
    var regionID = $(this).val();
    if (regionID != "") {
      ajaxSetup();
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
    }else{
      var option = "<option> Select Region </option>";
      $('#currency').empty().append(option);
      $('#secondPos').empty().append(option);
    }
    $('#thirdPos').empty().append(option);
    $('#firstPos').empty().append(option);
  });

  $('#year').click(function(){
    var year = $(this).val();
    if (year != "") {
      var regionID = $('#region').val();
      if (regionID != "") {
        ajaxSetup();
        $.ajax({
          url:"/ajax/adsales/thirdPosByRegion",
          method:"POST",
          data:{regionID, year},
          success: function(output){
            $('#thirdPos').html(output);
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
        var option = "<option> Select 3rd Pos </option>";
      }else{
        var option = "<option> Select Region </option>";
      }
      $('#firstPos').empty().append(option);
      $('#thirdPos').empty().append(option);
    }else{
      var option = "<option> Select Year </option>";
      $('#firstPos').empty().append(option);
      $('#secondPos').empty().append(option);
      $('#thirdPos').empty().append(option);
    }
  });

  $('#thirdPos').click(function(){
    var year = $('#year').val();
    var form = $(this).val();
    if (year != "" && form != "") {
      ajaxSetup();
      $.ajax({
        url:"/ajax/adsales/firstPosByRegion",
        method:"POST",
        data:{form, year},
        success: function(output){
          $('#firstPos').html(output);
        },
        error: function(xhr, ajaxOptions,thrownError){
          alert(xhr.status+" "+thrownError);
        }
      });
    }else{
      var option = "<option> Select 3rd Pos </option>";
      $('#firstPos').empty().append(option);
    }
  });

});