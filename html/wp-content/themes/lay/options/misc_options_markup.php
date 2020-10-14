<div class="wrap">
    <h2>Lay Options</h2>

    <?php if(isset( $_GET['settings-updated'])) { ?>
    <div class="updated">
        <p>Lay Options updated.</p>
    </div>
    <?php } ?>
   
	<form method="POST" action="options.php">
	<?php settings_fields( 'manage-miscoptions' );	//pass slug name of page, also referred
	                                        //to in Settings API as option group name
	do_settings_sections( 'manage-miscoptions' ); 	//pass slug name of page
	submit_button();
	?>
	</form>
</div>