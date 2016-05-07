<?php get_header('home'); ?>
<div id="content" class="cf">
    <?php
    if (have_posts()) :
        get_template_part('loop');
        ?>
        <div class="navigation g3 cf"><p><?php posts_nav_link(); ?></p></div>
    <?php endif; ?>
</div>
<?php get_footer(); ?>
