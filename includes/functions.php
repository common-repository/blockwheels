<?php
/**
 * Blockwheels Helper Functions
 *
 * @link       https://wpwheels.com/
 * @since      1.0.0
 *
 * @package    Blockwheels
 * @subpackage Blockwheels/includes
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Get the asset file produced by wp scripts.
 *
 * @param string $filepath the file path.
 * @return array
 */
if ( ! function_exists( 'blockwheels_get_asset_file' ) ) :
	function blockwheels_get_asset_file( $filepath ) {
		$asset_path = BLOCKWHEELS_PATH . $filepath . '.asset.php';
		return file_exists( $asset_path )
			? include $asset_path
			: array(
				'dependencies' => array( 'lodash', 'react', 'react-dom', 'wp-block-editor', 'wp-blocks', 'wp-data', 'wp-element', 'wp-i18n', 'wp-polyfill', 'wp-primitives', 'wp-api' ),
				'version'      => BLOCKWHEELS_VERSION,
			);
	}
endif;


/**
 * Get the Plugin Default Options.
 *
 * @since 1.0.0
 *
 * @param null
 *
 * @return array Default Options
 *
 * @author     wpwheels <info@wpwheels.com>
 *
 */
if ( ! function_exists( 'blockwheels_default_options' ) ) :
	function blockwheels_default_options() {
		$default_theme_options = array(
			'post_enable' 	=> true,
			'page_enable' 	=> true,
			'heading_typo' 	=> 'inherit',
			'base_type' 	=> 'inherit',
			'color_1' 		=> '#000000',
			'color_2' 		=> '#565656',
			'color_3' 		=> '#abb8c3',
			'color_4' 		=> '#f9f8f8',
			'color_5' 		=> '#ffffff'
		);

		return apply_filters( 'blockwheels_default_options', $default_theme_options );
	}
endif;

/**
 * Get the Plugin Saved Options.
 *
 * @since 1.0.0
 *
 * @param string $key optional option key
 *
 * @return mixed All Options Array Or Options Value
 *
 * @author     wpwheels <info@wpwheels.com>
 *
 */
if ( ! function_exists( 'blockwheels_get_options' ) ) :
	function blockwheels_get_options( $key = '' ) {
		$options         = get_option( 'blockwheels_options' );
		$default_options = blockwheels_default_options();

		if ( ! empty( $key ) ) {
			if ( isset( $options[ $key ] ) ) {
				return $options[ $key ];
			}
			return isset( $default_options[ $key ] ) ? $default_options[ $key ] : false;
		} else {
			if ( ! is_array( $options ) ) {
				$options = array();
			}
			return array_merge( $default_options, $options );
		}
	}
endif;

/**
 * Get an array of terms from a taxonomy.
 *
 * @param string|array $taxonomies See https://developer.wordpress.org/reference/functions/get_terms/ for details.
 * @return array
 */
if ( ! function_exists( 'blockwheels_get_terms' ) ) : 
	function blockwheels_get_terms( $taxonomies ) {
		$items = [];

		// Get the post types.
		$terms = get_terms( $taxonomies );

		// Build the array.
		foreach ( $terms as $term ) {
			$items[ $term->term_id ] = $term->name;
		}

		return $items;
	}
endif;

/**
 * Functionality for display content with characters or words with limitation
 *
 * @param int       $length content length
 * @param string    $end content suffix as null or string
 * @return  string
 */
if ( ! function_exists( 'blockwheels_get_content_limit' ) ) {

	function blockwheels_get_content_limit( $length = 40, $end = '...' ) {
		global $post;
		$end          = $end ? $end : '';
		$post_excerpt = $post->post_excerpt;

		if ( ! ( $post_excerpt ) ) {

			$post_excerpt = $post->post_content;
			$post_excerpt = preg_replace( '@\[caption[^\]]*?\].*?\[\/caption]@si', '', $post_excerpt );
			$post_excerpt = preg_replace( '@<script[^>]*?>.*?</script>@si', '', $post_excerpt );
			$post_excerpt = preg_replace( '@<style[^>]*?>.*?</style>@si', '', $post_excerpt );
			$post_excerpt = preg_replace( ' (\[.*?\])', '', $post_excerpt );
			$post_excerpt = strip_shortcodes( $post_excerpt );
			$post_excerpt = wp_strip_all_tags( $post_excerpt );
    	}

    	$post_excerpt = wp_trim_words( $post_excerpt, $length, $end );

    	return $post_excerpt;
    }
}

if ( ! function_exists( 'blockwheels_posted_categories' ) ) {
    /**
    * Prints HTML with meta information for the current categories.
    */
    function blockwheels_posted_categories( $extraClasses = null, $sept = null ) {

    // Hide category and tag text for pages.
    if ( 'post' === get_post_type() ) {
    $categories = get_the_category();
    if ( !empty( $categories ) ) {
    $classes = ['wp-block-term-links'];
    if ( $extraClasses ) {
    $classes[] = $extraClasses;
    }
    echo '<div class="'.esc_attr( implode( ' ', $classes ) ).'">';
        foreach ($categories as $key => $category) {
        echo '<a href="'.esc_url(get_category_link($category->term_id)).'">';
            echo esc_html($category->name);
            echo '</a>';
        if ( $sept ) {
        $aboveSymbol = '&#124;';
        if ( 'dash' == $sept ) {
        $aboveSymbol = '&#8208;';
        } else if ( 'slash' == $sept ) {
        $aboveSymbol = '&#47;';
        } else if ( 'dot' == $sept ) {
        $aboveSymbol = '&#183;';
        }
        if ( ( count($categories) - 1 ) !== $key ) {
        echo '<span>'.esc_html($aboveSymbol).'</span>';
        }
        }
        }
        echo '</div>';
    }
    }
    }
}