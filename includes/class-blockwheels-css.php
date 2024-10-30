<?php
/**
 * Class for dynamic CSS output
 *
 * @link       https://wpwheels.com/
 * @since      1.0.0
 *
 * @package    Blockwheels
 * @subpackage Blockwheels/includes
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Blockwheels_CSS' ) ) {
	/**
	 * Blockwheels_CSS 
	 */
	Class Blockwheels_CSS {

		/**
		 * Instance
		 */		
		private static $instance;

		/**
		 * Initiator
		 */
		public static function get_instance() {
			if ( ! isset( self::$instance ) ) {
				self::$instance = new self;
			}
			return self::$instance;
		}

		/**
		 * Constructor
		 */
		public function __construct() {

			add_action( 'enqueue_block_assets', array( $this, 'print_styles' ), 99999 );			
		}

		/**
		 * Output all custom CSS
		 */
		public function output_css( $custom_css = false ) {
			global $post;

			$all_fonts = Blockwheels_Google_Fonts::get_fonts();
			$css = '';

			/*--------------------------------------------------------------
			# Global Setting Page Typography
			--------------------------------------------------------------*/
			$options = blockwheels_get_options();
			// Base
			if ( array_key_exists( 'base_type', $options ) && $options['base_type'] != 'inherit' ) {
				$font = esc_html( $options['base_type'] );
				if ( $font && array_key_exists( $font, $all_fonts ) ) {
					$css .= $this->get_generate_css(
						'.editor-styles-wrapper > *, body:not(.wp-admin) > *',
						esc_html( $all_fonts[$font]['name'] ),
						'font-family'
					);
				}
			}
			// Heading
			if ( array_key_exists( 'heading_typo', $options ) && $options['heading_typo'] != 'inherit' ) {
				$font = esc_html( $options['heading_typo'] );
				if ( $font && array_key_exists( $font, $all_fonts ) ) {
					$css .= $this->get_generate_css(
						'body :is(h1, h2, h3, h4, h5, h6),body .editor-styles-wrapper :is(h1, h2, h3, h4, h5, h6)',
						esc_html( $all_fonts[$font]['name'] ),
						'font-family'
					);
				}
			}
			
			//Filter the value
			$css = apply_filters( 'blockwheels_css_output', $css );

			if ( $custom_css || !is_customize_preview() ) {
				$css .= wp_get_custom_css();
			}

			$css = $this->minify( $css );
			
			return $css;
		}

		/**
		 * Print styles
		 */
		public function print_styles() {

			//wp_enqueue_style( 'blockwheels-public-style' );

			$css = $this->output_css();

			wp_add_inline_style( 'blockwheels-public-style', $css );
		}

		/**
		 * CSS code minification.
		 */
		public static function minify( $css ) {
			$css = preg_replace( '/\s+/', ' ', $css );
			$css = preg_replace( '/\/\*[^\!](.*?)\*\//', '', $css );
			$css = preg_replace( '/(,|:|;|\{|}) /', '$1', $css );
			$css = preg_replace( '/ (,|;|\{|})/', '$1', $css );
			$css = preg_replace( '/(:| )0\.([0-9]+)(%|em|ex|px|in|cm|mm|pt|pc)/i', '${1}.${2}${3}', $css );
			$css = preg_replace( '/(:| )(\.?)0(%|em|ex|px|in|cm|mm|pt|pc)/i', '${1}0', $css );

			return trim( $css );
		}

		/**
		 * Get generate CSS
		 */
		public static function get_generate_css( $selector, $values, $property = '', $prefix = '', $suffix = '' ) {
			
			$output = '';

			if ( ! $values || ! $selector ) {
				return;
			}

			$selector = is_array( $selector ) ? join( ',', $selector ) : $selector;

			if ( is_array( $values ) ) {
				$sm_css = '';
				$md_css = '';
				$lg_css = '';
				$media_query = ['@media screen and (max-width: 1023px)','@media screen and (max-width: 767px)'];

				// Desktop
				if ( !empty( $values['desktop'] ) ) {
					$lg_css .= $property . ':' . esc_attr( $prefix . $values['desktop'] . $suffix ) . ';';
				}
				// Tablet
				if ( !empty( $values['tablet'] ) ) {
					$md_css .= $property . ':' . esc_attr( $prefix . $values['tablet'] . $suffix ) . ';';
				}
				// Mobile
				if ( !empty( $values['mobile'] ) ) {
					$sm_css .= $property . ':' . esc_attr( $prefix . $values['mobile'] . $suffix ) . ';';
				}

				// Base CSS
				if ( $lg_css != '' ) {
					$output .= $selector . '{' . $lg_css . '}' . "\n";
				}
				// For md Device
				if ( $md_css != '' ) {
					$output .= $media_query[0] . '{' . $selector . '{' . $md_css . '}}' . "\n";
				}
				// For sm Device
				if ( $sm_css != '' ) {
					$output .= $media_query[1] . '{' . $selector . '{' . $sm_css . '}}' . "\n";
				}
			}
			else {
				$output .= $selector . '{' . $property . ':' . esc_attr( $prefix . $values . $suffix ) . ';}' . "\n";
			}

			// Output
			$output = ( '' != $output ) ? $output : '';

			return $output;
		}

		/**
		 * Get dimensions css
		 */
		public static function get_dimensions_css( $selector, $values, $property = 'padding' ) {
			
			$output = '';
			
			if ( ! $values || ! $selector ) {
				return;
			}
			$sm_css = '';
			$md_css = '';
			$lg_css = '';
			$media_query = ['@media screen and (max-width: 1023px)','@media screen and (max-width: 767px)'];

			$selector = is_array( $selector ) ? join( ',', $selector ) : $selector;

			// Desktop
			if ( !empty( $values['desktop'] ) ) {
				foreach ( $values['desktop'] as $key => $value ) {
					$lg_css .= $property . '-' . $key . ':' . esc_attr( $value ) . ' !important;';
				}
			}
			// Tablet
			if ( !empty( $values['tablet'] ) ) {
				foreach ( $values['tablet'] as $key => $value ) {
					$md_css .= $property . '-' . $key . ':' . esc_attr( $value ) . ' !important;';
				}
			}
			// Mobile
			if ( !empty( $values['mobile'] ) ) {
				foreach ( $values['mobile'] as $key => $value ) {
					$sm_css .= $property . '-' . $key . ':' . esc_attr( $value ) . ' !important;';
				}
			}

			// Base CSS
			if ( $lg_css != '' ) {
				$output .= $selector . '{' . $lg_css . '}' . "\n";
			}
			// For md Device
			if ( $md_css != '' ) {
				$output .= $media_query[0] . '{' . $selector . '{' . $md_css . '}}' . "\n";
			}
			// For sm Device
			if ( $sm_css != '' ) {
				$output .= $media_query[1] . '{' . $selector . '{' . $sm_css . '}}' . "\n";
			}
			
			// Output
			$output = ( '' != $output ) ? $output : '';

			return $output;
		}

		/**
		 * Get border css
		 */
		public static function get_border_css( $selector, $values ) {
			
			$output = '';
			
			if ( ! $values ) {
				return;
			}
			
			$selector = is_array( $selector ) ? join( ',', $selector ) : $selector;

			foreach( $values as $key => $val ) {
				$output .= 'border-' . $key . '-width:' . esc_attr( $val ) . ';';
			}

			// Output
			$output = ( '' != $output ) ? $selector . '{' . $output . '}' : '';

			return $output;
		}

		/**
		 * Get color css
		 */
		public static function get_color_css( $selector, $value, $property = 'color', $suffix = null ) {
			
			$output = '';

			if ( ! $value || ! $selector ) {
				return;
			}

			$selector = is_array( $selector ) ? join( ',', $selector ) : $selector;

			$output .= $selector . '{';
			$output .= $property . ':' . esc_attr( $value . $suffix ) . ';';
			$output .= '}' . "\n";

			return $output;
		}

		/**
		 * Get range css
		 */
		public static function get_range_css( $selector, $values, $property = 'font-size', $prefix = '', $suffix = '' ) {
			
			$output = '';

			if ( ! $values || ! $selector ) {
				return;
			}
			$sm_css = '';
			$md_css = '';
			$lg_css = '';
			$media_query = ['@media screen and (max-width: 1023px)','@media screen and (max-width: 767px)'];

			$selector = is_array( $selector ) ? join( ',', $selector ) : $selector;

			// Desktop
			if ( !empty( $values['desktop'] ) ) {
				$lg_css .= $property . ':' . esc_attr( $prefix . $values['desktop'] . $suffix ) . ';';
			}
			// Tablet
			if ( !empty( $values['tablet'] ) ) {
				$md_css .= $property . ':' . esc_attr( $prefix . $values['tablet'] . $suffix ) . ';';
			}
			// Mobile
			if ( !empty( $values['mobile'] ) ) {
				$sm_css .= $property . ':' . esc_attr( $prefix . $values['mobile'] . $suffix ) . ';';
			}

			// Base CSS
			if ( $lg_css != '' ) {
				$output .= $selector . '{' . $lg_css . '}' . "\n";
			}
			// For md Device
			if ( $md_css != '' ) {
				$output .= $media_query[0] . '{' . $selector . '{' . $md_css . '}}' . "\n";
			}
			// For sm Device
			if ( $sm_css != '' ) {
				$output .= $media_query[1] . '{' . $selector . '{' . $sm_css . '}}' . "\n";
			}
			
			// Output
			$output = ( '' != $output ) ? $output : '';

			return $output;
		}

		/**
		 * Get image position
		 */
		public static function get_focal_point_css( $selector, $values, $property = 'background-position', $prefix = '', $suffix = '' ) {
			
			$output = '';

			if ( ! $values || ! $selector ) {
				return;
			}
			$selector = is_array( $selector ) ? join( ',', $selector ) : $selector;

			// x
			if ( !empty( $values['x'] ) ) {
				$x = $values['x'] * 100;
				$output .= esc_attr( $prefix . $x . $suffix );
			}
			// y
			if ( !empty( $values['y'] ) ) {
				$y = $values['y'] * 100;
				$output .= ' ' . esc_attr( $prefix . $y . $suffix );
			}

			// Output
			$output = ( '' != $output ) ? $selector . '{' . $property . ':' . $output . '}' . "\n" : '';

			return $output;
		}
		
	}
}

/**
 * Initialize class
 */
Blockwheels_CSS::get_instance();