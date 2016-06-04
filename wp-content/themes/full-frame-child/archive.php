<?php


/**
 * The Template for displaying all single posts
 *
 * @package Catch Themes
 * @subpackage Full Frame
 * @since Full Frame 1.0
 */

get_header(); ?>

<main id="main" class="site-main" role="main">

    <?php

    $page_title = single_cat_title("", false);
    $post = get_page_by_title($page_title, "OBJECT", 'post');
    query_posts('p=' . $post->ID);
    get_template_part('content', 'single');
    do_action('fullframe_after_post');

    /**
     * fullframe_comment_section hook
     *
     * @hooked fullframe_get_comment_section - 10
     */
    do_action('fullframe_comment_section');
    ?>

</main><!-- #main -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>

