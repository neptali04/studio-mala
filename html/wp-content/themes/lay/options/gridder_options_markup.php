<div class="wrap">
    <h2>Gridder Defaults</h2>

    <?php if(isset( $_GET['settings-updated'])) {
        $gridder_apply = get_option('gridder_apply', '');
        if($gridder_apply == "on"){
            GridderOptions::applyDesktopDefaultsToAllExistingGrids();
        }
        $phone_gridder_apply = get_option('phone_gridder_apply', '');
        if($phone_gridder_apply == "on"){
            GridderOptions::applyPhoneDefaultsToAllExistingGrids();
        }
        if($phone_gridder_apply != "on" && $gridder_apply != "on"){
            echo '<div class="updated"><p>Gridder Defaults saved.</p></div>';
        }
    } ?>
    
    <div class="lay-explanation <?php echo get_option('expand-how_to_use_gridder_defaults', 'expanded'); ?>" data-expand-status-option-name="expand-how_to_use_gridder_defaults">
        <header>
            <h3 class="title">How to use</h3>
            <button class="lay-explanation-handle"></button>
        </header>
        <div class="lay-explanation-inner">
        	<p>The "Gridder" is the interface you see when you create layouts for Projects, Pages or Categories.</p>
        	<p>If you check "Apply Desktop Gridder Defaults" and click <span class="lay-label label-info">Save Changes</span> these defaults (except for Column Count) will be applied to all Grids of existing Projects, Pages and Categories.</p>
            <?php
                $misc_options_extra_gridder_for_phone = get_option('misc_options_extra_gridder_for_phone', '');
                if($misc_options_extra_gridder_for_phone == "on"){
                    ?>
                    <p>The Phone Gridder Defaults only apply to custom phone layouts created with the <span style="border-radius:2px;border: 1px solid #ccc;color:#333;background-color:#fff;display:inline;padding:4px 10px;"><span class="dashicons dashicons-smartphone"></span></span> phone button in the Gridder. For automatically generated phone layouts please set the values in "Customize" &rarr; "Mobile" &rarr; "Mobile Spaces".</p>
                    <?php
                }
            ?>
        </div>
    </div>

	<form method="POST" action="options.php">
	<?php settings_fields( 'manage-gridderdefaults' );	//pass slug name of page, also referred
	                                        //to in Settings API as option group name
	do_settings_sections( 'manage-gridderdefaults' ); 	//pass slug name of page

    submit_button();
	?>
	</form>
</div>