$(document).ready(function(){
	$('#region').change(function(){
		var regionID = $(this).val();

    ajaxSetup();
		if (regionID != "") {
      var bool = false;
      $.ajax({
  			url:"/ajaxRanking/typeByRegion",
  			method:"POST",
  			data:{regionID, bool},
    		success: function(output){
      		$('#type').html(output);
    		},
    		error: function(xhr, ajaxOptions,thrownError){
      		alert(xhr.status+" "+thrownError);
    		}
    	});

      $.ajax({
        url:"/ajaxRanking/firstPosYear",
        method:"POST",
        data:{regionID},
        success: function(output){
          $('#firstPos').html(output);
          var first = $('#firstPos').val();

          $.ajax({
            url:"/ajaxRanking/secondPosYear",
            method:"POST",
            data:{first},
            success: function(output){

              $('#secondPos').html(output);

              var second = $('#secondPos').val();
              
              $.ajax({
                url:"/ajaxRanking/thirdPosYear",
                method:"POST",
                data:{second},
                success: function(output){
                  $('#thirdPos').html(output);
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
          $.ajax({
            url:"/ajaxRanking/typeNameByType",
            method:"POST",
            data:{type},
            success: function(output){
              $('#typeName').removeAttr("style").html(output+":");
            },
            error: function(xhr, ajaxOptions,thrownError){
                alert(xhr.status+" "+thrownError);
            }
          });

          var region = $('#region').val();
          var year = $("#firstPos").val();

          $.ajax({
            url:"/ajaxRanking/type2ByType",
            method:"POST",
            data:{type, region, year},
            success: function(output){
              $('#type2').html(output).selectpicker('refresh');

              var type2 = $('#type2').val();

              $.ajax({
                url:"/ajaxRanking/topsByType2",
                method:"POST",
                data:{type2},
                success: function(output){
                  $('#nPos').html(output);
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

          $('#firstPos').change(function(){
            
            var region = $('#region').val();
            var year = $("#firstPos").val();
            var type = $('#type').val();
            
            $.ajax({
              url:"/ajaxRanking/type2ByType",
              method:"POST",
              data:{type, region, year},
              success: function(output){
                $('#type2').html(output).selectpicker('refresh');
              },
              error: function(xhr, ajaxOptions,thrownError){
                  alert(xhr.status+" "+thrownError);
              }
            });            
          });

        }else{
          var option = "<option> Select Type </option>";    
          $('#typeName').html("Select the previous field:").css("color", "red");
          $('#type2').empty().selectpicker('refresh');
          $('#nPos').empty().append(option);
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