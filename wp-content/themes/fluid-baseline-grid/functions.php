<?php
if (!isset($content_width)) {
    $content_width = 400;
}

function fluid_baseline_grid_styles_scripts()
{
    wp_enqueue_style('fluid-baseline-grid-normalize', get_template_directory_uri() . '/fbg/css/fluid-baseline-grid-normalize.css', array(), '3.0.2');
    wp_enqueue_script('fluid-baseline-grid-respond', get_template_directory_uri() . '/fbg/js/fluid-baseline-grid-respond.min.js', array(), '1.4.2', true);
    wp_enqueue_style('fluid-baseline-grid-default', get_template_directory_uri() . '/fbg/css/fluid-baseline-grid-default.css', array('fluid-baseline-grid-normalize'));
    wp_enqueue_style('fluid-baseline-grid-style', get_stylesheet_uri(), array('fluid-baseline-grid-default'));

    //http://scribu.net/wordpress/optimal-script-loading.html to load comment-reply
    if (is_singular() && get_option('thread_comments'))
        wp_enqueue_script('comment-reply');
}

add_action('wp_enqueue_scripts', 'fluid_baseline_grid_styles_scripts');

function fluid_baseline_grid_register_html5shim()
{
    wp_register_script('fluid-baseline-grid-html5shim', get_template_directory_uri() . '/fbg/js/fluid-baseline-grid-html5shiv.min.js', array(), '3.7.3', true);
}

add_action('init', 'fluid_baseline_grid_register_html5shim');

function fluid_baseline_grid_footer()
{
    // HTML5 IE Enabling Script
    echo "<!--[if lt IE 9]>";
    wp_print_scripts('fluid-baseline-grid-html5shim');
    echo "<![endif]-->\n";
}

add_action('wp_footer', 'fluid_baseline_grid_footer');

function fluid_baseline_grid_my_menu_args($args = '')
{
    //$args['container_class']= 'nav cf';
    $args['container_class'] = 'menu';
    //$args['menu_class']= 'cf';
    $args['menu_class'] = 'sf-menu';
    //$args['menu_id']= 'main-menu';
    return $args;
}

add_filter('wp_nav_menu_args', 'fluid_baseline_grid_my_menu_args');

function fluid_baseline_grid_tag_cloud_widget($args)
{
    $args['largest'] = 1; //largest tag
    $args['smallest'] = 1; //smallest tag
    $args['separator'] = ' - ';
    $args['unit'] = 'em'; //tag font unit
    return $args;
}

add_filter('widget_tag_cloud_args', 'fluid_baseline_grid_tag_cloud_widget');

function fluid_baseline_grid_setup()
{
    add_theme_support('custom-background', array(
        'wp-head-callback' => '_custom_background_cb',
        'default-image' => get_template_directory_uri() . '/fbg/images/fluid-baseline-grid-24px_grid_bg.gif'
    ));
    add_theme_support('html5', array('comment-list', 'comment-form', 'search-form'));
    add_theme_support('post-thumbnails');
    add_theme_support('automatic-feed-links');
    add_theme_support('title-tag');
    add_editor_style(get_stylesheet_uri());
    register_nav_menu('header-menu', __('Header Menu', 'fluid-baseline-grid'));
    load_theme_textdomain('fluid-baseline-grid', get_template_directory() . '/languages');
    set_post_thumbnail_size(300, 144);
    add_image_size('fluid-baseline-grid-featured-img', 300, 144);
}

add_action('after_setup_theme', 'fluid_baseline_grid_setup');

// http://darrinb.com/notes/2008/get-the-last-page-of-a-post-in-wordpress/2/
function fluid_baseline_grid_is_last_page()
{
    global $page, $numpages, $multipage;
    if ($multipage) {
        return ($page === $numpages) ? true : false;
    } else {
        return true;
    };
}

;
function fluid_baseline_grid_page_number()
{
    global $page, $multipage;
    if ($multipage) {
        return $page;
    } else {
        return 0;
    };
}

;

function fluid_baseline_grid_my_excerpt_password_form($excerpt)
{
    if (post_password_required())
        $excerpt = get_the_password_form();
    return $excerpt;
}

add_filter('the_excerpt', 'fluid_baseline_grid_my_excerpt_password_form');

// http://www.wpbeginner.com/wp-themes/how-to-add-dynamic-widget-ready-sidebars-in-wordpress/
function fluid_baseline_grid_widgets_init()
{

    register_sidebar(array(
        'name' => __('Homepage Header', 'fluid-baseline-grid'),
        'id' => 'sidebar-homepage',
        'description' => __('This widget appears on the top-right corner of the homepage. RECOMMENDATIONS: this is a great place for unique homepage text to describe your website by using a text widget; only put one widget here.', 'fluid-baseline-grid'),
        'before_widget' => '<div class="g1">',
        'after_widget' => '</div>',
        'before_title' => '<h3 class="widget-title">',
        'after_title' => '</h3>',
    ));

    register_sidebar(array(
        'name' => __('Sitewide Footer', 'fluid-baseline-grid'),
        'id' => 'sidebar-footer',
        'description' => __('These widgets will appear in the footer of the homepage. RECOMMENDATION: add widgets in multiples of 3.', 'fluid-baseline-grid'),
        'before_widget' => '<div class="g1">',
        'after_widget' => '</div>',
        'before_title' => '<h3 class="widget-title">',
        'after_title' => '</h3>',
    ));

    register_sidebar(array(
        'name' => __('Sidebar for Page (optional)', 'fluid-baseline-grid'),
        'id' => 'page-sidebar',
        'before_title' => '<h3 class="widget-title">',
        'after_title' => '</h3>',
    ));

    register_sidebar(array(
        'name' => __('Sidebar for Post (optional)', 'fluid-baseline-grid'),
        'id' => 'post-sidebar',
        'before_title' => '<h3 class="widget-title">',
        'after_title' => '</h3>',
    ));
}

add_action('widgets_init', 'fluid_baseline_grid_widgets_init');

function fluid_baseline_grid_custom_excerpt_length($length)
{
    return 35;
}

add_filter('excerpt_length', 'fluid_baseline_grid_custom_excerpt_length', 999);

function fluid_baseline_grid_excerpt_more($more)
{
    return '&hellip;';
}

add_filter('excerpt_more', 'fluid_baseline_grid_excerpt_more');
?>
