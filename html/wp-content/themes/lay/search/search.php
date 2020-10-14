<?php
require get_template_directory().'/search/results_thumbnailgrid.php';
// searches for project titles and shows project titles and thumbnails

class LaySearch{

	public function __construct(){
		add_action( 'save_post', array($this, 'search_save_post'), 10, 2 );
		add_action( 'delete_post', array($this, 'search_delete_post'), 10, 2 );
		add_action( 'trash_post', array($this, 'search_delete_post'), 10, 2 );
		add_filter( 'rest_cache_skip', array($this, 'skip_search_endpoint'), 10, 4 );
        add_action( 'rest_api_init', array($this, 'add_search_route') );
        
        add_action( 'wp_ajax_get_search_result', array( $this, 'get_search_result_thumbnailgrid' ) );
        add_action( 'wp_ajax_nopriv_get_search_result', array( $this, 'get_search_result_thumbnailgrid' ) );
    }
    
    public static function get_search_result_thumbnailgrid(){
        // an array of post ids
        $found_posts = $_POST['found_posts'];
        $found_posts = json_decode($found_posts, true);

        $config = array(
            'desktop' => array('colCount' => 4, 'colGutter' => 2, 'rowGutter' => 1),
            'tablet' => array('colCount' => 3, 'colGutter' => 3, 'rowGutter' => 3),
            'phone' => array('colCount' => 1, 'colGutter' => 5, 'rowGutter' => 5),
            'layoutType' => "masonry",
            'postids' => $found_posts
        );

        // of course i dont need to wrap config in an $el array, but:
        // i mimick gridder json structure, this way i won't have to rewrite results_thumbnailgrid.php to be too different from thumbnailgrid.php
        // this is just more of an ease to use thing
        $el = array('config' => $config);
        $thumbnailgrid = new LaySearch_Thumbnailgrid($el);
        $markup = $thumbnailgrid->getMarkup();
        echo $markup;
        wp_die();
    }
	
	public static function add_search_route(){
		register_rest_route( 'laytheme', '/search/', array(
			'methods' => 'GET',
			'callback' => 'LaySearch::getSearchPostArray'
		) );
	}

	public static function skip_search_endpoint( $WP_DEBUG, $request_uri, $server, $request ){
		if ( strpos($request_uri, '/laytheme/search/') !== false ){
			return true;
		}
		return false;
	}

	public static function search_save_post( $post_id ) {
		$searchPostArray = array();
		$posts = get_posts(
            array(
                'numberposts' => -1,
                'post_status' => 'publish'
            )
        );

		if ( $posts ) {
			foreach ( $posts as $post ) {
                $fi_id = get_post_thumbnail_id($post->ID);
                $sizes = array();

                foreach(Setup::$sizes as $size){
                    $attachment = wp_get_attachment_image_src($fi_id, $size);
                    $sizes[$size] = $attachment[0];
                }
                $full = wp_get_attachment_image_src($fi_id, 'full');
                if( $full != false ){
                    $sizes['full'] = $full[0];
                    $ar = $full[2]/$full[1];
                }
                $_1024 = wp_get_attachment_image_src($fi_id, '_1024');
                if( $_1024 != false ) {
                    $_1024 = $_1024[0];
                }

                // needs to resemble post thumbnail json
                /*
                
                "type": "postThumbnail",
                "cont": "/wp-content/uploads/2018/03/Berlin_Showroom_12-1024x1536.jpg",
                "align": "middle",
                "row": 0,
                "col": 1,
                "colspan": 3,
                "offsetx": 0,
                "offsety": 0,
                "spaceabove": 0,
                "spacebelow": 0,
                "yvel": 1,
                "push": 1,
                "relid": 5,
                "title": "A Project",
                "postid": 214,
                "descr": "",
                "link": "/a-project/",
                "attid": 163,
                "ar": 1.5,
                "sizes": {
                    "full": "/wp-content/uploads/2018/03/Berlin_Showroom_12-1024x1536.jpg",
                    "_1024": "/wp-content/uploads/2018/03/Berlin_Showroom_12-1024x1536-1024x1536.jpg",
                    "_768": "/wp-content/uploads/2018/03/Berlin_Showroom_12-1024x1536-768x1152.jpg",
                    "_512": "/wp-content/uploads/2018/03/Berlin_Showroom_12-1024x1536-512x768.jpg",
                    "_265": "/wp-content/uploads/2018/03/Berlin_Showroom_12-1024x1536-265x398.jpg"
                },
                "mo_sizes": "",
                "mo_h": "",
                "mo_w": "",
                "video_url": "",
                "video_w": 0,
                "video_h": 0
                type!!
                */

				array_push($searchPostArray, array(
                    // 'ar' => $ar,
                    // 'attid' => $fi_id,
                    // 'descr' => get_post_meta( $post->ID, 'lay_project_description', true ),
                    // 'link' => get_permalink($post->ID),
                    // 'mo_h' => '',
                    // 'mo_sizes' => array(),
                    // 'mo_w' => '',
                    'postid' => $post->ID,
                    // 'sizes' => $sizes,
                    'title' => get_the_title($post->ID),
                    // 'type' => 'img',
                    // 'video_h' => null,
                    // 'video_url' => false,
                    // 'video_w' => null,
					'slug' => $post->post_name,
                    // 'cont' => $_1024,
                    // 'width' => $full[1],
                    // 'height' => $full[2]
				));
			}
		}
		update_option( 'lay-search-post-array', $searchPostArray);
	}

	public static function search_delete_post( $post_id ) {
		// TODO: von der Liste löschen und option abspeichern
		// TODO: überprüfen

		$searchPostArray = get_option( 'lay-search-post-array' );
		$needUpdate = false;
		if ( $searchPostArray == false ) {
			LaySearch::search_save_post($post_id);
		}
		for ($i = 0; $i < count($searchPostArray); $i++) {
			if ($searchPostArray[$i]['postid'] == $post_id) {
				$needUpdate = true;
				array_splice($searchPostArray, $i, 1);
				break;
			}
		}
		if ($needUpdate) {
			update_option( 'lay-search-post-array', $searchPostArray);
		}
	}

	public static function getSearchPostArray() {
		$searchPostArray = get_option( 'lay-search-post-array' );
		if ( $searchPostArray == false ) {
			LaySearch::search_save_post(-1);
		}
		return $searchPostArray;
	}
}
new LaySearch();
