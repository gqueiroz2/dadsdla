$(document).ready(function(){
  $('#region').change(function(){
    var regionID = $(this).val();
    ajaxSetup();
    if (regionID != "") {
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
        url:"/ajax/yearOnFcst",
        method:"POST",
        data:{regionID},
        success: function(output){
          $('#year').html(output);
          var year = $("#year").val();
          $.ajax({
            url:"/ajax/salesRepByRegionPandR",
            method:"POST",
            data:{regionID,year},
            success: function(output){
              $('#salesRep').html(output);
            },
            error: function(xhr, ajaxOptions,thrownError){
              alert(xhr.status+" "+thrownError);
            }
          })         
        
        },
        error: function(xhr, ajaxOptions,thrownError){
          alert(xhr.status+" "+thrownError);
        }
      });
      
    }else{
      var option = "<option> Select Region </option>";
      $('#currency').empty().append(option);
      $('#salesRep').empty().append(option);
    }

  });

});

function handleNumber(number){
  number = number.replaceAll(",","");
  number = parseFloat(number);
  return number;
}

  function Comma(x) { //function to add commas to textboxes
      if(x == Infinity){
        return 0
      } else {
        return x
      }

  }

$('.linked').scroll(function(){

  $('.linked').scrollLeft($(this).scrollLeft());
});