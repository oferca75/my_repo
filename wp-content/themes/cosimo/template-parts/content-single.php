<?php
/**
 * @package cosimo
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    <div class="theCosimoSingle-box">
        <header class="entry-header">
            <?php cosimo_entry_categories(); ?>
            <?php the_title('<h1 class="entry-title">', '</h1>'); ?>

            <div class="entry-meta smallPart">
                <?php cosimo_posted_on(); ?>
            </div><!-- .entry-meta -->
        </header><!-- .entry-header -->
        <div class="intSeparator"></div>
        <div class="entry-content">
            <?php the_content(); ?>
            <?php
            wp_link_pages(array(
                'before' => '<div class="page-links"><span class="page-links-title">' . esc_html__('Pages:', 'cosimo') . '</span>',
                'after' => '</div>',
                'link_before' => '<span class="page-links-number">',
                'link_after' => '</span>',
                'pagelink' => '<span class="screen-reader-text">' . esc_html__('Page', 'cosimo') . ' </span>%',
                'separator' => '<span class="screen-reader-text">, </span>',
            ));
            ?>
        </div><!-- .entry-content -->
        <div class="intSeparator"></div>
        <footer class="entry-footer smallPart">
            <?php cosimo_entry_footer(); ?>
        </footer><!-- .entry-footer -->
    </div><!-- .theCosimoSingle-box -->
</article><!-- #post-## -->
