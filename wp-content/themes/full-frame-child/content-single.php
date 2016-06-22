<?php
/**
 * The template used for displaying post content in single.php
 *
 * @package Catch Themes
 * @subpackage Full Frame
 * @since Full Frame 1.0
 */

global $post;

$postId = get_the_ID();
/**
 * @param $postId
 * @return mixed|string|void
 */


$postContent = getPostContent($postId);

?>
<?php require get_stylesheet_directory() . '/inc/get-article.php'; ?>
