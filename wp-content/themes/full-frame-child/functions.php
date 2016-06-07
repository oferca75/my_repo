<?php

/**
 * @param $postTitle
 * @return mixed
 */
function eliminateKeywords($postTitle, $moreWords = array())
{
    if (str_word_count($postTitle) > 2) {
        $postTitle = trim($postTitle);

        $postTitle = eliminateIfEndsWith($postTitle, $moreWords);

        $words = array("Position", "Positions");
        $postTitle = eliminateIfEndsWith($postTitle, $words);

        $words = array("Top", "Bottom");
        $postTitle = eliminateIfEndsWith($postTitle, $words);


    }
    return $postTitle;
}

/**
 * @param $postTitle
 * @param $words
 * @return array
 */
function eliminateIfEndsWith($postTitle, $words)
{
    foreach ($words as $word) {
        if (endsWith($postTitle, $word)) {
            $postTitle = str_replace($word, "", $postTitle);
        }
    }
    return trim($postTitle);
}

function endsWith($haystack, $needle)
{
    // search forward starting from end minus needle length characters
    return $needle === "" || (($temp = strlen($haystack) - strlen($needle)) >= 0 && strpos($haystack, $needle, $temp) !== false);
}

$arrowSVG = "<svg version=\"1.1\" xmlns=\"http://www.w3.org/2000/svg\" data-original-aspect-ratio=\"0.9424221535275619\" style=\"width: 85px; height: 90px;\"><g transform=\"translate(-5.930088996887207,-2.8621416091918945)\"><path d=\"M52.916595 47.775749l35.930886 -35.930886c1.900117 -1.900117,1.900117 -4.836924,0 -6.56426l-1.036689 -1.036689c-0.863907 -0.863907,-2.072899 -1.381773,-3.282369 -1.381773c-1.208992 0,-2.418462 0.518344,-3.282369 1.381773L40.824286 44.493379c-1.036689 0.863907,-1.381773 2.072899,-1.381773 3.282369s0.518344 2.418462,1.381773 3.282369l40.422246 40.422246c0.863907 0.863907,2.072899 1.381773,3.282369 1.381773c1.208992 0,2.418462 -0.518344,3.282369 -1.381773l1.036689 -1.036689c1.900117 -1.900117,1.900117 -4.836924,0 -6.56426L52.916595 47.775749z\"></path><path d=\"M19.404171 47.775749l35.930886 -35.930886c1.900117 -1.900117,1.900117 -4.836924,0 -6.56426l-1.036689 -1.036689c-0.863907 -0.863907,-2.072899 -1.381773,-3.282369 -1.381773c-1.208992 0,-2.418462 0.518344,-3.282369 1.381773L7.311862 44.493379c-0.863907 0.863907,-1.381773 2.072899,-1.381773 3.282369s0.518344 2.418462,1.381773 3.282369l40.422246 40.422246c0.863907 0.863907,2.072899 1.381773,3.282369 1.381773s2.418462 -0.518344,3.282369 -1.381773l1.036689 -1.036689c1.900117 -1.900117,1.900117 -4.836924,0 -6.56426L19.404171 47.775749z\"></path></g></svg>";


function nextMoveText($postTitle, $headline = false)
{
    $dispStr = "";
    if (endsWith($postTitle, "sitions")) {
        $dispStr .= $headline ? "Choose a position" : "Types of <strong>" . eliminateKeywords($postTitle) . "</strong>";
    } else
        if (endsWith($postTitle, "sition")) {
            $dispStr .= $headline ? "Choose a Technique" : "Techniques from the <strong>" . eliminateKeywords($postTitle) . "</strong>";
        } else {
            $dispStr .= $headline ? "Choose next move" : 'What you can do from the  <strong>' . eliminateKeywords($postTitle) . "</strong>";

        };
    return $dispStr;
}


/**
 * Proper way to enqueue scripts and styles.
 */
function front_page_scripts()
{
//    wp_enqueue_style( 'style-name', get_stylesheet_uri() );
    wp_enqueue_script('front-page.js', get_stylesheet_directory_uri() . '/js/front-page.js', array(), '1.0.0', true);
}

add_action('wp_enqueue_scripts', 'front_page_scripts');

function general_scripts()
{
    wp_enqueue_style('jssor', get_stylesheet_directory_uri() . '/css/jssor.css');
    wp_enqueue_script('carousel', get_stylesheet_directory_uri() . '/js/jssor/jssor.slider.mini.js', array(), '1.0.0', true);
    wp_enqueue_script('jssor', get_stylesheet_directory_uri() . '/js/jssor-init.js', array(), '1.0.0', true);
    wp_enqueue_script('general', get_stylesheet_directory_uri() . '/js/general.js', array(), '1.0.0', true);


}

add_action('wp_enqueue_scripts', 'general_scripts');


/**
 * Load Next Move file.
 */
require get_stylesheet_directory() . '/inc/next-move.php';

add_filter('sidebars_widgets', 'hidemywidget');
function hidemywidget($all_widgets)
{
//    echo "<pre>";
//    print_r($all_widgets);
//    echo "</pre>";
//comment the following and tell me what happens!
    global $dd_lastviewed_id;
    $pSideBar = $all_widgets["primary-sidebar"];
    $pSideBar = array_diff($pSideBar, array('lastviewed-' . $dd_lastviewed_id));
    $all_widgets["primary-sidebar"] = $pSideBar;
    return $all_widgets;
}

$dd_lastviewed_id = 3;

$default_video_image = site_url() . "/wp-content/uploads/2016/06/BJJ_black_red_belt.svg_.png";

add_filter('wp_list_categories', 'add_span_cat_count');
function add_span_cat_count($links) {
    $arr  = explode("<li",$links);
    foreach ($arr as $key => $titleLink){
        $origTitle = trim(strip_tags(str_replace("\t","","<li".$titleLink)));
        $mTitle = eliminateKeywords($origTitle);
        if (function_exists("getTrueTitle"))
            {
                $tTitle = getTrueTitle($origTitle);
            }
        $arr[$key] = str_replace("category/","",str_replace('<a href="h','<a class="'.$tTitle.'" href="h',str_replace(trim($origTitle),$mTitle,$titleLink)));
    }
    return implode("<li",$arr);
}

/**
 * @param $title
 * @return string
 */
function getTrueTitle($title)
{
    return trim(str_replace(" ", "-", trim($title)));
}

function fight_path(){
    ?>
    <div class="fight-path">
        <?php
        if (!is_page()) {
            global $dd_lastviewed_id;
            echo do_shortcode('[dd_lastviewed widget_id="'.$dd_lastviewed_id.'"]');
        }
        ?>
    </div>
<?php
}

function modified_post_title($title){
    $newTitle = eliminateKeywords($title);
    if (function_exists("getTrueTitle"))
    {
        $tTitle = getTrueTitle($title);
    }
    ?>
    <h1 class="entry-title <?php echo $tTitle;?>"><?php

    echo $newTitle."</h1>";

}