<?php

class LayCarouselPluginOptions {

    public static $transition;
    public static $autoplaySpeed;
    public static $mousecursor;
    public static $showCaptions;
    public static $captionTextformat;
    public static $numberTextformat;
    public static $showCircles;
    public static $showNumbers;
    public static $captionsPosition;
    public static $numbersPosition;
    public static $showArrowButtons;
    public static $pauseAutoplayOnHover;
    public static $rightButton;
    public static $lazyload;
    public static $alt_rightButton;
    public static $showArrowButtonsForTouchDevices;
    public static $alignSink;
    public static $contentAlignment;
    public static $sinkPosition;
    public static $textalignment;
    public static $loop;

    public function __construct() {

        $loop = get_option( 'laycarousel_loop', "on" );
        $loop = $loop == 'on' ? true : false;

        $transition = get_option( 'laycarousel_transition', "sliding" );
        $autoplaySpeed = get_option( 'laycarousel_autoplaySpeed', "3000" );
        $mousecursor = get_option( 'laycarousel_mousecursor', "leftright" );
        $showCaptions = get_option( 'laycarousel_showCaptions', "" );
        $showCaptions = $showCaptions == "on" ? true : false;

        $captionTextformat = get_option( 'laycarousel_captionTextformat', "Default" );
        $numberTextformat = get_option( 'laycarousel_numberTextformat', "Default" );
        $showCircles = get_option( 'laycarousel_showCircles', "" );
        $showCircles = $showCircles == "on" ? true : false;

        $showNumbers = get_option( 'laycarousel_showNumbers', "" );
        $showNumbers = $showNumbers == "on" ? true : false;

        $numbersPosition = get_option( 'laycarousel_numbersPosition', "right" );
        $captionsPosition = get_option( 'laycarousel_captionsPosition', "left" );
        $showArrowButtons = get_option( 'laycarousel_showArrowButtons', "" );
        $showArrowButtons = $showArrowButtons == "on" ? true : false;

        $pauseAutoplayOnHover = get_option( 'laycarousel_pauseAutoplayOnHover', "on" );
        $lazyload = get_option( 'laycarousel_lazyload', "on" );
        $alignSink = get_option( 'laycarousel_align_sink', "" ); 
        $alignSink = $alignSink == "on" ? true : false;

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
    
        $showArrowButtonsForTouchDevices = get_option( 'laycarousel_showArrowButtonsForTouchDevices', "" );
        $showArrowButtonsForTouchDevices = $showArrowButtonsForTouchDevices == "on" ? true : false;

        $laycarousel_sink_position = get_option( 'laycarousel_sink_position', 'below' );
        
        LayCarouselPluginOptions::$textalignment = get_option( 'laycarousel_text_alignment', 'middle' );
        LayCarouselPluginOptions::$transition = $transition;
        LayCarouselPluginOptions::$autoplaySpeed = $autoplaySpeed;
        LayCarouselPluginOptions::$mousecursor = $mousecursor;
        LayCarouselPluginOptions::$showCaptions = $showCaptions;
        LayCarouselPluginOptions::$captionTextformat = $captionTextformat;
        LayCarouselPluginOptions::$numberTextformat = $numberTextformat;
        LayCarouselPluginOptions::$showCircles = $showCircles;
        LayCarouselPluginOptions::$showNumbers = $showNumbers;
        LayCarouselPluginOptions::$captionsPosition = $captionsPosition;
        LayCarouselPluginOptions::$numbersPosition = $numbersPosition;
        LayCarouselPluginOptions::$showArrowButtons = $showArrowButtons;
        LayCarouselPluginOptions::$pauseAutoplayOnHover = $pauseAutoplayOnHover;
        LayCarouselPluginOptions::$rightButton = $rightButton;
        LayCarouselPluginOptions::$lazyload = $lazyload;
        LayCarouselPluginOptions::$alt_rightButton = $alt_rightButton;
        LayCarouselPluginOptions::$showArrowButtonsForTouchDevices = $showArrowButtonsForTouchDevices;
        LayCarouselPluginOptions::$alignSink = $alignSink;
        LayCarouselPluginOptions::$contentAlignment = $contentAlignment;
        LayCarouselPluginOptions::$sinkPosition = $laycarousel_sink_position;
        LayCarouselPluginOptions::$loop = $loop;
    }

}
new LayCarouselPluginOptions();