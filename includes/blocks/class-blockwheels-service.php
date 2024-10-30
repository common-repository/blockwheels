<?php
/**
 * Registers all block assets so that they can be enqueued through the block editor
 * in the corresponding context.
 *
 * @see https://developer.wordpress.org/block-editor/tutorials/block-tutorial/applying-styles-with-stylesheets/
 */

if ( ! function_exists( 'blockwheels_render_service' ) ) {

	function blockwheels_render_service( $attributes, $content ) {

		if ( isset( $attributes['blockId'] ) ) {

			$block_id 	= sanitize_text_field( $attributes['blockId'] );
			// now we generate the styles for wp_add_inline_style
			$inline_css = '';
			$selector 	= '.blockwheels-service-wrapper[data-id="' . esc_attr( $block_id ) . '"]';
			$block_name = 'service';
			$handle 	= 'blockwheels-' . $block_name . '-style';
			
			// Column Background
			if ( isset( $attributes['colBgColor'] ) ) {
				$inline_css .= Blockwheels_CSS::get_color_css(
					$selector,
					$attributes['colBgColor'],
					'background-color',
					'!important'
				);
			}
			if ( isset( $attributes['colBgGradient'] ) ) {
				$inline_css .= Blockwheels_CSS::get_color_css(
					$selector,
					$attributes['colBgGradient'],
					'background-image',
					'!important'
				);
			}
			// Icon color
			if ( isset( $attributes['iconColor'] ) ) {
				$inline_css .= Blockwheels_CSS::get_color_css(
					$selector . ' .wp-block-icon svg',
					$attributes['iconColor'],
					'color'
				);
			}
			// Background color
			if ( isset( $attributes['iconBgColor'] ) ) {
				$inline_css .= Blockwheels_CSS::get_color_css(
					$selector . ' .wp-block-icon',
					$attributes['iconBgColor'],
					'background-color'
				);
				$inline_css .= Blockwheels_CSS::get_color_css(
					$selector . ' .wp-block-image-wrap',
					$attributes['iconBgColor'],
					'background-color'
				);
			}
			// Background gradient
			if ( isset( $attributes['iconBgGradient'] ) ) {
				$inline_css .= Blockwheels_CSS::get_color_css(
					$selector . ' .wp-block-icon',
					$attributes['iconBgGradient'],
					'background-image'
				);
				$inline_css .= Blockwheels_CSS::get_color_css(
					$selector . ' .wp-block-image-wrap',
					$attributes['iconBgGradient'],
					'background-image'
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

if ( ! function_exists( 'blockwheels_register_service' ) ) {

	function blockwheels_register_service() {

		$block_name  		= 'service';
		$asset_config_file 	= sprintf( '%s/build/blocks/services/'. $block_name .'/index.asset.php', BLOCKWHEELS_PATH );
		if ( ! file_exists( $asset_config_file ) ) {
			return;
		}
	
		register_block_type(
			BLOCKWHEELS_PATH . 'build/blocks/services/' . $block_name . '/block.json',
			array(
				'render_callback' 	=> 'blockwheels_render_service'
			)
		);
	}
}
add_action( 'init', 'blockwheels_register_service' );