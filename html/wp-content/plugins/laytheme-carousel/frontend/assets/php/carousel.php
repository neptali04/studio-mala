<?php

// todo: see if for example calling LayElFunctions::getHTML5VideoMarkup will break the website if that function doesn't exist
// todo: show original image only if desktop size? 

class LayCarouselPluginFrontend {

    public static $config;

    public function __construct(){

        LayCarouselPluginFrontend::$config = array(
            'numbersPosition' => '',
            'captionsPosition' => '',
            'showCaptions' => '',
            'showNumbers' => ''
        );

        LayCarouselPluginFrontend::$config['showCaptions'] = LayCarouselPluginOptions::$showCaptions;
        LayCarouselPluginFrontend::$config['showNumbers'] = LayCarouselPluginOptions::$showNumbers;

        if( LayCarouselPluginOptions::$showNumbers ) {
            LayCarouselPluginFrontend::$config['numbersPosition'] = 'numbers-'.LayCarouselPluginOptions::$numbersPosition;
        }
        if( LayCarouselPluginOptions::$showCaptions ) {
            LayCarouselPluginFrontend::$config['captionsPosition'] = 'captions-'.LayCarouselPluginOptions::$captionsPosition;
        }

        if( LayCarouselPluginOptions::$showCircles ) {
            LayCarouselPluginFrontend::$config['showCaptions'] = false;
            LayCarouselPluginFrontend::$config['showNumbers'] = false;
        }
    }

    public static function getMarkup($el){
        $captions = array();
        $i;
        $carousel = $el['carousel'];
        $options = array_key_exists('carousel_options', $el) ? $el['carousel_options'] : false;   
        $slidesWidth = '';
        $transition = LayCarouselPluginOptions::$transition;
        $classesWrap = '';
        $classesCarousel = '';
        $classesCarousel .= 'content-alignment-'.LayCarouselPluginOptions::$contentAlignment;     
    
        $slidesWidth = '';
        $transition = LayCarouselPluginOptions::$transition;
        $classesWrap = '';
        $classesCarousel = '';
        $classesCarousel .= 'content-alignment-'.LayCarouselPluginOptions::$contentAlignment;
        $spacebetween = 0;

        if( $options ) {
            if( array_key_exists('spaceBetween', $options ) ){
                $spacebetween = $options['spaceBetween'];
            }
            $loop = LayCarouselPluginOptions::$loop;
            if( array_key_exists('loop', $options ) ){
                $loop = $options['loop'];
            }
            $classesWrap .= $loop == true ? 'loop-slides' : 'dont-loop-slides hide-previous-related-ui';

            if( array_key_exists('slidesWidth', $options) ){
                if( $options['slidesWidth'] != '' ) {
                    $slidesWidth = 'width:calc('.$options['slidesWidth'].');';
                }
            }
            if( array_key_exists('random', $options ) && $options['random'] ){
                shuffle($carousel);
            }
            if( $options['autoplay'] == true ){
                $autoplay = true;
                $classesCarousel .= ' autoplay';
            } else {
                $classesCarousel .= ' no-autoplay';
            }
            if ( array_key_exists('showMultipleSlides', $options) && $options['showMultipleSlides'] ) {
                $showMultipleSlides = true;
            }
            if ( array_key_exists('transition', $options) ){
                $transition = $options['transition'];
            }
            if ( array_key_exists('showMultipleSlides', $options) && $options['showMultipleSlides'] ){
                $classesWrap .= $options['showMultipleSlides'] == true ? ' show-multiple-slides' : ' dont-show-multiple-slides';
            } else {
                $classesWrap .= ' dont-show-multiple-slides';
            }
            $enableClickAndDrag = true;
            if ( array_key_exists('disableClick', $options) && $options['disableClick'] && $options['autoplay'] == true ) {
                $classesCarousel .= ' disable-click-and-drag';
                $enableClickAndDrag = false;
            } else {
                $classesCarousel .= ' enable-click-and-drag';
                $enableClickAndDrag = true;
            }
            if ( array_key_exists('freeScroll', $options) && $options['freeScroll'] && $enableClickAndDrag) {
                $classesCarousel .= ' free-scroll';
            }
            if ( array_key_exists('size', $options) ){
                $classesWrap .= ' '.$options['size'];
            }
            if ( array_key_exists('fillSlides', $options) ){
                $classesWrap .= $options['fillSlides'] == true ? ' fill-slides' : ' dont-fill-slides';
            } else {
                $classesWrap .= ' dont-fill-slides';
            }
            if ( array_key_exists('slidesWidth', $options) ){
                if ( $options['slidesWidth'] == '' ||  $options['slidesWidth'] == "null" || $options['slidesWidth'] == null){
                    $classesWrap .= ' no-fixed-slides-width';
                } else {
                    $classesWrap .= ' fixed-slides-width';
                }
            } else {
                $classesWrap .= ' no-fixed-slides-width';
            }
            if ( array_key_exists('fixedHeight', $options) ){
                if ( $options['fixedHeight'] == 'auto' ) {
                    $classesCarousel .= ' fixed-height-auto';
                }else{
                    $classesCarousel .= ' no-fixed-height-auto';
                }
            }
            else{
                $classesCarousel .= ' no-fixed-height-auto';
            }
            if( array_key_exists( 'centeredSlides', $options ) && $options['centeredSlides'] ) {
                $classesCarousel .= ' centered-slides';
            }
        }
    
        $classesCarousel .= ' transition-'.$transition;

        if( LayCarouselPluginOptions::$showCircles ) {
            $classesWrap .= ' show-circles';
        } else {
            $classesWrap .= ' no-circles';
        }
        if( $transition != 'sliding' ) {
            $classesWrap .= ' no-swiper';
        }
        $classesWrap .= ' sink-'.LayCarouselPluginOptions::$sinkPosition;
        if( LayCarouselPluginOptions::$pauseAutoplayOnHover ){
            $classesWrap .= ' pause-autoplay-on-hover';
        }

        $firstAr = 9/16;
        $pb = 9/16 * 100;

        if( $carousel[0]['type'] != "text" ) {
            $firstAr = $carousel[0]['ar'];
            $pb = $carousel[0]['ar'] * 100;
        }

        $fixedHeight = null;
        if ( array_key_exists('customAspectRatio', $options) == false  ) {
            if( $options['size'] == 'aspectRatioOfFirstElement' ) {
                if( $carousel[0]['type'] != "text" ) {
                    $firstAr = $carousel[0]['ar'];
                    $pb = $carousel[0]['ar'] * 100;
                }
                $showMultipleSlides = false;
            } else if ( $options['size'] == 'useCustomAspectRatio') {
                $firstAr = $options['customAspectRatioH'] / $options['customAspectRatioW'];
                $pb = $firstAr * 100;
            } else if ( $options['size'] == 'fixedHeight') {
                $fixedHeight = $options['fixedHeight'];
            }
        } else if( $options['customAspectRatio'] == true ){
            $firstAr = $options['customAspectRatioH'] / $options['customAspectRatioW'];
            $pb = $firstAr * 100;
            $showMultipleSlides = false;
        }
        if( $fixedHeight == '100vh' ){
            $classesCarousel .= ' _100vh';
        }

        if ( array_key_exists('fixedHeight', $options) ){
            if ($options['fixedHeight'] == 'auto'){
                $classesCarousel .= ' fixed-height-auto';
            } else {
                $classesCarousel .= ' fixed-height-not-auto';
            }
        }else{
            $classesCarousel .= ' fixed-height-not-auto';
        }
        $classesCarousel .= ' text-alignment-'.LayCarouselPluginOptions::$textalignment;
    
        $markup = '<div class="lay-carousel-wrap '.$classesWrap.'"><div class="lay-carousel '.$classesCarousel.'" data-spacebetween="'.$spacebetween.'" ';
        if ( $fixedHeight && $fixedHeight != '100vh') {
            $markup .= 'style="height: calc(' . $fixedHeight . ')"';
        }
        if ( $fixedHeight == null ) {
            $markup .= 'style="padding-bottom:'.$pb.'%;" data-carouselAr="'.$firstAr.'"';
        }
        $markup .= '>';
        if( $transition == 'sliding' ) {
            $markup .= '<div class="swiper-container"><div class="swiper-wrapper">';
        }
        $slideClass = '';
        if( $transition == 'sliding' ) {
            $slideClass = 'swiper-slide';
        }
        for( $i=0; $i<count($carousel); $i++ ) {
            $link = '';
            if ( array_key_exists('imagelink', $carousel[$i]) ) {
                $linkTarget = $carousel[$i]['imagelink']['newtab'] ? ' target="_blank"' : '';
                $url = '';
                if( method_exists( 'LTUtility', 'getFullURL' ) ){
                    $url = LTUtility::getFullURL($carousel[$i]['imagelink']['url']);
                }
                $data_id = '';
                if( array_key_exists('id', $carousel[$i]['imagelink']) && $carousel[$i]['imagelink']['id'] != '' ) {
                    $data_id = 'data-id="'.$carousel[$i]['imagelink']['id'].'"';
                }
                $data_type = '';
                if( array_key_exists('type', $carousel[$i]['imagelink']) && $carousel[$i]['imagelink']['type'] != '' ) {
                    $data_type = 'data-type="'.$carousel[$i]['imagelink']['type'].'"';
                }
                $data_title = '';
                if( array_key_exists('title', $carousel[$i]['imagelink']) && $carousel[$i]['imagelink']['title'] != '' ) {
                    $data_title = 'data-title="'.$carousel[$i]['imagelink']['title'].'"';
                }
                
                $data_title = '';
                $link = '<a '.$data_id.' '.$data_type.' '.$data_title.' href="'.$url.'" '.$linkTarget.'>';
            }
            // no use in using alt buttons when $enableClickAndDrag is false, bc i dont show any buttons when $enableClickAndDrag is false
            $use_alt_buttons = '';
            if(  array_key_exists('useAltButtons', $carousel[$i]) && $carousel[$i]['useAltButtons'] && $enableClickAndDrag ) {
                $use_alt_buttons = 'lay-use-alt-buttons';
            }
            $markup .= '<div class="lay-carousel-slide '.$slideClass.' '.$use_alt_buttons.' lay-carousel-slide-'.$carousel[$i]['type'].'" style="'.$slidesWidth.'">';
            $markup .= $link;
            $markup .= '<div class="slide-inner">';

            $caption = array_key_exists('caption', $carousel[$i]) ? $carousel[$i]['caption'] : '';
            $captions []= $caption;

            switch( $carousel[$i]['type'] ){
                case 'text':
                    $markup .= LayCarouselPluginFrontend::addText($carousel[$i]);
                break;
                case 'html5video':
                    $markup .= LayCarouselPluginFrontend::addHtml5Video($carousel[$i]);
                    // need placeholder div for for example: slidesWidth: 400px;, carouselHeight/fixedHeight: auto
                    $markup .= '<div class="ph" style="padding-bottom:'.($carousel[$i]['ar']*100).'%;"></div>';
                break;
                case 'img':
                case 'project':
                case 'postThumbnail':
                    $fillSlides = false;
                    if( array_key_exists('fillSlides', $options) && $options['fillSlides'] == true ) {
                        $fillSlides = true;
                    }
                    $markup .= LayCarouselPluginFrontend::addImage($carousel[$i], $fillSlides);
                    // need placeholder div for for example: slidesWidth: 400px;, carouselHeight/fixedHeight: auto
                    $markup .= '<div class="ph" style="padding-bottom:'.($carousel[$i]['ar'] * 100).'%;"></div>';
                break;
            }

            // closing .lay-carousel-slide & link & .slide-inner
            $markup .= '</div>';
            if ($link != '') {
                $markup .= '</a>';
            }
            $markup .= '</div>';
        }
        // closing .lay-carousel
        $markup .= '</div>';

        if( $transition == 'sliding' ) {
            // closing swiper container
            $markup .= '</div>';
            // closing swiper wrapper
            $markup .= '</div>';
        }
        
        // dots for fadecarousel
        if( LayCarouselPluginOptions::$showCircles && $transition != "sliding" && $enableClickAndDrag){
            $markup .= '<div class="laycarousel-bullets swiper-pagination-bullets">';
            for( $j=0; $j<count($carousel); $j++){
                $markup .= '<li class="swiper-pagination-bullet"></li>';
            }
            $markup .= '</div>';
        }

        $captionMarkup = '';
        if( LayCarouselPluginFrontend::$config['showCaptions'] && LayCarouselPluginOptions::$showCircles == false ){
            $allEmpty = true;
            for ($i = 0; $i < count($captions); $i++) {
                if( trim($captions[$i]) != "" ) { 
                    $allEmpty = false;
                }
                $captionMarkup .= '<div class="single-caption ix-'.$i.'"><div class="single-caption-inner _'.LayCarouselPluginOptions::$captionTextformat.'">'.$captions[$i].'</div></div>';
            };

            if($allEmpty){
                $captionMarkup = '';
            }else{
                $captionMarkup = '<div class="captions-wrap"><div class="captions-inner"><div class="captions-slider">'.$captionMarkup.'</div></div></div>';
            }
        }

        $sinkClass = LayCarouselPluginFrontend::$config['numbersPosition'].' '.LayCarouselPluginFrontend::$config['captionsPosition'];
        if( LayCarouselPluginFrontend::$config['showNumbers'] && LayCarouselPluginFrontend::$config['showCaptions'] ){
            if($captionMarkup == ''){
                $sinkClass .= " sink-only-numbers";
            }else{
                $sinkClass .= " sink-with-numbers-and-captions";
            }
        }
        else if( LayCarouselPluginFrontend::$config['showNumbers'] ){
            $sinkClass .= " sink-only-numbers";
        }
        else if( LayCarouselPluginFrontend::$config['showCaptions'] ){
            $sinkClass .= " sink-only-captions";
        }

        if( LayCarouselPluginFrontend::$config['showNumbers'] || LayCarouselPluginFrontend::$config['showCaptions'] ) {
            $markup .= 
            '<div class="lay-carousel-sink-parent">'
                .'<div class="lay-carousel-sink '.$sinkClass.'">'
                    .$captionMarkup
                    .'<span class="numbers _'.LayCarouselPluginOptions::$numberTextformat.'"></span>'
                .'</div>'
            .'</div>';
        }

        // closing .lay-carousel-wrap
        $markup .=
        '</div>';

        // not slide captions but normal caption like a caption for a video/image
        if( array_key_exists('caption', $el) ) {
            $markup .=
            '<div class="caption lay-textformat-parent">'
                .$el['caption']
            .'</div>';
        }

        // error_log(print_r($markup, true));

        return $markup;
    }
    
    public static function addHtml5Video( $slide ) {

        // not setting autoplay to true because then also html5videos that are not in-view would autoplay
        // function "playPauseHtml5Videos" is autoplaying only in-view videos anyway, so we don't need the autoplay attribute
        // model.set('loop', true);
        // mute is always true, so autoplay can work
        $slide['mute'] = true;
        $slide['playpauseonclick'] = false;
        $slide['autoplay'] = false;
        $slide['controls'] = false;
        $slide['playicon'] = false;
        $showPlaceHolderImage = false;

        // not sure why but firefox doesnt let me lazyload html5videos in carousels when entering the page not by refresh but by coming from a different page on the same lay theme website
        // thats why I do 'noLazyLoad' => false

        // why do i have playpauseonclick here AND in 'model'/$slide ?
        $videoConfig = [
            'model' => $slide,
            'captionMarkup' => '',
            'showPlaceholderImage' => $showPlaceHolderImage,
            'usePaddingPlaceholder' => false,
            'playpauseonclick' => false,
            'useWrap' => false,
            'noLazyLoad' => true
        ];

        $markup = '';

        if( method_exists( 'LayElFunctions', 'getHTML5VideoMarkup') ) {
            $markup = '<div class="video-slide" data-ar="'.$slide['ar'].'">'.LayElFunctions::getHTML5VideoMarkup($videoConfig).'</div>';
        }

        return $markup;
    }

    public static function addText( $slide ){
        return '<div class="slide-text lay-textformat-parent">'.$slide['cont'].'</div>';
    }
    
    // adds image or project thumbnail
    public static function addImage( $slide, $fillSlides ){
        $markup = '';
        $srcset = '';
        $sizesToBeFilled = ['_265', '_512', '_768', '_1024', '_1280', '_1920', '_2560', '_3200', '_3840', '_4096'];
        $alt = get_bloginfo('name');
        $sizes = $slide['sizes'];
        $small = '';
        if( array_key_exists('_265', $sizes ) ){
            $small = $sizes['_265'];
        }else{
            $small = $sizes['full'];
        }
        
        if( method_exists( 'LTUtility', 'getFullURL' ) ) {
            $small = LTUtility::getFullURL($small);
        }
        $fullSrc = $sizes['full'];
        $lastFour = substr($fullSrc, -4);
        if( method_exists( 'LTUtility', 'getFullURL' ) ) {
            $fullSrc = LTUtility::getFullURL($sizes['full']);
        }
        $showOriginal = false;
        if( $lastFour == '.gif' ) {
            $showOriginal = true;
        }
        if( LayFrontend_Options::$misc_options_showoriginalimages && !LTUtility::$isPhone ){
            $showOriginal = true;
        }

        // 
        if( array_key_exists('attid', $slide) ) {
            $alt = get_post_meta( $slide['attid'], '_wp_attachment_image_alt', true );
            $srcset = wp_get_attachment_image_srcset( $slide['attid'] );
        }
        // there was some freak bug with some website (http://a184.uk/), where wp_get_attachment_image_srcset would always return '' and i could not figure out why, so i do this instead of just else{
        if( $srcset == '' || $srcset == false || !array_key_exists('attid', $slide) ){
            if( array_key_exists('alt', $slide) ) {
                $alt = $slide['alt'];
            }
            foreach($sizes as $key => $value) {
                $key = (string)$key;

                if( array_search($key, $sizesToBeFilled) !== false ) {
                    $src = $value;
                    if( method_exists( 'LTUtility', 'getFullURL' ) ) {
                        $src = LTUtility::getFullURL($src);
                    }
        
                    $srcset = $src.' '.substr($key, 1).'w, ' . $srcset;
                    $ix = array_search($key, $sizesToBeFilled);

                    if($ix !== false){
                        array_splice( $sizesToBeFilled, $ix, 1 );
                    }
                }
            }
            // fill rest of sizes with full image
            for($i=0; $i<count($sizesToBeFilled); $i++){
                $srcset .= $fullSrc.' '.substr($sizesToBeFilled[$i], 1).'w, ';
            }
            $srcset = substr($srcset, 0, -2);
        }

        $hasLink = ( $slide['type'] == "project" || $slide['type'] == "postThumbnail" ) ? true : false;
        if( $hasLink ){
            $slideLink = '';
            if( method_exists( 'LTUtility', 'filterURL' ) ) {
                $slideLink = LTUtility::filterURL($slide['link']);
            }
            $markup .= '<a href="'.$slideLink.'" data-title="'.$slide['title'].'" data-id="'.$slide['postid'].'" data-type="project">';
        }
        // data-aspectratio needed for https://github.com/aFarkas/lazysizes/tree/gh-pages/plugins/parent-fit
        $data_width_data_height_data_ar = array_key_exists('w', $slide) ? 'data-w="'.$slide['w'].'" data-h="'.$slide['h'].'" data-aspectratio="'.( $slide['w'] / $slide['h'] ).'"' : '';
        $object_fit_data = $fillSlides ? 'data-parent-fit="cover"' : '';
        $loading_class = 'carousel-instant-img';
        if( LayCarouselPluginOptions::$lazyload == 'on' ) {
            $loading_class = 'carousel-lazy-img';
        }
        // need to do data-lay-src and data-lay-srcset because otherwise lazysizes will load this image if it is cached, even if i dont use a "lazyload" html class
        if( $showOriginal ) {
            $markup .= '<img class="carousel-img '.$loading_class.' carousel-original-img" '.$object_fit_data.' src="data:image/png;base64,R0lGODlhFAAUAIAAAP///wAAACH5BAEAAAAALAAAAAAUABQAAAIRhI+py+0Po5y02ouz3rz7rxUAOw==" data-lay-src="'.$fullSrc.'" data-ar="'.$slide['ar'].'" '.$data_width_data_height_data_ar.' alt="'.$alt.'"/>';
        }
        else{
            $markup .= '<img class="carousel-img '.$loading_class.' carousel-responsive-img" '.$object_fit_data.' src="data:image/png;base64,R0lGODlhFAAUAIAAAP///wAAACH5BAEAAAAALAAAAAAUABQAAAIRhI+py+0Po5y02ouz3rz7rxUAOw==" data-lay-src="'.$small.'" data-lay-srcset="'.$srcset.'" sizes="" data-ar="'.$slide['ar'].'" '.$data_width_data_height_data_ar.' alt="'.$alt.'"/>';
        }
        if( $hasLink ){
            $markup .= '</a>';
        }

        return $markup;
    }

}
new LayCarouselPluginFrontend();