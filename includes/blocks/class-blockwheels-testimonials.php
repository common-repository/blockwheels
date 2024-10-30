<?php
/**
 * Registers all block assets so that they can be enqueued through the block editor
 * in the corresponding context.
 *
 * @see https://developer.wordpress.org/block-editor/tutorials/block-tutorial/applying-styles-with-stylesheets/
 */
if ( ! function_exists( 'blockwheels_render_testimonials' ) ) {

	function blockwheels_render_testimonials( $attributes, $content, $props ) {

		if ( isset( $attributes['blockId'] ) ) {

			$block_id 	= sanitize_text_field( $attributes['blockId'] );
			// now we generate the styles for wp_add_inline_style
			$inline_css = '';
			$fonts		= [];
			$selector 	= '.blockwheels-section[data-id="' . esc_attr( $block_id ) . '"]';
			$block_name = 'testimonials';
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
			// Border Radius
			if ( isset( $attributes['colBorderRadius'] ) ) {
				$inline_css .= Blockwheels_CSS::get_generate_css(
					$selector . ' .blockwheels-testimonial-wrapper',
					$attributes['colBorderRadius'],
					'border-radius'
				);
			}
			// Padding
			if ( isset( $attributes['colPadding'] ) ) {
				$inline_css .= Blockwheels_CSS::get_dimensions_css(
					$selector . ' .blockwheels-testimonial-wrapper',
					$attributes['colPadding'],
					'padding'
				);
			}
			// Background color
			if ( isset( $attributes['backgroundColor'] ) ) {
				$inline_css .= Blockwheels_CSS::get_color_css(
					$selector . ' .blockwheels-testimonial-wrapper',
					$attributes['backgroundColor'],
					'background-color'
				);
			}
			// Background gradient
			if ( isset( $attributes['backgroundGradient'] ) ) {
				$inline_css .= Blockwheels_CSS::get_color_css(
					$selector . ' .blockwheels-testimonial-wrapper',
					$attributes['backgroundGradient'],
					'background-image'
				);
			}
			// CONTENT
			// Padding
			if ( isset( $attributes['contentPadding'] ) ) {
				$inline_css .= Blockwheels_CSS::get_dimensions_css(
					$selector . ' .blockwheels-testimonial-wrapper .wp-block-content',
					$attributes['contentPadding'],
					'padding'
				);
			}
			// IMAGE
			if ( isset( $attributes['enableImage'] ) ) {
				// Border Radius
				if ( isset( $attributes['imageRadius'] ) ) {
					$inline_css .= Blockwheels_CSS::get_generate_css(
						$selector . ' .blockwheels-testimonial-wrapper .wp-block-content .user-details-wrap .wp-block-image',
						$attributes['imageRadius'],
						'border-radius'
					);
				}
			}
			// Quotes
			if ( isset( $attributes['enableQuotes'] ) ) {
				// Font Color
				if ( isset( $attributes['quotesColor'] ) ) {
					$inline_css .= Blockwheels_CSS::get_color_css(
						$selector . ' .blockwheels-testimonial-wrapper .wp-block-content .wp-block-quotes',
						$attributes['quotesColor'],
						'color'
					);
					$inline_css .= Blockwheels_CSS::get_color_css(
						$selector . ' .blockwheels-testimonial-wrapper .wp-block-content .wp-block-quotes-rating-wrap .lines',
						$attributes['quotesColor'],
						'background-color'
					);
				}
			}
			// NAME
			if ( isset( $attributes['enableName'] ) ) {
				// Font Family
				if ( isset( $attributes['nameFontFamily'] ) && $attributes['nameFontFamily'] !== 'system' ) {
					$all_fonts	= Blockwheels_Google_Fonts::get_fonts();
					if ( array_key_exists( $attributes['nameFontFamily'], $all_fonts ) ) {
						$fonts[$attributes['nameFontFamily']] = map_deep( wp_unslash( $all_fonts[$attributes['nameFontFamily']] ), 'sanitize_text_field' );
					}
					$inline_css .= Blockwheels_CSS::get_generate_css(
						$selector . ' .blockwheels-testimonial-wrapper .wp-block-content h6',
						esc_html($all_fonts[$attributes['nameFontFamily']]['name']),
						'font-family'
					);
				}
				// Font Weight
				if ( isset( $attributes['nameFontWeight'] ) ) {
					$inline_css .= Blockwheels_CSS::get_generate_css(
						$selector . ' .blockwheels-testimonial-wrapper .wp-block-content h6',
						$attributes['nameFontWeight'],
						'font-weight'
					);
				}
				// Font Style
				if ( isset( $attributes['nameFontStyle'] ) ) {
					$inline_css .= Blockwheels_CSS::get_generate_css(
						$selector . ' .blockwheels-testimonial-wrapper .wp-block-content h6',
						$attributes['nameFontStyle'],
						'font-style'
					);
				}
				// Text Transform
				if ( isset( $attributes['nameLetterCase'] ) ) {
					$inline_css .= Blockwheels_CSS::get_generate_css(
						$selector . ' .blockwheels-testimonial-wrapper .wp-block-content h6',
						$attributes['nameLetterCase'],
						'text-transform'
					);
				}
				// Font Size
				if ( isset( $attributes['nameFontSize'] ) ) {
					$inline_css .= Blockwheels_CSS::get_range_css(
						$selector . ' .blockwheels-testimonial-wrapper .wp-block-content h6',
						$attributes['nameFontSize'],
						'font-size'
					);
				}
				// Color
				if ( isset( $attributes['nameFontColor'] ) ) {
					$inline_css .= Blockwheels_CSS::get_color_css(
						$selector . ' .blockwheels-testimonial-wrapper .wp-block-content h6',
						$attributes['nameFontColor'],
						'color'
					);
				}
			}
			// DESCRIPTION OR DESIGNATION
			if ( isset( $attributes['enableDescription'] ) || isset( $attributes['enableDesignation'] ) ) {
				// Font Family
				if ( isset( $attributes['descriptionFontFamily'] ) && $attributes['descriptionFontFamily'] !== 'system' ) {
					$all_fonts	= Blockwheels_Google_Fonts::get_fonts();
					if ( array_key_exists( $attributes['descriptionFontFamily'], $all_fonts ) ) {
						$fonts[$attributes['descriptionFontFamily']] = map_deep( wp_unslash( $all_fonts[$attributes['descriptionFontFamily']] ), 'sanitize_text_field' );
					}
					$inline_css .= Blockwheels_CSS::get_generate_css(
						$selector . ' .blockwheels-testimonial-wrapper .wp-block-content p',
						esc_html($all_fonts[$attributes['descriptionFontFamily']]['name']),
						'font-family'
					);
				}
				// Font Weight
				if ( isset( $attributes['descriptionFontWeight'] ) ) {
					$inline_css .= Blockwheels_CSS::get_generate_css(
						$selector . ' .blockwheels-testimonial-wrapper .wp-block-content p',
						$attributes['descriptionFontWeight'],
						'font-weight'
					);
				}
				// Font Style
				if ( isset( $attributes['descriptionFontStyle'] ) ) {
					$inline_css .= Blockwheels_CSS::get_generate_css(
						$selector . ' .blockwheels-testimonial-wrapper .wp-block-content p',
						$attributes['descriptionFontStyle'],
						'font-style'
					);
				}
				// Text Transform
				if ( isset( $attributes['descriptionLetterCase'] ) ) {
					$inline_css .= Blockwheels_CSS::get_generate_css(
						$selector . ' .blockwheels-testimonial-wrapper .wp-block-content p',
						$attributes['descriptionLetterCase'],
						'text-transform'
					);
				}
				// Font Size
				if ( isset( $attributes['descriptionFontSize'] ) ) {
					$inline_css .= Blockwheels_CSS::get_range_css(
						$selector . ' .blockwheels-testimonial-wrapper .wp-block-content p',
						$attributes['descriptionFontSize'],
						'font-size'
					);
				}
				// Color
				if ( isset( $attributes['descriptionFontColor'] ) ) {
					$inline_css .= Blockwheels_CSS::get_color_css(
						$selector . ' .blockwheels-testimonial-wrapper .wp-block-content p',
						$attributes['descriptionFontColor'],
						'color'
					);
				}
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

if ( ! function_exists( 'blockwheels_register_testimonials' ) ) {

	function blockwheels_register_testimonials() {

		$block_name = 'testimonials';
		$asset_config_file 	= sprintf( '%s/build/blocks/'. $block_name .'/index.asset.php', BLOCKWHEELS_PATH );
		if ( ! file_exists( $asset_config_file ) ) {
			return;
		}
	
		register_block_type(
			BLOCKWHEELS_PATH . 'build/blocks/' . $block_name . '/block.json',
			array(
				'render_callback' 	=> 'blockwheels_render_testimonials'
			)
		);
	}
}
add_action( 'init', 'blockwheels_register_testimonials' );
