<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
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

            <?php get_template_part('template-parts/content', 'page'); ?>

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
