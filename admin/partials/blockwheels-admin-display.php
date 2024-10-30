<?php
/**
 * Provide a admin area getting started page views.
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://wpwheels.com/
 * @since      1.0.0
 *
 * @package    Blockwheels
 * @subpackage Blockwheels/admin/partials
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<section class="blockwheels-section">
    <div class="blockwheels-notice-header flex flex-column justify-content-center align-items-center text-center">
        <div class="blockwheels-logo">
            <img src="<?php echo esc_url(BLOCKWHEELS_URL . 'assets/images/logo.svg'); ?>">
        </div>
        <h2>
            <?php esc_html_e( 'Getting Started To BlockWheels', 'blockwheels' ); ?>
        </h2>
        <h4>
            <?php esc_html_e( 'Gutenberg Blocks for Page Builder', 'blockwheels' ); ?>
        </h4>
        <div class="blockwheels-group-buttons flex flex-wrap">
            <a href="https://wpwheels.com/site-demos/?demo=blockwheels" target="_blank" class="btn btn-primary">
                <span class="dashicons dashicons-visibility"></span>
                <?php esc_html_e( 'View Demo', 'blockwheels' ); ?>
            </a>
            <a href="https://wpwheels.com/plugins/blockwheels-pro/" target="_blank" class="btn btn-primary btn-pro">
                <span class="dashicons dashicons-star-filled"></span>
                <?php esc_html_e( 'Buy Pro', 'blockwheels' ); ?>
            </a>
        </div>
    </div><!-- .aarambh-blocks-header -->
    <div class="blockwheels-content grid">
        <div class="main-panel">
            <div class="blocks-item-wrapper grid sm-grid-cols-1 md-grid-cols-2 grid-cols-3">
                <div class="blocks-item">
                    <h3><?php esc_html_e( 'Container', 'blockwheels' ); ?></h3>
                    <p><?php esc_html_e( 'Easily add columns and any blocks either individual or groups of blocks. Customize options to tailor the container as per the requirements.', 'blockwheels' ); ?>
                    </p>
                </div>
                <div class="blocks-item">
                    <h3><?php esc_html_e( 'Heading', 'blockwheels' ); ?></h3>
                    <p><?php esc_html_e( 'Organize your content and improve website readability by humans and search engines.', 'blockwheels' ); ?>
                    </p>
                </div>
                <div class="blocks-item">
                    <h3><?php esc_html_e( 'Paragraph', 'blockwheels' ); ?></h3>
                    <p><?php esc_html_e( 'Add and edit the text within the block editor using Paragraph Block. Create easy-to-read and well-structured content using a versatile and user-friendly interface.', 'blockwheels' ); ?>
                    </p>
                </div>
                <div class="blocks-item">
                    <h3><?php esc_html_e( 'Spacer/Divider', 'blockwheels' ); ?></h3>
                    <p><?php esc_html_e( 'Use the WordPress Spacer block to create appealing space between blocks, making your content more attractive and readable.', 'blockwheels' ); ?>
                    </p>
                </div>
                <div class="blocks-item">
                    <h3><?php esc_html_e( 'Template Library', 'blockwheels' ); ?></h3>
                    <p><?php esc_html_e( 'Includes a collection of pre-designed web pages and pattern layouts that are creative and functional.', 'blockwheels' ); ?>
                    </p>
                </div>
                <div class="blocks-item">
                    <h3><?php esc_html_e( 'Icons', 'blockwheels' ); ?></h3>
                    <p><?php esc_html_e( 'Creatively showcase contains within box format, complete with icons, titles, descriptions, and any information.', 'blockwheels' ); ?>
                    </p>
                </div>
                <div class="blocks-item">
                    <h3><?php esc_html_e( 'Video Box', 'blockwheels' ); ?></h3>
                    <p><?php esc_html_e( 'Compatible with popular video platforms with extensive customization options. Impressively showcase the audio and video content of the choice.', 'blockwheels' ); ?>
                    </p>
                </div>
                <div class="blocks-item">
                    <h3><?php esc_html_e( 'Testimonials', 'blockwheels' ); ?></h3>
                    <p><?php esc_html_e( 'Create testimonials and display positive customer comments in various ways to build trust and credibility.', 'blockwheels' ); ?>
                    </p>
                </div>
                <div class="blocks-item">
                    <h3><?php esc_html_e( 'Services', 'blockwheels' ); ?></h3>
                    <p><?php esc_html_e( 'Showcase your services professionally with a new block in the Block Editor, creating a stunning service section.', 'blockwheels' ); ?>
                    </p>
                </div>
                <div class="blocks-item">
                    <h3><?php esc_html_e( 'Grid Posts', 'blockwheels' ); ?></h3>
                    <p><?php esc_html_e( 'Easily create a post grid layout for displaying posts and thumbnails using the WordPress block editor.', 'blockwheels' ); ?>
                    </p>
                </div>
                <div class="blocks-item">
                    <h3><?php esc_html_e( 'Logo Carousel', 'blockwheels' ); ?></h3>
                    <p><?php esc_html_e( 'Showcase a group of logo images in an attractive carousel using an intuitive Shortcode Generator. It’s very user-friendly and convenient to manage.', 'blockwheels' ); ?>
                    </p>
                </div>
                <div class="blocks-item">
                    <h3><?php esc_html_e( 'Accordion', 'blockwheels' ); ?></h3>
                    <p><?php esc_html_e( 'Want to display your content interestingly and more easily to browse through? Accordion will allow you to to present the information interactively through click and expand patterns.', 'blockwheels' ); ?>
                    </p>
                </div>
                <div class="blocks-item">
                    <h3><?php esc_html_e( 'Google Map', 'blockwheels' ); ?></h3>
                    <p><?php esc_html_e( 'Integrates the Google Maps features and functionalities. Enables display maps, access to location information, generate directions, and other related functionalities.', 'blockwheels' ); ?>
                    </p>
                </div>
                <div class="blocks-item">
                    <h3><?php esc_html_e( 'Counters', 'blockwheels' ); ?></h3>
                    <p><?php esc_html_e( 'Display statistics in a fun and interesting way to highlight your achievements or company data.', 'blockwheels' ); ?>
                    </p>
                </div>
                <div class="blocks-item">
                    <span class="blocks-label-pro"><?php esc_html_e( 'PRO', 'blockwheels' ); ?></span>
                    <h3><?php esc_html_e( 'Hero Slider', 'blockwheels' ); ?></h3>
                    <p><?php esc_html_e( 'Create a visually impressive EXPERIENCE with the Hero Slider, impressing your site visitors with a memorable first impression.', 'blockwheels' ); ?>
                    </p>
                </div>
                <div class="blocks-item">
                    <span class="blocks-label-pro"><?php esc_html_e( 'PRO', 'blockwheels' ); ?></span>
                    <h3><?php esc_html_e( 'Team Members', 'blockwheels' ); ?></h3>
                    <p><?php esc_html_e( 'Easily showcase your team with pictures, positions, bios, and social links using a simple shortcode.', 'blockwheels' ); ?>
                    </p>
                </div>
                <div class="blocks-item">
                    <span class="blocks-label-pro"><?php esc_html_e( 'PRO', 'blockwheels' ); ?></span>
                    <h3><?php esc_html_e( 'Portfolios', 'blockwheels' ); ?></h3>
                    <p><?php esc_html_e( 'Display your personal or company portfolio items beautifully. The portfolio plugin includes a widget and a carousel slider with customizable item display settings.', 'blockwheels' ); ?>
                    </p>
                </div>
                <div class="blocks-item">
                    <span class="blocks-label-pro"><?php esc_html_e( 'PRO', 'blockwheels' ); ?></span>
                    <h3><?php esc_html_e( 'Masonry Gallery', 'blockwheels' ); ?></h3>
                    <p><?php esc_html_e( 'Add an appealing masonry effect to images in posts, custom posts, and pages with ease.', 'blockwheels' ); ?>
                    </p>
                </div>
                <div class="blocks-item">
                    <span class="blocks-label-pro"><?php esc_html_e( 'PRO', 'blockwheels' ); ?></span>
                    <h3><?php esc_html_e( 'Lottie Animation', 'blockwheels' ); ?></h3>
                    <p><?php esc_html_e( 'Customize Lottie animations effortlessly to match your brand identity, staying sharp at any size.', 'blockwheels' ); ?>
                    </p>
                </div>
                <div class="blocks-item">
                    <span class="blocks-label-pro"><?php esc_html_e( 'PRO', 'blockwheels' ); ?></span>
                    <h3><?php esc_html_e( 'Icon List', 'blockwheels' ); ?></h3>
                    <p><?php esc_html_e( 'Presenting a comprehensive list of icons within a structured box format, inclusive of icon images, corresponding titles, detailed descriptions, and relevant supplementary information.', 'blockwheels' ); ?>
                    </p>
                </div>

                <div class="blocks-item">
                    <span class="blocks-label-pro"><?php esc_html_e( 'PRO', 'blockwheels' ); ?></span>
                    <h3><?php esc_html_e( 'Progress Bar', 'blockwheels' ); ?></h3>
                    <p><?php esc_html_e( 'The Progress Bar WPWheels block visually represents skill levels with a linear progress bar. Customize colors, backgrounds, and more to match your style, offering a straightforward way to display progress and proficiency.', 'blockwheels' ); ?>
                    </p>
                </div>
                <div class="blocks-item">
                    <span class="blocks-label-pro"><?php esc_html_e( 'PRO', 'blockwheels' ); ?></span>
                    <h3><?php esc_html_e( 'Circular Progress Bar', 'blockwheels' ); ?></h3>
                    <p><?php esc_html_e( 'The Circular Progress Bar WPWheels block offers a dynamic way to showcase your skills. Simply input your skill name and proficiency percentage, and the block will generate a visually appealing circular progress indicator that illustrates your expertise in a clear, engaging manner out of 100%.', 'blockwheels' ); ?>
                    </p>
                </div>
                <div class="blocks-item">
                    <span class="blocks-label-pro"><?php esc_html_e( 'PRO', 'blockwheels' ); ?></span>
                    <h3><?php esc_html_e( 'CountDown', 'blockwheels' ); ?></h3>
                    <p><?php esc_html_e( 'The Countdown WPWheels block allows users to set and display a countdown timer, customizable by date and time, providing a clear visual representation of the remaining time for any event or deadline.', 'blockwheels' ); ?>
                    </p>
                </div>
                <div class="blocks-item">
                    <span class="blocks-label-pro"><?php esc_html_e( 'PRO', 'blockwheels' ); ?></span>
                    <h3><?php esc_html_e( 'Price Menu', 'blockwheels' ); ?></h3>
                    <p><?php esc_html_e( 'The Price Menu WPWheels block allows you to effortlessly design and display menu items with titles, descriptions, and prices. Featuring two versatile presets for styling, this block is ideal for presenting your offerings in a neat and well-organized layout.', 'blockwheels' ); ?>
                    </p>
                </div>
                <div class="blocks-item">
                    <span class="blocks-label-pro"><?php esc_html_e( 'PRO', 'blockwheels' ); ?></span>
                    <h3><?php esc_html_e( 'Shape Divider', 'blockwheels' ); ?></h3>
                    <p><?php esc_html_e( 'The Shape Divider WPWheels  block allows you to add stylish, customizable shapes to the top or bottom of your sections, enhancing the design. You can also apply background colors for added visual impact, making your content stand out beautifully.', 'blockwheels' ); ?>
                    </p>
                </div>
            </div>
        </div>

        <div class="side-panel flex flex-column">
            <div class="community-section sidebar-section components-panel">
                <div class="components-panel__body is-opened">
                    <h2><?php esc_html_e( 'Web Creators Community', 'blockwheels' ); ?></h2>
                    <p><?php esc_html_e( 'Join our community of fellow blockwheels users creating effective websites! Share your site, ask a question and help others.', 'blockwheels' ); ?>
                    </p>
                    <a href="https://www.facebook.com/wpwheels/" target="_blank"
                        class="sidebar-link"><?php esc_html_e( 'Join our Facebook Group', 'blockwheels' ); ?></a>
                </div>
            </div>
            <div class="support-section sidebar-section components-panel">
                <div class="components-panel__body is-opened">
                    <h2><?php esc_html_e( 'Documentation', 'blockwheels' ); ?></h2>
                    <p><?php esc_html_e( 'Need help? We have a knowledge base full of articles to get you started.', 'blockwheels' ); ?>
                    </p>
                    <a href="https://wpwheels.com/wp-documentation/blockwheels/" target="_blank"
                        class="sidebar-link"><?php esc_html_e( 'Browse Docs', 'blockwheels' ); ?></a>
                </div>
            </div>
            <div class="support-section sidebar-section components-panel">
                <div class="components-panel__body is-opened">
                    <h2><?php esc_html_e( 'Support', 'blockwheels' ); ?></h2>
                    <p><?php esc_html_e( 'Have a question, we are happy to help! Get in touch with our support team.', 'blockwheels' ); ?>
                    </p>
                    <a href="https://wpwheels.com/contact-us/" target="_blank"
                        class="sidebar-link"><?php esc_html_e( 'Submit a Ticket', 'blockwheels' ); ?></a>
                </div>
            </div>
        </div>
    </div><!-- .blockwheels-content -->
</section>