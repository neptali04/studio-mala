<?php
class LayCol{

    public $col_classes = 'col';
    public $col_id = '';
    public $type = '';
    public $inline_css = '';
    public $yvel = '';
    public $offset_data_attrs = '';

    public function __construct($col){
        // CSS
        $hasInlineCSS = false;
        $css = '';
        $spaceabove = array_key_exists( 'spaceabove', $col ) ? $col['spaceabove'] : 0;
        $spacebelow = array_key_exists( 'spacebelow', $col ) ? $col['spacebelow'] : 0;
        $offsetx = array_key_exists('offsetx', $col) ? $col['offsetx'] : 0;
        $offsety = array_key_exists('offsety', $col) ? $col['offsety'] : 0;
        if( $offsetx == 0 && $offsety == 0 ){
            $this->col_classes .= ' no-offset';
        }else{
            $this->col_classes .= ' has-offset';
        }

        if( $spaceabove != 0 ) {
            $css .= 'padding-top: '.$spaceabove.'vw;';
            $hasInlineCSS = true;
        }
        if( $spacebelow != 0 ) {
            $css .= 'padding-bottom: '.$spacebelow.'vw;';
            $hasInlineCSS = true;
        }
        if( $offsetx != 0 || $offsety != 0 ) {
            $this->offset_data_attrs .= ' data-offsetx="'.$offsetx.'" data-offsety="'.$offsety.'"';
            $css .= 'transform:translate('.$offsetx.'vw, '.$offsety.'vw);';
            $hasInlineCSS = true;
        }
        if( $hasInlineCSS ) {
            $this->inline_css = 'style="'.$css.'"';
        }

        // TYPE
        $this->type = $col['type'];
        // for backwards compatibility
        $isProjectLink = array_key_exists( 'postid', $col ) ? true : false;
        if(($this->type == 'img' && $isProjectLink) || $this->type == 'postThumbnail'){
            $this->type = 'project';
        }
        
        //  ID
        $this->col_id = array_key_exists('html_id', $col) ? 'id="'.$col['html_id'].'"' : '';

        // CLASSES
        $this->col_classes .= ' push-'.$col['push'];
        $this->col_classes .= ' span-'.$col['colspan'];
        $this->col_classes .= ' align-'.$col['align'];

        $this->col_classes .= array_key_exists('classes', $col) ? ' '.$col['classes'] : '';
        if( array_key_exists('frameOverflow', $col) ) {
            switch( $col['frameOverflow'] ){
                case 'left':
					$this->col_classes .= ' frame-overflow-left';
					break;
				case 'both':
					$this->col_classes .= ' frame-overflow-left frame-overflow-right';
					break;
				case 'right':
					$this->col_classes .= ' frame-overflow-right';
					break;
            }
        }
        if( array_key_exists('yvel', $col) && $col['yvel'] != 1 && LayFrontend_Options::$simple_parallax ){
            $this->col_classes .= ' has-parallax';
        }else{
            $this->col_classes .= ' no-parallax';
        }

        $this->col_classes .= ' type-'.$this->type;

        $lightboxoff = array_key_exists('lightboxoff', $col) && $col['lightboxoff'] == true && $this->type == 'img' ? 'lightboxoff' : '';

        $this->col_classes .= ' '.$lightboxoff;

        // YVEL
        if( array_key_exists( 'yvel', $col ) && $col['yvel'] != 0 ){
            $this->yvel = 'data-yvel="'.$col['yvel'].'"';
        }
    }

    public function getOpeningMarkup(){
        return '<div class="'.$this->col_classes.'" '.$this->col_id.' data-type="'.$this->type.'" '.$this->inline_css.' '.$this->yvel.' '.$this->offset_data_attrs.'>';
    }

    public function getClosingMarkup(){
        return '</div>';
    }

}