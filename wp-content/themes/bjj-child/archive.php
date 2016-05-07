<?php get_header(); ?>

<!--<aside id="widget-area-top">-->
<!--    --><?php //if ( !function_exists ( 'dynamic_sidebar' ) || !dynamic_sidebar ( 'widget-area-top' ) ) {
//    } ?>
<!--</aside>-->

<?php get_sidebar();

?>

<div id="content" class="cf">
    <?php
    $postTitle = single_cat_title('', false);
    ?><h1 itemprop="headline" class="entry-title"><?php echo $postTitle ?></h1>
    <?php
    if (function_exists('yoast_breadcrumb')) {
        yoast_breadcrumb('<p id="breadcrumbs">', '</p>');
    }

    ?>
    <div id="cat-desc"><?php
        echo category_description(); ?>
    </div><?php
    $catId = get_query_var('cat');
    $queryParams = array('posts_per_page' => 20,
        'category__in' => array($catId),
    );

    $next_moves = query_posts($queryParams);
    if (have_posts()) :
        get_template_part('loop');
        ?>
        <div class="navigation g3 cf"><p><?php posts_nav_link(); ?></p></div>
    <?php endif; ?>
</div>
<?php get_footer(); ?>
