<?php
/**
 * Registers all block assets so that they can be enqueued through the block editor
 * in the corresponding context.
 *
 * @see https://developer.wordpress.org/block-editor/tutorials/block-tutorial/applying-styles-with-stylesheets/
 */
if ( ! function_exists( 'blockwheels_render_accordion' ) ) {

	function blockwheels_render_accordion( $attributes, $content ) {

		if ( isset( $attributes['blockId'] ) ) {

			$block_id 	= sanitize_text_field( $attributes['blockId'] );
			// now we generate the styles for wp_add_inline_style
			$inline_css = '';
			$fonts		= [];
			$selector 	= '.blockwheels-section[data-id="' . esc_attr( $block_id ) . '"]';
			$block_name = 'accordion';
			$handle 	= 'blockwheels-' . $block_name . '-style';
			
			// Padding
			if ( isset( $attributes['padding'] ) ) {
				$inline_css .= Blockwheels_CSS::get_dimensions_css(
					$selector,
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
	
			// Title
			if ( isset( $attributes['titleFontFamily'] ) && $attributes['titleFontFamily'] !== 'system' ) {
				$all_fonts	= Blockwheels_Google_Fonts::get_fonts();
				if ( array_key_exists( $attributes['titleFontFamily'], $all_fonts ) ) {
					$fonts[$attributes['titleFontFamily']] = map_deep( wp_unslash( $all_fonts[$attributes['titleFontFamily']] ), 'sanitize_text_field' );
				}
				$inline_css .= Blockwheels_CSS::get_generate_css(
					$selector . ' .wp-block-blockwheels-accordion-panel .blockwheels-accordion-title-wrap',
					$all_fonts[$attributes['titleFontFamily']]['name'],
					'font-family'
				);
			}
			if ( isset( $attributes['titleFontWeight'] ) ) {
				$inline_css .= Blockwheels_CSS::get_generate_css(
					$selector . ' .wp-block-blockwheels-accordion-panel .blockwheels-accordion-title-wrap',
					$attributes['titleFontWeight'],
					'font-weight'
				);
			}
			if ( isset( $attributes['titleFontStyle'] ) ) {
				$inline_css .= Blockwheels_CSS::get_generate_css(
					$selector . ' .wp-block-blockwheels-accordion-panel .blockwheels-accordion-title-wrap',
					$attributes['titleFontStyle'],
					'font-style'
				);
			}
			if ( isset( $attributes['titleFontSize'] ) ) {
				$inline_css .= Blockwheels_CSS::get_range_css(
					$selector . ' .wp-block-blockwheels-accordion-panel .blockwheels-accordion-title-wrap > *',
					$attributes['titleFontSize'],
					'font-size'
				);
				$inline_css .= Blockwheels_CSS::get_range_css(
					$selector . ' .wp-block-blockwheels-accordion-panel .blockwheels-accordion-title-wrap .blockwheels-accordion-title-label',
					$attributes['titleFontSize'],
					'font-size'
				);
			}
			if ( isset( $attributes['titleFontColor'] ) ) {
				$inline_css .= Blockwheels_CSS::get_color_css(
					$selector . ' .wp-block-blockwheels-accordion-panel .blockwheels-accordion-title-wrap > *',
					$attributes['titleFontColor'],
					'color'
				);
			}
			if ( isset( $attributes['titleBgColor'] ) ) {
				$inline_css .= Blockwheels_CSS::get_color_css(
					$selector . ' .wp-block-blockwheels-accordion-panel .blockwheels-accordion-title-wrap',
					$attributes['titleBgColor'],
					'background-color'
				);
			}
			if ( isset( $attributes['titlePadding'] ) ) {
				$inline_css .= Blockwheels_CSS::get_dimensions_css(
					$selector . ' .wp-block-blockwheels-accordion-panel .blockwheels-accordion-title-wrap',
					$attributes['titlePadding'],
					'padding'
				);
			}
			if ( isset( $attributes['titleLetterCase'] ) ) {
                $inline_css .= Blockwheels_CSS::get_generate_css(
                    $selector . ' .wp-block-blockwheels-accordion-panel .blockwheels-accordion-title-wrap',
                    $attributes['titleLetterCase'],
                    'text-transform'
                );
            }
			
			// Description
			if ( isset( $attributes['descPadding'] ) ) {
				$inline_css .= Blockwheels_CSS::get_dimensions_css(
					$selector . ' .wp-block-blockwheels-accordion-panel .blockwheels-accordion-content',
					$attributes['descPadding'],
					'padding'
				);
			}
			if ( isset( $attributes['descFontColor'] ) ) {
				$inline_css .= Blockwheels_CSS::get_color_css(
					$selector . ' .wp-block-blockwheels-accordion-panel .blockwheels-accordion-content',
					$attributes['descFontColor'],
					'color'
				);
			}
			if ( isset( $attributes['descBgColor'] ) ) {
				$inline_css .= Blockwheels_CSS::get_color_css(
					$selector . ' .wp-block-blockwheels-accordion-panel .blockwheels-accordion-content',
					$attributes['descBgColor'],
					'background-color'
				);
			}
			if ( isset( $attributes['descFontFamily'] ) && $attributes['descFontFamily'] !== 'system' ) {
				$all_fonts	= Blockwheels_Google_Fonts::get_fonts();
				if ( array_key_exists( $attributes['descFontFamily'], $all_fonts ) ) {
					$fonts[$attributes['descFontFamily']] = map_deep( wp_unslash( $all_fonts[$attributes['descFontFamily']] ), 'sanitize_text_field' );
				}
				$inline_css .= Blockwheels_CSS::get_generate_css(
					$selector . ' .wp-block-blockwheels-accordion-panel .blockwheels-accordion-content',
					$all_fonts[$attributes['descFontFamily']]['name'],
					'font-family'
				);
			}
			if ( isset( $attributes['descFontWeight'] ) ) {
				$inline_css .= Blockwheels_CSS::get_generate_css(
					$selector . ' .wp-block-blockwheels-accordion-panel .blockwheels-accordion-content',
					$attributes['descFontWeight'],
					'font-weight'
				);
			}
			if ( isset( $attributes['descFontStyle'] ) ) {
				$inline_css .= Blockwheels_CSS::get_generate_css(
					$selector . ' .wp-block-blockwheels-accordion-panel .blockwheels-accordion-content',
					$attributes['descFontStyle'],
					'font-style'
				);
			}
			if ( isset( $attributes['descFontSize'] ) ) {
				$inline_css .= Blockwheels_CSS::get_range_css(
					$selector . ' .wp-block-blockwheels-accordion-panel .blockwheels-accordion-content',
					$attributes['descFontSize'],
					'font-size'
				);
			}
			if ( isset( $attributes['descLetterCase'] ) ) {
                $inline_css .= Blockwheels_CSS::get_generate_css(
                    $selector . ' .wp-block-blockwheels-accordion-panel .blockwheels-accordion-content',
                    $attributes['descLetterCase'],
                    'text-transform'
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

if ( ! function_exists( 'blockwheels_register_accordion' ) ) {

	function blockwheels_register_accordion() {

		$block_name = 'accordion';
		$asset_config_file 	= sprintf( '%s/build/blocks/'. $block_name .'/index.asset.php', BLOCKWHEELS_PATH );
		if ( ! file_exists( $asset_config_file ) ) {
			return;
		}
	
		register_block_type(
			BLOCKWHEELS_PATH . 'build/blocks/' . $block_name . '/block.json',
			array(
				'render_callback' 	=> 'blockwheels_render_accordion'
			)
		);
	}
}
add_action( 'init', 'blockwheels_register_accordion' );