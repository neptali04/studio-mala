<?php
class FormatsManager {
	public static $customFormats = false;
	public static $fontWeights = array(
		'Thin (100)' => '100',
		'Extra Light (200)' => '200',
		'Light (300)' => '300',
		'Normal (400)' => '400',
		'Medium (500)' => '500',
		'Semi Bold (600)' => '600',
		'Bold (700)' => '700',
		'Extra Bold (800)' => '800',
		'Black (900)' => '900'
	);
	public static $defaultFormat = array(
		'formatname' => 'Default',
		'type' => 'Paragraph',
		'headlinetype' => 'h1',
		'fontfamily' => 'helvetica,sans-serif',
		'fontsize' => '30',
		'color' => '#000000',
		'letterspacing' => '0',
		'fontweight' => '300',
		'spacebottom' => '20',
		'spacetopmu' => 'px',
		'spacetop' => '0',
		'spacebottommu' => 'px',
		'textalign' => 'left',
		'lineheight' => '1.2',
		'textindent' => '0',
		'caps' => false,
		'italic' => false,
		'underline' => false,
		'borderbottom' => false,
		'tablet-spacetop' => "0",
		'tablet-spacetopmu' => 'px',
		'tablet-spacebottom' => '20',
		'tablet-spacebottommu' => 'px',
		'tablet-fontsizemu' => 'px',
		'tablet-fontsize' => '16',
		'phone-spacetop' => "0",
		'phone-spacetopmu' => 'px',
		'phone-spacebottom' => '20',
		'phone-spacebottommu' => 'px',
		'phone-fontsizemu' => 'px',
		'phone-fontsize' => '16',
	);
	public static $hasTabletSettings = false;

	public static function init(){
		$customFormatsJSON = get_option('formatsmanager_json');
		if ($customFormatsJSON) {
			FormatsManager::$customFormats = json_decode($customFormatsJSON, true);
		}

		$misc_options_textformats_for_tablet = get_option( 'misc_options_textformats_for_tablet', "" );
		if ( $misc_options_textformats_for_tablet == "on" ) {
			FormatsManager::$hasTabletSettings = true;
		}


		add_action( 'admin_menu', 'FormatsManager::textformats_setup_menu', 10 );

		add_action( 'admin_init', 'FormatsManager::register_settings' );

		add_action( 'admin_enqueue_scripts', 'FormatsManager::formatsmanager_styles' );

		add_action( 'admin_init', 'FormatsManager::register_scripts', 10 );
		add_action( 'admin_enqueue_scripts', 'FormatsManager::formatsmanager_scripts', 9 );

		add_action( 'admin_head', 'FormatsManager::gridder_textformats_css', 10 );

		add_action( 'admin_footer', 'FormatsManager::print_JSON' );

		add_action( 'mce_external_plugins', 'FormatsManager::tinymce_add_textformatsloader' );
		add_filter( 'tiny_mce_before_init', 'FormatsManager::tinymce_formats' );

		add_action( 'wp_enqueue_scripts', 'FormatsManager::frontend_textformats_css', 13 );

	}

	// http://wordpress.stackexchange.com/questions/144705/unable-to-add-code-button-to-tinymce-toolbar
	// formats dropdown for tinymce
	public static function tinymce_formats($in) {

		// using 'attributes' => array('class' => '_'.$customFormats[$i]['formatname']),
		// instead of 'classes' => '_'.$customFormats[$i]['formatname']
		// this way the user can only have one format applied to a selected text

		$paragraph_formats = array();
		$headline_formats = array();
		$character_formats = array();

		$customFormats = FormatsManager::$customFormats;
		$style_formats = array();

		if ($customFormats) {
			for ($i=0; $i<count($customFormats); $i++) {
				switch ($customFormats[$i]['type']) {
					case 'Paragraph':
						$paragraph_formats []= array(
							'title' => $customFormats[$i]['formatname'],
							'attributes' => array('class' => '_'.$customFormats[$i]['formatname']),
							'block' => 'p',
							'exact' => true
						);
					break;
					case 'Headline':
						$block = 'h1';
						if( array_key_exists('headlinetype', $customFormats[$i]) ) {
							$block = $customFormats[$i]['headlinetype'];
						}
						$headline_formats []= array(
							'title' => $customFormats[$i]['formatname'],
							'attributes' => array('class' => '_'.$customFormats[$i]['formatname']),
							'block' => $block
						);
					break;
					case 'Character':
						$character_formats []= array(
							'title' => $customFormats[$i]['formatname'],
							'attributes' => array('class' => '_'.$customFormats[$i]['formatname']),
							'inline' => 'span'
						);
					break;
				}
			}

			if ($paragraph_formats) {
				$style_formats []= array(
					'title' => 'Paragraph',
					'items' => $paragraph_formats
				);
			}
			if ($headline_formats) {
				$style_formats []= array(
					'title' => 'Headline',
					'items' => $headline_formats
				);
			}
			if ($character_formats) {
				$style_formats []= array(
					'title' => 'Character',
					'items' => $character_formats
				);
			}

			$in['style_formats'] = json_encode( $style_formats );
		} else {
			$style_formats []= array(
				'title' => 'Paragraph',
				'items' => array()
			);

			$style_formats []= array(
				'title' => 'Headline',
				'items' => array()
			);

			$style_formats []= array(
				'title' => 'Character',
				'items' => array()
			);

			$in['style_formats'] = json_encode( $style_formats );
		}

		return $in;
	}

	public static function tinymce_add_textformatsloader( $plugins ) {
		$plugins['textformatsloader'] = Setup::$uri.'/formatsmanager/assets/js/tinymce_plugin/tinymce_textformatsloader.js';
		return $plugins;
	}

	public static function print_JSON() {
		echo '<textarea style="display: none;" id="formatsmanager_json" name="formatsmanager_json">'.get_option( 'formatsmanager_json', json_encode(array(FormatsManager::$defaultFormat)) ).'</textarea>';
	}

	private static function getPhoneSpecificCSS($array) {
		$phone_fontsize = array_key_exists('phone-fontsize', $array) ? $array['phone-fontsize'] : "16";
		$fontsizemu = array_key_exists('fontsizemu', $array) ? $array['fontsizemu'] : 'px';
		$phone_fontsizemu = array_key_exists('phone-fontsizemu', $array) ? $array['phone-fontsizemu'] : $fontsizemu;

		$phone_spacetop = array_key_exists('phone-spacetop', $array) ? $array['phone-spacetop'] : "0";
		$phone_spacetopmu = array_key_exists('phone-spacetopmu', $array) ? $array['phone-spacetopmu'] : "px";

		$phone_spacebottom = array_key_exists('phone-spacebottom', $array) ? $array['phone-spacebottom'] : "20";
		$phone_spacebottommu = array_key_exists('phone-spacebottommu', $array) ? $array['phone-spacebottommu'] : "px";

		$return =
			'font-size:'.$phone_fontsize.$phone_fontsizemu.';'
			.'margin:'.$phone_spacetop.$phone_spacetopmu.' 0 '.$phone_spacebottom.$phone_spacebottommu.' 0;';

		return $return;
	}

	private static function getDesktopSpecificCSS($array) {
		$fontsizemu = array_key_exists('fontsizemu', $array) ? $array['fontsizemu'] : 'px';

		$spacetop = array_key_exists('spacetop', $array) ? $array['spacetop'] : '0';
		$spacebottom = array_key_exists('spacebottom', $array) ? $array['spacebottom'] : '20';

		$spacetopmu = array_key_exists('spacetopmu', $array) ? $array['spacetopmu'] : "px";
		$spacebottommu = array_key_exists('spacebottommu', $array) ? $array['spacebottommu'] : "px";

		$return =
			'font-size:'.$array['fontsize'].$fontsizemu.';'
			.'margin:'.$spacetop.$spacetopmu.' 0 '.$spacebottom.$spacebottommu.' 0;';

		return $return;
	}

	private static function getTabletSpecificCSS($array) {
		// bail out early if tablet textformats is not active
		if (!FormatsManager::$hasTabletSettings) {
			return FormatsManager::getDesktopSpecificCSS($array);
		}
		$tablet_fontsize = array_key_exists('tablet-fontsize', $array) ? $array['tablet-fontsize'] : "16";
		$tablet_fontsizemu = array_key_exists('tablet-fontsizemu', $array) ? $array['tablet-fontsizemu'] : "px";

		$tablet_spacetop = array_key_exists('tablet-spacetop', $array) ? $array['tablet-spacetop'] : "0";
		$tablet_spacetopmu = array_key_exists('tablet-spacetopmu', $array) ? $array['tablet-spacetopmu'] : "px";

		$tablet_spacebottom = array_key_exists('tablet-spacebottom', $array) ? $array['tablet-spacebottom'] : "20";
		$tablet_spacebottommu = array_key_exists('tablet-spacebottommu', $array) ? $array['tablet-spacebottommu'] : "px";

		$return =
			'font-size:'.$tablet_fontsize.$tablet_fontsizemu.';'
			.'margin:'.$tablet_spacetop.$tablet_spacetopmu.' 0 '.$tablet_spacebottom.$tablet_spacebottommu.' 0;';

		return $return;
	}

	private static function getFontCSS($array) {
		$textindent = array_key_exists('textindent', $array) ? $array['textindent'] : '0';
		$fontweight = array_key_exists('fontweight', $array) ? $array['fontweight'] : '400';
		$textalign = array_key_exists('textalign', $array) ? $array['textalign'] : 'left';

		$caps = "";
		if ( array_key_exists('caps', $array) && $array['caps'] == true ) {
			$caps = "text-transform:uppercase;";
		} else {
			$caps = "text-transform:none;";
		}

		$italic = "";
		if ( array_key_exists('italic', $array) && $array['italic'] == true ) {
			$italic = "font-style:italic;";
		} else {
			$italic = "font-style:normal;";
		}
		$underline = "";
		if ( array_key_exists('underline', $array) && $array['underline'] == true ) {
			$underline = "text-decoration: underline;";
		} else {
			$underline = "text-decoration: none;";
		}

		$borderbottom = "";
		if ( array_key_exists('borderbottom', $array) && $array['borderbottom'] == true ) {
			$borderbottom = "border-bottom: 1px solid;";
		} else {
			$borderbottom = "border-bottom: none;";
		}

		// font-variation-settings: "opsz" 100, "wght" 152, "ital" 12;
		$variablesettings = "";
		if( array_key_exists('variablesettings', $array) ){
			$variablesettings = 'font-variation-settings: ';
			$values = array();
			foreach($array['variablesettings'] as $obj){
				$values []= '"'.$obj['tag'].'" '.$obj['value'];
			}
			$variablesettings .= join(', ', $values);
			$variablesettings .= ';';
		}

		$return =
		'font-family:'.$array['fontfamily'].';'
		.'color:'.$array['color'].';'
		.'letter-spacing:'.$array['letterspacing'].'em;'
		.'line-height:'.$array['lineheight'].';'
		.'font-weight:'.$fontweight.';'
		.'text-align:'.$textalign.';'
		.'text-indent:'.$textindent.'em;'
		.'padding: 0;'
		.$caps
		.$italic
		.$underline
		.$borderbottom
		.$variablesettings;

		return $return;
	}

	public static function frontend_textformats_css() {

		$formatsJsonString = get_option( 'formatsmanager_json', json_encode(array(FormatsManager::$defaultFormat)) );
		$formatsJsonArr = json_decode($formatsJsonString, true);

		$formatsCSS = '';

		for ($i = 0; $i<count($formatsJsonArr); $i++) {
			if ($formatsJsonArr[$i]["formatname"] == "Default") {
				// "Default" textformat
				$formatsCSS .=
					'
						/* default text format "Default" */
						.lay-textformat-parent > *, ._Default{
							'.FormatsManager::getFontCSS($formatsJsonArr[$i]).'
						}';

				if (FormatsManager::$hasTabletSettings) {
					$formatsCSS .=
						'@media (min-width: '.(MiscOptions::$tablet_breakpoint+1).'px){
							.lay-textformat-parent > *, ._Default{
								'.FormatsManager::getDesktopSpecificCSS($formatsJsonArr[$i]).'
							}
							.lay-textformat-parent > *:last-child, ._Default:last-child{
								margin-bottom: 0;
							}
						}
						@media (min-width: '.(MiscOptions::$phone_breakpoint+1).'px) and (max-width: '.(MiscOptions::$tablet_breakpoint).'px){
							.lay-textformat-parent > *, ._Default{
								'.FormatsManager::getTabletSpecificCSS($formatsJsonArr[$i]).'
							}
							.lay-textformat-parent > *:last-child, ._Default:last-child{
								margin-bottom: 0;
							}
						}
						@media (max-width: '.(MiscOptions::$phone_breakpoint).'px){
							.lay-textformat-parent > *, ._Default{
								'.FormatsManager::getPhoneSpecificCSS($formatsJsonArr[$i]).'
							}
							.lay-textformat-parent > *:last-child, ._Default:last-child{
								margin-bottom: 0;
							}
						}';
				} else {
					$formatsCSS .=
						'.lay-textformat-parent > *, ._Default{
							'.FormatsManager::getFontCSS($formatsJsonArr[$i]).'
						}
						.lay-textformat-parent > *:last-child, ._Default:last-child{
							margin-bottom: 0;
						}
						@media (min-width: '.(MiscOptions::$phone_breakpoint+1).'px){
							.lay-textformat-parent > *, ._Default{
								'.FormatsManager::getDesktopSpecificCSS($formatsJsonArr[$i]).'
							}
							.lay-textformat-parent > *:last-child, ._Default:last-child{
								margin-bottom: 0;
							}
						}
						@media (max-width: '.(MiscOptions::$phone_breakpoint).'px){
							.lay-textformat-parent > *, ._Default{
								'.FormatsManager::getPhoneSpecificCSS($formatsJsonArr[$i]).'
							}
							.lay-textformat-parent > *:last-child, ._Default:last-child{
								margin-bottom: 0;
							}
						}';
				}
			} else {
				$formatsCSS .=
					'._'.$formatsJsonArr[$i]['formatname'].'{'
						.FormatsManager::getFontCSS($formatsJsonArr[$i])
					.'}';
				// custom textformats
				if (FormatsManager::$hasTabletSettings) {
					$formatsCSS .=
						'@media (min-width: '.(MiscOptions::$tablet_breakpoint+1).'px){
							._'.$formatsJsonArr[$i]['formatname'].'{'
								.FormatsManager::getDesktopSpecificCSS($formatsJsonArr[$i]).
							'}
							._'.$formatsJsonArr[$i]['formatname'].':last-child{
								margin-bottom: 0;
							}
						}
							@media (min-width: '.(MiscOptions::$phone_breakpoint+1).'px) and (max-width: '.((int)MiscOptions::$tablet_breakpoint).'px){
								._'.$formatsJsonArr[$i]['formatname'].'{'
									.FormatsManager::getTabletSpecificCSS($formatsJsonArr[$i]).
								'}
								._'.$formatsJsonArr[$i]['formatname'].':last-child{
									margin-bottom: 0;
								}
							}
							@media (max-width: '.(MiscOptions::$phone_breakpoint).'px){
								._'.$formatsJsonArr[$i]['formatname'].'{'
									.FormatsManager::getPhoneSpecificCSS($formatsJsonArr[$i]).
								'}
								._'.$formatsJsonArr[$i]['formatname'].':last-child{
									margin-bottom: 0;
								}
							}';
				} else {
					$formatsCSS .=
						'@media (min-width: '.(MiscOptions::$phone_breakpoint+1).'px){'
							.'._'.$formatsJsonArr[$i]['formatname'].'{'
								.FormatsManager::getDesktopSpecificCSS($formatsJsonArr[$i])
							.'}
							._'.$formatsJsonArr[$i]['formatname'].':last-child{
								margin-bottom: 0;
							}
						}'
						.'@media (max-width: '.(MiscOptions::$phone_breakpoint).'px){'
							.'._'.$formatsJsonArr[$i]['formatname'].'{'
								.FormatsManager::getPhoneSpecificCSS($formatsJsonArr[$i])
							.'}
							._'.$formatsJsonArr[$i]['formatname'].':last-child{
								margin-bottom: 0;
							}
						}';
				}
			}
		}

		if ($formatsCSS != "") {
			wp_add_inline_style( 'frontend-style',
			$formatsCSS );
		}
	}

	public static function gridder_textformats_css() {
		$screen = get_current_screen();
		if ( $screen->id == 'edit-category' || $screen->id == 'post' || $screen->id == 'page' ) {
			$formatsJsonString = get_option( 'formatsmanager_json', json_encode(array(FormatsManager::$defaultFormat)) );
			$formatsJsonArr = json_decode($formatsJsonString, true);

			$formatsCSS = '';

			for ($i = 0; $i<count($formatsJsonArr); $i++) {
				if ($formatsJsonArr[$i]["formatname"] == "Default") {
					// "Default" textformat

					echo
						'<!-- default text format "Default" -->
						<style>
							#gridder .lay-textformat-parent > *,
							#gridder ._Default{
								'.FormatsManager::getFontCSS($formatsJsonArr[$i]).'
							}
							#gridder .show-desktop-version .lay-textformat-parent > *,
							#gridder .show-desktop-version ._Default,
							#thumbnailgrid-content-region.preview-desktop ._Default{
								'.FormatsManager::getDesktopSpecificCSS($formatsJsonArr[$i]).'
							}
							#thumbnailgrid-content-region.preview-tablet ._Default{
								'.FormatsManager::getTabletSpecificCSS($formatsJsonArr[$i]).'
							}
							#gridder .show-phone-version .lay-textformat-parent > *,
							#gridder .show-phone-version ._Default,
							#thumbnailgrid-content-region.preview-phone ._Default{
								'.FormatsManager::getPhoneSpecificCSS($formatsJsonArr[$i]).'
							}
						</style>';
				} else {
					// custom textformats
					$formatsCSS .=
						'#gridder .lay-textformat-parent ._'.$formatsJsonArr[$i]['formatname'].'{'
							.FormatsManager::getFontCSS($formatsJsonArr[$i]).
						'}
						#gridder .show-desktop-version .lay-textformat-parent ._'.$formatsJsonArr[$i]['formatname'].',
						#thumbnailgrid-content-region.preview-desktop ._'.$formatsJsonArr[$i]['formatname'].'{'
							.FormatsManager::getDesktopSpecificCSS($formatsJsonArr[$i]).
						'}
						#thumbnailgrid-content-region.preview-tablet ._'.$formatsJsonArr[$i]['formatname'].'{
							'.FormatsManager::getTabletSpecificCSS($formatsJsonArr[$i]).'
						}
						#gridder .show-phone-version .lay-textformat-parent ._'.$formatsJsonArr[$i]['formatname'].',
						#thumbnailgrid-content-region.preview-phone ._'.$formatsJsonArr[$i]['formatname'].'{'
							.FormatsManager::getPhoneSpecificCSS($formatsJsonArr[$i]).
						'}';
				}
			}

			if ($formatsCSS != "") {
				echo
				'<!-- custom text formats -->
				<style>
					'.$formatsCSS.'
				</style>';
			}
		}
	}

	// 
	public static function updateCustomizerStylesLinkedWithTextformats(){
		$textFormatsJsonString = get_option( 'formatsmanager_json', json_encode(array(FormatsManager::$defaultFormat)) );
		$textFormatsJsonArr = json_decode($textFormatsJsonString, true);

		$prefixes  = array('st_', 'pt_', 'nav_', 'pa_', 'tagline_', 'm_st_', 'intro_text_');

		foreach ($prefixes as $prefix) {
			$format_name = get_theme_mod($prefix.'textformat', 'Default');
			if($format_name){
				foreach ($textFormatsJsonArr as $value) {
					if ($format_name == $value['formatname']) {

						$fontweight = array_key_exists('fontweight', $value) ? $value['fontweight'] : '400';
						$fontsizemu = array_key_exists('fontsizemu', $value) ? $value['fontsizemu'] : 'px';

						set_theme_mod($prefix.'color', $value['color']);
						set_theme_mod($prefix.'fontfamily', $value['fontfamily']);
						set_theme_mod($prefix.'fontweight', $fontweight);
						set_theme_mod($prefix.'letterspacing', $value['letterspacing']);
						
						// for mobile site title i need to set phone fontsize and phone fontsizemu instead of desktop versions
						if($prefix == "m_st_"){
							$phonefontsize = array_key_exists('phone-fontsize', $value) ? $value['phone-fontsize'] : '16';
							set_theme_mod('mobile_menu_sitetitle_fontsize', $phonefontsize);

							$phonefontsizemu = array_key_exists('phone-fontsizemu', $value) ? $value['phone-fontsizemu'] : 'px';
							set_theme_mod('m_st_fontsize_mu', $phonefontsizemu);

						} else {
							set_theme_mod($prefix.'fontsize', $value['fontsize']);
							set_theme_mod($prefix.'fontsize_mu', $fontsizemu);
						}

						if ($prefix == "pt_") {
							set_theme_mod($prefix.'lineheight', $value['lineheight']);
							set_theme_mod($prefix.'align', $value['textalign']);
						}

						if ($prefix == "intro_text_") {
							set_theme_mod($prefix.'lineheight', $value['lineheight']);
							set_theme_mod($prefix.'align', $value['textalign']);
						}

					}
				}
			}
		}

	}

	public static function formatsmanager_styles($hook) {
		if ( $hook == 'toplevel_page_manage-textformats' ) {
			wp_enqueue_style( 'formatsmanager-parsley', Setup::$uri.'/assets/css/parsley.css' );
			wp_enqueue_style( 'formatsmanager-iris', Setup::$uri.'/formatsmanager/assets/css/iris.css' );
			wp_enqueue_style( 'formatsmanager-bootstrap', Setup::$uri.'/assets/bootstrap/css/bootstrap.css' );
			wp_enqueue_style( 'formatsmanager-application', Setup::$uri.'/formatsmanager/assets/css/formatsmanager.style.css', array(), Setup::$ver );
		}
	}

	public static function register_scripts(){
		// using modified version of iris to prevent scrolling when user drags inside colorpicker
		wp_register_script( 'lay-opentype', Setup::$uri."/formatsmanager/assets/js/vendor/opentype.js", array(),  Setup::$ver );
		wp_register_script( 'lay-variablefont', Setup::$uri."/formatsmanager/assets/js/vendor/variablefont.js", array(),  Setup::$ver );
		wp_register_script( 'lay-sortable', Setup::$uri."/formatsmanager/assets/js/vendor/sortable.min.js", array(),  Setup::$ver );
	}

	public static function formatsmanager_scripts($hook){
		if ( $hook == 'toplevel_page_manage-textformats' ) {
			wp_enqueue_script( 'plugin-bootstrap', Setup::$uri."/assets/bootstrap/js/bootstrap.min.js", array( 'jquery' ), Setup::$ver);
			wp_enqueue_script( 'plugin-parsley', Setup::$uri."/assets/js/vendor/parsley.js", array( 'jquery' ), Setup::$ver);
			wp_enqueue_script( 'formatsmanager-app', Setup::$uri."/formatsmanager/assets/js/formatsmanager.app.min.js", array( 'jquery', 'lay-iris', 'lay-sortable', 'lay-opentype', 'lay-variablefont', 'marionettev3', 'underscore' ), Setup::$ver, true);
			wp_localize_script( 'formatsmanager-app', 'formatslgPassedData', array(
				'advancedLineHeights' => false
			) 
		);
		}
	}

	public static function register_settings() {
		register_setting( 'admin-textformats-settings', 'formatsmanager_json' );
	}

	public static function textformats_setup_menu(){
		add_menu_page( 'Text Formats', 'Text Formats', 'manage_options', 'manage-textformats', 'FormatsManager::textformats_markup', 'dashicons-editor-textcolor' );
	}

	public static function textformats_markup(){
		require_once( Setup::$dir.'/formatsmanager/markup.php' );
	}
}
FormatsManager::init();
