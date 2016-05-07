<div class="cf"></div>
<div id="bottom" class="cf">
    <?php if (!dynamic_sidebar('sidebar-footer')): ?>
        <div id="search" class="widget-container widget_search g1">
            <h3 class="widget-title"><?php _e('Search', 'fluid-baseline-grid'); ?></h3>
            <?php get_search_form(); ?>
        </div>
        <div class="g1">
            <h3 class="widget-title"><?php _e('Categories', 'fluid-baseline-grid'); ?></h3>
            <ul>
                <?php wp_list_categories(array('title_li' => '')); ?>
            </ul>
        </div>
        <div class="g1">
            <h3 class="widget-title"><?php _e('Tags', 'fluid-baseline-grid'); ?></h3>
            <?php wp_tag_cloud(); ?>
        </div>

    <?php endif; ?>
</div>
<footer class="g3 cf">
    <small>&copy; <?php echo date('Y'); ?> <?php bloginfo('name') ?>.</small>
</footer>
<?php wp_footer(); ?>
</body>
</html>
