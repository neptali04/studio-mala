<div class="wrap">
    <h2>Carousel Addon</h2>

    <?php if(isset( $_GET['settings-updated'])) { ?>
	    <div class="updated">
	        <p>Settings updated successfully</p>
	    </div>
    <?php } ?>
	<div class="lay-explanation">
		<div class="lay-explanation-inner">
			<p>Add a carousel in the Gridder by clicking "+More" &rarr; "+Carousel".</p>
			<p>Want to use a SVG as a mouse cursor? <a href="http://laythemeforum.com:4567/topic/5190/svg-cursors-fix" target="_blank">Read this.</a></p>
		</div>
	</div>
	<form method="POST" action="options.php">
	<?php settings_fields( 'manage-laycarousel' );	//pass slug name of page, also referred
	                                        //to in Settings API as option group name
	do_settings_sections( 'manage-laycarousel' ); 	//pass slug name of page
	submit_button('Save Changes');
	?>
	</form>
</div>