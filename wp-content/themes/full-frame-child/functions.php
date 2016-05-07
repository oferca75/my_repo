<?php

/**
 * @param $postTitle
 * @return mixed
 */
function eliminateKeywords($postTitle)
{
    if (str_word_count($postTitle) > 2) {
        $postTitle = trim($postTitle);
        $words = array("Position", "Positions");
        $postTitle = eliminate($postTitle, $words);

        $words = array("Top", "Bottom");
        $postTitle = eliminate($postTitle, $words);
    }
    return $postTitle;
}

/**
 * @param $postTitle
 * @param $words
 * @return array
 */
function eliminate($postTitle, $words)
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