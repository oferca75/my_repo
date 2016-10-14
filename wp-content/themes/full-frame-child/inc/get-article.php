<article id="post-<?php echo $postId; ?>" <?php post_class(); ?>>
  <?php
    /**
     * fullframe_before_post_container hook
     *
     * @hooked fullframe_single_content_image - 10
     */
    ?>
    <div class="entry-container">
      <?php do_action('fullframe_before_post_container'); ?>

      <?php
        ?>
        <header class="entry-header">
          <?php
            $title = get_the_title();
            $newTitle = headlineText($title, "title");
            if (function_exists("getTrueTitle")) {
                $tTitle = getTrueTitle($title);
            }
            ?>

            <h1 class="entry-title <?php echo $tTitle; ?>"><?php

                echo $newTitle . "</h1>"; fullframe_entry_meta(); ?>
        </header>
        <!-- .entry-header -->

        <div class="entry-content">
          <?php
                require get_stylesheet_directory() . '/inc/video-wrap.php';
                if (!$disableNextMove){
                   require get_stylesheet_directory() . '/inc/next-move.php';
                }
            ?>

            <?php
            $eTitle = eliminateKeywords($title);
           ?>
              <p></p>
              <h2 class="some-facts">More Info</h2>
              <ul class="some-facts">
                <?php
                $lastViewedTitle = $_COOKIE["last_viewed"];
                if (strlen($postContent) > 20) { ?>
                  <li>
                    <?php
                        echo $postContent;
                        ?>
                  </li>
                  <?php } ?>

                  <?php
                $catId = get_cat_ID($title);
                $sep = ' » ';
                $parents = get_category_parents($catId, TRUE, $sep);
                if (!is_wp_error($parents) && substr_count($parents, $sep) > 2) {
                    ob_start();
                    ?>
                    <li>
                      A possible path to <strong><?php echo $eTitle; ?></strong> could be
                      <?php echo substr($parents, 0, strlen($parents) - 3);; ?>
                    </li>
                    <?php
                    $possiblePath = ob_get_clean();
                }

                ?>
                      <?php
                global $nextMoveArray;
                if (count($nextMoveArray) > 0) {
                    ?>
                        <li>
                          <?php
                        if (endsWith($eTitle, "sitions")) {
                            ?>
                            The types of <strong><?php echo $eTitle; ?></strong> are:

                            <?php
                        } else {
                            ?>
                              Your next move from <strong><?php echo $eTitle; ?></strong> could be:
                              <?php
                        }
                        ?>
                                <?php
                        $comma = "";
                        foreach ($nextMoveArray as $title => $permalink) {
                            echo $comma;
                            if (!$comma) $comma = ", ";
                            echo "<a href='$permalink'>$title</a>";
                        }
                        ?>
                        </li>
                        <?php }

                if ($lastViewedTitle && $lastViewedTitle != "" && $lastViewedTitle != $eTitle) {
                    $lastViewedPost = get_page_by_title($lastViewedTitle, "OBJECT", 'post');
                    $post1 = get_post($lastViewedPost);
                    ?>
                        <li>
                          You have arrived to the <strong><?php echo $eTitle; ?></strong> from
                          <a href="<?php echo get_permalink($lastViewedPost); ?>">
                            <?php echo headlineText($lastViewedTitle, "title"); ?>
                          </a>
                        </li>
                        <?php ob_start(); ?>
                        <li>Other options from <strong><?php echo headlineText($lastViewedTitle, "title"); ?></strong> could be:
                          <?php
                        $catId = get_cat_ID($lastViewedTitle);
                        if (!$dontDisplay && $catId != 0) {
                            $args = array('category__in' => array($catId), 'numberposts' => $numberposts);
                            $loop = new WP_Query($args);
                        }
                        $comma = null;
                        $counter = 0;

                        while ($loop->have_posts()) :
                            $counter++;
                            $loop->the_post();
                            if ($lastViewedTitle == get_the_title() || $title == get_the_title())
                                continue;
                            if ($comma == null)
                                $comma = ",";
                            else echo $comma;
                            ?>
                            <a href="<?php echo get_the_permalink() ?>">
                              <?php echo get_the_title();
                            ?>
                            </a>
                            <?php
                        endwhile; // end of the loop.
                        ?>
                        </li>
                        <?php $str = ob_get_clean();
                    if ($counter > 1) {
                        echo $str;
                    }
                }
                ?>
                        <?php
                if ($possiblePath) {
                    echo $possiblePath;
                }
                if (strlen($postContent) < 20 && strpos($title, "rom ") > 0) {
                    $post1 = get_page_by_title($lastViewedTitle, "OBJECT", 'post');
                    global $post;
                    $postContent = getPostContent($post1->ID);
                    if (strlen($postContent) > 20) { ?>
                          <li>
                            <?php
                            echo $postContent;
                            ?>
                          </li>
                          <?php
                    }
                }


                ?>
              </ul>


              <?php
            wp_link_pages(array(
                'before' => '<div class="page-links"><span class="pages">' . __('Pages:', 'full-frame') . '</span>',
                'after' => '</div>',
                'link_before' => '<span>',
                'link_after' => '</span>',
            ));
            ?>
        </div>
        <!-- .entry-content -->

        <footer class="entry-footer">
          <?php fullframe_tag_category(); ?>
        </footer>
        <!-- .entry-footer -->
    </div>
    <!-- .entry-container -->
</article>
<!-- #post-## -->