<?php

/**
 * The template for displaying the Breadcrumb
 *
 * @package Catch Themes
 * @subpackage Full Frame
 * @since Full Frame 1.0
 */


/**
 * Add breadcrumb.
 *
 * @action fullframe_after_header
 *
 * @since Fullframe 1.0
 */
if (!function_exists('header_logo')) :

    function header_logo()
    {
        ?>

        <div class="bjj-header wrapper">
            <a href="/">
                <img width="120" src="<?php echo get_stylesheet_directory_uri() ?>/img/logo.gif"/>
                <div id="promotion-message">
                    <div class="wrapper">
                        <div class="section left"><h2><strong>Brazilian Jiu Jitsu Tutorial</strong></h2>
                            <p>Free online resource to enhance your BJJ game</p>
                        </div><!-- .section.left -->
                    </div><!-- .wrapper -->
                </div>
            </a>
        </div>

        <?php

    }

endif;
add_action('fullframe_before_content', 'header_logo', 30);
