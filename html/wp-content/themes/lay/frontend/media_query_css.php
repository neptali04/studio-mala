<?php
class MediaQueryCSS{

	public static $desktop;
	public static $phone;

	public function __construct(){
		MediaQueryCSS::$desktop = require get_template_directory().'/frontend/style_desktop.php';
		MediaQueryCSS::$phone = require get_template_directory().'/frontend/style_phone.php';
	}
}

new MediaQueryCSS();
