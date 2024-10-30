<?php
/**
 * Class for pulling in library database and saving locally
 * Based on a package from the WPTT Team for local fonts.
 *
 * @link       https://wpwheels.com/
 * @since      1.0.0
 *
 * @package    Blockwheels
 * @subpackage Blockwheels/includes
 * 
 * @link    https://github.com/stellarwp/kadence-blocks/blob/master/includes/class-kadence-blocks-prebuilt-library.php
 * @license https://www.gnu.org/licenses/gpl-2.0.html
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Blockwheels_Prebuilt_Library' ) ) {
	/**
	 * Class for pulling in template database and saving locally
	 */
	class Blockwheels_Prebuilt_Library {

		/**
		 * Instance of this class
		 *
		 * @var null
		 */
		private static $instance = null;

		/**
		 * API email for blockwheels
		 *
		 * @var string
		 */
		private $package = 'section';

		/**
		 * Is a template for Blockwheels. 
		 *
		 * @var bool
		 */
		private $is_template = false;

		/**
		 * API email for blockwheels
		 *
		 * @var string
		 */
		private $url = '';

		/**
		 * Base URL.
		 *
		 * @access protected
		 * @var string
		 */
		protected $base_url;
		/**
		 * Base path.
		 *
		 * @access protected
		 * @var string
		 */
		protected $base_path;
		/**
		 * Subfolder name.
		 *
		 * @access protected
		 * @var string
		 */
		protected $subfolder_name;

		/**
		 * The starter templates folder.
		 *
		 * @access protected
		 * @var string
		 */
		protected $block_library_folder;
		/**
		 * The local stylesheet's path.
		 *
		 * @access protected
		 * @var string
		 */
		protected $local_template_data_path;
		/**
		 * The local stylesheet's path.
		 *
		 * @access protected
		 * @var string
		 */
		protected $local_pages_data_path;

		/**
		 * The local stylesheet's URL.
		 *
		 * @access protected
		 * @var string
		 */
		protected $local_template_data_url;
		/**
		 * The local stylesheet's URL.
		 *
		 * @access protected
		 * @var string
		 */
		protected $local_pages_data_url;
		/**
		 * The remote URL.
		 *
		 * @access protected
		 * @var string
		 */
		protected $remote_url = 'https://demo.wpwheels.com/blockwheels/template/wp-json/wpwheels/v1/patterns';

		/**
		 * The remote URL.
		 *
		 * @access protected
		 * @var string
		 */
		protected $remote_pages_url = 'https://demo.wpwheels.com/blockwheels/template/wp-json/wpwheels/v1/templates';

		/**
		 * The final data.
		 *
		 * @access protected
		 * @var string
		 */
		protected $data;
		/**
		 * Cleanup routine frequency.
		 */
		const CLEANUP_FREQUENCY = 'monthly';

		/**
		 * Instance Control
		 */
		public static function get_instance() {
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}
			return self::$instance;
		}

		/**
		 * Constructor.
		 */
		public function __construct() {
			if ( is_admin() ) {
				// Ajax Calls.
				add_action( 'wp_ajax_blockwheels_import_process_data', array( $this, 'process_data_ajax_callback' ) );
				add_action( 'wp_ajax_blockwheels_import_reload_prebuilt_data', array( $this, 'prebuilt_data_reload_ajax_callback' ) );
				add_action( 'wp_ajax_blockwheels_import_reload_prebuilt_pages_data', array( $this, 'prebuilt_pages_data_reload_ajax_callback' ) );
				add_action( 'wp_ajax_blockwheels_import_get_prebuilt_data', array( $this, 'prebuilt_data_ajax_callback' ) );
				add_action( 'wp_ajax_blockwheels_import_get_prebuilt_pages_data', array( $this, 'prebuilt_pages_data_ajax_callback' ) );
			}

			// Add a cleanup routine.
			$this->schedule_cleanup();
			add_filter( 'cron_schedules', array( $this, 'add_monthly_to_cron_schedule' ), 10, 1 );
			add_action( 'delete_block_library_folder', array( $this, 'delete_block_library_folder' ) );
		}

		/**
		 * Get the local data file if there, else query the api.
		 *
		 * @access public
		 * @return string
		 */
		public function get_template_data( $skip_local = false ) {
			if ( 'custom' === $this->package ) {
				return wp_json_encode( apply_filters( 'blockwheels_library_custom_array', array() ) );
			}
			// Check if the local data file exists. (true means the file doesn't exist).
			if ( $skip_local || $this->local_file_exists() ) {
				// Attempt to create the file.
				if ( $this->create_template_data_file( $skip_local ) ) {
					return $this->get_local_template_data_contents();
				}
			}

			// If the local file exists, return it's data.
			return file_exists( $this->get_local_template_data_path() )
				? $this->get_local_template_data_contents()
				: '';
		}
		/**
		 * Write the data to the filesystem.
		 *
		 * @access protected
		 * @return string|false Returns the absolute path of the file on success, or false on fail.
		 */
		protected function create_template_data_file( $skip_local ) {
			$file_path  = $this->get_local_template_data_path();
			$filesystem = $this->get_filesystem();

			// If the folder doesn't exist, create it.
			if ( ! file_exists( $this->get_block_library_folder() ) ) {
				$chmod_dir = ( 0755 & ~ umask() );
				if ( defined( 'BLOCKWHEELS_CHMOD_DIR' ) ) {
					$chmod_dir = BLOCKWHEELS_CHMOD_DIR;
				}
				$this->get_filesystem()->mkdir( $this->get_block_library_folder(), $chmod_dir );
			}

			// If the file doesn't exist, create it. Return false if it can not be created.
			if ( ! $filesystem->exists( $file_path ) && ! $filesystem->touch( $file_path ) ) {
				return false;
			}

			// If we got this far, we need to write the file.
			// Get the data.
			if ( $skip_local || ! $this->data ) {
				$this->get_data();
			}
			// Put the contents in the file. Return false if that fails.
			if ( ! $filesystem->put_contents( $file_path, $this->data ) ) {
				return false;
			}

			return $file_path;
		}
		/**
		 * Get data.
		 *
		 * @access public
		 * @return string
		 */
		public function get_data() {
			// Get the remote URL contents.
			$this->data = $this->get_remote_url_contents();

			return $this->data;
		}
		/**
		 * Get local data contents.
		 *
		 * @access public
		 * @return string|false Returns the data contents.
		 */
		public function get_local_template_data_contents() {
			$local_path = $this->get_local_template_data_path();

			// Check if the local exists. (true means the file doesn't exist).
			if ( $this->local_file_exists() ) {
				return false;
			}

			$data = file_get_contents( $local_path );
			return $data;
		}
		/**
		 * Get remote file contents.
		 *
		 * @access public
		 * @return string Returns the remote URL contents.
		 */
		public function get_remote_url_contents() {
			if ( is_callable( 'network_home_url' ) ) {
				$site_url = network_home_url( '', 'http' );
			} else {
				$site_url = get_bloginfo( 'url' );
			}
			$site_url = preg_replace( '/^https/', 'http', $site_url );
			$site_url = preg_replace( '|/$|', '', $site_url );
			$args = array(
				'key'  => $this->key,
				'site' => $site_url,
			);
			if ( 'templates' === $this->package ) {
				$args['request'] = 'blocks';
			}
			// Get the response.
			$api_url  = add_query_arg( $args, $this->url );

			$response = wp_remote_get(
				$api_url,
				array(
					'timeout' => 20,
				)
			);
			// Early exit if there was an error.
			if ( is_wp_error( $response ) ) {
				return '';
			}

			// Get the CSS from our response.
			$contents = wp_remote_retrieve_body( $response );

			// Early exit if there was an error.
			if ( is_wp_error( $contents ) ) {
				return;
			}

			return $contents;
		}
		/**
		 * Check if the local file exists.
		 *
		 * @access public
		 * @return bool
		 */
		public function local_file_exists() {
			return ( ! file_exists( $this->get_local_template_data_path() ) );
		}
		/**
		 * Get the data path.
		 *
		 * @access public
		 * @return string
		 */
		public function get_local_template_data_path() {
			if ( ! $this->local_template_data_path ) {
				$this->local_template_data_path = $this->get_block_library_folder() . '/' . $this->get_local_template_data_filename() . '.json';
			}
			return $this->local_template_data_path;
		}
		/**
		 * Get the local data filename.
		 *
		 * This is a hash, generated from the site-URL, the wp-content path and the URL.
		 * This way we can avoid issues with sites changing their URL, or the wp-content path etc.
		 *
		 * @access public
		 * @return string
		 */
		public function get_local_template_data_filename() {
			$kb_api = 'free';
			return md5( $this->get_base_url() . $this->get_base_path() . $this->package . BLOCKWHEELS_VERSION . $kb_api );
		}
		
		/**
		 * Get the local data file if there, else query the api.
		 *
		 * @access public
		 * @return string
		 */
		public function get_connection_data( $skip_local = false ) {
			if ( is_callable( 'network_home_url' ) ) {
				$site_url = network_home_url( '', 'http' );
			} else {
				$site_url = get_bloginfo( 'url' );
			}
			$site_url = preg_replace( '/^https/', 'http', $site_url );
			$site_url = preg_replace( '|/$|', '', $site_url );
			$args = array(
				'key'  => $this->key,
				'site' => $site_url,
			);
			// Get the response.
			$api_url  = add_query_arg( $args, $this->url );
			$response = wp_remote_get(
				$api_url,
				array(
					'timeout' => 20,
				)
			);
			// Early exit if there was an error.
			if ( is_wp_error( $response ) ) {
				return '';
			}

			// Get the CSS from our response.
			$contents = wp_remote_retrieve_body( $response );

			// Early exit if there was an error.
			if ( is_wp_error( $contents ) ) {
				return;
			}

			return $contents;
		}
		/**
		 * Main AJAX callback function for:
		 * 1). get local data if there
		 * 2). query api for data if needed
		 * 3). import content
		 * 4). execute 'after content import' actions (before widget import WP action, widget import, customizer import, after import WP action)
		 */
		public function prebuilt_data_ajax_callback() {
			// Verify if the AJAX call is valid (checks nonce and current_user_can).
			$this->verify_ajax_call();
			$this->local_template_data_path = '';
			
			
			
			$this->package       = empty( $_POST['package'] ) ? 'section' : sanitize_text_field( $_POST['package'] );
			$this->url           = $this->remote_url;
			$this->key           = isset( $_POST['key'] ) && ! empty( $_POST['key'] ) ? sanitize_text_field( $_POST['key'] ) : 'section';
			$this->is_template   = isset( $_POST['is_template'] ) && ! empty( $_POST['is_template'] ) ? true : false;
			// Do you have the data?
			$get_data = $this->get_template_data();

			if ( ! $get_data ) {
				// Send JSON Error response to the AJAX call.
				wp_send_json( esc_html__( 'No library data', 'blockwheels' ) );
			} else {
				wp_send_json( $get_data );
			}
			die;
		}
		
		/**
		 * Main AJAX callback function for getting the prebuilt templates array.
		 */
		public function prebuilt_pages_data_ajax_callback() {
			// Verify if the AJAX call is valid (checks nonce and current_user_can).
			$this->verify_ajax_call();
			$this->local_pages_data_path = '';
			$this->package       = 'pages';
			$this->url           = $this->remote_pages_url;
			$this->key           = 'pages';
			// Do you have the data?
			$get_data = $this->get_template_data();
			if ( ! $get_data ) {
				// Send JSON Error response to the AJAX call.
				wp_send_json( esc_html__( 'No library data', 'blockwheels' ) );
			} else {
				wp_send_json( $get_data );
			}
			die;
		}
		/**
		 * Main AJAX callback function for:
		 * 1). get local data if there
		 * 2). query api for data if needed
		 * 3). import content
		 * 4). execute 'after content import' actions (before widget import WP action, widget import, customizer import, after import WP action)
		 */
		public function prebuilt_pages_data_reload_ajax_callback() {

			// Verify if the AJAX call is valid (checks nonce and current_user_can).
			$this->verify_ajax_call();
			$this->local_pages_data_path = '';
			$this->package       = 'pages';
			$this->url           = $this->remote_pages_url;
			$this->key           = 'pages';

			// Do you have the data?
			$get_data = $this->get_template_data( true );

			if ( ! $get_data ) {
				// Send JSON Error response to the AJAX call.
				wp_send_json( esc_html__( 'No library data', 'blockwheels' ) );
			} else {
				wp_send_json( $get_data );
			}
			die;
		}
		/**
		 * Ajax function for processing the import data.
		 */
		public function process_data_ajax_callback() {
			// Verify if the AJAX call is valid (checks nonce and current_user_can).
			$this->verify_ajax_call();
			$data           = empty( $_POST['import_content'] ) ? '' : stripslashes( $_POST['import_content'] );
			$import_library = empty( $_POST['import_library'] ) ? 'standard' : sanitize_text_field( $_POST['import_library'] );
			$import_type    = empty( $_POST['import_type'] ) ? 'pattern' : sanitize_text_field( $_POST['import_type'] );
			$import_id      = empty( $_POST['import_item_id'] ) ? '' : sanitize_text_field( $_POST['import_item_id'] );
			$this->package  = empty( $_POST['package'] ) ? 'section' : sanitize_text_field( $_POST['package'] );
			$this->url      = $this->remote_url;
			$this->key      = isset( $_POST['key'] ) && ! empty( $_POST['key'] ) ? sanitize_text_field( $_POST['key'] ) : 'section';
			$data = $this->process_content( $data, $import_library, $import_type, $import_id );
			if ( ! $data ) {
				// Send JSON Error response to the AJAX call.
				wp_send_json( esc_html__( 'No data', 'blockwheels' ) );
			} else {
				wp_send_json( $data );
			}
			die;
		}
		/**
		 * Download and Replace images
		 *
		 * @param  string $content the import post content.
		 */
		public function process_content( $content = '', $import_library = '', $import_type = '', $import_id = '' ) {
			// Find all urls.
			preg_match_all( '#\bhttps?://[^,\s()<>]+(?:\([\w\d]+\)|([^,[:punct:]\s]|/))#', $content, $match );
			$all_urls = array_unique( $match[0] );

			if ( empty( $all_urls ) ) {
				return $content;
			}

			$map_urls    = array();
			$image_urls  = array();
			// Find all the images.
			foreach ( $all_urls as $key => $link ) {
				if ( $this->check_for_image( $link ) ) {
					// Avoid srcset images.
					if (
						false === strpos( $link, '-150x' ) &&
						false === strpos( $link, '-300x' ) &&
						false === strpos( $link, '-1024x' )
					) {
						$image_urls[] = $link;
					}
				}
			}
			// Process images.
			if ( ! empty( $image_urls ) ) {
				foreach ( $image_urls as $key => $image_url ) {
					// Download remote image.
					$image            = array(
						'url' => $image_url,
						'id'  => 0,
					);
					$downloaded_image       = $this->import_image( $image );
					$map_urls[ $image_url ] = $downloaded_image['url'];
				}
			}
			// Replace images in content.
			foreach ( $map_urls as $old_url => $new_url ) {
				$content = str_replace( $old_url, $new_url, $content );
				// Replace the slashed URLs if any exist.
				$old_url = str_replace( '/', '/\\', $old_url );
				$new_url = str_replace( '/', '/\\', $new_url );
				$content = str_replace( $old_url, $new_url, $content );
			}
			return $content;
		}
		/**
		 * Import an image.
		 *
		 * @param array $image_data the image data to import.
		 */
		public function import_image( $image_data ) {
			$local_image = $this->check_for_local_image( $image_data );
			if ( $local_image['status'] ) {
				return $local_image['image'];
			}

			$file_content = wp_remote_retrieve_body(
				wp_safe_remote_get(
					$image_data['url'],
					array(
						'timeout'   => '60',
						'sslverify' => false,
					)
				)
			);
			// Empty file content?
			if ( empty( $file_content ) ) {
				return $image_data;
			}

			$filename = basename( $image_data['url'] );

			$upload = wp_upload_bits( $filename, null, $file_content );
			$post = array(
				'post_title' => $filename,
				'guid'       => $upload['url'],
			);
			$info = wp_check_filetype( $upload['file'] );
			if ( $info ) {
				$post['post_mime_type'] = $info['type'];
			} else {
				return $image_data;
			}
			$post_id = wp_insert_attachment( $post, $upload['file'] );
			wp_update_attachment_metadata(
				$post_id,
				wp_generate_attachment_metadata( $post_id, $upload['file'] )
			);
			update_post_meta( $post_id, '_blockwheels_image_hash', sha1( $image_data['url'] ) );

			return array(
				'id'  => $post_id,
				'url' => $upload['url'],
			);
		}

		/**
		 * Check if image is already imported.
		 *
		 * @param array $image_data the image data to import.
		 */
		public function check_for_local_image( $image_data ) {
			global $wpdb;

			// Thanks BrainstormForce for this idea.
			// Check if image is already local based on meta key and custom hex value.
			$image_id = $wpdb->get_var(
				$wpdb->prepare(
					'SELECT `post_id` FROM `' . $wpdb->postmeta . '`
						WHERE `meta_key` = \'_blockwheels_image_hash\'
							AND `meta_value` = %s
					;',
					sha1( $image_data['url'] )
				)
			);
			if ( $image_id ) {
				$local_image = array(
					'id'  => $image_id,
					'url' => wp_get_attachment_url( $image_id ),
				);
				return array(
					'status' => true,
					'image'  => $local_image,
				);
			}
			return array(
				'status' => false,
				'image'  => $image_data,
			);
		}
		/**
		 * Check if link is for an image.
		 *
		 * @param string $link url possibly to an image.
		 */
		public function check_for_image( $link = '' ) {
			return preg_match( '/^((https?:\/\/)|(www\.))([a-z0-9-].?)+(:[0-9]+)?\/[\w\-]+\.(jpg|png|gif|webp|jpeg)\/?$/i', $link );
		}
		/**
		 * Check if the AJAX call is valid.
		 */
		public static function verify_ajax_call() {
			check_ajax_referer( 'blockwheels-ajax-verification', 'security' );
			// Make sure we are working with a user that can edit posts.
			if ( ! current_user_can( 'edit_posts' ) ) {
				wp_die( -1, 403 );
			}
		}
		/**
		 * Main AJAX callback function for:
		 * 1). get local data if there
		 * 2). query api for data if needed
		 * 3). import content
		 * 4). execute 'after content import' actions (before widget import WP action, widget import, customizer import, after import WP action)
		 */
		public function prebuilt_data_reload_ajax_callback() {

			// Verify if the AJAX call is valid (checks nonce and current_user_can).
			$this->verify_ajax_call();
			$this->local_template_data_path = '';
			$this->package   = empty( $_POST['package'] ) ? 'section' : sanitize_text_field( $_POST['package'] );
			$this->url       = $this->remote_url;
			$this->key       = empty( $_POST['key'] ) ? 'section' : sanitize_text_field( $_POST['key'] );

			// Do you have the data?
			$get_data = $this->get_template_data( true );

			if ( ! $get_data ) {
				// Send JSON Error response to the AJAX call.
				wp_send_json( esc_html__( 'No library data', 'blockwheels' ) );
			} else {
				wp_send_json( $get_data );
			}
			die;
		}

		/**
		 * Schedule a cleanup.
		 *
		 * Deletes the templates files on a regular basis.
		 * This way, templates get updated regularly.
		 *
		 * @access public
		 * @return void
		 */
		public function schedule_cleanup() {
			if ( ! is_multisite() || ( is_multisite() && is_main_site() ) ) {
				// Check if the plugin version has changed or the plugin is freshly installed
				$current_version = get_option( 'blockwheels_free_version' );

				if ( $current_version !== BLOCKWHEELS_VERSION ) {
					// Unschedule any existing event to prevent duplicates
					$timestamp = wp_next_scheduled( 'delete_block_library_folder' );
					if ( $timestamp ) {
						wp_unschedule_event( $timestamp, 'delete_block_library_folder' );
					}

					// Schedule a new cleanup event
					if ( ! wp_installing() ) {
						wp_schedule_event( time(), self::CLEANUP_FREQUENCY, 'delete_block_library_folder' );
					}

					// Update the stored plugin version
					update_option( 'blockwheels_free_version', BLOCKWHEELS_VERSION );
				}
			}
		}


		/**
		 * Add Monthly to Schedule.
		 *
		 * @param array $schedules the current schedules.
		 * @access public
		 */
		public function add_monthly_to_cron_schedule( $schedules ) {
			// Adds once monthly to the existing schedules.
			if ( ! isset( $schedules[ self::CLEANUP_FREQUENCY ] ) ) {
				$schedules[ self::CLEANUP_FREQUENCY ] = array(
					'interval' => MONTH_IN_SECONDS,
					'display' => __( 'Once Monthly', 'blockwheels' ),
				);
			}
			return $schedules;
		}
		/**
		 * Delete the fonts folder.
		 *
		 * This runs as part of a cleanup routine.
		 *
		 * @access public
		 * @return bool
		 */
		public function delete_block_library_folder() {
			if ( file_exists( $this->get_old_block_library_folder() ) ) {
				$this->get_filesystem()->delete( $this->get_old_block_library_folder(), true );
			}
			return $this->get_filesystem()->delete( $this->get_block_library_folder(), true );
		}
		/**
		 * Get the old folder for templates data.
		 *
		 * @access public
		 * @return string
		 */
		public function get_old_block_library_folder() {
			$old_block_library_folder = trailingslashit( $this->get_filesystem()->wp_content_dir() ) . 'blockwheels_library';
			return $old_block_library_folder;
		}
		/**
		 * Get the folder for templates data.
		 *
		 * @access public
		 * @return string
		 */
		public function get_block_library_folder() {
			if ( ! $this->block_library_folder ) {
				$this->block_library_folder = $this->get_base_path();
				if ( $this->get_subfolder_name() ) {
					$this->block_library_folder .= $this->get_subfolder_name();
				}
			}
			return $this->block_library_folder;
		}
		/**
		 * Get the subfolder name.
		 *
		 * @access public
		 * @return string
		 */
		public function get_subfolder_name() {
			if ( ! $this->subfolder_name ) {
				$this->subfolder_name = apply_filters( 'blockwheels_library_local_data_subfolder_name', 'blockwheels_library' );
			}
			return $this->subfolder_name;
		}
		/**
		 * Get the base path.
		 *
		 * @access public
		 * @return string
		 */
		public function get_base_path() {
			if ( ! $this->base_path ) {
				$upload_dir = wp_upload_dir();
				$this->base_path = apply_filters( 'blockwheels_library_local_data_base_path', trailingslashit( $upload_dir['basedir'] ) );
			}
			return $this->base_path;
		}
		/**
		 * Get the base URL.
		 *
		 * @access public
		 * @return string
		 */
		public function get_base_url() {
			if ( ! $this->base_url ) {
				$this->base_url = apply_filters( 'blockwheels_library_local_data_base_url', content_url() );
			}
			return $this->base_url;
		}
		/**
		 * Get the filesystem.
		 *
		 * @access protected
		 * @return WP_Filesystem
		 */
		protected function get_filesystem() {
			global $wp_filesystem;

			// If the filesystem has not been instantiated yet, do it here.
			if ( ! $wp_filesystem ) {
				if ( ! function_exists( 'WP_Filesystem' ) ) {
					require_once wp_normalize_path( ABSPATH . '/wp-admin/includes/file.php' );
				}
				$wpfs_creds = apply_filters( 'blockwheels_wpfs_credentials', false );
				WP_Filesystem( $wpfs_creds );
			}
			return $wp_filesystem;
		}
	}
}
Blockwheels_Prebuilt_Library::get_instance();