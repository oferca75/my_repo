<?php
// Template Name: How 2 Use

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

get_header(); ?>
<main id="main" class="site-main" role="main">

    <?php while (have_posts()) : the_post(); ?>

        <?php get_template_part('content', 'page'); ?>

        <?php
        $post1 = get_page_by_title("Top Positions", "OBJECT", 'post');
        $post = get_post($post1->ID);
        global $overrideText;
        include get_stylesheet_directory() . '/inc/next-move.php';

        $post1 = get_page_by_title("Bottom Positions", "OBJECT", 'post');
        $post = get_post($post1->ID);
        global $jssor_id;
        $jssor_id = 2;
        include get_stylesheet_directory() . '/inc/next-move.php'; ?>

        <?php
        /**
         * fullframe_comment_section hook
         *
         * @hooked fullframe_get_comment_section - 10
         */
        do_action('fullframe_comment_section');
        ?>

    <?php endwhile; // end of the loop. ?>

</main><!-- #main -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>
