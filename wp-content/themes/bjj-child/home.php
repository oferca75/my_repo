<div class="site-title">
    Brazilian JiuJitsu <b>Interactive</b>
</div>
<div class="site-desc">
    <?php echo esc_html(get_bloginfo('description')) ?>
</div>
<div class='home'>

    <?php
    get_header('archive');
    ?>

</div>
<div class="site-title">
    <b> OR </b></div>
<div class="site-desc">
    Explore Fighting Positions:
</div>


<div id="content" style="text-align: center" class="cf">

    <div class="post intro">
        <!--        <h1> Brazilian JiuJitsu Techniques</h1>-->

        <?php
        ($post->post_title) ? $post->post_title : '';
        $next_moves = query_posts(array('posts_per_page' => 20,
            'category__in' => array(1)));
        ?>
        <div class="home-posts"><?php
            if (have_posts()) :
                get_template_part('loop');
                ?>
                <div class="navigation g3 cf"><p><?php // posts_nav_link();      
                        ?>
                    </p></div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php get_footer(); ?>
