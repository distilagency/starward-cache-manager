<?php
function clear_redis_cache() {
    throw new Exception('test');
}

// add a link to the WP Toolbar
function clear_redis_cache_toolbar($wp_admin_bar) {
    $href = '';
    $settings = get_option( 'SCM_settings' );
    if(is_null($settings)) {
        $href = admin_url( 'options-general.php?page=starward_cache' );
    }

    
    $starward_api = $settings['SCM_starward_api'];
    if (is_null($starward_api) || empty($starward_api)) {
        $href = admin_url( 'options-general.php?page=starward_cache' );
    }
	$args = array(
		'id' => 'clear-redis-cache',
		'title' => '<span>Clear Starward Cache</span><span class="dashicons-before dashicons-yes go-invisible"></span>', 
        'href' => $href,
		'meta' => array(
			'class' => 'clear-redis-cache', 
			'title' => 'Clear Starward Cache',
            'html' => '<a href="http://localhost:3000/api/flushrediss" class="clear-redis-url" style="display:none"></a>'
			)
	);
	$wp_admin_bar->add_node($args);
}
add_action('admin_bar_menu', 'clear_redis_cache_toolbar', 999);


add_action( 'wp_ajax_clear_starward_cache', 'clear_starward_cache' );

function clear_starward_cache() {
    $response = StarwardCacheManager::flush_redis();
    if($response['status'] == 'error') {
        echo $response['message'] ;
    } else {
        // echo 'Cache Cleared';   
        echo '';
    }
	wp_die(); // this is required to terminate immediately and return a proper response
}
?>