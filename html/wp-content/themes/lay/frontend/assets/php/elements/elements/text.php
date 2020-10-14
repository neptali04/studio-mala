<?php

class LayText{

    public $el;

    public function __construct($el){
        $this->el = $el;
    }

    public function getMarkup(){
        $isscrolltotop = array_key_exists('isscrolltotop', $this->el) ? $this->el['isscrolltotop'] : false;
        if( $isscrolltotop == true ) {
            $isscrolltotop = 'scrolltotop';
        } else {
            $isscrolltotop = '';
        }

        $markup = '<div class="text lay-textformat-parent '.$isscrolltotop.'">'.$this->el['cont'].'</div>';
        return $markup;
    }

}