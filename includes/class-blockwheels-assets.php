<?php
/**
 * Load assets for our blocks.
 *
 * @link       https://wpwheels.com/
 * @since      1.0.0
 *
 * @package    Blockwheels
 * @subpackage Blockwheels/includes
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Blockwheels_Assets' ) ) {
	/**
	 * Load general assets for our blocks.
	 *
	 * @since 1.0.0
	 */
	class Blockwheels_Assets {
		/**
		 * This plugin's instance.
		 *
		 * @var Blockwheels_Assets
		 */
		private static $instance;

		/**
		 * Registers the plugin.
		 *
		 * @return Blockwheels_Assets
		 */
		public static function register() {
			if ( null === self::$instance ) {
				self::$instance = new Blockwheels_Assets();
			}

			return self::$instance;
		}

		/**
		 * The Constructor.
		 */
		public function __construct() {

			add_action( 'block_categories_all', array( $this, 'categories' ) );
			add_action( 'init', array( $this, 'block_assets' ) );
			add_action( 'enqueue_block_editor_assets', array( $this, 'editor_assets' ) );
		}

		/**
		 * Callback functions for block_categories_all,
		 * Adding Block Categories
		 *
		 * @since    1.0.0
		 * @access   public
		 *
		 * @param array $categories
		 * @return array merge $categories
		 */
		public function categories( $categories ) {

			return array_merge(
				array(
					array(
						'slug'  => 'blockwheels',
						'title' => esc_html__( 'Blockwheels', 'blockwheels' ),
					),
				),
				$categories
			);
		}

		/**
		 * Enqueue block assets for editor and front-end.
		 *
		 * @access public
		 */
		public function block_assets() {

			$public_meta = blockwheels_get_asset_file( 'build/public/index' );

			// Register Scripts
			wp_register_script( 
				'blockwheels-countUp', 
				BLOCKWHEELS_URL . 'assets/library/countUp/countUp.js', 
				[],
				'2.6.0',
				true
			);
			wp_register_script( 
				'blockwheels-splide', 
				BLOCKWHEELS_URL . 'assets/library/splide/splide.js', 
				[],
				'4.1.2',
				true
			);
			wp_register_script( 
				'blockwheels-swiper', 
				BLOCKWHEELS_URL . 'assets/library/swiper/swiper-bundle.js', 
				[],
				'8.1.4',
				true
			);
			wp_enqueue_script( 
				'blockwheels-public-script', 
				BLOCKWHEELS_URL . 'build/public/index.js',
				array_merge( $public_meta['dependencies'], array( 'wp-api', 'blockwheels-countUp', 'blockwheels-splide' ) ),
				$public_meta['version'],
				true
			);

			// Register Styles
			wp_register_style( 
				'blockwheels-splide', 
				BLOCKWHEELS_URL . 'assets/library/splide/splide.css', 
				[], 
				'4.1.2',
				'all'
			);
			wp_register_style( 
				'blockwheels-swiper', 
				BLOCKWHEELS_URL . 'assets/library/swiper/swiper-bundle.css', 
				[], 
				'8.1.4',
				'all'
			);
			wp_register_style(
				'blockwheels-public-style',
				BLOCKWHEELS_URL . 'build/public/index.css',
				[],
				$public_meta['version'],
				'all'
			);
		}

		/**
		 * Enqueue for the editing interface.
		 *
		 * @access public
		 */
		public function editor_assets() {

			$icon_names_path  = BLOCKWHEELS_PATH . 'includes/icon-names-array.php';
			$icon_ico_path    = BLOCKWHEELS_PATH . 'includes/icons-ico-array.php';
			$icons_path       = BLOCKWHEELS_PATH . 'includes/icons-array.php';

			// Theme details.
			$active_theme = wp_get_theme();

			$block_editor_meta = blockwheels_get_asset_file( 'build/editor/index' );
			wp_enqueue_script( 
				'blockwheels-editor-script', 
				BLOCKWHEELS_URL . 'build/editor/index.js',
				array_merge( $block_editor_meta['dependencies'], array( 'wp-api' ) ),
				$block_editor_meta['version'],
				true
			);

			wp_localize_script(
				'blockwheels-editor-script',
				'blockwheels_params',
				array(
					'ajax_url'			=> admin_url( 'admin-ajax.php' ),
					'ajax_nonce'		=> wp_create_nonce( 'blockwheels-ajax-verification' ),
					'rest_url' 			=> get_rest_url(),
					'allFonts'  		=> Blockwheels_Google_Fonts::get_fonts(),
					'showDesignLibrary' => apply_filters( 'blockwheels_design_library_enabled', true ),
					'pro'				=> ( blockwheels_fs()->can_use_premium_code__premium_only() ) ? 'true' : 'false',
					'post_categories'	=> blockwheels_get_terms(array('taxonomy' => 'category')),
					'post_tags'			=> blockwheels_get_terms(array('taxonomy' => 'post_tag')),
					'settings'			=> blockwheels_get_options(),
					'adminUrl' 			=> get_admin_url(),
					'baseUrl'			=> BLOCKWHEELS_URL,
					'wpWheels'			=> $active_theme->get('TextDomain'),
					'placeholder'		=> BLOCKWHEELS_URL . 'assets/images/placeholder.png',
					'icon_names' 		=> file_exists( $icon_names_path ) ? include $icon_names_path : array()
				)
			);
			wp_localize_script(
				'blockwheels-editor-script',
				'blockwheels_params_ico',
				array(
					'icons' => file_exists( $icon_ico_path ) ? include $icon_ico_path : array(),
				)
			);
			wp_localize_script(
				'blockwheels-editor-script',
				'blockwheels_params_fa',
				array(
					'icons' => file_exists( $icons_path ) ? include $icons_path : array(),
				)
			);

			wp_enqueue_style(
				'blockwheels-editor-style',
				BLOCKWHEELS_URL . 'build/editor/index.css',
				['wp-edit-blocks'],
				$block_editor_meta['version'],
				'all'
			);

			// Enable automatic RTL support by looking for index-rtl.css.
			wp_style_add_data( 'blockwheels-editor-style', 'rtl', 'replace' );
		}
	}
}
Blockwheels_Assets::register();