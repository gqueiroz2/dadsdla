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
              url:"/ajax/adsales/agencyByRegion",
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
              url:"/ajax/adsales/clientByRegion",
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
              url:"/ajax/adsales/clientByRegion",
              method:"POST",
              data:{regionID},
              success: function(output){
                $('#sizeOfClient').val(output);
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
      
      $('#sourceDataBase').change(function(){
        var source = $(this).val();
        var year = $('#year').val();
        var sourceDataBase = $('#sourceDataBase').val();
        
        if (sourceDataBase == "SF") {
          $('#stageFCST').html("Stage:");
          
          $('#stageFCST').val("");
          $('#stageFCSTCol').css("display", "block");
          $('#stageFCST').css("display", "block");
        }else{
          $('#stageFCST').val("0");
          $('#stageFCSTCol').css("display", "none");
          $('#stageFCST').css("display", "none");
        }

        if(sourceDataBase == "CMAPS" || sourceDataBase == "SF"){

          if(sourceDataBase == "CMAPS"){
            $('#especificNumberName').html("PI:");
          }else{
            $('#especificNumberName').html("OPPID:");
          }

          $('#especificNumber').val("");
          $('#especificNumberCol').css("display", "block");
          $('#especificNumber').css("display", "block");

        }else{
          $('#especificNumber').val("0");
          $('#especificNumberCol').css("display", "none");
          $('#especificNumber').css("display", "none");
        }

        if (sourceDataBase == "CMAPS") {
          $.ajax({
            url:"/ajax/adsales/newSalesRepRepresentativesByRegionAndYear",
            method:"POST",
            data:{regionID,year},
            success: function(output){
              $('#salesRep').html(output).selectpicker("refresh");
              //$('#vlau').html(output).selectpicker("refresh");
            },
            error: function(xhr, ajaxOptions,thrownError){
              alert(xhr.status+" "+thrownError);
            }
          });

          $.ajax({
            url:"/ajax/adsales/agencyByRegionAndYear",
            method:"POST",
            data:{year},
            success: function(output){
              $('#agency').html(output).selectpicker("refresh");
            },
            error: function(xhr, ajaxOptions,thrownError){
              alert(xhr.status+" "+thrownError);
            }
          });  

          $.ajax({
            url:"/ajax/adsales/clientByRegionAndYear",
            method:"POST",
            data:{year},
            success: function(output){
              $('#client').html(output).selectpicker("refresh");
            },
            error: function(xhr, ajaxOptions,thrownError){
              alert(xhr.status+" "+thrownError);
            }
          });  
        }else{

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

          $.ajax({
            url:"/ajax/adsales/newSalesRepByRegion",
            method:"POST",
            data:{regionID},
            success: function(output){
              $('#salesRep').html(output).selectpicker("refresh");
            },
            error: function(xhr, ajaxOptions,thrownError){
              alert(xhr.status+" "+thrownError);
            }
          });

            $.ajax({
              url:"/ajax/adsales/agencyByRegion",
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
              url:"/ajax/adsales/clientByRegion",
              method:"POST",
              data:{regionID},
              success: function(output){
                $('#client').html(output).selectpicker("refresh");
              },
              error: function(xhr, ajaxOptions,thrownError){
                alert(xhr.status+" "+thrownError);
              }
            }); 
        }
      });

      $('#year').change(function(){
        if(sourceDataBase == "CMAPS"){
          $.ajax({
            url:"/ajax/adsales/newSalesRepRepresentativesByRegionAndYear",
            method:"POST",
            data:{regionID,year},
            success: function(output){
              $('#salesRep').html(output).selectpicker("refresh");
              //$('#vlau').html(output).selectpicker("refresh");
            },
            error: function(xhr, ajaxOptions,thrownError){
              alert(xhr.status+" "+thrownError);
            }
          });

        }else{

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
        }
        
      });

      var currentTime = new Date();

      var year = currentTime.getFullYear();
      var month = currentTime.getMonth();

      if(month == 11){
        year ++;
      }

      var source = $('#sourceDataBase').val();

      $.ajax({
        url:"/ajax/adsales/clientByRegionSize",
        method:"POST",
        data:{year,regionID},
        success: function(output){
          $('#sizeOfClient').html(output).selectpicker("refresh");
        },
        error: function(xhr, ajaxOptions,thrownError){
          alert(xhr.status+" "+thrownError);
        }
      });

       $.ajax({
        url:"/ajax/adsales/agencyByRegionSize",
        method:"POST",
        data:{year,regionID},
        success: function(output){
          $('#sizeOfAgency').html(output).selectpicker("refresh");
        },
        error: function(xhr, ajaxOptions,thrownError){
          alert(xhr.status+" "+thrownError);
        }
      });

      /*
          XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX
          XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX
          XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX
          XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX
          XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX
      */

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
      //$('#brand').empty().html("<option value='' selected='true'> Select Region </option>").selectpicker('refresh');
      $('#salesRep').empty().html("<option value='' selected='true'> Select Region </option>").selectpicker('refresh');
      $('#sourceDataBase').empty().html("<option value='' selected='true'> Select Region </option>").selectpicker('refresh');
      $('#currency').empty().append(option);
      $('#value').empty().append("<option>Select Source</option>");
      $('#especificNumber').val("0");
      $('#especificNumberCol').css("display", "none");
      $('#especificNumber').css("display", "none");
      $('#stageFCST').val("0");
      $('#stageFCSTCol').css("display", "none");
      $('#stageFCST').css("display", "none");
    }

 

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

});