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
                <div class="img-container">
                    <img width="120" src="<?php echo get_stylesheet_directory_uri() ?>/img/logo.gif"/>
                </div>
                <div id="promotion-message">
                    <div class="wrapper">
                        <div class="section left">
                            <h2><strong><div class="headTxt part1">JJ</div> <div class="headTxt part2">TECHNIQUES</div>
                                </strong></h2>
                            <p>A visual guide to BJJ game</p>
                        </div><!-- .section.left -->
                    </div><!-- .wrapper -->
                </div>
            </a>
        </div>

        <?php

    }

endif;
add_action('fullframe_before_content', 'header_logo', 30);
