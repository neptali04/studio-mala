<?php

class LayThemeCarouselSettings{

	public function __construct(){
		add_action( 'admin_init', array($this, 'carousel_settings_api_init') );
		add_action( 'admin_menu', array($this, 'carousel_setup_menu'), 20 );
		add_action( 'admin_enqueue_scripts', array($this, 'carousel_settings_enqueue_scripts') );
	}

	public function carousel_settings_enqueue_scripts($hook){
		if($hook == "lay-options_page_manage-laycarousel"){
			wp_enqueue_script( 'laycarousel_settings_showhide', plugin_dir_url( __FILE__ ).'/assets/js/settings_showhide.js', array(), LayThemeCarousel::$ver );
			wp_enqueue_media();
			wp_enqueue_script( 'laycarousel_settings_image_upload', plugin_dir_url( __FILE__ ).'/assets/js/image_upload.js', array(), LayThemeCarousel::$ver );
		}
	}

	public function carousel_setup_menu(){
		add_submenu_page( 'manage-layoptions', 'Carousel Addon', 'Carousel Addon', 'manage_options', 'manage-laycarousel', array($this, 'carousel_options_markup') );
	}

	public function carousel_options_markup(){
		require_once( LayThemeCarousel::$dir.'/settings/carousel_options_markup.php' );
	}

	public function carousel_settings_api_init(){
		add_settings_section(
			'laycarouselsoptions_general_section',
			'General',
			'',
			'manage-laycarousel'
		);
		add_settings_section(
			'laycarouselsoptions_mousecursor_section',
			'Mouse Cursor',
			'',
			'manage-laycarousel'
		);
		add_settings_section(
			'laycarouselsoptions_circles_section',
			'Circles as Navigation',
			'',
			'manage-laycarousel'
		);
		add_settings_section(
			'laycarouselsoptions_captions_section',
			'Captions for Slides',
			'',
			'manage-laycarousel'
		);
		add_settings_section(
			'laycarouselsoptions_numbers_section',
			'Numbers',
			'',
			'manage-laycarousel'
		);
		add_settings_section(
			'laycarouselsoptions_buttons_section',
			'Previous/Next Buttons',
			'',
			'manage-laycarousel'
		);
		
		add_settings_field(
			'laycarousel_transition',
			'Transition Type',
			array($this, 'laycarousel_transition_setting'),
			'manage-laycarousel',
			'laycarouselsoptions_general_section'
		);
		register_setting( 'manage-laycarousel', 'laycarousel_transition' );

		add_settings_field(
			'laycarousel_sink_position',
			'Position of Captions, Numbers and Circles',
			array($this, 'laycarousel_sink_position_setting'),
			'manage-laycarousel',
			'laycarouselsoptions_general_section'
		);
		register_setting( 'manage-laycarousel', 'laycarousel_sink_position' );

		add_settings_field(
			'laycarousel_align_sink',
			'Align Captions and Numbers to width of slides',
			array($this, 'laycarousel_align_sink_setting'),
			'manage-laycarousel',
			'laycarouselsoptions_general_section'
		);
		register_setting( 'manage-laycarousel', 'laycarousel_align_sink' );

		// autoplay
		add_settings_field(
			'laycarousel_autoplaySpeed',
			'Autoplay Speed',
			array($this, 'laycarousel_autoplaySpeed_setting'),
			'manage-laycarousel',
			'laycarouselsoptions_general_section'
		);
		register_setting( 'manage-laycarousel', 'laycarousel_autoplaySpeed' );

		add_settings_field(
			'laycarousel_pauseAutoplayOnHover',
			'Pause Autoplay on Mouseover',
			array($this, 'laycarousel_pauseAutoplayOnHover_setting'),
			'manage-laycarousel',
			'laycarouselsoptions_general_section'
		);
		register_setting( 'manage-laycarousel', 'laycarousel_pauseAutoplayOnHover' );

		add_settings_field(
			'laycarousel_loop',
			'Loop Slides',
			array($this, 'laycarousel_loop_setting'),
			'manage-laycarousel',
			'laycarouselsoptions_general_section'
		);
		register_setting( 'manage-laycarousel', 'laycarousel_loop' );

		add_settings_field(
			'laycarousel_lazyload',
			'Lazyload images',
			array($this, 'laycarousel_lazyload_setting'),
			'manage-laycarousel',
			'laycarouselsoptions_general_section'
		);
		register_setting( 'manage-laycarousel', 'laycarousel_lazyload' );

		// vertical alignment
		add_settings_field(
			'laycarousel_alignment',
			'Vertical Alignment of Slides',
			array($this, 'laycarousel_alignment_setting'),
			'manage-laycarousel',
			'laycarouselsoptions_general_section'
		);
		register_setting( 'manage-laycarousel', 'laycarousel_alignment' );

		add_settings_field(
			'laycarousel_text_alignment',
			'Vertical Alignment of Text Slides',
			array($this, 'laycarousel_text_alignment_setting'),
			'manage-laycarousel',
			'laycarouselsoptions_general_section'
		);
		register_setting( 'manage-laycarousel', 'laycarousel_text_alignment' );
		// 

		// a textslide setting
		add_settings_field(
			'laycarousel_textspace',
			'Horizontal Space around Text in Slides',
			array($this, 'laycarousel_textspace_setting'),
			'manage-laycarousel',
			'laycarouselsoptions_general_section'
		);
		register_setting( 'manage-laycarousel', 'laycarousel_textspace' );
		//

		// mouse cursor
		add_settings_field(
			'laycarousel_mousecursor',
			'Mouse Cursor Controls',
			array($this, 'laycarousel_mousecursor_setting'),
			'manage-laycarousel',
			'laycarouselsoptions_mousecursor_section'
		);
		register_setting( 'manage-laycarousel', 'laycarousel_mousecursor' );

		add_settings_field(
			'laycarousel_arrowleft',
			'Custom Mouse Cursor "Previous"',
			array($this, 'laycarousel_arrowleft_setting'),
			'manage-laycarousel',
			'laycarouselsoptions_mousecursor_section'
		);
		register_setting( 'manage-laycarousel', 'laycarousel_arrowleft' );

		add_settings_field(
			'laycarousel_arrowright',
			'Custom Mouse Cursor "Next"',
			array($this, 'laycarousel_arrowright_setting'),
			'manage-laycarousel',
			'laycarouselsoptions_mousecursor_section'
		);
		register_setting( 'manage-laycarousel', 'laycarousel_arrowright' );

		// circles as navigation
		add_settings_field(
			'laycarousel_showCircles',
			'Show Circles',
			array($this, 'laycarousel_showCircles_setting'),
			'manage-laycarousel',
			'laycarouselsoptions_circles_section'
		);
		register_setting( 'manage-laycarousel', 'laycarousel_showCircles' );

		add_settings_field(
			'laycarousel_spaceBetweenForCircles',
			'Space Top',
			array($this, 'laycarousel_spaceBetweenForCircles_setting'),
			'manage-laycarousel',
			'laycarouselsoptions_circles_section'
		);
		register_setting( 'manage-laycarousel', 'laycarousel_spaceBetweenForCircles' );

		add_settings_field(
			'laycarousel_spaceBottomCircles',
			'Space Bottom',
			array($this, 'laycarousel_spaceBottomCircles_setting'),
			'manage-laycarousel',
			'laycarouselsoptions_circles_section'
		);
		register_setting( 'manage-laycarousel', 'laycarousel_spaceBottomCircles' );

		// captions
		add_settings_field(
			'laycarousel_showCaptions',
			'Show Slide Captions',
			array($this, 'laycarousel_showCaptions_setting'),
			'manage-laycarousel',
			'laycarouselsoptions_captions_section'
		);
		register_setting( 'manage-laycarousel', 'laycarousel_showCaptions' );

		add_settings_field(
			'laycarousel_captionsPosition',
			'Position',
			array($this, 'laycarousel_captionsPosition_setting'),
			'manage-laycarousel',
			'laycarouselsoptions_captions_section'
		);
		register_setting( 'manage-laycarousel', 'laycarousel_captionsPosition' );

		add_settings_field(
			'laycarousel_captionTextformat',
			'Textformat',
			array($this, 'laycarousel_captionTextformat_setting'),
			'manage-laycarousel',
			'laycarouselsoptions_captions_section'
		);
		register_setting( 'manage-laycarousel', 'laycarousel_captionTextformat' );

		add_settings_field(
			'laycarousel_spaceBetween',
			'Space Top',
			array($this, 'laycarousel_spaceBetween_setting'),
			'manage-laycarousel',
			'laycarouselsoptions_captions_section'
		);
		register_setting( 'manage-laycarousel', 'laycarousel_spaceBetween' );

		add_settings_field(
			'laycarousel_spaceBottomCaptions',
			'Space Bottom',
			array($this, 'laycarousel_spaceBottomCaptions_setting'),
			'manage-laycarousel',
			'laycarouselsoptions_captions_section'
		);
		register_setting( 'manage-laycarousel', 'laycarousel_spaceBottomCaptions' );

		add_settings_field(
			'laycarousel_caption_space_left',
			'Space Left',
			array($this, 'laycarousel_caption_space_left_setting'),
			'manage-laycarousel',
			'laycarouselsoptions_captions_section'
		);
		register_setting( 'manage-laycarousel', 'laycarousel_caption_space_left' );

		add_settings_field(
			'laycarousel_caption_space_right',
			'Space Right',
			array($this, 'laycarousel_caption_space_right_setting'),
			'manage-laycarousel',
			'laycarouselsoptions_captions_section'
		);
		register_setting( 'manage-laycarousel', 'laycarousel_caption_space_right' );
		// 

		// numbers
		add_settings_field(
			'laycarousel_showNumbers',
			'Show Numbers',
			array($this, 'laycarousel_showNumbers_setting'),
			'manage-laycarousel',
			'laycarouselsoptions_numbers_section'
		);
		register_setting( 'manage-laycarousel', 'laycarousel_showNumbers' );

		add_settings_field(
			'laycarousel_numbersPosition',
			'Position',
			array($this, 'laycarousel_numbersPosition_setting'),
			'manage-laycarousel',
			'laycarouselsoptions_numbers_section'
		);
		register_setting( 'manage-laycarousel', 'laycarousel_numbersPosition' );

		add_settings_field(
			'laycarousel_numberTextformat',
			'Textformat',
			array($this, 'laycarousel_numberTextformat_setting'),
			'manage-laycarousel',
			'laycarouselsoptions_numbers_section'
		);
		register_setting( 'manage-laycarousel', 'laycarousel_numberTextformat' );

		add_settings_field(
			'laycarousel_spaceBetweenForNumbers',
			'Space Top',
			array($this, 'laycarousel_spaceBetweenForNumbers_setting'),
			'manage-laycarousel',
			'laycarouselsoptions_numbers_section'
		);
		register_setting( 'manage-laycarousel', 'laycarousel_spaceBetweenForNumbers' );

		add_settings_field(
			'laycarousel_spaceBottomNumbers',
			'Space Bottom',
			array($this, 'laycarousel_spaceBottomNumbers_setting'),
			'manage-laycarousel',
			'laycarouselsoptions_numbers_section'
		);
		register_setting( 'manage-laycarousel', 'laycarousel_spaceBottomNumbers' );

		add_settings_field(
			'laycarousel_numbers_space_left',
			'Space Left',
			array($this, 'laycarousel_numbers_space_left_setting'),
			'manage-laycarousel',
			'laycarouselsoptions_numbers_section'
		);
		register_setting( 'manage-laycarousel', 'laycarousel_numbers_space_left' );

		add_settings_field(
			'laycarousel_numbers_space_right',
			'Space Right',
			array($this, 'laycarousel_numbers_space_right_setting'),
			'manage-laycarousel',
			'laycarouselsoptions_numbers_section'
		);
		register_setting( 'manage-laycarousel', 'laycarousel_numbers_space_right' );
		// 

		// prev/next button
		add_settings_field(
			'laycarousel_showArrowButtons',
			'Show Buttons',
			array($this, 'laycarousel_showArrowButtons_setting'),
			'manage-laycarousel',
			'laycarouselsoptions_buttons_section'
		);
		register_setting( 'manage-laycarousel', 'laycarousel_showArrowButtons' );

		add_settings_field(
			'laycarousel_showArrowButtonsForTouchDevices',
			'Show Buttons on Touchdevices',
			array($this, 'laycarousel_showArrowButtonsForTouchDevices_setting'),
			'manage-laycarousel',
			'laycarouselsoptions_buttons_section'
		);
		register_setting( 'manage-laycarousel', 'laycarousel_showArrowButtonsForTouchDevices' );

		add_settings_field(
			'laycarousel_rightbutton',
			'Custom "Next" Button',
			array($this, 'laycarousel_rightbutton_setting'),
			'manage-laycarousel',
			'laycarouselsoptions_buttons_section'
		);
		register_setting( 'manage-laycarousel', 'laycarousel_rightbutton' );

		add_settings_field(
			'laycarousel_altrightbutton',
			'Custom Alternative "Next" Button',
			array($this, 'laycarousel_altrightbutton_setting'),
			'manage-laycarousel',
			'laycarouselsoptions_buttons_section'
		);
		register_setting( 'manage-laycarousel', 'laycarousel_altrightbutton' );

		add_settings_field(
			'laycarousel_buttonspace',
			'Space between buttons and edge of carousel',
			array($this, 'laycarousel_buttonspace_setting'),
			'manage-laycarousel',
			'laycarouselsoptions_buttons_section'
		);
		register_setting( 'manage-laycarousel', 'laycarousel_buttonspace' );

	}

	public function laycarousel_showArrowButtonsForTouchDevices_setting(){
		$val = get_option( 'laycarousel_showArrowButtonsForTouchDevices', "" );
		$checked = "";
		if( $val == "on" ){
			$checked = "checked";
		}
		echo '<input type="checkbox" name="laycarousel_showArrowButtonsForTouchDevices" id="laycarousel_showArrowButtonsForTouchDevices" '.$checked.'>';
	}

	public function laycarousel_showArrowButtons_setting(){
		$val = get_option( 'laycarousel_showArrowButtons', "" );
		$checked = "";
		if( $val == "on" ){
			$checked = "checked";
		}
		echo '<input type="checkbox" name="laycarousel_showArrowButtons" id="laycarousel_showArrowButtons" '.$checked.'>';
	}

	public function laycarousel_arrowright_setting(){
		LayThemeCarouselSettings::laycarousel_arrow_setting('laycarousel_arrowright');
	}

	public function laycarousel_arrowleft_setting(){
		LayThemeCarouselSettings::laycarousel_arrow_setting('laycarousel_arrowleft');
	}

	public function laycarousel_rightbutton_setting(){
		LayThemeCarouselSettings::laycarousel_button_setting('laycarousel_rightbutton');
	}
	
	public function laycarousel_altrightbutton_setting(){
		LayThemeCarouselSettings::laycarousel_button_setting('laycarousel_altrightbutton');
	}

	public static function laycarousel_button_setting($option_name){
		// https://gist.github.com/hlashbrooke/9267467#file-class-php-L324
		$image_thumb_id = get_option( $option_name, '' );
		$image_thumb = "";
		$noimage_image = $image_thumb;
		$hideRemoveButtonCSS = '';
		if($image_thumb_id != ''){
			$image_thumb = wp_get_attachment_image_src( $image_thumb_id, 'full' );
			$image_thumb = $image_thumb[0];
		}
		else{
			$hideRemoveButtonCSS = 'style="display:none;"';
		}
		echo 
		'<img id="'.$option_name.'_preview" style="max-width: 200px;" class="image_preview" data-noimagesrc="'.$noimage_image.'" src="'.$image_thumb.'">
		<p style="margin-bottom: 10px;">"Previous" button will be the mirrored version of this</p>
		<input id="'.$option_name.'_button" style="margin-bottom: 5px;" type="button" class="image_upload_button button" value="Set image" /><br>
		<input id="'.$option_name.'_delete" '.$hideRemoveButtonCSS.' type="button" class="image_delete_button button" value="Remove image" /><br>
		<input id="'.$option_name.'" class="image_data_field" type="hidden" name="'.$option_name.'" value="'.$image_thumb_id.'"/>';
	}

	public static function laycarousel_arrow_setting($option_name){
		// https://gist.github.com/hlashbrooke/9267467#file-class-php-L324
		$image_thumb_id = get_option( $option_name, '' );
		$image_thumb = "";
		$noimage_image = $image_thumb;
		$hideRemoveButtonCSS = '';
		if($image_thumb_id != ''){
			$image_thumb = wp_get_attachment_image_src( $image_thumb_id, 'full' );
			$image_thumb = $image_thumb[0];
		}
		else{
			$hideRemoveButtonCSS = 'style="display:none;"';
		}
		echo 
		'<img id="'.$option_name.'_preview" style="max-width: 200px;" class="image_preview" data-noimagesrc="'.$noimage_image.'" src="'.$image_thumb.'">
		<p style="margin-bottom: 10px;">Max. size: 128 * 128 px</p>
		<input id="'.$option_name.'_button" style="margin-bottom: 5px;" type="button" class="image_upload_button button" value="Set image" /><br>
		<input id="'.$option_name.'_delete" '.$hideRemoveButtonCSS.' type="button" class="image_delete_button button" value="Remove image" /><br>
		<input id="'.$option_name.'" class="image_data_field" type="hidden" name="'.$option_name.'" value="'.$image_thumb_id.'"/>';
	}

	public function laycarousel_buttonspace_setting(){
		$val = get_option( 'laycarousel_buttonspace', "10" );
		echo '<input type="number" value="'.$val.'" min="0" step="1" name="laycarousel_buttonspace" id="laycarousel_buttonspace"> <label>px</label>';	
	}

	public function laycarousel_textspace_setting(){
		$val = get_option( 'laycarousel_textspace', "0" );
		echo '<input type="number" value="'.$val.'" step="1" min="0" name="laycarousel_textspace" id="laycarousel_textspace"> <label>px</label>';	
	}	

	// space bottom captions
	public function laycarousel_spaceBottomCaptions_setting(){
		$val = get_option( 'laycarousel_spaceBottomCaptions', "0" );
		echo '<input type="number" value="'.$val.'" min="0" step="1" name="laycarousel_spaceBottomCaptions" id="laycarousel_spaceBottomCaptions"> px';		
	}

	// space top captions
	public function laycarousel_spaceBetween_setting(){
		$val = get_option( 'laycarousel_spaceBetween', "0" );
		echo '<input type="number" value="'.$val.'" min="0" step="1" name="laycarousel_spaceBetween" id="laycarousel_spaceBetween"> px';		
	}

	// space left captions
	public function laycarousel_caption_space_left_setting(){
		$val = get_option( 'laycarousel_caption_space_left', "0" );
		echo '<input type="number" value="'.$val.'" min="0" step="1" name="laycarousel_caption_space_left" id="laycarousel_caption_space_left"> px';		
	}

	// space right captions
	public function laycarousel_caption_space_right_setting(){
		$val = get_option( 'laycarousel_caption_space_right', "0" );
		echo '<input type="number" value="'.$val.'" min="0" step="1" name="laycarousel_caption_space_right" id="laycarousel_caption_space_right"> px';		
	}

	// space bottom
	public function laycarousel_spaceBottomCircles_setting(){
		$val = get_option( 'laycarousel_spaceBottomCircles', "0" );
		echo '<input type="number" value="'.$val.'" min="0" step="1" name="laycarousel_spaceBottomCircles" id="laycarousel_spaceBottomCircles"> px';	 		
	}

	public function laycarousel_spaceBetweenForCircles_setting(){
		$val = get_option( 'laycarousel_spaceBetweenForCircles', "10" );
		echo '<input type="number" value="'.$val.'" min="0" step="1" name="laycarousel_spaceBetweenForCircles" id="laycarousel_spaceBetweenForCircles"> px';	 		
	}

	// space bottom
	public function laycarousel_spaceBottomNumbers_setting(){
		$val = get_option( 'laycarousel_spaceBottomNumbers', "0" );
		echo '<input type="number" value="'.$val.'" min="0" step="1" name="laycarousel_spaceBottomNumbers" id="laycarousel_spaceBottomNumbers"> px';	 		

	}

	// Space Top
	public function laycarousel_spaceBetweenForNumbers_setting(){
		$val = get_option( 'laycarousel_spaceBetweenForNumbers', "0" );
		echo '<input type="number" value="'.$val.'" min="0" step="1" name="laycarousel_spaceBetweenForNumbers" id="laycarousel_spaceBetweenForNumbers"> px';	 		
	}

	// Space Left
	public function laycarousel_numbers_space_left_setting(){
		$val = get_option( 'laycarousel_numbers_space_left', "0" );
		echo '<input type="number" value="'.$val.'" min="0" step="1" name="laycarousel_numbers_space_left" id="laycarousel_numbers_space_left"> px';	 		
	}

	// Space Right
	public function laycarousel_numbers_space_right_setting(){
		$val = get_option( 'laycarousel_numbers_space_right', "0" );
		echo '<input type="number" value="'.$val.'" min="0" step="1" name="laycarousel_numbers_space_right" id="laycarousel_numbers_space_right"> px';	 		
	}

	public function laycarousel_numbersPosition_setting(){
		$val = get_option( 'laycarousel_numbersPosition', "right" );

		$selectedLeft = '';
		$selectedCenter = '';
		$selectedRight = '';

		switch ($val) {
			case 'left':
				$selectedLeft = 'selected';
			break;
			case 'center':
				$selectedCenter = 'selected';
			break;
			case 'right':
				$selectedRight = 'selected';
			break;
		}

		echo 
		'<select name="laycarousel_numbersPosition" id="laycarousel_numbersPosition">
			<option value="left" '.$selectedLeft.'>left</option>
			<option value="center" '.$selectedCenter.'>center</option>
			<option value="right" '.$selectedRight.'>right</option>
		</select>';  		
	}

	public function laycarousel_captionsPosition_setting(){
		$val = get_option( 'laycarousel_captionsPosition', "left" );

		$selectedLeft = '';
		$selectedCenter = '';
		$selectedRight = '';

		switch ($val) {
			case 'left':
				$selectedLeft = 'selected';
			break;
			case 'center':
				$selectedCenter = 'selected';
			break;
			case 'right':
				$selectedRight = 'selected';
			break;
		}

		echo 
		'<select name="laycarousel_captionsPosition" id="laycarousel_captionsPosition">
			<option value="left" '.$selectedLeft.'>left</option>
			<option value="center" '.$selectedCenter.'>center</option>
			<option value="right" '.$selectedRight.'>right</option>
		</select>'; 		
	}

	public function laycarousel_showNumbers_setting(){
		$val = get_option( 'laycarousel_showNumbers', "" );
		$checked = "";
		if( $val == "on" ){
			$checked = "checked";
		}
		echo '<input type="checkbox" name="laycarousel_showNumbers" id="laycarousel_showNumbers" '.$checked.'><label for="laycarousel_showNumbers"> (for example "1/4")</label>'; 		
	}

	public function laycarousel_showcircles_setting(){
		$val = get_option( 'laycarousel_showCircles', "" );
		$checked = "";
		if( $val == "on" ){
			$checked = "checked";
		}
		echo '<input type="checkbox" name="laycarousel_showCircles" id="laycarousel_showCircles" '.$checked.'><label for="laycarousel_showCircles"> (Uncheck this to be able to show "Captions" and "Numbers")</label>';
	}

	public function laycarousel_captionTextformat_setting(){
		// display a selectable list of textformats
		$val = get_option( 'laycarousel_captionTextformat', "Default" );

		echo '<select name="laycarousel_captionTextformat" id="laycarousel_captionTextformat">';
		foreach (Customizer::$textformatsSelect as $format) {
			$selected = ($val == $format) ? 'selected' : '';
			echo '<option value="'.$format.'" '.$selected.'>'.$format.'</option>';
		}	
		echo '</select>'; 
	}

	public function laycarousel_numberTextformat_setting(){
		// display a selectable list of textformats
		$val = get_option( 'laycarousel_numberTextformat', "Default" );

		echo '<select name="laycarousel_numberTextformat" id="laycarousel_numberTextformat">';
		foreach (Customizer::$textformatsSelect as $format) {
			$selected = ($val == $format) ? 'selected' : '';
			echo '<option value="'.$format.'" '.$selected.'>'.$format.'</option>';
		}	
		echo '</select>'; 
	}

	public function laycarousel_showCaptions_setting(){
		$val = get_option( 'laycarousel_showCaptions', "" );
		$checked = "";
		if( $val == "on" ){
			$checked = "checked";
		}
		echo '<input type="checkbox" name="laycarousel_showCaptions" id="laycarousel_showCaptions" '.$checked.'>';
	}

	public function laycarousel_autoplaySpeed_setting(){
		$val = get_option( 'laycarousel_autoplaySpeed', "3000" );
		echo '<input type="number" value="'.$val.'" min="0" step="1" name="laycarousel_autoplaySpeed" id="laycarousel_autoplaySpeed"> <label for="laycarousel_autoplaySpeed"> milliseconds</label>';		
	}

	public function laycarousel_pauseAutoplayOnHover_setting(){
		$val = get_option( 'laycarousel_pauseAutoplayOnHover', "on" );
		$checked = "";
		if( $val == "on" ){
			$checked = "checked";
		}
		echo '<input type="checkbox" name="laycarousel_pauseAutoplayOnHover" id="laycarousel_pauseAutoplayOnHover" '.$checked.'>';
	}

	public function laycarousel_loop_setting(){
		$val = get_option( 'laycarousel_loop', "on" );
		$checked = "";
		if( $val == "on" ){
			$checked = "checked";
		}
		echo '<input type="checkbox" name="laycarousel_loop" id="laycarousel_loop" '.$checked.'>';
	}

	public function laycarousel_mousecursor_setting(){
		$val = get_option( 'laycarousel_mousecursor', "leftright" );

		$selectedLeftRight = '';
		$selectedRight = '';
		$selectedNone = '';
		$selectedGrab = '';
		$selectedPointer = '';

		switch ($val) {
			case 'leftright':
				$selectedLeftRight = 'selected';
			break;
			case 'right':
				$selectedRight = 'selected';
			break;
			case 'none':
				$selectedNone = 'selected';
			break;
			case 'grab':
				$selectedGrab = 'selected';
			break;
			case 'pointer':
				$selectedPointer = 'selected';
			break;
		}

		echo 
		'<select style="width:auto;" name="laycarousel_mousecursor" id="laycarousel_mousecursor">
			<option value="leftright" '.$selectedLeftRight.'>"Previous" and "Next" Cursors</option>
			<option value="right" '.$selectedRight.'>Only "Next" Cursor</option>
			<option value="grab" '.$selectedGrab.'>"Grab" Hand Cursor</option>
			<option value="pointer" '.$selectedPointer.'>"Pointer" Hand Cursor</option>
			<option value="none" '.$selectedNone.'>No Cursor</option>
		</select>'; 		
	}

	public function laycarousel_sink_position_setting(){
		$val = get_option( 'laycarousel_sink_position', "below" );

		$selectedBelow = 'selected';
		$selectedOntop = '';

		switch ($val) {
			case 'below':
				$selectedBelow = 'selected';
				$selectedOntop = '';
			break;
			case 'ontop':
				$selectedBelow = '';
				$selectedOntop = 'selected';
			break;
		}

		echo 
		'<select style="width:auto;" name="laycarousel_sink_position" id="laycarousel_sink_position">
			<option value="below" '.$selectedBelow.'>Below Carousel</option>
			<option value="ontop" '.$selectedOntop.'>On top of Carousel</option>
		</select>';	
	}

	public function laycarousel_transition_setting(){
		$val = get_option( 'laycarousel_transition', "sliding" );

		$selectedSliding = 'selected';
		$selectedFading = '';
		$selectedImmediate = '';

		switch ($val) {
			case 'fading':
				$selectedSliding = '';
				$selectedFading = 'selected';
				$selectedImmediate = '';
			break;
			case 'immediate':
				$selectedSliding = '';
				$selectedFading = '';
				$selectedImmediate = 'selected';
			break;
		}

		echo 
		'<select style="width:auto;" name="laycarousel_transition" id="laycarousel_transition">
			<option value="sliding" '.$selectedSliding.'>Slide (swipeable)</option>
			<option value="fading" '.$selectedFading.'>Fade</option>
			<option value="immediate" '.$selectedImmediate.'>No transition</option>
		</select>';
	}

	public function laycarousel_lazyload_setting(){
		$val = get_option( 'laycarousel_lazyload', "on" );
		$checked = "";
		if( $val == "on" ){
			$checked = "checked";
		}
		echo '<input type="checkbox" name="laycarousel_lazyload" id="laycarousel_lazyload" '.$checked.'> <label for="laycarousel_lazyload">(Lazyload images when you scroll to them and lazyload non-visible images. Recommended for quicker loading time of your page.)</label>';
	}

	public function laycarousel_align_sink_setting(){
		$val = get_option( 'laycarousel_align_sink', "" );
		$checked = "";
		if( $val == "on" ){
			$checked = "checked";
		}
		echo '<input type="checkbox" name="laycarousel_align_sink" id="laycarousel_align_sink" '.$checked.'>';		
	}

	public function laycarousel_alignment_setting(){
		$val = get_option( 'laycarousel_alignment', "bottom" );
		
		$selectedTop = '';
		$selectedMiddle = '';
		$selectedBottom = 'selected';

		switch ($val) {
			case 'top':
				$selectedTop = 'selected';
				$selectedMiddle = '';
				$selectedBottom = '';
			break;
			case 'middle':
				$selectedTop = '';
				$selectedMiddle = 'selected';
				$selectedBottom = '';
			break;
		}

		echo 
		'<select name="laycarousel_alignment" id="laycarousel_alignment">
			<option value="top" '.$selectedTop.'>top</option>
			<option value="middle" '.$selectedMiddle.'>middle</option>
			<option value="bottom" '.$selectedBottom.'>bottom</option>
		</select>';
	}

	public function laycarousel_text_alignment_setting(){
		$val = get_option( 'laycarousel_text_alignment', "middle" );
		
		$selectedTop = '';
		$selectedMiddle = 'selected';
		$selectedBottom = '';

		switch ($val) {
			case 'top':
				$selectedTop = 'selected';
				$selectedMiddle = '';
				$selectedBottom = '';
			break;
			case 'bottom':
				$selectedTop = '';
				$selectedMiddle = '';
				$selectedBottom = 'selected';
			break;
		}

		echo 
		'<select name="laycarousel_text_alignment" id="laycarousel_text_alignment">
			<option value="top" '.$selectedTop.'>top</option>
			<option value="middle" '.$selectedMiddle.'>middle</option>
			<option value="bottom" '.$selectedBottom.'>bottom</option>
		</select>';
	}
}

new LayThemeCarouselSettings();