<?php

$post_count = 0;


while (have_posts()) :
    the_post();
    if ($post_count > 0 && $post_count % 3 == 0) {
        ?>
        <div class="cf"></div><?php
    }
    $post_count++;
    ?>
<div id="post-<?php the_ID(); ?>" <?php
// only display sticky as three-column wide on first page
if (is_sticky() && get_query_var('paged') < 2) {
    post_class('g3');
    $post_count--;
} else
    post_class('g1');
?>>
    <?php

    $postTitle = get_the_title();
    $postTitle = eliminateKeywords($postTitle);

    if ($postTitle): ?>
        <div class="portfoliooverlay"><a href="<?php

            if ($post_title == get_the_title()) {
                $addition = "?ov=1";
            }
            the_permalink();
            echo $addition;


            ?>"> </a></div>
        <h2>
            <a href="<?php the_permalink() ?>" rel="bookmark" title="<?php echo $postTitle ?>"><?php echo $postTitle ?>
            </a>
        </h2>
        <!--        --><?php //if (has_post_thumbnail()) the_post_thumbnail('featured-img', array('class' => 'alignnone'));
        the_content();
// ?>
        <p class="postmetadata"><em><?php
                //the_time ( get_option ( 'date_format' ) );
                if (get_the_category()) {
                    _e(' In ', 'fluid-baseline-grid');
                    the_category(', ');
                }
                _e(' by ', 'fluid-baseline-grid');
                the_author_posts_link(); ?></em></p>
    <?php else: ?>
        <h2><a href="<?php the_permalink() ?>" rel="bookmark"
               title="<?php the_title(); ?>"><?php the_time(get_option('date_format')) ?></a></h2><p
            class="postmetadata"><em><?php if (get_the_category()) {
                    _e(' in ', 'fluid-baseline-grid');
                    the_category(', ');
                }
                _e(' by ', 'fluid-baseline-grid');
                the_author_posts_link();
                // ?></em></p>
        <?php
    endif;
    //the_excerpt(); 
    ?>
    </div><?php
endwhile;
?>
