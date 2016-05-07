<?php
get_header(); ?>
<!--<aside id="widget-area-top">-->
<!--    --><?php //if ( !function_exists ( 'dynamic_sidebar' ) || !dynamic_sidebar ( 'widget-area-top' ) ) {
//    } ?>
<!--</aside>-->

<?php get_sidebar(); ?>
<div id="content" class="cf">
    <div class="<?php echo (is_active_sidebar('post-sidebar')) ? "g2" : "g3"; ?>">
        <?php


        if (have_posts()) :
            while (have_posts()) : the_post();

//                $posttags = get_the_tags();
//                if ($posttags && !$_GET["ov"]) {
//                    foreach ($posttags as $tag) {
//                        if ($tag->name == "position") {
//                            $isPosition = true;
//                        }
//                    }
//                }

                // breadcrumbs for the WordPress SEO Plugin by Yoast- if you want to use this, uncomment the line, below:
                //if ( function_exists('yoast_breadcrumb') ) { yoast_breadcrumb('<p id="breadcrumbs">','</p>'); } 
                ?>
                <div id="primary-post">
                    <article itemscope itemtype="http://schema.org/BlogPosting"
                             id="post-<?php the_ID(); ?>" <?php post_class('cf'); ?>>
                        <meta itemscope itemprop="mainEntityOfPage" itemType="https://schema.org/WebPage"
                              itemid="<?php echo get_permalink(); ?>"/>
                        <?php
                        $pg_num = fluid_baseline_grid_page_number();
                        $pageText = ($pg_num > 0) ? ' (Page ' . $pg_num . ')' : "";
                        $postTitle = eliminateKeywords(get_the_title());

                        echo '<h1 itemprop="headline" class="entry-title">' . $postTitle . $pageText . '</h1>';

                        if (function_exists('yoast_breadcrumb')) {
                            yoast_breadcrumb('<p id="breadcrumbs">', '</p>');
                        }
                        if ($pg_num < 2) :
                            if (has_post_thumbnail()) { ?>
                                <div itemprop="image" itemscope itemtype="https://schema.org/ImageObject">
                                    <?php the_post_thumbnail('', array('class' => 'alignnone'));
                                    $thumb = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'full');
                                    ?>
                                    <meta itemprop="url" content="<?php echo $thumb[0]; ?>"/>
                                    <meta itemprop="width" content="<?php echo $thumb[1]; ?>"/>
                                    <meta itemprop="height" content="<?php echo $thumb[2]; ?>"/>
                                </div>
                                <span itemprop="publisher" itemscope itemtype="https://schema.org/Organization"><meta
                                        itemprop="name" content="<?php echo get_bloginfo(); ?>"/></span>
                            <?php } ?>
                            <p class="postmetadata"><em><span class="updated" itemprop="datePublished"
                                                              content="<?php echo get_the_time("Y-m-d"); ?>"><?php
                                        the_time(get_option('date_format')); ?></span>
                                    <meta itemprop="dateModified"
                                          content="<?php echo the_modified_date("Y-m-d"); ?>"/><?php
                                    if (get_the_category()) {
                                        _e(' in ', 'fluid-baseline-grid');
                                        the_category(', ');
                                    }
                                    _e(' by ', 'fluid-baseline-grid'); ?>
                                    <span itemprop="author" itemscope itemtype="http://schema.org/Person"
                                          class="vcard author"><span itemprop="name" class="fn">
                          <?php the_author_posts_link(); ?></span></span>
                                    <?php edit_post_link(__('Edit this post', 'fluid-baseline-grid'), ' &mdash; '); ?>
                                </em>
                            </p>
                            <?php
                        endif;
                        if (!post_password_required()) {
                            ?>
                            <div itemprop="articleBody">
                                <?php
                                if (!$isPosition) {
                                    the_content();
                                    global $post;
                                    $title = isset($post->post_title) ? $post->post_title : '';
                                    $catId = get_cat_ID($title);
                                    if ($catId) {
                                        ?>
                                        <div id="cat-desc"><?php
                                        echo category_description($catId);
                                        ?>
                                        </div><?php

                                    }
                                }
                                ?>
                            </div>
                            <div class="cf"></div>
                            <?php
                            wp_link_pages();
                            if (fluid_baseline_grid_is_last_page() && 0) { // !!!!!!!!!!!!!!! Comments disabled
                                // only show tags and comments on last page
                                the_tags('<p>' . __('Tags: ', 'fluid-baseline-grid'), ', ', '</p>');
                                comments_template();
                                ?>
                                <div class="navigation g3 cf">
                                <p><?php previous_post_link(); ?> &mdash; <?php next_post_link(); ?></p></div><?php
                            }
                        } else {
                            the_excerpt();
                        }
                        ?></article>
                </div>
                <?php
            endwhile;
        endif;
        ?>
    </div>
    <?php

    $queryParams = array('posts_per_page' => 20,
        'category__in' => array($catId),
    );
    if (!$isPosition) {
        $queryParams['post__not_in'] = array($post->ID);
    }
    $next_moves = query_posts($queryParams);
    if (count($next_moves) > 0) {
        if (!$isPosition) {
            ?>

        <?php } ?>
        <div class="cf <?php echo !$isPosition ? 'blurred' : ""; ?>">
            <?php
            if (have_posts()) :
                if (endsWith($postTitle, "sitions")) {
                    $str = str_replace("Positions", "", $postTitle);
                    $dispStr = "Positions from the " . $str;
                } else {
                    if (count($next_moves) > 1) {
                        $dispStr = "Options from the " . eliminateKeywords($postTitle);
                    } else {
                        $dispStr = "Next move";// from the " . eliminateKeywords($postTitle);
                    }
                }
                ?><h2><span class="next-moves"><?php echo $dispStr ?> :</span></h2><?php
                set_query_var('post_title', $title);
                get_template_part('loop');
                ?>
                <div class="navigation g3 cf"><p><?php posts_nav_link(); ?></p></div>
            <?php endif; ?>
        </div>

    <?php } ?>

    <?php
    comments_template();

    ?>

    <?php if (is_active_sidebar('post-sidebar')) : ?>
        <aside class="g1 sidebar">
            <ul>
                <?php dynamic_sidebar('post-sidebar'); ?>
            </ul>
        </aside>
    <?php endif; ?>
</div>
<?php


get_footer(); ?>
