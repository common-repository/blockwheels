<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://wpwheels.com/
 * @since      1.0.0
 *
 * @package    Blockwheels
 * @subpackage Blockwheels/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Blockwheels
 * @subpackage Blockwheels/public
 * @author     wpwheels <info@wpwheels.com>
 */
class Blockwheels_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

    /**
     * Unique ID for this class.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $id    The ID of this class.
     */
    private $id;

    /**
     * Unique ID for this class.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $id    The ID of this class.
     */
    private $blocks;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string $plugin_name       The name of the plugin.
	 * @param      string $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name 	= $plugin_name;
		$this->version     	= $version;
        $this->id     		= $plugin_name.'-public';
        $this->blocks     	= $plugin_name.'-blocks';

    }

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_public_resources() {
		wp_enqueue_style( 'blockwheels-public-style' );

		// Enable automatic RTL support by looking for index-rtl.css.
		wp_style_add_data( 'blockwheels-public-style', 'rtl', 'replace' );
	}
}