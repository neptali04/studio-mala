var misc_options_showhide_settings = (function(){

	var initModule = function(){
		showhide_for_textformats_for_tablet();
		jQuery('#misc_options_textformats_for_tablet').on('change', showhide_for_textformats_for_tablet);
	};

	var showhide_for_textformats_for_tablet = function(){
		var val = jQuery('#misc_options_textformats_for_tablet').is(':checked');
		if(val){
			jQuery(document.getElementById("misc_options_textformats_tablet_breakpoint").parentNode.parentNode).show();
		}
		else{
			jQuery(document.getElementById("misc_options_textformats_tablet_breakpoint").parentNode.parentNode).hide();
		}		
	};

	return {
		initModule : initModule
	}
}());

jQuery(document).ready(function(){
	misc_options_showhide_settings.initModule();
});