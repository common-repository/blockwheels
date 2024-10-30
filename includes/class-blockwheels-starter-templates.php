<?php
/**
 * Starter Templates
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if ( ! class_exists( 'Blockwheels_Starter_Templates' ) ) :
	/**
	 * Main class.
	 *
	 * @since 1.0.2
	 */
	class Blockwheels_Starter_Templates {

		/**
		 * Instance
		 *
		 * @access private
		 * @var null $instance
		 * @since 1.0.2
		 */
		private static $instance;

		/**
		 * Initiator
		 *
		 * @since 1.0.2
		 * @return object initialized object of class.
		 */
		public static function get_instance() {
			if ( ! isset( self::$instance ) ) {
				self::$instance = new self();
			}
			return self::$instance;
		}

		/**
		 * Hold the config settings
		 *
		 * @access private
		 * @var array $config_data
		 * @since 1.0.2
		 */
		private $config_data;

		/**
		 * Current theme
		 *
		 * @access private
		 * @var string $current_theme
		 * @since 1.0.2
		 */
		private $current_theme;

		/**
		 * Current template
		 *
		 * @access private
		 * @var string $current_template
		 * @since 1.0.2
		 */
		private $current_template;

		/**
		 * Theme url
		 *
		 * @access private
		 * @var string $theme_url
		 * @since 1.0.2
		 */
		private $theme_url;

		/**
		 * Github base url
		 *
		 * @access private
		 * @var string $base_url
		 * @since 1.0.2
		 */
		private $base_url;

		/**
		 * Constructor.
		 *
		 * @since 1.0.2
		 */
		public function __construct() {

			add_action( 'plugins_loaded', array( $this, 'import_init' ) );
		}

		/**
		 * Check for OCDI plugin and start setup.
		 *
		 * @since 1.0.2
		 */
		public function import_init() {

			if ( ! function_exists( 'is_plugin_active' ) ) {
				require_once ABSPATH . 'wp-admin/includes/plugin.php';
			}

			if ( ! class_exists( 'OCDI_Plugin' ) ) {
				if ( is_multisite() ) {
					add_action( 'network_admin_notices', array( $this, 'ocdi_notice' ) );
				} else {
					add_action( 'admin_notices', array( $this, 'ocdi_notice' ) );
				}
			} else {
				$this->setup_init();
			}

		}

		/**
		 * Show notice if OCDI is not installed.
		 *
		 * @since 1.0.2
		 */
		public function ocdi_notice() {
			?>
<div class="error">
    <p><?php esc_html_e( 'Please install & activate One click Demo Import plugin for the starter templates.', 'blockwheels' ); ?>
    </p>
</div>
<?php
		}

		/**
		 * Setup OCDI Filters
		 *
		 * @since 1.0.2
		 */
		public function setup_init() {

			// Only execute on the admin side.
			if ( is_admin() ) {

				// Get Current Theme.
				$current_theme = wp_get_theme();
				if ( $current_theme->exists() && $current_theme->parent() ) {
					$parent_theme = $current_theme->parent();
					if ( $parent_theme->exists() ) {
						$this->current_theme = $parent_theme->get_stylesheet();
					}
					// Set current theme template for child theme.
					$this->current_template = $current_theme->get_stylesheet();
				} elseif ( $current_theme->exists() ) {
					$this->current_theme = $current_theme->get_stylesheet();
					// Set current theme template.
					$this->current_template = $this->current_theme;
				}

				$this->theme_url = 'https://wpwheels.com/products/' . $this->current_theme;

				// Base url of the repository.
				$this->base_url = 'https://raw.githubusercontent.com/wpwheels/starter-templates/master/';

				// Get the json file to populate proper demo setup settings.
				$config_file = $this->base_url . $this->current_template . '/init.json';

				$data = wp_remote_get( $config_file );

				// Only execute if our config is loaded properly.
				if ( is_array( $data ) && ! is_wp_error( $data ) ) {

					$data              = wp_remote_retrieve_body( $data );
					$this->config_data = json_decode( $data, true );

					add_filter( 'ocdi/plugin_page_title', array( $this, 'disable_ocdi_title' ) );
					add_filter( 'ocdi/plugin_intro_text', array( $this, 'disable_ocdi_intro' ) );

					add_filter( 'ocdi/register_plugins', array( $this, 'ocdi_register_plugins' ) );
					add_filter( 'ocdi/plugin_page_setup', array( $this, 'ocdi_setup' ) );
					add_filter( 'ocdi/import_files', array( $this, 'manage_import' ) );
					add_action( 'ocdi/before_content_import', array( $this, 'before_content_import' ) );
					add_action( 'ocdi/before_widgets_import', array( $this, 'before_widgets_import' ) );
					add_action( 'ocdi/after_import', array( $this, 'after_import' ) );
				}
			}

		}

		/**
		 * Disable OCDI title
		 *
		 * @param string $plugin_title OCDI Title.
		 * @since 1.0.2
		 */
		public function disable_ocdi_title( $plugin_title ) {
			$plugin_title = '';
			return $plugin_title;
		}

		/**
		 * Disable OCDI Intro Text.
		 *
		 * @param string $plugin_intro_text OCDI Intro Text.
		 * @since 1.0.2
		 */
		public function disable_ocdi_intro( $plugin_intro_text ) {
			$plugin_intro_text = '';
			return $plugin_intro_text;
		}

		/**
		 * Register recommended plugins for OCDI.
		 *
		 * @param array $plugins Array of plugins.
		 * @since 1.0.2
		 */
		public function ocdi_register_plugins( $plugins ) {

			if ( isset( $this->config_data['plugins'] ) ) {

				$recommended_plugins = array();

				foreach ( $this->config_data['plugins'] as $plugin_data ) {
					$recommended_plugins[] = array(
						'name'        => $plugin_data['name'],
						'slug'        => $plugin_data['slug'],
						'required'    => $plugin_data['required'],
						'preselected' => $plugin_data['preselected'],
					);
				}

				return array_merge( $plugins, $recommended_plugins );

			} else {
				return $plugins;
			}
		}

		/**
		 * Change OCDI default texts
		 *
		 * @param array $default_settings Default text array.
		 * @since 1.0.2
		 */
		public function ocdi_setup( $default_settings ) {
			$default_settings['parent_slug'] 	= ( function_exists( 'blockwheels' ) ) ? 'blockwheels' : 'themes.php';
			$default_settings['menu_slug']  	= ( function_exists( 'blockwheels' ) ) ? 'blockwheels-demo-import' : $this->current_theme . '-demo-import';
			$default_settings['menu_title']  	= esc_html__( 'Demo Importer' , 'blockwheels' );
			return $default_settings;
		}

		/**
		 * Init array for the OCDI demos
		 *
		 * @since 1.0.2
		 */
		public function manage_import() {

			$output = [];

			if ( $this->config_data && isset( $this->config_data['import_files'] ) ) {

				// free 
				if ( isset( $this->config_data['import_files']['free'] ) ) {
					$free_demos = $this->config_data['import_files']['free'];
					foreach ( $free_demos as $demo_data ) {
						$file_url = $this->base_url . $this->current_template . '/' . $demo_data['import_path'] . '/';
						$demos = [
							'import_file_name'           => $demo_data['import_name'],
							'import_file_url'            => $file_url . 'content.xml',
							'import_widget_file_url'     => $file_url . 'widgets.wie',
							'import_customizer_file_url' => $file_url . 'customizer.dat',
							'import_preview_image_url'   => $file_url . 'screenshot.png',
							'preview_url'                => $demo_data['preview_url'],
							'import_notice'              => esc_html( 'Make sure to leave the preselected plugins as it is to make the starter sites working as in our preview sites. Other plugins are optional and you can install them only if you need them.', 'unfoldwp-import-companion' ),
						];
						if ( isset( $demo_data['categories'] ) ) {
							$demos['categories'] = $demo_data['categories'];
						}
		
						$output[] = $demos;
					}
				}

				// pro 
				if ( isset( $this->config_data['import_files']['pro'] ) ) {
					$pro_demos = $this->config_data['import_files']['pro'];
					foreach ( $pro_demos as $demo_data ) {
						$file_url = $this->base_url . $this->current_template . '/' . $demo_data['import_path'] . '/';
						$demos = [
							'import_file_name'           => $demo_data['import_name'],
							'import_file_url'            => $file_url . 'content.xml',
							'import_widget_file_url'     => $file_url . 'widgets.wie',
							'import_customizer_file_url' => $file_url . 'customizer.dat',
							'import_preview_image_url'   => $file_url . 'screenshot.png',
							'preview_url'                => $demo_data['preview_url'],
							'import_notice'              => esc_html( 'Make sure to leave the preselected plugins as it is to make the starter sites working as in our preview sites. Other plugins are optional and you can install them only if you need them.', 'unfoldwp-import-companion' ),
						];
						if ( isset( $demo_data['categories'] ) ) {
							$demos['categories'] = $demo_data['categories'];
						}
		
						$output[] = $demos;
					}
				}
			}
				
			return $output;
		}

		/**
		 * Before Content import.
		 *
		 * @since 1.2.6
		 */
		function before_content_import() {
			// Trash default "hello word" post.
			$post = get_post( 1 );
			$slug = isset( $post->post_name ) ? $post->post_name : '';
			if ( 'hello-world' == $slug ) {
				wp_trash_post( 1 );
			}
		}

		/**
		 * Before widgets import.
		 *
		 * @since 1.2.6
		 */
		function before_widgets_import() {
			// Empty default sidebar widgetarea.
			$registered_sidebars = get_option( 'sidebars_widgets' );
			if ( isset( $registered_sidebars['sidebar-1'] ) && ! empty( $registered_sidebars['sidebar-1'] ) ) {
				update_option( 'sidebars_widgets', array( 'sidebar-1' => array() ) );
			}
		}

		/**
		 * Setup after finishing demo import
		 *
		 * @since 1.0.2
		 */
		public function after_import() {

			// Assign front page and posts page (blog page) if any.
			$front_page_id = null;
			$blog_page_id  = null;

			$front_page = new WP_Query(
				array(
					'post_type'              => 'page',
					'title'                  => 'Home',
					'post_status'            => 'all',
					'posts_per_page'         => 1,
					'no_found_rows'          => true,
					'ignore_sticky_posts'    => true,
					'update_post_term_cache' => false,
					'update_post_meta_cache' => false,
					'orderby'                => 'post_date ID',
					'order'                  => 'ASC',
				)
			);

			if ( ! empty( $front_page->post ) ) {
				$front_page_id = $front_page->post->ID;
			}

			if ( $front_page_id  ) {
				update_option( 'show_on_front', 'page' );
				update_option( 'page_on_front', $front_page_id );
			}

			
			// Assign logo.
			$favicon_id = get_option( 'site_icon' );
			if ( isset( $favicon_id ) || absint( $favicon_id ) ) {
				$media_object = get_post( $favicon_id );
				set_theme_mod( 'custom_logo', absint( $media_object->ID ) );
			}

			// Define the name of the menu to set
			$menu_name = 'Primary Menu'; // Change this to match your menu name

			// Retrieve the menu by name
			$menu = get_posts( array(
				'post_type'   => 'wp_navigation',
				'post_title'  => $menu_name,
				'numberposts' => 1
			) );

			if ( $menu ) {
				$menu_id = $menu[0]->ID;

				// Assign the menu to the 'primary' location
				$locations = get_theme_mod( 'nav_menu_locations' ); // Get current menu locations
				$locations['primary'] = $menu_id; // Set 'primary' to the new menu ID
				set_theme_mod( 'nav_menu_locations', $locations );
			}
			
		}
	}

endif;

Blockwheels_Starter_Templates::get_instance();