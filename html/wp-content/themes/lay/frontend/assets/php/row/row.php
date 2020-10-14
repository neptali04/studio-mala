<?php
// todo: everywhere I use showOriginalImages, also use is no Touchdevice
// todo: test gif

class LayRow {

    public $row_classes;
    public $row_inner_classes;
    public $row;
    public $index;
    // bg image
    public $img;
    // bg video
    public $videoMarkup;
    public $html_classes;
    public $html_id;
    public $background_color_css;
    public $column_wrap_classes;
    public $linkForEmptyBackground;

    public function __construct($row, $index) {
        $this->index = $index;
        $this->row = $row;
        $this->row_classes = '';
        $this->row_inner_classes = '';
        $this->img = '';

        $bgcolor = array_key_exists('bgcolor', $row) ? $row['bgcolor'] : '';
		$rowGutter = array_key_exists('rowGutter', $row) ? $row['rowGutter'] : '';
		$row100vh = array_key_exists('row100vh', $row) ? $row['row100vh'] : '';
		$bgimage = array_key_exists('bgimage', $row) ? $row['bgimage'] : '';
		$bgvideo = array_key_exists('bgvideo', $row) ? $row['bgvideo'] : '';
		$link = array_key_exists('link', $row) ? $row['link'] : '';
		$this->html_classes = array_key_exists('classes', $row) ? $row['classes'] : '';
		$this->html_id = array_key_exists('html_id', $row) ? $row['html_id'] : '';

		$row_has_bg = false;
		$linkMarkup = false;

        if( count($row['columns']) == 0 ){
            $this->row_classes .= ' empty';
        }
        if( count($row['columns']) == 1 ){
            $this->row_classes .= ' one-col-row';
        }
        if( array_key_exists( 'row100vh', $row ) && $row['row100vh'] == true ) {
            $this->row_classes .= ' _100vh';
            $this->row_inner_classes .= ' _100vh';
            $this->column_wrap_classes .= ' _100vh';
        }
        if( array_key_exists('link', $row ) ) {
            $this->row_classes .= ' row-has-link';
            if( is_plugin_active( 'laytheme-imagehover/laytheme-imagehover.php' ) && array_key_exists('hoverimageid', $row['link']) && $row['link']['hoverimageid'] != null ) {
                $this->row_classes .= ' row-has-hoverimage';
            } else {
                $this->row_classes .= ' no-row-hoverimage';
            }
        } else {
            $this->row_classes .= ' no-row-hoverimage';
        }

        $setBgColor = true;
        $rowBgClass = '';

		// link markup
		if ( is_array($link) && array_key_exists('url', $link) ) {
			$target = '';
			if ( $link['newtab'] == true) {
				$target = ' target="_blank"';
            }
            $catidAttrs = array_key_exists('catid', $link) ? ' data-catid="'.LayElFunctions::stringifyCatIds($link['catid']).'"' : '';
			$dataAttrs = $link['type'] == '' ? '' : 'data-id="'.$link['id'].'" data-type="'.$link['type'].'" data-title="'.$link['title'].'"'.$catidAttrs;
            // imagehover link
            if( is_plugin_active( 'laytheme-imagehover/laytheme-imagehover.php' ) && array_key_exists('hoverimageid', $link) && $link['hoverimageid'] != null ) {
                $dataAttrs .= ' data-hoverimageid="'.$link['hoverimageid'].'"';
            }
            $linkMarkup = '<a '.$dataAttrs.' href="' . LTUtility::getFullURL($link['url']) . '"' . $target;
		}

        // bg image
        if( array_key_exists('bgimage', $row) && $row['bgimage'] != '' ) {
            // cannot use el_functions's getLazyImg function here, but I'll try to mimick it as best a possible
            // earlier lay theme versions do not save attid of an image, so here I care about this case for backwards compatibility
            $setBgColor = false;
            $row_has_bg = true;
			$full_src = LTUtility::getFullURL($bgimage['url']);
            $smallest_src = $full_src;
            if( array_key_exists('_265', $bgimage['sizes']) ) {
                if( array_key_exists('url', $bgimage['sizes']['_265']) ){
                    $smallest_src = LTUtility::getFullURL( $bgimage['sizes']['_265']['url'] );
                } else {
                    // backwards compatibility for when this did not contain url and w and h but the url was saved in ['sizes']['_265'] directly
                    $smallest_src = LTUtility::getFullURL( $bgimage['sizes']['_265'] );
                }
            } 

			$srcset = LayRowFunctions::getBgSet($bgimage);
            $ar = LayRowFunctions::getBgAr($bgimage);
            
            $this->img = '';
			if ($linkMarkup != false) {
				$this->img = $linkMarkup;
				$rowBgClass .= 'row-bg-link';
			} else {
				$this->img = '<div';
			}
            $rowBgClass .= ' background-image';
            $isGif = substr($full_src, -4) == '.gif' ? true : false;
            
            $alt = get_bloginfo('name');
            $_alt = '';
            if( array_key_exists('attid', $bgimage) ) {
                $attid = $bgimage['attid'];
                $_alt = get_post_meta( $attid, '_wp_attachment_image_alt', true );
            }
            if( $_alt != '' ) {
                $alt = $_alt;
            }
            $this->img .= ' class="'.$rowBgClass.'">';
            
            // data w and data h are not the actual heights and widths of the original image. However, i need this data to calculate the correct sizes attribute, it is about the aspect ratio, not the real width and height of the original image
            $dataHdataW = LayRowFunctions::getBgDataWDataH($bgimage);

            switch( LayFrontend_Options::$image_loading ) {
                case 'instant_load':
                    if( $isGif ) {
                        $this->img .= '<img class="lay-gif" src="data:image/png;base64,R0lGODlhFAAUAIAAAP///wAAACH5BAEAAAAALAAAAAAUABQAAAIRhI+py+0Po5y02ouz3rz7rxUAOw==" data-ar="'.$ar.'" data-src="'.$full_src.'" alt="'.$alt.'"/>';
                    }
                    else if( LayFrontend_Options::$misc_options_showoriginalimages && !LTUtility::$isPhone ){
                        $this->img .= '<img class="lay-image-original" src="data:image/png;base64,R0lGODlhFAAUAIAAAP///wAAACH5BAEAAAAALAAAAAAUABQAAAIRhI+py+0Po5y02ouz3rz7rxUAOw==" data-ar="'.$ar.'" data-src="'.$full_src.'" alt="'.$alt.'"/>';
                    }
                    else{
                        $this->img .= '<img class="lay-image-responsive setsizes setsizes-objectfit-cover" src="data:image/png;base64,R0lGODlhFAAUAIAAAP///wAAACH5BAEAAAAALAAAAAAUABQAAAIRhI+py+0Po5y02ouz3rz7rxUAOw==" data-ar="'.$ar.'" data-src="'.$smallest_src.'" data-srcset="'.$srcset.'" sizes="" alt="'.$alt.'" '.$dataHdataW.'/>';
                    }
                break;
                case 'lazy_load':
                    // https://github.com/aFarkas/lazysizes/tree/gh-pages/plugins/parent-fit
                    // needs data-aspectratio: <!-- Usage of the data-aspectratio attribute: Divide width by height: 400/800 = data-aspectratio="0.5" -->
                    // todo: have imageW and imageH in json instead of this, also put imageAttid into json and get the image using that attid. To be compatible with cdn plugins
                    $aspect_ratio_for_lazysizes = array_key_exists('_265', $bgimage['sizes']) ? $bgimage['sizes']['_265']['w'] / $bgimage['sizes']['_265']['h'] : '';
                    if( $isGif ) {
                        $this->img .= '<img class="lay-gif lazyload" src="data:image/png;base64,R0lGODlhFAAUAIAAAP///wAAACH5BAEAAAAALAAAAAAUABQAAAIRhI+py+0Po5y02ouz3rz7rxUAOw==" data-ar="'.$ar.'" data-src="'.$full_src.'" alt="'.$alt.'"/>';
                    }
                    if( LayFrontend_Options::$misc_options_showoriginalimages && !LTUtility::$isPhone ){
                        $this->img .= '<img class="lay-image-original lazyload" src="data:image/png;base64,R0lGODlhFAAUAIAAAP///wAAACH5BAEAAAAALAAAAAAUABQAAAIRhI+py+0Po5y02ouz3rz7rxUAOw==" data-ar="'.$ar.'" data-src="'.$full_src.'" alt="'.$alt.'"/>';
                    }
                    else{
                        $this->img .= '<img class="lazyload" src="data:image/png;base64,R0lGODlhFAAUAIAAAP///wAAACH5BAEAAAAALAAAAAAUABQAAAIRhI+py+0Po5y02ouz3rz7rxUAOw==" data-ar="'.$ar.'" data-src="'.$smallest_src.'" data-srcset="'.$srcset.'" data-aspectratio="'.$aspect_ratio_for_lazysizes.'" data-sizes="auto" data-parent-fit="cover" alt="'.$alt.'"/>';
                    }
                break;
            }

			if ($linkMarkup != false) {
				$this->img .= '</a>';
			} else {
				$this->img .= '</div>';
			}
        }

        // todo: test bg video and bg video with image set

        // bg video.
		// if on touch device and we have "playsinline" support, then show video with playsinline attribute
		// otherwise show image if available
		// playsinline is supported from iOS 10 WebKit and Chrome > 53
		// bg video, on touch devices just show image as background in the same way I use a normal row background image
		if( is_array($bgvideo) && array_key_exists('mp4', $bgvideo) ) {

			$setBgColor = false;
			$row_has_bg = true;
			$this->videoMarkup = '';
			$mp4 = LTUtility::getFullURL($bgvideo['mp4']);
			$poster = array_key_exists('image', $bgvideo) ? '' : 'poster="'.LTUtility::getFullURL($bgvideo['image']['full']).'"';

			if ($linkMarkup != false) {
				$this->videoMarkup = $linkMarkup;
				$rowBgClass .= 'row-bg-link';
			} else {
				$this->videoMarkup = '<div';
            }
            
            // show normal video if on desktop or if on touchdevice and playsinline is supported
            // don't need poster when I'm not on touch device
            if ( LTUtility::$supportsPlaysInlineOnTouchDevice && LTUtility::$isTouchDevice || !LTUtility::$isTouchDevice ) {
                $rowBgClass .= ' background-video';
                $this->videoMarkup .= ' class="'.$rowBgClass.'"><video data-ar="'.$bgvideo['mp4Ar'].'" preload="none" playsinline muted data-autoplay loop><source src="'.$mp4.'" type="video/mp4"></video>';
            }
            // no playsinline support. just add background image if available
            else if( !LTUtility::$supportsPlaysInlineOnTouchDevice && LTUtility::$isTouchDevice ) {
                if( array_key_exists('image', $bgvideo) && $bgvideo['image'] != "" ) {
                    $full_src = LTUtility::getFullURL($bgvideo['image']['full']);
                    $smallest_src = LTUtility::getFullURL($bgvideo['image']['_265']);
                    $isGif = substr($full_src, -4) == '.gif' ? true : false;
                    $alt = '';
                    $srcset = '';
                    if( array_key_exists('imageAttid', $bgvideo) ){
                        $alt = get_post_meta( $bgvideo['imageAttid'], '_wp_attachment_image_alt', true );
                        $srcset = wp_get_attachment_image_srcset( $bgvideo['imageAttid'] );
                    }else{
                        $alt = get_bloginfo('name');
                        $srcset = LayRowFunctions::getBackgroundVideoBgSet( $bgvideo['image'] );
                    }
        
                    $ar = array_key_exists('imageAr', $bgvideo) ? $bgvideo['imageAr'] : '';
                    // i need data-ar so i can size the backgrounds in case browser has no object-fit
                    $rowBgClass .= ' background-image';
                    $this->videoMarkup .= ' class="'.$rowBgClass.'">';
                    // data w and data h are not the actual heights and widths of the original image. However, i need this data to calculate the correct sizes attribute, it is about the aspect ratio, not the real width and height of the original image
                    $dataHdataW = LayRowFunctions::getBgDataWDataH($bgimage);
                    $aspect_ratio_for_lazysizes = array_key_exists('imageW', $bgvideo) ? $bgvideo['imageW'] / $bgvideo['imageH'] : '';
                    // same as in getLazyImg
                    switch( LayFrontend_Options::$image_loading ) {
                        case 'instant_load':
                            if( $isGif ) {
                                $this->videoMarkup .= '<img class="lay-gif" src="data:image/png;base64,R0lGODlhFAAUAIAAAP///wAAACH5BAEAAAAALAAAAAAUABQAAAIRhI+py+0Po5y02ouz3rz7rxUAOw==" data-ar="'.$ar.'" data-src="'.$full_src.'" alt="'.$alt.'"/>';
                            }
                            // im just changing 'isDesktopOrTabletSize' to !LTUtility::$isPhone, i think that makes sense
                            else if( LayFrontend_Options::$misc_options_showoriginalimages && !LTUtility::$isPhone ){
                                $this->videoMarkup .= '<img class="lay-image-original" src="data:image/png;base64,R0lGODlhFAAUAIAAAP///wAAACH5BAEAAAAALAAAAAAUABQAAAIRhI+py+0Po5y02ouz3rz7rxUAOw==" data-ar="'.$ar.'" data-src="'.$full_src.'" alt="'.$alt.'"/>';
                            }
                            else{
                                $this->videoMarkup .= '<img class="lay-image-responsive setsizes setsizes-objectfit-cover" src="data:image/png;base64,R0lGODlhFAAUAIAAAP///wAAACH5BAEAAAAALAAAAAAUABQAAAIRhI+py+0Po5y02ouz3rz7rxUAOw==" data-ar="'.$ar.'" data-src="'.$smallest_src.'" data-srcset="'.$srcset.'" sizes="" alt="'.$alt.'" '.$dataHdataW.'/>';
                            }
                        break;
                        case 'lazy_load':
                            // https://github.com/aFarkas/lazysizes/tree/gh-pages/plugins/parent-fit
                            // needs data-aspectratio: <!-- Usage of the data-aspectratio attribute: Divide width by height: 400/800 = data-aspectratio="0.5" -->
                            if( $isGif ) {
                                $this->videoMarkup .= '<img class="lay-gif lazyload" src="data:image/png;base64,R0lGODlhFAAUAIAAAP///wAAACH5BAEAAAAALAAAAAAUABQAAAIRhI+py+0Po5y02ouz3rz7rxUAOw==" data-ar="'.$ar.'" data-src="'.$full_src.'" alt="'.$alt.'"/>';
                            }
                            if( LayFrontend_Options::$misc_options_showoriginalimages && !LTUtility::$isPhone ){
                                $this->videoMarkup .= '<img class="lay-image-original lazyload" src="data:image/png;base64,R0lGODlhFAAUAIAAAP///wAAACH5BAEAAAAALAAAAAAUABQAAAIRhI+py+0Po5y02ouz3rz7rxUAOw==" data-ar="'.$ar.'" data-src="'.$full_src.'" alt="'.$alt.'"/>';
                            }
                            else{
                                $this->videoMarkup .= '<img class="lazyload" src="data:image/png;base64,R0lGODlhFAAUAIAAAP///wAAACH5BAEAAAAALAAAAAAUABQAAAIRhI+py+0Po5y02ouz3rz7rxUAOw==" data-ar="'.$ar.'" data-src="'.$smallest_src.'" data-srcset="'.$srcset.'" data-aspectratio="'.$aspect_ratio_for_lazysizes.'" data-sizes="auto" data-parent-fit="cover" alt="'.$alt.'"/>';
                            }
                        break;
                    }
                }
            }

            if ($linkMarkup != false) {
                $this->videoMarkup .= '</a>';
            } else {
                $this->videoMarkup .= '</div>';
            }
        }

        $linkForEmptyBackground = '';
		if (!$row_has_bg && $linkMarkup != false) {
            $linkMarkup .= ' class="row-bg-link no-row-bg-link-children"></a>';
            $this->linkForEmptyBackground = $linkMarkup;
		}

		// set bg color only if there is no bgvideo or bgimage. because otherwise setting the bgcolor would overlay the bgvideo/-image, because it is applied to the wrapping container
		if( $bgcolor != '' && $setBgColor ) {
            $row_has_bg = true;
            $this->background_color_css = 'style="background-color:'.$bgcolor.';"';
		}

		if( $row_has_bg ) {
			$this->row_classes .= ' has-background';
		}
    }

    public function getOpeningMarkup( ){
        // todo: test row background color
        $id_attr = $this->html_id != '' ? 'id="'.$this->html_id.'"' : '';
        return
        '<div class="row '.$this->row_classes.' '.$this->html_classes.' row-'.$this->index.'" '.$id_attr.' '.$this->background_color_css.'>
                <div class="row-inner '.$this->row_inner_classes.'">
                    <div class="column-wrap '.$this->column_wrap_classes.'">';
    }

    public function getClosingMarkup( ){
        return
        '</div>
            </div>
                '.$this->img.'
                '.$this->videoMarkup.'
                '.$this->linkForEmptyBackground.'
                </div>';
    }

}