<?php

// todo: css, html5video with placeholder image, image has transition transform and moves around on init of page

class LayHTML5Video{

    public $el;

    public function __construct($el){
        $this->el = $el;
    }

    public function getMarkup(){
        $caption = LayElFunctions::getCaptionMarkup($this->el);
        $videoConfig = array(
            'model' => $this->el,
            'captionMarkup' => $caption,
            'showPlaceholderImage' => true,
            'usePaddingPlaceholder' => true,
            'classPrefix' => '',
            'noLazyLoad' => true
        );
        $markup = LayElFunctions::getHTML5VideoMarkup($videoConfig);
        return $markup;
    }

}