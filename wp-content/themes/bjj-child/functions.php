<?php
add_action('widgets_init', 'child_register_sidebar');

function child_register_sidebar()
{
    register_sidebar(array(
        'name' => 'Widget Area Top',
        'id' => 'widget-area-top',
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h4 class="widgettitle">',
        'after_title' => '</h4>',
    ));

    register_sidebar(array(
        'name' => 'Main Sidebar',
        'id' => 'main-sidebar',
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h4 class="widgettitle">',
        'after_title' => '</h4>',
    ));
}


/**
 * @param $postTitle
 * @return mixed
 */
function eliminateKeywords($postTitle)
{
    $words = array("Top", "Bottom");
    foreach ($words as $word) {
        if (endsWith($postTitle, $word)) {
            $postTitle = str_replace($word, "", $postTitle);
        }
    }
//    if (endsWith($postTitle,"ass")) {
//        $postTitle = str_replace("pass", "", $postTitle);
//        $postTitle = str_replace("Pass", "", $postTitle);
//    }
//    }
    return $postTitle;
}

function endsWith($haystack, $needle)
{
    // search forward starting from end minus needle length characters
    return $needle === "" || (($temp = strlen($haystack) - strlen($needle)) >= 0 && strpos($haystack, $needle, $temp) !== false);
}