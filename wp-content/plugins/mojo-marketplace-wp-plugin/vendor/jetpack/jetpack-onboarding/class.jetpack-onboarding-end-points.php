<?php

class Jetpack_Onboarding_EndPoints
{
    const AJAX_NONCE = 'jpo-ajax';
    const STEP_STATUS_KEY = 'jpo_step_statuses';
    const FIRSTRUN_KEY = 'jpo_firstrun';
    const STARTED_KEY = 'jpo_started';
    const DISABLED_KEY = 'jpo_disabled';
    const CONTACTPAGE_ID_KEY = 'jpo_contactpage_id';
    const MAX_THEMES = 3;
    const NUM_RAND_THEMES = 3;
    const VERSION = "1.1";

    //static $default_themes = array( 'writr', 'flounder', 'sorbet', 'motif', 'hexa', 'twentyfourteen', 'twentytwelve', 'responsive', 'bushwick', 'singl', 'tonal', 'fontfolio', 'hemingway-rewritten', 'skylark' , 'twentythirteen' , 'twentyeleven' );
    static $themes;

    static function init()
    {
        if (defined('DOING_AJAX') && DOING_AJAX) {
            self::init_ajax();
        }
    }

    static function init_ajax()
    {
        if (is_admin()) {
            add_action('wp_ajax_jpo_set_title', array(__CLASS__, 'set_title'));
            add_action('wp_ajax_jpo_set_layout', array(__CLASS__, 'set_layout'));
            add_action('wp_ajax_jpo_build_contact_page', array(__CLASS__, 'build_contact_page'));
            add_action('wp_ajax_jpo_set_theme', array(__CLASS__, 'set_theme'));
            add_action('wp_ajax_jpo_install_theme', array(__CLASS__, 'install_theme'));
            add_action('wp_ajax_jpo_get_popular_themes', array(__CLASS__, 'get_popular_themes'));
            add_action('wp_ajax_jpo_configure_jetpack', array(__CLASS__, 'configure_jetpack'));
            add_action('wp_ajax_jpo_activate_jetpack_modules', array(__CLASS__, 'activate_jetpack_modules'));
            add_action('wp_ajax_jpo_deactivate_jetpack_modules', array(__CLASS__, 'deactivate_jetpack_modules'));
            add_action('wp_ajax_jpo_list_jetpack_modules', array(__CLASS__, 'list_jetpack_modules'));
            add_action('wp_ajax_jpo_step_skip', array(__CLASS__, 'step_skip'));
            add_action('wp_ajax_jpo_step_view', array(__CLASS__, 'step_view'));
            add_action('wp_ajax_jpo_step_complete', array(__CLASS__, 'step_complete'));
            add_action('wp_ajax_jpo_started', array(__CLASS__, 'started'));
            add_action('wp_ajax_jpo_disabled', array(__CLASS__, 'disabled'));
            add_action('wp_ajax_jpo_reset_data', array(__CLASS__, 'reset_data'));
        }
    }

    static function js_vars()
    {
        $step_statuses = get_option(self::STEP_STATUS_KEY, array());
        $started = get_option(self::STARTED_KEY, false);
        $contact_page_id = get_option(self::CONTACTPAGE_ID_KEY, false);

        if ($contact_page_id) {
            $contact_page_info = self::contact_page_to_json($contact_page_id);
        } else {
            $contact_page_info = null;
        }

        $jetpack_config = array();

        if (class_exists('Jetpack')) {
            $jetpack_config = array(
                'plugin_active' => true,
                'configured' => Jetpack::is_active(),
                'logo_url' => plugins_url('jetpack/images/jetpack-logo.png'),
                'jumpstart_modules' => array_values(self::jumpstart_modules()),
                'additional_modules' => array(),
                'active_modules' => array_values(Jetpack::init()->get_active_modules())
            );
        } else {
            $jetpack_config = array(
                'plugin_active' => false,
                'configured' => false,
                'logo_url' => '',
                'jumpstart_modules' => array(),
                'additional_modules' => array(),
                'active_modules' => array()
            );
        }

        // set the jetpack step status to "completed" if jetpack is active
        if ($jetpack_config['configured']) {
            $step_statuses['jetpack'] = array('completed' => true);
        }

        return array(
            'base_url' => JETPACK_ONBOARDING_BASE_URL,
            'site_url' => site_url(),
            'nonce' => wp_create_nonce(Jetpack_Onboarding_EndPoints::AJAX_NONCE),
            'debug' => WP_DEBUG ? true : false,
            'bloginfo' => array(
                'name' => wp_kses_decode_entities(stripslashes(get_bloginfo('name'))),
                'description' => wp_kses_decode_entities(stripslashes(get_bloginfo('description')))
            ),
            'site_actions' => array(
                'set_title' => 'jpo_set_title',
                'set_layout' => 'jpo_set_layout',
                'set_theme' => 'jpo_set_theme',
                'install_theme' => 'jpo_install_theme',
                'get_popular_themes' => 'jpo_get_popular_themes',
                'configure_jetpack' => 'jpo_configure_jetpack',
                'activate_jetpack_modules' => 'jpo_activate_jetpack_modules',
                'deactivate_jetpack_modules' => 'jpo_deactivate_jetpack_modules',
                'list_jetpack_modules' => 'jpo_list_jetpack_modules',
                'reset_data' => 'jpo_reset_data',
                'build_contact_page' => 'jpo_build_contact_page'
            ),
            'step_actions' => array(
                'start' => 'jpo_started',
                'disable' => 'jpo_disabled',
                'view' => 'jpo_step_view',
                'skip' => 'jpo_step_skip',
                'complete' => 'jpo_step_complete'
            ),
            'jetpack' => $jetpack_config,
            'started' => $started,
            'step_status' => $step_statuses,
            'steps' => array(
                'layout' => self::get_layout(),
                'contact_page' => $contact_page_info,
                'advanced_settings' => array(
                    'jetpack_modules_url' => admin_url('admin.php?page=jetpack_modules'),
                    'jetpack_dash' => admin_url('admin.php?page=jetpack'),
                    'widgets_url' => admin_url('widgets.php'),
                    'themes_url' => admin_url('themes.php'),
                    'plugins_url' => admin_url('plugins.php'),
                    'customize_url' => wp_customize_url(),
                    'new_blog_post_url' => admin_url('post-new.php'),
                    'manage_posts_url' => admin_url('edit.php'),
                    'new_page_url' => admin_url('post-new.php?post_type=page'),
                    'manage_pages_url' => admin_url('edit.php?post_type=page')
                )
            ),
        );
    }

    static function contact_page_to_json($page_id)
    {
        $contact_page = get_post($page_id);

        return array(
            'url' => $contact_page->guid,
            'editUrl' => get_edit_post_link($page_id),
            'post_title' => $contact_page->post_title,
            'post_content' => $contact_page->post_content
        );
    }

    static function jumpstart_modules()
    {
        $modules = Jetpack_Admin::init()->get_modules();

        $module_info = array();
        foreach ($modules as $module => $value) {
            if (in_array('Jumpstart', $value['feature'])) {
                $module_info[] = array(
                    'slug' => $value['module'],
                    'name' => $value['name'],
                    'description' => $value['jumpstart_desc'],
                    'configure_url' => $value['configurable'] ? $value['configure_url'] : null,
                );
            }
        }
        return $module_info;
    }

    static function get_layout()
    {
        $posts_url = $welcome_url = '';
        if (get_option('show_on_front') == 'page') {
            if (get_option('page_for_posts') == 0 || get_option('page_for_posts') == null) {
                $layout = 'website';
                $welcome_url = get_edit_post_link(get_option('page_on_front'));
                $posts_url = '';
            } else {
                $layout = 'site-blog';
                $welcome_url = get_edit_post_link(get_option('page_on_front'));
                $posts_url = get_edit_post_link(get_option('page_for_posts'));
            }
        } else {
            $layout = 'blog';
        }

        return array(
            'current' => $layout,
            'welcomeEditUrl' => $welcome_url,
            'postsEditUrl' => $posts_url,
        );
    }

    static function default_theme_filter($theme)
    {
        return (in_array($theme['id'], self::$default_themes) || get_stylesheet() == $theme['id']);
    }

    static function existing_theme_filter($theme)
    {
        return !wp_get_theme($theme->slug)->exists();
    }

    static function get_popular_themes()
    {
        // add_action( 'wp_ajax_jpo_set_theme', array( __CLASS__, 'set_theme' ) );
        global $theme_field_defaults;
        $args = array(
            'browse' => 'popular',
            'per_page' => 40,
            'fields' => $theme_field_defaults
        );
        $themes = themes_api('query_themes', $args);
        if (is_wp_error($themes)) {
            wp_send_json_error("There was an error loading themes: " . $themes->get_error_message());
            die();
        }
        $non_installed_themes = array_filter($themes->themes, array(__CLASS__, 'existing_theme_filter'));
        $rand_keys_to_exclude = array_rand($non_installed_themes, (sizeof($non_installed_themes) - self::NUM_RAND_THEMES));

        $random_non_installed_themes = array_diff_key($non_installed_themes, array_flip($rand_keys_to_exclude));


        // error_log(print_r($random_non_installed_themes, true));

        wp_send_json_success(array_map(array(__CLASS__, 'normalize_api_theme'), array_values($random_non_installed_themes)));
    }

    static function normalize_api_theme($theme)
    {
        return array(
            'id' => $theme->slug,
            'screenshot' => array($theme->screenshot_url),
            'name' => $theme->name,
            'author' => $theme->author,
            'description' => $theme->description,
            'active' => false,
            'installed' => false
        );
    }

    static function normalize_installed_theme($theme)
    {
        return array(
            'id' => $theme['id'],
            'screenshot' => array($theme['screenshot'][0]),
            'name' => $theme['name'],
            'author' => $theme['author'],
            'description' => $theme['description'],
            'active' => $theme['active'],
            'installed' => true
        );
    }

    static function reset_data()
    {
        check_ajax_referer(self::AJAX_NONCE, 'nonce');

        delete_option(self::STEP_STATUS_KEY);
        delete_option(self::STARTED_KEY);

        wp_send_json_success('deleted');
    }

    static function activate_jetpack_modules()
    {
        check_ajax_referer(self::AJAX_NONCE, 'nonce');

        // shamelessly copied from class.jetpack.php
        $modules = $_REQUEST['modules'];
        $modules = array_map('sanitize_key', $modules);
        // $modules_filtered = Jetpack::init()->filter_default_modules( $modules );

        foreach ($modules as $module_slug) {
            Jetpack::log('activate', $module_slug);
            Jetpack::activate_module($module_slug, false, false);
            Jetpack::state('message', 'no_message');
        }

        //XXX TODO: determine whether this is really useful
        // self::set_default_publicize_config();

        wp_send_json_success($modules);
    }

    static function deactivate_jetpack_modules()
    {
        check_ajax_referer(self::AJAX_NONCE, 'nonce');

        // shamelessly copied from class.jetpack.php
        $modules = $_REQUEST['modules'];
        $modules = array_map('sanitize_key', $modules);
        // $modules_filtered = Jetpack::init()->filter_default_modules( $modules );

        foreach ($modules as $module_slug) {
            Jetpack::log('deactivate', $module_slug);
            Jetpack::deactivate_module($module_slug);
            Jetpack::state('message', 'module_deactivated');
        }

        wp_send_json_success($modules);
    }

    // shamelessly copied from class.jetpack.php

    static function list_jetpack_modules()
    {
        check_ajax_referer(self::AJAX_NONCE, 'nonce');
        $modules = Jetpack_Admin::init()->get_modules();

        $module_info = array();
        foreach ($modules as $module => $value) {
            $module_info[] = array(
                'slug' => $value['module'],
                'name' => $value['name'],
                'description' => $value['jumpstart_desc'] ? $value['jumpstart_desc'] : $value['description'],
                'configure_url' => $value['configurable'] ? $value['configure_url'] : null,
            );
        }

        wp_send_json_success(array_values($module_info));
    }

    static function set_default_publicize_config()
    {
        // Set the default sharing buttons and set to display on posts if none have been set.
        $sharing_services = get_option('sharing-services');
        $sharing_options = get_option('sharing-options');
        if (empty($sharing_services['visible'])) {
            // Default buttons to set
            $visible = array(
                'twitter',
                'facebook',
                'google-plus-1',
            );
            $hidden = array();

            // Set some sharing settings
            $sharing = new Sharing_Service();
            $sharing_options['global'] = array(
                'button_style' => 'icon',
                'sharing_label' => $sharing->default_sharing_label,
                'open_links' => 'same',
                'show' => array('post'),
                'custom' => isset($sharing_options['global']['custom']) ? $sharing_options['global']['custom'] : array()
            );

            update_option('sharing-options', $sharing_options);
            update_option('sharing-services', array('visible' => $visible, 'hidden' => $hidden));
        }
    }

    static function started()
    {
        check_ajax_referer(self::AJAX_NONCE, 'nonce');
        update_option(self::STARTED_KEY, true);
        do_action('jpo_started');
        wp_send_json_success('true');
    }

    static function disabled()
    {
        check_ajax_referer(self::AJAX_NONCE, 'nonce');
        update_option(self::DISABLED_KEY, true);
        do_action('jpo_disabled');
        wp_send_json_success('true');
    }

    static function step_view()
    {
        check_ajax_referer(self::AJAX_NONCE, 'nonce');
        do_action('jpo_step_viewed', $_REQUEST['step']);
        wp_send_json_success('true');
    }

    static function step_skip()
    {
        check_ajax_referer(self::AJAX_NONCE, 'nonce');
        $result = self::update_step_status($_REQUEST['step'], 'skipped', true);
        do_action('jpo_step_skipped', $_REQUEST['step']);
        wp_send_json_success($result);
    }

    static function update_step_status($step, $field, $value)
    {
        $step_statuses = get_option(self::STEP_STATUS_KEY, array());

        if (!array_key_exists($step, $step_statuses)) {
            $step_statuses[$step] = array();
        }

        $step_statuses[$step][$field] = $value;
        update_option(self::STEP_STATUS_KEY, $step_statuses);
        return $step_statuses;
    }

    static function step_complete()
    {
        check_ajax_referer(self::AJAX_NONCE, 'nonce');
        self::update_step_status($_REQUEST['step'], 'completed', true);
        $result = self::update_step_status($_REQUEST['step'], 'skipped', false);

        if (array_key_exists('data', $_REQUEST)) {
            $data = $_REQUEST['data'];
        } else {
            $data = null;
        }

        do_action('jpo_step_complete', $_REQUEST['step'], $data);
        wp_send_json_success($result);
    }

    static function set_title()
    {
        check_ajax_referer(self::AJAX_NONCE, 'nonce');

        $title = wp_unslash($_REQUEST['title']); // sanitization (sanitize_option) is run in update_option
        $description = wp_unslash($_REQUEST['description']); // sanitization (sanitize_option) is run in update_option

        $updated_title = get_option('blogname') === $title || update_option('blogname', $title);
        $updated_description = get_option('blogdescription') === $description || update_option('blogdescription', $description);

        if ($updated_title && $updated_description) {
            wp_send_json_success($title);
        } else {
            wp_send_json_error();
        }
    }

    static function build_contact_page()
    {
        check_ajax_referer(self::AJAX_NONCE, 'nonce');

        $jpo_contact_us_page = array(
            'post_title' => 'Contact Us',
            'post_content' => 'Hi There,
We are looking forward to hearing from you. Please feel free to get in touch via the form below, we will get back to you as soon as possible.

A Great Company Name
123 Main St,
Warwick, RI 02889
718.555.0062

<!-- The form below requires a jetpack connection to work-->
[contact-form][contact-field label=\'Name\' type=\'name\' required=\'1\'/][contact-field label=\'Email\' type=\'email\' required=\'1\'/][contact-field label=\'Comment\' type=\'textarea\' required=\'1\'/][/contact-form]',
            'post_status' => 'publish',
            'post_type' => 'page'
        );

        // Insert the page into the database
        $page_id = wp_insert_post($jpo_contact_us_page);

        if (0 !== $page_id) {
            update_option(self::CONTACTPAGE_ID_KEY, $page_id);
            do_action('jpo_contact_page_built');
            wp_send_json_success(self::contact_page_to_json($page_id));
        } else {
            wp_send_json_error($page_id);
        }
    }

    static function set_layout()
    {
        check_ajax_referer(self::AJAX_NONCE, 'nonce');
        $layout = esc_html($_REQUEST['layout']);

        if ($layout == 'website') {
            self::set_layout_to_website();
        } elseif ($layout == 'site-blog') {
            self::set_layout_to_site_with_blog();
        } elseif ($layout == 'blog') {
            self::set_layout_to_blog();
        } else {
            wp_send_json_error('Unknown layout type: ' . $layout);
        }
    }

    static function set_layout_to_website()
    {
        // no posts page for this layout
        update_option('page_for_posts', null);

        $page_on_front = self::set_front_page_to_page();

        wp_send_json_success(array(
            'current' => 'website',
            'welcome' => get_edit_post_link($page_on_front),
            'posts' => '',
        ));
        die();
    }

    static function set_front_page_to_page()
    {
        // ensure that front page is a static page
        if (get_option('show_on_front') == 'posts') {
            update_option('show_on_front', 'page');
        }

        // if no specific front page already set, find first or create
        $existing_front_page = get_option('page_on_front') &&
            (get_option('page_on_front') != 0) &&
            get_page(get_option('page_on_front'));

        if ($existing_front_page) {
            return $existing_front_page;
        } else {
            // Always create a new page
            $page = array(
                'post_type' => 'page',
                'post_title' => 'Home page',
                'post_name' => 'home',
                'post_content' => "This is your front page. Click the 'edit' link to change the contents",
                'post_status' => 'publish',
                'comment_status' => 'closed'
            );

            $page_id = wp_insert_post($page);

            if ($page_id != 0) {
                update_option('page_on_front', $page_id);
                return $page_id;
            }

            wp_send_json_error();
            die();
        }
    }

    // try to activate the plugin if necessary and kick off the jetpack connection flow
    // in a single action (possibly in a dialog / iframe / something?)

    static function set_layout_to_site_with_blog()
    {
        $page_on_front = self::set_front_page_to_page();

        $blog_page = get_page_by_path('blog');

        if ($blog_page != null) {
            $page_id = $blog_page->ID;
        } else {
            // create page
            $page = array(
                'post_type' => 'page',
                'post_title' => 'Blog',
                'post_name' => 'blog',
                'post_content' => '',
                'post_status' => 'publish',
                'comment_status' => 'open'
            );

            $page_id = wp_insert_post($page);
        }

        if ($page_id == 0) {
            wp_send_json_error();
            die();
        }

        update_option('page_for_posts', $page_id);
        wp_send_json_success(array(
            'current' => 'site-blog',
            'welcome' => get_edit_post_link($page_on_front),
            'posts' => get_edit_post_link($page_id),
        ));
    }

    static function set_layout_to_blog()
    {
        if (get_option('show_on_front') == 'page') {
            update_option('show_on_front', 'posts');
        }
        wp_send_json_success(array(
            'current' => 'blog',
            'welcome' => '',
            'posts' => '',
        ));
    }

    static function set_theme()
    {
        check_ajax_referer(self::AJAX_NONCE, 'nonce');
        $theme_id = $_REQUEST['themeId'];
        $theme = wp_get_theme($theme_id);

        // try to install the theme if it doesn't exist
        if (!$theme->exists()) {
            wp_send_json_error('Theme does not exist: ' . $theme_id);
            die();
        }

        if (!$theme->is_allowed()) {
            wp_send_json_error('Action not permitted for ' . $theme_id);
            die();
        }

        switch_theme($theme->get_stylesheet());
        wp_send_json_success($theme_id);
    }

    static function install_theme()
    {
        check_ajax_referer(self::AJAX_NONCE, 'nonce');
        $theme_id = $_REQUEST['themeId'];
        $theme = wp_get_theme($theme_id);

        if (!$theme->exists()) {
            include_once(ABSPATH . 'wp-admin/includes/class-wp-upgrader.php');
            include_once(ABSPATH . 'wp-admin/includes/theme-install.php');

            $theme_info = themes_api('theme_information', array(
                'slug' => $theme_id,
                'fields' => array('sections' => false, 'tags' => false)
            ));

            error_log(print_r($theme_info, true));

            if (is_wp_error($theme_info)) {
                wp_send_json_error('Could not look up theme ' . $theme_id . ': ' . $theme_info->get_error_message());
                die();
            } else {
                $upgrader = new Theme_Upgrader(new Automatic_Upgrader_Skin());
                $install_response = $upgrader->install($theme_info->download_link);

                if (is_wp_error($install_response)) {
                    wp_send_json_error('Could not install theme: ' . $install_response->get_error_message());
                    die();
                } elseif (!$install_response) {
                    wp_send_json_error('Could not install theme (unspecified server error)');
                    die();
                }
            }
        }

        wp_send_json_success($theme_id);
    }

    static function configure_jetpack()
    {
        check_ajax_referer(self::AJAX_NONCE, 'nonce');
        $return_to_step = $_REQUEST['return_to_step'];

        if (!is_plugin_active('jetpack')) {
            activate_plugin('jetpack');
        }

        if (!class_exists('Jetpack')) {
            wp_send_json_error('There was a problem activating Jetpack');
            die();
        }

        if (!Jetpack::is_active()) {

            if (!Jetpack_Options::get_option('blog_token') || !Jetpack_Options::get_option('id')) {
                $result = Jetpack::try_registration();
                if (is_wp_error($result)) {
                    $error = $result->get_error_code();
                    $message = $result->get_error_message();
                    wp_send_json_error($message . ' (code: ' . $error . ')');
                    die();
                }
            }

            $jp_landing_page = new Jetpack_Landing_Page();
            $jp_landing_page->add_actions();

            // redirect to activate link
            $connect_url = Jetpack::init()->build_connect_url(true, admin_url('index.php#welcome/steps/' . $return_to_step));

            if (JETPACK_STEP_AUTO_REDIRECT) {
                $connect_url = add_query_arg('src', JETPACK_STEP_AUTO_REDIRECT_SRC, $connect_url);
                $connect_url = add_query_arg('host', 'bluehost', $connect_url);
                $connect_url = add_query_arg('from', 'jpo', $connect_url);
            }

            wp_send_json_success(array('next' => $connect_url));
        } else {
            wp_send_json_success('already_active');
        }


    }
}