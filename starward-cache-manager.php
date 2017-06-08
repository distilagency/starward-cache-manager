<?php
/**
 * Plugin Name: Starward Cache Manager
 * Plugin URI:  https://github.com/distilagency/starward_wp
 * Description: A plugin to manage your Starward Redis cache via webhooks
 * Version:     0.0.0
 * Author:      BirdBrain
 * Author URI:  https://www.birdbrain.com.au/
 * Donate link: https://github.com/distilagency/starward_wp
 * License:     MIT
 * Text Domain: starward-cache-manager
 * Domain Path: /languages
 *
 * @link    https://github.com/distilagency/starward_wp
 *
 * @package StarwardCacheManager
 * @version 0.0.0
 *
 * Built using generator-plugin-wp (https://github.com/WebDevStudios/generator-plugin-wp)
 */

/**
 * Copyright (c) 2017 BirdBrain (email : michael@birdbrain.com.au)
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License, version 2 or, at
 * your discretion, any later version, as published by the Free
 * Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */


/**
 * Autoloads files with classes when needed.
 *
 * @since  0.0.0
 * @param  string $class_name Name of the class being requested.
 */
function starward_cache_manager_autoload_classes( $class_name ) {

	// If our class doesn't have our prefix, don't load it.
	if ( 0 !== strpos( $class_name, 'SCM_' ) ) {
		return;
	}

	// Set up our filename.
	$filename = strtolower( str_replace( '_', '-', substr( $class_name, strlen( 'SCM_' ) ) ) );

	// Include our file.
	StarwardCacheManager::include_file( 'includes/class-' . $filename );
}
spl_autoload_register( 'starward_cache_manager_autoload_classes' );

/**
 * Main initiation class.
 *
 * @since  0.0.0
 */
final class StarwardCacheManager {

	/**
	 * Current version.
	 *
	 * @var    string
	 * @since  0.0.0
	 */
	const VERSION = '0.0.0';

	/**
	 * URL of plugin directory.
	 *
	 * @var    string
	 * @since  0.0.0
	 */
	protected $url = '';

	/**
	 * Path of plugin directory.
	 *
	 * @var    string
	 * @since  0.0.0
	 */
	protected $path = '';

	/**
	 * Plugin basename.
	 *
	 * @var    string
	 * @since  0.0.0
	 */
	protected $basename = '';

	/**
	 * Detailed activation error messages.
	 *
	 * @var    array
	 * @since  0.0.0
	 */
	protected $activation_errors = array();

	/**
	 * Singleton instance of plugin.
	 *
	 * @var    StarwardCacheManager
	 * @since  0.0.0
	 */
	protected static $single_instance = null;

	/**
	 * Creates or returns an instance of this class.
	 *
	 * @since   0.0.0
	 * @return  StarwardCacheManager A single instance of this class.
	 */
	public static function get_instance() {
		if ( null === self::$single_instance ) {
			self::$single_instance = new self();
		}

		return self::$single_instance;
	}

	/**
	 * Sets up our plugin.
	 *
	 * @since  0.0.0
	 */
	protected function __construct() {
		$this->basename = plugin_basename( __FILE__ );
		$this->url      = plugin_dir_url( __FILE__ );
		$this->path     = plugin_dir_path( __FILE__ );
	}

	/**
	 * Attach other plugin classes to the base plugin class.
	 *
	 * @since  0.0.0
	 */
	public function plugin_classes() {

	} // END OF PLUGIN CLASSES FUNCTION

	/**
	 * Add hooks and filters.
	 * Priority needs to be
	 * < 10 for CPT_Core,
	 * < 5 for Taxonomy_Core,
	 * and 0 for Widgets because widgets_init runs at init priority 1.
	 *
	 * @since  0.0.0
	 */
	public function hooks() {
		add_action( 'init', array( $this, 'init' ), 0 );
	}

	/**
	 * Activate the plugin.
	 *
	 * @since  0.0.0
	 */
	public function _activate() {
		// Bail early if requirements aren't met.
		if ( ! $this->check_requirements() ) {
			return;
		}

		// Make sure any rewrite functionality has been loaded.
		flush_rewrite_rules();
	}

	/**
	 * Deactivate the plugin.
	 * Uninstall routines should be in uninstall.php.
	 *
	 * @since  0.0.0
	 */
	public function _deactivate() {
		// Add deactivation cleanup functionality here.
	}

	/**
	 * Init hooks
	 *
	 * @since  0.0.0
	 */
	public function init() {

		// Bail early if requirements aren't met.
		if ( ! $this->check_requirements() ) {
			return;
		}

		// Load translated strings for plugin.
		load_plugin_textdomain( 'starward-cache-manager', false, dirname( $this->basename ) . '/languages/' );

		// Initialize plugin classes.
		$this->plugin_classes();
	}

	/**
	 * Check if the plugin meets requirements and
	 * disable it if they are not present.
	 *
	 * @since  0.0.0
	 *
	 * @return boolean True if requirements met, false if not.
	 */
	public function check_requirements() {

		// Bail early if plugin meets requirements.
		if ( $this->meets_requirements() ) {
			return true;
		}

		// Add a dashboard notice.
		add_action( 'all_admin_notices', array( $this, 'requirements_not_met_notice' ) );

		// Deactivate our plugin.
		add_action( 'admin_init', array( $this, 'deactivate_me' ) );

		// Didn't meet the requirements.
		return false;
	}

	/**
	 * Deactivates this plugin, hook this function on admin_init.
	 *
	 * @since  0.0.0
	 */
	public function deactivate_me() {

		// We do a check for deactivate_plugins before calling it, to protect
		// any developers from accidentally calling it too early and breaking things.
		if ( function_exists( 'deactivate_plugins' ) ) {
			deactivate_plugins( $this->basename );
		}
	}

	/**
	 * Check that all plugin requirements are met.
	 *
	 * @since  0.0.0
	 *
	 * @return boolean True if requirements are met.
	 */
	public function meets_requirements() {

		// Do checks for required classes / functions or similar.
		// Add detailed messages to $this->activation_errors array.
		return true;
	}

	/**
	 * Adds a notice to the dashboard if the plugin requirements are not met.
	 *
	 * @since  0.0.0
	 */
	public function requirements_not_met_notice() {

		// Compile default message.
		$default_message = sprintf( __( 'starward-cache-manager is missing requirements and has been <a href="%s">deactivated</a>. Please make sure all requirements are available.', 'starward-cache-manager' ), admin_url( 'plugins.php' ) );

		// Default details to null.
		$details = null;

		// Add details if any exist.
		if ( $this->activation_errors && is_array( $this->activation_errors ) ) {
			$details = '<small>' . implode( '</small><br /><small>', $this->activation_errors ) . '</small>';
		}

		// Output errors.
		?>
		<div id="message" class="error">
			<p><?php echo wp_kses_post( $default_message ); ?></p>
			<?php echo wp_kses_post( $details ); ?>
		</div>
		<?php
	}

	/**
	 * Magic getter for our object.
	 *
	 * @since  0.0.0
	 *
	 * @param  string $field Field to get.
	 * @throws Exception     Throws an exception if the field is invalid.
	 * @return mixed         Value of the field.
	 */
	public function __get( $field ) {
		switch ( $field ) {
			case 'version':
				return self::VERSION;
			case 'basename':
			case 'url':
			case 'path':
			case 'starward_cache':
				return $this->$field;
			default:
				throw new Exception( 'Invalid ' . __CLASS__ . ' property: ' . $field );
		}
	}

	/**
	 * Include a file from the includes directory.
	 *
	 * @since  0.0.0
	 *
	 * @param  string $filename Name of the file to be included.
	 * @return boolean          Result of include call.
	 */
	public static function include_file( $filename ) {
		$file = self::dir( $filename . '.php' );
		if ( file_exists( $file ) ) {
			return include_once( $file );
		}
		return false;
	}

	/**
	 * This plugin's directory.
	 *
	 * @since  0.0.0
	 *
	 * @param  string $path (optional) appended path.
	 * @return string       Directory and path.
	 */
	public static function dir( $path = '' ) {
		static $dir;
		$dir = $dir ? $dir : trailingslashit( dirname( __FILE__ ) );
		return $dir . $path;
	}

	/**
	 * This plugin's url.
	 *
	 * @since  0.0.0
	 *
	 * @param  string $path (optional) appended path.
	 * @return string       URL and path.
	 */
	public static function url( $path = '' ) {
		static $url;
		$url = $url ? $url : trailingslashit( plugin_dir_url( __FILE__ ) );
		return $url . $path;
	}

	public static function flush_redis() {
		$settings = get_option( 'SCM_settings' );
		if(is_null($settings)) {
			return array(
							'status' => 'error',
							'message' => 'Please setup Starward Redis Cache Manager first!!');
		}

		
		$starward_api = $settings['SCM_starward_api'];
		if (is_null($starward_api)) {
			return array(
							'status' => 'error',
							'message' => 'Please setup Starward Redis Cache Manager first!!');
		}

		$clear_redis_url = $starward_api . '/flushredis';
		$response = wp_remote_get($clear_redis_url);

		if(is_wp_error($response)) {
			return array(
							'status' => 'error',
							'message' => 'Issue with calling the flush endpoint: ' . $clear_redis_url . 'see error: ' . $response);
		}

		try {
			$responseJson = json_decode($response['body']);
		} catch (Exception $e) {
			return array(
						'status' => 'error',
						'message' => 'Got a weird response from the API!: ' . $clear_redis_url);
		}

		if (is_null($responseJson) || is_null($responseJson->success)) {
			return array(
						'status' => 'error',
						'message' => 'Got a weird response from the API!: ' . $clear_redis_url);
		}

		if(!$responseJson->success) {
			return array(
						'status' => 'error',
						'message' => 'success was false!: ' . $clear_redis_url);
		}

		return array(
						'status' => 'success');
	}
}

/**
 * Grab the StarwardCacheManager object and return it.
 * Wrapper for StarwardCacheManager::get_instance().
 *
 * @since  0.0.0
 * @return StarwardCacheManager  Singleton instance of plugin class.
 */
function starward_cache_manager() {
	return StarwardCacheManager::get_instance();
}

function load_scripts() {
	wp_enqueue_style('clearCacheStyles', plugin_dir_url(__FILE__) . 'css/styles.css');
	wp_enqueue_script('clearCache', plugin_dir_url(__FILE__) . 'js/clearCache.js', array('jquery'));
}

// Kick it off.
add_action( 'plugins_loaded', array( starward_cache_manager(), 'hooks' ) );
add_action( 'admin_enqueue_scripts', 'load_scripts' );

// Activation and deactivation.
register_activation_hook( __FILE__, array( starward_cache_manager(), '_activate' ) );
register_deactivation_hook( __FILE__, array( starward_cache_manager(), '_deactivate' ) );

if(!StarwardCacheManager::include_file( 'includes/starward-cache-options')) {
	throw new Exception('includes/starward-cache-options NOT included');
} 

if(!StarwardCacheManager::include_file( 'includes/starward-clear-cache-shortcut')) {
	throw new Exception('includes/starward-clear-cache-shortcut NOT included');
} 


// Flush Redis
add_action( 'save_post', 'flush_redis' );
function flush_redis()
{
  StarwardCacheManager::flush_redis();
}