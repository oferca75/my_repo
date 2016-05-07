<?php
/**
 * The template for displaying all single posts.
 *
 * @package cosimo
 */

get_header();
$enlargeFeatImage = get_theme_mod('cosimo_theme_options_enlargefeatured', '1');
?>

<div id="primary" class="content-area">
    <?php if ($enlargeFeatImage == 1) : ?>
        <div class="openFeatImage"><i class="fa fa-lg fa-angle-double-down"></i></div>
    <?php endif; ?>
    <main id="main" class="site-main" role="main">

        <?php while (have_posts()) : the_post(); ?>

            <?php get_template_part('template-parts/content', 'single'); ?>

            <?php
            the_post_navigation(array(
                'next_text' => '<div class="theMetaLink"><div class="meta-nav" aria-hidden="true"><span class="smallPart">' . esc_html__('Next Post', 'cosimo') . '</span></div> <i class="fa fa-lg fa-angle-right spaceLeft"></i></div>' .
                    '<span class="screen-reader-text">' . esc_html__('Next post:', 'cosimo') . '</span> ' .
                    '<div class="theMetaTitle">%title</div>',
                'prev_text' => '<div class="theMetaLink"><i class="fa fa-lg fa-angle-left spaceRight"></i> <div class="meta-nav" aria-hidden="true"><span class="smallPart">' . esc_html__('Previous Post', 'cosimo') . '</span></div></div>' .
                    '<span class="screen-reader-text">' . esc_html__('Previous post:', 'cosimo') . '</span> ' .
                    '<div class="theMetaTitle">%title</div>',
            ));
            ?>

            <?php
            // If comments are open or we have at least one comment, load up the comment template
            if (comments_open() || get_comments_number()) :
                comments_template();
            endif;
            ?>

        <?php endwhile; // end of the loop. ?>

    </main><!-- #main -->
</div><!-- #primary -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>
