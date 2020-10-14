<?php
/**
 * Plugin Name: Lay Theme Carousel
 * Plugin URI: http://laytheme.com/addons/
 * Description: Use carousels (slideshows) to show your images. Only works with Lay Theme!
 * Version: 1.7.3
 * Author: 100k Studio
 * Author URI: http://100k.studio
 */

class LayThemeCarousel{

	public static $url;
	public static $dir;
	public static $ver;

	public function __construct(){
		LayThemeCarousel::$url = plugins_url( 'laytheme-carousel' );
		LayThemeCarousel::$dir = plugin_dir_path( __FILE__ );
		LayThemeCarousel::$ver = '1.7.3';

		// admin panel
		add_action( 'lay_after_projectpage_gridder_buttons', array( $this, 'add_carousel_button_to_gridder_header' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'gridder_carouseapp_styles' ) );
		// TODO: delete filter?
		// add_action( 'lay_before_projectpage_gridder_modals', array( $this, 'add_modal_markup' ) );
		add_filter( 'plugin_action_links_'.plugin_basename(__FILE__), array($this, 'add_action_links') );
		// add_action( 'lay_gridder_context_menu_end', array($this, 'add_toggle_fullscreen_contextmenu_button') );

		// frontend
		add_action( 'init', array( $this, 'register_vendor' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'frontend_carousel_scripts' ), 11 );
		// add_action( 'wp_enqueue_scripts', array( $this, 'frontend_carousel_styles' ) );
		add_action( 'wp_footer', array( $this, 'frontend_carousel_styles' ) );
	}

	public function add_toggle_fullscreen_contextmenu_button(){
		echo 
		'<button type="button" class="js-carousel-fullscreen-button btn btn-default"><span class="glyphicon glyphicon-ok"></span> Make Carousel Fullscreen</button>';
	}

	public function add_action_links( $links ){
		$mylinks = array(
			'<a href="' . admin_url( 'admin.php?page=manage-laycarousel' ) . '">Settings</a>',
		);
		return array_merge( $links, $mylinks );
	}

	public static function add_carousel_button_to_gridder_header(){
		echo
		'<button type="button" class="btn btn-default add-carousel-js">
			<span class="glyphicon glyphicon-plus"></span> Carousel
		</button>';
	}

	public static function gridder_carouseapp_styles(){
		$screen = get_current_screen();
		if ( $screen->id == 'post' || $screen->id == 'page' || $screen->id == 'edit-category') {
			wp_enqueue_style( 'gridder-laycarousel-style', LayThemeCarousel::$url.'/gridder/assets/css/gridder.style.css', array(), LayThemeCarousel::$ver );
		}
	}

	public static function register_vendor(){
		wp_register_script( 'swiper', LayThemeCarousel::$url."/frontend/assets/js/vendor/swiper.js", array(), LayThemeCarousel::$ver, true );
	}

	public static function frontend_carousel_scripts(){
		$transition = get_option( 'laycarousel_transition', "sliding" );
		$autoplaySpeed = get_option( 'laycarousel_autoplaySpeed', "3000" );
		$mousecursor = get_option( 'laycarousel_mousecursor', "leftright" );
		$showCaptions = get_option( 'laycarousel_showCaptions', "" );
		$captionTextformat = get_option( 'laycarousel_captionTextformat', "Default" );
		$numberTextformat = get_option( 'laycarousel_numberTextformat', "Default" );
		$showCircles = get_option( 'laycarousel_showCircles', "" );
		$showNumbers = get_option( 'laycarousel_showNumbers', "" );
		$numbersPosition = get_option( 'laycarousel_numbersPosition', "right" );
		$captionsPosition = get_option( 'laycarousel_captionsPosition', "left" );
		$showArrowButtons = get_option( 'laycarousel_showArrowButtons', "" );
		$pauseAutoplayOnHover = get_option( 'laycarousel_pauseAutoplayOnHover', "on" );
		$lazyload = get_option( 'laycarousel_lazyload', "on" );
		$alignSink = get_option( 'laycarousel_align_sink', "" ); 
		$contentAlignment = get_option( 'laycarousel_alignment', "bottom" );

		$rightButton = '';
		$laycarousel_rightbutton = get_option( 'laycarousel_rightbutton', '' );
		if($laycarousel_rightbutton != ''){
			$laycarousel_rightbutton = wp_get_attachment_image_src( $laycarousel_rightbutton, 'full' );
			if($laycarousel_rightbutton){
				$rightButton = $laycarousel_rightbutton[0];
			}
		}
		$alt_rightButton = '';
		$alt_laycarousel_rightbutton = get_option( 'laycarousel_altrightbutton', '' );
		if($alt_laycarousel_rightbutton != ''){
			$alt_laycarousel_rightbutton = wp_get_attachment_image_src( $alt_laycarousel_rightbutton, 'full' );
			if($alt_laycarousel_rightbutton){
				$alt_rightButton = $alt_laycarousel_rightbutton[0];
			}
		}
		// register swiper
		wp_enqueue_script( 'laycarousel-app', LayThemeCarousel::$url.'/frontend/assets/js/carousel.plugin.min.js', array( 'swiper' ), LayThemeCarousel::$ver, true);

		$showArrowButtonsForTouchDevices = get_option( 'laycarousel_showArrowButtonsForTouchDevices', "" );
		$laycarousel_sink_position = get_option( 'laycarousel_sink_position', 'below' );
		$textalignment = get_option( 'laycarousel_text_alignment', "middle" );
		$loop = get_option( 'laycarousel_loop', 'on' ) == 'on' ? true : false;

		wp_localize_script( 'laycarousel-app', 'layCarouselPassedData',
			array(
				'loop' => $loop,
				'transition' => $transition,
				'autoplaySpeed' => $autoplaySpeed,
				'mousecursor' => $mousecursor,
				'showCaptions' => $showCaptions,
				'captionTextformat' => $captionTextformat,
				'numberTextformat' => $numberTextformat,
				'showCircles' => $showCircles,
				'showNumbers' => $showNumbers,
				'captionsPosition' => $captionsPosition,
				'numbersPosition' => $numbersPosition,
				'showArrowButtons' => $showArrowButtons,
				'pauseAutoplayOnHover' => $pauseAutoplayOnHover,
				'rightButton' => $rightButton,
				'lazyload' => $lazyload,
				'alt_rightButton' => $alt_rightButton,
				'showArrowButtonsForTouchDevices' => $showArrowButtonsForTouchDevices,
				'alignSink' => $alignSink,
				'contentAlignment' => $contentAlignment,
				'sinkPosition' => $laycarousel_sink_position,
				'textAlignment' => $textalignment
		  	)
		);

	}

	public static function frontend_carousel_styles(){
		wp_enqueue_style( 'laycarousel-style', LayThemeCarousel::$url.'/frontend/assets/css/frontend.style.css', array(), LayThemeCarousel::$ver );

		$sink_position = get_option('laycarousel_sink_position', 'below');
		// custom cursors
		$cursorMarkup = "";

		$left_cursor = get_option( 'laycarousel_arrowleft', '' );
		if($left_cursor != ''){
			$left_cursor = wp_get_attachment_image_src( $left_cursor, 'full' );
			if($left_cursor){
				$cursorMarkup .= '.col .lay-carousel.cursor-left .lay-carousel-slide{cursor:url("'.$left_cursor[0].'") '.($left_cursor[1]/2).' '.($left_cursor[2]/2).', w-resize;}';
			}
		}

		$right_cursor = get_option( 'laycarousel_arrowright', '' );
		if($right_cursor != ''){
			$right_cursor = wp_get_attachment_image_src( $right_cursor, 'full' );
			if($right_cursor){
				$cursorMarkup .= '.col .lay-carousel.cursor-right .lay-carousel-slide{cursor:url("'.$right_cursor[0].'") '.($right_cursor[1]/2).' '.($right_cursor[2]/2).', e-resize;}';
			}
		}
	
		$spaceTopCaptions = get_option( 'laycarousel_spaceBetween', "0" );
		$spaceBottomCaptions = get_option( 'laycarousel_spaceBottomCaptions', "0" );
		$captionsCSS = '';
		switch( $sink_position ){
			case 'below':
				$captionsCSS .= 'margin-top:'.$spaceTopCaptions.'px;';
			break;
			case 'ontop':
				$captionsCSS .= 'padding-bottom:'.$spaceBottomCaptions.'px;';
			break;
		}

		$captionsPosition = get_option( 'laycarousel_captionsPosition', 'left' );
		$captionsPositionCSS = '';
		switch( $captionsPosition ) {
			case 'left':
				$captionsPositionCSS .= 'padding-left:'.get_option( 'laycarousel_caption_space_left', "0" ).'px;';
			break;
			case 'right':
				$captionsPositionCSS .= 'padding-right:'.get_option( 'laycarousel_caption_space_right', "0" ).'px;';
			break;
		}

		$spaceTopNumbers = get_option( 'laycarousel_spaceBetweenForNumbers', "0" );
		$spaceBottomNumbers = get_option( 'laycarousel_spaceBottomNumbers', "0" );
		$numbersCSS = '';
		switch( $sink_position ){
			case 'below':
				$numbersCSS .= 'margin-top:'.$spaceTopNumbers.'px;';
			break;
			case 'ontop':
				$numbersCSS .= 'padding-bottom:'.$spaceBottomNumbers.'px;';
			break;
		}

		$numbersPosition = get_option( 'laycarousel_numbersPosition', 'left' );
		switch( $numbersPosition ) {
			case 'left':
				$numbersCSS .= 'padding-left:'.get_option( 'laycarousel_numbers_space_left', "0" ).'px;';
			break;
			case 'right':
				$numbersCSS .= 'padding-right:'.get_option( 'laycarousel_numbers_space_right', "0" ).'px;';
			break;
		}

		// space between edge of slide and text slide
		$textspace = get_option( 'laycarousel_textspace', "0" );

		//space between arrow buttons and carousel edge
		$laycarousel_buttonspace = get_option( 'laycarousel_buttonspace', "10" );

		$spaceTopCircles = get_option( 'laycarousel_spaceBetweenForCircles', "10" );
		$spaceBottomCircles = get_option( 'laycarousel_spaceBottomCircles', "0" );
		$circlesCSS = '';
		switch( $sink_position ){
			case 'below':
				$circlesCSS .= 'padding-top:'.$spaceTopCircles.'px;';
			break;
			case 'ontop':
				$circlesCSS .= 'padding-bottom:'.$spaceBottomCircles.'px;';
			break;
		}

		// need selector like this so margin-top doesnt get overwritten by textformat: ".lay-carousel-sink-parent.lay-textformat-parent"
		wp_add_inline_style( 'laycarousel-style',
			'.single-caption{'.$captionsPositionCSS.'}
			.lay-carousel-sink-parent .captions-wrap{ '.$captionsCSS.' }
			.lay-carousel-sink-parent .numbers{ '.$numbersCSS.' }
			.laycarousel-bullets{ '.$circlesCSS.' }'
			.$cursorMarkup
			.'.lay-carousel .slide-text{ padding-left:'.$textspace.'px; padding-right:'.$textspace.'px; }'
			.'.flickity-prev-next-button.next{ right:'.$laycarousel_buttonspace.'px; }'
			.'.flickity-prev-next-button.previous{ left:'.$laycarousel_buttonspace.'px; }'
		);
	}
}
new LayThemeCarousel();

// settings
require plugin_dir_path(__FILE__).'settings/settings.php';
// gridder
require plugin_dir_path(__FILE__).'gridder/gridder.php';
// frontend
require plugin_dir_path(__FILE__).'frontend/assets/php/carouselOptions.php';
require plugin_dir_path(__FILE__).'frontend/assets/php/carousel.php';

require 'plugin_update_check.php';
$MyUpdateChecker = new PluginUpdateChecker_2_0 (
    'https://kernl.us/api/v1/updates/56b09a95ffb0be4f0769e299/',
    __FILE__,
    'laytheme-carousel',
    1
);
