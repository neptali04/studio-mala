<?php
require_once get_template_directory().'/assets/mobile_detect/Mobile_Detect.php';

// order of styles

// frontend style: frontend_styles
// desktop and phone styles: essential_head_styles_and_desktop_and_phone_styles
// textformats: frontend_textformats_css
// customizer styles: lay_customize_css
// custom styles: get_custom_lay_css

class Frontend{

	public static $topframe_mu;
	public static $bottomframe_mu;
	public static $current_type_id_slug_catid;

	public function __construct(){
		// any customizer styles need to come after default styles in order to overwrite default styles
		// meaning customizer styles add_action needs priority number higher than frontend_styles
		add_action( 'wp_enqueue_scripts', array( $this, 'frontend_scripts' ), 9 );
		// add_action( 'wp_enqueue_scripts', array($this, 'radio_channel_scripts'), 10 );
		add_action( 'wp_enqueue_scripts', array( $this, 'frontend_styles' ), 9);
		// need to put this in head so elements are aligned top/bottom/middle without realigning themselves after css has loaded
		add_action( 'wp_enqueue_scripts', array( $this, 'essential_head_styles_and_desktop_and_phone_styles' ), 12 );
		add_action( 'rest_api_init', array( $this, 'include_griddermeta_in_term') );
		add_action( 'rest_api_init', array( $this, 'include_griddermeta_in_post') );
		add_action( 'rest_api_init', array( $this, 'include_griddermeta_in_page') );

		add_action( 'wp_head', array($this, 'set_current_data') );
	}

	// need this because i put the frontend app in the footer, but ppl access window.laytheme in <head>
	public static function radio_channel_scripts(){

	}

	public static function set_current_data(){
		Frontend::$current_type_id_slug_catid = Frontend::get_current_type_id_slug_catid();
	}

	public static function get_current_type_id_slug_catid(){
		$type = '';
		$id = '';
		$projectcatid = '';
		$slug = '';

		global $post;

		// post is null if page doesnt exist. then just show frontpage
		if(is_category()){
			$category = get_category( get_query_var( 'cat' ) );
			$cat_id = $category->cat_ID;

			$slug = $category->slug;
			$type = 'category';
			$id = $cat_id;
		}
		else if(is_front_page() || is_null($post)){

			$type = get_theme_mod('frontpage_type', 'category');
			switch ($type) {
				case 'category':
					$id = get_theme_mod('frontpage_select_category', 1);
					$category = get_category( $id );
					$slug = $category->slug;
				break;
				case 'project':
					$id = get_theme_mod('frontpage_select_project');
					$projectcatid = json_encode(wp_get_post_categories($id, array('fields'=>'ids')));
					$post = get_post($id);
					$slug = $post->post_name;
				break;
				case 'page':
					$id = get_theme_mod('frontpage_select_page');
					$post = get_post($id);
					$slug = $post->post_name;
				break;
			}

		}
		else if(is_single()){
			$type = 'project';
			$id = $post->ID;
			$slug = $post->post_name;
			$projectcatid = json_encode(wp_get_post_categories($id, array('fields'=>'ids')));
			// error_log(print_r($projectcatid, true));
		}
		else{
			$type = 'page';
			$id = $post->ID;
			$slug = $post->post_name;
		}

		return array(
			"type" => $type,
			"id" => $id,
			"projectcatid" => $projectcatid,
			"slug" => $slug
		);
	}

	public static function get_body_data($echo = true){

		$data = Frontend::$current_type_id_slug_catid;
		$mobile_menu_style_class = 'mobile-menu-'.get_theme_mod('mobile_menu_style', 'style_1');
		$mobile_menu_do_animation = get_theme_mod('mobile_menu_do_animation', true) == 1 ? 'mobile-menu-has-animation' : 'mobile-menu-no-animation';
		
		$thumb_mo_image_has_transition = get_theme_mod('thumb_mo_image_has_transition', false) == 1 ? 'thumb-mo-image-has-transition' : 'thumb-mo-image-no-transition';
		$language = '';
		if( function_exists('qtranxf_getLanguage') ){
			$language = 'language-'.qtranxf_getLanguage();
		}
		$category_classes = '';
		if( array_key_exists('projectcatid', $data) ) {
			$catids = json_decode($data["projectcatid"]);
			if( is_array( $catids ) ) {
				foreach( $catids as $catid ) {
					$category_classes .= 'category-'.$catid.' ';
				}
			}
		}

		$detect = new Mobile_Detect;
		$touchdevice_class = $detect->isMobile() ? 'touchdevice' : 'no-touchdevice';

		// class to hide content while intro has not rendered yet
		$intro_loading = (is_front_page() && LayIntro::$isActive) ? 'intro-loading' : '';

		$return = 'class="'.$intro_loading.' '.$touchdevice_class.' type-'.$data["type"].' id-'.$data["id"].' slug-'.$data["slug"].' '.$mobile_menu_style_class.' '.$mobile_menu_do_animation.' '.$thumb_mo_image_has_transition.' '.$language.' '.$category_classes.'" data-type="'.$data["type"].'" data-id="'.$data["id"].'" data-catid="'.$data["projectcatid"].'" data-slug="'.$data["slug"].'"';

		if($echo){
			echo $return;
		}
		else{
			return $return;
		}

	}

	public static function get_max_width_option_css(){
		$maxwidth = get_option( 'misc_options_max_width', '0' );

		if($maxwidth != '0'){
			echo '<!-- max width option -->';
			echo
			'<style>@media (min-width: '.(MiscOptions::$phone_breakpoint+1).'px){.row-inner{margin-left:auto;margin-right:auto;max-width:'.$maxwidth.'px;}}</style>';
		}
	}

	public static function get_custom_lay_css(){
		$desktopCSS = get_option( 'misc_options_desktop_css', '' );

		if($desktopCSS != ''){
			echo '<!-- custom css for desktop version -->';
			echo '<style>@media (min-width: '.(MiscOptions::$phone_breakpoint+1).'px){'.$desktopCSS.'}</style>';
		}

		$mobileCSS = get_option( 'misc_options_mobile_css', '' );
		if($mobileCSS != ''){
			echo '<!-- custom css for mobile version -->';
			echo '<style>@media (max-width: '.MiscOptions::$phone_breakpoint.'px){'.$mobileCSS.'}</style>';
		}
	}

	public static function get_custom_head_content(){

		$customHeadContent = get_option( 'misc_options_analytics_code', '' );
		if($customHeadContent != ''){
			echo '<!-- custom head content -->';
			echo $customHeadContent;
		}

		$navigation_transition_duration = Frontend::get_transition_duration_in_milliseconds();
		// the CSS transition duration needs to be half of the transition duration set in lay options, because
		// whole animation duration = fadeout duration + fadein duration
		$navigation_transition_duration /= 2;

		echo
		'<!-- navigation transition duration css -->
		<style>
			body{
				-webkit-transition: background-color '.$navigation_transition_duration.'ms ease;
				transition: background-color '.$navigation_transition_duration.'ms ease;
			}
			.lay-content{
				-webkit-transition: opacity '.$navigation_transition_duration.'ms ease;
				transition: opacity '.$navigation_transition_duration.'ms ease;
			}
		</style>';
	}

	public static function get_transition_duration_in_milliseconds(){
		$navigation_transition_duration = get_option( 'misc_options_navigation_transition_duration', '0.5' );
		$navigation_transition_duration = (float)$navigation_transition_duration;
		// to milliseconds
		$navigation_transition_duration *= 1000;
		return $navigation_transition_duration;
	}

	public static function get_meta(){

		// https://codex.wordpress.org/Function_Reference/is_plugin_active
		// include needed to use is_plugin_active
		include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
		if(is_plugin_active('wordpress-seo/wp-seo.php')){
			// bail out early if yoast seo plugin is active, because it provides all these metatags already
			return;
		}

		$description = get_option( 'misc_options_website_description', '' );
		$fbimage_id = get_option( 'misc_options_fbimage', '' );
		$fbimage = wp_get_attachment_image_src( $fbimage_id, 'full' );

		$title = get_bloginfo('name');

		// for google
		if( is_single() ){
			global $post;
			$project_descr = get_post_meta( $post->ID, 'lay_project_description', true );
			$project_descr = strip_tags($project_descr);

			if($project_descr != ""){
				echo '<meta name="description" content="'.$project_descr.'"/>';
			}
		}else{
			if($description != ''){
				echo '<meta name="description" content="'.$description.'"/>';
			}
		}

		// og tags
		if($fbimage){
			echo
			'<meta property="og:image" content="'.$fbimage[0].'">
			<meta property="og:image:width" content="'.$fbimage[1].'">
			<meta property="og:image:height" content="'.$fbimage[2].'">';
		}
		echo
		'<meta property="og:title" content="'.$title.'">
		<meta property="og:site_name" content="'.get_bloginfo('name').'">';

		if($description != ''){
			echo '<meta property="og:description" content="'.$description.'">';
		}

		if($fbimage){
			echo
			'<meta name="twitter:card" content="summary_large_image">
			<meta name="twitter:title" content="'.$title.'">
			<meta name="twitter:image" content="'.$fbimage[0].'">';
		}
		else{
			echo
			'<meta name="twitter:card" content="summary">
			<meta name="twitter:title" content="'.$title.'">';
		}

		if($description != ''){
			echo '<meta name="twitter:description" content="'.$description.'">';
		}

	}

	public static function get_home_link_data(){
		$type = get_theme_mod('frontpage_type', 'category');
		$id = '';
		$projectcatid = '';

		switch ($type) {
			case 'category':
				$id = get_theme_mod('frontpage_select_category', 1);
			break;
			case 'project':
				$id = get_theme_mod('frontpage_select_project');
				$cats = get_the_category( $id );
				$catids = array();
				foreach( $cats as $cat ) {
					$catids []= $cat->term_id;
				}  
				// error_log(print_r($catids, true));
				$projectcatid = json_encode($catids);
			break;
			case 'page':
				$id = get_theme_mod('frontpage_select_page');
			break;
		}

		$return = 'data-type="'.$type.'" data-id="'.$id.'" data-catid="'.$projectcatid.'"';

		return $return;
	}

	public static function get_footer(){
		// i put site title and menus in footer so when I get the flash of unstyled content on Chrome
		// at least it won't create a flash of extra space above the main grid but only underneath which won't be visible anyways
		$title = get_bloginfo( 'name');
		$homeurl = get_bloginfo('url');


		$mobiletitle = '';

		$hideTagLine = get_theme_mod('tagline_hide', "1");

		$taglineMarkup = '';
		if($hideTagLine != "1"){
			$tagline = html_entity_decode(get_bloginfo( 'description' ));

			if (strpos($tagline, '<br>') === false) {
				$tagline = nl2br($tagline);
			}

			$tagline_textformat = get_theme_mod('tagline_textformat', 'Default');
			if($tagline_textformat != ""){
				$tagline_textformat = '_'.$tagline_textformat;
			}
			$taglineMarkup = '<div class="tagline '.$tagline_textformat.'">'.$tagline.'</div>';
		}

		$txt_or_img_or_html = get_theme_mod('st_txt_or_img', 'text');
		$m_txt_or_img = get_theme_mod( 'm_st_txt_or_img', $txt_or_img_or_html );
		if( $m_txt_or_img == 'html' ){
			$m_txt_or_img = 'text';
		}

		$title_with_linebreak = nl2br($title);

		// desktop
		switch ($txt_or_img_or_html) {
			case 'text':
				$st_textformat = get_theme_mod('st_textformat', 'Default');
				if($st_textformat != ""){
					$st_textformat = '_'.$st_textformat;
				}
				// we need a span el in here to make site title underline work well with multiline text
				echo
				'<a class="sitetitle txt" href="'.$homeurl.'" '.Frontend::get_home_link_data().'>
					<div class="sitetitle-txt-inner '.$st_textformat.'"><span>'.$title_with_linebreak.'</span></div>
					'.$taglineMarkup.'
				</a>';
			break;
			case 'image':
				$imgurl = LTUtility::filterURL(get_theme_mod('st_image'));
				echo
				'<a class="sitetitle img" href="'.$homeurl.'" '.Frontend::get_home_link_data().'>
					<img src="'.$imgurl.'" alt="'.$title.'">
					'.$taglineMarkup.'
				</a>';
			break;
			case 'html':
			echo
				'<a class="sitetitle html" href="'.$homeurl.'" '.Frontend::get_home_link_data().'>
					'.get_theme_mod('site_title_html', '').'
					'.$taglineMarkup.'
				</a>';
			break;
		}

		// mobile
		switch ($m_txt_or_img){
			case 'text':
				$mobiletitle = get_bloginfo('name');
			break;
			case 'image':
				$default = get_theme_mod('st_image');
				$imgurl = LTUtility::filterURL(get_theme_mod('m_st_image', $default));
				$mobiletitle = '<img src="'.$imgurl.'" alt="'.$title.'">';
			break;
		}

		echo LayMenuManager::get_mobile_menu();
		echo LayMenuManager::get_menu_markup();

		// dont show burger if there is no menu
		$hideburgerCSS = '';
		if(!LayMenuManager::at_least_one_menu_is_filled()){
			$hideburgerCSS = 'style="display:none;"';
		}

		$customBurgerImg = get_theme_mod('mobile_menu_icon_burger', '');
		$customCloseImg = get_theme_mod('mobile_menu_icon_close', '');
		$burger_icon_type = get_theme_mod('burger_icon_type', 'default');

		echo
		'<div class="navbar"></div>';
		echo
		'<a class="mobile-title '.$m_txt_or_img.'" href="'.$homeurl.'" '.Frontend::get_home_link_data().'><span>'.$mobiletitle.'</span></a>';
		
		if( $customBurgerImg != '' && $burger_icon_type == 'custom' ){
			echo 
			'<div class="burger-wrap burger-custom-wrap-open" '.$hideburgerCSS.'>
				<div class="burger-inner">
					<img src="'.$customBurgerImg.'" class="mobile-menu-icon burger-custom" '.$hideburgerCSS.'>
				</div>
			</div>';
		}
		if( $customCloseImg != '' && $burger_icon_type == 'custom' ){
			echo 
			'<div class="burger-wrap burger-custom-wrap-close" '.$hideburgerCSS.'>
				<div class="burger-inner">
					<img src="'.$customCloseImg.'" class="mobile-menu-close-custom mobile-menu-icon" '.$hideburgerCSS.'>
				</div>
			</div>';
		}
		$burger_animated_class = get_theme_mod('mobile_menu_style', 'style_1') == 'style_2' ? 'burger-no-animation' : 'burger-has-animation';
		if( $burger_icon_type == "default" || $burger_icon_type == "default_thin" ){
			echo 
			'<div class="burger-wrap burger-wrap-default '.$burger_animated_class.' '.$burger_icon_type.'" '.$hideburgerCSS.'>
				<div class="burger-inner">
					<div class="burger burger-default mobile-menu-icon">
						<span></span>
						<span></span>
						<span></span>
					</div>
				</div>
			</div>';
		}
		if( $burger_icon_type == 'new' ){
			echo 
			'<div class="burger-wrap burger-wrap-new '.$burger_animated_class.'" '.$hideburgerCSS.'>
				<div class="burger-inner">
					<div class="burger burger-new mobile-menu-icon">
						<span class="bread-top">
							<span class="bread-crust-top"></span>
						</span>
						<span class="bread-bottom">
							<span class="bread-crust-bottom"></span>
						</span>
					</div>
				</div>
			</div>';
		}

		$custom_html_bottom = get_option( 'misc_options_custom_htmlbottom', '' );
		if( $custom_html_bottom != "" ){
			echo $custom_html_bottom;
		}
	}

	public static function get_project_arrows(){
		//project arrows
		$showArrows = get_option('misc_options_show_project_arrows', '');
		if($showArrows == "on"){

			$pa_hide_prev = get_theme_mod('pa_hide_prev', '');
			$pa_type = get_theme_mod('pa_type', 'icon');
			$pa_position = get_theme_mod('pa_position', 'center');
			$prevA = '';
			$nextA = '';
			// need to use "pa-inner" because there i can use transform: rotate.
			// when i would use transform: rotate on the parent element ".project-arrow" there is some z-index or stacking context bug on iphone safari,
			// causing the project arrow to disappear behind html5 videos 
			switch($pa_type){
				case 'icon':
					$icon = get_theme_mod('pa_icon', '&#9654;');
					$prevA = '<a data-type="project" class="pa-prev project-arrow pa-icon pa-mirror '.$pa_position.'"><div class="pa-inner">'.$icon.'</div></a>';
					$nextA = '<a data-type="project" class="pa-next project-arrow pa-icon '.$pa_position.'"><div class="pa-inner">'.$icon.'</div></a>';
				break;
				case 'text':
					$next = get_theme_mod('pa_next_text', 'Next');
					$prev = get_theme_mod('pa_prev_text', 'Previous');
					$prevA = '<a data-type="project" class="pa-prev project-arrow pa-text '.$pa_position.'"><div class="pa-inner">'.$prev.'</div></a>';
					$nextA = '<a data-type="project" class="pa-next project-arrow pa-text '.$pa_position.'"><div class="pa-inner">'.$next.'</div></a>';
				break;
				case 'project-thumbnails':
					$prevA = '<a data-type="project" class="pa-prev project-arrow pa-thumb '.$pa_position.'"></a>';
					$nextA = '<a data-type="project" class="pa-next project-arrow pa-thumb '.$pa_position.'"></a>';
				break;
				case 'custom-image':
					$src = get_theme_mod('pa_image', '');
					$prevA = '<a data-type="project" class="pa-prev project-arrow pa-img pa-mirror '.$pa_position.'"><div class="pa-inner"><img src="'.$src.'" alt=""></div></a>';
					$nextA = '<a data-type="project" class="pa-next project-arrow pa-img '.$pa_position.'"><div class="pa-inner"><img src="'.$src.'" alt=""></div></a>';
				break;
			}

			if($pa_hide_prev == "on"){
				echo $nextA;
			}
			else{
				echo $prevA;
				echo $nextA;
			}

		}
	}

	// top custom html,
	// site title
	// mobile site title
	// mobile menu
	// desktop menu
	// menu bar
	// burger
	public static function get_header(){
		$custom_html_top = get_option( 'misc_options_custom_htmltop', '' );
		if( $custom_html_top != "" ){
			echo $custom_html_top;
		}
	}

	// essential styles are the styles that need to be loaded in head for the website to render OK when most styles are only loaded in the footer
	public static function essential_head_styles_and_desktop_and_phone_styles(){
		// most of my css is loaded in the footer, however, i do need some css so the website will look normal until the css in the footer is loaded
		// need to put this in head, otherwise image will appear opacity:1; and only when the style is loaded it would be opacity:0; and then go back to opacity:1; once loaded
		$prefixes = array( '#search-results-region', '.col', '.project-arrow', '.background-image', '.background-video' );
		$opacity_css = '';
		foreach( $prefixes as $pref ) {
			// not loaded
			$opacity_css .= $pref . ' img.setsizes{opacity: 0;}';
			$opacity_css .= $pref . ' img.lay-gif{opacity: 0;}';
			$opacity_css .= $pref . ' img.lay-image-original{opacity: 0;}';
			$opacity_css .= $pref . ' img.carousel-img{opacity: 0;}';
			// somehow, some videos just dont get a 'loaded' class :/
			$opacity_css .= $pref . ' video{opacity: 0;}';
			// loaded
			$opacity_css .= $pref . ' img.setsizes.loaded{opacity: 1;}';
			$opacity_css .= $pref . ' img.lay-gif.loaded{opacity: 1;}';
			$opacity_css .= $pref . ' img.lay-image-original.loaded{opacity: 1;}';
			$opacity_css .= $pref . ' img.carousel-img.loaded{opacity: 1;}';
			$opacity_css .= $pref . ' video.loaded{opacity: 1;}';
		}

		wp_add_inline_style( 'frontend-style',
		'/* essential styles that need to be in head */
		html{
			overflow-y: scroll;
			height: 100%;
			/* prevent anchor font size from changing when rotating iphone to landscape mode */
			/* also: https://stackoverflow.com/questions/49589861/is-there-a-non-hacky-way-to-prevent-pinch-zoom-on-ios-11-3-safari */
			-webkit-text-size-adjust: none;
			/* prevent overflow while loading */
			overflow-x: hidden!important;
		}
		body{
			background-color: white;
			transition: background-color 300ms ease;
			min-height: 100%;
			margin: 0;
			width: 100%;
		}
		/* lay image opacity css */
		'.$opacity_css.'
		/* lay media query styles */
		@media (min-width: '.(MiscOptions::$phone_breakpoint+1).'px){
			'.MediaQueryCSS::$desktop.'
		}
		@media (max-width: '.(MiscOptions::$phone_breakpoint).'px){
			'.MediaQueryCSS::$phone.'
		}');
	}

	public function frontend_styles() {
		wp_enqueue_style( 'frontend-style', Setup::$uri."/frontend/assets/css/frontend.style.css", array(), Setup::$ver );
	}

	// for projects prev/next navigation
	public static function get_projects_meta($prevnext_navigate_through){

		$meta = array();

		switch($prevnext_navigate_through){
			case 'same_category':
				// create an array for each category that includes all projects of this category
				// a project meta only has one category

				$allCategoryIds = get_terms('category', array('fields' => 'ids'));

				foreach ($allCategoryIds as $key => $catid) {

					$args = array(
						'fields' => 'ids',
						'posts_per_page' => -1,
						'orderby' => 'menu_order',
						'post_type' => 'post',
						'cat' => $catid,
						'order' => 'ASC'
					);

					$query = new WP_Query( $args );

					if ( $query->have_posts() ) {
						foreach ($query->posts as $id){

							$meta[$catid] []= Frontend::get_project_meta($id, $catid);

						}
					};

				}
			break;
			case 'all_projects':
				// a project meta only has one category

				$args = array(
					'fields' => 'ids',
					'posts_per_page' => -1,
					'orderby' => 'menu_order',
					'post_type' => 'post',
					'order' => 'ASC'
				);

				$query = new WP_Query( $args );

				if ( $query->have_posts() ) {
					foreach ($query->posts as $id){

						$catids = wp_get_post_categories( $id );
						$meta []= Frontend::get_project_meta($id, $catids);

					}
				};

			break;
		}

		return json_encode($meta);
	}

	public static function get_project_meta($id, $catid){
		$thumb_id = get_post_thumbnail_id($id);

		$_265 = wp_get_attachment_image_src($thumb_id, '_265');
		$_512 = wp_get_attachment_image_src($thumb_id, '_512');
		$_768 = wp_get_attachment_image_src($thumb_id, '_768');
		$_1024 = wp_get_attachment_image_src($thumb_id, '_1024');
		$_1280 = wp_get_attachment_image_src($thumb_id, '_1280');
		$_1920 = wp_get_attachment_image_src($thumb_id, '_1920');
		$_2560 = wp_get_attachment_image_src($thumb_id, '_2560');
		$_3200 = wp_get_attachment_image_src($thumb_id, '_3200');
		$_3840 = wp_get_attachment_image_src($thumb_id, '_3840');
		$_4096 = wp_get_attachment_image_src($thumb_id, '_4096');
		$full = wp_get_attachment_image_src($thumb_id, 'full');

		$thumbar = 0;
		if($full){
			if($full[1] != 0){
				$thumbar = $full[2] / $full[1];
			}
		}

		$catids = wp_get_post_categories($id, array( 'fields' => 'ids' ));

		return array(
			'title' => get_the_title($id),
			'url' => get_permalink($id),
			'id' => $id,
			'catid' => $catids,
			'thumb' => array(
				'_265' => isset( $_265[0] ) ? $_265[0] : '',
				'_512' => isset( $_512[0] ) ? $_512[0] : '',
				'_768' => isset( $_768[0] ) ? $_768[0] : '',
				'_1024' => isset( $_1024[0] ) ? $_1024[0] : '',
				'_1280' => isset( $_1280[0] ) ? $_1280[0] : '',
				'_1920' => isset( $_1920[0] ) ? $_1920[0] : '',
				'_2560' => isset( $_2560[0] ) ? $_2560[0] : '',
				'_3200' => isset( $_3200[0] ) ? $_3200[0] : '',
				'_3840' => isset( $_3840[0] ) ? $_3840[0] : '',
				'_4096' => isset( $_4096[0] ) ? $_4096[0] : '',
				'full' => isset( $full[0] ) ? $full[0] : ''
			),
			'thumbar' => $thumbar
		);
	}

	public function frontend_scripts(){
		wp_enqueue_script( 'vendor-backbone-radio', Setup::$uri.'/frontend/assets/vendor/backbone.radio.js', array('jquery', 'underscore', 'backbone'), Setup::$ver, false );
		
		// yes i still need marionette for search, intro, lightbox
		wp_enqueue_script( 'frontend-marionettev3', Setup::$uri.'/assets/js/vendor/marionettev3/backbone.marionette.min.js', array('jquery', 'underscore', 'backbone'), Setup::$ver, true );
		// create radio channel here, because ppl might use it in <head> even though it would normally be initialized in app js in footer
		wp_add_inline_script( 'vendor-backbone-radio',
			"window.laytheme = Backbone.Radio.channel('laytheme');
			// Frontend.GlobalEvents for backwards compatibility
			window.Frontend = {};
			window.Frontend.GlobalEvents = Backbone.Radio.channel('globalevents');"
		);

		// loading other vendor js in footer
		wp_register_script( 'vendor-frontend-footer', Setup::$uri.'/frontend/assets/js/vendor.min.js', array(), Setup::$ver, true );
		wp_enqueue_script( 'frontend-app', Setup::$uri."/frontend/assets/js/frontend.app.min.js", array('jquery', 'vendor-frontend-footer'), Setup::$ver, true );
		$sep = get_option( 'misc_options_title_separator', '—' );
		wp_localize_script( 'frontend-app', 'passedDataHistory', array( 'titleprefix'=>get_bloginfo('name').' '.$sep.' ', 'title'=>get_bloginfo('name') ) );

		// is this a good idea?
		wp_deregister_script( 'wp-embed' );

		$pt_textformat = get_theme_mod("pt_textformat", "Default");
		if($pt_textformat != ""){
			$pt_textformat = '_'.$pt_textformat;
		}

		$pt_position = get_theme_mod("pt_position", "below-image");
		$pd_position = get_theme_mod("pd_position", "below-image");

		$st_position = get_theme_mod('st_position', 'top-left');
		$st_hide = get_theme_mod('st_hide', 0);
		$navbar_hide = get_theme_mod('navbar_hide', 0);

		$showArrows = get_option('misc_options_show_project_arrows', '');
		$pa_type = get_theme_mod('pa_type', 'icon');
		$simple_parallax = get_option('misc_options_simple_parallax', '');

		$playicon = '';
		$playicon_id = get_option( 'misc_options_html5video_playicon', '' );
		if($playicon_id != ''){
			$playicon = wp_get_attachment_image_src( $playicon_id, 'full' );
			$playicon = $playicon[0];
		}

		$projectsFooterId = '';
		$footer_active_in_projects = get_option('lay_footer_active_in_projects', 'off');
		if($footer_active_in_projects=="all"){
			$projectsFooterId = get_option('lay_projects_footer', '');
		}

		$pagesFooterId = '';
		$footer_active_in_pages = get_option('lay_footer_active_in_pages', 'off');
		if($footer_active_in_pages=="all"){
			$pagesFooterId = get_option('lay_pages_footer', '');
		}

		$categoriesFooterId = '';
		$footer_active_in_categories = get_option('lay_footer_active_in_categories', 'off');
		if($footer_active_in_categories=="all"){
			$categoriesFooterId = get_option('lay_categories_footer', '');
		}

		$individual_project_footers = get_option('lay_individual_project_footers', '');
		$individual_page_footers = get_option('lay_individual_page_footers', '');
		$individual_category_footers = get_option('lay_individual_category_footers', '');

		$prevnext_navigate_through = get_option('misc_options_prevnext_navigate_through', 'same_category');

		$projectsMeta = Frontend::get_projects_meta($prevnext_navigate_through);

		$navigation_transition_duration = Frontend::get_transition_duration_in_milliseconds();

		$fi_mo_touchdevice_behaviour = get_theme_mod('fi_mo_touchdevice_behaviour', 'mo_dont_show');
		
        /* 
		i changed the misc option inputs from radio to checkbox
		this used to be either "instant_load" or "lazy_load"
		but now it can be '' or 'on'
		'on' is when lazyload is turned on, '' is when lazyload is off
		i need to make sure in my code the values 'instant_load' and 'lazy_load' are handled too
		*/
		$image_loading = get_option('misc_options_image_loading', 'instant_load');
		if( $image_loading == 'on' ) {
			$image_loading = 'lazy_load';
		} else if ($image_loading == '') {
			$image_loading = 'instant_load';
		}

		$cover_scrolldown_on_click = get_option('cover_scrolldown_on_click', '');
		$cover_darken_when_scrolling = get_option('cover_darken_when_scrolling', '');
		$cover_parallaxmove_when_scrolling = get_option('cover_parallaxmove_when_scrolling', '');

		$misc_options_cover = get_option('misc_options_cover', '');

		$misc_options_max_width_apply_to_logo_and_nav = get_option('misc_options_max_width_apply_to_logo_and_nav', '');
		$maxwidth = get_option( 'misc_options_max_width', '0' );

		$default = get_theme_mod('st_fontfamily', Customizer::$defaults['fontfamily']);
		$m_st_fontfamily = get_theme_mod('m_st_fontfamily', $default);

		$misc_options_showoriginalimages = get_option('misc_options_showoriginalimages', '');

		$phone_layout_active = get_option('misc_options_extra_gridder_for_phone', '');

		$misc_options_thumbnail_mouseover_image = get_option('misc_options_thumbnail_mouseover_image', '');

		$frame_leftright = get_option( 'gridder_defaults_frame', LayConstants::gridder_defaults_frame );

		// $bg_color = get_theme_mod('bg_color', '#ffffff');
		// if($bg_color == ""){
		// 	$bg_color = "#ffffff";
		// }
		// $bg_image = get_theme_mod('bg_image', "");

		$is_customize = is_customize_preview();
		$is_ssl = is_ssl();
		$has_www = LTUtility::has_www();

		$is_qtranslate_active = is_plugin_active('qtranslate-x/qtranslate.php') || is_plugin_active('qtranslate-xt-master/qtranslate.php');

		$intro_text_textformat = get_theme_mod('intro_text_textformat', 'Default');
		if($intro_text_textformat != ""){
			$intro_text_textformat = '_'.$intro_text_textformat;
		}

		$element_transition_on_scroll = get_option('misc_options_element_transition_on_scroll', '');
		$misc_options_disable_ajax = get_option( 'misc_options_disable_ajax', "" );

		$anchorscroll_offset_desktop = get_option( 'misc_options_anchorscroll_offset_desktop', 0 );
		$anchorscroll_offset_phone = get_option( 'misc_options_anchorscroll_offset_phone', 0 );

		wp_localize_script( 'frontend-app', 'frontendPassedData',
			array(
				'wpapiroot' => esc_url_raw( rest_url() ),
				'simple_parallax' => $simple_parallax,
				'pa_type' => $pa_type,
				'show_arrows' => $showArrows,
				'projectsMeta' => $projectsMeta,
				// 'siteTitle' => get_bloginfo('name'),
				'nav_amount' => intval(get_option('misc_options_menu_amount', 1)),
				'nav_customizer_properties' => LayMenuCustomizerManager::get_customizer_properties_for_js(),
				'st_hidewhenscrollingdown' => CSS_Output::st_get_hide_when_scrolling_down(),
				'st_hide' => $st_hide,
				'navbar_hide' => $navbar_hide,
				'navbar_position' => CSS_Output::get_navbar_position(),
				'navbar_hidewhenscrollingdown' => CSS_Output::navbar_get_hide_when_scrolling_down(),
				'st_position' => $st_position,
				'footer_active_in_projects' => $footer_active_in_projects,
				'footer_active_in_pages' => $footer_active_in_pages,
				'footer_active_in_categories' => $footer_active_in_categories,
				'projectsFooterId' => $projectsFooterId,
				'pagesFooterId' => $pagesFooterId,
				'categoriesFooterId' => $categoriesFooterId,
				'individual_project_footers' => $individual_project_footers,
				'individual_page_footers' => $individual_page_footers,
				'individual_category_footers' => $individual_category_footers,
				'prevnext_navigate_through' => $prevnext_navigate_through,
				'navigation_transition_duration' => $navigation_transition_duration,
				'fi_mo_touchdevice_behaviour' => $fi_mo_touchdevice_behaviour,
				'image_loading' => $image_loading,
				'cover_scrolldown_on_click' => $cover_scrolldown_on_click,
				'cover_darken_when_scrolling' => $cover_darken_when_scrolling,
				'cover_parallaxmove_when_scrolling' => $cover_parallaxmove_when_scrolling,
				'cover_disable_for_phone' => get_option('cover_disable_for_phone', '') == 'on' ? true : false,
				'misc_options_cover' => $misc_options_cover,
				'misc_options_max_width_apply_to_logo_and_nav' => $misc_options_max_width_apply_to_logo_and_nav,
				'maxwidth' => $maxwidth,
				'frame_leftright' => $frame_leftright,
				'm_st_fontfamily' => $m_st_fontfamily,
				'misc_options_showoriginalimages' => $misc_options_showoriginalimages,
				'phone_layout_active' => $phone_layout_active,
				'breakpoint' => MiscOptions::$phone_breakpoint,
				'tabletbreakpoint' => get_option('lay_tablet_breakpoint', 1024),
				'shortcodes' => LayShortcodes::$shortcodes_array,
				'is_customize' => $is_customize,
				'mobile_hide_menubar' => get_theme_mod('mobile_hide_menubar', 0),
				'mobile_menu_style' => get_theme_mod('mobile_menu_style', 'style_1'),
				'mobile_burger_type' => get_theme_mod('burger_icon_type', 'default'),
				'siteUrl' => get_site_url(),
				'ajax_url' => admin_url( 'admin-ajax.php' ),
				'intro_active' => get_option('misc_options_intro', 'on') == 'on' ? true : false,
				'intro_hide_after' => get_option( 'intro_hide_after', '4000' ),
				// seems to be 0 and 1 instead of 'on' and 'off', maybe because this option is set in the customizer
				'intro_movement' => get_theme_mod( 'intro_movement', 0 ) == 1 ? true : false,
				'intro_landscape' => LayIntro::get_data('landscape'),
				'intro_portrait' => LayIntro::get_data('portrait'),
				'intro_use_svg_overlay' => get_theme_mod('intro_use_svg_overlay', 0) == 1 ? true : false,
				'intro_use_text_overlay' => get_theme_mod('intro_use_text_overlay', 0) == 1 ? true : false,
				'intro_text' => get_theme_mod('intro_text', ''),
				'is_frontpage' => is_front_page(),
				'intro_svg_url' => LayIntro::get_svg_overlay_url(),
				'intro_text_textformat' => $intro_text_textformat,
				'is_ssl' => $is_ssl,
				'has_www' => $has_www,
				'is_qtranslate_active' => $is_qtranslate_active,
				'video_thumbnail_mouseover_behaviour' => get_theme_mod('fi_mo_video_behaviour', 'autoplay'),
				'element_transition_on_scroll' => $element_transition_on_scroll,
				'mobile_navbar_fixed' => get_theme_mod('mobile_menu_isfixed', '1'),
				'misc_options_disable_ajax' => $misc_options_disable_ajax,
				'anchorscroll_offset_desktop' => $anchorscroll_offset_desktop,
				'anchorscroll_offset_phone' => $anchorscroll_offset_phone,
			)
		);
	}

	public function include_griddermeta_in_term(){
		register_rest_field( 'category',
		    'grid',
		    array(
		        'get_callback'    => array($this, 'get_term_griddermeta'),
		        'update_callback' => null,
		        'schema'          => null,
		    )
		);

		register_rest_field( 'category',
		    'phonegrid',
		    array(
		        'get_callback'    => array($this, 'get_term_phone_griddermeta'),
		        'update_callback' => null,
		        'schema'          => null,
		    )
		);
	}

	public function get_term_griddermeta( $object, $field_name, $request ){
		return get_option( $object['id'].'_category_gridder_json', '' );
	}

	public function get_term_phone_griddermeta( $object, $field_name, $request ){
		return get_option( $object['id'].'_phone_category_gridder_json', '' );
	}

	public function include_griddermeta_in_page(){
		register_rest_field( 'page',
		    'grid',
		    array(
		        'get_callback'    => array($this, 'get_post_griddermeta'),
		        'update_callback' => null,
		        'schema'          => null,
		    )
		);

		register_rest_field( 'page',
		    'phonegrid',
		    array(
		        'get_callback'    => array($this, 'get_post_phone_griddermeta'),
		        'update_callback' => null,
		        'schema'          => null,
		    )
		);
	}

	public function include_griddermeta_in_post(){
		register_rest_field( 'post',
		    'grid',
		    array(
		        'get_callback'    => array($this, 'get_post_griddermeta'),
		        'update_callback' => null,
		        'schema'          => null,
		    )
		);

		register_rest_field( 'post',
		    'phonegrid',
		    array(
		        'get_callback'    => array($this, 'get_post_phone_griddermeta'),
		        'update_callback' => null,
		        'schema'          => null,
		    )
		);
	}

	public function get_post_phone_griddermeta( $object, $field_name, $request ){
		if ( $this->can_access_password_content( $object, $request ) ) {
			return get_post_meta( $object['id'], '_phone_gridder_json', true );
		} else {
			return '';
		}
	}

	public function get_post_griddermeta( $object, $field_name, $request ){
		if ( $this->can_access_password_content( $object, $request ) ) {
			return get_post_meta( $object['id'], '_gridder_json', true );
		} else {
			return '';
		}
	}

	// modified version, taken from: class-wp-rest-posts-controller.php
	public function can_access_password_content( $object, $request ) {
		$post = get_post( $object['id'] );
		$request_params = $request->get_params();

		if ( empty( $post->post_password ) ) {
			// No password required, can access content
			return true;
		}

		// Edit context always gets access to password-protected posts.
		if ( 'edit' === $request_params['context'] ) {
			return true;
		}

		// No password, no auth.
		if ( empty( $request_params['password'] ) ) {
			return false;
		}

		// Double-check the request password.
		return hash_equals( $post->post_password, $request_params['password'] );
	}

}
$frontend = new Frontend();