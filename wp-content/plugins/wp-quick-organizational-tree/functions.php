<?php
/**
 * Called when plugin is activated or upgraded
 *
 * @uses add_option()
 * @uses get_option()
 * @uses update_option()
 *
 * @return void
 */
function wm_set_settings()
{
    $options = array(
        'version' => '1.0',
        'default_h_map_options' => array(
            'mp_enable_thumbnail' => 1,
            'mp_thumbnail_size' => 64,
            'mp_thumbnail_shape' => 'circle',
            'mp_thumbnail_place' => 'above',
            'mp_title_color' => 'FFFFFF',
            'mp_container_bgc' => 'FFFFFF',
            'mp_bgcolor' => '27a9e3',
            'mp_current_bgcolor' => '28b779',
            'mp_parent_bgcolor' => '852b99',
            'mp_child_bgcolor' => 'ffb848',
        ),
        'default_v_map_options' => array(
            'mp_enable_thumbnail' => 1,
            'mp_enable_link' => 1,
            'mp_list_posts' => 10,
        ),
    );

    if (get_option('wm_website_map_settings') === false) {
        add_option('wm_website_map_settings', $options);
        set_time_limit(120);
        wm_create_database_tables();
    } else {
        update_option('wm_website_map_settings', $options);
    }
}

//---------------------------------------------------
/**
 * to hook including scripts & styles to admin_enqueue_scripts, wp_enqueue_scripts at a very late order
 *
 * @uses add_action()
 *
 * @return void
 */
function wm_manage_enqueue()
{
    global $wp_filter;

    $adn_priority = 1;
    if (isset($wp_filter['admin_enqueue_scripts'])) {
        foreach ($wp_filter['admin_enqueue_scripts'] as $key => $val) {
            $adn_priority = ($key >= $adn_priority) ? $key + 1 : $adn_priority;
        }
    }

    add_action('admin_enqueue_scripts', 'wm_admin_styles_scripts', $adn_priority);

    $cln_priority = 1;
    if (isset($wp_filter['wp_enqueue_scripts'])) {
        foreach ($wp_filter['wp_enqueue_scripts'] as $key => $val) {
            $cln_priority = ($key >= $cln_priority) ? $key + 1 : $cln_priority;
        }
    }
    add_action('wp_enqueue_scripts', 'wm_styles_scripts', $cln_priority);
}

//---------------------------------------------------
/**
 * registers custom thumbnail size
 *
 * @uses add_image_size()
 *
 * @return void
 */
function wm_register_thumbnail_size()
{
    add_image_size('wm_vtree_thumbnail', 22, 22);
}

//---------------------------------------------------
function wm_add_shortcode_to_editor()
{
    if (current_user_can('edit_posts') && current_user_can('edit_pages')) {
        add_filter('mce_external_plugins', 'wm_mce_plugin');
        add_filter('mce_buttons', 'wm_mce_button');
    }
}

//---------------------------------------------------
/**
 * dequeue unwanted scripts and enqueue scripts & styles only when required
 *
 * @uses get_bloginfo()
 * @uses wp_dequeue_script()
 * @uses wp_deregister_script()
 * @uses plugins_url()
 * @uses wp_enqueue_style()
 * @uses wp_enqueue_script()
 *
 * @return void
 */
function wm_admin_styles_scripts()
{
    global $current_screen, $wp_scripts, $wp_styles, $wm_wordpress_scripts, $wm_wordpress_scripts_35;
    $version = get_bloginfo('version');
    $version = substr($version, 0, 3);
    $version = (float)$version;
    $wp_reg_scripts = ($version >= 3.5) ? $wm_wordpress_scripts_35 : $wm_wordpress_scripts;

    if (preg_match('/^.*wm_website_maps$/', $current_screen->id) || preg_match('/^.*wm_edit_website_map$/', $current_screen->id)) {
        $registeredScripts = array();
        foreach ($wp_scripts->registered as $reg) {
            $registeredScripts[] = $reg->handle;
        }

        $queuedScripts = array();
        foreach ($wp_scripts->queue as $q) {
            $queuedScripts[] = $q;
        }

        foreach ($queuedScripts as $q) {
            if (!in_array($q, $wp_reg_scripts)) {
                wp_dequeue_script($q);
            }
        }

        foreach ($registeredScripts as $r) {
            if (!in_array($r, $wp_reg_scripts)) {
                wp_deregister_script($r);
            }
        }

        if (!in_array('jquery', $queuedScripts)) {
            wp_enqueue_script('jquery');
        }

        if ($version >= 3.5) {
            if (!in_array('jquery-migrate', $queuedScripts)) {
                wp_enqueue_script('jquery-migrate');
            }
        } else {
            wp_enqueue_script('jquery-migrate', plugins_url('lib/horizontal-tree/js/jquery-migrate-1.2.1.min.js', WM_PLUGIN_MAIN_FILE));
        }

        $jq_ui_comp = array('jquery-ui-core', 'jquery-ui-widget', 'jquery-ui-mouse', 'jquery-ui-draggable', 'jquery-ui-droppable',
            'jquery-ui-resizable', 'jquery-ui-selectable', 'jquery-ui-sortable', 'jquery-ui-accordion', 'jquery-ui-autocomplete',
            'jquery-ui-button', 'jquery-ui-datepicker', 'jquery-ui-dialog', 'jquery-ui-menu', 'jquery-ui-position',
            'jquery-ui-progressbar', 'jquery-ui-slider', 'jquery-ui-spinner', 'jquery-ui-tabs', 'jquery-ui-tooltip');

        foreach ($queuedScripts as $q) {
            if (in_array($q, $jq_ui_comp)) {
                wp_dequeue_script($q);
            }
        }
        wp_enqueue_script('jquery-ui-js', plugins_url('lib/horizontal-tree/js/jquery-ui.js', WM_PLUGIN_MAIN_FILE));

        if (!in_array('media-upload', $queuedScripts)) {
            wp_enqueue_script('media-upload');
        }
        if (!in_array('thickbox', $queuedScripts)) {
            wp_enqueue_script('thickbox');
        }

        wp_enqueue_media();

        wp_enqueue_style('bootstrap-css', plugins_url('lib/bootstrap/css/bootstrap.min.css', WM_PLUGIN_MAIN_FILE));
        wp_enqueue_style('bootstrap-theme-css', plugins_url('lib/bootstrap/css/bootstrap-theme.min.css', WM_PLUGIN_MAIN_FILE));
        wp_enqueue_style('wm-admin-css', plugins_url('css/admin-css.css', WM_PLUGIN_MAIN_FILE));
        wp_enqueue_style('wm-css', plugins_url('css/css.css', WM_PLUGIN_MAIN_FILE));

        wp_enqueue_script('bootstrap-js', plugins_url('lib/bootstrap/js/bootstrap.min.js', WM_PLUGIN_MAIN_FILE));
        wp_enqueue_script('wm-admin-js', plugins_url('js/admin-js.js', WM_PLUGIN_MAIN_FILE));
        wp_enqueue_script('wm-js', plugins_url('js/js.js', WM_PLUGIN_MAIN_FILE));
    }
}

//---------------------------------------------------
/**
 * dequeue unwanted scripts and enqueue scripts & styles only when required
 *
 * @uses get_bloginfo()
 * @uses plugins_url()
 * @uses wp_dequeue_script()
 * @uses wp_enqueue_style()
 * @uses wp_enqueue_script()
 *
 * @return void
 */
function wm_styles_scripts()
{
    global $wp_scripts, $wp_styles, $wm_wordpress_scripts, $wm_wordpress_scripts_35;
    $version = get_bloginfo('version');
    $version = substr($version, 0, 3);
    $version = (float)$version;

    $queuedScripts = array();
    foreach ($wp_scripts->queue as $q) {
        $queuedScripts[] = $q;
    }
    // horizontal
    wp_enqueue_style('fonts-google-css', 'http://fonts.googleapis.com/css?family=Cabin:400,700,600');
    wp_enqueue_style('hor-tree-css', plugins_url('lib/horizontal-tree/style.css', WM_PLUGIN_MAIN_FILE));
    // vertical
    wp_enqueue_style('wm-awesome-css', plugins_url('lib/font-awesome/css/font-awesome.min.css', WM_PLUGIN_MAIN_FILE));
    wp_enqueue_style('wm-jstree-proton-theme-css', plugins_url('lib/jstree-bootstrap-theme/src/themes/proton/style.css', WM_PLUGIN_MAIN_FILE));
    wp_enqueue_style('jstree-css', plugins_url('lib/jstree/dist/themes/default/style.css', WM_PLUGIN_MAIN_FILE));

    if (!in_array('jquery', $queuedScripts)) {
        wp_enqueue_script('jquery');
    }

    if ($version >= 3.5) {
        if (!in_array('jquery-migrate', $queuedScripts)) {
            wp_enqueue_script('jquery-migrate');
        }
    } else {
        wp_enqueue_script('jquery-migrate', plugins_url('lib/horizontal-tree/js/jquery-migrate-1.2.1.min.js', WM_PLUGIN_MAIN_FILE));
    }

    $jq_ui_comp = array('jquery-ui-core', 'jquery-ui-widget', 'jquery-ui-mouse', 'jquery-ui-draggable', 'jquery-ui-droppable',
        'jquery-ui-resizable', 'jquery-ui-selectable', 'jquery-ui-sortable', 'jquery-ui-accordion', 'jquery-ui-autocomplete',
        'jquery-ui-button', 'jquery-ui-datepicker', 'jquery-ui-dialog', 'jquery-ui-menu', 'jquery-ui-position',
        'jquery-ui-progressbar', 'jquery-ui-slider', 'jquery-ui-spinner', 'jquery-ui-tabs', 'jquery-ui-tooltip');

    foreach ($queuedScripts as $q) {
        if (in_array($q, $jq_ui_comp)) {
            wp_dequeue_script($q);
        }
    }
    wp_enqueue_script('jquery-ui-js', plugins_url('lib/horizontal-tree/js/jquery-ui.js', WM_PLUGIN_MAIN_FILE));

    wp_enqueue_style('wm-css', plugins_url('css/css.css', WM_PLUGIN_MAIN_FILE));

    wp_enqueue_script('wm-js', plugins_url('js/js.js', WM_PLUGIN_MAIN_FILE));
}

//---------------------------------------------------
/**
 * creates admin menu pages
 *
 * @uses add_menu_page()
 * @uses add_submenu_page()
 *
 * @return void
 */
function wm_add_menu_page()
{
    add_menu_page('Tree Website Map', 'Tree Website Map', 'manage_options', 'wm_website_maps', 'wm_create_maps_page', 'dashicons-networking', 61);
    add_submenu_page(NULL, 'Add New Website Map', NULL, 'manage_options', 'wm_edit_website_map', 'wm_create_edit_map_page');
}

//---------------------------------------------------
/**
 * creates maps view page
 *
 * @uses current_user_can()
 *
 * @return void
 */
function wm_create_maps_page()
{
    if (!current_user_can('manage_options')) {
        wp_die(__('You do not have permissions to access this page!', 'websitemap'));
    }

    include(WM_PLUGIN_ROOT_DIR . WM_DS . 'views' . WM_DS . 'admin' . WM_DS . 'maps-view.php');
}

//---------------------------------------------------
/**
 * creates map edit page
 *
 * @uses current_user_can()
 *
 * @return void
 */
function wm_create_edit_map_page()
{
    global $table_prefix;
    if (!current_user_can('manage_options')) {
        wp_die(__('You do not have permissions to access this page!', 'websitemap'));
    }

    include(WM_PLUGIN_ROOT_DIR . WM_DS . 'views' . WM_DS . 'admin' . WM_DS . 'map-edit.php');
}

//---------------------------------------------------
/**
 * reads hierarchical posts (like pages) from database
 *
 * @uses wpdb::get_results()
 * @uses wpdb::prepare()
 *
 * @return array
 */
function wm_read_hierarchical_posts($post_type, $post_parent = NULL, $limit = NULL)
{
    global $wpdb;
    $sql = "SELECT ID, post_title, post_parent 
			FROM " . $wpdb->posts . " 
			WHERE post_type = %s AND post_status = 'publish' ";
    $sql .= (NULL !== $post_parent) ? " AND post_parent = %d " : "";
    $sql .= " ORDER BY menu_order";
    $sql .= (!empty($limit)) ? " LIMIT {$limit} OFFSET 0" : "";
    return $wpdb->get_results($wpdb->prepare($sql, $post_type, $post_parent));
}

//---------------------------------------------------
/**
 * counts hierarchical posts (like pages) from database
 *
 * @uses wpdb::get_results()
 * @uses wpdb::prepare()
 *
 * @return integer
 */
function wm_count_hierarchical_posts($post_type, $post_parent = NULL)
{
    global $wpdb;
    $sql = "SELECT COUNT(ID) cnt  
			FROM " . $wpdb->posts . " 
			WHERE post_type = %s AND post_status = 'publish' ";
    $sql .= (NULL !== $post_parent) ? " AND post_parent = %d " : "";
    $results = $wpdb->get_results($wpdb->prepare($sql, $post_type, $post_parent));
    if (false !== $results) {
        return $results[0]->cnt;
    }
}

//---------------------------------------------------
/**
 * arranges pages hierarchical
 *
 * @return array
 */
function wm_arrange_hierarchical_posts($pages, $parent = 0)
{
    $res = array();
    $c = 0;
    if (is_array($pages)) {
        for ($i = 0; $i < count($pages); $i++) {
            if ($parent == $pages[$i]->post_parent) {
                $res[$c]['id'] = $pages[$i]->ID;
                $res[$c]['title'] = $pages[$i]->post_title;
                $res[$c]['parent'] = $pages[$i]->post_parent;
                $res[$c]['children'] = wm_arrange_hierarchical_posts($pages, $pages[$i]->ID);
                $c++;
            }
        }
    }
    return $res;
}

//---------------------------------------------------
/**
 * counts a post children
 *
 * @uses wpdb::get_results()
 * @uses wpdb::prepare()
 *
 * @return boolean
 */
function wm_count_post_children($post_type, $ID)
{
    global $wpdb;
    $sql = "SELECT COUNT(ID) cnt FROM {$wpdb->posts} WHERE post_type = %s AND post_parent = %d";
    $results = $wpdb->get_results($wpdb->prepare($sql, $post_type, $ID));
    if (false !== $results) {
        return ($results[0]->cnt);
    }
}

//---------------------------------------------------
/**
 * counts a taxonomy term children
 *
 * @uses wpdb::get_results()
 * @uses wpdb::prepare()
 *
 * @return boolean
 */
function wm_count_taxonomy_term_children($term_id)
{
    global $wpdb;
    $sql = "SELECT COUNT(term_id) cnt FROM {$wpdb->term_taxonomy} WHERE parent = %d";
    $results = $wpdb->get_results($wpdb->prepare($sql, $term_id));
    if (false !== $results) {
        return ($results[0]->cnt);
    }
    return false;
}

//---------------------------------------------------
/**
 * counts a taxonomy children
 *
 * @uses wpdb::get_results()
 * @uses wpdb::prepare()
 *
 * @return boolean
 */
function wm_count_taxonomy_children($taxonomy)
{
    global $wpdb;
    $sql = "SELECT COUNT(term_id) cnt FROM {$wpdb->term_taxonomy} WHERE taxonomy = %s";
    $results = $wpdb->get_results($wpdb->prepare($sql, $taxonomy));
    if (false !== $results) {
        return ($results[0]->cnt);
    }
    return false;
}

//---------------------------------------------------
/**
 * counts excludeds children
 *
 * @uses wpdb::get_results()
 * @uses wpdb::prepare()
 *
 * @return boolean
 */
function wm_count_excludeds_children($mp_id, $ex_parent_node_id)
{
    global $wpdb;
    $sql = "SELECT COUNT(ex_id) cnt 
			FROM {$wpdb->prefix}wm_excludeds 
			WHERE mp_id = %d AND ex_parent_node_id = %s";
    $results = $wpdb->get_results($wpdb->prepare($sql, $mp_id, $ex_parent_node_id), OBJECT);
    if (false !== $results) {
        return ($results[0]->cnt);
    }
    return false;
}

//---------------------------------------------------
/**
 * reads non hierarchical posts (like posts) from database
 *
 * @uses wpdb::get_results()
 * @uses wpdb::prepare()
 *
 * @return array
 */
function wm_read_non_hierarchical_posts($post_type, $term_id, $limit = NULL)
{

    global $wpdb;
    /*$sql = "SELECT p.ID, p.post_title, p.post_parent 
			FROM {$wpdb->posts} p, {$wpdb->term_relationships} tr
			WHERE tr.term_taxonomy_id = %d AND tr.object_id = p.ID AND post_type = %s AND post_status = 'publish' 
			ORDER BY menu_order";*/

    $sql = "SELECT p.ID, p.post_title, p.post_parent 
			FROM {$wpdb->posts} p, {$wpdb->term_relationships} tr, {$wpdb->term_taxonomy} trm
			WHERE tr.term_taxonomy_id = trm.term_taxonomy_id and trm.term_id = %d AND tr.object_id = p.ID AND post_type = %s AND post_status = 'publish' 
			ORDER BY menu_order";


    $sql .= (!empty($limit)) ? " LIMIT {$limit} OFFSET 0" : "";


    return $wpdb->get_results($wpdb->prepare($sql, $term_id, $post_type));
}

//---------------------------------------------------
/**
 * counts non hierarchical posts (like posts) from database
 *
 * @uses wpdb::get_results()
 * @uses wpdb::prepare()
 *
 * @return integer
 */
function wm_count_non_hierarchical_posts($post_type, $term_id)
{


    global $wpdb;
    /*$sql = "SELECT COUNT(p.ID) cnt 
			FROM {$wpdb->posts} p, {$wpdb->term_relationships} tr
			WHERE tr.term_taxonomy_id = %d AND tr.object_id = p.ID AND post_type = %s AND post_status = 'publish'";
	$results = $wpdb->get_results( $wpdb->prepare( $sql, $term_id, $post_type ) );*/


    $sql = "SELECT COUNT(p.ID) cnt 
			FROM {$wpdb->posts} p, {$wpdb->term_relationships} tr, {$wpdb->term_taxonomy} trm
			WHERE tr.term_taxonomy_id = trm.term_taxonomy_id and trm.term_id = %d AND tr.object_id = p.ID AND post_type = %s AND post_status = 'publish'";


    $results = $wpdb->get_results($wpdb->prepare($sql, $term_id, $post_type));


    if (false !== $results) {
        return ($results[0]->cnt);
    }

}

//---------------------------------------------------
/**
 * retrieves taxonomies terms in hierarchical way
 *
 * @return array
 */
function wm_get_all_taxonomies_terms($post_type)
{
    $response = array();
    $taxonomies = get_object_taxonomies($post_type, 'objects');
    foreach ($taxonomies as $key => $tax) {
        if ($tax->public && $tax->hierarchical) {
            $response[$key] = array(
                'name' => $tax->labels->name,
                'terms' => wm_arrange_taxonomy_terms(wm_read_taxonomy_terms($key)),
            );
        }
    }
    return $response;
}

//---------------------------------------------------
/**
 * reads taxonomy terms from database
 *
 * @uses wpdb::get_results()
 *
 * @return array
 */
function wm_read_taxonomy_terms($taxonomy, $parent = NULL)
{
    global $wpdb;
    $sql = "SELECT t.term_id, t.name, t.slug, tt.description, tt.parent, tt.`count` 
			FROM {$wpdb->prefix}terms t, {$wpdb->prefix}term_taxonomy tt 
			WHERE t.term_id = tt.term_id AND tt.taxonomy = '" . $taxonomy . "'";
    $sql .= (NULL !== $parent) ? " AND tt.parent = " . $parent : "";
    return $wpdb->get_results($sql);
}

//---------------------------------------------------
/**
 * read taxonomies
 *
 * @uses wpdb::get_results()
 *
 * @return array
 */
function wm_read_other_taxonomies()
{
    global $wpdb;
    $sql = "SELECT DISTINCT taxonomy 
			FROM {$wpdb->prefix}term_taxonomy 
			WHERE taxonomy <> 'category' AND taxonomy <> 'post_tag' AND taxonomy <> 'nav_menu'";

    $results = $wpdb->get_results($sql);
    $response = array();
    if (false !== $results) {
        for ($i = 0; $i < count($results); $i++) {
            $response[] = $results[$i]->taxonomy;
        }
    }
    return $response;
}

//---------------------------------------------------
/**
 * arranges taxonomy terms in hierarchical way
 *
 * @return array
 */
function wm_arrange_taxonomy_terms($terms, $parent = 0)
{
    $response = array();
    $c = 0;
    if (is_array($terms)) {
        for ($i = 0; $i < count($terms); $i++) {
            if ($parent == $terms[$i]->parent) {
                $response[$c]['id'] = $terms[$i]->term_id;
                $response[$c]['name'] = $terms[$i]->name;
                $response[$c]['slug'] = $terms[$i]->slug;
                $response[$c]['description'] = $terms[$i]->description;
                $response[$c]['parent'] = $terms[$i]->parent;
                $response[$c]['count'] = $terms[$i]->count;
                $response[$c]['children'] = wm_arrange_taxonomy_terms($terms[$i]->ID);
                $c++;
            }
        }
    }
    return $response;
}

//---------------------------------------------------
/**
 * reads custom post types
 *
 * @uses get_post_types()
 *
 * @return array
 */
function wm_read_custom_post_types($hierarchical, $query_var = NULL)
{
    $args = array(
        'public' => true,
        'publicly_queryable' => true,
        'exclude_from_search' => false,
        'show_ui' => true,
        'hierarchical' => $hierarchical,
        '_builtin' => false,
    );
    if (!empty($query_var)) {
        $args['query_var'] = $query_var;
    }

    return get_post_types($args, 'objects');
}

//---------------------------------------------------
/**
 * creates needed tables
 *
 * @uses wpdb::query()
 *
 * @return boolean
 */
function wm_create_database_tables()
{
    global $wpdb, $table_prefix;
    $sqlQueries = array();
    $sqlQueries[] = "CREATE TABLE IF NOT EXISTS `{$table_prefix}wm_maps` (
						`mp_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
						`mp_name` varchar(150) DEFAULT NULL,
						`mp_type` varchar(45) NOT NULL COMMENT 'vertical, horizontal',
						`mp_options` TEXT NOT NULL,
						PRIMARY KEY (`mp_id`)
					) ENGINE=InnoDB DEFAULT CHARSET=utf8";

    $sqlQueries[] = "CREATE TABLE IF NOT EXISTS `{$table_prefix}wm_trees` (
						`tr_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
						`mp_id` int(11) unsigned NOT NULL,
						`tr_parent_id` int(11) unsigned DEFAULT NULL,
						`tr_title` varchar(200) DEFAULT NULL,
						`tr_link` varchar(300) DEFAULT NULL,
						`tr_active_link` tinyint(1) unsigned NOT NULL DEFAULT '1',
						`tr_hide` tinyint(1) unsigned NOT NULL DEFAULT '0',
						`tr_thumbnail_url` varchar(200) DEFAULT NULL,
						PRIMARY KEY (`tr_id`) USING BTREE, 
						KEY `FK_{$table_prefix}wm_trees_1` (`mp_id`), 
						KEY `FK_{$table_prefix}wm_trees_2` (`tr_parent_id`), 
						CONSTRAINT `FK_{$table_prefix}wm_trees_2` FOREIGN KEY (`tr_parent_id`) REFERENCES `{$table_prefix}wm_trees` (`tr_id`) ON DELETE CASCADE, 
						CONSTRAINT `FK_{$table_prefix}wm_trees_1` FOREIGN KEY (`mp_id`) REFERENCES `{$table_prefix}wm_maps` (`mp_id`) ON DELETE CASCADE 
					) ENGINE=InnoDB DEFAULT CHARSET=utf8";

    $sqlQueries[] = "CREATE TABLE IF NOT EXISTS `{$table_prefix}wm_excludeds` (
						`ex_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
						`mp_id` int(11) unsigned NOT NULL,
						`ex_node_id` varchar(200) NOT NULL,
						`ex_parent_node_id` varchar(200) DEFAULT NULL,
						PRIMARY KEY (`ex_id`),
						KEY `FK_{$wpdb->prefix}wm_excludeds_1` (`mp_id`),
						CONSTRAINT `FK_{$wpdb->prefix}wm_excludeds_1` FOREIGN KEY (`mp_id`) REFERENCES `{$wpdb->prefix}wm_maps` (`mp_id`) ON DELETE CASCADE
					) ENGINE=InnoDB DEFAULT CHARSET=utf8";

    foreach ($sqlQueries as $sql) {
        if (false === $wpdb->query($sql)) {
            return false;
        }
    }
    return true;
}

//---------------------------------------------------
/**
 * retrieves all maps
 *
 * @uses wpdb::get_results()
 *
 * @return array
 */
function wm_get_all_maps($title = '', $type = '', $limit = 20, $offset = 0)
{
    global $wpdb, $table_prefix;
    $response = array();
    $where = "";
    $where .= (!empty($title)) ? " AND mp_name LIKE '%" . $title . "%'" : "";
    $where .= (!empty($type)) ? " AND mp_type = '" . $type . "'" : "";

    $cSql = "SELECT COUNT(mp_id) tot 
			FROM `{$table_prefix}wm_maps` 
			WHERE 1 = 1" . $where;
    $count = $wpdb->get_results($cSql, OBJECT);
    $response['total'] = $count[0]->tot;

    $sql = "SELECT mp_id, mp_name, mp_type 
			FROM `{$table_prefix}wm_maps` 
			WHERE 1 = 1";
    $sql .= $where;
    $sql .= " ORDER BY mp_id DESC ";
    $sql .= (!empty($limit)) ? " LIMIT " . $limit . " OFFSET " . $offset : "";

    $results = $wpdb->get_results($sql, OBJECT);
    $response['data'] = array();
    if (false !== $results) {
        for ($i = 0; $i < count($results); $i++) {
            $response['data'][$i]['mp_id'] = $results[$i]->mp_id;
            $response['data'][$i]['mp_name'] = $results[$i]->mp_name;
            $response['data'][$i]['mp_type'] = ucfirst($results[$i]->mp_type);
        }
    }
    return $response;
}

//---------------------------------------------------
/**
 * retrieves a map
 *
 * @uses wpdb::prepare()
 * @uses wpdb::get_results()
 * @uses wp_parse_args()
 *
 * @return array
 */
function wm_read_map($mp_id)
{
    global $wpdb;

    $default_options = get_option('wm_website_map_settings');

    $sql = "SELECT mp_id, mp_name, mp_type, mp_options 
			FROM `{$wpdb->prefix}wm_maps` 
			WHERE mp_id = %d";

    $results = $wpdb->get_results($wpdb->prepare($sql, $mp_id), OBJECT);
    $response = array();
    if (is_array($results)) {
        $response['mp_id'] = $results[0]->mp_id;
        $response['mp_name'] = $results[0]->mp_name;
        $response['mp_type'] = $results[0]->mp_type;
        $defaults = ('horizontal' == $results[0]->mp_type) ? $default_options['default_h_map_options'] : $default_options['default_v_map_options'];
        $mp_options = unserialize($results[0]->mp_options);
        $mp_options = wp_parse_args($mp_options, $defaults);
        $response = array_merge($response, $mp_options);
        return $response;
    }
}

//---------------------------------------------------
/**
 * pagination
 *
 * @return string
 */
function wm_pagination($link, $total, $per_page, $current_page, $total_links = 5)
{
    $total_pages = (int)ceil($total / $per_page);
    $out = '<nav>
		<ul class="pagination">
			<li ' . (($current_page == 1) ? ' class="disabled"' : '') . '>
				<a href="' . $link . ($current_page - 1) . '" aria-label="Previous">
					<span aria-hidden="true">&laquo;</span>
				</a>
			</li>';

    for ($i = 1; $i <= $total_pages; $i++) {
        $out .= '<li ' . (($current_page == $i) ? ' class="active"' : '') . '>
			<a href="' . $link . $i . '">' . $i . (($current_page == $i) ? ' <span class="sr-only">(current)</span>' : '') . '</a>
		</li>';
    }

    $out .= '<li' . (($current_page == $total_pages) ? ' class="disabled"' : '') . '>
			  <a href="' . $link . ($current_page + 1) . '" aria-label="Next">
				<span aria-hidden="true">&raquo;</span>
			  </a>
			</li>
		</ul>
	</nav>';

    return $out;
}

//---------------------------------------------------
/**
 * renders horizontal tree
 *
 * @return string
 */
function wm_horizontal_tree($mp_id = 0, $forAdmin = true)
{
    $mp_id = (int)$mp_id;

    $map = wm_read_map($mp_id);
    $tree = wm_get_map_tree($mp_id);
    $id = 'tree_' . uniqid();
    $forAdmin = ($forAdmin) ? 'true' : 'false';
    $out = '<ul class="tree" id ="' . $id . '">' . wm_render_horizontal_tree_nodes($tree, $map) . '</ul>
		<script type="text/javascript">
			jQuery(document).ready(function() {
				jQuery("#' . $id . '").tree_structure({
					"add_option": ' . $forAdmin . ',
					"edit_option": ' . $forAdmin . ',
					"delete_option": ' . $forAdmin . ',
					"confirm_before_delete": true,
					"animate_option": false,
					"fullwidth_option": false,
					"align_option": "center",
					"draggable_option": ' . $forAdmin . '
				});
			});
		</script>';
    return $out;
}

//---------------------------------------------------
/**
 * renders horizontal tree node
 *
 * @return string
 */
function wm_render_horizontal_tree_nodes($tree, $map)
{
    $out = '';
    foreach ($tree as $node) {
        $out .= '<li' . (($node['tr_hide']) ? ' class="thide"' : '') . '>';
        $out .= '<div id="' . $node['tr_id'] . '" data-parent-id="' . $node['tr_parent_id'] . '">';

        $title = ($node['tr_active_link'] && !empty($node['tr_link'])) ? '<a href="' . $node['tr_link'] . '" target="_blank">' . $node['tr_title'] . '</a>' : $node['tr_title'];

        $content = array();
        if ($map['mp_enable_thumbnail']) {
            $tr_thumbnail_url = (!empty($node['tr_thumbnail_url'])) ? $node['tr_thumbnail_url'] : plugins_url('images/no_image_128.png', WM_PLUGIN_MAIN_FILE);
            $content[] = '<img src="' . $tr_thumbnail_url . '" width="' . $map['mp_thumbnail_size'] . '" height="' . $map['mp_thumbnail_size'] . '" 
						class="wm-thumbnail-img ' . $map['mp_thumbnail_shape'] . '" />';
            $content[] = '<div class="wm-cleaner"></div>';
        }
        $content[] = '<span class="first_name">' . $title . '</span>';
        if ('under' == $map['mp_thumbnail_place']) {
            $content = array_reverse($content);
        }
        $out .= implode('', $content);

        $out .= '</div>';
        $out .= (!empty($node['children'])) ?
            '<ul>' . wm_render_horizontal_tree_nodes($node['children'], $map) . '</ul>' : '';
        $out .= '</li>';
    }
    return $out;
}

//---------------------------------------------------
/**
 * creates new horizontal map
 *
 * @uses home_url()
 * @uses wpdb::insert()
 *
 * @return boolean
 */
function wm_create_new_horizontal_map()
{
    global $wpdb, $table_prefix;
    /* start saving map */
    $default_options = get_option('wm_website_map_settings');
    $mp_options = serialize($default_options['default_h_map_options']);
    $name = 'horizontal map ' . getMapsNextMaxId();
    $mp_data = array('mp_name' => $name, 'mp_type' => 'horizontal', 'mp_options' => $mp_options);
    if ($wpdb->insert($table_prefix . 'wm_maps', $mp_data, array('%s', '%s', '%s')) !== false) {
        $mp_id = $wpdb->insert_id;
        /* end saving map */
        /* start saving tree root */
        $link = home_url();
        $title = parse_url($link, PHP_URL_HOST);
        $rootValues = array(
            'mp_id' => $mp_id,
            'tr_title' => $title,
            'tr_link' => $link,
        );
        $insertRoot = $wpdb->insert($table_prefix . 'wm_trees', $rootValues, array('%d', '%s', '%s'));
        if ($insertRoot !== false) {
            $root_id = $wpdb->insert_id;
            /* end saving tree root */
            /* start saving pages */
            $allPages = wm_read_hierarchical_posts('page');
            $pages = wm_arrange_hierarchical_posts($allPages);

            $vals = array(
                'mp_id' => $mp_id,
                'tr_parent_id' => $root_id,
                'tr_active_link' => '0',
                'tr_title' => 'Pages',
                'tr_link' => '',
            );
            $frms = array('%d', '%d', '%d', '%s', '%s');
            if ($wpdb->insert($table_prefix . 'wm_trees', $vals, $frms) !== false) {
                $pgsId = $wpdb->insert_id;
                wm_save_hierarchical_posts($pages, $mp_id, $pgsId);
            }
            /* end saving pages */
            /* start saving other hierarchical posts */
            $hierarchical_cus_pst = wm_read_custom_post_types(true);
            foreach ($hierarchical_cus_pst as $key => $cust_pst) {
                $vals = array(
                    'mp_id' => $mp_id,
                    'tr_parent_id' => $root_id,
                    'tr_active_link' => '0',
                    'tr_title' => $cust_pst->labels->name,
                    'tr_title' => '',
                );
                $frms = array('%d', '%d', '%d', '%s', '%s');

                $allPosts = wm_read_hierarchical_posts($key);
                $posts = wm_arrange_hierarchical_posts($allPosts);
                if ($wpdb->insert($table_prefix . 'wm_trees', $vals, $frms) !== false) {
                    $pId = $wpdb->insert_id;
                    wm_save_hierarchical_posts($posts, $mp_id, $pId);
                }
            }
            /* end saving other hierarchical posts */
            /* start saving posts taxonomies' terms */
            $vals = array(
                'mp_id' => $mp_id,
                'tr_parent_id' => $root_id,
                'tr_active_link' => '1',
                'tr_title' => 'Posts',
                'tr_link' => home_url('?post_type=post'),
            );
            $frms = array('%d', '%d', '%d', '%s', '%s');

            if ($wpdb->insert($table_prefix . 'wm_trees', $vals, $frms) !== false) {
                $pId = $wpdb->insert_id;

                $taxs = wm_get_all_taxonomies_terms('post');
                foreach ($taxs as $key => $tax) {
                    $vals = array('mp_id' => $mp_id, 'tr_parent_id' => $pId, 'tr_active_link' => '0', 'tr_title' => $tax['name']);
                    $frms = array('%d', '%d', '%d', '%s');
                    if ($wpdb->insert($table_prefix . 'wm_trees', $vals, $frms) !== false) {
                        $taxId = $wpdb->insert_id;
                        wm_save_terms($tax['terms'], $key, $mp_id, $taxId);
                    }
                }
            }
            /* end saving posts taxonomies' terms */
            /* start saving custom posts type taxonomies' terms */
            $non_hierarchical_cus_pst = wm_read_custom_post_types(false);
            foreach ($non_hierarchical_cus_pst as $key => $cust_pst) {
                $vals = array(
                    'mp_id' => $mp_id,
                    'tr_parent_id' => $root_id,
                    'tr_active_link' => '1',
                    'tr_title' => $cust_pst->labels->name,
                    'tr_link' => home_url('?post_type=' . $key),
                );
                $frms = array('%d', '%d', '%d', '%s', '%s');

                if ($wpdb->insert($table_prefix . 'wm_trees', $vals, $frms) !== false) {
                    $pId = $wpdb->insert_id;

                    $taxs = wm_get_all_taxonomies_terms($key);
                    foreach ($taxs as $key => $tax) {
                        $vals = array('mp_id' => $mp_id, 'tr_parent_id' => $pId, 'tr_active_link' => '0', 'tr_title' => $tax['name']);
                        $frms = array('%d', '%d', '%d', '%s');
                        if ($wpdb->insert($table_prefix . 'wm_trees', $vals, $frms) !== false) {
                            $taxId = $wpdb->insert_id;
                            wm_save_terms($tax['terms'], $key, $mp_id, $taxId);
                        }
                    }
                }
            }
            /* end saving custom posts type taxonomies' terms */
        }
        return $mp_id;
    }
    return false;
}

//---------------------------------------------------
/**
 * saves horizontal tree pages
 *
 * @uses wpdb::insert()
 * @uses wpdb::show_errors()
 *
 * @return void
 */
function wm_save_hierarchical_posts($pages, $mp_id, $tr_parent_id)
{
    global $wpdb, $table_prefix;

    foreach ($pages as $p) {
        $values = array('mp_id' => $mp_id, 'tr_parent_id' => $tr_parent_id, 'tr_title' => $p['title'], 'tr_link' => get_permalink($p['id']));
        $formats = array('%d', '%d', '%s', '%s');
        if ($wpdb->insert($table_prefix . 'wm_trees', $values, $formats) !== false) {
            $insertedId = $wpdb->insert_id;
            if (!empty($p['children'])) {
                wm_save_hierarchical_posts($p['children'], $mp_id, $insertedId);
            }
        } else {
            $wpdb->show_errors();
        }
    }
}

//---------------------------------------------------
/**
 * saves horizontal tree terms
 *
 * @uses wpdb::insert()
 * @uses wpdb::show_errors()
 *
 * @return void
 */
function wm_save_terms($terms, $taxonomy, $mp_id, $tr_parent_id)
{
    global $wpdb, $table_prefix;
    foreach ($terms as $t) {
        $term = get_term($t['id'], $taxonomy, OBJECT);
        $link = get_term_link($term->name, $taxonomy);
        $link = (is_wp_error($link)) ? '' : $link;
        $values = array(
            'mp_id' => $mp_id,
            'tr_parent_id' => $tr_parent_id,
            'tr_active_link' => '1',
            'tr_title' => $t['name'],
            'tr_link' => ($link instanceof WP_Error) ? '' : $link,
        );
        $formats = array('%d', '%d', '%d', '%s', '%s');
        if ($wpdb->insert($table_prefix . 'wm_trees', $values, $formats) !== false) {
            $insertedId = $wpdb->insert_id;
            if (!empty($t['children'])) {
                wm_save_terms($t['children'], $taxonomy, $mp_id, $insertedId);
            }
        } else {
            $wpdb->show_errors();
        }
    }
}

//---------------------------------------------------
/**
 * retrieves horizontal map tree
 *
 * @return array
 */
function wm_get_map_tree($mp_id)
{
    $mp_id = (int)$mp_id;
    $tree = wm_read_map_tree($mp_id);
    return wm_arrange_tree($tree);
}

//---------------------------------------------------
/**
 * reads horizontal tree from database
 *
 * @uses wpdb::get_results()
 * @uses wpdb::prepare()
 *
 * @return array
 */
function wm_read_map_tree($mp_id)
{
    global $wpdb, $table_prefix;
    $mp_id = (int)$mp_id;
    $sql = "SELECT tr_id, tr_parent_id, tr_title, tr_link, tr_active_link, tr_hide, tr_thumbnail_url 
			FROM {$table_prefix}wm_trees 
			WHERE mp_id = %d";
    return $wpdb->get_results($wpdb->prepare($sql, $mp_id));
}

//---------------------------------------------------
/**
 * arranges horizontal tree
 *
 * @return array
 */
function wm_arrange_tree($tree, $parent = NULL)
{
    $res = array();
    if (is_array($tree)) {
        for ($i = 0; $i < count($tree); $i++) {
            if ($parent == $tree[$i]->tr_parent_id) {
                $res[$i]['tr_id'] = $tree[$i]->tr_id;
                $res[$i]['tr_title'] = $tree[$i]->tr_title;
                $res[$i]['tr_parent_id'] = $tree[$i]->tr_parent_id;
                $res[$i]['tr_link'] = $tree[$i]->tr_link;
                $res[$i]['tr_active_link'] = $tree[$i]->tr_active_link;
                $res[$i]['tr_hide'] = $tree[$i]->tr_hide;
                $res[$i]['tr_thumbnail_url'] = $tree[$i]->tr_thumbnail_url;
                $res[$i]['children'] = wm_arrange_tree($tree, $tree[$i]->tr_id);
            }
        }
    }
    return $res;
}

//---------------------------------------------------
/**
 * reads column from certain table, using where condition
 *
 * @uses wpdb::get_results()
 * @uses wpdb::prepare()
 *
 * @return array
 */
function wm_read_column_value($table, $reqColumn, $colName, $colValue)
{
    global $wpdb;
    return $wpdb->get_results($wpdb->prepare("SELECT `" . $reqColumn . "` FROM `" . $table . "` WHERE `" . $colName . "` = %s", $colValue), OBJECT);
}

//---------------------------------------------------
/**
 * deletes a horizontal tree node
 *
 * @uses wpdb::query()
 * @uses wpdb::prepare()
 *
 * @return void
 */
function wm_delete_tree_node()
{
    global $wpdb;
    $ids = (isset($_POST['id'])) ? implode(', ', $_POST['id']) : '';
    $ids = (empty($ids)) ? '0' : $ids;
    echo ($wpdb->query($wpdb->prepare("DELETE FROM `{$wpdb->prefix}wm_trees` WHERE tr_id IN (%s) AND tr_parent_id IS NOT NULL", $ids)) !== false) ? '1' : '0';
    exit();
}

//---------------------------------------------------
/**
 * Retrieves attachment id from url
 *
 * @uses wp_upload_dir()
 * @uses wpdb::get_var()
 * @uses wpdb::prepare()
 *
 * @return integer|boolean
 */
function wm_get_attachment_id_from_url($attachment_url)
{
    global $wpdb;
    $upload_dir_paths = wp_upload_dir();
    if (strpos($attachment_url, $upload_dir_paths['baseurl']) !== false) {
        $attachment_url = preg_replace('/-\d+x\d+(?=\.(jpg|jpeg|png|gif)$)/i', '', $attachment_url);

        $attachment_url = str_replace($upload_dir_paths['baseurl'] . '/', '', $attachment_url);

        $sql = "SELECT ID 
				FROM {$wpdb->posts} wposts, {$wpdb->postmeta} wpostmeta 
				WHERE wposts.ID = wpostmeta.post_id AND wpostmeta.meta_key = '_wp_attached_file' 
				AND wpostmeta.meta_value = '%s' AND wposts.post_type = 'attachment'";

        $attachment_id = $wpdb->get_var($wpdb->prepare($sql, $attachment_url));

        return $attachment_id;
    }
    return false;
}

//---------------------------------------------------
/**
 * render a horizontal tree node form
 *
 * @uses plugins_url()
 * @uses checked()
 *
 * @return void
 */
function wm_get_node_form()
{
    $action = (isset($_POST['action'])) ? $_POST['action'] : '';
    $edit_ele_id = (isset($_POST['edit_ele_id'])) ? $_POST['edit_ele_id'] : 0;
    $tr_parent_id = (isset($_POST['tr_parent_id'])) ? $_POST['tr_parent_id'] : NULL;

    $tr_title = $tr_link = $tr_hide = $tr_hide_checked = NULL;
    $tr_active_link = '1';
    $formClass = 'add_data';
    $buttonClass = 'submit';

    if ('wm_node_editform' == $action && $edit_ele_id > 0) {
        if ($node = wm_read_tree_node($edit_ele_id)) {
            $tr_parent_id = $node->tr_parent_id;
            $tr_title = $node->tr_title;
            $tr_link = $node->tr_link;
            $tr_active_link = $node->tr_active_link;
            $tr_hide = $node->tr_hide;
            $formClass = 'edit_data';
            $buttonClass = 'edit';
        }
    }

    ?>
    <form class="<?php echo $formClass ?>" method="post" action="">
        <img class="close"
             src="<?php echo plugins_url('lib/horizontal-tree/images/close.png', WM_PLUGIN_MAIN_FILE) ?>"/>
        <br/>
        <input type="hidden" name="thumbnail_id" value="">
        <input type="hidden" name="tr_parent_id" value="<?php echo $tr_parent_id ?>">
        <input type="text" class="first_name" name="first_name" value="<?php echo $tr_title ?>"
               placeholder="first name">
        <input type="text" name="tr_link" value="<?php echo $tr_link ?>" placeholder="url">
        <input type="checkbox" <?php checked($tr_active_link, '1') ?> value="1" name="tr_active_link"
               id="tr_active_link"/>
        <label for="tr_active_link">Active Link</label>
        <br/>
        <input type="checkbox" <?php checked($tr_hide, '1') ?> value="1" name="showhideval" id="hide"/>
        <label for="hide">Hide Child Nodes</label>
        <input id="wm_thumb_selector" type="button" style="margin-top: 5px;" class="button-secondary"
               onclick="wm_addImage(this)" value="<?php _e('Add image', 'websitemap') ?>">
        <input type="submit" class="btn btn-success <?php echo $buttonClass ?>" style="margin-top: 10px;" name="submit"
               value="<?php _e('Submit', 'websitemap') ?>">
    </form>
    <?php
    exit();
}

//---------------------------------------------------
/**
 * read horizontal tree node
 *
 * @uses wpdb::prepare()
 * @uses wpdb::get_results()
 *
 * @return object|boolean
 */
function wm_read_tree_node($tr_id)
{
    global $wpdb, $table_prefix;
    $results = $wpdb->get_results($wpdb->prepare("SELECT * FROM `{$table_prefix}wm_trees` WHERE `tr_id` = %d", $tr_id));
    return (is_array($results)) ? $results[0] : false;
}

//---------------------------------------------------
/**
 * saves horizontal tree node
 *
 * @uses wpdb::update()
 * @uses wpdb::insert()
 * @uses wpdb::prepare()
 * @uses wpdb::get_var()
 * @uses wp_get_attachment_url()
 * @uses get_attached_file()
 * @uses wp_get_image_editor()
 * @uses WP_Image_Editor::resize()
 * @uses WP_Image_Editor::set_quality()
 * @uses WP_Image_Editor::save()
 * @uses WP_Image_Editor::generate_filename()
 *
 * @return void
 */
function wm_save_tree_node()
{
    global $wpdb;
    $response = array();
    $data = array();
    $tr_id = (isset($_POST['id'])) ? $_POST['id'] : 0;
    $data['tr_title'] = (isset($_POST['first_name'])) ? $_POST['first_name'] : '';
    $data['tr_link'] = (isset($_POST['tr_link'])) ? $_POST['tr_link'] : '';
    $tr_parent_id = (isset($_POST['tr_parent_id'])) ? $_POST['tr_parent_id'] : NULL;
    $data['tr_active_link'] = (isset($_POST['tr_active_link'])) ? 1 : 0;
    $data['tr_hide'] = (isset($_POST['showhideval'])) ? 1 : 0;
    $dataFormats = array('tr_title' => '%s', 'tr_link' => '%s', 'tr_active_link' => '%d', 'tr_hide' => '%d');

    if ($tr_id > 0) {
        $where = array('tr_id' => $tr_id);
        $response['success'] = (false !== $wpdb->update($wpdb->prefix . 'wm_trees', $data, $where, $dataFormats, array('%d')));
    } else {
        $mp_id = wm_read_column_value($wpdb->prefix . 'wm_trees', 'mp_id', 'tr_id', $tr_parent_id);
        $data = array_merge($data, array('mp_id' => $mp_id[0]->mp_id, 'tr_parent_id' => $tr_parent_id));
        $dataFormats = array_merge($dataFormats, array('tr_parent_id' => '%d', 'mp_id' => '%d'));
        $response['success'] = (false !== $wpdb->insert($wpdb->prefix . 'wm_trees', $data, $dataFormats));
        $tr_id = $wpdb->insert_id;
    }

    $response['tr_title'] = ($data['tr_active_link'] && !empty($data['tr_link'])) ?
        '<a href="' . $data['tr_link'] . '" target="_blank">' . $data['tr_title'] . '</a>' : $data['tr_title'];

    /* start handling thumbnail image */
    $thumbnail_id = (isset($_POST['thumbnail_id'])) ? $_POST['thumbnail_id'] : '';

    if (!empty($thumbnail_id)) {
        $attch_id = wm_get_attachment_id_from_url(urldecode($thumbnail_id));
        if ($attch_id) {
            $url = dirname(wp_get_attachment_url($attch_id));

            $editor = wp_get_image_editor(get_attached_file($attch_id));
            if ($editor instanceof WP_Image_Editor) {
                $editor->resize(128, 128, true);
                $editor->set_quality(100);
                $result = $editor->save($editor->generate_filename());
                $attach_url = $url . '/' . $result['file'];
                $response['success'] = (false !== $wpdb->update($wpdb->prefix . 'wm_trees',
                        array('tr_thumbnail_url' => $attach_url),
                        array('tr_id' => $tr_id),
                        array('tr_thumbnail_url' => '%s'),
                        array('%d')
                    )
                );
                $response['tr_thumbnail_url'] = $attach_url;
            }
        }
    } else {
        $tr_thumbnail_url = $wpdb->get_var($wpdb->prepare("SELECT tr_thumbnail_url FROM `{$wpdb->prefix}wm_trees` WHERE tr_id = %d", $tr_id));
        $response['tr_thumbnail_url'] = (!empty($tr_thumbnail_url)) ? $tr_thumbnail_url : plugins_url('images/no_image_128.png', WM_PLUGIN_MAIN_FILE);
    }
    /* end handling thumbnail image */

    header("Content-Type: application/json");
    header("Content-Type: text/json");
    echo json_encode($response);
    exit();
}

//---------------------------------------------------
/**
 * change horizontal tree node parent
 *
 * @uses wpdb::update()
 *
 * @return void
 */
function wm_change_parent_node()
{
    global $wpdb;
    $tr_id = (isset($_POST['tr_id'])) ? $_POST['tr_id'] : 0;
    $tr_parent_id = (isset($_POST['tr_parent_id'])) ? $_POST['tr_parent_id'] : 0;

    $res = $wpdb->update(
        $wpdb->prefix . 'wm_trees',
        array('tr_parent_id' => $tr_parent_id),
        array('tr_id' => $tr_id),
        array('%d'),
        array('%d')
    );
    echo (false !== $res) ? '1' : '0';
    exit();
}

//---------------------------------------------------
/**
 * updates horizontal map
 *
 * @uses get_option()
 * @uses wp_parse_args()
 * @uses wpdb::update()
 *
 * @return void
 */
function wm_edit_horizontal_map()
{
    global $wpdb;
    $mp_id = (isset($_POST['mp_id'])) ? $_POST['mp_id'] : 0;
    $mp_name = (isset($_POST['mp_name'])) ? $_POST['mp_name'] : '';
    $mp_options = array();
    $mp_options['mp_enable_thumbnail'] = (isset($_POST['mp_enable_thumbnail'])) ? $_POST['mp_enable_thumbnail'] : 0;
    $mp_options['mp_thumbnail_size'] = (isset($_POST['mp_thumbnail_size'])) ? $_POST['mp_thumbnail_size'] : 0;
    $mp_options['mp_thumbnail_shape'] = (isset($_POST['mp_thumbnail_shape'])) ? $_POST['mp_thumbnail_shape'] : NULL;
    $mp_options['mp_thumbnail_place'] = (isset($_POST['mp_thumbnail_place'])) ? $_POST['mp_thumbnail_place'] : NULL;
    $mp_options['mp_container_bgc'] = (isset($_POST['mp_container_bgc'])) ? $_POST['mp_container_bgc'] : NULL;
    $mp_options['mp_title_color'] = (isset($_POST['mp_title_color'])) ? $_POST['mp_title_color'] : NULL;
    $mp_options['mp_bgcolor'] = (isset($_POST['mp_bgcolor'])) ? $_POST['mp_bgcolor'] : NULL;
    $mp_options['mp_current_bgcolor'] = (isset($_POST['mp_current_bgcolor'])) ? $_POST['mp_current_bgcolor'] : NULL;
    $mp_options['mp_parent_bgcolor'] = (isset($_POST['mp_parent_bgcolor'])) ? $_POST['mp_parent_bgcolor'] : NULL;
    $mp_options['mp_child_bgcolor'] = (isset($_POST['mp_child_bgcolor'])) ? $_POST['mp_child_bgcolor'] : NULL;
    $mp_options['mp_container_width'] = (isset($_POST['mp_container_width'])) ? $_POST['mp_container_width'] : NULL;
    $mp_options['mp_container_height'] = (isset($_POST['mp_container_height'])) ? $_POST['mp_container_height'] : NULL;

    $default_options = get_option('wm_website_map_settings');
    $mp_options = wp_parse_args($mp_options, $default_options['default_h_map_options']);
    $mp_options = serialize($mp_options);

    $res = $wpdb->update(
        $wpdb->prefix . 'wm_maps',
        array('mp_name' => $mp_name, 'mp_options' => $mp_options),
        array('mp_id' => $mp_id),
        array('%s', '%s'),
        array('%d')
    );

    echo (false !== $res) ? '1' : '0';
    exit();
}

//---------------------------------------------------
/**
 * updates vertical map
 *
 * @uses wpdb::update()
 *
 * @return void
 */
function wm_edit_vertical_map()
{
    global $wpdb;
    $mp_id = (isset($_POST['mp_id'])) ? $_POST['mp_id'] : 0;
    $mp_name = (isset($_POST['mp_name'])) ? $_POST['mp_name'] : '';
    $mp_options = array();
    $mp_options['mp_enable_thumbnail'] = (isset($_POST['mp_enable_thumbnail'])) ? $_POST['mp_enable_thumbnail'] : 0;
    $mp_options['mp_enable_link'] = (isset($_POST['mp_enable_link'])) ? $_POST['mp_enable_link'] : 0;
    $mp_options['mp_root_node'] = (isset($_POST['mp_root_node'])) ? $_POST['mp_root_node'] : '';
    $mp_options['mp_root_node_link'] = (isset($_POST['mp_root_node_link'])) ? $_POST['mp_root_node_link'] : '';
    $mp_options['mp_list_posts'] = (isset($_POST['mp_list_posts'])) ? $_POST['mp_list_posts'] : 10;

    $default_options = get_option('wm_website_map_settings');
    $mp_options = wp_parse_args($mp_options, $default_options['default_v_map_options']);
    $mp_options = serialize($mp_options);

    $res = $wpdb->update(
        $wpdb->prefix . 'wm_maps',
        array('mp_name' => $mp_name, 'mp_options' => $mp_options),
        array('mp_id' => $mp_id),
        array('%s', '%s'),
        array('%d')
    );
    echo (false !== $res) ? '1' : '0';
    exit();
}

//---------------------------------------------------
/**
 * creates vertical map
 *
 * @uses get_option()
 * @uses wpdb::insert()
 * @uses home_url()
 *
 * @return integer|boolean
 */
function wm_create_new_vertical_map()
{
    global $wpdb, $table_prefix;
    $default_options = get_option('wm_website_map_settings');
    $mp_options = $default_options['default_v_map_options'];
    $mp_options['mp_root_node_link'] = home_url();
    $mp_options['mp_root_node'] = parse_url($mp_options['mp_root_node_link'], PHP_URL_HOST);
    $name = 'vertical map ' . getMapsNextMaxId();
    $data = array('mp_name' => $name, 'mp_type' => 'vertical', 'mp_options' => serialize($mp_options));
    if ($wpdb->insert($table_prefix . 'wm_maps', $data, array('%s', '%s', '%s')) !== false) {
        return $wpdb->insert_id;
    }
    return false;
}

//---------------------------------------------------
/**
 * retrieves vertical tree nodes by parent id
 *
 * @uses is_user_logged_in()
 * @uses current_user_can()
 * @uses home_url()
 * @uses get_permalink()
 * @uses get_term_link()
 * @uses plugins_url()
 *
 * @return void
 */
function wm_get_vertical_tree_nodes()
{
    $html = true;
    $mp_id = (isset($_GET['mp_id'])) ? $_GET['mp_id'] : 0;
    $parent = (isset($_GET['parent'])) ? $_GET['parent'] : NULL;
    $control_panel = (isset($_GET['control_panel']) && is_user_logged_in() && current_user_can('manage_options'));

    $parent = explode('++', $parent);
    $arrange_type = $parent[0];
    $post_type = (isset($parent[1])) ? $parent[1] : NULL;
    $post_id = (isset($parent[2])) ? $parent[2] : NULL;
    $taxonomy = (isset($parent[3])) ? $parent[3] : NULL;
    $term = (isset($parent[4])) ? $parent[4] : NULL;
    $nodes = array();
    $excludeds = wm_read_excluded_nodes($mp_id);

    $map = wm_read_map($mp_id);

    switch ($arrange_type) {
        /* start parents */
        case '#':
            $chNo = 2 + count(wm_read_custom_post_types(true)) + count(wm_read_custom_post_types(false));
            $excludeds_no = ($control_panel) ? 0 : wm_count_excludeds_children($mp_id, 'root_node');
            if (!in_array('root_node', $excludeds) || $control_panel) {
                $nodes[] = array(
                    "id" => "root_node",
                    "text" => $map['mp_root_node'],
                    "icon" => ($map['mp_enable_thumbnail']) ? wm_get_icon('root') : '',
                    "children" => ($chNo > $excludeds_no),
                    "type" => "root",
                    "excluded" => in_array('root_node', $excludeds),
                    "link" => ($map['mp_enable_link'] && !$control_panel) ? $map['mp_root_node_link'] : '',
                );
            }
            break;

        case 'root_node':
            //$link = home_url();
            //$title = parse_url( $link, PHP_URL_HOST );

            $excludeds_no1 = ($control_panel) ? 0 : wm_count_excludeds_children($mp_id, 'hierarchical++page++0');
            if (!in_array('hierarchical++page++0', $excludeds) || $control_panel) {
                $nodes[] = array(
                    "id" => "hierarchical++page++0",
                    "text" => 'Pages',
                    "icon" => ($map['mp_enable_thumbnail']) ? wm_get_icon('parent') : '',
                    "children" => (wm_count_post_children('page', 0) > $excludeds_no1),
                    "excluded" => in_array('hierarchical++page++0', $excludeds),
                    "link" => '',
                );
            }

            $hierarchical_cus_pst = wm_read_custom_post_types(true);

            foreach ($hierarchical_cus_pst as $key => $type) {
                if (!in_array('hierarchical++' . $key . '++0', $excludeds) || $control_panel) {
                    $excludeds_no2 = ($control_panel) ? 0 : wm_count_excludeds_children($mp_id, 'hierarchical++' . $key . '++0');
                    $nodes[] = array(
                        "id" => 'hierarchical++' . $key . '++0',
                        "text" => $type->labels->name,
                        "icon" => ($map['mp_enable_thumbnail']) ? wm_get_icon('parent') : '',
                        "children" => (wm_count_post_children($key, 0) > $excludeds_no2),
                        "excluded" => in_array('hierarchical++' . $key . '++0', $excludeds),
                        "link" => '',
                    );
                }
            }

            if (!in_array('non_hierarchical++post++0', $excludeds) || $control_panel) {
                $excludeds_no3 = ($control_panel) ? 0 : wm_count_excludeds_children($mp_id, 'non_hierarchical++post++0');
                $taxonomies = wm_get_object_taxonomies('post');
                $cntTax = count($taxonomies);
                $cntTax = ($cntTax > 0) ? $cntTax : wm_count_post_children('post', 0);
                $nodes[] = array(
                    "id" => "non_hierarchical++post++0",
                    "text" => 'Posts',
                    "icon" => ($map['mp_enable_thumbnail']) ? wm_get_icon('parent') : '',
                    "children" => ($cntTax > $excludeds_no3),
                    "excluded" => in_array('non_hierarchical++post++0', $excludeds),
                    "link" => ($map['mp_enable_link'] && !$control_panel) ? home_url('?post_type=post') : '',
                );
            }

            $non_hierarchical_cus_pst = wm_read_custom_post_types(false);

            foreach ($non_hierarchical_cus_pst as $key => $type) {
                if (!in_array('non_hierarchical++' . $key . '++0', $excludeds) || $control_panel) {
                    $excludeds_no4 = ($control_panel) ? 0 : wm_count_excludeds_children($mp_id, 'non_hierarchical++' . $key . '++0');
                    $taxonomies = wm_get_object_taxonomies($key);
                    $cntTax = count($taxonomies);
                    $cntTax = ($cntTax > 0) ? $cntTax : wm_count_post_children($key, 0);
                    $nodes[] = array(
                        "id" => 'non_hierarchical++' . $key . '++0',
                        "text" => $type->labels->name,
                        "icon" => ($map['mp_enable_thumbnail']) ? wm_get_icon('parent') : '',
                        "children" => ($cntTax > $excludeds_no4),
                        "excluded" => in_array('non_hierarchical++' . $key . '++0', $excludeds),
                        "link" => ($map['mp_enable_link'] && !$control_panel) ? home_url('?post_type=' . $key) : '', // get_post_type_archive_link( $key )
                    );
                }
            }
            break;
        /* end parents */

        /* start hierarchical */
        case 'hierarchical':
            $posts = wm_read_hierarchical_posts($post_type, $post_id);
            foreach ($posts as $p) {
                if (!in_array('hierarchical++' . $post_type . '++' . $p->ID, $excludeds) || $control_panel) {
                    $excludeds_no5 = ($control_panel) ? 0 : wm_count_excludeds_children($mp_id, 'hierarchical++' . $post_type . '++' . $p->ID);
                    $nodes[] = array(
                        "id" => 'hierarchical++' . $post_type . '++' . $p->ID,
                        "text" => $p->post_title,
                        "icon" => ($map['mp_enable_thumbnail']) ? wm_get_icon('post', $post_type, $p->ID) : '',
                        "children" => (wm_count_post_children($post_type, $p->ID) > $excludeds_no5),
                        "excluded" => in_array('hierarchical++' . $post_type . '++' . $p->ID, $excludeds),
                        "link" => ($map['mp_enable_link'] && !$control_panel) ? get_permalink($p->ID) : '',
                    );
                }
            }
            break;
        /* end hierarchical */

        /* start non hierarchical */
        case 'non_hierarchical':
            if (empty($post_id) && empty($taxonomy)) {
                $taxonomies = wm_get_object_taxonomies($post_type);
                if (!empty($taxonomies)) {
                    foreach ($taxonomies as $key => $tax) {
                        if (!in_array('non_hierarchical++' . $post_type . '++0++' . $key, $excludeds) || $control_panel) {
                            $excludeds_no6 = ($control_panel) ? 0 : wm_count_excludeds_children($mp_id, 'non_hierarchical++' . $post_type . '++0++' . $key);
                            $nodes[] = array(
                                "id" => 'non_hierarchical++' . $post_type . '++0++' . $key,
                                "text" => $tax->labels->name,
                                "icon" => ($map['mp_enable_thumbnail']) ? wm_get_icon('taxonomy') : '',
                                "children" => (wm_count_taxonomy_children($key) > $excludeds_no6),
                                "excluded" => in_array('non_hierarchical++' . $post_type . '++0++' . $key, $excludeds),
                                "link" => "",
                            );
                        }
                    }
                } else {
                    $cnt = wm_count_hierarchical_posts($post_type, $post_id);
                    $posts = wm_read_hierarchical_posts($post_type, 0, $map['mp_list_posts']);
                    foreach ($posts as $p) {
                        if (!in_array('non_hierarchical++' . $post_type . '++' . $p->ID, $excludeds) || $control_panel) {
                            $nodes[] = array(
                                "id" => 'non_hierarchical++' . $post_type . '++' . $p->ID,
                                "text" => $p->post_title,
                                "icon" => ($map['mp_enable_thumbnail']) ? wm_get_icon('post', $post_type, $p->ID) : '',
                                "children" => false,
                                "excluded" => in_array('non_hierarchical++' . $post_type . '++' . $p->ID, $excludeds),
                                "link" => ($map['mp_enable_link'] && !$control_panel) ? get_permalink($p->ID) : '',
                            );
                        }
                    }
                    if ($cnt > $map['mp_list_posts']) {
                        $nodes[] = array(
                            "id" => 'archive++' . $post_type,
                            "text" => __('Go to archive', 'websitemap'),
                            "icon" => ($map['mp_enable_thumbnail']) ? wm_get_icon('archive') : '',
                            "children" => false,
                            "excluded" => false,
                            "link" => ($map['mp_enable_link'] && !$control_panel) ? home_url('?post_type=' . $post_type) : '',
                        );
                    }
                }
            } else {
                if (!empty($taxonomy) && empty($term)) {
                    $terms = wm_read_taxonomy_terms($taxonomy, 0);
                    foreach ($terms as $t) {
                        if (!in_array('non_hierarchical++' . $post_type . '++0++' . $taxonomy . '++' . $t->term_id, $excludeds) || $control_panel) {
                            $excludeds_no7 = ($control_panel) ? 0 : wm_count_excludeds_children($mp_id, 'non_hierarchical++' . $post_type . '++0++' . $taxonomy . '++' . $t->term_id);
                            $link = '';
                            if ($map['mp_enable_link']) {
                                $link = get_term_link($t->name, $taxonomy);
                                $link = (is_wp_error($link)) ? '' : $link;
                            }
                            $nodes[] = array(
                                "id" => 'non_hierarchical++' . $post_type . '++0++' . $taxonomy . '++' . $t->term_id,
                                "text" => $t->name,
                                "icon" => ($map['mp_enable_thumbnail']) ? wm_get_icon('term') : '',
                                "children" => ($t->count + wm_count_taxonomy_term_children($t->term_id) > $excludeds_no7),
                                "excluded" => in_array('non_hierarchical++' . $post_type . '++0++' . $taxonomy . '++' . $t->term_id, $excludeds),
                                "link" => ($map['mp_enable_link'] && !$control_panel) ? $link : '',
                            );
                        }
                    }
                } else if (!empty($term)) {
                    $term = (int)$term;
                    $terms = wm_read_taxonomy_terms($taxonomy, $term);
                    foreach ($terms as $t) {
                        if (!in_array('non_hierarchical++' . $post_type . '++0++' . $taxonomy . '++' . $t->term_id, $excludeds) || $control_panel) {
                            $excludeds_no8 = ($control_panel) ? 0 : wm_count_excludeds_children($mp_id, 'non_hierarchical++' . $post_type . '++0++' . $taxonomy . '++' . $t->term_id);
                            $link = '';
                            if ($map['mp_enable_link']) {
                                $link = get_term_link($t->name, $taxonomy);
                                $link = (is_wp_error($link)) ? '' : $link;
                            }
                            $nodes[] = array(
                                "id" => 'non_hierarchical++' . $post_type . '++0++' . $taxonomy . '++' . $t->term_id,
                                "text" => $t->name,
                                "icon" => ($map['mp_enable_thumbnail']) ? wm_get_icon('term') : '',
                                "children" => ($t->count + wm_count_taxonomy_term_children($t->term_id) > $excludeds_no8),
                                "excluded" => in_array('non_hierarchical++' . $post_type . '++0++' . $taxonomy . '++' . $t->term_id, $excludeds),
                                "link" => ($map['mp_enable_link'] && !$control_panel) ? $link : '',
                            );
                        }
                    }

                    $cnt = wm_count_non_hierarchical_posts($post_type, $term);
                    $posts = wm_read_non_hierarchical_posts($post_type, $term, $map['mp_list_posts']);
                    foreach ($posts as $p) {
                        if (!in_array('non_hierarchical++' . $post_type . '++' . $p->ID . '++' . $taxonomy . '++' . $term, $excludeds) || $control_panel) {
                            $nodes[] = array(
                                "id" => 'non_hierarchical++' . $post_type . '++' . $p->ID . '++' . $taxonomy . '++' . $term,
                                "text" => $p->post_title,
                                "icon" => ($map['mp_enable_thumbnail']) ? wm_get_icon('post', $post_type, $p->ID) : '',
                                "children" => false,
                                "excluded" => in_array('non_hierarchical++' . $post_type . '++' . $p->ID . '++' . $taxonomy . '++' . $term, $excludeds),
                                "link" => ($map['mp_enable_link'] && !$control_panel) ? get_permalink($p->ID) : '',
                            );
                        }
                    }
                    if ($cnt > $map['mp_list_posts']) {
                        $nodes[] = array(
                            "id" => 'archive++' . $post_type,
                            "text" => __('Go to archive', 'websitemap'),
                            "icon" => ($map['mp_enable_thumbnail']) ? wm_get_icon('archive') : '',
                            "children" => false,
                            "excluded" => false,
                            "link" => ($map['mp_enable_link'] && !$control_panel) ? home_url('?post_type=' . $post_type) : '',
                        );
                    }
                }
            }
            break;
        /* end non hierarchical */
    }

    $out = '';
    if ($html) {
        $out = '<ul>';
        foreach ($nodes as $n) {
            $cls = '';
            if ($control_panel && $n['excluded']) {
                $cls = 'excluded';
            }

            $text = (!empty($n['link'])) ? '<a href="' . $n['link'] . '" target="_blank" onclick="wm_toLink(this);" >
													<span class="' . $cls . '">' . $n['text'] . '</span>
												</a>' :
                '<span class="' . $cls . '">' . $n['text'] . '</span>';

            $out .= '<li id="' . $n['id'] . '" class="wm-li-node ' . (($n['children']) ? ' jstree-closed' : '') . '" 
					data-jstree=\'{"icon":"' . $n['icon'] . '"}\'>&nbsp;' . $text;

            $out .= '</li>';
        }
        $out .= '</ul>';
    }

    if (!$html) {
        header('Content-type: text/json');
        header('Content-type: application/json');
    }
    echo ($html) ? $out : json_encode($nodes);
    exit();
}

//---------------------------------------------------
/**
 * retrieves vertical excluded nodes
 *
 * @uses wpdb::prepare()
 * @uses wpdb::get_results()
 *
 * @return array
 */
function wm_read_excluded_nodes($mp_id)
{
    global $wpdb;

    $sql = "SELECT ex_node_id 
			FROM {$wpdb->prefix}wm_excludeds 
			WHERE mp_id = %d";

    $results = $wpdb->get_results($wpdb->prepare($sql, $mp_id));
    $excludeds = array();
    if (is_array($results)) {
        foreach ($results as $node) {
            $excludeds[] = $node->ex_node_id;
        }
    }
    return $excludeds;
}

//---------------------------------------------------
/**
 * renders website map (for shortcode)
 *
 * @uses wp_enqueue_style()
 * @uses wp_enqueue_script()
 * @uses plugins_url()
 * @uses admin_url()
 *
 * @return string
 */
function wm_render_website_map($atts)
{
    $mp_id = (isset($atts['id'])) ? $atts['id'] : 0;
    $map = wm_read_map($mp_id);

    $uniqid = uniqid();
    $out = '';
    if ('horizontal' == $map['mp_type']) {
        /* start ifram */
        $out = '<iframe src="' . home_url('?wmpageid=wm_h_map&mp_id=' . $mp_id) . '" 
				style="border: none; width: ' . $map['mp_container_width'] . 'px; height: ' . $map['mp_container_height'] . 'px; 
				background-color: #' . $map['mp_container_bgc'] . '; padding: 0; margin: 0;" scrolling="no" ></iframe>';
        /* end iframe */

        /* start default */
        /*wp_enqueue_script( 'hor-tree-js', plugins_url( 'lib/horizontal-tree/js/jquery.tree.js', WM_PLUGIN_MAIN_FILE ) );
		
		$maxWidth = 120;
		if( $map['mp_enable_thumbnail'] && $map['mp_thumbnail_size'] > 64 ){
			$maxWidth = 160;
		}
		
		$out = '<style type="text/css">
			.first_name{color: #' . $map['mp_title_color'] . ';}
			.first_name a:link{color: #' . $map['mp_title_color'] . '; text-decoration: none;}
			.first_name a:hover{color: #' . $map['mp_title_color'] . '; text-decoration: none;}
			ul.tree li > div { background:#' . $map['mp_bgcolor'] . '; max-width: <?php echo $maxWidth ?>px !important;}
			ul.tree li div.current { background:#' . $map['mp_current_bgcolor'] . '; }
			ul.tree li div.children { background:#' . $map['mp_child_bgcolor'] . '; }
			ul.tree li div.parent { background:#' . $map['mp_parent_bgcolor'] . '; }
		</style>
		<div class="wm-tree-container" style="padding: 0px; margin: 0px;" id="treeContainer_' . $uniqid . '">' . wm_horizontal_tree( $mp_id, false ) . '</div>
		<script type="text/javascript">
			if(!(typeof wm_options == "object" && typeof wm_options.admin_url == "string")){
				var wm_options = {
					admin_url: "' . admin_url() . '",
					images_url: "' . plugins_url( 'lib/horizontal-tree/images/', WM_PLUGIN_MAIN_FILE ) . '",
					plug_images_url: "' . plugins_url( 'images/', WM_PLUGIN_MAIN_FILE ) . '"
				};
			}
			if(typeof wm_options.mp_enable_thumbnail == "undefined"){
				wm_options.mp_enable_thumbnail = ' . ( ( $map['mp_enable_thumbnail'] )? '1' : '0' ) . ';
				wm_options.mp_thumbnail_size = ' . ( ( $map['mp_thumbnail_size'] > 0 )? $map['mp_thumbnail_size'] : '0') . ';
				wm_options.mp_thumbnail_shape = "' . $map['mp_thumbnail_shape'] . '";
				wm_options.mp_thumbnail_place = "' . $map['mp_thumbnail_place'] . '";
			}
		</script>';*/
        /* end default */
    } else if ('vertical' == $map['mp_type']) {
        wp_enqueue_script('jstree-js', plugins_url('lib/jstree/dist/jstree.min.js', WM_PLUGIN_MAIN_FILE));

        $out = '<div class="wm-tree-container">
			<div id="tree_' . $uniqid . '" class="wm-vertical-map"></div>
		</div>
		<script type="text/javascript">
			if(!(typeof wm_options == "object" && typeof wm_options.admin_url == "string")){
				var wm_options = {
					admin_url: "' . admin_url() . '",
					images_url: "' . plugins_url('lib/horizontal-tree/images/', WM_PLUGIN_MAIN_FILE) . '",
					plug_images_url: "' . plugins_url('images/', WM_PLUGIN_MAIN_FILE) . '"
				};
			}
			
			jQuery(document).ready(function(){
				jQuery("#tree_' . $uniqid . '").jstree({
					"core" : {
						"themes" : {
							"name": "proton",
							"responsive": true,
							"icons": ' . (($map['mp_enable_thumbnail']) ? 'true' : 'false') . ',
						},
						"check_callback" : true,
						"data" : {
							"url" : function (node) {
							  return "' . admin_url() . 'admin-ajax.php";
							},
							"data" : function (node) {
							  return {
								  "action": "wm_get_vertical_tree_nodes",
								  "mp_id": ' . $mp_id . ',
								  "parent" : node.id
							  };
							}
						}
					},
					"dnd" : {
                    "is_draggable" : false
                },
					"types" : {
						"default" : {
							"icon" : "fa fa-folder icon-state-warning icon-lg"
						},
						"file" : {
							"icon" : "fa fa-file icon-state-warning icon-lg"
						}
					},
					"state" : { "key" : "demo3" },
					"plugins" : [ "dnd", "state", "types" ]
				});
			});
		</script>';
    }
    return $out;
}

//---------------------------------------------------
function wm_mce_plugin($plgs)
{
    if (get_bloginfo('version') < 3.9) {
        $plgs['wmtreeshortcode'] = plugins_url('lib/mceplugin/mceplugin.js', WM_PLUGIN_MAIN_FILE);
    } else {
        $plgs['wmtreeshortcode'] = plugins_url('lib/mceplugin/mceplugin_x4.js', WM_PLUGIN_MAIN_FILE);
    }
    return $plgs;
}

//---------------------------------------------------
function wm_mce_button($btns)
{
    array_push($btns, 'separator', 'wmsrtcdcombo');
    return $btns;
}

//---------------------------------------------------
/**
 * deletes a vertical tree node
 *
 * @uses wpdb::get_results()
 * @uses wpdb::insert()
 *
 * @return void
 */
function wm_delete_vertical_tree_node()
{
    global $wpdb;

    $data = array();
    $mp_id = (isset($_POST['mp_id'])) ? (int)$_POST['mp_id'] : 0;
    $nodes = (isset($_POST['nodes'])) ? $_POST['nodes'] : array();
    if (is_array($nodes)) {
        foreach ($nodes as $node) {
            $data = array('mp_id' => $mp_id, 'ex_node_id' => $node['id'], 'ex_parent_node_id' => $node['parent_id']);
            if (!$wpdb->insert($wpdb->prefix . 'wm_excludeds', $data, array('%d', '%s', '%s'))) {
                echo '0';
                break;
            }
        }
        echo '1';
    }
    exit();
}

//---------------------------------------------------
/**
 * restores a vertical tree node
 *
 * @uses wpdb::get_results()
 * @uses wpdb::insert()
 *
 * @return void
 */
function wm_restore_vertical_tree_node()
{
    global $wpdb;

    $where = array();
    $mp_id = (isset($_POST['mp_id'])) ? (int)$_POST['mp_id'] : 0;
    $nodes = (isset($_POST['nodes'])) ? $_POST['nodes'] : array();
    if (is_array($nodes)) {
        foreach ($nodes as $node) {
            $where = array('mp_id' => $mp_id, 'ex_node_id' => $node);
            if (!$wpdb->delete($wpdb->prefix . 'wm_excludeds', $where, array('%d', '%s'))) {
                echo '0';
                break;
            }
        }
        echo '1';
    }
    exit();
}

//---------------------------------------------------
/**
 * deletes a map
 *
 * @uses wpdb::delete()
 *
 * @return void
 */
function wm_delete_map()
{
    global $wpdb;
    $mp_id = (isset($_POST['mp_id'])) ? (int)$_POST['mp_id'] : 0;

    echo ($wpdb->delete($wpdb->prefix . 'wm_maps', array('mp_id' => $mp_id), array('%d')) !== false) ? '1' : '0';
    exit();
}

//---------------------------------------------------
/**
 * returns an object taxonomies
 *
 * @uses get_object_taxonomies()
 *
 * @return array
 */
function wm_get_object_taxonomies($post_type)
{
    $taxonomies = get_object_taxonomies($post_type, 'objects');
    $res = array();
    if (!empty($taxonomies)) {
        foreach ($taxonomies as $key => $tax) {
            if ($tax->public && $tax->hierarchical) {
                $res[$key] = $tax;
            }
        }
    }
    return $res;
}

//---------------------------------------------------
/**
 * returns the suitable icon
 *
 * @uses plugins_url()
 * @uses post_type_supports()
 * @uses has_post_thumbnail()
 * @uses get_post_thumbnail_id()
 * @uses wp_get_attachment_image_src()
 * @uses home_url()
 *
 * @return string
 */
function wm_get_icon($nodeType, $post_type = '', $post_id = 0)
{

    switch ($nodeType) {
        case 'root':
            return plugins_url('images/home_22.png', WM_PLUGIN_MAIN_FILE);
            break;

        case 'parent':
            return plugins_url('images/folder_22.png', WM_PLUGIN_MAIN_FILE);
            break;

        case 'taxonomy':
            return plugins_url('images/folder_22.png', WM_PLUGIN_MAIN_FILE);
            break;

        case 'term':
            return plugins_url('images/category_22.png', WM_PLUGIN_MAIN_FILE);
            break;

        case 'post':
            if (post_type_supports($post_type, 'thumbnail')) {
                if (has_post_thumbnail($post_id)) {
                    $thumb_id = get_post_thumbnail_id($post_id);
                    $arr = wp_get_attachment_image_src($thumb_id, 'wm_vtree_thumbnail');
                    if (preg_match('/22x22\./', $arr[0])) {
                        return $arr[0];
                    }
                    return home_url('?wmpageid=wm_image_gen&atchid=' . $thumb_id);
                    //wp_get_attachment_link( $thumb_id, false );
                } else {
                    return plugins_url('images/post_22.png', WM_PLUGIN_MAIN_FILE);
                }
            } else {
                return plugins_url('images/post_22.png', WM_PLUGIN_MAIN_FILE);
            }
            break;

        case 'archive':
            return plugins_url('images/archives_22.png', WM_PLUGIN_MAIN_FILE);
            break;
    }
}

//---------------------------------------------------
/**
 * streams an image with 22x22 size
 *
 * @uses wp_get_attachment_image_src()
 * @uses get_attached_file()
 * @uses wp_get_image_editor()
 * @uses WP_Image_Editor::resize()
 * @uses WP_Image_Editor::set_quality()
 * @uses WP_Image_Editor::stream()
 *
 * @return void
 */
function wm_get_custom_image_size()
{
    ob_clean();
    $attach_id = (isset($_GET['atchid'])) ? $_GET['atchid'] : NULL;
    $attach = wp_get_attachment_image_src($attach_id, 'wm_vtree_thumbnail');
    $ext = wm_get_file_extension($attach[0]);
    if (!empty($attach_id)) {
        $editor = wp_get_image_editor(get_attached_file($attach_id));
        if ($editor instanceof WP_Image_Editor) {
            $editor->resize(22, 22, true);
            $editor->set_quality(100);
            $editor->stream('image/' . $ext);
        }
    }
    exit();
}

//---------------------------------------------------
/**
 * returns a file extension
 *
 * @return string
 */
function wm_get_file_extension($fileName)
{
    return substr($fileName, strrpos($fileName, '.') + 1);
}

//---------------------------------------------------
function wm_render_shortcodes()
{
    global $pagenow;
    if (('post.php' == $pagenow || 'post-new.php' == $pagenow) && current_user_can('edit_posts') && current_user_can('edit_pages')) {
        $maps = wm_get_all_maps();
        $arr = array();
        if (get_bloginfo('version') >= 3.9) {
            for ($i = 0; $i < count($maps['data']); $i++) {
                $arr[$i]['name'] = $maps['data'][$i]['mp_name'];
                $arr[$i]['shortcode'] = '[wm_website_map id="' . $maps['data'][$i]['mp_id'] . '"]';
            }
        } else {
            for ($i = 0; $i < count($maps['data']); $i++) {
                $arr[] = '[wm_website_map id="' . $maps['data'][$i]['mp_id'] . '"]';
            }
        }
        if (!empty($arr)) {
            ?>
            <script type="text/javascript">
                var wm_maps_shortcode = <?php echo json_encode($arr) ?>;
            </script>
            <?php
        }
    }
}

//---------------------------------------------------
function wm_render_horiontal_map()
{
    $mp_id = (isset($_POST['mp_id'])) ? $_POST['mp_id'] : 0;
    echo wm_horizontal_tree($mp_id, false);
    exit();
}

//---------------------------------------------------
function wm_render_h_map_to_frame()
{
    wm_enqueue_for_frame();
    $mp_id = (isset($_GET['mp_id'])) ? $_GET['mp_id'] : NULL;
    include(WM_PLUGIN_ROOT_DIR . WM_DS . 'views' . WM_DS . 'front' . WM_DS . 'horizontal_map.php');
    exit();
}

//---------------------------------------------------
function wm_enqueue_for_frame()
{
    ?>
    <link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=Cabin:400,700,600"/>
    <link rel="stylesheet" type="text/css"
          href="<?php echo plugins_url('lib/horizontal-tree/style.css', WM_PLUGIN_MAIN_FILE) ?>"/>
    <link rel="stylesheet" type="text/css" href="<?php echo plugins_url('css/css.css', WM_PLUGIN_MAIN_FILE) ?>"/>
    <script type="text/javascript"
            src="<?php echo plugins_url('lib/horizontal-tree/js/jquery-1.11.1.min.js', WM_PLUGIN_MAIN_FILE) ?>"></script>
    <script type="text/javascript"
            src="<?php echo plugins_url('lib/horizontal-tree/js/jquery-migrate-1.2.1.min.js', WM_PLUGIN_MAIN_FILE) ?>"></script>
    <script type="text/javascript"
            src="<?php echo plugins_url('lib/horizontal-tree/js/jquery-ui.js', WM_PLUGIN_MAIN_FILE) ?>"></script>
    <script type="text/javascript"
            src="<?php echo plugins_url('lib/horizontal-tree/js/jquery-ui.js', WM_PLUGIN_MAIN_FILE) ?>"></script>
    <script type="text/javascript"
            src="<?php echo plugins_url('lib/horizontal-tree/js/jquery.tree.js', WM_PLUGIN_MAIN_FILE) ?>"></script>
    <script type="text/javascript" src="<?php echo plugins_url('js/js.js', WM_PLUGIN_MAIN_FILE) ?>"></script>
    <?php
}

//---------------------------------------------------
/**
 * retrieve maximum id
 *
 * @uses wpdb::get_var()
 *
 * @return integer
 */
function getMapsNextMaxId()
{
    global $wpdb;
    $sql = "SELECT IFNULL(MAX(mp_id), 0) + 1 m FROM {$wpdb->prefix}wm_maps";
    return $wpdb->get_var($sql);
}

//---------------------------------------------------
/**
 * initializes
 *
 * @uses load_theme_textdomain()
 *
 * @return void
 */
function wm_plugin_ini()
{
    wm_add_shortcode_to_editor();
    load_theme_textdomain('websitemap', WM_PLUGIN_ROOT_DIR . WM_DS . 'languages');
}

//---------------------------------------------------


function wmap_after_plugin_row($plugin_file, $plugin_data, $status)
{

    if (get_option('wmap_upgrade_message') != 'yes') {
        $class_name = $plugin_data['slug'];

        echo '<tr id="' . $class_name . '-plugin-update-tr" class="plugin-update-tr active">';
        echo '<td  colspan="3" class="plugin-update">';
        echo '<div id="' . $class_name . '-upgradeMsg" class="update-message" style="background:#FFFF99" >';

        echo 'You are running Tree Website Map LITE. To get more features, you can <a href="http://www.wp-buy.com/product/wp-tree-pro/" target="_blank"><strong>upgrade now</strong></a> ';

        //echo '<span id="HideMe" style="cursor:pointer" ><a href="javascript:void(0)"><strong>dismiss</strong></a> this message</span>';
        echo '</div>';
        echo '</td>';
        echo '</tr>';

        ?>

        <script type="text/javascript">
            jQuery(document).ready(function () {
                var row = jQuery('#<?php echo $class_name;?>-plugin-update-tr').closest('tr').prev();
                jQuery(row).addClass('update');


                jQuery("#HideMe").click(function () {
                    jQuery.ajax({
                        type: 'POST',
                        url: '<?php echo admin_url();?>/admin-ajax.php',
                        data: {
                            action: 'wmap_HideMessageAjaxFunction'
                        },
                        success: function (data, textStatus, XMLHttpRequest) {

                            jQuery("#<?php echo $class_name;?>-upgradeMsg").hide();

                        },
                        error: function (MLHttpRequest, textStatus, errorThrown) {
                            alert(errorThrown);
                        }
                    });
                });

            });
        </script>

        <?php
    }
}

?>