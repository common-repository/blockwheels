<?php
/**
 * Registers all block assets so that they can be enqueued through the block editor
 * in the corresponding context.
 *
 * @see https://developer.wordpress.org/block-editor/tutorials/block-tutorial/applying-styles-with-stylesheets/
 */
if ( ! function_exists( 'blockwheels_render_spacer' ) ) {

	function blockwheels_render_spacer( $attributes, $content ) {

		if ( isset( $attributes['blockId'] ) ) {

			$block_id 	= sanitize_text_field( $attributes['blockId'] );
			// now we generate the styles for wp_add_inline_style
			$inline_css = '';
			$selector 	= '.blockwheels-section[data-id="' . esc_attr( $block_id ) . '"]';
			$block_name = 'spacer';
			$handle 	= 'blockwheels-' . $block_name . '-style';
			
			// CONTAINER
			// Margin
			if ( isset( $attributes['margin'] ) ) {
				$inline_css .= Blockwheels_CSS::get_dimensions_css(
					$selector,
					$attributes['margin'],
					'margin'
				);
			}
			// padding
			if ( isset( $attributes['padding'] ) ) {
				$inline_css .= Blockwheels_CSS::get_dimensions_css(
					$selector . ' .wp-block-blockwheels-spacer',
					$attributes['padding'],
					'padding',
				);
			}
			// Height
			if ( isset( $attributes['spacerHeight'] ) ) {
				$inline_css .= Blockwheels_CSS::get_range_css(
					$selector . ' .wp-block-blockwheels-spacer',
					$attributes['spacerHeight'],
					'height',
				);
			}
			// Divider Width
			if ( isset( $attributes['dividerWidth'] ) ) {
				$inline_css .= Blockwheels_CSS::get_range_css(
					$selector . ' .wp-block-blockwheels-spacer .blockwheels-divider',
					$attributes['dividerWidth'],
					'width',
				);
			}
			// Divider Height
			if ( isset( $attributes['dividerHeight'] ) ) {
				$inline_css .= Blockwheels_CSS::get_range_css(
					$selector . ' .wp-block-blockwheels-spacer .blockwheels-divider',
					$attributes['dividerHeight'],
					'border-top-width',
				);
			}
			// Divider Style
			if ( isset( $attributes['dividerStyle'] ) ) {
				$inline_css .= Blockwheels_CSS::get_generate_css(
					$selector . ' .wp-block-blockwheels-spacer .blockwheels-divider',
					$attributes['dividerStyle'],
					'border-top-style'
				);
			}
			// Divider Color
			if ( isset( $attributes['dividerColor'] ) ) {
				$inline_css .= Blockwheels_CSS::get_generate_css(
					$selector . ' .wp-block-blockwheels-spacer .blockwheels-divider',
					$attributes['dividerColor'],
					'border-top-color'
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

if ( ! function_exists( 'blockwheels_register_spacer' ) ) {

	function blockwheels_register_spacer() {

		$block_name = 'spacer';
		$asset_config_file 	= sprintf( '%s/build/blocks/'. $block_name .'/index.asset.php', BLOCKWHEELS_PATH );
		if ( ! file_exists( $asset_config_file ) ) {
			return;
		}
	
		register_block_type(
			BLOCKWHEELS_PATH . 'build/blocks/' . $block_name . '/block.json',
			array(
				'render_callback' 	=> 'blockwheels_render_spacer'
			)
		);
	}
}
add_action( 'init', 'blockwheels_register_spacer' );
