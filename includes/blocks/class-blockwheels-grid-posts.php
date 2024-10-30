<?php
/**
 * Registers all block assets so that they can be enqueued through the block editor
 * in the corresponding context.
 *
 * @see https://developer.wordpress.org/block-editor/tutorials/block-tutorial/applying-styles-with-stylesheets/
 */

if ( ! function_exists( 'blockwheels_render_grid_posts' ) ) {

	function blockwheels_render_grid_posts( $attributes, $content, $props ) {

		if ( isset( $attributes['blockId'] ) ) {
	
			global $post;
	
			$args = [
				'post_type' 			=> 'post',
				'no_found_rows' 		=> true,
				'ignore_sticky_posts' 	=> true,
				'suppress_filters' 		=> false,
				'post__not_in'     		=> array( $post->ID ),
				'post_status'      		=> 'publish'
			];
			// Posts Limit
			if ( $attributes['numberOfPosts'] ) {
				$args['posts_per_page'] = absint( $attributes['numberOfPosts'] );
			}
			// Order
			if ( $attributes['order'] ) {
				$args['order'] = sanitize_text_field( strtoupper($attributes['order']) );
			}
			// Order By
			if ( $attributes['orderBy'] ) {
				$args['orderby'] = sanitize_text_field( $attributes['orderBy'] );
			}
			$the_query = new WP_Query( $args );
	
			if ( $the_query->have_posts() ) :
				$block_id			= sanitize_text_field( $attributes['blockId'] );
				$block_name  		= 'grid-posts';
				$handle 			= 'blockwheels-' . $block_name . '-style';
				$section_classes 	= ['blockwheels-section'];
				if ( isset( $attributes['align'] ) ) {
					$section_classes[] = 'align'. sanitize_text_field( $attributes['align'] );
				}
				if ( isset( $attributes['className'] ) ) {
					$section_classes[] = sanitize_text_field( $attributes['className'] );
				}
				$image_ratio 		= ! empty( $attributes['imageRatio'] ) ? sanitize_text_field( $attributes['imageRatio'] ) : 'auto';
				$title_html_tag 	= ! empty( $attributes['titleHtmlTag'] ) ? sanitize_text_field( $attributes['titleHtmlTag'] ) : 'h2';
	
				$containerClasses 	= ['wp-block-blockwheels-grid-posts blockwheels-grid-posts-container grid gap image-ratio'];
				if( isset( $attributes['columns'] ) ) {
					$containerClasses[] = isset( $attributes['columns']['mobile'] ) ? 'sm-grid-cols-' . sanitize_text_field( $attributes['columns']['mobile'] ) : 'sm-grid-cols-1';
					$containerClasses[] = isset( $attributes['columns']['tablet'] ) ? 'md-grid-cols-' . sanitize_text_field( $attributes['columns']['tablet'] ) : 'md-grid-cols-2';
					$containerClasses[] = isset( $attributes['columns']['desktop'] ) ? 'grid-cols-' . sanitize_text_field( $attributes['columns']['desktop'] ) : 'grid-cols-3';
					$containerClasses[] = isset( $attributes['contentAlign']['mobile'] ) ? 'sm-text-' . sanitize_text_field( $attributes['contentAlign']['mobile'] ) : 'sm-text-left';
					$containerClasses[] = isset( $attributes['contentAlign']['tablet'] ) ? 'md-text-' . sanitize_text_field( $attributes['contentAlign']['tablet'] ) : 'md-text-left';
					$containerClasses[] = isset( $attributes['contentAlign']['desktop'] ) ? 'text-' . sanitize_text_field( $attributes['contentAlign']['desktop'] ) : 'text-left';
				}
				$imageClasses = ['wp-block-image'];
				if( isset( $attributes['imageHoverStyle'] ) ) {
					$imageClasses[] = 'effect_'.sanitize_text_field( $attributes['imageHoverStyle'] );
				}
	
	
				// now we generate the styles for wp_add_inline_style
				$inline_css 	= '';
				$fonts			= [];
				$selector 		= '.blockwheels-section[data-id="' . esc_attr( $block_id ) . '"]';
	
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
						$selector . ' .blockwheels-post-details',
						$attributes['colBorderRadius'],
						'border-radius'
					);
				}
				// Padding
				if ( isset( $attributes['colPadding'] ) ) {
					$inline_css .= Blockwheels_CSS::get_dimensions_css(
						$selector . ' .blockwheels-post-details',
						$attributes['colPadding'],
						'padding'
					);
				}
				// Background color
				if ( isset( $attributes['backgroundColor'] ) ) {
					$inline_css .= Blockwheels_CSS::get_color_css(
						$selector . ' .blockwheels-post-details',
						$attributes['backgroundColor'],
						'background-color'
					);
				}
				// Background gradient
				if ( isset( $attributes['backgroundGradient'] ) ) {
					$inline_css .= Blockwheels_CSS::get_color_css(
						$selector . ' .blockwheels-post-details',
						$attributes['backgroundGradient'],
						'background-image'
					);
				}
	
				// CONTENT
				// Padding
				if ( isset( $attributes['contentPadding'] ) ) {
					$inline_css .= Blockwheels_CSS::get_dimensions_css(
						$selector . ' .blockwheels-post-details .wp-block-content',
						$attributes['contentPadding'],
						'padding'
					);
				}
				// CATEGORIES
				if ( $attributes['enableCategory'] ) {
					// Font Family
					if ( isset( $attributes['categoriesFontFamily'] ) && $attributes['categoriesFontFamily'] !== 'system' ) {
						$all_fonts	= Blockwheels_Google_Fonts::get_fonts();
						if ( array_key_exists( $attributes['categoriesFontFamily'], $all_fonts ) ) {
							$fonts[$attributes['categoriesFontFamily']] = map_deep( wp_unslash( $all_fonts[$attributes['categoriesFontFamily']] ), 'sanitize_text_field' );
						}
						$inline_css .= Blockwheels_CSS::get_generate_css(
							$selector . ' .blockwheels-post-details .wp-block-content .wp-block-term-links a',
							esc_html($all_fonts[$attributes['categoriesFontFamily']]['name']),
							'font-family'
						);
					}
					// Font Weight
					if ( isset( $attributes['categoriesFontWeight'] ) ) {
						$inline_css .= Blockwheels_CSS::get_generate_css(
							$selector . ' .blockwheels-post-details .wp-block-content .wp-block-term-links a',
							$attributes['categoriesFontWeight'],
							'font-weight'
						);
					}
					// Font Style
					if ( isset( $attributes['categoriesFontStyle'] ) ) {
						$inline_css .= Blockwheels_CSS::get_generate_css(
							$selector . ' .blockwheels-post-details .wp-block-content .wp-block-term-links a',
							$attributes['categoriesFontStyle'],
							'font-style'
						);
					}
					// Text Transform
					if ( isset( $attributes['categoriesLetterCase'] ) ) {
						$inline_css .= Blockwheels_CSS::get_generate_css(
							$selector . ' .blockwheels-post-details .wp-block-content .wp-block-term-links a',
							$attributes['categoriesLetterCase'],
							'text-transform'
						);
					}
					// Font Size
					if ( isset( $attributes['categoriesFontSize'] ) ) {
						$inline_css .= Blockwheels_CSS::get_range_css(
							$selector . ' .blockwheels-post-details .wp-block-content .wp-block-term-links a',
							$attributes['categoriesFontSize'],
							'font-size'
						);
					}
					// Border Radius
                    if ( isset( $attributes['categoriesBorderRadius'] ) ) {
                        $inline_css .= Blockwheels_CSS::get_generate_css(
                            $selector . ' .blockwheels-grid-posts-container .blockwheels-post-details .wp-block-content .wp-block-term-links.category-style-boxed a',
                            $attributes['categoriesBorderRadius'],
                            'border-radius'
                        );
                    }
					// Font Color
					if ( isset( $attributes['categoriesFontColor'] ) ) {
						$inline_css .= Blockwheels_CSS::get_generate_css(
							$selector . ' .blockwheels-post-details .wp-block-content .wp-block-term-links a',
							$attributes['categoriesFontColor'],
							'color'
						);
					}
					// BG Color
					if ( isset( $attributes['categoriesBgColor'] ) ) {
						$inline_css .= Blockwheels_CSS::get_generate_css(
							$selector . ' .blockwheels-post-details .wp-block-content .wp-block-term-links.category-style-boxed a',
							$attributes['categoriesBgColor'],
							'background-color'
						);
						$inline_css .= Blockwheels_CSS::get_generate_css(
							$selector . ' .blockwheels-post-details .wp-block-content .wp-block-term-links.category-style-normal a',
							$attributes['categoriesBgColor'],
							'color'
						);
					}
					// Padding
					if ( isset( $attributes['categoriesPadding'] ) ) {
						$inline_css .= Blockwheels_CSS::get_dimensions_css(
							$selector . ' .blockwheels-post-details .wp-block-content .category-style-boxed a',
							$attributes['categoriesPadding'],
							'padding'
						);
					}
				}
				
				// POST TITLE
				// Font Family
				if ( isset( $attributes['titleFontFamily'] ) && $attributes['titleFontFamily'] !== 'system' ) {
					$all_fonts	= Blockwheels_Google_Fonts::get_fonts();
					if ( array_key_exists( $attributes['titleFontFamily'], $all_fonts ) ) {
						$fonts[$attributes['titleFontFamily']] = map_deep( wp_unslash( $all_fonts[$attributes['titleFontFamily']] ), 'sanitize_text_field' );
					}
					$inline_css .= Blockwheels_CSS::get_generate_css(
						$selector . ' .blockwheels-post-details .wp-block-content .wp-block-post-title',
						esc_html($all_fonts[$attributes['titleFontFamily']]['name']),
						'font-family'
					);
				}
				// Font Weight
				if ( isset( $attributes['titleFontWeight'] ) ) {
					$inline_css .= Blockwheels_CSS::get_generate_css(
						$selector . ' .blockwheels-post-details .wp-block-content .wp-block-post-title',
						$attributes['titleFontWeight'],
						'font-weight'
					);
				}
				// Font Style
				if ( isset( $attributes['titleFontStyle'] ) ) {
					$inline_css .= Blockwheels_CSS::get_generate_css(
						$selector . ' .blockwheels-post-details .wp-block-content .wp-block-post-title',
						$attributes['titleFontStyle'],
						'font-style'
					);
				}
				// Text Transform
				if ( isset( $attributes['titleLetterCase'] ) ) {
					$inline_css .= Blockwheels_CSS::get_generate_css(
						$selector . ' .blockwheels-post-details .wp-block-content .wp-block-post-title',
						$attributes['titleLetterCase'],
						'text-transform'
					);
				}
				// Font Size
				if ( isset( $attributes['titleFontSize'] ) ) {
					$inline_css .= Blockwheels_CSS::get_range_css(
						$selector . ' .blockwheels-post-details .wp-block-content .wp-block-post-title',
						$attributes['titleFontSize'],
						'font-size'
					);
				}
				// Color
				if ( isset( $attributes['titleFontColor'] ) ) {
					$inline_css .= Blockwheels_CSS::get_color_css(
						$selector . ' .blockwheels-post-details .wp-block-content .wp-block-post-title a',
						$attributes['titleFontColor'],
						'color'
					);
				}
				// Hover Color
				if ( isset( $attributes['titleHoverFontColor'] ) ) {
					$inline_css .= Blockwheels_CSS::get_color_css(
						$selector . ' .blockwheels-post-details .wp-block-content .wp-block-post-title a:hover',
						$attributes['titleHoverFontColor'],
						'color'
					);
				}
	
				// Meta
				if ( $attributes['enableMeta'] ){
					// Font Family
					if ( isset( $attributes['metaFontFamily'] ) && $attributes['metaFontFamily'] !== 'system' ) {
						$all_fonts	= Blockwheels_Google_Fonts::get_fonts();
						if ( array_key_exists( $attributes['metaFontFamily'], $all_fonts ) ) {
							$fonts[$attributes['metaFontFamily']] = map_deep( wp_unslash( $all_fonts[$attributes['metaFontFamily']] ), 'sanitize_text_field' );
						}
						$inline_css .= Blockwheels_CSS::get_generate_css(
							$selector . ' .blockwheels-post-details .wp-block-content .wp-block-post-meta >span:not(.meta-sept)',
							esc_html($all_fonts[$attributes['metaFontFamily']]['name']),
							'font-family'
						);
					}
					// Font Weight
					if ( isset( $attributes['metaFontWeight'] ) ) {
						$inline_css .= Blockwheels_CSS::get_generate_css(
							$selector . ' .blockwheels-post-details .wp-block-content .wp-block-post-meta >span:not(.meta-sept)',
							$attributes['metaFontWeight'],
							'font-weight'
						);
					}
					// Font Style
					if ( isset( $attributes['metaFontStyle'] ) ) {
						$inline_css .= Blockwheels_CSS::get_generate_css(
							$selector . ' .blockwheels-post-details .wp-block-content .wp-block-post-meta >span:not(.meta-sept)',
							$attributes['metaFontStyle'],
							'font-style'
						);
					}
					// Text Transform
					if ( isset( $attributes['metaLetterCase'] ) ) {
						$inline_css .= Blockwheels_CSS::get_generate_css(
							$selector . ' .blockwheels-post-details .wp-block-content .wp-block-post-meta >span:not(.meta-sept)',
							$attributes['metaLetterCase'],
							'text-transform'
						);
					}
					// Font Size
					if ( isset( $attributes['metaFontSize'] ) ) {
						$inline_css .= Blockwheels_CSS::get_range_css(
							$selector . ' .blockwheels-post-details .wp-block-content .wp-block-post-meta >span:not(.meta-sept)',
							$attributes['metaFontSize'],
							'font-size'
						);
					}
					// Color
					if ( isset( $attributes['metaFontColor'] ) ) {
						$inline_css .= Blockwheels_CSS::get_color_css(
							$selector . ' .blockwheels-post-details .wp-block-content .wp-block-post-meta >span',
							$attributes['metaFontColor'],
							'color'
						);
						$inline_css .= Blockwheels_CSS::get_color_css(
							$selector . ' .blockwheels-post-details .wp-block-content .wp-block-post-meta a',
							$attributes['metaFontColor'],
							'color'
						);
					}		
				}
	
				// Excerpt
				if ( $attributes['enableExcerpt'] ){
					// Font Family
					if ( isset( $attributes['excerptFontFamily'] ) && $attributes['excerptFontFamily'] !== 'system' ) {
						$all_fonts	= Blockwheels_Google_Fonts::get_fonts();
						if ( array_key_exists( $attributes['excerptFontFamily'], $all_fonts ) ) {
							$fonts[$attributes['excerptFontFamily']] = map_deep( wp_unslash( $all_fonts[$attributes['excerptFontFamily']] ), 'sanitize_text_field' );
						}
						$inline_css .= Blockwheels_CSS::get_generate_css(
							$selector . ' .blockwheels-post-details .wp-block-content .wp-block-post-excerpt__excerpt',
							esc_html($all_fonts[$attributes['excerptFontFamily']]['name']),
							'font-family'
						);
					}
					// Font Weight
					if ( isset( $attributes['excerptFontWeight'] ) ) {
						$inline_css .= Blockwheels_CSS::get_generate_css(
							$selector . ' .blockwheels-post-details .wp-block-content .wp-block-post-excerpt__excerpt',
							$attributes['excerptFontWeight'],
							'font-weight'
						);
					}
					// Font Style
					if ( isset( $attributes['excerptFontStyle'] ) ) {
						$inline_css .= Blockwheels_CSS::get_generate_css(
							$selector . ' .blockwheels-post-details .wp-block-content .wp-block-post-excerpt__excerpt',
							$attributes['excerptFontStyle'],
							'font-style'
						);
					}
					// Text Transform
					if ( isset( $attributes['excerptLetterCase'] ) ) {
						$inline_css .= Blockwheels_CSS::get_generate_css(
							$selector . ' .blockwheels-post-details .wp-block-content .wp-block-post-excerpt__excerpt',
							$attributes['excerptLetterCase'],
							'text-transform'
						);
					}
					// Font Size
					if ( isset( $attributes['excerptFontSize'] ) ) {
						$inline_css .= Blockwheels_CSS::get_range_css(
							$selector . ' .blockwheels-post-details .wp-block-content .wp-block-post-excerpt__excerpt',
							$attributes['excerptFontSize'],
							'font-size'
						);
					}
					// Color
					if ( isset( $attributes['excerptFontColor'] ) ) {
						$inline_css .= Blockwheels_CSS::get_color_css(
							$selector . ' .blockwheels-post-details .wp-block-content .wp-block-post-excerpt__excerpt',
							$attributes['excerptFontColor'],
							'color'
						);
					}					
				}
				// Button
				if ( $attributes['enableReadMore'] ){
					// Font Family
					if ( isset( $attributes['buttonFontFamily'] ) && $attributes['buttonFontFamily'] !== 'system' ) {
						$all_fonts	= Blockwheels_Google_Fonts::get_fonts();
						if ( array_key_exists( $attributes['buttonFontFamily'], $all_fonts ) ) {
							$fonts[$attributes['buttonFontFamily']] = map_deep( wp_unslash( $all_fonts[$attributes['buttonFontFamily']] ), 'sanitize_text_field' );
						}
						$inline_css .= Blockwheels_CSS::get_generate_css(
							$selector . ' .blockwheels-post-details .wp-block-content .wp-block-post-excerpt__more-text a',
							esc_html($all_fonts[$attributes['buttonFontFamily']]['name']),
							'font-family'
						);
					}
					// Font Weight
					if ( isset( $attributes['buttonFontWeight'] ) ) {
						$inline_css .= Blockwheels_CSS::get_generate_css(
							$selector . ' .blockwheels-post-details .wp-block-content .wp-block-post-excerpt__more-text a',
							$attributes['buttonFontWeight'],
							'font-weight'
						);
					}
					// Font Style
					if ( isset( $attributes['buttonFontStyle'] ) ) {
						$inline_css .= Blockwheels_CSS::get_generate_css(
							$selector . ' .blockwheels-post-details .wp-block-content .wp-block-post-excerpt__more-text a',
							$attributes['buttonFontStyle'],
							'font-style'
						);
					}
					// Text Transform
					if ( isset( $attributes['buttonLetterCase'] ) ) {
						$inline_css .= Blockwheels_CSS::get_generate_css(
							$selector . ' .blockwheels-post-details .wp-block-content .wp-block-post-excerpt__more-text a',
							$attributes['buttonLetterCase'],
							'text-transform'
						);
					}
					// Font Size
					if ( isset( $attributes['buttonFontSize'] ) ) {
						$inline_css .= Blockwheels_CSS::get_range_css(
							$selector . ' .blockwheels-post-details .wp-block-content .wp-block-post-excerpt__more-text a',
							$attributes['buttonFontSize'],
							'font-size'
						);
					}
					// Color
					if ( isset( $attributes['buttonFontColor'] ) ) {
						$inline_css .= Blockwheels_CSS::get_color_css(
							$selector . ' .blockwheels-post-details .wp-block-content .wp-block-post-excerpt__more-text a.wp-block-post-excerpt__more-link',
							$attributes['buttonFontColor'],
							'color'
						);
					}
					// Hover Color
					if ( isset( $attributes['buttonHoverFontColor'] ) ) {
						$inline_css .= Blockwheels_CSS::get_color_css(
							$selector . ' .blockwheels-post-details .wp-block-content .wp-block-post-excerpt__more-text a.wp-block-link:hover',
							$attributes['buttonHoverFontColor'],
							'color'
						);
					}
					// Background Color
					if ( isset( $attributes['buttonBgColor'] ) ) {
						$inline_css .= Blockwheels_CSS::get_color_css(
							$selector . ' .blockwheels-post-details .wp-block-content .wp-block-post-excerpt__more-text a.wp-block-post-excerpt__more-link',
							$attributes['buttonBgColor'],
							'background-color'
						);
						$inline_css .= Blockwheels_CSS::get_color_css(
							$selector . ' .blockwheels-post-details .wp-block-content .wp-block-post-excerpt__more-text a.wp-block-link',
							$attributes['buttonBgColor'],
							'color'
						);
					}
					// Padding
					if ( isset( $attributes['buttonPadding'] ) ) {
						$inline_css .= Blockwheels_CSS::get_dimensions_css(
							$selector . ' .blockwheels-post-details .wp-block-content .wp-block-post-excerpt__more-text a.wp-block-post-excerpt__more-link',
							$attributes['buttonPadding'],
							'padding'
						);
					}
					// Border Radius
					if ( isset( $attributes['buttonBorderRadius'] ) ) {
						$inline_css .= Blockwheels_CSS::get_generate_css(
							$selector . ' .blockwheels-post-details .wp-block-content .wp-block-post-excerpt__more-text a.wp-block-post-excerpt__more-link',
							$attributes['buttonBorderRadius'],
							'border-radius'
						);
					}
				}
				
				if ( $inline_css !== '' ) {
					$css = Blockwheels_CSS::minify( $inline_css );
					wp_add_inline_style( $handle, $css );
				}
				if ( ! empty( $fonts ) ) {
					foreach ( $fonts as $key => $selected_font ) {
						if ( ! array_key_exists( 'inherit', $fonts ) ) {
							wp_enqueue_style(
								$handle . '-' . $key . '-font',
								wptt_get_webfont_url( $selected_font['url'] ),
								[],
								BLOCKWHEELS_VERSION
							);
						}
					}
				}
				ob_start();
				?>
<div class="<?php echo esc_attr( implode( ' ', $containerClasses ) ); ?>"
    data-ratio="<?php echo esc_attr( $image_ratio ); ?>">
    <?php while ( $the_query->have_posts() ) : $the_query->the_post(); ?>
    <div class="blockwheels-post-details">
        <?php if ( $attributes['enableFeaturedImage'] ) : ?>
        <?php if ( $attributes['enableImagePlaceholder'] || has_post_thumbnail() ) : ?>
        <figure class="<?php echo esc_attr( implode( ' ', $imageClasses ) ); ?>">
            <a href="<?php echo esc_url( get_permalink() ); ?>" target="_self">
                <?php if ( has_post_thumbnail() ) { the_post_thumbnail(); } ?>
            </a>
        </figure>
        <?php endif; ?>
        <?php endif; ?>
        <div class="wp-block-content">

            <?php if ( $attributes['enableCategory'] ) {
									blockwheels_posted_categories( 'category-style-boxed' );
								} ?>

            <<?php echo esc_attr( $title_html_tag ); ?> class="wp-block-post-title">
                <a href="<?php echo esc_url( get_permalink() ); ?>" rel="bookmark"><?php the_title(); ?></a>
            </<?php echo esc_attr( $title_html_tag ); ?>>

            <?php if ( $attributes['enableMeta'] ) :
									$symbol 	= '&#8208;';
									?>
            <div class="wp-block-post-meta meta-sept-dash">
                <?php if( $attributes['metaAuthorEnable'] ) :
											
											?>
                <span class="posted-by">
                    <span class="author vcard">
                        <a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>"
                            class="url fn n">
                            <?php echo esc_html( get_the_author() ); ?>
                        </a>
                    </span>
                </span>
                <span class="meta-sept"><?php echo esc_html($symbol); ?></span>
                <?php endif; ?>
                <?php if( $attributes['metaDateEnable'] ) : ?>
                <span class="posted-on">
                    <time class="entry-date published"
                        datetime="<?php echo esc_attr( get_the_date( DATE_W3C ) ); ?>"><?php echo esc_html( get_the_date() ); ?></time>
                </span>
                <span class="meta-sept"><?php echo esc_html($symbol); ?></span>
                <?php endif; ?>
                <?php if( $attributes['metaCategoryEnable'] ) : ?>
                <span class="posted-in">
                    <?php 
												// Hide category and tag text for pages.
												if ( 'post' === get_post_type() ) {
													$categories = get_the_category();
													if ( !empty( $categories ) ) {
														echo '<span class="category-link-items">';
														foreach ($categories as $key => $category) {
															echo '<a href="'.esc_url(get_category_link($category->term_id)).'" rel="category tag">';
															echo esc_html($category->name);
															echo '</a>';
															if ( ( count($categories) - 1 ) !== $key ) {
																echo '<span>&#44; </span>';
															}
														}
														echo '</span>';
													}
												}
												?>
                </span>
                <span class="meta-sept"><?php echo esc_html($symbol); ?></span>
                <?php endif; ?>
                <?php if( $attributes['metaCommentsEnable'] && ! post_password_required() && ( comments_open() || get_comments_number() )) : ?>
                <span class="posted-comments">
                    <?php 
												comments_popup_link( 
													false,
													__( '1 Comment', 'blockwheels' ), 
													__( '% Comments', 'blockwheels' ),
													'meta-comments-link anchor-scroll',
													__( 'Comments are Closed', 'blockwheels' )
												);
												?>
                </span>
                <span class="meta-sept"><?php echo esc_html($symbol); ?></span>
                <?php endif; ?>
            </div>

            <?php endif; ?>

            <?php if ( $attributes['enableExcerpt'] ) : ?>
            <div class="wp-block-post-excerpt__excerpt"><?php echo esc_html( blockwheels_get_content_limit( 40 ) ); ?>
            </div>
            <?php endif; ?>

            <?php if ( $attributes['enableReadMore'] ) :
									$link_class = $attributes['buttonStyle'] && $attributes['buttonStyle'] == 'boxed' ? 'wp-block-post-excerpt__more-link' : 'wp-block-link';
									?>
            <div class="wp-block-post-excerpt__more-text">
                <a href="<?php echo esc_url( get_permalink() ); ?>" class="<?php echo esc_attr( $link_class ); ?>"
                    target="_self">
                    <?php echo esc_html( $attributes['buttonText'] ); ?>
                </a>
            </div>
            <?php endif; ?>

        </div>

    </div>
    <?php endwhile; ?>
    <?php wp_reset_postdata(); ?>
</div>
<?php
				$content = ob_get_clean();
				return sprintf( '<div class="%1$s" data-id="%2$s">%3$s</div>', 
					esc_attr(implode(' ', $section_classes )),
					esc_attr($block_id),
					$content
				);
			endif;
		}
	}
}

if ( ! function_exists( 'blockwheels_register_grid_posts' ) ) {

	function blockwheels_register_grid_posts() {

		$block_name = 'grid-posts';
		$asset_config_file 	= sprintf( '%s/build/blocks/'. $block_name .'/index.asset.php', BLOCKWHEELS_PATH );
		if ( ! file_exists( $asset_config_file ) ) {
			return;
		}
	
		register_block_type(
			BLOCKWHEELS_PATH . 'build/blocks/' . $block_name . '/block.json',
			array(
				'render_callback' 	=> 'blockwheels_render_grid_posts'
			)
		);
	}
}
add_action( 'init', 'blockwheels_register_grid_posts' );