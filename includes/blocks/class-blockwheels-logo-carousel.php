<?php
/**
 * Registers all block assets so that they can be enqueued through the block editor
 * in the corresponding context.
 *
 * @see https://developer.wordpress.org/block-editor/tutorials/block-tutorial/applying-styles-with-stylesheets/
 */
if ( ! function_exists( 'blockwheels_render_logo_carousel' ) ) {

	function blockwheels_render_logo_carousel( $attributes, $content ) {

		if ( isset( $attributes['blockId'] ) ) {

			$block_id 	= sanitize_text_field( $attributes['blockId'] );
			// now we generate the styles for wp_add_inline_style
			$inline_css = '';
			$fonts		= [];
			$selector 	= '.blockwheels-section[data-id="' . esc_attr( $block_id ) . '"]';
			$block_name = 'logo-carousel';
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

			// Padding
			if ( isset( $attributes['padding'] ) ) {
				$inline_css .= Blockwheels_CSS::get_dimensions_css(
					$selector . ' .wp-block-blockwheels-logo-carousel',
					$attributes['padding'],
					'padding'
				);
			}
	
			// Navigation Icon Color
			if ( isset( $attributes['navIconColor'] ) ) {
				$inline_css .= Blockwheels_CSS::get_generate_css(
					$selector . ' .wp-block-blockwheels-logo-carousel .logo-carousel-navigation div[class*=swiper-button-]',
					$attributes['navIconColor'],
					'color'
				);
			}
			// Navigation Background Color
			if ( isset( $attributes['navBgColor'] ) ) {
				$inline_css .= Blockwheels_CSS::get_generate_css(
					$selector . ' .wp-block-blockwheels-logo-carousel .logo-carousel-navigation div[class*=swiper-button-]',
					$attributes['navBgColor'],
					'background-color'
				);
			}
			// Navigation Radius Color
			if ( isset( $attributes['navBorderRadius'] ) ) {
				$inline_css .= Blockwheels_CSS::get_generate_css(
					$selector . ' .wp-block-blockwheels-logo-carousel .logo-carousel-navigation div[class*=swiper-button-]',
					$attributes['navBorderRadius'],
					'border-radius'
				);
			}
			// Pagination Color
			if ( isset( $attributes['dotsColor'] ) ) {
				$inline_css .= Blockwheels_CSS::get_generate_css(
					$selector . ' .wp-block-blockwheels-logo-carousel .logo-carousel-pagination .swiper-pagination-bullet',
					$attributes['dotsColor'],
					'background-color'
				);
			}
			// Pagination Active Color
			if ( isset( $attributes['dotsActiveColor'] ) ) {
				$inline_css .= Blockwheels_CSS::get_generate_css(
					$selector . ' .wp-block-blockwheels-logo-carousel .logo-carousel-pagination .swiper-pagination-bullet.swiper-pagination-bullet-active',
					$attributes['dotsActiveColor'],
					'background-color'
				);
			}
			// Items
			if ( isset( $attributes['imageBorderRadius'] ) ) {
				$inline_css .= Blockwheels_CSS::get_generate_css(
					$selector . ' .wp-block-blockwheels-logo-carousel .logo-carousel-item',
					$attributes['imageBorderRadius'],
					'border-radius'
				);
			}

			wp_enqueue_script( 'blockwheels-swiper' );
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

if ( ! function_exists( 'blockwheels_register_logo_carousel' ) ) {

	function blockwheels_register_logo_carousel() {

		$block_name = 'logo-carousel';
		$asset_config_file 	= sprintf( '%s/build/blocks/'. $block_name .'/index.asset.php', BLOCKWHEELS_PATH );
		if ( ! file_exists( $asset_config_file ) ) {
			return;
		}
	
		register_block_type(
			BLOCKWHEELS_PATH . 'build/blocks/' . $block_name . '/block.json',
			array(
				'render_callback' 	=> 'blockwheels_render_logo_carousel'
			)
		);
	}
}
add_action( 'init', 'blockwheels_register_logo_carousel' );
