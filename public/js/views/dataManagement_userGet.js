jQuery(document).ready(function($){
			
	$('#user_region').click(function(e){			
		var region = $(this).val();

		if(region == ''){
			$('#user_sub_level_bool').attr("readonly",true);
		}else{
			$('#user_sub_level_bool').attr("readonly",false);										
		}

	});

	$('#user_sub_level_bool').click(function(e){
		var subLevelBool = $(this).val();                		
		var regionID = $('#user_region').val();				

		if(subLevelBool = ''){
			$('#user_sub_level_group').attr("readonly",true);
		}else{
			$('#user_sub_level_group').attr("readonly",false);
			/*
				SETUP THE AJAX FOR ALL CALLS
			*/
				ajaxSetup();
			/*
				GET THE TARGET/FCST'S BY YEAR
			*/
				getSubLevelGroupByRegion(regionID);
		}
	});
});