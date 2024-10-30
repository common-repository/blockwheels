<?php
/**
 * Registers all block assets so that they can be enqueued through the block editor
 * in the corresponding context.
 *
 * @see https://developer.wordpress.org/block-editor/tutorials/block-tutorial/applying-styles-with-stylesheets/
 */

if ( ! function_exists( 'blockwheels_render_accordion_panel' ) ) {
	
	function blockwheels_render_accordion_panel( $attributes, $content ) {

		if ( isset( $attributes['blockId'] ) ) {
			
			return $content;
		}
	}
}

if ( ! function_exists( 'blockwheels_register_accordion_panel' ) ) {

	function blockwheels_register_accordion_panel() {

		$block_name  		= 'panel';
		$asset_config_file 	= sprintf( '%s/build/blocks/accordion/'. $block_name .'/index.asset.php', BLOCKWHEELS_PATH );
		if ( ! file_exists( $asset_config_file ) ) {
			return;
		}
	
		register_block_type(
			BLOCKWHEELS_PATH . 'build/blocks/accordion/' . $block_name . '/block.json',
			array(
				'render_callback' 	=> 'blockwheels_render_accordion_panel'
			)
		);
	}
}
add_action( 'init', 'blockwheels_register_accordion_panel' );
