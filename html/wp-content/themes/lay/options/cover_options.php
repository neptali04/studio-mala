<?php
// http://codex.wordpress.org/Settings_API#Examples
class CoverOptions{

	public function __construct(){
		$val = get_option('misc_options_cover', '');
		if($val == "on"){
			add_action( 'admin_menu', array($this, 'cover_options_setup_menu'), 10 );
			add_action( 'admin_init', array($this, 'misc_options_settings_api_init') );
		}
	}

	public function misc_options_settings_api_init(){
	 	add_settings_section(
			'cover_options_section',
			'',
			'',
			'manage-coveroptions'
		);
		
	 	add_settings_field(
			'cover_active_in_projects',
			'Cover for Projects',
			array($this, 'cover_active_in_projects_setting'),
			'manage-coveroptions',
			'cover_options_section'
		);
	 	register_setting( 'manage-coveroptions', 'cover_active_in_projects' );

 	 	add_settings_field(
 			'cover_active_in_pages',
 			'Cover for Pages',
 			array($this, 'cover_active_in_pages_setting'),
 			'manage-coveroptions',
 			'cover_options_section'
 		);
 	 	register_setting( 'manage-coveroptions', 'cover_active_in_pages' );

 	 	add_settings_field(
 			'cover_active_in_categories',
 			'Cover for Categories',
 			array($this, 'cover_active_in_categories_setting'),
 			'manage-coveroptions',
 			'cover_options_section'
 		);
 	 	register_setting( 'manage-coveroptions', 'cover_active_in_categories' );

 	 	add_settings_field(
 			'cover_scrolldown_on_click',
 			'Scroll down on Cover click',
 			array($this, 'cover_scrolldown_on_click_setting'),
 			'manage-coveroptions',
 			'cover_options_section'
 		);
 	 	register_setting( 'manage-coveroptions', 'cover_scrolldown_on_click' );

 	 	add_settings_field(
 			'cover_darken_when_scrolling',
 			'Darken Cover when scrolling down',
 			array($this, 'cover_darken_when_scrolling_setting'),
 			'manage-coveroptions',
 			'cover_options_section'
 		);
 	 	register_setting( 'manage-coveroptions', 'cover_darken_when_scrolling' );

 	 	add_settings_field(
 			'cover_parallaxmove_when_scrolling',
 			'Parallax move when scrolling down',
 			array($this, 'cover_parallaxmove_when_scrolling_setting'),
 			'manage-coveroptions',
 			'cover_options_section'
 		);
 	 	register_setting( 'manage-coveroptions', 'cover_parallaxmove_when_scrolling' );

 	 	add_settings_field(
 			'cover_disable_for_phone',
 			'Disable for phone',
 			array($this, 'cover_disable_for_phone_setting'),
 			'manage-coveroptions',
 			'cover_options_section'
 		);
 	 	register_setting( 'manage-coveroptions', 'cover_disable_for_phone' );

	}

	public function cover_disable_for_phone_setting(){
		$val = get_option('cover_disable_for_phone', '');
		$checked = "";
		if( $val == "on" ){
			$checked = "checked";
		}
		echo '<input type="checkbox" name="cover_disable_for_phone" id="cover_disable_for_phone" '.$checked.'>';
	}

	public function cover_parallaxmove_when_scrolling_setting(){
		$val = get_option('cover_parallaxmove_when_scrolling', '');
		$checked = "";
		if( $val == "on" ){
			$checked = "checked";
		}
		echo '<input type="checkbox" name="cover_parallaxmove_when_scrolling" id="cover_parallaxmove_when_scrolling" '.$checked.'>';
	}

	public function cover_darken_when_scrolling_setting(){
		$val = get_option('cover_darken_when_scrolling', '');
		$checked = "";
		if( $val == "on" ){
			$checked = "checked";
		}
		echo '<input type="checkbox" name="cover_darken_when_scrolling" id="cover_darken_when_scrolling" '.$checked.'>';
	}

	public function cover_scrolldown_on_click_setting(){
		$val = get_option('cover_scrolldown_on_click', '');
		$checked = "";
		if( $val == "on" ){
			$checked = "checked";
		}
		echo '<input type="checkbox" name="cover_scrolldown_on_click" id="cover_scrolldown_on_click" '.$checked.'><label>(Not recommended if you have clickable elements on your cover like links, videos, carousels etc.)</label>';
	}

	public function cover_active_in_projects_setting(){
		$val = get_option( 'cover_active_in_projects', "off" );
		$checkedAll = "";
		$checkedIndividual = "";
		$checkedOff = "";

		if($val == "on" || $val == "all"){
			$checkedAll = "checked";
		}
		else if($val == "individual"){
			$checkedIndividual = "checked";
		}
		else if($val == "off" || $val == ""){
			$checkedOff = "checked";
		}

		echo 
		'<input type="radio" name="cover_active_in_projects" value="all" '.$checkedAll.' id="all-projects"><label for="all-projects">Active for all Projects</label><br>
		<input type="radio" name="cover_active_in_projects" value="individual" '.$checkedIndividual.' id="individual-projects"><label for="individual-projects">Show a checkbox in the Project Edit Screen to activate Cover for individual Projects</label><br>
		<input type="radio" name="cover_active_in_projects" value="off" '.$checkedOff.' id="off-projects"><label for="off-projects">Off for all Projects</label>';
	}

	public function cover_active_in_pages_setting(){
		$val = get_option( 'cover_active_in_pages', "off" );
		$checkedAll = "";
		$checkedIndividual = "";
		$checkedOff = "";

		if($val == "on" || $val == "all"){
			$checkedAll = "checked";
		}
		else if($val == "individual"){
			$checkedIndividual = "checked";
		}
		else if($val == "off" || $val == ""){
			$checkedOff = "checked";
		}

		echo 
		'<input type="radio" name="cover_active_in_pages" value="all" '.$checkedAll.' id="all-pages"><label for="all-pages">Active for all Pages</label><br>
		<input type="radio" name="cover_active_in_pages" value="individual" '.$checkedIndividual.' id="individual-pages"><label for="individual-pages">Show a checkbox in the Page Edit Screen to activate Cover for individual Pages</label><br>
		<input type="radio" name="cover_active_in_pages" value="off" '.$checkedOff.' id="off-pages"><label for="off-pages">Off for all Pages</label>';
	}

	public function cover_active_in_categories_setting(){
		$val = get_option( 'cover_active_in_categories', "off" );
		$checkedAll = "";
		$checkedIndividual = "";
		$checkedOff = "";

		if($val == "on" || $val == "all"){
			$checkedAll = "checked";
		}
		else if($val == "individual"){
			$checkedIndividual = "checked";
		}
		else if($val == "off" || $val == ""){
			$checkedOff = "checked";
		}

		echo
		'<input type="radio" name="cover_active_in_categories" value="all" '.$checkedAll.' id="all-categories"><label for="all-categories">Active for all Categories</label><br>
		<input type="radio" name="cover_active_in_categories" value="individual" '.$checkedIndividual.' id="individual-categories"><label for="individual-categories">Show a checkbox in the Category Edit Screen to activate Cover for individual Categories</label><br>
		<input type="radio" name="cover_active_in_categories" value="off" '.$checkedOff.' id="off-categories"><label for="off-categories">Off for all Categories</label>';
	}

	public function cover_options_setup_menu(){
		// http://wordpress.stackexchange.com/questions/66498/add-menu-page-with-different-name-for-first-submenu-item
        add_submenu_page( 'manage-layoptions', 'Cover Options', 'Cover Options', 'manage_options', 'manage-coveroptions', array($this, 'cover_options_markup') );
	}
	
	public function cover_options_markup(){
		require_once( Setup::$dir.'/options/cover_options_markup.php' );
	}

	// if arrayIn is just '' instead of an array, this function will return an empty array
	public static function flattenArray($arrayIn){
		$arrayOut = array();
		if(is_array($arrayIn)){
			foreach ($arrayIn as $key => $value) {
				$arrayOut []= $arrayIn[$key];
			}
		}
		return $arrayOut;
	}

 	// for an unknown reason in rare cases the arrays are saved as associative arrays, so flatten them here
	public static function get_individual_project_ids(){
		$individualProjectIds = get_option('cover_individual_project_ids', '');
		$individualProjectIds = json_decode( $individualProjectIds, true );
		$individualProjectIds = json_encode( CoverOptions::flattenArray($individualProjectIds) );
		return $individualProjectIds;
	}

	public static function get_individual_page_ids(){
		$individualPageIds = get_option('cover_individual_page_ids', '');
		$individualPageIds = json_decode( $individualPageIds, true );
		$individualPageIds = json_encode( CoverOptions::flattenArray($individualPageIds) );
		return $individualPageIds;
	}

	public static function get_individual_category_ids(){
		$individualCategoryIds = get_option('cover_individual_category_ids', '');
		$individualCategoryIds = json_decode( $individualCategoryIds, true );
		$individualCategoryIds = json_encode( CoverOptions::flattenArray($individualCategoryIds) );
		return $individualCategoryIds;
	}

}
new CoverOptions();