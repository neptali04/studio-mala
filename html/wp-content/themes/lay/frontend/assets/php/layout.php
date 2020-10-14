<?php
require get_template_directory().'/frontend/assets/php/layout_functions.php';
require get_template_directory().'/frontend/assets/php/footer/footer.php';
require get_template_directory().'/frontend/assets/php/row/row_functions.php';
require get_template_directory().'/frontend/assets/php/row/row.php';
require get_template_directory().'/frontend/assets/php/col/col.php';
require get_template_directory().'/frontend/assets/php/elements/el_functions.php';
require get_template_directory().'/frontend/assets/php/elements/el.php';
require get_template_directory().'/frontend/assets/php/cover/cover.php';
require get_template_directory().'/frontend/assets/php/cover/cover_functions.php';
require get_template_directory().'/frontend/assets/php/password_screen.php';

// todo: add lay-col-needs-leftframe-margin
class Lay_Layout{

    public function __construct(){
        add_action( 'wp_ajax_get_laytheme_layout', array( $this, 'getLayoutAjax' ) );
        add_action( 'wp_ajax_nopriv_get_laytheme_layout', array( $this, 'getLayoutAjax' ) );
    }

    public static function getLayoutAjax(){
        $id = $_POST['id'];
        $type = $_POST['type'];
        $password = $_POST['password'];
        $layout = Lay_Layout::getLayout($id, $type, $password);
        echo json_encode($layout);
        wp_die();
    }

    public static function getLayoutInit(){
        // error_log(print_r('getLayoutInit', true));
        $data = Frontend::$current_type_id_slug_catid;

        $id = $data["id"];
        $type = $data["type"];
        $layout = Lay_Layout::getLayout($id, $type);
        return $layout['markup'];
    }

    // todo: password protection
    // todo: custom phone layout: only load content and play videos when it is visible?

    // I need layoutObj, type and obj for the newpageshown event
    // getLayout returns an array of "markup", "layoutObj", "type", "obj". These are needed for the newpageshown javascript event
    // It also returns a "notification" array member, used for password protection status, so my javascript can shake the password input on wrongpassword

    // todo: split this function? getLayout

    public static function getLayout($id, $type, $password = '') {

        $password_status = Lay_Password_Screen::getPasswordStatus($id, $type, $password);

        switch( $password_status ) {
            case 'protected':
                return array(
                    'markup' => Lay_Password_Screen::getPasswordScreen(),
                    'type' => $type,
                    'layoutObj' => '',
                    'obj' => '',
                    'notification' => 'protected'
                );
            break;
            case 'wrongpassword':
                return array(
                    'markup' => '',
                    'type' => $type,
                    'layoutObj' => '',
                    'obj' => '',
                    'notification' => 'wrongpassword'
                );
            break;
        }

        // no password protection, just continue

        $desktop_json = '';
        $cpl_json = '';
        $desktop_footer_json = '';
        $cpl_footer_json = '';

        // for newpageshown event:
        $layoutObj;
        $obj = array( 
            'id' => '',
            'type' => '',
            'slug' => '',
            'catid' => ''
        );

        // cover
        $cover_on_desktop = LayCoverFunctions::hasCover( $type, $id );
        $desktop_cover_is_100vh_set_by_user = false;
        $cover_on_phone = $cover_on_desktop && LayFrontend_Options::$cover_disable_for_phone == false ? true : false;

        // disable cover for desktop and phone when magnetic slides is active, because cover feature is incompatible with magnetic slides feature
        $magnetic_slides_active = false;
        if( class_exists('LayThemeMagneticSlides') && method_exists('LayThemeMagneticSlides', 'isActiveHere') ){
            $magnetic_slides_active = LayThemeMagneticSlides::isActiveHere($id, $type);
            if( $magnetic_slides_active == true ) {
                $cover_on_desktop = false;
            }
        }
        $magnetic_slides_active_on_phone = false;
        if( class_exists('LayThemeMagneticSlides') && method_exists('LayThemeMagneticSlides', 'activeOnPhone') ){
            if( LayThemeMagneticSlides::activeOnPhone() == true && $magnetic_slides_active ) {
                $magnetic_slides_active_on_phone = true;
            }
            if( $magnetic_slides_active_on_phone == true ){
                $cover_on_phone = false;
            }
        }

        switch($type){
            case "category":
                $desktop_json = get_option($id."_category_gridder_json", '');
                $cpl_json = get_option( $id.'_phone_category_gridder_json', '' );
                $term = get_term($id);
                $obj['id'] = $term->term_id;
                $obj['slug'] = $term->slug;
                $obj['type'] = 'category';
                $obj['catid'] = array($term->term_id);
            break;
            case "project":
                $desktop_json = get_post_meta( $id, '_gridder_json', true );
                $cpl_json = get_post_meta( $id, '_phone_gridder_json', true );
                $post = get_post($id);
                $obj['id'] = $post->ID;
                $obj['slug'] = $post->post_name;
                $obj['type'] = 'project';
                $obj['catid'] = wp_get_post_categories($post->ID, array('fields'=>'ids'));
            break;
            case "page":
                $desktop_json = get_post_meta( $id, '_gridder_json', true );
                $cpl_json = get_post_meta( $id, '_phone_gridder_json', true );
                $page = get_post($id);
                $obj['id'] = $page->ID;
                $obj['slug'] = $page->post_name;
                $obj['type'] = 'page';
                $obj['catid'] = '';
            break;
        }

        // no content, like when someone didnt create a page yet but that page is shown on frontend
        if( $desktop_json == '' ) {
            return array(
                'markup' => '<div class="lay-content"></div>',
                'type' => $type,
                'layoutObj' => '',
                'obj' => '',
                'notification' => 'success'
            );
        }

        $footer_json_array = Lay_Footer::getFooterJSON($id, $type);

        if( is_array( $footer_json_array ) ) {
            $desktop_footer_json = array_key_exists('desktop', $footer_json_array) ? $footer_json_array['desktop'] : '';
            $cpl_footer_json = array_key_exists('phone', $footer_json_array) ? $footer_json_array['phone'] : '';
        }

        // decode JSON
        $desktop_arr = json_decode($desktop_json, true);
        $cpl_arr = json_decode($cpl_json, true);
        $footer_desktop_arr = json_decode($desktop_footer_json, true);
        $cpl_footer_arr = json_decode($cpl_footer_json, true);

        // for newpageshown event:
        $layoutObj = $desktop_arr;

        // prepare Rows
        $desktop_rows = false;
        $cpl_rows = false;
        $desktop_footer_rows = false;
        $cpl_footer_rows = false;
        $desktop_cover_row = false;
        $cpl_cover_rows = false;

        // markups
        $desktop_cover_markup = false;
        $desktop_markup = false;
        $cpl_markup = false;
        $cpl_cover_markup = false;
        $desktop_footer_markup = false;
        $cpl_footer_markup = false;
        $cpl_row_col_el_markup = '';

        // DESKTOP
        if( is_array($desktop_arr) ){
            $desktop_rows = Lay_Layout::prepareRows($desktop_arr);
        }
        // split up rows into cover row and desktop rows
        if( $cover_on_desktop && is_array( $desktop_rows ) ) {
            // https://www.php.net/manual/en/function.array-shift.php
            $desktop_cover_row = array_shift( $desktop_rows );
            $desktop_cover_is_100vh_set_by_user = Lay_LayoutFunctions::getRowIs100vh($desktop_cover_row);
            $desktop_cover_row['row100vh'] = true;
            $desktop_cover_row = array($desktop_cover_row);
            $desktop_cover_markup = Lay_Layout::getRowColElMarkup($desktop_cover_row);
        }
        if( is_array($desktop_arr) ){
            $desktop_markup = Lay_Layout::getRowColElMarkup($desktop_rows);
        }

        // CPL
        if( is_array($cpl_arr) ){
            $cpl_rows = Lay_Layout::prepareRows($cpl_arr);
        }
        // split up rows into cover row and phone rows
        if( $cover_on_phone && is_array($cpl_arr) ){
            // https://www.php.net/manual/en/function.array-shift.php
            $cpl_cover_rows = array_shift( $cpl_rows );
            $cpl_cover_rows['row100vh'] = true;
            $cpl_cover_rows = array($cpl_cover_rows);
            $cpl_cover_markup = Lay_Layout::getRowColElMarkup($cpl_cover_rows);
        }
        if( is_array($cpl_arr) ){
            $cpl_row_col_el_markup = Lay_Layout::getRowColElMarkup($cpl_rows);
        }

        // FOOTER
        if( is_array($footer_desktop_arr) ){
            $desktop_footer_rows = Lay_Layout::prepareRows($footer_desktop_arr);
            $desktop_footer_markup = Lay_Layout::getRowColElMarkup($desktop_footer_rows);
        }
        if( is_array($cpl_footer_arr) ){
            $cpl_footer_rows = Lay_Layout::prepareRows($cpl_footer_arr);
            $cpl_footer_markup = Lay_Layout::getRowColElMarkup($cpl_footer_rows);
        }

        // Custom Phone Layout
        $cpl_exists_html_class = 'nocustomphonegrid';
        $cover_cpl_class = '';
        if( LayFrontend_Options::$phone_layout_active && is_array($cpl_arr) ) {
            // Cover CPL
            $cover_cpl_class = 'cpl-nocover';
            $cpl_cover_rowgutter_css = '';
            $cover_cpl_css = '';
            // todo: && !isFullscreenSliderActive()
            // if( cover_controller.hasCover(type, obj.id) && !isFullscreenSliderActive() ){
            if( $cover_on_phone ) {
                $cover_cpl_class = 'cpl-hascover';
                $cover_cpl_css = Lay_LayoutFunctions::getGridCSS($cpl_arr, '.cover-region-phone', true);
                // set main grid padding-top to first rowgutter
                $cpl_cover_rowgutter_css = Lay_LayoutFunctions::getFirstRowGutterAsPaddingTopForCoverCSS($cpl_arr, '#custom-phone-grid', true);
                // need .cover-inner so the grid's background color can overwrite the global background color set in customizer
                $cpl_cover_markup = 
                '<div class="cover-region cover-region-phone _100vh">
                    <div class="cover-inner _100vh">
                        '.$cpl_cover_markup.'
                    </div>
                </div>
                <div class="cover-region-placeholder cover-region-placeholder-phone _100vh"></div>';
            }

            $cpl_exists_html_class = 'hascustomphonegrid';
            $cpl_css = Lay_LayoutFunctions::getGridCSS($cpl_arr, '#custom-phone-grid', true);
            $cpl_frame_css = Lay_LayoutFunctions::getGridFrameCSS($cpl_arr, '#custom-phone-grid', '#custom-phone-grid', true);
            if( $cover_on_phone ) {
                $cpl_frame_css = Lay_LayoutFunctions::getGridFrameCSS($cpl_arr, '.cover-region-phone .cover-inner', '#custom-phone-grid', true);
            }
            $cpl_rows_css = Lay_LayoutFunctions::getRowsMarginBottomCSS($cpl_rows, '#custom-phone-grid', true);
            $cpl_background_color_css = Lay_LayoutFunctions::getBackgroundCSS($cpl_arr, '#custom-phone-grid, .cover-region-phone .cover-inner');

            $cpl_markup = 
                '<!-- Start CPL Layout -->'
                .$cpl_cover_rowgutter_css
                .$cover_cpl_css
                .$cpl_cover_markup;
                if( $cover_on_phone ) {
                    // need cover content wrap, so i can set global bg color and bg image to it
                    // the inner div: .grid can now be set to the grid-specific background-color, overwriting .cover-content's background
                    // i cannot just set the global background color or background image to an outer div like .lay-content, because then ->
                    // when cover would be active and i would scroll down, the transparent div that is on top of the cover would not hide it beneath
                    $cpl_markup .= '<div class="cover-content">';
                }
                // need .grid-inner wrapper so I can have a fullscreen slider that is horizontal
                $cpl_markup .= 
                '<div id="custom-phone-grid" class="grid">
                    <div class="grid-inner">
                        '.$cpl_frame_css.'
                        '.$cpl_rows_css.'
                        '.$cpl_css.'
                        '.$cpl_background_color_css.'
                        '.$cpl_row_col_el_markup.'
                    </div>
                </div>';
                if( $cover_on_phone ) {
                    $cpl_markup .= '</div>';
                }
                $cpl_markup .= 
                '<!-- End CPL Layout -->';
        }

        // Footer Custom Phone Layout
        $footer_cpl_markup = '';
        $footer_cpl_exists_html_class = 'footer-nocustomphonegrid';
        if( LayFrontend_Options::$phone_layout_active && is_array($cpl_footer_arr) ) {
            $footer_cpl_exists_html_class = 'footer-hascustomphonegrid';
            $footer_cpl_css = Lay_LayoutFunctions::getGridCSS($cpl_footer_arr, '#footer-custom-phone-grid', true);
            $footer_cpl_frame_css = Lay_LayoutFunctions::getGridFrameCSS($cpl_footer_arr, '#footer-custom-phone-grid', '#footer-custom-phone-grid', true);
            $footer_cpl_rows_css = Lay_LayoutFunctions::getRowsMarginBottomCSS($cpl_footer_rows, '#footer-custom-phone-grid', true);
            $footer_cpl_background_color_css = Lay_LayoutFunctions::getBackgroundCSS($cpl_footer_arr, '#footer-custom-phone-grid');
            
            $footer_cpl_markup = 
            '<div id="footer-custom-phone-grid" class="grid">
                '.$footer_cpl_frame_css.'
                '.$footer_cpl_rows_css.'
                '.$footer_cpl_css.'
                '.$footer_cpl_background_color_css.'
                '.$cpl_footer_markup.'
            </div>';
        }

        // Desktop CSS
        $desktop_css = Lay_LayoutFunctions::getGridCSS($desktop_arr, '#grid', false);
        $desktop_frame_css = Lay_LayoutFunctions::getGridFrameCSS($desktop_arr, '#grid', '#grid', false);
        if( $cover_on_desktop ){
            $desktop_frame_css = Lay_LayoutFunctions::getGridFrameCSS($desktop_arr, '.cover-region-desktop .cover-inner', '#grid', false);
        }
        $desktop_rows_css = Lay_LayoutFunctions::getRowsMarginBottomCSS($desktop_rows, '#grid', false);
        // need to set background color for .cover-inner too because it is not inside #grid div
        $background_color_css = Lay_LayoutFunctions::getBackgroundCSS($desktop_arr, '#grid, .cover-region-desktop .cover-inner');

        // Is there a footer?

        // Footer Desktop CSS
        $desktop_footer_css = '';
        $desktop_frame_footer_css = '';
        $desktop_rows_footer_css = '';
        $background_color_footer_css = '';

        if( is_array($footer_desktop_arr) ){
            $desktop_footer_css = Lay_LayoutFunctions::getGridCSS($footer_desktop_arr, '#footer', false);
            $desktop_frame_footer_css = Lay_LayoutFunctions::getGridFrameCSS($footer_desktop_arr, '#footer', '#footer', false);
            $desktop_rows_footer_css = Lay_LayoutFunctions::getRowsMarginBottomCSS($desktop_footer_rows, '#footer', false);
            $background_color_footer_css = Lay_LayoutFunctions::getBackgroundCSS($footer_desktop_arr, '#footer');    
        }

        // Cover
        // todo: cover disabled for phone
        $cover_desktop_class = 'nocover';
        // todo: && !isFullscreenSliderActive()
        // if( cover_controller.hasCover(type, obj.id) && !isFullscreenSliderActive() ){
        $desktop_cover_rowgutter_css = '';
        $cover_desktop_css = '';
        if( $cover_on_desktop ){
            $cover_desktop_class = 'hascover';
            $cover_desktop_css = Lay_LayoutFunctions::getGridCSS($desktop_arr, '.cover-region', false);
            // set main grid padding-top to first rowgutter
            $desktop_cover_rowgutter_css = Lay_LayoutFunctions::getFirstRowGutterAsPaddingTopForCoverCSS($desktop_arr, '#grid');
            
            // need .cover-inner so the grid's background color can overwrite the global background color set in customizer

            // ok so when we have a cover on desktop, but cover is disabled for phone, 
            // i dont want the cover row to be 100vh on APL if the user hasn't set it to be 100vh explicitly for phone
            // APL = automatically generated phone layout
            $_100vh_set_by_user_class = $desktop_cover_is_100vh_set_by_user ? '_100vh-set-by-user' : '_100vh-not-set-by-user';
            $desktop_cover_markup = 
            '<div class="cover-region cover-region-desktop _100vh '.$_100vh_set_by_user_class.'">
                <div class="cover-inner _100vh">
                    '.$desktop_cover_markup.'
                </div>
            </div>
            <div class="cover-region-placeholder cover-region-placeholder-desktop _100vh"></div>';
        }

        // this is not for cpl but just so i can disable cover via css on phone version of the page
        $cover_disabled_on_phone_class = LayFrontend_Options::$cover_disable_for_phone == true ? 'cover-disabled-on-phone' : 'cover-enabled-on-phone';

        $hover_image_markup = '';
        if( is_plugin_active( 'laytheme-imagehover/laytheme-imagehover.php' ) ) {
            $all_rows = array($desktop_rows, $cpl_rows, $desktop_footer_rows, $cpl_footer_rows, $desktop_cover_row, $cpl_cover_rows);
            $array_to_merge_to = array();
            LayRowFunctions::mergeAllRows($all_rows, $array_to_merge_to);
            $hover_image_markup = '<div class="lay-imagehover-region">'.LayThemeImagehover::getMarkup( $array_to_merge_to ).'</div>';

        }

        $markup =
        '<div class="lay-content '.$cpl_exists_html_class.' '.$footer_cpl_exists_html_class.' '.$cover_desktop_class.' '.$cover_cpl_class.' '.$cover_disabled_on_phone_class.'">
            <!-- Start Desktop Layout -->
            '.$desktop_cover_rowgutter_css.'
            '.$cover_desktop_css.'
            '.$desktop_cover_markup;
            if( $cover_on_desktop == true ) {
                // need cover content wrap, so i can set global bg color and bg image to it
                // the inner div: .grid can now be set to the grid-specific background-color, overwriting .cover-content's background
                // i cannot just set the global background color or background image to an outer div like .lay-content, because then ->
                // when cover would be active and i would scroll down, the transparent div that is on top of the cover would not hide it beneath
                $markup .= '<div class="cover-content">';
            }
            // need .grid-inner wrapper so I can have a fullscreen slider that is horizontal
            $markup .= 
            '<div id="grid" class="grid">
                <div class="grid-inner">
                '.$desktop_frame_css.'
                '.$desktop_rows_css.'
                '.$desktop_css.'
                '.$background_color_css.'
                '.$desktop_markup.'
                </div>
            </div>';
            if( $cover_on_desktop == true ) {
                // closing .cover-content
                $markup .= '</div>';
            }
            $markup .= 
            '<!-- End Desktop Layout -->'
            .$cpl_markup.'
            <div id="footer-region">';
            if( is_array($footer_desktop_arr) ){
                $markup .=
                '<div id="footer" class="footer">
                    '.$desktop_frame_footer_css.'
                    '.$desktop_rows_footer_css.'
                    '.$desktop_footer_css.'
                    '.$background_color_footer_css.'
                    '.$desktop_footer_markup.'
                </div>';
            }
            $markup .=
                $footer_cpl_markup.
            '</div>'
            .$hover_image_markup.
        '</div>';

        return array(
            'markup' => $markup,
            'type' => $type,
            'layoutObj' => $layoutObj,
            'obj' => $obj,
            'notification' => 'success'
        );
    }

    public static function prepareRows($grid_arr) {
        if( $grid_arr == null ) {
            return false;
        }
        // error_log(print_r($grid_arr, true));
        $cols = $grid_arr['cont'];
        $rowAttrs = array_key_exists( 'rowAttrs', $grid_arr ) ? $grid_arr['rowAttrs'] : false;
        $rowAmt = count($grid_arr['rowGutters']) + 1;
        $rows = array();
        $cover = null;

        // create row objects in an array, possibly with row attributes
        for( $i = 0; $i < $rowAmt; $i++ ){
            $rows []= array();
            if( $rowAttrs ) {
                if( $rowAttrs[$i] ) {
                    $rows[$i] = $rowAttrs[$i];
                }
            }
            // add row gutter
            // error_log(print_r($grid_arr, true));
            if( array_key_exists($i, $grid_arr['rowGutters']) ){
                // error_log(print_r($rows[$i], true));
                // error_log(print_r($grid_arr['rowGutters'], true));
                $rows[$i]['rowGutter'] = $grid_arr['rowGutters'][$i];
            }
            $rows[$i]['columns'] = array();
        }

        // loop through all columns and put them into their according row's .columns
        if( $cols ) {
            for( $i=0; $i<count($cols); $i++ ){
                $rowIx = $cols[$i]['row'];
                $rows[$rowIx]['columns'] []= $cols[$i];
            }
        }

        return $rows;
    }

    public static function getRowColElMarkup($rows) {
        $markup = '';
        for( $i = 0; $i < count($rows); $i++ ){
            $row = $rows[$i];
            if( $i == 0 ){
                if( array_key_exists('classes', $row) ){
                    $row['classes'] .= ' first-row';
                }else{
                    $row['classes'] = 'first-row';
                }
                
            }
            $layRow = new LayRow($row, $i);
            $markup .= $layRow->getOpeningMarkup();

            // when row is 100vh, the first column inside that is not a text needs class lay-col-needs-leftframe-margin
            $row100vh = array_key_exists('row100vh', $row) ? $row['row100vh'] : false;
            if( $row100vh == true ) {
                for( $i3=0; $i3<count($row['columns']); $i3++ ) {
                    $col = $row['columns'][$i3];
                    if( $col['type'] != 'text' ) {
                        if( array_key_exists('classes', $col) ){
                            $row['columns'][$i3]['classes'] .= ' lay-col-needs-leftframe-margin';
                        } else {
                            $row['columns'][$i3]['classes'] = 'lay-col-needs-leftframe-margin';
                        }
                    break;
                    }
                }
            }

            // 
            for( $i2=0; $i2<count($row['columns']); $i2++ ){
                $col = $row['columns'][$i2];
                $layCol = new LayCol($col);
                $markup .= $layCol->getOpeningMarkup();
                $layEl = new LayEl($col);
                $markup .= $layEl->getMarkup();

                $markup .= $layCol->getClosingMarkup();;
            }

            $markup .= $layRow->getClosingMarkup();
        }
        return $markup;
    }

}

new Lay_Layout();