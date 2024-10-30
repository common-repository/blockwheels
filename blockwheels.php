<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://wpwheels.com/
 * @since             1.0.0
 * @package           Blockwheels
 *
 * @wordpress-plugin
 * Plugin Name:       BlockWheels
 * Plugin URI:        https://wpwheels.com/plugins/blockwheels/
 * Description:       BlockWheels provides interactive Gutenberg blocks within the WordPress block editor. Create engaging posts and pages by using a variety of features, including post grids, post blocks, and more. It serves as a flexible page builder, accommodating blocks, patterns, templates, and full-site editing.
 * Version:           1.0.1
 * Author:            wpwheels
 * Author URI:        https://wpwheels.com/
 * License:           GPLv3 or later
 * License URI:       https://www.gnu.org/licenses/gpl-3.0.en.html
 * Text Domain:       blockwheels
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

// Freemius SDK: Auto deactivate the free version when activating the paid one.
if ( function_exists( 'blockwheels_fs' ) ) {
    blockwheels_fs()->set_basename( false, __FILE__ );
    return;
}

/**
 * Current plugin path.
 * Current plugin url.
 * Current plugin version.
 *
 * Rename these constants for your plugin
 * Update version as you release new versions.
 */

define( 'BLOCKWHEELS_PATH', plugin_dir_path( __FILE__ ) );
define( 'BLOCKWHEELS_URL', plugin_dir_url( __FILE__ ) );
define( 'BLOCKWHEELS_BASE_FILE', __FILE__ );
define( 'BLOCKWHEELS_VERSION', '1.0.1' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-blockwheels-activator.php
 */
if ( ! function_exists( 'blockwheels_plugin_activate' ) ) {
	function blockwheels_plugin_activate() {
		require_once BLOCKWHEELS_PATH . 'includes/class-blockwheels-activator.php';
		Blockwheels_Activator::activate();
	}
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-blockwheels-deactivator.php
 */
if ( ! function_exists( 'blockwheels_plugin_deactivate' ) ) {
	function blockwheels_plugin_deactivate() {
		require_once BLOCKWHEELS_PATH . 'includes/class-blockwheels-deactivator.php';
		Blockwheels_Deactivator::deactivate();
	}
}
register_activation_hook( __FILE__, 'blockwheels_plugin_activate' );
register_deactivation_hook( __FILE__, 'blockwheels_plugin_deactivate' );

/**
 * Freemius.
 * This needs to be first.
 */
require_once plugin_dir_path( __FILE__ ) . 'freemius.php';

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require BLOCKWHEELS_PATH . 'includes/class-blockwheels.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
if ( ! function_exists( 'blockwheels' ) ) {

	function blockwheels() {

		$plugin = new Blockwheels();
		$plugin->run();
	}
	blockwheels();
}