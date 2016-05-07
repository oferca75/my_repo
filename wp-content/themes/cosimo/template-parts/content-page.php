<?php
/**
 * The template used for displaying page content in page.php
 *
 * @package cosimo
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    <div class="theCosimoSingle-box">
        <header class="entry-header">
            <?php the_title('<h1 class="entry-title">', '</h1>'); ?>
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
        <span style="display:none" class="updated"><?php the_time(get_option('date_format')); ?></span>
        <div style="display:none" class="vcard author"><a class="url fn"
                                                          href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'))); ?>"><?php echo esc_html(get_the_author()); ?></a>
        </div>
        <div class="intSeparator"></div>
        <footer class="entry-footer smallPart">
            <?php edit_post_link(esc_html__('Edit', 'cosimo'), '<span class="edit-link floatLeft"><i class="fa fa-wrench spaceRight"></i>', '</span>'); ?>
        </footer><!-- .entry-footer -->
    </div><!-- .theCosimoSingle-box -->
</article><!-- #post-## -->
