<?php

add_action( 'admin_enqueue_scripts', 'm4c_duplicate_post_scripts' );
/**
 * Add the necessary jquery.
 *
 * @since 1.0.0
 */
function m4c_duplicate_post_scripts( $hook_suffix ) {
	if( $hook_suffix == 'edit.php' ) {
		wp_enqueue_script( 'mtphr-post-duplicator', MTPHR_POST_DUPLICATOR_URL.'/assets/js/pd-admin.js', array('jquery'), MTPHR_POST_DUPLICATOR_VERSION );
	}
}