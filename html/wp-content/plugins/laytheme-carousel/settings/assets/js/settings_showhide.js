var laycarousel_showhide_settings = (function(){
	var stateMap = {
		circlesActive: null,
		cursors: null,
		transition: null,
		showCaptions: null,
		showNumbers: null,
		sinkPosition: null
	};

	var initModule = function(){
		numbersIsChecked = jQuery('#laycarousel_showNumbers').is(':checked');
		captionsIsChecked = jQuery('#laycarousel_showCaptions').is(':checked');
		stateMap.sinkPosition = jQuery('#laycarousel_sink_position').val();

		jQuery('#laycarousel_sink_position').on('change', showhide_for_sinkposition)

		showhide_for_circles();
		jQuery('#laycarousel_showCircles').on('change', showhide_for_circles);

		showhide_for_cursors();
		jQuery('#laycarousel_mousecursor').on('change', showhide_for_cursors);

		showhide_for_transitiontype();
		jQuery('#laycarousel_transition').on('change', showhide_for_transitiontype);

		showhide_for_captions();
		jQuery('#laycarousel_showCaptions').on('change', showhide_for_captions);

		showhide_for_numbers();
		jQuery('#laycarousel_showNumbers').on('change', showhide_for_numbers);

		showhide_for_arrow_buttons();
		jQuery('#laycarousel_showArrowButtons').on('change', showhide_for_arrow_buttons);
		jQuery('#laycarousel_showArrowButtonsForTouchDevices').on('change', showhide_for_arrow_buttons);

		// hide show space left and space right for numbers and captions
		showhide_for_caption_position();
		showhide_for_numbers_position();
		jQuery('#laycarousel_captionsPosition').on('change', showhide_for_caption_position)
		jQuery('#laycarousel_numbersPosition').on('change', showhide_for_numbers_position)
	};

	var showhide_for_sinkposition = function() {
		stateMap.sinkPosition = jQuery('#laycarousel_sink_position').val();
		showhide_for_caption_position();
		showhide_for_numbers_position();
		showhide_for_circles_position();
	}

	var showhide_for_caption_position = function() {
		if(stateMap.showCaptions == false){
			return;
		}
		var val = jQuery('#laycarousel_captionsPosition').val();
		switch(val){
			case 'left':
				jQuery(document.getElementById('laycarousel_caption_space_right').parentNode.parentNode).hide();
				jQuery(document.getElementById('laycarousel_caption_space_left').parentNode.parentNode).show();
			break;
			case 'right':
				jQuery(document.getElementById('laycarousel_caption_space_right').parentNode.parentNode).show();
				jQuery(document.getElementById('laycarousel_caption_space_left').parentNode.parentNode).hide();
			break;
			case 'center':
				jQuery(document.getElementById('laycarousel_caption_space_right').parentNode.parentNode).hide();
				jQuery(document.getElementById('laycarousel_caption_space_left').parentNode.parentNode).hide();
			break;
		}
		switch(stateMap.sinkPosition){
			case 'below':
				jQuery(document.getElementById('laycarousel_spaceBottomCaptions').parentNode.parentNode).hide();
				// space top
				jQuery(document.getElementById('laycarousel_spaceBetween').parentNode.parentNode).show();
			break;
			case 'ontop':
				jQuery(document.getElementById('laycarousel_spaceBottomCaptions').parentNode.parentNode).show();
				// space top
				jQuery(document.getElementById('laycarousel_spaceBetween').parentNode.parentNode).hide();
			break;
		}
	}

	var showhide_for_numbers_position = function() {
		if(stateMap.showNumbers == false){
			return;
		}
		var val = jQuery('#laycarousel_numbersPosition').val();
		switch(val){
			case 'left':
				jQuery(document.getElementById('laycarousel_numbers_space_right').parentNode.parentNode).hide();
				jQuery(document.getElementById('laycarousel_numbers_space_left').parentNode.parentNode).show();
			break;
			case 'right':
				jQuery(document.getElementById('laycarousel_numbers_space_right').parentNode.parentNode).show();
				jQuery(document.getElementById('laycarousel_numbers_space_left').parentNode.parentNode).hide();
			break;
			case 'center':
				jQuery(document.getElementById('laycarousel_numbers_space_right').parentNode.parentNode).hide();
				jQuery(document.getElementById('laycarousel_numbers_space_left').parentNode.parentNode).hide();
			break;
		}
		switch(stateMap.sinkPosition){
			case 'below':
				jQuery(document.getElementById('laycarousel_spaceBottomNumbers').parentNode.parentNode).hide();
				// space top
				jQuery(document.getElementById('laycarousel_spaceBetweenForNumbers').parentNode.parentNode).show();
			break;
			case 'ontop':
				jQuery(document.getElementById('laycarousel_spaceBottomNumbers').parentNode.parentNode).show();
				// space top
				jQuery(document.getElementById('laycarousel_spaceBetweenForNumbers').parentNode.parentNode).hide();
			break;
		}
	}

	var showhide_for_arrow_buttons = function(){
		var showArrowButtons = jQuery('#laycarousel_showArrowButtons').is(':checked');
		var showArrowButtonsForTouchDevices = jQuery('#laycarousel_showArrowButtonsForTouchDevices').is(':checked');

		if(showArrowButtons){
			jQuery(document.getElementById('laycarousel_showArrowButtonsForTouchDevices').parentNode.parentNode).hide();
		}else{
			jQuery(document.getElementById('laycarousel_showArrowButtonsForTouchDevices').parentNode.parentNode).show();
		}

		if(showArrowButtonsForTouchDevices || showArrowButtons){
			jQuery(document.getElementById('laycarousel_rightbutton_button').parentNode.parentNode).show();
			jQuery(document.getElementById('laycarousel_altrightbutton_button').parentNode.parentNode).show();
			jQuery(document.getElementById('laycarousel_buttonspace').parentNode.parentNode).show();
		}else{
			jQuery(document.getElementById('laycarousel_rightbutton_button').parentNode.parentNode).hide();
			jQuery(document.getElementById('laycarousel_altrightbutton_button').parentNode.parentNode).hide();
			jQuery(document.getElementById('laycarousel_buttonspace').parentNode.parentNode).hide();
		}
	}

	var showhide_for_numbers = function(){
		stateMap.showNumbers = jQuery('#laycarousel_showNumbers').is(':checked');
		var array = ['laycarousel_spaceBetweenForNumbers', 'laycarousel_numbersPosition', 'laycarousel_numberTextformat', 'laycarousel_numbers_space_right', 'laycarousel_numbers_space_left', 'laycarousel_spaceBottomNumbers']
		
		if(stateMap.showNumbers){
			for(i=0; i<array.length; i++){
				jQuery(document.getElementById(array[i]).parentNode.parentNode).show();
			}
			showhide_for_numbers_position();
		}
		else{
			for(i=0; i<array.length; i++){
				jQuery(document.getElementById(array[i]).parentNode.parentNode).hide();
			}
		}
	};

	var showhide_for_captions = function(){
		stateMap.showCaptions = jQuery('#laycarousel_showCaptions').is(':checked');
		var array = ['laycarousel_captionsPosition', 'laycarousel_captionTextformat', 'laycarousel_spaceBetween', 'laycarousel_caption_space_right', 'laycarousel_caption_space_left', 'laycarousel_spaceBottomCaptions']

		if(stateMap.showCaptions){
			for(i=0; i<array.length; i++){
				jQuery(document.getElementById(array[i]).parentNode.parentNode).show();
			}
			showhide_for_caption_position();
		}
		else{
			for(i=0; i<array.length; i++){
				jQuery(document.getElementById(array[i]).parentNode.parentNode).hide();
			}
		}
	};

	var showhide_for_transitiontype = function(){
		stateMap.transition = jQuery('#laycarousel_transition').val();
		if(stateMap.transition == "sliding"){
			if( jQuery('#laycarousel_mousecursor').val() == "pointer" ){
				jQuery('#laycarousel_mousecursor').val('leftright').trigger('change');
			}
			jQuery('#laycarousel_mousecursor option[value="grab"]').show();
			jQuery('#laycarousel_mousecursor option[value="pointer"]').hide();
		}
		else{
			if( jQuery('#laycarousel_mousecursor').val() == "grab" ){
				jQuery('#laycarousel_mousecursor').val('leftright').trigger('change');
			}
			jQuery('#laycarousel_mousecursor option[value="grab"]').hide();
			jQuery('#laycarousel_mousecursor option[value="pointer"]').show();
		}
	}

	var showhide_for_cursors = function(){
		stateMap.cursor = jQuery('#laycarousel_mousecursor').val();
		switch(stateMap.cursor){
			case 'leftright':
				jQuery(document.getElementById('laycarousel_arrowleft').parentNode.parentNode).show();
				jQuery(document.getElementById('laycarousel_arrowright').parentNode.parentNode).show();
			break;
			case 'right':
				jQuery(document.getElementById('laycarousel_arrowleft').parentNode.parentNode).hide();
				jQuery(document.getElementById('laycarousel_arrowright').parentNode.parentNode).show();
			break;
			default:
				jQuery(document.getElementById('laycarousel_arrowleft').parentNode.parentNode).hide();
				jQuery(document.getElementById('laycarousel_arrowright').parentNode.parentNode).hide();
			break;
		}
	}

	var numbersIsChecked = null;
	var captionsIsChecked = null;
	var showhide_for_circles = function(){
		stateMap.circlesActive = jQuery('#laycarousel_showCircles').is(':checked');
		if(stateMap.circlesActive){
			numbersIsChecked = jQuery('#laycarousel_showNumbers').is(':checked');
			captionsIsChecked = jQuery('#laycarousel_showCaptions').is(':checked');
			jQuery('#laycarousel_showNumbers').removeAttr('checked').attr('disabled', 'disabled');
			jQuery('#laycarousel_showCaptions').removeAttr('checked').attr('disabled', 'disabled');
		}else{
			jQuery('#laycarousel_showNumbers').removeAttr('disabled');
			if(numbersIsChecked){
				jQuery('#laycarousel_showNumbers').attr('checked', 'checked');
			}
			
			jQuery('#laycarousel_showCaptions').removeAttr('disabled');
			if(captionsIsChecked){
				jQuery('#laycarousel_showCaptions').attr('checked', 'checked');
			}
		}

		showhide_for_captions();
		showhide_for_numbers();
		showhide_for_circles_position();
	};

	var showhide_for_circles_position = function() {
		if(stateMap.circlesActive){
			switch(stateMap.sinkPosition){
				case 'below':
					jQuery(document.getElementById('laycarousel_spaceBottomCircles').parentNode.parentNode).hide();
					jQuery(document.getElementById('laycarousel_spaceBetweenForCircles').parentNode.parentNode).show();
				break;
				case 'ontop':
					jQuery(document.getElementById('laycarousel_spaceBottomCircles').parentNode.parentNode).show();
					jQuery(document.getElementById('laycarousel_spaceBetweenForCircles').parentNode.parentNode).hide();
				break;
			}
		}
		else{
			jQuery(document.getElementById('laycarousel_spaceBottomCircles').parentNode.parentNode).hide();
			jQuery(document.getElementById('laycarousel_spaceBetweenForCircles').parentNode.parentNode).hide();
		}	
	}

	return {
		initModule : initModule
	}
}());

jQuery(document).ready(function(){
	laycarousel_showhide_settings.initModule();
});