<?php
/**
 * Registers all block assets so that they can be enqueued through the block editor
 * in the corresponding context.
 *
 * @see https://developer.wordpress.org/block-editor/tutorials/block-tutorial/applying-styles-with-stylesheets/
 */
if ( ! function_exists( 'blockwheels_render_services' ) ) {

	function blockwheels_render_services( $attributes, $content ) {

		if ( isset( $attributes['blockId'] ) ) {

			$block_id 	= sanitize_text_field( $attributes['blockId'] );
			// now we generate the styles for wp_add_inline_style
			$inline_css = '';
			$fonts		= [];
			$selector 	= '.blockwheels-section[data-id="' . esc_attr( $block_id ) . '"]';
			$block_name = 'services';
			$handle 	= 'blockwheels-' . $block_name . '-style';
			
			// Margin
			if ( isset( $attributes['colMargin'] ) ) {
				$inline_css .= Blockwheels_CSS::get_dimensions_css(
					$selector,
					$attributes['colMargin'],
					'margin'
				);
			}
			// Background color
			if ( isset( $attributes['colBgColor'] ) ) {
				$inline_css .= Blockwheels_CSS::get_color_css(
					$selector . ' .blockwheels-service-wrapper',
					$attributes['colBgColor'],
					'background-color'
				);
			}
			// Background gradient
			if ( isset( $attributes['colBgGradient'] ) ) {
				$inline_css .= Blockwheels_CSS::get_color_css(
					$selector . ' .blockwheels-service-wrapper',
					$attributes['colBgGradient'],
					'background-image'
				);
			}
			// Border Radius
			if ( isset( $attributes['colBorderRadius'] ) ) {
				$inline_css .= Blockwheels_CSS::get_generate_css(
					$selector . ' .blockwheels-service-wrapper',
					$attributes['colBorderRadius'],
					'border-radius'
				);
			}
			// Padding
			if ( isset( $attributes['colPadding'] ) ) {
				$inline_css .= Blockwheels_CSS::get_dimensions_css(
					$selector . ' .blockwheels-service-wrapper',
					$attributes['colPadding'],
					'padding'
				);
			}
			// Icon Size
			if ( isset( $attributes['iconSize'] ) ) {
				$inline_css .= Blockwheels_CSS::get_range_css(
					$selector . ' .blockwheels-service-wrapper',
					$attributes['iconSize'],
					'--service-icon-size'
				);
			}
			// Border Radius
			if ( isset( $attributes['imageRadius'] ) ) {
				$inline_css .= Blockwheels_CSS::get_generate_css(
					$selector . ' .blockwheels-service-wrapper .wp-block-image-wrap',
					$attributes['imageRadius'],
					'border-radius'
				);
				$inline_css .= Blockwheels_CSS::get_generate_css(
					$selector . ' .blockwheels-service-wrapper .wp-block-image-wrap img',
					$attributes['imageRadius'],
					'border-radius'
				);
				$inline_css .= Blockwheels_CSS::get_generate_css(
					$selector . ' .blockwheels-service-wrapper .wp-block-icon',
					$attributes['imageRadius'],
					'border-radius'
				);
			}
			// Padding
			if ( isset( $attributes['iconPadding'] ) ) {
				$inline_css .= Blockwheels_CSS::get_generate_css(
					$selector . ' .blockwheels-service-wrapper',
					$attributes['iconPadding'],
					'--service-icon-padding'
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
					$selector . ' .blockwheels-service-wrapper .wp-block-content h4',
					esc_html($all_fonts[$attributes['nameFontFamily']]['name']),
					'font-family'
				);
			}
			// Font Weight
			if ( isset( $attributes['nameFontWeight'] ) ) {
				$inline_css .= Blockwheels_CSS::get_generate_css(
					$selector . ' .blockwheels-service-wrapper .wp-block-content h4',
					$attributes['nameFontWeight'],
					'font-weight'
				);
			}
			// Font Style
			if ( isset( $attributes['nameFontStyle'] ) ) {
				$inline_css .= Blockwheels_CSS::get_generate_css(
					$selector . ' .blockwheels-service-wrapper .wp-block-content h4',
					$attributes['nameFontStyle'],
					'font-style'
				);
			}
			// Text Transform
			if ( isset( $attributes['nameLetterCase'] ) ) {
				$inline_css .= Blockwheels_CSS::get_generate_css(
					$selector . ' .blockwheels-service-wrapper .wp-block-content h4',
					$attributes['nameLetterCase'],
					'text-transform'
				);
			}
			// Font Size
			if ( isset( $attributes['nameFontSize'] ) ) {
				$inline_css .= Blockwheels_CSS::get_range_css(
					$selector . ' .blockwheels-service-wrapper .wp-block-content h4',
					$attributes['nameFontSize'],
					'font-size'
				);
			}
			// Color
			if ( isset( $attributes['nameFontColor'] ) ) {
				$inline_css .= Blockwheels_CSS::get_color_css(
					$selector . ' .blockwheels-service-wrapper .wp-block-content h4',
					$attributes['nameFontColor'],
					'color'
				);
			}

			// Description
			// Font Family
			if ( isset( $attributes['descFontFamily'] ) && $attributes['descFontFamily'] !== 'system' ) {
				$all_fonts	= Blockwheels_Google_Fonts::get_fonts();
				if ( array_key_exists( $attributes['descFontFamily'], $all_fonts ) ) {
					$fonts[$attributes['descFontFamily']] = map_deep( wp_unslash( $all_fonts[$attributes['descFontFamily']] ), 'sanitize_text_field' );
				}
				$inline_css .= Blockwheels_CSS::get_generate_css(
					$selector . ' .blockwheels-service-wrapper .wp-block-content > :where(span, p)',
					esc_html($all_fonts[$attributes['descFontFamily']]['name']),
					'font-family'
				);
			}
			// Font Weight
			if ( isset( $attributes['descFontWeight'] ) ) {
				$inline_css .= Blockwheels_CSS::get_generate_css(
					$selector . ' .blockwheels-service-wrapper .wp-block-content > :where(span, p)',
					$attributes['descFontWeight'],
					'font-weight'
				);
			}
			// Font Style
			if ( isset( $attributes['descFontStyle'] ) ) {
				$inline_css .= Blockwheels_CSS::get_generate_css(
					$selector . ' .blockwheels-service-wrapper .wp-block-content > :where(span, p)',
					$attributes['descFontStyle'],
					'font-style'
				);
			}
			// Text Transform
			if ( isset( $attributes['descLetterCase'] ) ) {
				$inline_css .= Blockwheels_CSS::get_generate_css(
					$selector . ' .blockwheels-service-wrapper .wp-block-content > :where(span, p)',
					$attributes['descLetterCase'],
					'text-transform'
				);
			}
			// Font Size
			if ( isset( $attributes['descFontSize'] ) ) {
				$inline_css .= Blockwheels_CSS::get_range_css(
					$selector . ' .blockwheels-service-wrapper .wp-block-content > :where(span, p)',
					$attributes['descFontSize'],
					'font-size'
				);
			}
			// Color
			if ( isset( $attributes['descFontColor'] ) ) {
				$inline_css .= Blockwheels_CSS::get_color_css(
					$selector . ' .blockwheels-service-wrapper .wp-block-content > :where(span, p)',
					$attributes['descFontColor'],
					'color'
				);
			}

			// Buttons
			// Border Radius
			if ( isset( $attributes['buttonBorderRadius'] ) ) {
				$inline_css .= Blockwheels_CSS::get_generate_css(
					$selector . ' .blockwheels-service-wrapper .blockwheels-service-buttons ul li a',
					$attributes['buttonBorderRadius'],
					'border-radius'
				);
			}
			
			// Color
			if ( isset( $attributes['buttonFontColor'] ) ) {
				$inline_css .= Blockwheels_CSS::get_color_css(
					$selector . ' .blockwheels-service-wrapper .blockwheels-service-buttons ul li a',
					$attributes['buttonFontColor'],
					'color'
				);
			}
			// Background Color
			if ( isset( $attributes['buttonBgColor'] ) ) {
				$inline_css .= Blockwheels_CSS::get_color_css(
					$selector . ' .blockwheels-service-wrapper .blockwheels-service-buttons ul li a',
					$attributes['buttonBgColor'],
					'background-color'
				);
			}
			
			// Font Family
			if ( isset( $attributes['buttonFontFamily'] ) && $attributes['buttonFontFamily'] !== 'system' ) {
				$all_fonts	= Blockwheels_Google_Fonts::get_fonts();
				if ( array_key_exists( $attributes['buttonFontFamily'], $all_fonts ) ) {
					$fonts[$attributes['buttonFontFamily']] = map_deep( wp_unslash( $all_fonts[$attributes['buttonFontFamily']] ), 'sanitize_text_field' );
				}
				$inline_css .= Blockwheels_CSS::get_generate_css(
					$selector . ' .blockwheels-service-wrapper .blockwheels-service-buttons ul li a',
					esc_html($all_fonts[$attributes['buttonFontFamily']]['name']),
					'font-family'
				);
			}
			// Font Weight
			if ( isset( $attributes['buttonFontWeight'] ) ) {
				$inline_css .= Blockwheels_CSS::get_generate_css(
					$selector . ' .blockwheels-service-wrapper .blockwheels-service-buttons ul li a',
					$attributes['buttonFontWeight'],
					'font-weight'
				);
			}
			// Font Style
			if ( isset( $attributes['buttonFontStyle'] ) ) {
				$inline_css .= Blockwheels_CSS::get_generate_css(
					$selector . ' .blockwheels-service-wrapper .blockwheels-service-buttons ul li a',
					$attributes['buttonFontStyle'],
					'font-style'
				);
			}
			// Text Transform
			if ( isset( $attributes['buttonLetterCase'] ) ) {
				$inline_css .= Blockwheels_CSS::get_generate_css(
					$selector . ' .blockwheels-service-wrapper .blockwheels-service-buttons ul li a',
					$attributes['buttonLetterCase'],
					'text-transform'
				);
			}
			// Font Size
			if ( isset( $attributes['buttonFontSize'] ) ) {
				$inline_css .= Blockwheels_CSS::get_range_css(
					$selector . ' .blockwheels-service-wrapper .blockwheels-service-buttons ul li a',
					$attributes['buttonFontSize'],
					'font-size'
				);
			}
			
			// Padding
			if ( isset( $attributes['buttonPadding'] ) ) {
				$inline_css .= Blockwheels_CSS::get_dimensions_css(
					$selector . ' .blockwheels-service-wrapper .blockwheels-service-buttons ul li a',
					$attributes['buttonPadding'],
					'padding'
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

if ( ! function_exists( 'blockwheels_register_services' ) ) {

	function blockwheels_register_services() {

		$block_name = 'services';
		$asset_config_file 	= sprintf( '%s/build/blocks/'. $block_name .'/index.asset.php', BLOCKWHEELS_PATH );
		if ( ! file_exists( $asset_config_file ) ) {
			return;
		}
	
		register_block_type(
			BLOCKWHEELS_PATH . 'build/blocks/' . $block_name . '/block.json',
			array(
				'render_callback' 	=> 'blockwheels_render_services'
			)
		);
	}
}
add_action( 'init', 'blockwheels_register_services' );