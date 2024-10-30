<?php
/**
 * Registers all block assets so that they can be enqueued through the block editor
 * in the corresponding context.
 *
 * @see https://developer.wordpress.org/block-editor/tutorials/block-tutorial/applying-styles-with-stylesheets/
 */
if ( ! function_exists( 'blockwheels_render_video_box' ) ) {

	function blockwheels_render_video_box( $attributes, $content ) {

		if ( isset( $attributes['blockId'] ) ) {

			$block_id 	= sanitize_text_field( $attributes['blockId'] );
			// now we generate the styles for wp_add_inline_style
			$inline_css = '';
			$selector 	= '.blockwheels-section[data-id="' . esc_attr( $block_id ) . '"]';
			$block_name = 'video-box';
			$handle 	= 'blockwheels-' . $block_name . '-style';

			// Padding
			if ( isset( $attributes['padding'] ) ) {
				$inline_css .= Blockwheels_CSS::get_dimensions_css(
					$selector .' .blockwheels-video-box-container',
					$attributes['padding'],
					'padding'
				);
			}
			// Margin
			if ( isset( $attributes['margin'] ) ) {
				$inline_css .= Blockwheels_CSS::get_dimensions_css(
					$selector,
					$attributes['margin'],
					'margin'
				);
			}

			if ( $inline_css !== '' ) {
				$css = Blockwheels_CSS::minify( $inline_css );
				wp_add_inline_style( $handle, $css );
			}

			$section_classes = ['blockwheels-section'];
			if ( isset( $attributes['align'] ) ) {
				$section_classes[] = 'align'. sanitize_text_field($attributes['align']);
			}
			if ( isset( $attributes['className'] ) ) {
				$section_classes[] = sanitize_text_field( $attributes['className'] );
			}

			return sprintf( '<div class="%1$s" data-id="%2$s">%3$s</div>', 
				esc_attr(implode(' ', $section_classes )),
				esc_attr($block_id),
				$content
			);
		}
	}
}

if ( ! function_exists( 'blockwheels_register_video_box' ) ) {

	function blockwheels_register_video_box() {

		$block_name  		= 'video-box';
		$asset_config_file 	= sprintf( '%s/build/blocks/'. $block_name .'/index.asset.php', BLOCKWHEELS_PATH );
		if ( ! file_exists( $asset_config_file ) ) {
			return;
		}
	
		register_block_type(
			BLOCKWHEELS_PATH . 'build/blocks/' . $block_name . '/block.json',
			array(
				'render_callback' 	=> 'blockwheels_render_video_box'
			)
		);
	}
}
add_action( 'init', 'blockwheels_register_video_box' );
