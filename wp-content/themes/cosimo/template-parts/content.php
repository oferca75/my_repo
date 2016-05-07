<?php
/**
 * @package cosimo
 */
?>
<?php
global $i;
$masonryBig = get_theme_mod('cosimo_theme_options_masonrybig', '0');
?>

<?php if ($i == 0 && $masonryBig == 1) : ?>
<article id="post-<?php the_ID(); ?>" <?php post_class('cosimomas w2'); ?>>
    <?php else : ?>
    <article id="post-<?php the_ID(); ?>" <?php post_class('cosimomas w1'); ?>>
        <?php endif; ?>

        <div class="content-cosimo">
            <?php
            if ('' != get_the_post_thumbnail()) {
                echo '<div class="entry-featuredImg cosimo-loader"><a href="' . esc_url(get_permalink()) . '">';
                if ($i == 0 && $masonryBig == 1) {
                    the_post_thumbnail('cosimo-masonry-w2');
                } else {
                    the_post_thumbnail('cosimo-masonry-w1');
                }
                echo '</a></div>';
            }
            ?>
            <div class="theCosimo-box">
                <header class="entry-header">
                    <?php the_title(sprintf('<h2 class="entry-title"><a href="%s" rel="bookmark">', esc_url(get_permalink())), '</a></h2>'); ?>

                    <?php if ('post' == get_post_type()) : ?>
                        <div class="entry-meta smallPart">
                            <?php cosimo_posted_on(); ?>
                        </div><!-- .entry-meta -->
                    <?php endif; ?>
                </header><!-- .entry-header -->

                <div class="entry-summary">
                    <?php the_excerpt(); ?>
                </div><!-- .entry-summary -->

                <footer class="entry-footer smallPart">
                    <?php edit_post_link(esc_html__('Edit', 'cosimo'), '<span class="edit-link floatLeft"><i class="fa fa-wrench spaceRight"></i>', '</span>'); ?>
                    <span class="read-more"><a
                            href="<?php echo esc_url(get_permalink()); ?>"><?php esc_html_e('Read More', 'cosimo') ?></a><i
                            class="fa spaceLeft fa-caret-right"></i></span>
                </footer><!-- .entry-footer -->
            </div><!-- .theCosimo-box -->

        </div><!-- .content-cosimo -->
    </article><!-- #post-## -->