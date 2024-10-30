<?php
/**
 * Registers all block assets so that they can be enqueued through the block editor
 * in the corresponding context.
 *
 * @see https://developer.wordpress.org/block-editor/tutorials/block-tutorial/applying-styles-with-stylesheets/
 */
if ( ! function_exists( 'blockwheels_render_testimonial' ) ) {

	function blockwheels_render_testimonial( $attributes, $content ) {

		if ( isset( $attributes['blockId'] ) ) {

			$block_id 	= sanitize_text_field( $attributes['blockId'] );
			// now we generate the styles for wp_add_inline_style
			$inline_css = '';
			$selector 	= '.blockwheels-testimonial-wrapper[data-id="' . $block_id . '"]';
			$block_name = 'testimonial';
			$handle 	= 'blockwheels-' . $block_name . '-style';
			
			// Padding
			if ( isset( $attributes['contentPadding'] ) ) {
				$inline_css .= Blockwheels_CSS::get_dimensions_css(
					$selector . ' .wp-block-content',
					$attributes['contentPadding'],
					'padding'
				);
			}
			// Margin
			if ( isset( $attributes['contentMargin'] ) ) {
				$inline_css .= Blockwheels_CSS::get_dimensions_css(
					$selector . ' .wp-block-content',
					$attributes['contentMargin'],
					'margin'
				);
			}

			if ( $inline_css !== '' ) {
				$css = Blockwheels_CSS::minify( $inline_css );
				wp_add_inline_style( $handle, $css );
			}

			return $content;
		}
	}
}

if ( ! function_exists( 'blockwheels_register_testimonial' ) ) {

	function blockwheels_register_testimonial() {

		$block_name  	= 'testimonial';
		$asset_config_file 	= sprintf( '%s/build/blocks/testimonials/'. $block_name .'/index.asset.php', BLOCKWHEELS_PATH );
		if ( ! file_exists( $asset_config_file ) ) {
			return;
		}
	
		register_block_type(
			BLOCKWHEELS_PATH . 'build/blocks/testimonials/' . $block_name . '/block.json',
			array(
				'render_callback' 	=> 'blockwheels_render_testimonial'
			)
		);
	}
}
add_action( 'init', 'blockwheels_register_testimonial' );
