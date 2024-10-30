<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://wpwheels.com/
 * @since      1.0.0
 *
 * @package    Blockwheels
 * @subpackage Blockwheels/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Blockwheels
 * @subpackage Blockwheels/includes
 * @author     wpwheels <info@wpwheels.com>
 */
class Blockwheels {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Blockwheels_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
        $this->version = BLOCKWHEELS_VERSION;
		$this->plugin_name = 'blockwheels';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

		if ( ! wp_is_block_theme() ) {
			// Load separate block styles.
			add_filter( 'should_load_separate_core_block_assets', '__return_true' );
			remove_action( 'wp_footer', 'wp_maybe_inline_styles', 1 );
		}
	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Blockwheels_Loader. Orchestrates the hooks of the plugin.
	 * - Blockwheels_i18n. Defines internationalization functionality.
	 * - Blockwheels_Admin. Defines all hooks for the admin area.
	 * - Blockwheels_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * Plugin Core Functions.
		 */
		require_once BLOCKWHEELS_PATH . 'includes/functions.php';

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once BLOCKWHEELS_PATH . 'includes/class-blockwheels-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once BLOCKWHEELS_PATH . 'includes/class-blockwheels-i18n.php';

		/**
         * The class responsible for defining all actions that occur in both admin and public area.
         */
        require_once BLOCKWHEELS_PATH . 'includes/class-blockwheels-assets.php';
		require_once BLOCKWHEELS_PATH . 'includes/class-blockwheels-webfont-loader.php';
		require_once BLOCKWHEELS_PATH . 'includes/class-blockwheels-google-fonts.php';
		require_once BLOCKWHEELS_PATH . 'includes/class-blockwheels-svg.php';
		require_once BLOCKWHEELS_PATH . 'includes/class-blockwheels-css.php';
		require_once BLOCKWHEELS_PATH . 'includes/class-blockwheels-starter-templates.php';

		// Accordion
		require_once BLOCKWHEELS_PATH . '/includes/blocks/class-blockwheels-accordion.php';
		require_once BLOCKWHEELS_PATH . '/includes/blocks/class-blockwheels-accordion-panel.php';
		// Container
        require_once BLOCKWHEELS_PATH . '/includes/blocks/class-blockwheels-container.php';
		// Counters
		require_once BLOCKWHEELS_PATH . '/includes/blocks/class-blockwheels-counters.php';
		require_once BLOCKWHEELS_PATH . '/includes/blocks/class-blockwheels-counter.php';
		// Google Map
		require_once BLOCKWHEELS_PATH . '/includes/blocks/class-blockwheels-google-map.php';
		// Grid Posts
		require_once BLOCKWHEELS_PATH . '/includes/blocks/class-blockwheels-grid-posts.php';
		// Heading
        require_once BLOCKWHEELS_PATH . '/includes/blocks/class-blockwheels-heading.php';
		// Icons
		require_once BLOCKWHEELS_PATH . '/includes/blocks/class-blockwheels-icons.php';
		require_once BLOCKWHEELS_PATH . '/includes/blocks/class-blockwheels-single-icon.php';
		// Logo Carousel
        require_once BLOCKWHEELS_PATH . '/includes/blocks/class-blockwheels-logo-carousel.php';
		// Paragraph
        require_once BLOCKWHEELS_PATH . '/includes/blocks/class-blockwheels-paragraph.php';
		// Services
		require_once BLOCKWHEELS_PATH . '/includes/blocks/class-blockwheels-services.php';
		require_once BLOCKWHEELS_PATH . '/includes/blocks/class-blockwheels-service.php';
		// Spacer/Divider
        require_once BLOCKWHEELS_PATH . '/includes/blocks/class-blockwheels-spacer.php';
		// Template Library
        require_once BLOCKWHEELS_PATH . '/includes/blocks/class-blockwheels-template-library.php';
		// Library Patterns
		require_once BLOCKWHEELS_PATH . 'includes/class-blockwheels-prebuilt-library.php';
		// Testimonials
		require_once BLOCKWHEELS_PATH . '/includes/blocks/class-blockwheels-testimonials.php';
		require_once BLOCKWHEELS_PATH . '/includes/blocks/class-blockwheels-testimonial.php';
		// Video Box
		require_once BLOCKWHEELS_PATH . '/includes/blocks/class-blockwheels-video-box.php';
		
		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once BLOCKWHEELS_PATH . 'admin/class-blockwheels-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once BLOCKWHEELS_PATH . 'public/class-blockwheels-public.php';

		$this->loader = new Blockwheels_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Blockwheels_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Blockwheels_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Blockwheels_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_menu', $plugin_admin, 'add_admin_menu' );
        $this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_resources' );

        /*Register Settings*/
        $this->loader->add_action( 'rest_api_init', $plugin_admin, 'register_settings' );
        $this->loader->add_action( 'admin_init', $plugin_admin, 'register_settings' );
		$this->loader->add_action( 'register_setting', $plugin_admin, 'setting_schema', 10, 3 );

		// Redirect Plugin Menu Page
		$this->loader->add_action( 'activated_plugin', $plugin_admin, 'redirect_plugin_page' );

		// Disable Gutenberg
		$this->loader->add_filter( 'use_block_editor_for_post_type', $plugin_admin, 'enable_gutenberg', 999, 2 );
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Blockwheels_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_public_resources' );

	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Blockwheels_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}