<?php

class Gridder_CSS_Output{

	public function __construct(){
        add_action( 'admin_head', array($this, 'lay_customize_css_for_admin'));
    }

    public static function lay_customize_css_for_admin(){
        // projecttitle styles for backend edit category screen
        $screen = get_current_screen();
        if ( $screen->id != 'edit-category' && $screen->id != 'post' && $screen->id != 'page' ) {
            return;
        }
        // for tinymce style
        echo
        '<script type="text/javascript">
            var customizerPassedData = {};
            customizerPassedData.linkcolor = "'.get_theme_mod('link_color', '#00f').'";
            customizerPassedData.linkUnderlineStrokeWidth = "'.get_theme_mod('link_underline_strokewidth', '0').'";
        </script>';

        $css = '';
        // project title 
        $mod = get_theme_mod("pt_visibility", 'always-show');
        if($mod == 'hide'){
            $css .= '#gridder #gridder-wrap .title, #gridder .lay-input-modal .title{display:none!important;}';
        }
        $css .= CSS_Output::generate_opacity_css('#gridder #gridder-wrap .title, #gridder .lay-input-modal .title', 'pt_opacity', 100);
        $css .= CSS_Output::generate_css('#gridder .container', 'background-color', 'bg_color', '#ffffff');
        $css .= CSS_Output::pt_generate_position_css('#gridder #gridder-wrap .titlewrap-on-image, #gridder .lay-input-modal .titlewrap-on-image');
        $css .= CSS_Output::generate_opacity_css('.title', 'pt_opacity', 100);

        // if a textformat was set for project title, then we don't need to output css for it, cause we would use the textformat's css instead
        if(get_theme_mod("pt_textformat", "_Default") == ""){
            $css .= CSS_Output::generate_css('#gridder #gridder-wrap .title, #gridder .lay-input-modal .title', 'font-family', 'pt_fontfamily', Customizer::$defaults['fontfamily']);
            $css .= CSS_Output::generate_css('#gridder #gridder-wrap .title, #gridder .lay-input-modal .title', 'color', 'pt_color', Customizer::$defaults['color']);
            $css .= CSS_Output::generate_css('#gridder #gridder-wrap .title, #gridder .lay-input-modal .title', 'letter-spacing', 'pt_letterspacing', Customizer::$defaults['letterspacing'], '', 'em');
            $css .= CSS_Output::generate_css('#gridder #gridder-wrap .title, #gridder .lay-input-modal .title', 'line-height', 'pt_lineheight', Customizer::$defaults['lineheight'], '', '');
            // textalign only applies to project title when its position is below image
            $css .= CSS_Output::generate_css('#gridder #gridder-wrap .title, #gridder .lay-input-modal .title', 'text-align', 'pt_align', 'left');
            $css .= CSS_Output::generate_css('#gridder #gridder-wrap .title, #gridder .lay-input-modal .title', 'font-weight', 'pt_fontweight', Customizer::$defaults['fontweight']);

            $pt_fontsize_mu = CSS_Output::get_mu('pt_fontsize_mu', 'px');
            $css .= CSS_Output::generate_css('#gridder #gridder-wrap .title, #gridder .lay-input-modal .title', 'font-size', 'pt_fontsize', Customizer::$defaults['fontsize'], '', $pt_fontsize_mu);
        }

        $css .= CSS_Output::generate_css('#gridder #gridder-wrap .above-image .title, .lay-input-modal .above-image .title', 'margin-bottom', 'pt_spacetop', '5', '', 'px');
        $css .= CSS_Output::generate_css('#gridder #gridder-wrap .below-image .title, .lay-input-modal .below-image .title', 'margin-top', 'pt_spacetop', '5', '', 'px');

        $css .= CSS_Output::generate_css('#gridder .text a, #gridder .caption a, #gridder .text-to-add a', 'color', 'link_color', '#00f');
        $css .= CSS_Output::generate_css('#gridder .text a, #gridder .caption a, #gridder .text-to-add a', 'border-bottom-width', 'link_underline_strokewidth', '0', '', 'px');
        $css .= CSS_Output::generate_css('#gridder .container', 'background-color', 'bg_color', '#ffffff');

        // project description
        
        // pd position
        $pd_position = get_theme_mod('pd_position', 'below-image');
        $pt_position = get_theme_mod('pt_position', 'below-image');
        // when pt position is below or above and pd pos is on-image, do center css
        // otherwise, the pt alignment defines where pt and pd are (can be on-image-top-left, or on-image-top-right, on-image-bottom-left etc)
        if( $pd_position == 'on-image' && strpos($pt_position, 'on-image') === false ){
            $css .= 
            '#gridder #gridder-wrap .titlewrap-on-image,
            #gridder .lay-input-modal .titlewrap-on-image{
                top: 50%;
                left: 50%;
                -webkit-transform: translate(-50%,-50%);
                -moz-transform: translate(-50%,-50%);
                -ms-transform: translate(-50%,-50%);
                -o-transform: translate(-50%,-50%);
                transform: translate(-50%,-50%);
            }';   
        }
        $css .= CSS_Output::generate_opacity_css('#gridder #gridder-wrap .descr, #gridder .lay-input-modal .descr', 'pd_opacity', 100);
        $css .= CSS_Output::generate_css('#gridder #gridder-wrap .descr, #gridder .lay-input-modal .descr', 'margin-top', 'pd_spacetop', '0', '', 'px');
        
        echo 
        '<!-- customizer css -->
        <style>'.$css.'</style>';

    }

}
new Gridder_CSS_Output();