<?php
/**
 * Google fonts utilities
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

/**
 * class for google fonts utilities
 *
 * @access public
 */
class Blockwheels_Google_Fonts {
    
    /**
     * Main Instance
     *
     * Insures that only one instance of Blockwheels_Google_Fonts exists in memory at any one
     * time. Also prevents needing to define globals all over the place.
     *
     * @since    1.0.0
     * @access   public
     *
     * @return object
     */
    public static function instance() {

        // Store the instance locally to avoid private static replication
        static $instance = null;

        // Only run these methods if they haven't been ran previously
        if ( null === $instance ) {
            $instance = new Blockwheels_Google_Fonts;
        }

        // Always return the instance
        return $instance;
    }

    /**
     *  Run functionality with hooks
     *
     * @since    1.0.0
     * @access   public
     *
     * @return void
     */
    public function run() {

       add_action( 'enqueue_block_assets', array( $this, 'enqueue_google_fonts' ), 180 );
    }

	/**
	 * Google Web Fonts
	 *
	 * @return array $webfonts
	 */
	public static function get_fonts() {

        $gfonts_path    = BLOCKWHEELS_PATH . 'includes/gfonts-array.php';
        $g_fonts        = file_exists( $gfonts_path ) ? include $gfonts_path : array();

        // Variable to hold fonts;
		$webfonts = [
            'inherit'    => [
                'name'  => esc_html__('System Font', 'blockwheels')
            ]
        ];

        if ( !empty( $g_fonts ) ) {

            foreach( $g_fonts['items'] as $item ) {
                // Font
                $name   = str_replace(' ', '+', $item['family']);
                // font url
                $url    = "https://fonts.googleapis.com/css?family={$name}:" . implode( ',', $item['variants'] );
        
                $w = [];
                $s = [];
                if ( isset( $item['variants'] ) ) {
                    foreach ($item['variants'] as $key => $variant) {
                        if( is_string($variant) && !preg_match('/\d/', $variant) ) {
                            $s[] = ( $variant == 'regular' ) ? 'normal' : 'italic';
                            if ( $variant == 'regular' ) {
                                $w[] = '400';
                            }
                        }
                        elseif( is_int($variant) || ctype_digit($variant) ) {
                            $w[] = $variant;
                        }
                    }
                    rsort($s);
                }
                
                // Create a font array containing it's properties and add it to the $webfonts array
                $attr = array(
                    'name'      => $item['family'],
                    'category'  => $item['category'],
                    'variants'  => $item['variants'],
                    'url'       => $url
                );
        
                if ( !empty($w) ) {
                    $attr['weights'] = $w;
                }
                if ( !empty($s) ) {
                    $attr['styles'] = $s;
                }
        
                // Add this font to the fonts array
                $id                 = strtolower( str_replace( ' ', '_', $item['family'] ) );
                $webfonts[ $id ]    = $attr;
            }

        }

		return $webfonts;
	}

	public function enqueue_google_fonts() {

        $selected_fonts = self::add_google_fonts();

        if ( empty( $selected_fonts ) ) {
            return;
        }
        foreach ( $selected_fonts as $key => $selected_font ) {
            wp_enqueue_style(
                'blockwheels-' . $key . '-google-font',
                blockwheels_get_webfont_url( $selected_font['url'] ),
                [],
                BLOCKWHEELS_VERSION
            );
        }
    }

    /**
     * Returns an array of added google fonts
     *
     * @static
     * @access public
     * @return array
     */
    public static function add_google_fonts() {

        $added_fonts        = [];
        $all_fonts          = self::get_fonts();

        /*--------------------------------------------------------------
        # Global Setting Page Typography
        --------------------------------------------------------------*/
        $options = blockwheels_get_options();
        // Heading
        if ( array_key_exists( 'heading_typo', $options ) && $options['heading_typo'] != 'inherit' ) {
            $font = esc_html( $options['heading_typo'] );
            if ( $font && array_key_exists( $font, $all_fonts ) ) {
                $added_fonts[$font] = map_deep( wp_unslash( $all_fonts[$font] ), 'sanitize_text_field' );
            }
        }
        // Text
        if ( array_key_exists( 'base_type', $options ) && $options['base_type'] != 'inherit' ) {
            $font = esc_html( $options['base_type'] );
            if ( $font && array_key_exists( $font, $all_fonts ) ) {
                $added_fonts[$font] = map_deep( wp_unslash( $all_fonts[$font] ), 'sanitize_text_field' );
            }
        }
        
        $added_fonts = apply_filters( 'blockwheels_added_fonts', $added_fonts );
        return $added_fonts;
    }
}

if ( ! function_exists( 'blockwheels_google_fonts' ) ) {

    function blockwheels_google_fonts() {

        return Blockwheels_Google_Fonts::instance();
    }
    blockwheels_google_fonts()->run();
}