<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>"/>

    <link rel="profile" href="http://gmpg.org/xfn/11"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
    <!-- Optimized mobile viewport -->
    <meta name="viewport" content="width=device-width,minimum-scale=1,initial-scale=1">
    <link rel="pingback" href="<?php bloginfo('pingback_url'); ?>"/>
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<header class="cf">
    <div id="branding" class="g2">
        <h1><a href="<?php echo esc_url(home_url('/')); ?>" rel="home"><?php echo esc_html(get_bloginfo('name')); ?></a>
        </h1>
        <p><?php bloginfo('description'); ?></p>
    </div>
    <?php if (get_query_var('paged') < 2 && !dynamic_sidebar('sidebar-homepage')): ?>
        <div class="g1">
            <p><?php _e('This theme uses the Fluid Baseline Grid and was adapted for WordPress by dizzysoft.', 'fluid-baseline-grid'); ?></p>
            <p><?php _e('You can add your own content here using the Text Widget, or insert any other Widget here to replace this content.', 'fluid-baseline-grid'); ?></p>
        </div>
        <?php
    endif;
    ?>
</header>
<?php
if (has_nav_menu('header-menu')) : ?>
    <nav class="g3" id="access">
        <label for="menuopen"><?php _e('Menu', 'fluid-baseline-grid'); ?></label>
        <input type="checkbox" id="menuopen"/>
        <?php wp_nav_menu(); ?>
    </nav>
<?php endif; ?>
<div class="cf"></div>