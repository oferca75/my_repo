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
//if (!function_exists('next-move')) :

//     function next_move()
//     {
        if(is_front_page()){
            $dontDisplay = true;
        }
        $nextMoveWidth = "808px";
        $postTitle = get_the_title();
        $numberposts = 40;
        $postTitle = $postTitle == "Front" || $postTitle == "" ? "Top Positions" : $postTitle;

        $catId = get_cat_ID($postTitle);
        if(!$dontDisplay && $catId != 0) {
            $args = array('category__in' => array($catId), 'numberposts' => $numberposts);
            $loop = new WP_Query( $args );
        }
        $nextMoveTechniqueTitle = "";
        if ($loop->post_count<=1 && $_COOKIE["last_viewed"]) {
            $gameOver = true;
            $catId = get_cat_ID($_COOKIE["last_viewed"]);
            $nextMoveLoopArgs = array('category__in' => array($catId), 'numberposts' => $numberposts);
            $loop = new WP_Query( $nextMoveLoopArgs );
            $nextMoveTechniqueTitle = $_COOKIE["last_viewed"];
            $dispStr = "Watch more options from the <strong>" . eliminateKeywords($nextMoveTechniqueTitle)."</strong>";
        } else {
            $nextMoveTechniqueTitle = $postTitle;
            if ($_COOKIE["last_viewed"] != $postTitle){
                setcookie("last_viewed", $nextMoveTechniqueTitle, time() + (60 * 60 * 24 * 30), "/"); // 30 days
            }
            if (function_exists("headlineText")) {
                $dispStr = headlineText($nextMoveTechniqueTitle);
            }
        }

        ?>


        <div id="jssor_1"
             <?php
//                if ($gameOver) $nextPositionText = 'Submission. Learn More';
//                    else $nextPositionText = headlineText($postTitle, "next-move-title");
             ?>
             style="position: relative; margin: 0 auto;
                 width: <?php echo $nextMoveWidth ?>; height: 250px; background: #fcfcfc;
                 overflow: hidden; visibility: visible;">
            <?php

            // echo '<h2 class="next-move-title">'.$nextPositionText.'</h2><h4>'. $dispStr .':</h4>';
            ?><div class="next-move-title"><?php echo $dispStr; ?></div>

            <!-- Loading Screen -->
            <div data-u="loading" style="position: absolute; top: 0px; left: 0px;">
                <div
                    style="filter: alpha(opacity=70); opacity: 0.7; position: absolute; display: block; top: 0px; left: 0px; width: 100%; height: 100%;"></div>
                <div
                    style="position:absolute;display:block;background:url('<?php echo get_stylesheet_directory_uri() ?>/css/img/loading.gif') no-repeat center center;top:0px;left:0px;width:100%;height:100%;"></div>
            </div>

            <div data-u="slides"
                 style="cursor: default; position: relative;
                 width: 650px; height: 200px; overflow: hidden;
                 margin-left:70px;
                 margin-top:20px;
                 ">
                <?php
                ob_start();
                $nextMoveCounter = 0;
                while (!$dontDisplay && $loop->have_posts()) :
                    $loop->the_post();
                    if ($postTitle == get_the_title() || $nextMoveTechniqueTitle == get_the_title() )
                        continue;
                    $nextMoveCounter++;
                    ?>
                    <div style="display: none;"><?php

                        ?>
                        <a class='yarpp-thumbnail' href='<?php echo get_permalink() ?>'
                           title='<?php echo the_title_attribute('echo=0') ?>'>
                            <div class='next-overlay'></div>
                            <?php $video_id = get_metadata('post', get_the_ID(), "video_id", true);

                            $post_thumbnail_html = '<img src="http://img.youtube.com/vi/'.$video_id.'/0.jpg" />'
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
                $nextMovesHtml =  ob_get_clean();
                echo $nextMovesHtml;

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

//     }

//endif;
//add_action('fullframe_after_post', 'next_move', 30);
