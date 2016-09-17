<?php
// Template Name: Page 2 Post
/**
 * The template for displaying all pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
 *
 * @package Catch Themes
 * @subpackage Full Frame
 * @since Full Frame 1.0
 */


/**
 * The Template for displaying all single posts
 *
 * @package Catch Themes
 * @subpackage Full Frame
 * @since Full Frame 1.0
 */

get_header();

get_sidebar();
?>

    <main id="main" class="site-main" role="main">

        <?php

        $page_title = get_the_title();
        $post = get_page_by_title($page_title, "OBJECT", 'post');



//Define the loop based on arguments
$args = array(
    'p' => $post->ID
);
$loop = new WP_Query( $args );

//Display the contents

while ( $loop->have_posts() ) : $loop->the_post();

        ?>

            <?php get_template_part('content', 'single'); ?>

            <?php
            /**
             * fullframe_after_post hook
             *
             * @hooked fullframe_post_navigation - 10
             */
            //do_action('fullframe_after_post');

            /**
             * fullframe_comment_section hook
             *
             * @hooked fullframe_get_comment_section - 10
             */
            do_action('fullframe_comment_section');
            ?>
<?php endwhile; // end of the loop. ?>

    </main><!-- #main -->
 
<?php get_footer(); ?>