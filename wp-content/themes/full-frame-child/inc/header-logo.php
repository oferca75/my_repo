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
                    <img width="250" alt="Brazilian Jiu Jitsu Techniques" src="<?php echo get_stylesheet_directory_uri() ?>/img/logo4.jpe"/>
                </div>
            </a>
        </div>

        <?php

    }

endif;
add_action('fullframe_before_content', 'header_logo', 30);
