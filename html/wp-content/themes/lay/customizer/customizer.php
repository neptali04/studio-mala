<?php
// https://make.wordpress.org/core/2014/07/08/customizer-improvements-in-4-0/
class Customizer{

	public static $fonts;
	public static $defaults = array();
	public static $textformatsSelect;
	public static $self;
	public static $projectDescr;

	public function __construct(){
		Customizer::$self = $this;
		Customizer::populateDefaults();
		Customizer::populateTextformatsSelect();

		$val = get_option('misc_options_activate_project_description', '');
		if($val == 'on'){
			Customizer::$projectDescr = true;
		}
		else{
			Customizer::$projectDescr = false;
		}

		add_action( 'customize_register', array($this, 'lay_customize_register'), 10 );

		add_action( 'customize_preview_init', array($this, 'customizer_preview_js') );
		add_action( 'customize_controls_enqueue_scripts', array($this, 'customizer_controls_js') );

		add_action( 'mce_external_plugins', array( $this, 'tinymce_add_setcustomizercss') );
		add_action( 'customize_controls_enqueue_scripts', array( $this, 'customizer_controls_css') );

		$newFonts = array('' => '--SELECT--');
		$customFontsJSON = get_option('fontmanager_json');

		if($customFontsJSON){
			$customFonts = json_decode($customFontsJSON, true);
			if($customFonts){
				for($i=0; $i<count($customFonts); $i++){
					$key = $customFonts[$i]['fontname'];

					if( array_key_exists('type', $customFonts[$i]) &&
						($customFonts[$i]["type"] == "script" || $customFonts[$i]["type"] == "link") ){
						$css = $customFonts[$i]['css'];
						$css = str_replace('font-family: ', '', $css);
						$css = str_replace('font-family:', '', $css);
						$css = str_replace(';', '', $css);
						$newFonts[$css] = $key;
					}
					else{
						$newFonts[$key] = $customFonts[$i]['fontname'];
					}
				}
			}
		}

		$originalFonts = FontManager::$originalFonts;
		$originalFonts = array_flip($originalFonts);

		Customizer::$fonts = array_merge($newFonts, $originalFonts);
	}

	function customizer_controls_js(){
		wp_enqueue_script('lay-showhide_in_controls', Setup::$uri.'/customizer/assets/js/on_sections_panels_showhide_in_controls.js', array('jquery', 'customize-preview'), Setup::$ver);
	}

	function customizer_preview_js(){
		wp_enqueue_script('lay-showhide_in_preview', Setup::$uri.'/customizer/assets/js/on_sections_panels_showhide_in_preview.js', array('jquery', 'customize-preview'), Setup::$ver);
        wp_enqueue_script('lay-customizer', Setup::$uri.'/customizer/assets/js/app.handlers.min.js', array('jquery', 'customize-preview'), Setup::$ver, true);
        $textFormatsJsonString = get_option( 'formatsmanager_json', json_encode(array(FormatsManager::$defaultFormat)) );
		$menu_amount = intval(get_option('misc_options_menu_amount', 1));
		wp_localize_script('lay-customizer', 'layCustomizerPassedData', array('textformats' => $textFormatsJsonString, 'menu_amount' => $menu_amount));
	}

	function customizer_controls_css() {
		//hiding customizer "menu" panel here
	    wp_add_inline_style( 'customize-controls', '.accordion-section-content.description, .customize-panel-description.description { display: block!important; } .control-panel-nav_menus{ display: none!important; }');
		wp_enqueue_style( 'customizer-custom-css', Setup::$uri.'/customizer/assets/css/style.css' );	
	}

	public function tinymce_add_setcustomizercss( $plugins ) {
		$plugins['setcustomizercss'] = Setup::$uri.'/customizer/assets/js/tinymce_set_customizer_css.js';
		return $plugins;
	}

	public static function lay_customize_register($wp_customize){

		$wp_customize->remove_setting('blogname');
		$wp_customize->remove_control('blogname');
		$wp_customize->remove_section('title_tagline');
		$wp_customize->remove_section('nav');
		$wp_customize->remove_section('static_front_page');
		// $wp_customize->remove_section('custom_css');
		// this would cause errors for wordpress 4.4 but would be the preferred method of removing "menus" from the customizer instead of hiding it with css like i do now
		//$wp_customize->remove_panel('nav_menus');

		$wp_customize->add_panel( 'sitetitle_panel', array(
		    'priority'       => 10,
		    'capability'     => 'edit_theme_options',
		    'theme_supports' => '',
		    'title'          => 'Site Title',
		) );

		$wp_customize->add_panel( 'projectlink_panel', array(
		    'priority'       => 20,
		    'capability'     => 'edit_theme_options',
		    'theme_supports' => '',
		    'title'          => 'Project Thumbnails',
		    'description'	 => 'A Project Thumbnail is inserted in the Gridder with "+Project".'
		) );

		$breakpoint = get_option('lay_breakpoint', 600);
		$wp_customize->add_panel( 'mobile_panel', array(
			'priority'       => 25,
			'capability'     => 'edit_theme_options',
			'theme_supports' => '',
			'title'          => 'Mobile (Smartphone)',
		) );

		$wp_customize->add_panel( 'linksintexts_panel', array(
			'priority'       => 30,
			'capability'     => 'edit_theme_options',
			'theme_supports' => '',
			'title'          => 'Links in Texts',
			'description'	 => 'A Link in a text is when you added a Text in the Gridder with "+Text" and it contains a link.'
		) );

		$wp_customize->add_panel( 'lay_css', array(
			'priority'       => 999,
			'capability'     => 'edit_theme_options',
			'theme_supports' => '',
			'title'          => 'CSS',
		) );

		require Setup::$dir.'/customizer/assets/php/controls/category-dropdown-custom-control.php';
		require Setup::$dir.'/customizer/assets/php/controls/post-dropdown-custom-control.php';

		if(LayIntro::$isActive){
			require Setup::$dir.'/customizer/assets/php/intro.php';
		}
		require Setup::$dir.'/customizer/assets/php/projecttitle.php';
		require Setup::$dir.'/customizer/assets/php/project_thumbnail_mouseover.php';
		if(Customizer::$projectDescr){
			require Setup::$dir.'/customizer/assets/php/project_descr.php';
		}
		require Setup::$dir.'/customizer/assets/php/links_in_texts.php';
		require Setup::$dir.'/customizer/assets/php/links_in_texts_mouseover.php';
		require Setup::$dir.'/customizer/assets/php/favicon.php';
		require Setup::$dir.'/customizer/assets/php/sitetitle.php';
		require Setup::$dir.'/customizer/assets/php/sitetitle_mouseover.php';
		require Setup::$dir.'/customizer/assets/php/tagline.php';

		require Setup::$dir.'/customizer/assets/php/menu_bar.php';
		require Setup::$dir.'/customizer/assets/php/menu_active_section.php';
		require Setup::$dir.'/customizer/assets/php/menu_mouseover_section.php';

		require Setup::$dir.'/customizer/assets/php/background.php';
		require Setup::$dir.'/customizer/assets/php/mobile_spaces.php';
		require Setup::$dir.'/customizer/assets/php/mobile_menu_icons.php';
		require Setup::$dir.'/customizer/assets/php/mobile_menu.php';
		require Setup::$dir.'/customizer/assets/php/mobile_menubar.php';
		require Setup::$dir.'/customizer/assets/php/mobile_sitetitle.php';
		require Setup::$dir.'/customizer/assets/php/mobile_background.php';
		require Setup::$dir.'/customizer/assets/php/project_arrows.php';
		require Setup::$dir.'/customizer/assets/php/frontpage.php';

		require Setup::$dir.'/customizer/assets/php/css.php';
	}

	public static function populateTextformatsSelect(){
		$textFormatsJsonString = get_option( 'formatsmanager_json', json_encode(array(FormatsManager::$defaultFormat)) );
		$textFormatsJsonArr = json_decode($textFormatsJsonString, true);
		$result = array('' => '--SELECT--');
		foreach ($textFormatsJsonArr as $value) {
			$result[$value['formatname']] = $value['formatname'];
		}
		Customizer::$textformatsSelect = $result;
	}

	public static function populateDefaults(){

		$formatsJsonString = get_option( 'formatsmanager_json', json_encode(array(FormatsManager::$defaultFormat)) );
		$formatsJsonArr = json_decode($formatsJsonString, true);

		$defaults = null;

		for($i = 0; $i<count($formatsJsonArr); $i++) {
		 	if($formatsJsonArr[$i]["formatname"] == "Default"){
		 		$defaults['color'] = $formatsJsonArr[$i]["color"];
		 		$defaults['fontfamily'] = $formatsJsonArr[$i]["fontfamily"];
		 		$defaults['fontsize'] = $formatsJsonArr[$i]["fontsize"];
		 		$defaults['letterspacing'] = $formatsJsonArr[$i]["letterspacing"];
		 		$defaults['fontweight'] = $formatsJsonArr[$i]["fontweight"];
		 		$defaults['lineheight'] = $formatsJsonArr[$i]["lineheight"];
		 	}
		 }

		if(!is_null($defaults)){
			Customizer::$defaults['color'] = $defaults['color'];
			Customizer::$defaults['fontfamily'] = $defaults['fontfamily'];
			Customizer::$defaults['fontsize'] = $defaults['fontsize'];
			Customizer::$defaults['letterspacing'] = $defaults['letterspacing'];
			Customizer::$defaults['fontweight'] = $defaults['fontweight'];
			Customizer::$defaults['lineheight'] = $defaults['lineheight'];
		}
		else{
			Customizer::$defaults['color'] = FormatsManager::$defaultFormat['color'];
			Customizer::$defaults['fontfamily'] = FormatsManager::$defaultFormat['fontfamily'];
			Customizer::$defaults['fontsize'] = FormatsManager::$defaultFormat['fontsize'];
			Customizer::$defaults['letterspacing'] = FormatsManager::$defaultFormat['letterspacing'];
			Customizer::$defaults['fontweight'] = FormatsManager::$defaultFormat['fontweight'];
			Customizer::$defaults['lineheight'] = FormatsManager::$defaultFormat['lineheight'];
		}


		Customizer::$defaults['underline_width'] = '0';
		Customizer::$defaults['st_spacetop'] = '16';
		Customizer::$defaults['st_spaceleft'] = '5';
		Customizer::$defaults['st_spaceright'] = '5';
		Customizer::$defaults['st_spacebottom'] = '16';
		Customizer::$defaults['st_img_width'] = '10';

		Customizer::$defaults['mobile_menu_txt_color'] = CSS_Output::get_mobile_menu_txt_color();
		Customizer::$defaults['mobile_menu_light_color'] = CSS_Output::get_mobile_menu_light_color();
		Customizer::$defaults['mobile_menu_dark_color'] = CSS_Output::get_mobile_menu_dark_color();

		Customizer::$defaults['mobile_menu_fontsize'] = '15';

		Customizer::$defaults['intro_text_spacetop'] = '5';
		Customizer::$defaults['intro_text_spaceleft'] = '5';
		Customizer::$defaults['intro_text_spaceright'] = '5';
		Customizer::$defaults['intro_text_spacebottom'] = '5';
	}

}
new Customizer();
