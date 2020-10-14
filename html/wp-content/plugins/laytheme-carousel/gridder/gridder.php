<?php
class LayThemeCarouselGridder {
	public static function init() {
		add_action( 'admin_enqueue_scripts', 'LayThemeCarouselGridder::lg_localize_gridder_js_with_carousel', 11 );
	}

	public static function lg_localize_gridder_js_with_carousel() {
		$showArrowButtons = get_option( 'laycarousel_showArrowButtonsForTouchDevices', "" ) == "on" || get_option( 'laycarousel_showArrowButtons', "" ) == "on";
		$transition = get_option( 'laycarousel_transition', "sliding" );
		$loop = get_option( 'laycarousel_loop', 'on' ) == 'on' ? true : false;

		$array = array( 
			'transition' => $transition,
			'showArrowButtons' => $showArrowButtons,
			'loop' => $loop
		);
		wp_localize_script( 'gridder-app', 'carouselPassedData', $array ); 
	}
}

LayThemeCarouselGridder::init();