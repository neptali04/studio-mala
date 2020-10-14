<?php
// todo: make project prevnext work with this project order

class Thumbnailgrid{

	public function __construct(){
		if ( is_admin() ) {
			add_action('wp_ajax_get_thumbnails_for_thumbnailgrid', array($this, 'get_thumbnails_for_thumbnailgrid'));
		}
		
		add_action('wp_ajax_get_order_list_for_thumbnailgrid', array($this, 'get_order_list_for_thumbnailgrid'));
		add_action('wp_ajax_thumbgrid_save_project_order', array($this, 'thumbgrid_save_project_order'));

		add_action( 'wp_trash_post', array($this, 'remove_post_from_project_order'), 10, 1 );
		add_action( 'post_updated', array($this, 'maybe_remove_post_from_project_orders_on_post_save'), 10, 3 );
	}

	public static function maybe_remove_post_from_project_orders_on_post_save($post_ID, $post_after, $post_before){
		// if a post was saved and its categories changed, remove that post from custom project order metadatas

		// first get categories of post
		$cats = get_the_category($post_ID);
		$posts_cat_ids = array();
		foreach( $cats as $cat ) {
			$cat_id = $cat->cat_ID;
			$posts_cat_ids []= $cat_id;
		}

		// get all cat ids
		$all_categories = get_categories();
		$all_cat_ids = array();
		foreach( $all_categories as $cat ) {
			$all_cat_ids []= $cat->cat_ID;
		}

		// get all categories that are not categories of the post
		$diff = array_diff($all_cat_ids, $posts_cat_ids);

		foreach( $diff as $cat_id ){
			$thumb_order_ids = get_term_meta($cat_id, 'project_order', true);
			if ( !is_array($thumb_order_ids) ) {
				$thumb_order_ids = json_decode($thumb_order_ids);
			}
			// can be false or array
			if ( is_array($thumb_order_ids) ) {
				// the thumb_order_ids are post ids, just remove the current post's id from project order
				$offset = array_search($post_ID, $thumb_order_ids);
				if (is_int($offset)) {
					array_splice($thumb_order_ids, $offset, 1);
					$ids_str = json_encode($thumb_order_ids);
					update_term_meta($cat_id, 'project_order', $ids_str);
				}
			}
		}
	}

	public static function remove_post_from_project_order($postid){
		// get all cat ids
		$all_categories = get_categories();
		$all_cat_ids = array();
		foreach( $all_categories as $cat ) {
			$all_cat_ids []= $cat->cat_ID;
		}

		foreach( $all_cat_ids as $cat_id ) {
			$thumb_order_ids = get_term_meta($cat_id, 'project_order', true);
			if ( !is_array($thumb_order_ids) ) {
				$thumb_order_ids = json_decode($thumb_order_ids);
			}
			if ( is_array($thumb_order_ids) ) {
				$offset = array_search($postid, $thumb_order_ids);
				if (is_int($offset)) {
					array_splice($thumb_order_ids, $offset, 1);
					$ids_str = json_encode($thumb_order_ids);
					update_term_meta($cat_id, 'project_order', $ids_str);
				}
			}
		}
	}

	public static function thumbgrid_save_project_order(){
		$obj = $_POST['project_order'];
		$ids = json_encode($obj['ids']);
		update_term_meta($obj["termid"], 'project_order', $ids);
	}

	public static function get_project_ids_possibly_with_order($cat_id){
		// post ids of projects of category with cat_id
		$query_ids = array();

		$args = array(
			'posts_per_page' => -1,
			'orderby' => 'post_date',
			'order' => 'ASC',
			'post_type' => 'post',
			'cat' => $cat_id,
			'fields' => 'ids'
		);

		$query = new WP_Query( $args );
		if ( $query->have_posts() ) {
			foreach ($query->posts as $post_id){
				$query_ids []= $post_id;
			}
		}

		// if project order was saved before as meta (it is an array of project ids), see if the query above has new projects. if so, prepend them and return resulting array
		$thumb_order_ids = get_term_meta($cat_id, 'project_order', true);
		if( !is_array($thumb_order_ids) ){
			$thumb_order_ids = json_decode($thumb_order_ids);
		}
		// $thumb_order_ids can be false or array
		if( is_array($thumb_order_ids) ){

			$diff = array_diff($query_ids, $thumb_order_ids);

			if(count($diff) > 0){
				$result = array_merge($diff, $thumb_order_ids);
				update_term_meta($cat_id, 'project_order', json_encode($result));
				return $result;
			}

			return $thumb_order_ids;
		}

		// if theres no project order term meta, just return $query_ids
		return $query_ids;
	}

	public static function get_order_list_for_thumbnailgrid(){
		$cat_id = $_GET['catId'];
		$ids = Thumbnailgrid::get_project_ids_possibly_with_order($cat_id);

		$array = array();

		foreach ($ids as $post_id){
			if(get_post_status($post_id) != "publish"){
				continue;
			}
			$attid = get_post_thumbnail_id($post_id);
			$arr = wp_get_attachment_image_src($attid, '_265');
			$array []= array("post_id" => $post_id, "src" => $arr[0], "title" => get_the_title($post_id));
		}

		wp_send_json($array);
		die();
	}

	public static function get_thumbnails_for_thumbnailgrid(){
		$cat_id = $_GET['catId'];
		$ids = Thumbnailgrid::get_project_ids_possibly_with_order($cat_id);

		$array = array();

		// generating an array of objects that contain all the info needed to view a Marionette thumbnail_view
		// ar, cont, sizes, sizes._1024, title, descr,
		foreach ($ids as $post_id){
			if(get_post_status($post_id) != "publish"){
				continue;
			}
			$attid = get_post_thumbnail_id($post_id);
			$_512 = wp_get_attachment_image_src($attid, '_512');
			$full = wp_get_attachment_image_src($attid, 'full');

			$ar = 0;
			if($full){
				$ar = $full[2]/$full[1];
			}

			$array []= array(
				"ar" => $ar,
				"cont" => $full[0],
				"sizes" => array("_512" => $_512[0]),
				"title" => get_the_title($post_id),
				"descr" => get_post_meta($post_id, 'lay_project_description', true)
			);
		}

		wp_send_json($array);
		die();
	}

}
new Thumbnailgrid();
