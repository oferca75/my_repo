<?php get_header(); ?>
<div id="content" class="cf">
    <div class="g3">
        <h1><?php _e('Page Not Found', 'fluid-baseline-grid'); ?></h1>
        <p><?php _e("Sorry, but you are looking for something that doesn&apos;t exist.", 'fluid-baseline-grid'); ?></p>
        <p><?php _e("Try searching for it&hellip;", 'fluid-baseline-grid'); ?></p>
        <?php get_search_form(); ?>
        <p>&nbsp;</p>
    </div>
</div>
<?php get_footer(); ?>
