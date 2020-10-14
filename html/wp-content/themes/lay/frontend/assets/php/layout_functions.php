<?php 

class Lay_LayoutFunctions{

    public function __construct(){

    }

    public static function getBackgroundCSS( $json, $target ){
        $bgColor = $json['bgColor'];
        if( $bgColor == '' || $bgColor == null ) {
            $bgColor = 'transparent';
        }
        return '<!-- background color css --><style>'.$target.'{background-color:'.$bgColor.';}</style>';
    }

    public static function getRowsMarginBottomCSS($rows, $target, $isPhoneGrid) {
        if( !is_array($rows) ) {
            return '';
        }
        $markup = '';
        $style = '';
        if($isPhoneGrid){
            $style = '<style>@media (max-width: '.LayFrontend_Options::$breakpoint.'px){';
        }else{
            $style = '<style>@media (min-width: '.( LayFrontend_Options::$breakpoint+1 ).'px){';
        }

        for( $i = 0; $i < count($rows); $i++ ){
            $row = $rows[$i];
            // error_log(print_r($row, true));
            if( array_key_exists( 'rowGutter', $row ) ) {
                $measuring_unit = $isPhoneGrid == true ? '%' : Gridder::$rowgutter_mu;
                $style .= $target.' .row-'.$i.'{margin-bottom:'.$row['rowGutter'].$measuring_unit.';}';
            }
        }
        return '<!-- rows margin bottom css -->'.$style.'}</style>';
    }

    public static function getGridCSS($grid_arr, $target, $isPhoneGrid = false) {

        $colcount = (int)$grid_arr['colCount'];
        $colgutter = (float)$grid_arr['colGutter'];
        $framemargin = (float)$grid_arr['frameMargin'];
        $topframemargin = (float)$grid_arr['topFrameMargin'];
        $bottomframemargin = (float)$grid_arr['bottomFrameMargin'];

        // error_log(print_r('$grid_arr[colCount]', true));
        // error_log(print_r($grid_arr['colCount'], true));
        // error_log(print_r('$grid_arr', true));
        // error_log(print_r($grid_arr, true));
        // error_log(print_r('$isPhoneGrid', true));
        // error_log(print_r($isPhoneGrid, true));
        // error_log(print_r('$colcount', true));
        // error_log(print_r($colcount, true));

        if(!is_numeric($topframemargin)) {
            $topframemargin = $framemargin;
        }
        if(!is_numeric($bottomframemargin)) {
            $bottomframemargin = $framemargin;
        }

        $colandgutterspace = 100 - $framemargin * 2;
        $gutterspace = ($colcount-1) * $colgutter;
        $colspace = $colandgutterspace - $gutterspace;

        $onecolspace = $colspace / $colcount;
        $onegutterspace = $colgutter;

        $style;

        if($isPhoneGrid){
            $style = '<!-- grid css --><style>@media (max-width: '.LayFrontend_Options::$breakpoint.'px){';
        }else{
            $style = '<!-- grid css --><style>@media (min-width: '.( LayFrontend_Options::$breakpoint+1 ).'px){';
        }

        for ($i=0; $i < $colcount+1; $i++) {
            if( $i>0 ) {
                $space = $onecolspace * $i + $onegutterspace * ($i-1);
                $space_and_framemargin = $space+$framemargin;
                $space_and_framemargin_x2 = $space+$framemargin+$framemargin;

                $space_str = Lay_LayoutFunctions::replaceCommaWithDot($space);
                $space_and_framemargin_str = Lay_LayoutFunctions::replaceCommaWithDot($space_and_framemargin);
                $space_and_framemargin_x2_str = Lay_LayoutFunctions::replaceCommaWithDot($space_and_framemargin_x2);

                $style .= $target.' .span-'.$i.'{width:'.$space_str.'%}';
                $style .= $target.' .frame-overflow-left.span-'.$i.'{width:'.($space_and_framemargin_str).'%}';
                $style .= $target.' .frame-overflow-right.span-'.$i.'{width:'.($space_and_framemargin_str).'%}';
                $style .= $target.' .frame-overflow-left.frame-overflow-right.span-'.$i.'{width:'.($space_and_framemargin_x2_str).'%}';
            }

            // push for :first-child
            $spacefirst = $framemargin;
            $spacefirst += $onecolspace * $i + $onegutterspace * $i;
            $spacefirst_str = Lay_LayoutFunctions::replaceCommaWithDot($spacefirst);

            $style .= $target.' .push-'.$i.':first-child{margin-left:'.$spacefirst_str.'%}';
            $style .= $target.' .push-'.$i.'.lay-col-needs-leftframe-margin{margin-left:'.$spacefirst_str.'%}';
            if ($i == 0) {
                $style .= $target.' .frame-overflow-left.push-0:first-child{margin-left:0}';
            }

            $stackWidth = $onecolspace * $i + $onegutterspace * ($i - 1);
            for ($k=0; $k < $i; $k++) {
                $elementSpace = $onecolspace * $k + $onegutterspace * $k;
                $elementInnerSpace = ($elementSpace / $stackWidth) * 100;
                $elementInnerSpace_str = Lay_LayoutFunctions::replaceCommaWithDot($elementInnerSpace);
                $style .= $target.' .span-' . $i . ' .stack-element .push-'.$k.'{margin-left:'.$elementInnerSpace_str.'%}';
                $style .= $target.' .span-' . $i . ' .stack-element .push-'.$k.':first-child{margin-left:'.$elementInnerSpace_str.'%}';
                $style .= $target.' .span-' . $i .' .stack-element .push-'.$k.'.lay-col-needs-leftframe-margin{margin-left:'.$elementInnerSpace_str.'%}';
            }
            for ($k=1; $k <= $i; $k++) {
                $elementWidth = $onecolspace * $k + $onegutterspace * ($k-1);
                $elementInnerWidth = ($elementWidth / $stackWidth) * 100;
                // in one case floats would use commas instead of dots in PHP. This is why i convert to dots here, cause commas aren't valid for CSS
                $elementInnerWidth_str = Lay_LayoutFunctions::replaceCommaWithDot($elementInnerWidth);
                $style .= $target.' .span-' . $i .' .stack-element .span-'.$k.'{width:'.$elementInnerWidth_str.'%}';
            }

            // push for el in 100vh row -> needs left instead of margin-left, and always including left frame
		    $style .= $target.' ._100vh :not(.stack-element) > .col[data-type="text"].push-'.$i.'{left:'.$spacefirst_str.'%}';

            // push
            $space = $onecolspace * $i + $onegutterspace * ($i+1);
            $space_str = Lay_LayoutFunctions::replaceCommaWithDot($space);
            $style .= $target.' .push-'.$i.'{margin-left:'.$space_str.'%}';
        }
        $style .= '}</style>';
        return $style;
    }

    // attention, this returns a string! the issue was all floats used , instead of . so i just return a string here that uses .
    public static function replaceCommaWithDot($float){
        $str = (string)$float;
        if(strstr($str, ",")) {
            $str = str_replace(",", ".", $str); // replace ',' with '.'
        }
        return $str;
    }

    public static function getRowIs100vh($row){
        if( array_key_exists('row100vh', $row) && $row['row100vh'] == true ) {
            return true;
        }
        return false;
    }

    public static function getGridFrameCSS($grid_array, $topFrameTarget, $bottomFrameTarget, $isPhoneGrid = false) {
        $topframemargin = (float)$grid_array['topFrameMargin'];
        $topframemargin_str = Lay_LayoutFunctions::replaceCommaWithDot($topframemargin);
        $bottomframemargin = (float)$grid_array['bottomFrameMargin'];
        $bottomframemargin_str = Lay_LayoutFunctions::replaceCommaWithDot($bottomframemargin);
    
        $style = "";
        $topFrameMu = Gridder::$topframe_mu;
        $bottomFrameMu = Gridder::$bottomframe_mu;
    
        if($isPhoneGrid){
            $style = '@media (max-width: '.LayFrontend_Options::$breakpoint.'px){';
            $topFrameMu = "%";
            $bottomFrameMu = "%";
        }else{
            $style = '@media (min-width: '.(LayFrontend_Options::$breakpoint+1).'px){';
        }
        $style .= $topFrameTarget.'{padding-top:'.$topframemargin_str.$topFrameMu.';}'
            .$bottomFrameTarget.'{padding-bottom:'.$bottomframemargin_str.$bottomFrameMu.';}'
        .'}';
    
        $style = '<!-- grid frame css --><style>'.$style.'</style>';
        return $style;
    }

    // only needed when cover is active
    // set rows region padding-top to first rowgutter
    // todo: for phone 
    public static function getFirstRowGutterAsPaddingTopForCoverCSS($grid_array, $target, $forPhone = false) {
        if( count( $grid_array['rowGutters'] ) > 0 ) {
            $gutter = $grid_array['rowGutters'][0];
            $gutter_str = Lay_LayoutFunctions::replaceCommaWithDot($gutter);
            if( $forPhone ) {
                // phone
                return 
                '<!-- First Row-Gutter as padding top for pages with Cover --> <style>'
                .'@media (max-width: '.(LayFrontend_Options::$breakpoint).'px){'
                    .$target.'{padding-top:'.$gutter_str.Gridder::$rowgutter_mu.';}'
                .'}'
                .'</style>';
            } else {
                // desktop
                return 
                '<!-- First Row-Gutter as padding top for pages with Cover --> <style>'
                .'@media (min-width: '.(LayFrontend_Options::$breakpoint+1).'px){'
                    .$target.'{padding-top:'.$gutter_str.Gridder::$rowgutter_mu.';}'
                .'}'
                .'</style>';
            }
        }
        return '';
    }

}
new Lay_LayoutFunctions();