<?php
/**
 * starward-cache-manager Starward Cache.
 *
 * @since   0.0.0
 * @package Starward_cache-manager
 */



/**
 * starward-cache-manager Starward Cache class.
 *
 * @since 0.0.0
 */
class SCM_Starward_Cache {
	/**
	 * Parent plugin class.
	 *
	 * @var    Starward_cache-manager
	 * @since  0.0.0
	 */
	protected $plugin = null;

	/**
	 * Option key, and option page slug.
	 *
	 * @var    string
	 * @since  0.0.0
	 */
	protected $key = 'starward_cache_manager_starward_cache';

	/**
	 * Options page metabox ID.
	 *
	 * @var    string
	 * @since  0.0.0
	 */
	protected $metabox_id = 'starward_cache_manager_starward_cache_metabox';

	/**
	 * Options Page title.
	 *
	 * @var    string
	 * @since  0.0.0
	 */
	protected $title = '';

	/**
	 * Options Page hook.
	 *
	 * @var string
	 */
	protected $options_page = '';

	/**
	 * Constructor.
	 *
	 * @since  0.0.0
	 *
	 * @param  Starward_cache-manager $plugin Main plugin object.
	 */
	public function __construct( $plugin ) {
		$this->plugin = $plugin;
		$this->hooks();

		// Set our title.
		$this->title = esc_attr__( 'Starward Cache', 'Starward-cache-manager' );
	}

	/**
	 * Initiate our hooks.
	 *
	 * @since  0.0.0
	 */
	public function hooks() {

		// Hook in our actions to the admin.
		add_action( 'admin_init', array( $this, 'admin_init' ) );
		add_action( 'admin_menu', array( $this, 'add_options_page' ) );
		
	}

	/**
	 * Register our setting to WP.
	 *
	 * @since  0.0.0
	 */
	public function admin_init() {
		register_setting( $this->key, $this->key );
	}

	/**
	 * Add menu options page.
	 *
	 * @since  0.0.0
	 */
	public function add_options_page() {
		$this->options_page = add_menu_page(
			$this->title,
			$this->title,
			'manage_options',
			$this->key,
			array( $this, 'admin_page_display' )
		);

		// Include CMB CSS in the head to avoid FOUC.
		add_action( "admin_print_styles-{$this->options_page}", array( 'CMB2_hookup', 'enqueue_cmb_css' ) );
	}

	/**
	 * Admin page markup. Mostly handled by CMB2.
	 *
	 * @since  0.0.0
	 */
	public function admin_page_display() {
		?>
		<div class="wrap cmb2-options-page <?php echo esc_attr( $this->key ); ?>">
			<h2><?php echo esc_html( get_admin_page_title() ); ?></h2>
		</div>
		<?php
	}
}
