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
    <div id="branding" class="g1">
        <h2><a href="<?php echo esc_url(home_url('/')); ?>" rel="home"><?php echo esc_html(get_bloginfo('name')); ?></a>
        </h2>
        <p><?php bloginfo('description'); ?></p>
    </div>
    <div class="g2" id="archive-head">
        <?php
        if (have_posts()) :

            if (is_archive()) {
                $archive_page = get_query_var('paged');
                /* If this is a category archive */
                if (is_category()) { ?>
                    <h1><?php single_cat_title(''); ?></h1>
                    <?php if ($archive_page < 2) {
                        echo category_description();
                    } ?>

                    <?php /* If this is a tag page */
                } elseif (is_tag()) { ?>
                    <h1><?php single_tag_title(''); ?></h1>
                    <?php if ($archive_page < 2) {
                        echo "<p>" . tag_description() . "</p>";
                    } ?>

                    <?php /* If this is an author page */
                } elseif (is_author()) { ?>
                    <h1><?php the_author_meta('display_name'); ?></h1>
                    <?php if ($archive_page < 2) {
                        nl2br("<p>" . the_author_meta('description') . "</p>");
                    } ?>

                    <?php /* If this is a yearly archive */
                } else { ?>
                    <h1><?php
                    _e('Archive for ', 'fluid-baseline-grid');
                    if (is_day()) the_time(get_option('date_format'));
                    if (is_month()) the_time('F Y');
                    if (is_year()) the_time('Y');
                    ?></h1><?php
                }
                if ($archive_page > 1) {
                    printf("<p>%s %u</p>", __("Page", 'fluid-baseline-grid'), $archive_page);
                }
            }
        endif; ?>
    </div>
</header>
<?php if (has_nav_menu('header-menu')) { ?>
    <nav class="g3" id="access">
        <label for="menuopen"><?php _e('Menu', 'fluid-baseline-grid'); ?></label>
        <input type="checkbox" id="menuopen"/>
        <?php wp_nav_menu(array('theme_location' => 'header-menu')); ?>
    </nav>
<?php } ?>
<div class="cf"></div>
