<?php

class LayElementGrid{

    public $el;
    public $config;
    public $elements;

    public function __construct($el){
        $this->el = $el;
    }

    public function getMarkup(){
        $this->config = $this->el['config'];
        $this->elements = $this->config['elements'];
        $markup = '';

        foreach( $this->elements as $el ) {
            $caption = '';
            switch( $el['type'] ) {
                case 'img':
                case 'html5video':
                    $el['captionMarkup'] = LayElFunctions::getCaptionMarkup($this->el);
            }
            $element = false;
            switch( $el['type'] ) {
                case "postThumbnail":
                    $element = new LayThumbnail($el);
                break;
                case "text":
                    $element = new LayText($el);
                break;
                case "img":
                    $element = new LayImg($el);
                break;
                case "html5video":
                    $element = new LayHTML5Video($el);
                break;
            }
            if( $element ) {
                $markup .= 
                '<div class="element-wrap">'
                    .$element->getMarkup().
                '</div>';
            }
        }

        unset( $this->config['elements'] );
        return 
        '<div>
            <div class="elements-collection-region" data-config="'.htmlspecialchars(json_encode($this->config)).'">
                <div class="element-collection">'
                    .$markup.
                '</div>
            </div>
        </div>';
    }

}