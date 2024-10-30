<?php
/**
 * Registers all block assets so that they can be enqueued through the block editor
 * in the corresponding context.
 *
 * @see https://developer.wordpress.org/block-editor/tutorials/block-tutorial/applying-styles-with-stylesheets/
 */
if ( ! function_exists( 'blockwheels_render_single_icon' ) ) {

	function blockwheels_render_single_icon( $attributes, $content ) {

		if ( isset( $attributes['blockId'] ) ) {

			$block_id 	= sanitize_text_field( $attributes['blockId'] );
			// now we generate the styles for wp_add_inline_style
			$inline_css = '';
			$fonts		= [];
			$selector 	= '.blockwheels-single-icon-wrapper[data-id="' . esc_attr( $block_id ) . '"]';
			$block_name = 'single-icon';
			$handle 	= 'blockwheels-' . $block_name . '-style';

			$section_classes = ['blockwheels-section'];
			if ( isset( $attributes['align'] ) ) {
				$section_classes[] = 'align'. sanitize_text_field( $attributes['align'] );
			}
			if ( isset( $attributes['className'] ) ) {
				$section_classes[] = sanitize_text_field( $attributes['className'] );
			}

			// Icon size
			if ( isset( $attributes['iconSize'] ) ) {
				$inline_css .= Blockwheels_CSS::get_range_css(
					$selector . ' .wp-block-icon svg',
					$attributes['iconSize'],
					'height'
				);
				$inline_css .= Blockwheels_CSS::get_range_css(
					$selector . ' .wp-block-icon svg',
					$attributes['iconSize'],
					'width'
				);
			}
			// Icon Bg Size
			if ( isset( $attributes['iconBgSize'] ) ) {
				$inline_css .= Blockwheels_CSS::get_range_css(
					$selector . ' .wp-block-icon ',
					$attributes['iconBgSize'],
					'padding'
				);
			}
			// Border Radius
			if ( isset( $attributes['iconBorderRadius'] ) ) {
				$inline_css .= Blockwheels_CSS::get_generate_css(
					$selector . ' .wp-block-icon',
					$attributes['iconBorderRadius'],
					'border-radius'
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
			if ( isset( $attributes['iconHoverColor'] ) ) {
				$inline_css .= Blockwheels_CSS::get_color_css(
					$selector . ' .wp-block-icon:hover svg',
					$attributes['iconHoverColor'],
					'color'
				);
			}
			// Icon Background color
			if ( isset( $attributes['iconBgColor'] ) ) {
				$inline_css .= Blockwheels_CSS::get_color_css(
					$selector . ' .wp-block-icon',
					$attributes['iconBgColor'],
					'background-color'
				);
			}
			if ( isset( $attributes['iconHoverBgColor'] ) ) {
				$inline_css .= Blockwheels_CSS::get_color_css(
					$selector . ' .wp-block-icon:hover',
					$attributes['iconHoverBgColor'],
					'background-color'
				);
			}
			// Icon Background gradient
			if ( isset( $attributes['iconBgGradient'] ) ) {
				$inline_css .= Blockwheels_CSS::get_color_css(
					$selector . ' .wp-block-icon',
					$attributes['iconBgGradient'],
					'background-image'
				);
			}
			if ( isset( $attributes['iconHoverBgGradient'] ) ) {
				$inline_css .= Blockwheels_CSS::get_color_css(
					$selector . ' .wp-block-icon:hover',
					$attributes['iconHoverBgGradient'],
					'background-image'
				);
			}
			// Icon Border color
			if ( isset( $attributes['iconBorderColor'] ) ) {
				$inline_css .= Blockwheels_CSS::get_color_css(
					$selector . ' .wp-block-icon',
					$attributes['iconBorderColor'],
					'border-color'
				);
			}
			if ( isset( $attributes['iconHoverBorderColor'] ) ) {
				$inline_css .= Blockwheels_CSS::get_color_css(
					$selector . ' .wp-block-icon:hover',
					$attributes['iconHoverBorderColor'],
					'border-color'
				);
			}

			if ( ! empty( $attributes['icon'] ) ) {

				if ( $inline_css !== '' ) {
					$css = Blockwheels_CSS::minify( $inline_css );
					wp_add_inline_style( $handle, $css );
				}
				
				$type		= substr( $attributes['icon'], 0, 2 );
				$line_icon 	= ( ! empty( $type ) && 'fe' == $type ? true : false );
				$fill        = ( $line_icon ? 'none' : 'currentColor' );
				$stroke_width = false;
				if ( $line_icon ) {
					$stroke_width = ( ! empty( $attributes['iconWidth'] ) ? $attributes['iconWidth'] : 2 );
				}
				$content .= '<div class="wp-block-blockwheels-single-icon blockwheels-single-icon-wrapper flex align-items-start" data-id="'.esc_attr($block_id).'">';
				$content .= '<div class="wp-block-icon">';
				$content .= Blockwheels_Svg_Render::render( $attributes['icon'], $fill, $stroke_width );
				$content .= '</div>';
				$content .= '</div>';
			}
			
			return $content;
		}
	}
}

if ( ! function_exists( 'blockwheels_register_single_icon' ) ) {

	function blockwheels_register_single_icon() {

		$block_name  		= 'single-icon';
		$asset_config_file 	= sprintf( '%s/build/blocks/icons/'. $block_name .'/index.asset.php', BLOCKWHEELS_PATH );
		if ( ! file_exists( $asset_config_file ) ) {
			return;
		}
	
		register_block_type(
			BLOCKWHEELS_PATH . 'build/blocks/icons/' . $block_name . '/block.json',
			array(
				'render_callback' 	=> 'blockwheels_render_single_icon'
			)
		);
	}
}
add_action( 'init', 'blockwheels_register_single_icon' );
