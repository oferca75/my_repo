<?php
/**
 * The Sidebar containing the primary widget area
 *
 * @package Catch Themes
 * @subpackage Full Frame
 * @since Full Frame 1.0
 */
?>

<?php
/**
 * fullframe_before_secondary hook
 */
do_action('fullframe_before_secondary');

$fullframe_layout = fullframe_get_theme_layout();

if ('no-sidebar' == $fullframe_layout) {
    return;
}

do_action('fullframe_before_primary_sidebar');
?>
    <aside class="sidebar sidebar-primary widget-area" role="complementary">
        <?php
            dynamic_sidebar('front-sidebar');
        ?>
    </aside><!-- .sidebar sidebar-primary widget-area -->

<?php
/**
 * fullframe_after_primary_sidebar hook
 */
do_action('fullframe_after_primary_sidebar');


/**
 * fullframe_after_secondary hook
 *
 */
do_action('fullframe_after_secondary');
