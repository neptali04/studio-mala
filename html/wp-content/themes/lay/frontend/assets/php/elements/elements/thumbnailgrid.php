<?php

// todo: different css for desktop, tablet, phone

class LayThumbnailgrid{

    public $el;
    public $config;

    public function __construct($el){
        $this->el = $el;
    }

    public function getMarkup(){
        $this->config = $this->el['config'];
        $thumbs_data_array = LayElFunctions::get_thumbnails_data_for_thumbnailgrid_by_catid( $this->config['cat_id'] );
        $thumbs_array = array();

        foreach( $thumbs_data_array as $thumb_data ) {
            $thumbs_array []= new LayThumbnail($thumb_data);
        }

        $thumbs_markup = '';
        foreach( $thumbs_array as $thumb ) {
            $thumbs_markup .= $thumb->getMarkup();
        }

        return 
        '<div class="thumbs-collection-region" data-config="'.htmlspecialchars(json_encode($this->config)).'">
            <div class="thumb-collection">
                '.$thumbs_markup.'
            </div>
        </div>';
    }

}