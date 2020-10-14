<?php
// http://codex.wordpress.org/Settings_API#Examples
class MiscOptions{

	public static $phoneLayoutActive;
	public static $phone_breakpoint;
	public static $tablet_breakpoint;

	public function __construct(){
		add_action( 'admin_menu', array($this, 'misc_options_setup_menu'), 10 );
		add_action( 'admin_init', array($this, 'misc_options_settings_api_init') );
		add_action( 'admin_enqueue_scripts', array($this, 'enqueue_scripts_for_misc') );
		add_action( 'wp_head', array($this, 'misc_options_css') );
		add_action( 'admin_head', array($this, 'misc_options_css_for_admin'));

		MiscOptions::$phoneLayoutActive = get_option('misc_options_extra_gridder_for_phone', '');
		if(MiscOptions::$phoneLayoutActive == "on"){
			MiscOptions::$phoneLayoutActive = true;
		}else{
			MiscOptions::$phoneLayoutActive = false;
		}

		MiscOptions::$phone_breakpoint = get_option('lay_breakpoint', 600);
		MiscOptions::$phone_breakpoint = (int)MiscOptions::$phone_breakpoint;
		MiscOptions::$tablet_breakpoint = get_option( 'misc_options_textformats_tablet_breakpoint', 1024 );
		MiscOptions::$tablet_breakpoint = (int)MiscOptions::$tablet_breakpoint;
	}

	public static function misc_options_css_for_admin(){
		$screen = get_current_screen();
		if ( $screen->id == 'edit-category' || $screen->id == 'post' || $screen->id == 'page' ) {
			MiscOptions::horizontal_lines_css();
		}
	}

	public static function misc_options_css(){
		$placeholder_color = get_option( 'misc_options_image_placeholder_color', '' );
		if($placeholder_color != ""){
			echo
			'<!-- image placeholder background color -->
			<style>
				.col[data-type="img"] .ph{
				    background-color: '.$placeholder_color.';
				}
			</style>';
		}
		MiscOptions::horizontal_lines_css();
	}

	public static function horizontal_lines_css(){
		// horizontal lines
		$hr_height = get_option( 'misc_options_hr_height', '1' );
		$hr_color = get_option( 'misc_options_hr_color', '#000000' );
		echo
		'<!-- horizontal lines -->
		<style>
			.lay-hr{
				height:'.$hr_height.'px;
				background-color:'.$hr_color.';
			}
		</style>';
	}

	public function misc_options_setup_menu(){
		// http://wordpress.stackexchange.com/questions/66498/add-menu-page-with-different-name-for-first-submenu-item
		add_menu_page( 'Lay Options', 'Lay Options', 'manage_options', 'manage-layoptions', '' );

        add_submenu_page( 'manage-layoptions', 'Lay Options', 'Lay Options', 'manage_options', 'manage-layoptions', array($this, 'misc_options_markup') );
	}

	public function misc_options_markup(){
		require_once( Setup::$dir.'/options/misc_options_markup.php' );
	}

	public function enqueue_scripts_for_misc($hook){
		if( $hook == 'toplevel_page_manage-layoptions' ){
			wp_enqueue_media();

			wp_enqueue_style( 'wp-color-picker' );
	        wp_enqueue_script( 'misc_options-colorpicker_controller', Setup::$uri.'/options/assets/js/misc_colorpicker_controller.js', array( 'wp-color-picker' ), Setup::$ver, true );

			wp_enqueue_script( 'misc_options-image_upload', Setup::$uri.'/options/assets/js/image_upload.js', array(), Setup::$ver );
			wp_enqueue_script( 'misc_options-settings_showhide', Setup::$uri.'/options/assets/js/misc_options_showhide_settings.js', array(), Setup::$ver );
		}
	}

	public function misc_options_settings_api_init(){
	 	add_settings_section(
			'extra_features_section',
			'Extra Features',
			'',
			'manage-miscoptions'
		);

	 	add_settings_section(
			'textformats_settings_section',
			'Textformats Settings',
			'',
			'manage-miscoptions'
		);

		add_settings_section(
			'lazyloading_section',
			'Lazy Loading',
			'',
			'manage-miscoptions'
		);

 	 	add_settings_section(
 			'images_section',
 			'Images',
 			'',
 			'manage-miscoptions'
 		);

	 	add_settings_section(
			'appearance_section',
			'Appearance',
			'',
			'manage-miscoptions'
		);

		// yoast
		$yoast_message = '';
		if ( is_plugin_active('wordpress-seo/wp-seo.php') ) {
			$yoast_message = '<br><br><div style="line-height: 1.2;">Yoast SEO Plugin Warning!<br>Because you have Yoast activated, none of these Meta Tags will be used. Instead, Yoast\'s Meta Tags will be used.</div>';
		}
	 	add_settings_section(
			'meta_section',
			'Meta '.$yoast_message,
			'',
			'manage-miscoptions'
		);

	 	add_settings_section(
			'hr_section',
			'Horizontal Lines',
			'',
			'manage-miscoptions'
		);

	 	add_settings_section(
			'other_section',
			'Other',
			'',
			'manage-miscoptions'
		);

		// extra features
		add_settings_field(
			'misc_options_menu_amount',
			'Menu Amount',
			array($this, 'misc_setting_menu_amount'),
			'manage-miscoptions',
			'extra_features_section'
		);
		register_setting( 'manage-miscoptions', 'misc_options_menu_amount' );

 	 	add_settings_field(
 			'misc_options_extra_gridder_for_phone',
 			'Activate custom phone layouts',
 			array($this, 'misc_setting_extra_gridder_for_phone'),
 			'manage-miscoptions',
 			'extra_features_section'
 		);
 	 	register_setting( 'manage-miscoptions', 'misc_options_extra_gridder_for_phone' );

 	 	add_settings_field(
			'misc_options_element_transition_on_scroll',
			'Activate on scroll element transitions',
			array($this, 'misc_setting_element_transition_on_scroll'),
			'manage-miscoptions',
			'extra_features_section'
		);
		 register_setting( 'manage-miscoptions', 'misc_options_element_transition_on_scroll' );


 	 	add_settings_field(
 			'misc_options_show_project_arrows',
 			'Show arrows in projects for navigation',
 			array($this, 'misc_setting_show_project_arrows'),
 			'manage-miscoptions',
 			'extra_features_section'
 		);
 	 	register_setting( 'manage-miscoptions', 'misc_options_show_project_arrows' );

 	 	add_settings_field(
 			'misc_options_prevnext_navigate_through',
 			'Next & previous project links navigate through:',
 			array($this, 'misc_setting_prevnext_navigate_through'),
 			'manage-miscoptions',
 			'extra_features_section'
 		);
 	 	register_setting( 'manage-miscoptions', 'misc_options_prevnext_navigate_through' );

 	 	add_settings_field(
 			'misc_options_activate_project_description',
 			'Activate project descriptions for thumbnails',
 			array($this, 'misc_setting_activate_project_description'),
 			'manage-miscoptions',
 			'extra_features_section'
 		);
 	 	register_setting( 'manage-miscoptions', 'misc_options_activate_project_description' );

 	 	add_settings_field(
 			'misc_options_thumbnail_mouseover_image',
 			'Activate mouseover image for thumbnails',
 			array($this, 'misc_setting_thumbnail_mouseover_image'),
 			'manage-miscoptions',
 			'extra_features_section'
 		);
 	 	register_setting( 'manage-miscoptions', 'misc_options_thumbnail_mouseover_image' );

 	 	add_settings_field(
 			'misc_options_thumbnail_video',
 			'Activate video for thumbnails',
 			array($this, 'misc_setting_thumbnail_video'),
 			'manage-miscoptions',
 			'extra_features_section'
 		);
 	 	register_setting( 'manage-miscoptions', 'misc_options_thumbnail_video' );

 	 	add_settings_field(
 			'misc_options_cover',
 			'Activate Cover Feature',
 			array($this, 'misc_setting_cover'),
 			'manage-miscoptions',
 			'extra_features_section'
 		);
 	 	register_setting( 'manage-miscoptions', 'misc_options_cover' );

 	 	add_settings_field(
 			'misc_options_intro',
 			'Activate Intro Feature',
 			array($this, 'misc_setting_intro'),
 			'manage-miscoptions',
 			'extra_features_section'
 		);
 	 	register_setting( 'manage-miscoptions', 'misc_options_intro' );

 	 	add_settings_field(
 			'misc_options_simple_parallax',
 			'Activate Simple Parallax',
 			array($this, 'misc_setting_simple_parallax'),
 			'manage-miscoptions',
 			'extra_features_section'
 		);
		register_setting( 'manage-miscoptions', 'misc_options_simple_parallax' );

 	 	// textformats settings section
 	 	add_settings_field(
 			'misc_options_textformats_for_tablet',
 			'Add "Tablet" settings to Textformats',
 			array($this, 'textformats_for_tablet_setting'),
 			'manage-miscoptions',
 			'textformats_settings_section'
 		);
 	 	register_setting( 'manage-miscoptions', 'misc_options_textformats_for_tablet' );

 	 	add_settings_field(
 			'misc_options_textformats_tablet_breakpoint',
 			'Tablet Breakpoint for Textformats',
 			array($this, 'textformats_tablet_breakpoint_setting'),
 			'manage-miscoptions',
 			'textformats_settings_section'
 		);
 	 	register_setting( 'manage-miscoptions', 'misc_options_textformats_tablet_breakpoint' );

		// lazy loading section
		add_settings_field(
			'misc_options_image_loading',
			'Use Lazy Loading',
			array($this, 'misc_setting_image_loading'),
			'manage-miscoptions',
			'lazyloading_section'
		);
		 register_setting( 'manage-miscoptions', 'misc_options_image_loading' );

 	 	// images section
 	 	add_settings_field(
 			'misc_options_image_placeholder_color',
 			'Image Placeholder Background Color',
 			array($this, 'misc_setting_image_placeholder_color'),
 			'manage-miscoptions',
 			'images_section'
 		);
 	 	register_setting( 'manage-miscoptions', 'misc_options_image_placeholder_color' );

 	 	add_settings_field(
 			'misc_options_image_quality',
 			'Image Quality (.jpeg)',
 			array($this, 'misc_setting_image_quality'),
 			'manage-miscoptions',
 			'images_section'
 		);
 	 	register_setting( 'manage-miscoptions', 'misc_options_image_quality' );

 	 	add_settings_field(
 			'misc_options_showoriginalimages',
 			'Never show resized versions of your images',
 			array($this, 'misc_setting_showoriginalimages'),
 			'manage-miscoptions',
 			'images_section'
 		);
 	 	register_setting( 'manage-miscoptions', 'misc_options_showoriginalimages' );

 	 	// appearance section
 	 	add_settings_field(
 			'lay_breakpoint',
 			'Phone Breakpoint',
 			array($this, 'misc_setting_lay_breakpoint'),
 			'manage-miscoptions',
 			'appearance_section'
 		);
 	 	register_setting( 'manage-miscoptions', 'lay_breakpoint' );

		add_settings_field(
			'lay_tablet_breakpoint',
			'Tablet Breakpoint',
			array($this, 'misc_setting_lay_tablet_breakpoint'),
			'manage-miscoptions',
			'appearance_section'
		);
		register_setting( 'manage-miscoptions', 'lay_tablet_breakpoint' );

 	 	add_settings_field(
 			'misc_options_navigation_transition_duration',
 			'Transition duration when navigating',
 			array($this, 'misc_setting_navigation_transition_duration'),
 			'manage-miscoptions',
 			'appearance_section'
 		);
 	 	register_setting( 'manage-miscoptions', 'misc_options_navigation_transition_duration' );

 	 	add_settings_field(
 			'misc_options_max_width',
 			'Max width of content',
 			array($this, 'misc_setting_max_width'),
 			'manage-miscoptions',
 			'appearance_section'
 		);
 	 	register_setting( 'manage-miscoptions', 'misc_options_max_width' );

 	 	add_settings_field(
 			'misc_options_max_width_apply_to_logo_and_nav',
 			'Apply max width of content to logo and menu',
 			array($this, 'misc_setting_max_width_apply_to_logo_and_nav'),
 			'manage-miscoptions',
 			'appearance_section'
 		);
 	 	register_setting( 'manage-miscoptions', 'misc_options_max_width_apply_to_logo_and_nav' );

 	 	add_settings_field(
 			'misc_options_html5video_playicon',
 			'HTML5 Video Play Icon',
 			array($this, 'misc_setting_html5video_playicon'),
 			'manage-miscoptions',
 			'appearance_section'
 		);
 	 	register_setting( 'manage-miscoptions', 'misc_options_html5video_playicon' );

 	 	// meta section
 	 	add_settings_field(
 			'misc_options_website_description',
 			'Website Description',
 			array($this, 'misc_setting_website_description'),
 			'manage-miscoptions',
 			'meta_section'
 		);
 	 	register_setting( 'manage-miscoptions', 'misc_options_website_description' );

 	 	add_settings_field(
 			'misc_options_fbimage',
 			'Facebook/Twitter Share Image',
 			array($this, 'misc_setting_fbimage'),
 			'manage-miscoptions',
 			'meta_section'
 		);
 	 	register_setting( 'manage-miscoptions', 'misc_options_fbimage' );

 	 	// hr section
 	 	add_settings_field(
 			'misc_options_hr_color',
 			'Horizontal Line Color',
 			array($this, 'misc_setting_hr_color'),
 			'manage-miscoptions',
 			'hr_section'
 		);
 	 	register_setting( 'manage-miscoptions', 'misc_options_hr_color' );

 	 	add_settings_field(
 			'misc_options_hr_height',
 			'Horizontal Line Height',
 			array($this, 'misc_setting_hr_height'),
 			'manage-miscoptions',
 			'hr_section'
 		);
		  register_setting( 'manage-miscoptions', 'misc_options_hr_height' );
		  
		// other
		add_settings_field(
			'misc_options_title_separator',
			'Title Separator',
			array($this, 'misc_setting_title_separator'),
			'manage-miscoptions',
			'other_section'
		);
		register_setting( 'manage-miscoptions', 'misc_options_title_separator' );

		add_settings_field(
			'misc_options_disable_ajax',
			'Disable Ajax / Compatibility Mode',
			array($this, 'misc_setting_disable_ajax'),
			'manage-miscoptions',
			'other_section'
		);
		register_setting( 'manage-miscoptions', 'misc_options_disable_ajax' );

		add_settings_field(
			'misc_options_anchorscroll_offset_desktop',
			'Anchorscroll Space Top for Desktop',
			array($this, 'misc_setting_anchorscroll_offset_desktop'),
			'manage-miscoptions',
			'other_section'
		);
		register_setting( 'manage-miscoptions', 'misc_options_anchorscroll_offset_desktop' );

		add_settings_field(
			'misc_options_anchorscroll_offset_phone',
			'Anchorscroll Space Top for Phone',
			array($this, 'misc_setting_anchorscroll_offset_phone'),
			'manage-miscoptions',
			'other_section'
		);
		register_setting( 'manage-miscoptions', 'misc_options_anchorscroll_offset_phone' );

	}

	public static function misc_setting_anchorscroll_offset_desktop(){
		$val = get_option( 'misc_options_anchorscroll_offset_desktop', 0 );
		echo
		'<input type="number" step="1" value="'.$val.'" name="misc_options_anchorscroll_offset_desktop" id="misc_options_anchorscroll_offset_desktop"> px
		<br><label for="misc_options_anchorscroll_offset_desktop">You click on an anchor that is linked to <code>#hey</code>.
		The page scrolls to an element with <code>id="hey"</code>.<br>
		The offset here is the space between your top browser edge and that div.</label>';
	}

	public static function misc_setting_anchorscroll_offset_phone(){
		$val = get_option( 'misc_options_anchorscroll_offset_phone', 0 );
		echo
		'<input type="number" step="1" value="'.$val.'" name="misc_options_anchorscroll_offset_phone" id="misc_options_anchorscroll_offset_phone"> px
		<br><label for="misc_options_anchorscroll_offset_phone">Same as above but just for when you\'re on the phone version.</label>';
	}

	public static function misc_setting_disable_ajax(){
		$val = get_option( 'misc_options_disable_ajax', 0 );
		$checked = "";
		if( $val == "on" ){
			$checked = "checked";
		}
		echo '<input type="checkbox" name="misc_options_disable_ajax" id="misc_options_disable_ajax" '.$checked.'><br>
		<label for="misc_options_disable_ajax">Activate this setting if you have issues with plugin compatibility.<br>
		When Ajax is disabled, there will be no page transitions. And when going to the frontpage the intro will always re-appear.
		</label>';
	}

	public static function misc_setting_title_separator(){
		$val = get_option( 'misc_options_title_separator', '—' );
		echo '<input type="text" name="misc_options_title_separator" id="misc_options_title_separator" value="'.$val.'"><br>
		Separator for the page titles that are shown in your browser tab. Example: "My Website – My Page". Default is "–".';
	}

	public static function misc_setting_hr_height(){
		$val = get_option( 'misc_options_hr_height', '1' );
		echo '<input type="number" min="1" step="1" name="misc_options_hr_height" id="misc_options_hr_height" value="'.$val.'"/> px';
	}

	public static function misc_setting_hr_color(){
		$val = get_option( 'misc_options_hr_color', '#000000' );
		echo '<input type="text" name="misc_options_hr_color" id="misc_options_hr_color" value="'.$val.'" class="lay-hr-color-picker"><br>
			Find out how to have a different line color for different pages with <a href="http://laytheme.com/documentation.html#css-based-on-current-page" target="_blank">CSS based on current page</a>.<br>
			<code>body.slug-yourslug .lay-hr{ background-color: #f0f; }</code>';
	}

	public static function textformats_for_tablet_setting(){
		$val = get_option( 'misc_options_textformats_for_tablet', "" );
		$checked = "";
		if( $val == "on" ){
			$checked = "checked";
		}
		echo '<input type="checkbox" name="misc_options_textformats_for_tablet" id="misc_options_textformats_for_tablet" '.$checked.'>';
	}

	public static function textformats_tablet_breakpoint_setting(){
		$breakpoint = get_option( 'misc_options_textformats_tablet_breakpoint', 1024 );
		echo
		'<input type="number" min="0" step="1" value="'.$breakpoint.'" name="misc_options_textformats_tablet_breakpoint" id="misc_options_textformats_tablet_breakpoint"> px
		<br><label for="misc_options_textformats_tablet_breakpoint">Breakpoint between desktop and tablet version. Needs to be bigger than "Breakpoint to Phone version".</label>';
	}

	public function misc_setting_intro(){
		$val = get_option('misc_options_intro', 'on');
		$checked = "";
		if( $val == "on" ){
			$checked = "checked";
		}
		echo '<input type="checkbox" name="misc_options_intro" id="misc_options_intro" '.$checked.'>
		<a class="lay-options-doc-link" href="http://laytheme.com/documentation.html#intro-image" title="Documentation on laytheme.com" target="_blank"><span class="dashicons dashicons-editor-help"></span></a>
		<br><label for="misc_options_intro">When activated, find options in "Customizer" &rarr; "Intro".</label>';
	}

	public function misc_setting_cover(){
		$val = get_option('misc_options_cover', '');
		$checked = "";
		if( $val == "on" ){
			$checked = "checked";
		}
		echo '<input type="checkbox" name="misc_options_cover" id="misc_options_cover" '.$checked.'>
		<a class="lay-options-doc-link" href="http://laytheme.com/documentation.html#covers" title="Documentation on laytheme.com" target="_blank"><span class="dashicons dashicons-editor-help"></span></a>
		<br><label for="misc_options_cover">When activated, find options for Covers in "Lay Options" &rarr; "Cover Options".</label>';
	}

	public function misc_setting_showoriginalimages(){
		$val = get_option('misc_options_showoriginalimages', '');
		$checked = "";
		if( $val == "on" ){
			$checked = "checked";
		}
		echo '<input type="checkbox" name="misc_options_showoriginalimages" id="misc_options_showoriginalimages" '.$checked.'><label for="misc_options_showoriginalimages">Normally, when appropriate, Lay Theme uses automatically generated smaller image sizes to speed up loading.<br>
			This setting does not apply to the phone version. Activate this if your images seem blurry.</label>';
	}

	public function misc_setting_simple_parallax(){
		$val = get_option('misc_options_simple_parallax', '');
		$checked = "";
		if( $val == "on" ){
			$checked = "checked";
		}
		echo '<input type="checkbox" name="misc_options_simple_parallax" id="misc_options_simple_parallax" '.$checked.'> <br><label for="misc_options_simple_parallax">Right click on an element in the Gridder and define the "y-parallax" value to change the speed of an element while scrolling.</label>';
	}

	public function misc_setting_max_width_apply_to_logo_and_nav(){
		$val = get_option('misc_options_max_width_apply_to_logo_and_nav', '');
		$checked = "";
		if( $val == "on" ){
			$checked = "checked";
		}
		echo '<input type="checkbox" name="misc_options_max_width_apply_to_logo_and_nav" id="misc_options_max_width_apply_to_logo_and_nav" '.$checked.'><br>
		<label for="misc_options_max_width_apply_to_logo_and_nav">Logo and menu will only align to grids that use the same "Frame Left, Right" value as in "Lay Options" &rarr; "Gridder Defaults".</label>';
	}

	public function misc_setting_element_transition_on_scroll(){
		$val = get_option('misc_options_element_transition_on_scroll', '');
		$checked = "";
		if( $val == "on" ){
			$checked = "checked";
		}
		echo '<input type="checkbox" name="misc_options_element_transition_on_scroll" id="misc_options_element_transition_on_scroll" '.$checked.'>
		<a class="lay-options-doc-link" href="http://laytheme.com/documentation.html#element-transition-on-scroll" title="Documentation on laytheme.com" target="_blank"><span class="dashicons dashicons-editor-help"></span></a>
		<br><label for="misc_options_element_transition_on_scroll">Element will animate in when you scroll to it.</label>';
	}

	public function misc_setting_extra_gridder_for_phone(){
		$val = get_option('misc_options_extra_gridder_for_phone', '');
		$checked = "";
		if( $val == "on" ){
			$checked = "checked";
		}
		echo '<input type="checkbox" name="misc_options_extra_gridder_for_phone" id="misc_options_extra_gridder_for_phone" '.$checked.'>
		<a class="lay-options-doc-link" href="http://laytheme.com/documentation.html#custom-phone-layouts" title="Documentation on laytheme.com" target="_blank"><span class="dashicons dashicons-editor-help"></span></a>
		<br><label for="misc_options_extra_gridder_for_phone">In the Gridder you will have new buttons to switch between the desktop and phone layout.</label>';
	}

	public function misc_setting_menu_amount(){
		$val = intval(get_option('misc_options_menu_amount', 1));

		$selected1 = $val == 1 ? 'selected' : '';
		$selected2 = $val == 2 ? 'selected' : '';
		$selected3 = $val == 3 ? 'selected' : '';
		$selected4 = $val == 4 ? 'selected' : '';

		echo 
		'<select name="misc_options_menu_amount">
			<option value="1" '.$selected1.'>1</option> 
			<option value="2" '.$selected2.'>2</option>
			<option value="3" '.$selected3.'>3</option>
			<option value="4" '.$selected4.'>4</option>
	  	</select>';	
	}

	public function misc_setting_show_project_arrows(){
		$val = get_option('misc_options_show_project_arrows', '');
		$checked = "";
		if( $val == "on" ){
			$checked = "checked";
		}
		echo '<input type="checkbox" name="misc_options_show_project_arrows" id="misc_options_show_project_arrows" '.$checked.'>
		<a class="lay-options-doc-link" href="http://laytheme.com/documentation.html#project-arrows" title="Documentation on laytheme.com" target="_blank"><span class="dashicons dashicons-editor-help"></span></a>
		<br><label for="misc_options_show_project_arrows">In projects on the left and right there will be clickable arrows to go to the previous/next project.<br>In "Customize" when you navigate inside a project you can style the arrows in the "Project Arrows" panel.</label>';
	}

	public function misc_setting_prevnext_navigate_through(){
		$val = get_option('misc_options_prevnext_navigate_through', 'same_category');

		$checked1 = $val == 'same_category' ? 'checked' : '';
		$checked2 = $val == 'all_projects' ? 'checked' : '';

		echo '<input type="radio" name="misc_options_prevnext_navigate_through" id="same_category" value="same_category" '.$checked1.'><label for="same_category">Projects of same Category</label><br>';
		echo '<input type="radio" name="misc_options_prevnext_navigate_through" id="all_projects" value="all_projects" '.$checked2.'><label for="all_projects">All Projects</label>';
	}

	public function misc_setting_image_loading(){
        /* 
		i changed the misc option inputs from radio to checkbox
		this used to be either "instant_load" or "lazy_load"
		but now it can be '' or 'on'
		'on' is when lazyload is turned on, '' is when lazyload is off
		i need to make sure in my code the values 'instant_load' and 'lazy_load' are handled too
		*/
		$val = get_option('misc_options_image_loading', 'instant_load');

		$checked = "";
		if( $val == "on" || $val == "lazy_load" ){
			$checked = "checked";
		}
		echo '<input type="checkbox" name="misc_options_image_loading" id="misc_options_image_loading" '.$checked.'>
		<br><label for="misc_options_image_loading">When lazy loading is on, images and HTML5 videos will be loaded when you scroll to them.<br>
		Recommended for when you have many images and HTML5 videos on a page.</label>';
	}

	public function misc_setting_image_placeholder_color(){
		$val = get_option( 'misc_options_image_placeholder_color', '' );
		echo '<input type="text" name="misc_options_image_placeholder_color" id="misc_options_image_placeholder_color" value="'.$val.'" class="image-placeholder-color-picker"><br><label>When an image has not loaded yet, a placeholder rectangle of this color will be visible.</label>';
	}

	public function misc_setting_thumbnail_video(){
		$val = get_option('misc_options_thumbnail_video', '');
		$checked = "";
		if( $val == "on" ){
			$checked = "checked";
		}
		echo '<input type="checkbox" name="misc_options_thumbnail_video" id="misc_options_thumbnail_video" '.$checked.'>
		<br><label for="misc_options_thumbnail_video">When editing a project you can insert a video as a project thumbnail.</label>';
	}

	public function misc_setting_thumbnail_mouseover_image(){
		$val = get_option('misc_options_thumbnail_mouseover_image', '');
		$checked = "";
		if( $val == "on" ){
			$checked = "checked";
		}
		echo '<input type="checkbox" name="misc_options_thumbnail_mouseover_image" id="misc_options_thumbnail_mouseover_image" '.$checked.'>
		<br><label for="misc_options_thumbnail_mouseover_image">When editing a project you can insert another image that will be shown on mouseover.</label>';
	}

	public function misc_setting_activate_project_description(){
		$val = get_option('misc_options_activate_project_description', '');
		$checked = "";
		if( $val == "on" ){
			$checked = "checked";
		}
		echo '<input type="checkbox" name="misc_options_activate_project_description" id="misc_options_activate_project_description" '.$checked.'>
		<a class="lay-options-doc-link" href="http://laytheme.com/documentation.html#project-description" title="Documentation on laytheme.com" target="_blank"><span class="dashicons dashicons-editor-help"></span></a>
		<br><label for="misc_options_activate_project_description">A new description textfield will appear above the Gridder when you edit projects.<br>Project descriptions will show up underneath a project thumbnail.</label>';
	}

	public function misc_setting_lay_breakpoint(){
		$breakpoint = get_option('lay_breakpoint', 600);
		echo '<input type="number" min="0" step="1" name="lay_breakpoint" id="lay_breakpoint" value="'.$breakpoint.'"> <label for="lay_breakpoint"> px (0 = disable phone version)<br>
		Below this browser-width the phone version is shown.</label>';
	}

	public function misc_setting_lay_tablet_breakpoint(){
		$breakpoint = get_option('lay_tablet_breakpoint', 1024);
		echo '<input type="number" min="0" step="1" name="lay_tablet_breakpoint" id="lay_tablet_breakpoint" value="'.$breakpoint.'"> <label for="lay_breakpoint"> px<br>
		Must be bigger than phone breakpoint. Important for "Thumbnailgrid", "Elementgrid" and "Textformats Tablet Settings".</label>';		
	}

	public function misc_setting_max_width(){
		$maxwidth = get_option( 'misc_options_max_width', '0' );
		echo '<input type="number" min="0" step="1" name="misc_options_max_width" id="misc_options_max_width" value="'.$maxwidth.'"> <label for="misc_options_max_width"> px (0 = off)</label>';
	}

	public function misc_setting_image_quality(){
		$quality = get_option( 'misc_options_image_quality', '90' );
		echo '<input type="number" min="0" max="100" step="1" name="misc_options_image_quality" id="misc_options_image_quality" value="'.$quality.'"> <label for="misc_options_image_quality"> % <br>When you change the quality you need to regenerate all images with a plugin like <a href="https://wordpress.org/plugins/regenerate-thumbnails/" target="_blank">Regenerate Thumbnails</a>.<br>In some cases the original uploaded image will be shown on your website. The original image is not affected by the quality set here.</label>';
	}

	public function misc_setting_navigation_transition_duration(){
		$value = get_option( 'misc_options_navigation_transition_duration', '0.5' );
		echo '<input type="number" min="0" step="0.1" name="misc_options_navigation_transition_duration" id="misc_options_navigation_transition_duration" value="'.$value.'"> <label for="misc_options_navigation_transition_duration"> seconds <br>When you click a menu point the content fades out and the new content fades in. This is the duration of that animation.</label>';
	}

	public function misc_setting_website_description(){
		$description = get_option( 'misc_options_website_description', '' );
		echo
		'<textarea style="max-width: 700px; width: 100%; height: 150px;" name="misc_options_website_description" id="misc_options_website_description">'.$description.'</textarea><br>
		<p>The Website description will show up on Google and when someone shares your website on Facebook or Twitter.</p>';
	}

	public function misc_setting_fbimage(){
		// https://gist.github.com/hlashbrooke/9267467#file-class-php-L324
		$image_thumb_id = get_option( 'misc_options_fbimage', '' );
		$image_thumb = Setup::$uri.'/options/assets/img/noimage.jpg';
		$noimage_image = $image_thumb;
		$hideRemoveButtonCSS = '';
		if($image_thumb_id != ''){
			$image_thumb = wp_get_attachment_image_src( $image_thumb_id, 'full' );
			$image_thumb = $image_thumb[0];
		}
		else{
			$hideRemoveButtonCSS = 'style="display:none;"';
		}
		$option_name = 'misc_options_fbimage';
		echo
		'<img id="'.$option_name.'_preview" style="max-width: 100%;" class="image_preview" data-noimagesrc="'.$noimage_image.'" src="'.$image_thumb.'">
		<p style="margin-bottom: 10px;">This image will show up when someone posts your website on Facebook or Twitter.<br>
		This should be a .jpg with 1200px width and 630px height to look good on Facebook.<br>
		To let Facebook know you are using a new image go <a href="https://developers.facebook.com/tools/debug/og/object/" target="_blank">here</a>, paste your website address, and click "Fetch new scrape information".</p>
		<input id="'.$option_name.'_button" style="margin-bottom: 5px;" type="button" class="image_upload_button button" value="Set image" /><br>
		<input id="'.$option_name.'_delete" '.$hideRemoveButtonCSS.' type="button" class="image_delete_button button" value="Remove image" /><br>
		<input id="'.$option_name.'" class="image_data_field" type="hidden" name="'.$option_name.'" value="'.$image_thumb_id.'"/>';
	}

	public function misc_setting_html5video_playicon(){
		// https://gist.github.com/hlashbrooke/9267467#file-class-php-L324
		$image_thumb_id = get_option( 'misc_options_html5video_playicon', '' );
		$image_thumb = Setup::$uri.'/frontend/assets/img/play.svg';
		$noimage_image = $image_thumb;
		$hideRemoveButtonCSS = '';
		if($image_thumb_id != ''){
			$image_thumb = wp_get_attachment_image_src( $image_thumb_id, 'full' );
			$image_thumb = $image_thumb[0];
		}
		else{
			$hideRemoveButtonCSS = 'style="display:none;"';
		}
		$option_name = 'misc_options_html5video_playicon';
		echo
		'<img id="'.$option_name.'_preview" style="max-width: 100%;" class="image_preview" data-noimagesrc="'.$noimage_image.'" src="'.$image_thumb.'"><br>
		<input id="'.$option_name.'_button" style="margin-bottom: 5px;" type="button" class="image_upload_button button" value="Set image" /><br>
		<input id="'.$option_name.'_delete" '.$hideRemoveButtonCSS.' type="button" class="image_delete_button button" value="Set to default" /><br>
		<input id="'.$option_name.'" class="image_data_field" type="hidden" name="'.$option_name.'" value="'.$image_thumb_id.'"/>';
	}

}
new MiscOptions();
