<?php

/**
 * The template for displaying the Breadcrumb
 *
 * @package Catch Themes
 * @subpackage Full Frame
 * @since Full Frame 1.0
 */


/**
 * Add breadcrumb.
 *
 * @action fullframe_after_header
 *
 * @since Fullframe 1.0
 */
if (!function_exists('next-move')) :

    function next_move()
    {
        $nextMoveWidth = "1235px";
        $postTitle = get_the_title();
        $numberposts = 40;
        $postTitle = $postTitle == "Front" || $postTitle == "" ? "Top Positions" : $postTitle;

        $catId = get_cat_ID($postTitle);
        query_posts(array('category__in' => array($catId), 'numberposts' => $numberposts));
        if (!have_posts() && $_COOKIE["last_viewed"]) {
            $catId = get_cat_ID($_COOKIE["last_viewed"]);
            query_posts(array('category__in' => array($catId), 'numberposts' => $numberposts));
            $dispStr = "Choose more techniques from " . eliminateKeywords($_COOKIE["last_viewed"]);
        } else {
            setcookie("last_viewed", $postTitle, time() + (60 * 60 * 24 * 30), "/"); // 30 days
            if (function_exists("nextMoveText")) {
                $dispStr = nextMoveText($postTitle);
            }
        }
        ?>


        <div id="jssor_1"
             style="position: relative; margin: 0 auto; top: 0px; width: <?php echo $nextMoveWidth ?>; height: 250px; overflow: hidden; visibility: visible;">
            <?php echo '<h2 class="next-move-title">' . $dispStr . ':</h2>'; ?>

            <!-- Loading Screen -->
            <div data-u="loading" style="position: absolute; top: 0px; left: 0px;">
                <div
                    style="filter: alpha(opacity=70); opacity: 0.7; position: absolute; display: block; top: 0px; left: 0px; width: 100%; height: 100%;"></div>
                <div
                    style="position:absolute;display:block;background:url('<?php echo get_stylesheet_directory_uri() ?>/css/img/loading.gif') no-repeat center center;top:0px;left:0px;width:100%;height:100%;"></div>
            </div>
            <div data-u="slides"
                 style="cursor: default; position: relative; top: 60px; width: 1235px; height: 250px; overflow: hidden;">
                <?php

                while (have_posts()) :
                    ?>
                    <div style="display: none;"><?php
                        the_post();
                        ?>
                        <a class='yarpp-thumbnail' href='<?php echo get_permalink() ?>'
                           title='<?php echo the_title_attribute('echo=0') ?>'>
                            <div class='next-overlay'></div>
                            <?php
                            if (preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', get_the_content(), $match)) {
                                $video_id = $match[1];
                            }
                            global $post;

                            $post_thumbnail_html = '<iframe id="_ytid_56590" src="https://www.youtube.com/embed/' . $video_id . '?enablejsapi=1&amp;loop=1&amp;playlist=fvmhXk95ZtA&amp;autoplay=0&amp;cc_load_policy=0&amp;iv_load_policy=1&amp;modestbranding=0&amp;rel=1&amp;showinfo=1&amp;playsinline=0&amp;controls=2&amp;autohide=2&amp;theme=dark&amp;color=red&amp;wmode=opaque&amp;vq=&amp;&amp;enablejsapi=1&amp;origin=http://bjjinteractive.ga" frameborder="0" class="__youtube_prefs__" data-vol="0" allowfullscreen=""></iframe>';

                            ?>

                            <?php
                            if (trim($post_thumbnail_html) != '')
                                echo $post_thumbnail_html;
                            ?>
                            <span
                                class="yarpp-thumbnail-title"><?php echo eliminateKeywords(get_the_title(), array("Control", "control")) ?>
                        </span>
                        </a>
                    </div>
                    <?php

                endwhile; // end of the loop.
                ?>


            </div>
            <!-- Bullet Navigator -->
            <div data-u="navigator" class="jssorb03" style="bottom:10px;right:10px;">
                <!-- bullet navigator item prototype -->
                <div data-u="prototype" style="width:21px;height:21px;">
                    <div data-u="numbertemplate"></div>
                </div>
            </div>
            <!-- Arrow Navigator -->
            <span data-u="arrowleft" class="jssora03l" style="top:0px;left:8px;width:55px;height:55px;"
                  data-autocenter="2"></span>
            <span data-u="arrowright" class="jssora03r" style="top:0px;right:8px;width:55px;height:55px;"
                  data-autocenter="2"></span>
        </div>


        <?php
        wp_reset_query();

    }

endif;
add_action('fullframe_before_content', 'next_move', 30);