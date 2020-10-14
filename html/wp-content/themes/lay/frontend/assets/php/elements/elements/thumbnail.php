<?php
// todo: video thumbnail new
// todo: test mouseover image

class LayThumbnail{

    public $el;

    public function __construct($el){
        $this->el = $el;
    }

    public function getMarkup(){
        $pb = $this->el['ar'] * 100;
        $markup = '';

        $attid = $this->el['attid'];
        $alt = get_post_meta( $attid, '_wp_attachment_image_alt', true );
        
        $img = LayElFunctions::getLazyImg($this->el);

        $mo_thumb = array_key_exists('mo_sizes', $this->el) ? $this->el['mo_sizes'] : false;
        $mo_thumb_img = "";
        $has_mo_thumb_class = "";

        if( LayFrontend_Options::$misc_options_thumbnail_mouseover_image && is_array($mo_thumb) && !empty($mo_thumb) ){
            // error_log(print_r('bro', true));
            $mo_thumb_img = LayElFunctions::getMouseOverThumbImg($this->el);
            $has_mo_thumb_class = "has-mouseover-img";
        }

        $innerMarkup = $img.$mo_thumb_img;

        // if on touch device and we have "playsinline" support, then show video with playsinline attribute and autoplay
		// otherwise show image
        // playsinline is supported from iOS 10 WebKit and Chrome > 53
        $video_url = array_key_exists('video_url', $this->el) ? $this->el['video_url'] : false;
        if( LayFrontend_Options::$misc_options_thumbnail_video && $video_url != false && $video_url != "") {
            // is touch device and has playsinline support
            if ( LTUtility::$isTouchDevice && LTUtility::$supportsPlaysInlineOnTouchDevice ) {
                $model = array(
                    'autoplay' => true,
                    'loop' => true,
                    'mute' => true,
                    'mp4' => $video_url
                );
                // $innerMarkup = '<video autoplay playsinline loop muted><source src="'.$video_url.'" type="video/mp4"></video>';

                // todo: test this
                $innerMarkup = LayElFunctions::getHTML5VideoMarkupSimple($model);
                $has_mo_thumb_class = "";
                $pb = (int)$this->el['video_h'] / (int)$this->el['video_w'] * 100;
            }
            // is not touch device
            if( !LTUtility::$isTouchDevice ) {
                // when not touchdevice, use mouseover image
                // when the video thumbnail mouseover behaviour is set to only "play_on_mouseover", then don't use a autoplay attribute!
                $autoplay = true;
                if( LayFrontend_Options::$video_thumbnail_mouseover_behaviour == 'play_on_mouseover' ){
                    $autoplay = false;
                }
                $model = array(
                    'autoplay' => $autoplay,
                    'loop' => true,
                    'mute' => true,
                    'mp4' => $video_url
                );
                // todo: test this
                $innerMarkup = LayElFunctions::getHTML5VideoMarkupSimple($model).$mo_thumb_img;
                $pb = (int)$this->el['video_h'] / (int)$this->el['video_w'] * 100;
            }
        }
        // #video thumbnail new

        //project thumbnail
        
        $link = $this->el['link'];
        $link = get_permalink($this->el['postid']);
        
        $title = $this->el['title'];
        $postid = $this->el['postid'];
        $descr = array_key_exists('descr', $this->el) ? $this->el['descr'] : false;
        // sometimes there is an empty p at the start of descr, remove it
        // http://laythemeforum.com:4567/topic/5390/p-p-adds-in-project-description-for-thumbail-when-updating/2
        // <p></p>
        $substr = substr($descr, 0,7);
        if( $substr == '<p></p>' ) {
            $descr = substr($descr,7);
        }

        $cats_wp_terms_array = get_the_category($postid);
        $catids = array();

        for( $i = 0; $i<count($cats_wp_terms_array); $i++ ) {
            $cat = $cats_wp_terms_array[$i];
            $catids []= $cat->term_id;
        }

        $dataAttrs = 'data-id="'.$postid.'" data-catid="'.LayElFunctions::stringifyCatIds($catids).'" data-title="'.$title.'" data-type="project"';

        $mo_always = "";
        if( LayFrontend_Options::$fi_mo_touchdevice_behaviour == "mo_always" && LTUtility::$isTouchDevice == true ){
            $mo_always = "hover";
        }

        $markup = 
        '<a class="thumb '.$mo_always.' '.$has_mo_thumb_class.'" href="'.$link.'" '.$dataAttrs.'>';
            if(LayFrontend_Options::$pt_position == "above-image"){
                $markup .= '<div class="lay-textformat-parent above-image">';
                $markup .= '<span class="title '.LayFrontend_Options::$pt_textformat.'">'.$title.'</span>';
                $markup .= '</div>';
            }
        $markup .= '<div class="thumb-rel">'
        .'<div class="lay-textformat-parent titlewrap-on-image '.LayFrontend_Options::$pt_position.'">';

        $pt_on_image = strpos(LayFrontend_Options::$pt_position, 'on-image') === false ? false : true;

        if( $pt_on_image ){
            $markup .= '<span class="title '.LayFrontend_Options::$pt_textformat.'">'.$title.'</span>';
        }
        if( LayFrontend_Options::$pd_position == "on-image" && $descr != false && LayFrontend_Options::$activate_project_description && trim($descr) != ""){
            $markup .= '<span class="descr">'.$descr.'</span>';
        }

        $markup .= '</div>';

        //empty span for bgcolor
        $markup .=
        '<div class="ph" style="padding-bottom:'.Lay_LayoutFunctions::replaceCommaWithDot($pb).'%;">'
            .$innerMarkup
            .'<span></span>'
        .'</div>'
        .'</div>'
        .'<div class="lay-textformat-parent below-image">';
            if( LayFrontend_Options::$pt_position == "below-image" ) {
                $markup .= '<span class="title '.LayFrontend_Options::$pt_textformat.'">'.$title.'</span>';
            }
            if( LayFrontend_Options::$pd_position == "below-image" && $descr != false && LayFrontend_Options::$activate_project_description && trim($descr) != ""){
                $markup .= '<span class="descr">'.$descr.'</span>';
            }
        $markup .= '</div></a>';
        
        return '<div class="thumbnail-wrap">'.$markup.'</div>';
    }
}