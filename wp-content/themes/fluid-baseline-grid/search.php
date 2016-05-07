<?php get_header(); ?>
<div id="content" class="cf">
    <?php
    if (have_posts()) :
        ?>
        <div class="g3 cf">
        <h2><?php printf(__('Found: %s', 'fluid-baseline-grid'), get_search_query()); ?></h2>
        </div><?php
        get_template_part('loop');
        ?>
        <div class="navigation g3 cf"><p><?php posts_nav_link(); ?></p></div>
    <?php else: ?>
        <div class="g3 cf">
            <h2><?php printf(__('Not Found: %s', 'fluid-baseline-grid'), get_search_query()); ?></h2>
            <p><?php _e("Try another search&hellip;", 'fluid-baseline-grid'); ?></p>
            <?php get_search_form(); ?>
            <p>&nbsp;</p>
        </div>
    <?php endif; ?>
</div>
<?php get_footer(); ?>
