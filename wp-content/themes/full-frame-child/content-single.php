<?php
/**
 * The template used for displaying post content in single.php
 *
 * @package Catch Themes
 * @subpackage Full Frame
 * @since Full Frame 1.0
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    <?php
    /**
     * fullframe_before_post_container hook
     *
     * @hooked fullframe_single_content_image - 10
     */
?>
    <div class="entry-container">
        <?php  do_action('fullframe_before_post_container'); ?>

        <?php
        //global $arrowSVG;
         //echo '<div class="next-step next-arrow">' . $arrowSVG . "</div>";
        ?>
        <header class="entry-header">
            <div class="fight-path">
                <?php
                global $dd_lastviewed_id;
                echo do_shortcode('[dd_lastviewed widget_id="'.$dd_lastviewed_id.'"]');

                $title = get_the_title();
                $newTitle = headlineText($title,"title");
                if (function_exists("getTrueTitle"))
                {
                    $tTitle = getTrueTitle($title);
                }
                ?>
            </div>
            <h1 class="entry-title <?php echo $tTitle;?>"><?php

                echo $newTitle."</h1>";

             fullframe_entry_meta(); ?>
        </header><!-- .entry-header -->

        <div class="entry-content">

                <?php $video_id = get_metadata('post', get_the_ID(), "video_id", true); ?>
                <iframe src="https://www.youtube.com/embed/<?php echo $video_id;?>" frameborder="0" ></iframe>
 


           <?php the_content();
         require get_stylesheet_directory() . '/inc/next-move.php';
                 
            ?>

            <?php
            wp_link_pages(array(
                'before' => '<div class="page-links"><span class="pages">' . __('Pages:', 'full-frame') . '</span>',
                'after' => '</div>',
                'link_before' => '<span>',
                'link_after' => '</span>',
            ));
            ?>
        </div><!-- .entry-content -->

        <footer class="entry-footer">
            <?php fullframe_tag_category(); ?>
        </footer><!-- .entry-footer -->
    </div><!-- .entry-container -->
</article><!-- #post-## -->
