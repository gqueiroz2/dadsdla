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
  /*
  number = number.replace(",",";");
  for (var i = 0; i < number.length/3; i++) {
    number = number.replace(".","");
  }
  number = number.replace(";",".");
  number = parseFloat(number); 
  */
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

  $('.linked').scroll(function(){

    $('.linked').scrollLeft($(this).scrollLeft());
});