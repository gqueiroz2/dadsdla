$(document).ready(function(){
  $('#type').change(function(){
    var type = $(this).val();
    var region = $('#region').val();
    $.ajax({
        url:"/ajax/typeSelectConsolidateDLA",
        method:"POST", 
        data:{type,region},       
          success: function(output){
            $('#typeSelect').html(output).selectpicker('refresh');

            if(type == 'agencyGroup'){
              typeShow = 'Agency Group';
            }else if(type == 'ae'){
              typeShow = 'Account Executive';
            }else{
              typeShow = type.charAt(0).toUpperCase() + type.slice(1);
            }

            $('#typeSelectLabel').html(typeShow);
            $('#typeSelectLabel').css("color", "black");

          },
          error: function(xhr, ajaxOptions,thrownError){
            alert(xhr.status+" "+thrownError);
        }
      });  
  });

	
});