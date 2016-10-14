<?php
/**
 * The template for displaying Search Results pages
 *
 * @package Catch Themes
 * @subpackage Full Frame
 * @since Full Frame 1.0
 */

get_header(); ?>

<section id="primary" class="content-area">

    <main id="main" class="site-main" role="main">

        <?php if (have_posts()) : ?>

            <header class="page-header">
                <h1 class="page-title"><?php printf(__('Search Results for: %s', 'full-frame'), '<span>' . get_search_query() . '</span>'); ?></h1>
            </header><!-- .page-header -->

            <?php /* Start the Loop */ ?>
            <?php while (have_posts()) : the_post(); ?>

                <?php
                $postId = get_the_ID();
                $title = get_the_title();

                if (function_exists("eliminateKeywords")) {
                    $title = eliminateKeywords($title);
                }
//Ofer End
                // Ofer BEGIN
                $video_id = get_metadata('post', $postId, "video_id", true);

                global $post, $default_video_image;
                $content = '<a href="' . get_the_permalink() . '" rel="bookmark">';
                $content .= $video_id != null ? '<img src="http://img.youtube.com/vi/' . $video_id . '/0.jpg" />' : '<img height="280" src="' . $default_video_image . '"/>';
                $content .= '</a>';
                
                echo '<h3 class="SearchedQueryTitle">' . $title . '</h3>';

                echo $content;
                ?>
                <?php //get_template_part('content', 'search'); ?>

            <?php endwhile; ?>

            <?php fullframe_content_nav('nav-below'); ?>

        <?php else : ?>

            <?php get_template_part('content', 'none'); ?>

        <?php endif; ?>

    </main><!-- #main -->
</section><!-- #primary -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>
