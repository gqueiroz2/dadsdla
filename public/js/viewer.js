$(document).ready(function(){
  $('#region').change(function(){

    var regionID = $(this).val();

    ajaxSetup();

    if (regionID != "") {
      //$('#brand').empty().html("<option value='' selected='true'> Select Source </option>").selectpicker('refresh');
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
              url:"/ajax/adsales/newSalesRepByRegionAndYear",
              method:"POST",
              data:{regionID,year},
              success: function(output){
                $('#salesRep').html(output).selectpicker("refresh");
              },
              error: function(xhr, ajaxOptions,thrownError){
                alert(xhr.status+" "+thrownError);
              }
            });

            $.ajax({
              url:"/ajax/adsales/newSalesRepUnitByRegionAndYear",
              method:"POST",
              data:{regionID,year},
              success: function(output){
                $('#salesRepUnit').html(output).selectpicker("refresh");
              },
              error: function(xhr, ajaxOptions,thrownError){
                alert(xhr.status+" "+thrownError);
              }
            });
            
            $.ajax({
              url:"/ajax/adsales/thirdPosByRegion",
              method:"POST",
              data:{regionID, year},
                success: function(output){
                  $('#thirdPos').html(output);
                  var form = $('#thirdPos').val();
                  $.ajax({
                    url:"/ajax/adsales/firstPosByRegion",
                    method:"POST",
                    data:{year,form},
                      success: function(output){
                        $('#firstPos').html(output);
                      },
                      error: function(xhr, ajaxOptions,thrownError){
                        alert(xhr.status+" "+thrownError);
                    }
                  });

                  var source = $('#thirdPos').val();
                  $.ajax({
                    url:"/ajaxResults/valueBySource",
                    method:"POST",
                    data:{source},
                      success: function(output){
                        $('#value').html(output);
                      },
                      error: function(xhr, ajaxOptions,thrownError){
                        alert(xhr.status+" "+thrownError);
                    }
                  });                  

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

              $.ajax({
                url:"/ajax/adsales/clientByRegionSize",
                method:"POST",
                data:{regionID,year},
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
        
        var sourceDataBase = $('#sourceDataBase').val();
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
      });

      var currentTime = new Date();

      var year = currentTime.getFullYear();

      $.ajax({
        url:"/ajax/adsales/salesRepByRegionAndYear",
        method:"POST",
        data:{regionID,year},
        success: function(output){
          $('#saleRep').html(output).selectpicker("refresh");
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

      $.ajax({
        url:"/ajax/adsales/sourceByRegion",
        method:"POST",
        data:{regionID},
        success: function(output){
          $('#sourceDataBase').html(output).selectpicker("refresh");
        },
        error: function(xhr, ajaxOptions,thrownError){
          alert(xhr.status+" "+thrownError);
        }
      });

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
      $('#firstPos').empty().append(option);
      $('#secondPos').empty().append(option);
      $('#thirdPos').empty().append(option);
      $('#value').empty().append("<option>Select Source</option>");
      $('#especificNumber').val("0");
      $('#especificNumberCol').css("display", "none");
      $('#especificNumber').css("display", "none");
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

  $('#thirdPos').change(function(){
    var source = $('#thirdPos').val();
    $.ajax({
      url:"/ajaxResults/valueBySource",
      method:"POST",
      data:{source},
        success: function(output){
          $('#value').html(output);
        },
        error: function(xhr, ajaxOptions,thrownError){
          alert(xhr.status+" "+thrownError);
      }
    }); 
  });

  $('#year').click(function(){
    var year = $(this).val();
    if (year != "") {
      var regionID = $('#region').val();
      ajaxSetup();      
      if (regionID != "") {

        $.ajax({
          url:"/ajax/adsales/thirdPosByRegion",
          method:"POST",
          data:{regionID, year},
            success: function(output){
              $('#thirdPos').html(output);
              var form = $('#thirdPos').val();
              $.ajax({
                url:"/ajax/adsales/firstPosByRegion",
                method:"POST",
                data:{year,form},
                  success: function(output){
                    $('#firstPos').html(output);
                  },
                  error: function(xhr, ajaxOptions,thrownError){
                    alert(xhr.status+" "+thrownError);
                }
              });
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
        
        $.ajax({
          url:"/ajax/adsales/salesRepByRegionAndYear",
          method:"POST",
          data:{regionID,year},
          success: function(output){
            $('#salesRep').html(output).selectpicker("refresh");
          },
          error: function(xhr, ajaxOptions,thrownError){
            alert(xhr.status+" "+thrownError);
          }
        });


      }else{
        var option = "<option> Select Region </option>";
      }
    }else{
      var option = "<option> Select Year </option>";
      $('#firstPos').empty().append(option);
      $('#secondPos').empty().append(option);
      $('#thirdPos').empty().append(option);
    }
  });
});