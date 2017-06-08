<?php
add_action( 'admin_menu', 'SCM_add_admin_menu' );
add_action( 'admin_init', 'SCM_settings_init' );


function SCM_add_admin_menu(  ) { 

	add_options_page( 'Starward Cache Manager', 'Starward Cache Manager', 'manage_options', 'starward_cache', 'SCM_options_page' );

}


function SCM_settings_init(  ) { 

	register_setting( 'starward_cache_options', 'SCM_settings', 'sanitize_input' );

	add_settings_section(
		'SCM_pluginPage_section', 
		__( 'WebHook URL\'s', 'wordpress' ), 
		'SCM_settings_section_callback', 
		'webhooks'
	);

	add_settings_field( 
		'SCM_starward_api', 
		__( 'Starward API Url', 'wordpress' ), 
		'SCM_starward_api_render', 
		'webhooks', 
		'SCM_pluginPage_section' 
	);


}

function sanitize_input($input) {
	$api = $input['SCM_starward_api'];
	if(!is_null($api) && substr($api, -1) == '/') {
		$input['SCM_starward_api'] = substr($api, 0, -1);
	}
	return $input;
}


function SCM_starward_api_render(  ) { 

	$options = get_option( 'SCM_settings' );
	?>
	<input type='url' name='SCM_settings[SCM_starward_api]' value='<?php echo $options['SCM_starward_api']; ?>' style='width: 95%'>
	<?php

}


function SCM_settings_section_callback(  ) { 

	// echo __( 'This section description', 'wordpress' );

}


function SCM_options_page(  ) { 

	?>
	<form action='options.php' method='post'>

		<h2>Starward Cache Manager</h2>

		<?php
		settings_fields( 'starward_cache_options' );
		do_settings_sections( 'webhooks' );
		submit_button();
		?>

	</form>
	<?php

}

?>