<?php
/**
 * Registers all block assets so that they can be enqueued through the block editor
 * in the corresponding context.
 *
 * @see https://developer.wordpress.org/block-editor/tutorials/block-tutorial/applying-styles-with-stylesheets/
 */

if ( ! function_exists( 'blockwheels_render_counters' ) ) {

	function blockwheels_render_counters( $attributes, $content ) {

		if ( isset( $attributes['blockId'] ) ) {

			$block_id 	= sanitize_text_field( $attributes['blockId'] );
			// now we generate the styles for wp_add_inline_style
			$inline_css = '';
			$fonts		= [];
			$selector 	= '.blockwheels-section[data-id="' . esc_attr( $block_id ) . '"]';
			$block_name = 'counters';
			$handle 	= 'blockwheels-' . $block_name . '-style';
			
			// Margin
			if ( isset( $attributes['colMargin'] ) ) {
				$inline_css .= Blockwheels_CSS::get_dimensions_css(
					$selector,
					$attributes['colMargin'],
					'margin'
				);
			}
	
			// COLUMNS
			// Background color
			if ( isset( $attributes['colBgColor'] ) ) {
				$inline_css .= Blockwheels_CSS::get_color_css(
					$selector . ' .blockwheels-counter-wrapper',
					$attributes['colBgColor'],
					'background-color'
				);
			}
			// Background gradient
			if ( isset( $attributes['colBgGradient'] ) ) {
				$inline_css .= Blockwheels_CSS::get_color_css(
					$selector . ' .blockwheels-counter-wrapper',
					$attributes['colBgGradient'],
					'background-image'
				);
			}
			// Border Radius
			if ( isset( $attributes['colBorderRadius'] ) ) {
				$inline_css .= Blockwheels_CSS::get_generate_css(
					$selector . ' .blockwheels-counter-wrapper',
					$attributes['colBorderRadius'],
					'border-radius'
				);
			}
			// Padding
			if ( isset( $attributes['colPadding'] ) ) {
				$inline_css .= Blockwheels_CSS::get_dimensions_css(
					$selector . ' .blockwheels-counter-wrapper',
					$attributes['colPadding'],
					'padding'
				);
			}
			// NAME
			// Font Family
			if ( isset( $attributes['nameFontFamily'] ) && $attributes['nameFontFamily'] !== 'system' ) {
				$all_fonts	= Blockwheels_Google_Fonts::get_fonts();
				if ( array_key_exists( $attributes['nameFontFamily'], $all_fonts ) ) {
					$fonts[$attributes['nameFontFamily']] = map_deep( wp_unslash( $all_fonts[$attributes['nameFontFamily']] ), 'sanitize_text_field' );
				}
				$inline_css .= Blockwheels_CSS::get_generate_css(
					$selector . ' .blockwheels-counter-wrapper .wp-block-content h4',
					esc_html($all_fonts[$attributes['nameFontFamily']]['name']),
					'font-family'
				);
			}
			// Font Weight
			if ( isset( $attributes['nameFontWeight'] ) ) {
				$inline_css .= Blockwheels_CSS::get_generate_css(
					$selector . ' .blockwheels-counter-wrapper .wp-block-content h4',
					$attributes['nameFontWeight'],
					'font-weight'
				);
			}
			// Font Style
			if ( isset( $attributes['nameFontStyle'] ) ) {
				$inline_css .= Blockwheels_CSS::get_generate_css(
					$selector . ' .blockwheels-counter-wrapper .wp-block-content h4',
					$attributes['nameFontStyle'],
					'font-style'
				);
			}
			// Text Transform
			if ( isset( $attributes['nameLetterCase'] ) ) {
				$inline_css .= Blockwheels_CSS::get_generate_css(
					$selector . ' .blockwheels-counter-wrapper .wp-block-content h4',
					$attributes['nameLetterCase'],
					'text-transform'
				);
			}
			// Font Size
			if ( isset( $attributes['nameFontSize'] ) ) {
				$inline_css .= Blockwheels_CSS::get_range_css(
					$selector . ' .blockwheels-counter-wrapper .wp-block-content h4',
					$attributes['nameFontSize'],
					'font-size'
				);
			}
			// Color
			if ( isset( $attributes['nameFontColor'] ) ) {
				$inline_css .= Blockwheels_CSS::get_color_css(
					$selector . ' .blockwheels-counter-wrapper .wp-block-content h4',
					$attributes['nameFontColor'],
					'color'
				);
			}
			
			// Number
			// Font Family
			if ( isset( $attributes['numberFontFamily'] ) && $attributes['numberFontFamily'] !== 'system' ) {
				$all_fonts	= Blockwheels_Google_Fonts::get_fonts();
				if ( array_key_exists( $attributes['numberFontFamily'], $all_fonts ) ) {
					$fonts[$attributes['numberFontFamily']] = map_deep( wp_unslash( $all_fonts[$attributes['numberFontFamily']] ), 'sanitize_text_field' );
				}
				$inline_css .= Blockwheels_CSS::get_generate_css(
					$selector . ' .blockwheels-counter-wrapper .wp-block-content .blockwheels-counter-number',
					esc_html($all_fonts[$attributes['numberFontFamily']]['name']),
					'font-family'
				);
			}
			// Font Weight
			if ( isset( $attributes['numberFontWeight'] ) ) {
				$inline_css .= Blockwheels_CSS::get_generate_css(
					$selector . ' .blockwheels-counter-wrapper .wp-block-content .blockwheels-counter-number',
					$attributes['numberFontWeight'],
					'font-weight'
				);
			}
			// Font Style
			if ( isset( $attributes['numberFontStyle'] ) ) {
				$inline_css .= Blockwheels_CSS::get_generate_css(
					$selector . ' .blockwheels-counter-wrapper .wp-block-content .blockwheels-counter-number',
					$attributes['numberFontStyle'],
					'font-style'
				);
			}
			// Text Transform
			if ( isset( $attributes['numberLetterCase'] ) ) {
				$inline_css .= Blockwheels_CSS::get_generate_css(
					$selector . ' .blockwheels-counter-wrapper .wp-block-content .blockwheels-counter-number',
					$attributes['numberLetterCase'],
					'text-transform'
				);
			}
			// Font Size
			if ( isset( $attributes['numberFontSize'] ) ) {
				$inline_css .= Blockwheels_CSS::get_range_css(
					$selector . ' .blockwheels-counter-wrapper .wp-block-content .blockwheels-counter-number',
					$attributes['numberFontSize'],
					'font-size'
				);
			}
			// Color
			if ( isset( $attributes['numberFontColor'] ) ) {
				$inline_css .= Blockwheels_CSS::get_color_css(
					$selector . ' .blockwheels-counter-wrapper .wp-block-content .blockwheels-counter-number',
					$attributes['numberFontColor'],
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

			return sprintf( '<div class="%1$s" data-id="%2$s">%3$s</div>', 
				esc_attr(implode(' ', $section_classes )),
				esc_attr($block_id),
				$content
			);
		}
	}
}

if ( ! function_exists( 'blockwheels_register_counters' ) ) {

	function blockwheels_register_counters() {

		$block_name = 'counters';
		$asset_config_file 	= sprintf( '%s/build/blocks/'. $block_name .'/index.asset.php', BLOCKWHEELS_PATH );
		if ( ! file_exists( $asset_config_file ) ) {
			return;
		}
	
		register_block_type(
			BLOCKWHEELS_PATH . 'build/blocks/' . $block_name . '/block.json',
			array(
				'render_callback' 	=> 'blockwheels_render_counters'
			)
		);
	}
}
add_action( 'init', 'blockwheels_register_counters' );
