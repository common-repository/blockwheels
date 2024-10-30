<?php
/**
 * Render an SVG given a key.
 *
 * @since   2.4.0
 * @package Kadence Blocks
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class to Enqueue CSS/JS of all the blocks.
 *
 * @category class
 */
class Blockwheels_Svg_Render {

	/**
	 * Instance of this class
	 *
	 * @var null
	 */
	private static $instance = null;

	/**
	 * All SVG Icons
	 *
	 * @var null
	 */
	private static $all_icons = null;

	/**
	 * Instance Control
	 */
	public static function get_instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}
	/**
	 * Class Constructor.
	 */
	public function __construct() {
		
		if ( apply_filters( 'blockwheels_blocks_fix_svg_dimensions', false ) ) {
			add_filter( 'wp_get_attachment_image_src', array( $this, 'fix_wp_get_attachment_image_svg' ), 10, 4 );
		}
	}
	
	/**
	 *  Return or echo an SVG icon matching the provided key
	 *
	 * @param $name
	 * @param $fill
	 * @param $stroke_width
	 * @param $title
	 * @param $hidden
	 * @param $extras string Escape any attributes passed to this
	 * @param $echo
	 *
	 * @return string|void
	 */
	public static function render( $name, $fill = 'none', $stroke_width = false, $title = '', $hidden = true, $extras = '', $echo = false ) {

		if ( null === self::$all_icons ) {
			self::$all_icons = self::get_icons();
		}

		$svg = '';
		if ( 'fa_facebook' === $name ) {
			$name = 'fa_facebook-n';
		}
		if ( ! empty( self::$all_icons[ $name ] ) ) {
			$icon = self::$all_icons[ $name ];
			$vb = ( ! empty( $icon['vB'] ) ? $icon['vB'] : '0 0 24 24' );
			$preserve = '';
			$vb_array = explode( ' ', $vb );
			$typeL = substr( $name, 0, 3 );

			// This is added because some people upload icons that have negative values in the viewbox which cause part of the icons to get cut off unless this is added.
			if ( $typeL && 'fas' !== $typeL && 'fe_' !== $typeL && 'ic_' !== $typeL && ( ( isset( $vb_array[0] ) && absint( $vb_array[0] ) > 0 ) || ( isset( $vb_array[1] ) && absint( $vb_array[1] ) > 0 ) ) ) {
				$preserve = 'preserveAspectRatio="xMinYMin meet"';
			}
			$svg .= '<svg viewBox="' . $vb . '" height="24" width="24" ' . $preserve . ' fill="' . esc_attr( $fill ) . '"' . ( ! empty( $stroke_width ) ? ' stroke="currentColor" stroke-width="' . esc_attr( $stroke_width ) . '" stroke-linecap="round" stroke-linejoin="round"' : '' ) . ' xmlns="http://www.w3.org/2000/svg" ' . ( ! empty( $extras ) ? ' ' . $extras : '' ) . ( $hidden ? ' aria-hidden="true"' : ' role="img"' ) . '>';
			if ( ! empty( $title ) ) {
				$svg .= '<title>' . $title . '</title>';
			}
			if ( ! empty( $icon['cD'] ) ) {
				foreach ( $icon['cD'] as $cd ) {
					$nE      = $cd['nE'];
					$aBs     = $cd['aBs'];
					$tmpAttr = array();

					foreach ( $aBs as $key => $attribute ) {
						if ( ! in_array( $key, array( 'fill', 'stroke', 'none' ) ) ) {
							$tmpAttr[ $key ] = $key . '="' . $attribute . '"';
						}
					}

					if ( isset( $aBs['fill'], $aBs['stroke'] ) && $aBs['fill'] === 'none' ) {
						$tmpAttr['stroke'] = 'stroke="currentColor"';
					}

					$svg .= '<' . $nE . ' ' . implode( ' ', $tmpAttr ) . '/>';
				}
			}

			$svg .= '</svg>';

		}

		if ( $echo ) {
			echo $svg;

			return;
		}

		return $svg;

	}
	/**
	 * Return an array of icons.
	 *
	 * @return array();
	 */
	private static function get_icons() {
		$ico   = include BLOCKWHEELS_PATH . 'includes/icons-ico-array.php';
		$faico = include BLOCKWHEELS_PATH . 'includes/icons-array.php';

		return apply_filters( 'blockwheels_svg_icons', array_merge( $ico, $faico ) );
	}

	/**
	 * Fix an issue where wp_get_attachment_source returns non-values for width and height on svg's
	 *
	 * @param string  $image the image retrieved.
	 * @param boolean $attachment_id The attachment id.
	 * @param boolean $size The size request.
	 * @param boolean $icon If it was requested as an icon.
	 *
	 * @return array|boolean
	 */
	public function fix_wp_get_attachment_image_svg( $image, $attachment_id, $size, $icon ) {
		// If the image requested is an svg and the width is unset (1 or less in this case).
		if ( is_array( $image ) && preg_match( '/\.svg$/i', $image[0] ) && $image[1] <= 1 ) {
			// Use the requested size's dimensions first if available.
			if ( is_array( $size ) ) {
				$image[1] = $size[0];
				$image[2] = $size[1];
			} elseif ( ini_get( 'allow_url_fopen' ) && ( $xml = simplexml_load_file( $image[0], SimpleXMLElement::class, LIBXML_NOWARNING ) ) !== false ) {
				$attr = $xml->attributes();
				$viewbox = explode( ' ', $attr->viewBox );
				$image[1] = isset( $attr->width ) && preg_match( '/\d+/', $attr->width, $value ) ? (int) $value[0] : ( count( $viewbox ) == 4 ? (int) $viewbox[2] : null );
				$image[2] = isset( $attr->height ) && preg_match( '/\d+/', $attr->height, $value ) ? (int) $value[0] : ( count( $viewbox ) == 4 ? (int) $viewbox[3] : null );
			} else {
				$image[1] = null;
				$image[2] = null;
			}
		}
		return $image;
	}

}

Blockwheels_Svg_Render::get_instance();
