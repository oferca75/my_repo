<?php get_header(); ?>
<div id="content" class="cf">
    <div class="<?php echo (is_active_sidebar('page-sidebar')) ? "g2" : "g3"; ?>">
        <?php
        if (have_posts()) :
            while (have_posts()) : the_post();
                // breadcrumbs for the WordPress SEO Plugin by Yoast- if you want to use this, uncomment the line, below:
                //if ( !is_front_page() && function_exists('yoast_breadcrumb') ) { yoast_breadcrumb('<p id="breadcrumbs">','</p>'); } ?>
                <div id="post-<?php the_ID(); ?>" <?php post_class('cf'); ?>>
                    <?php
                    $pg_num = fluid_baseline_grid_page_number();
                    the_title('<h1>', ($pg_num > 0) ? ' (Page ' . $pg_num . ')</h1>' : '</h1>');
                    if (has_post_thumbnail()) the_post_thumbnail('', array('class' => 'alignnone'));
                    edit_post_link(__('Edit this page', 'fluid-baseline-grid'), '<p>', '</p>');
                    if (!post_password_required()) {
                        the_content();
                        ?>
                        <div class="cf"></div>
                        <?php
                        wp_link_pages();
                        if (fluid_baseline_grid_is_last_page()) {
                            // only show tags and comments on last page
                            comments_template();
                        }
                    } else {
                        the_excerpt();
                    }
                    ?></div>
                <?php
            endwhile;
        endif;
        ?>
        <div class="navigation"><p><?php posts_nav_link(); ?></p></div>
    </div>
    <?php if (is_active_sidebar('page-sidebar')) : ?>
        <aside class="g1 sidebar">
            <ul>
                <?php dynamic_sidebar('page-sidebar'); ?>
            </ul>
        </aside>
    <?php endif; ?>
</div>
<?php get_footer(); ?>
