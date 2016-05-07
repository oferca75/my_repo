<?php
/**
 * The template for displaying search results pages.
 *
 * @package cosimo
 */

get_header(); ?>

<section id="primary" class="content-area">
    <main id="main" class="site-main" role="main">

        <?php if (have_posts()) : ?>

            <header class="page-header">
                <h1 class="page-title"><?php printf(esc_html__('Search Results for: %s', 'cosimo'), '<span>' . get_search_query() . '</span>'); ?></h1>
            </header><!-- .page-header -->

            <?php /* Start the Loop */
            global $i;
            $i = 0; ?>
            <div class="cosimo-back">
                <div class="cosimo" id="mainCosimo">
                    <div class="grid-sizer"></div>
                    <?php while (have_posts()) : the_post(); ?>

                        <?php
                        /**
                         * Run the loop for the search to output the results.
                         * If you want to overload this in a child theme then include a file
                         * called content-search.php and that will be used instead.
                         */
                        get_template_part('template-parts/content', get_post_format());
                        $i++;
                        ?>

                    <?php endwhile; ?>
                </div><!-- #mainCosimo -->
            </div><!-- .cosimo-back -->

            <?php if (version_compare($GLOBALS['wp_version'], '4.1', '<')) {
                the_posts_navigation();
            } else {
                the_posts_pagination(array(
                    'prev_text' => '<i class="fa fa-angle-double-left spaceRight"></i>' . esc_html__('Previous', 'cosimo'),
                    'next_text' => esc_html__('Next', 'cosimo') . '<i class="fa fa-angle-double-right spaceLeft"></i>',
                    'before_page_number' => '<span class="meta-nav screen-reader-text">' . esc_html__('Page', 'cosimo') . ' </span>',
                ));
            } ?>

        <?php else : ?>

            <?php get_template_part('template-parts/content', 'none'); ?>

        <?php endif; ?>

    </main><!-- #main -->
</section><!-- #primary -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>
