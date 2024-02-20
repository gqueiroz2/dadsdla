// Considerations: use the front-end filters for generating data for the table and not the actual :D

$(document).ready(function(){
    var regionID = 1;
    var year = $('#year').val();
    ajaxSetup();
    if (regionID != "") {
      $.ajax({ 
        url:"/ajax/adsales/yearByRegion",
        method:"POST",
        data:{regionID},
        success: function(output){
          $('#year').html(output);
          var year = $('#year').val();
          if(year == ""){
            var option = "<option> Select Year </option>";           
          }else{            
            $.ajax({
              url:"/ajax/adsales/repByRegionAndYear",
              method:"POST",
              data:{regionID, year},
              success: function(output){
                $('#salesRep').html(output).selectpicker("refresh");
              },
              error: function(xhr, ajaxOptions,thrownError){
                alert(xhr.status+" "+thrownError);
              }
            });

            $.ajax({
              url:"/ajax/adsales/getPacketsFilter",
              method:"POST",
              data:{regionID},
              success: function(output){
                $('#property').html(output).selectpicker("refresh");
              },
              error: function(xhr, ajaxOptions,thrownError){
                alert(xhr.status+" "+thrownError);
              }
            });  

            $.ajax({
              url:"/ajax/adsales/getAgencyPipeline",
              method:"POST",
              data:{regionID},
              success: function(output){
                $('#agency').html(output).selectpicker("refresh");
              },
              error: function(xhr, ajaxOptions,thrownError){
                alert(xhr.status+" "+thrownError);
              }
            });  

            $.ajax({
              url:"/ajax/adsales/getClientPipeline",
              method:"POST",
              data:{regionID},
              success: function(output){
                $('#client').html(output).selectpicker("refresh");
              },
              error: function(xhr, ajaxOptions,thrownError){
                alert(xhr.status+" "+thrownError);
              }
            }); 

            $.ajax({
              url:"/ajax/adsales/getClientPipeline",
              method:"POST",
              data:{regionID},
              success: function(output){
                $('#sizeOfClient').val(output);
              },
              error: function(xhr, ajaxOptions,thrownError){
                alert(xhr.status+" "+thrownError);
              }
            });

             $.ajax({
              url:"/ajax/adsales/getDirector",
              method:"POST",
              data:{regionID, year},
              success: function(output){
                $('#director').html(output).selectpicker("refresh");
              },
              error: function(xhr, ajaxOptions,thrownError){
                alert(xhr.status+" "+thrownError);
              }
            });
            
          }
        },
        error: function(xhr, ajaxOptions,thrownError){
          alert(xhr.status+" "+thrownError);
        }
      });
      
     

      $('#year').change(function(){ 

        $.ajax({
          url:"/ajax/adsales/repByRegionAndYear",
          method:"POST",
          data:{regionID, year},
          success: function(output){
            $('#salesRep').html(output).selectpicker("refresh");
          },
          error: function(xhr, ajaxOptions,thrownError){
            alert(xhr.status+" "+thrownError);
          }
        });
        
        
      });

      var currentTime = new Date();

      var year = currentTime.getFullYear();
      var month = currentTime.getMonth();

      if(month == 11){
        year ++;
      }

      /*
          XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX
          XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX
          XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX
          XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX
          XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX
      */

    

    }else{
      var option = "<option> Select Region </option>";
      $('#year').empty().append(option);
      //$('#brand').empty().html("<option value='' selected='true'> Select Region </option>").selectpicker('refresh');
      $('#salesRep').empty().html("<option value='' selected='true'> Select Region </option>").selectpicker('refresh');
     
    }

  });

  $('.agencyChange').change(function(){
    var agency = $(this).val();
    var region = $('#region').val();
    var year = $('#year').val();
    $.ajax({
      url:"/ajax/adsales/clientByRegionAndAgency",
      method:"POST",
      data:{agency,region,year},
      success: function(output){
        $('#client').html(output).selectpicker("refresh");
      },
      error: function(xhr, ajaxOptions,thrownError){
        alert(xhr.status+" "+thrownError);
      }
    });

    $.ajax({
      url:"/ajax/adsales/clientByRegionAndAgencySize",
      method:"POST",
      data:{agency,region,year},
      success: function(output){
        $('#sizeOfClient').val(output);
      },
      error: function(xhr, ajaxOptions,thrownError){
        alert(xhr.status+" "+thrownError);
      }
    });    
});