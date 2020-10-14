<?php
// http://codex.wordpress.org/Settings_API#Examples
class GridderOptions{

	public function __construct(){
		add_action( 'admin_menu', array($this, 'layoptions_setup_menu'), 10 );
		add_action( 'admin_init', array($this, 'gridder_defaults_settings_api_init') );
	}

	public function layoptions_setup_menu(){
		// http://wordpress.stackexchange.com/questions/66498/add-menu-page-with-different-name-for-first-submenu-item

        add_submenu_page( 'manage-layoptions', 'Gridder Defaults', 'Gridder Defaults', 'manage_options', 'manage-gridderdefaults', array($this, 'gridder_options_markup') );
	}
	 
	public function gridder_options_markup(){
    	require_once( Setup::$dir.'/options/gridder_options_markup.php' );
	}

	public function gridder_defaults_settings_api_init() {
	 	// Add the section to gridderdefaults settings so we can add our
	 	// fields to it
	 	add_settings_section(
			'gridder_defaults_section',
			'Desktop Gridder Defaults',
			'',
			'manage-gridderdefaults'
		);
	 	
	 	// Add the field with the names and function to use for our new
	 	// settings, put it in our new section
	 	add_settings_field(
			'gridder_defaults_columncount',
			'Column Count',
			array($this, 'gridder_setting_columns'),
			'manage-gridderdefaults',
			'gridder_defaults_section'
		);
	 	// Register our setting so that $_POST handling is done for us and
	 	// our callback function just has to echo the <input>
	 	register_setting( 'manage-gridderdefaults', 'gridder_defaults_columncount' );

 	 	add_settings_field(
 			'gridder_defaults_column_gutter',
 			'Column Gutter',
 			array($this, 'gridder_setting_column_gutter'),
 			'manage-gridderdefaults',
 			'gridder_defaults_section'
 		);
 	 	register_setting( 'manage-gridderdefaults', 'gridder_defaults_column_gutter' );

 	 	add_settings_field(
 			'gridder_defaults_rowgutter_mu',
 			'Row Gutter in',
 			array($this, 'gridder_setting_rowgutter_mu'),
 			'manage-gridderdefaults',
 			'gridder_defaults_section'
 		);
 	 	register_setting( 'manage-gridderdefaults', 'gridder_defaults_rowgutter_mu' );

 	 	add_settings_field(
 			'gridder_defaults_row_gutter',
 			'Row Gutter',
 			array($this, 'gridder_setting_row_gutter'),
 			'manage-gridderdefaults',
 			'gridder_defaults_section'
 		);
 	 	register_setting( 'manage-gridderdefaults', 'gridder_defaults_row_gutter' );

 	 	add_settings_field(
 			'gridder_defaults_topframe_mu',
 			'Frame Top in',
 			array($this, 'gridder_setting_topframe_mu'),
 			'manage-gridderdefaults',
 			'gridder_defaults_section'
 		);
 	 	register_setting( 'manage-gridderdefaults', 'gridder_defaults_topframe_mu' );

 	 	add_settings_field(
 			'gridder_defaults_topframe',
 			'Frame Top',
 			array($this, 'gridder_setting_topframe'),
 			'manage-gridderdefaults',
 			'gridder_defaults_section'
 		);
 	 	register_setting( 'manage-gridderdefaults', 'gridder_defaults_topframe' );

 	 	add_settings_field(
 			'gridder_defaults_frame',
 			'Frame Left, Right',
 			array($this, 'gridder_setting_frame'),
 			'manage-gridderdefaults',
 			'gridder_defaults_section'
 		);
 	 	register_setting( 'manage-gridderdefaults', 'gridder_defaults_frame' );

 	 	add_settings_field(
 			'gridder_defaults_bottomframe_mu',
 			'Frame Bottom in',
 			array($this, 'gridder_setting_bottomframe_mu'),
 			'manage-gridderdefaults',
 			'gridder_defaults_section'
 		);
 	 	register_setting( 'manage-gridderdefaults', 'gridder_defaults_bottomframe_mu' );

 	 	add_settings_field(
 			'gridder_defaults_bottomframe',
 			'Frame Bottom',
 			array($this, 'gridder_setting_bottomframe'),
 			'manage-gridderdefaults',
 			'gridder_defaults_section'
 		);
 	 	register_setting( 'manage-gridderdefaults', 'gridder_defaults_bottomframe' );

 	 	// phone gridder defaults
 	 	if(MiscOptions::$phoneLayoutActive){
	 	 	add_settings_section(
	 			'phone_gridder_defaults_section',
	 			'Phone Gridder Defaults',
	 			'',
	 			'manage-gridderdefaults'
	 		);
		 	add_settings_field(
				'phone_gridder_defaults_columncount',
				'Column Count',
				array($this, 'phone_gridder_setting_columns'),
				'manage-gridderdefaults',
				'phone_gridder_defaults_section'
			);
		 	register_setting( 'manage-gridderdefaults', 'phone_gridder_defaults_columncount' );

	 	 	add_settings_field(
	 			'phone_gridder_defaults_column_gutter',
	 			'Column Gutter',
	 			array($this, 'phone_gridder_setting_column_gutter'),
	 			'manage-gridderdefaults',
	 			'phone_gridder_defaults_section'
	 		);
	 	 	register_setting( 'manage-gridderdefaults', 'phone_gridder_defaults_column_gutter' );

	 	 	add_settings_field(
	 			'phone_gridder_defaults_row_gutter',
	 			'Row Gutter',
	 			array($this, 'phone_gridder_setting_row_gutter'),
	 			'manage-gridderdefaults',
	 			'phone_gridder_defaults_section'
	 		);
	 	 	register_setting( 'manage-gridderdefaults', 'phone_gridder_defaults_row_gutter' );

	 	 	add_settings_field(
	 			'phone_gridder_defaults_topframe',
	 			'Frame Top',
	 			array($this, 'phone_gridder_setting_topframe'),
	 			'manage-gridderdefaults',
	 			'phone_gridder_defaults_section'
	 		);
	 	 	register_setting( 'manage-gridderdefaults', 'phone_gridder_defaults_topframe' );

 	 	 	add_settings_field(
 	 			'phone_gridder_defaults_frame',
 	 			'Frame Left, Right',
 	 			array($this, 'phone_gridder_setting_frame'),
 	 			'manage-gridderdefaults',
 	 			'phone_gridder_defaults_section'
 	 		);
 	 	 	register_setting( 'manage-gridderdefaults', 'phone_gridder_defaults_frame' );

	 	 	add_settings_field(
	 			'phone_gridder_defaults_bottomframe',
	 			'Frame Bottom',
	 			array($this, 'phone_gridder_setting_bottomframe'),
	 			'manage-gridderdefaults',
	 			'phone_gridder_defaults_section'
	 		);
	 	 	register_setting( 'manage-gridderdefaults', 'phone_gridder_defaults_bottomframe' );
 	 	}

 	 	// apply to existing grids?
 	 	add_settings_section(
 			'apply_to_grids_section',
 			'Apply Defaults to existing Grids?',
 			'',
 			'manage-gridderdefaults'
 		);

	 	add_settings_field(
			'gridder_apply',
			'Apply "Desktop Gridder Defaults"',
			array($this, 'gridder_apply_setting'),
			'manage-gridderdefaults',
			'apply_to_grids_section'
		);
	 	register_setting( 'manage-gridderdefaults', 'gridder_apply' );
	 	
	 	if(MiscOptions::$phoneLayoutActive){
 		 	add_settings_field(
 				'phone_gridder_apply',
 				'Apply "Phone Gridder Defaults"',
 				array($this, 'phone_gridder_apply_setting'),
 				'manage-gridderdefaults',
 				'apply_to_grids_section'
 			);
 		 	register_setting( 'manage-gridderdefaults', 'phone_gridder_apply' );
	 	}
	}

	function phone_gridder_apply_setting(){
		echo '<input type="checkbox" name="phone_gridder_apply" id="phone_gridder_apply">';
	}

	function gridder_apply_setting(){
		echo '<input type="checkbox" name="gridder_apply" id="gridder_apply">';
	}

	function gridder_setting_columns() {
	 	$columncount = get_option( 'gridder_defaults_columncount', LayConstants::gridder_defaults_columncount );
	 	echo 
	 	'<select name="gridder_defaults_columncount" id="gridder_defaults_columncount">';
	 		for($i = 1; $i<32; $i++){
	 			$selected = $i == $columncount ? 'selected="selected"' : '';
	 			echo '<option value="'.$i.'" '.$selected.'>'.$i.'</option>';
	 		}
		echo '</select>';
	}

	function gridder_setting_column_gutter(){
	 	$colgutter = get_option( 'gridder_defaults_column_gutter', LayConstants::gridder_defaults_column_gutter );
	 	echo 
	 	'<input type="number" autocomplete="off" min="0" max="100" step="0.1" value="'.$colgutter.'" name="gridder_defaults_column_gutter" id="gridder_defaults_column_gutter"> %';
	}

	function gridder_setting_row_gutter(){
	 	$rowgutter = get_option( 'gridder_defaults_row_gutter', LayConstants::gridder_defaults_row_gutter );
	 	echo 
	 	'<input type="number" autocomplete="off" min="0" max="999" step="0.1" value="'.$rowgutter.'" name="gridder_defaults_row_gutter" id="gridder_defaults_row_gutter">';
	}

	function gridder_setting_frame(){
	 	$frame = get_option( 'gridder_defaults_frame', LayConstants::gridder_defaults_frame );
	 	echo 
	 	'<input type="number" autocomplete="off" min="0" max="40" step="0.1" value="'.$frame.'" name="gridder_defaults_frame" id="gridder_defaults_frame"> %';
	}

	function gridder_setting_topframe(){
	 	$frame = get_option( 'gridder_defaults_topframe', LayConstants::gridder_defaults_topframe );
	 	echo 
	 	'<input type="number" autocomplete="off" min="0" max="999" step="0.1" value="'.$frame.'" name="gridder_defaults_topframe" id="gridder_defaults_topframe">';
	}

	function gridder_setting_bottomframe(){
	 	$frame = get_option( 'gridder_defaults_bottomframe', LayConstants::gridder_defaults_bottomframe );
	 	echo 
	 	'<input type="number" autocomplete="off" min="0" max="999" step="0.1" value="'.$frame.'" name="gridder_defaults_bottomframe" id="gridder_defaults_bottomframe">';
	}

	function gridder_setting_rowgutter_mu(){
		$mu = get_option( 'gridder_defaults_rowgutter_mu', LayConstants::gridder_defaults_rowgutter_mu );

		$selpercent = '';
		$selpixel = 'selected';

		if($mu == '%'){
			$selpercent = 'selected';
			$selpixel = '';
		}
		echo 
		'<select name="gridder_defaults_rowgutter_mu" id="gridder_defaults_rowgutter_mu">
			 <option '.$selpercent.' value="%">%</option>
			 <option '.$selpixel.' value="px">px</option>
		</select>';		
	}

	function gridder_setting_topframe_mu(){
		$mu = get_option( 'gridder_defaults_topframe_mu', LayConstants::gridder_defaults_rowgutter_mu );

		$selpercent = '';
		$selpixel = 'selected';

		if($mu == '%'){
			$selpercent = 'selected';
			$selpixel = '';
		}
		echo 
		'<select name="gridder_defaults_topframe_mu" id="gridder_defaults_topframe_mu">
			 <option '.$selpercent.' value="%">%</option>
			 <option '.$selpixel.' value="px">px</option>
		</select>';		
	}

	function gridder_setting_bottomframe_mu(){
		$mu = get_option( 'gridder_defaults_bottomframe_mu', LayConstants::gridder_defaults_bottomframe_mu );

		$selpercent = '';
		$selpixel = 'selected';

		if($mu == '%'){
			$selpercent = 'selected';
			$selpixel = '';
		}
		echo
		'<select name="gridder_defaults_bottomframe_mu" id="gridder_defaults_bottomframe_mu">
			 <option '.$selpercent.' value="%">%</option>
			 <option '.$selpixel.' value="px">px</option>
		</select>';
	}

	// phone gridder settings
	function phone_gridder_setting_columns() {
	 	$columncount = get_option( 'phone_gridder_defaults_columncount', LayConstants::gridder_defaults_rowgutter_mu );
	 	echo 
	 	'<select name="phone_gridder_defaults_columncount" id="phone_gridder_defaults_columncount">';
	 		for($i = 1; $i<32; $i++){
	 			$selected = $i == $columncount ? 'selected="selected"' : '';
	 			echo '<option value="'.$i.'" '.$selected.'>'.$i.'</option>';
	 		}
		echo '</select>';
	}

	function phone_gridder_setting_column_gutter(){
	 	$colgutter = get_option( 'phone_gridder_defaults_column_gutter', LayConstants::phone_gridder_defaults_column_gutter );
	 	echo 
	 	'<input type="number" autocomplete="off" min="0" max="100" step="0.1" value="'.$colgutter.'" name="phone_gridder_defaults_column_gutter" id="phone_gridder_defaults_column_gutter"> %';
	}

	function phone_gridder_setting_row_gutter(){
	 	$rowgutter = get_option( 'phone_gridder_defaults_row_gutter', LayConstants::phone_gridder_defaults_row_gutter );
	 	echo 
	 	'<input type="number" autocomplete="off" min="0" step="0.1" value="'.$rowgutter.'" name="phone_gridder_defaults_row_gutter" id="phone_gridder_defaults_row_gutter"> %';
	}

	function phone_gridder_setting_frame(){
	 	$frame = get_option( 'phone_gridder_defaults_frame', LayConstants::phone_gridder_defaults_frame );
	 	echo 
	 	'<input type="number" autocomplete="off" min="0" max="40" step="0.1" value="'.$frame.'" name="phone_gridder_defaults_frame" id="phone_gridder_defaults_frame"> %';
	}

	function phone_gridder_setting_topframe(){
	 	$frame = get_option( 'phone_gridder_defaults_topframe', LayConstants::phone_gridder_defaults_topframe );
	 	echo 
	 	'<input type="number" autocomplete="off" min="0" max="999" step="0.1" value="'.$frame.'" name="phone_gridder_defaults_topframe" id="phone_gridder_defaults_topframe"> %';
	}

	function phone_gridder_setting_bottomframe(){
	 	$frame = get_option( 'phone_gridder_defaults_bottomframe', LayConstants::phone_gridder_defaults_bottomframe );
	 	echo 
	 	'<input type="number" autocomplete="off" min="0" max="999" step="0.1" value="'.$frame.'" name="phone_gridder_defaults_bottomframe" id="phone_gridder_defaults_bottomframe"> %';
	}

	private static function updateGridderJSONObj($jsonObj, $topframe, $bottomframe, $frame, $rowgutter, $colgutter){
		if(!is_null($jsonObj)){
			for($i=0; $i<count($jsonObj["rowGutters"]); $i++) {
				$jsonObj["rowGutters"][$i] = (float)$rowgutter;
			}	

			$jsonObj['colGutter'] = (float)$colgutter;
			$jsonObj['frameMargin'] = (float)$frame;
			$jsonObj['topFrameMargin'] = (float)$topframe;
			$jsonObj['bottomFrameMargin'] = (float)$bottomframe;

			$jsonStr = json_encode($jsonObj);

			return $jsonStr;
		}
		else{
			return false;
		}
	}

	public static function applyPhoneDefaultsToAllExistingGrids(){
		$colgutter = get_option( 'phone_gridder_defaults_column_gutter', LayConstants::phone_gridder_defaults_column_gutter );
		$rowgutter = get_option( 'phone_gridder_defaults_row_gutter', LayConstants::phone_gridder_defaults_row_gutter );
		$frame = get_option( 'phone_gridder_defaults_frame', LayConstants::phone_gridder_defaults_frame );
		$topframe = get_option( 'phone_gridder_defaults_topframe', LayConstants::phone_gridder_defaults_topframe );
		$bottomframe = get_option( 'phone_gridder_defaults_bottomframe', LayConstants::phone_gridder_defaults_bottomframe );

		GridderOptions::updateGridderDefaultsInJSON('phone', $topframe, $bottomframe, $frame, $rowgutter, $colgutter);

		echo
		'<div class="updated">
		    <p>Phone Gridder Defaults applied.</p>
		</div>';
	}

	public static function applyDesktopDefaultsToAllExistingGrids(){
		$topframe = get_option( 'gridder_defaults_topframe', LayConstants::gridder_defaults_topframe );
		$bottomframe = get_option( 'gridder_defaults_bottomframe', LayConstants::gridder_defaults_bottomframe );
		$frame = get_option( 'gridder_defaults_frame', LayConstants::gridder_defaults_frame );
		$rowgutter = get_option( 'gridder_defaults_row_gutter', LayConstants::gridder_defaults_row_gutter );
		$colgutter = get_option( 'gridder_defaults_column_gutter', LayConstants::gridder_defaults_column_gutter );

		GridderOptions::updateGridderDefaultsInJSON('desktop', $topframe, $bottomframe, $frame, $rowgutter, $colgutter);

		echo
		'<div class="updated">
		    <p>Desktop Gridder Defaults applied.</p>
		</div>';
	}

	public static function updateGridderDefaultsInJSON($type, $topframe, $bottomframe, $frame, $rowgutter, $colgutter){
		// loop through all posts and pages
		$args = array(
			'post_type' => array('post', 'page'),
			'post_status' => 'publish',
			'posts_per_page' => -1,
			'fields' => 'ids'
		);
		$result = '';

		$metaname = $type == "desktop" ? "_gridder_json" : "_phone_gridder_json";

		$query = new WP_Query( $args );
		if ( $query->have_posts() ) {
			foreach ($query->posts as $id){
				$gridderJsonString = get_post_meta( $id, $metaname, true );

				if($gridderJsonString != "" && $gridderJsonString != "null"){
					$jsonObj = json_decode($gridderJsonString, true);
					$jsonStr = GridderOptions::updateGridderJSONObj($jsonObj, $topframe, $bottomframe, $frame, $rowgutter, $colgutter);
					if($jsonStr){
						// http://wordpress.stackexchange.com/questions/53336/wordpress-is-stripping-escape-backslashes-from-json-strings-in-post-meta
						$jsonStr = wp_slash($jsonStr);
						update_post_meta( $id, $metaname, $jsonStr );	
					}
				}

			}
		}

		$optionname = $type == "desktop" ? "_category_gridder_json" : "_phone_category_gridder_json";

		// loop through all terms
		$terms = get_terms(array('taxonomy' => 'category', 'hide_empty' => false, 'fields' => 'ids'));
		foreach ($terms as $key => $term_id) {
		 	$gridderJsonString = get_option($term_id.$optionname, '');
		 	$jsonObj = json_decode($gridderJsonString, true);
		 	$jsonStr = GridderOptions::updateGridderJSONObj($jsonObj, $topframe, $bottomframe, $frame, $rowgutter, $colgutter);
	 		if($jsonStr){
		 		update_option( $term_id.$optionname, $jsonStr );
		 	}
		}
	}

}
new GridderOptions();