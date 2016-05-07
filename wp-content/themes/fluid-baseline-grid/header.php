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
<body <?php body_class(); ?> itemscope itemtype="http://schema.org/WebPage">
<header id="branding" class="g3 cf">
    <h2><a href="<?php echo esc_url(home_url('/')); ?>" rel="home"><?php echo esc_html(get_bloginfo('name')); ?></a>
    </h2>
    <p><?php bloginfo('description'); ?></p>
</header>
<?php if (has_nav_menu('header-menu')) { ?>
    <nav class="g3" id="access">
        <label for="menuopen"><?php _e('Menu', 'fluid-baseline-grid'); ?></label>
        <input type="checkbox" id="menuopen"/>
        <?php wp_nav_menu(array('theme_location' => 'header-menu')); ?>
    </nav>
<?php } ?>
<div class="cf"></div>
