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

    while (have_posts()) : the_post(); ?>

        <?php
        $has_posts = true;
        get_template_part('content', 'single'); ?>

        <?php
        /**
         * fullframe_after_post hook
         *
         * @hooked fullframe_post_navigation - 10
         */
        do_action('fullframe_after_post');

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


<?php

if (!$has_posts) {
    /**
     * The template for displaying Archive pages
     *
     * Learn more: http://codex.wordpress.org/Template_Hierarchy
     *
     * @package Catch Themes
     * @subpackage Full Frame
     * @since Full Frame 1.0 single_cat_title();
     */

    get_header(); ?>

    <section id="primary" class="content-area">

        <main id="main" class="site-main" role="main">

            <?php if (have_posts()) : ?>

                <header class="page-header">
                    <h1 class="page-title">
                        <?php
                        if (is_category()) :
                            single_cat_title();

                        elseif (is_tag()) :
                            single_tag_title();

                        elseif (is_author()) :
                            printf(__('Author: %s', 'full-frame'), '<span class="vcard">' . get_the_author() . '</span>');

                        elseif (is_day()) :
                            printf(__('Day: %s', 'full-frame'), '<span>' . get_the_date() . '</span>');

                        elseif (is_month()) :
                            printf(__('Month: %s', 'full-frame'), '<span>' . get_the_date(_x('F Y', 'monthly archives date format', 'full-frame')) . '</span>');

                        elseif (is_year()) :
                            printf(__('Year: %s', 'full-frame'), '<span>' . get_the_date(_x('Y', 'yearly archives date format', 'full-frame')) . '</span>');

                        elseif (is_tax('post_format', 'post-format-aside')) :
                            _e('Asides', 'full-frame');

                        elseif (is_tax('post_format', 'post-format-gallery')) :
                            _e('Galleries', 'full-frame');

                        elseif (is_tax('post_format', 'post-format-image')) :
                            _e('Images', 'full-frame');

                        elseif (is_tax('post_format', 'post-format-video')) :
                            _e('Videos', 'full-frame');

                        elseif (is_tax('post_format', 'post-format-quote')) :
                            _e('Quotes', 'full-frame');

                        elseif (is_tax('post_format', 'post-format-link')) :
                            _e('Links', 'full-frame');

                        elseif (is_tax('post_format', 'post-format-status')) :
                            _e('Statuses', 'full-frame');

                        elseif (is_tax('post_format', 'post-format-audio')) :
                            _e('Audios', 'full-frame');

                        elseif (is_tax('post_format', 'post-format-chat')) :
                            _e('Chats', 'full-frame');

                        else :
                            _e('Archives', 'full-frame');

                        endif;
                        ?>
                    </h1>
                    <?php
                    // Show an optional term description.
                    $term_description = term_description();
                    if (!empty($term_description)) :
                        printf('<div class="taxonomy-description">%s</div>', $term_description);
                    endif;
                    ?>
                </header><!-- .page-header -->

                <?php /* Start the Loop */ ?>
                <?php while (have_posts()) : the_post(); ?>

                    <?php
                    /* Include the Post-Format-specific template for the content.
                     * If you want to override this in a child theme, then include a file
                     * called content-___.php (where ___ is the Post Format name) and that will be used instead.
                     */
                    get_template_part('content', get_post_format());
                    ?>

                <?php endwhile; ?>

                <?php fullframe_content_nav('nav-below'); ?>
            <?php else : ?>

                <?php get_template_part('content', 'none'); ?>

            <?php endif; ?>

        </main><!-- #main -->
    </section><!-- #primary -->

    <?php get_sidebar(); ?>
    <?php get_footer();

}

?>
