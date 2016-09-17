<?php

$page_title = single_cat_title("", false);
$post1 = get_page_by_title($page_title, "OBJECT", 'post');
global $post;
$postContent = getPostContent($post1->ID);

get_header();
get_sidebar();
$postId = $post1->ID;
$post = get_post($postId);

?>

<main id="main" class="site-main" role="main">
    <?php require get_stylesheet_directory() . '/inc/get-article.php'; ?>
    <?php do_action('fullframe_after_post');

    /**
     * fullframe_comment_section hook
     *
     * @hooked fullframe_get_comment_section - 10
     */
    do_action('fullframe_comment_section');
    ?>

</main><!-- #main -->

<?php  ?>
<?php get_footer(); ?>

