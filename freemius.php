<?php

/**
 * Init Freemius.
 */
// Exit if accessed directly.
if ( !defined( 'ABSPATH' ) ) {
    exit;
}


if ( ! function_exists( 'blockwheels_fs' ) ) {
    // Create a helper function for easy SDK access.
    function blockwheels_fs() {
        global $blockwheels_fs;

        if ( ! isset( $blockwheels_fs ) ) {
            // Include Freemius SDK.
            require_once dirname(__FILE__) . '/freemius/start.php';

            $blockwheels_fs = fs_dynamic_init( array(
                'id'            => '14992',
                'slug'          => 'blockwheels',
                'premium_slug'  => 'blockwheels-pro',
                'type'          => 'plugin',
                'public_key'    => 'pk_2d4094f6653d3885ada5f3e8a675d',
                'is_premium'    => false,
                'has_addons'    => false,
                'has_paid_plans'=> true,
                'menu'          => array(
                    'slug'          => 'blockwheels',
                    'account'       => true,
                    'pricing'       => true,
                    'contact'       => true,
                    'support'       => false,
                    'affiliation'   => false,
                ),
                'is_live'        => true,
            ) );
        }

        return $blockwheels_fs;
    }
    // Init Freemius.
    blockwheels_fs();
    // Disable some Freemius features.
    blockwheels_fs()->add_filter( 'show_deactivation_feedback_form', '__return_false' );
    blockwheels_fs()->add_filter( 'hide_freemius_powered_by', '__return_true' );
    blockwheels_fs()->add_filter( 'permission_diagnostic_default', '__return_false' );
    // Disable opt-in option by default
    blockwheels_fs()->add_filter( 'permission_extensions_default', '__return_false' );
    // Disable opt-in option by default
    // Signal that SDK was initiated.
    do_action( 'blockwheels_fs_loaded' );
    
}
