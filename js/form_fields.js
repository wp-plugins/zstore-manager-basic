jQuery(document).ready(function() {

jQuery('#zStore_admin').validate({
   
   rules: {
	showHowMany:
		{
			digits:true
		},
	gridCellSpacing:{
		digits:true
	
	},
	cacheLifetime:{
	
		digits:true
	}
	}
	,
	
	
});   //end rules
	

	function enable_disable_fields( field_name,status,bgColor,txtColor)
	{
	
		if (field_name == 'clear_cache')
		{
			console.log('in if statemnt');
			 jQuery('#clear_cache').attr("disabled", status);
			 jQuery('label[for='+field_name+']').css({color:txtColor});
		
		}
		else{

			jQuery('#'+field_name).attr('disabled', status).css('backgroundColor',bgColor);
			jQuery('label[for='+field_name+']').css({color:txtColor});
		}
		
	
	}
	
	
	function init_form_fields(){
	
	
		
		
		
	//google id
	
		
		var isChecked = jQuery('#useAnalytics').attr('checked');
  
		
		if(isChecked != 'checked') {

			
			enable_disable_fields('analyticsId',true,'#CCC','#BBB');
	
			
		}
		//cache
		var isChecked = jQuery('#useCaching').attr('checked');
		
			if(isChecked != 'checked') {
				enable_disable_fields('cacheLifetime',true,'#CCC','#BBB');
				enable_disable_fields('clear_cache',true,0,0);
		
			
			}
		var isChecked = jQuery('#use_customFeedUrl').attr('checked');
		
			if(isChecked != 'checked') {
				enable_disable_fields('customFeedUrl',true,'#CCC','#BBB');
				
		
			
		}
		
	
	}
  
  
	init_form_fields();

	jQuery('#useAnalytics').click(function() {
	//check to make sure field is checked
	
	
	
		if (jQuery(this).attr('checked')) {
		
			enable_disable_fields('analyticsId',false,'','');
		 
		} else {
			enable_disable_fields('analyticsId',true,'#CCC','#BBB');
		 
		}
	

	}); // end click()

	jQuery('#useCaching').click(function() {
	//check to make sure field is checked
	
	
	
		if (jQuery(this).attr('checked')) {
		
		 enable_disable_fields('cacheLifetime',false,'','');
		 enable_disable_fields('clear_cache',false,0,0);
		} else {
			
			enable_disable_fields('cacheLifetime','true','#CCC','#BBB');
			enable_disable_fields('clear_cache',true,0,0);
		}
	

	}); // end click()
	jQuery('#use_customFeedUrl').click(function(){
		if (jQuery(this).attr('checked')) {
			 enable_disable_fields('customFeedUrl', false,'','');
		}
		else {
			 enable_disable_fields('customFeedUrl','true','#CCC','#BBB');
		}
	});
	jQuery('#clear_cache').click(function(){
		var mydata = {
			action: 'zstore_clear_cache'
			
		};
	 

		jQuery.ajax({                
			url: ajax_object.ajax_url,
			type: "POST",
			data: mydata,     
			cache: false,
			success:function(result){
    alert('Cache successfully cleared');
  },error:
       function(result){
    alert('Error cache not cleared');
  }
		});		
		
		
	});

	jQuery('#submit').click(function() {

		var data = jQuery("#zstore :input").serializeArray();
	
		jQuery.post(jQuery("#zstore").attr('action'), data, function(json){
			
			if (json.status == "fail") {
				alert(json.message);
			}
			if (json.status == "success") {
				alert(json.message);
				
			}
		}, "json");

	});	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	



});