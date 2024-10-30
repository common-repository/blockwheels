<?php
/**
 * Registers all block assets so that they can be enqueued through the block editor
 * in the corresponding context.
 *
 * @see https://developer.wordpress.org/block-editor/tutorials/block-tutorial/applying-styles-with-stylesheets/
 */

if ( ! function_exists( 'blockwheels_render_heading' ) ) {

	function blockwheels_render_heading( $attributes, $content ) {

		if ( isset( $attributes['blockId'] ) ) {

			$block_id 	= sanitize_text_field( $attributes['blockId'] );
			// now we generate the styles for wp_add_inline_style
			$inline_css = '';
			$fonts		= [];
			$selector 	= '.blockwheels-section[data-id="' . esc_attr( $block_id ) . '"]';
			$block_name = 'heading';
			$handle 	= 'blockwheels-' . $block_name . '-style';
			
			// Text Transform
			if ( isset( $attributes['letterCase'] ) ) {
				$inline_css .= Blockwheels_CSS::get_generate_css(
					$selector . ' .blockwheels-heading',
					$attributes['letterCase'],
					'text-transform'
				);
			}
			// Font Family
			if ( isset( $attributes['fontFamily'] ) && $attributes['fontFamily'] !== 'system' ) {
				$all_fonts	= Blockwheels_Google_Fonts::get_fonts();
				if ( array_key_exists( $attributes['fontFamily'], $all_fonts ) ) {
					$fonts[$attributes['fontFamily']] = map_deep( wp_unslash( $all_fonts[$attributes['fontFamily']] ), 'sanitize_text_field' );
				}
				$inline_css .= Blockwheels_CSS::get_generate_css(
					$selector . ' .blockwheels-heading',
					$all_fonts[$attributes['fontFamily']]['name'],
					'font-family'
				);
			}
			// Font Weight
			if ( isset( $attributes['fontWeight'] ) ) {
				$inline_css .= Blockwheels_CSS::get_generate_css(
					$selector . ' .blockwheels-heading',
					$attributes['fontWeight'],
					'font-weight'
				);
			}
			// Font Style
			if ( isset( $attributes['fontStyle'] ) ) {
				$inline_css .= Blockwheels_CSS::get_generate_css(
					$selector . ' .blockwheels-heading',
					$attributes['fontStyle'],
					'font-style'
				);
			}
			// Font Size
			if ( isset( $attributes['fontSize'] ) ) {
				$inline_css .= Blockwheels_CSS::get_range_css(
					$selector . ' .blockwheels-heading',
					$attributes['fontSize'],
					'font-size'
				);
			}
			// Padding
			if ( isset( $attributes['padding'] ) ) {
				$inline_css .= Blockwheels_CSS::get_dimensions_css(
					$selector . ' .blockwheels-heading',
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
			// Color
			if ( isset( $attributes['textColor'] ) ) {
				$inline_css .= Blockwheels_CSS::get_color_css(
					$selector . ' .blockwheels-heading',
					$attributes['textColor'],
					'color'
				);
			}
			// Link Color
			if ( isset( $attributes['linkColor'] ) ) {
				$inline_css .= Blockwheels_CSS::get_color_css(
					$selector . ' a',
					$attributes['linkColor'],
					'color'
				);
			}
			if ( isset( $attributes['linkHoverColor'] ) ) {
				$inline_css .= Blockwheels_CSS::get_color_css(
					$selector . ' a:hover',
					$attributes['linkHoverColor'],
					'color'
				);
			}

			if ( $inline_css !== '' ) {
				$css = Blockwheels_CSS::minify( $inline_css );
				wp_add_inline_style( $handle, $css );
				// Google Fonts
				if ( ! empty( $fonts ) ) {
					foreach ( $fonts as $key => $selected_font ) {
						if ( ! array_key_exists( 'inherit', $fonts ) ) {
							wp_enqueue_style(
								$handle . '-' . $key . '-font',
								blockwheels_get_webfont_url( $selected_font['url'] ),
								[],
								BLOCKWHEELS_VERSION
							);
						}
					}
				}
			}


			$section_classes = ['blockwheels-section'];
			if ( isset( $attributes['align'] ) ) {
				$section_classes[] = 'align'. sanitize_text_field($attributes['align']);
			}
			if ( isset( $attributes['className'] ) ) {
				$section_classes[] = sanitize_text_field( $attributes['className'] );
			}
			// Alignment
			if ( !empty( $attributes['alignment'] ) ) {
				$section_classes[]	= isset( $attributes['alignment']['mobile'] ) ? 'sm-text-' . sanitize_text_field($attributes['alignment']['mobile']) : 'sm-text-left';
				$section_classes[]	= isset( $attributes['alignment']['tablet'] ) ? 'md-text-' . sanitize_text_field($attributes['alignment']['tablet']) : 'md-text-left';
				$section_classes[]	= isset( $attributes['alignment']['desktop'] ) ? 'text-' . sanitize_text_field($attributes['alignment']['desktop']) : 'text-left';
			}
			return sprintf( '<div class="%1$s" data-id="%2$s">%3$s</div>', 
				esc_attr(implode(' ', $section_classes )),
				esc_attr($block_id),
				$content
			);
		}
	}
}

if ( ! function_exists( 'blockwheels_register_heading' ) ) {

	function blockwheels_register_heading() {

		$block_name = 'heading';
		$asset_config_file 	= sprintf( '%s/build/blocks/'. $block_name .'/index.asset.php', BLOCKWHEELS_PATH );
		if ( ! file_exists( $asset_config_file ) ) {
			return;
		}
	
		register_block_type(
			BLOCKWHEELS_PATH . 'build/blocks/' . $block_name . '/block.json',
			array(
				'render_callback' 	=> 'blockwheels_render_heading'
			)
		);
	}
}
add_action( 'init', 'blockwheels_register_heading' );
