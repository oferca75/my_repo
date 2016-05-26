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



function nextMoveText($postTitle)
{
    $dispStr = "";
    if (endsWith($postTitle, "sitions")) {
        $dispStr .= "Choose a " . rtrim(eliminateKeywords($postTitle),"s");
    } else
    if (endsWith($postTitle, "sition")) {
        $dispStr .= "Choose what to do from the " . eliminateKeywords($postTitle);
    } else {
        $dispStr .= 'Choose next move from the ' . eliminateKeywords($postTitle);

    };
    return $dispStr;
}


/**
 * Proper way to enqueue scripts and styles.
 */
function front_page_scripts() {
//    wp_enqueue_style( 'style-name', get_stylesheet_uri() );
    wp_enqueue_script( 'front-page.js', get_stylesheet_directory_uri() . '/js/front-page.js', array(), '1.0.0', true );
}
add_action( 'wp_enqueue_scripts', 'front_page_scripts' );

function general_scripts() {
    wp_enqueue_style( 'jssor', get_stylesheet_directory_uri().'/css/jssor.css' );
    wp_enqueue_script( 'carousel', get_stylesheet_directory_uri() . '/js/jssor/jssor.slider.mini.js', array(), '1.0.0', true );
    wp_enqueue_script( 'general', get_stylesheet_directory_uri() . '/js/general.js', array(), '1.0.0', true );

}
add_action( 'wp_enqueue_scripts', 'general_scripts' );


/**
 * Load Next Move file.
 */
require get_stylesheet_directory() . '/inc/next-move.php';