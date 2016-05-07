<?php
/*
Plugin Name: ST Categories
Plugin URI: http://beautiful-templates.com
Description: A list or dropdown of categories
Version: 1.0.0
Author: Beautiful Templates
Author URI: http://beautiful-templates.com
License:  GPL2
*/
define('ST_CAT_ROOT', dirname(__FILE__) . '/');
define('ST_CAT_CSS_URL', trailingslashit(plugins_url('/css/', __FILE__)));
define('ST_CAT_JS_URL', trailingslashit(plugins_url('/js/', __FILE__)));
define('ST_CAT_IMG_URL', trailingslashit(plugins_url('/images/', __FILE__)));

class st_categories
{
    function __construct()
    {
        $this->actions();
        $this->shortcodes();
    }

    function actions()
    {
        // Active notice 
        $st_Cat_notice = get_option('st_Cat_admin_notice');
        if ($st_Cat_notice == 'TRUE' && is_admin()) {
            add_action('admin_notices', array(&$this, 'st_Cat_activation_notice'));
            update_option('st_Cat_admin_notice', 'FALSE');
        }
        add_action('wp_enqueue_scripts', array(&$this, "add_style"));
        add_action('init', array(&$this, 'add_button'));
        add_action('admin_footer', array($this, 'get_shortcodes'));
        add_action('admin_menu', array($this, 'st_register_admin_menu_page'));
        add_action('admin_enqueue_scripts', array($this, 'admin_style'));
        add_action('plugins_loaded', array($this, 'st_cat_wp_init'));
    }

    /* 
    
    *Active Notice
    
    */

    function shortcodes()
    {
        add_shortcode('st-categories', array(&$this, 'St_Categories_Shortcode'));
    }

    function st_Cat_activation_notice()
    {
        echo '<div class="updated" style="background-color: #53be2a; border-color:#199b57">            
        <p>Thank you for installing <strong>ST Categories WP</strong></p>
    </div>';
    }

    function st_Cat_activate()
    {
        update_option('st_Cat_admin_notice', 'TRUE');
    }

    function reg_act_hook()
    {
        register_activation_hook(__FILE__, array(&$this, 'st_Cat_activate'));
    }

    /* 

    *Admin Menu Item
    
    */

    function st_cat_wp_init()
    {
        $plugin_dir = basename(dirname(__FILE__)) . '/languages/';
        load_plugin_textdomain('st-categories-wp', false, $plugin_dir);
    }

    function st_register_admin_menu_page()
    {
        add_options_page('ST Categories WP', 'ST Categories WP', 'manage_options', 'st_categories_wp', array(&$this, 'admin_menu_page'));
    } // END FORM

    function admin_menu_page()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') :
            $this->save_code('css/style.css', $_POST['customcss']);
        endif;
        ?>
        <div id="st-categories" class="st-categories">
            <h2><?php _e('ST Categories WP', 'st-categories-wp') ?></h2>
            <div class="main box left">
                <h3 class="box-title">
                    <div class="dashicons dashicons-admin-generic"></div><?php _e('Custom Css', 'st-categories-wp') ?>
                </h3>
                <div class="content">
                    <form method="post" action="" id="signUp">
                        <div>
                            <div class="label"><p><?php _e('Custom Css', 'st-categories-wp') ?></p></div>
                            <textarea name="customcss"><?php $this->load_code('style.css'); ?></textarea>
                        </div>
                        <div class="sts-actions popup">
                            <div class="box-button">
                                <input class="sts-button save" type="submit" value="save"/>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="main box right">
                <?php $this->copyright(); ?>
            </div>
        </div>
        <script>
            jQuery(document).ready(function ($) {
                $(window).load(function () {
                    var feedURL = 'http://beautiful-templates.com/evo/category/products/feed/';
                    $.ajax({
                        type: "GET",
                        url: document.location.protocol + '//ajax.googleapis.com/ajax/services/feed/load?v=1.0&num=1000&callback=?&q=' + encodeURIComponent(feedURL),
                        dataType: 'json',
                        success: function (xml) {
                            var item = xml.responseData.feed.entries;

                            var html = "<ul>";
                            $.each(item, function (i, value) {
                                html += '<li><a href="' + value.link + '">' + value.title + '</a></li>';
                                if (i === 9) {
                                    return false;
                                }
                            });
                            html += "</ul>";
                            $('.st_load_rss').html(html);
                        }

                    });
                });
            });
        </script>
        <?php
    }

    function save_code($file, $content)
    {
        if (current_user_can('edit_plugins')) :
            $filename = ST_CAT_ROOT . $file;
            $setContent = wp_unslash($content); // Remove slashes from a string or array of strings.
            if (is_writeable($filename)) :
                $setfile = fopen($filename, "w+") or die("Unable to open file!");
                if ($setfile !== false) :
                    fwrite($setfile, urldecode($setContent));
                    fclose($setfile);
                endif;
            endif;
        else :
            wp_die('<p>' . __('You do not have sufficient permissions to edit plugin for this site.') . '</p>');
        endif;
    }

    /* 

    * SHORTCODE
    
    */

    function load_code($file)
    {
        $filename = ST_CAT_CSS_URL . trim($file);
        if (@file_get_contents($filename) == true) :
            $code = @file_get_contents($filename);
            echo $code;
        else :
            return false;
        endif;
    }

    function copyright()
    {
        ?>
        <h3 class="box-title">
            <div class="dashicons dashicons-sos"></div><?php _e('Abouts', 'st-categories-wp') ?></h3>
        <div class="st-box">
            <div class="box-content">
                <div class="st-row">
                    Hi,</br></br>We are Beautiful-Templates and we provide Wordpress Themes & Plugins, Joomla Templates
                    & Extensions.</br>Thank you for using our products. Let drop us feedback to improve products &
                    services.</br></br>Best regards,</br> Beautiful Templates Team
                </div>
            </div>
            <div class="st-row st-links">
                <div class="col col-8 links">
                    <ul>
                        <li>
                            <a href="http://beautiful-templates.com/"
                               target="_blank"><?php _e('Home', 'st-categories-wp') ?></a>
                        </li>
                        <li>
                            <a href="http://beautiful-templates.com/amember/"
                               target="_blank"><?php _e('Submit Ticket', 'st-categories-wp') ?></a>
                        </li>
                        <li>
                            <a href="http://beautiful-templates.com/evo/forum/"
                               target="_blank"><?php _e('Forum', 'st-categories-wp') ?></a>
                        </li>
                        <li>
                            <?php add_thickbox(); ?>
                            <a href="<?php echo plugins_url('/doc/index.html', __FILE__); ?>?TB_iframe=true&width=1000&height=600"
                               class="thickbox"><?php _e('Document', 'st-categories-wp') ?></a>
                        </li>
                    </ul>
                </div>
                <div class="col col-2 social">
                    <ul>
                        <li>
                            <a href="https://www.facebook.com/beautifultemplates/" target="_blank">
                                <div class="dashicons dashicons-facebook-alt"></div>
                            </a>
                        </li>
                        <li>
                            <a href="https://twitter.com/cooltemplates/" target="_blank">
                                <div class="dashicons dashicons-twitter"></div>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="st-box st-rss">
            <div class="box-content">
                <div class="st-row st_load_rss">
                    <span class="spinner" style="display:block;"></span>
                </div>
            </div>
        </div>
        <?php
    }

    function St_Categories_Shortcode($atts = array())
    {
        extract(shortcode_atts(array(
            'title' => '',
            'show_count' => '',
            'hide_empty' => '',
            'depth' => '',
            'number' => '',
            'child_of' => '',
            'templates' => '',
            'type' => ''
        ), $atts));
        $classSuffix = (($type == 'true') ? "st-dropdown-categories" : "st-categories");
        $title = (($title != "") ? $title : "Categories");
        $show_count = (($show_count == 'true') ? 1 : 0);
        $number = (($number == 'true') ? 1 : 0);
        $hide_empty = (($hide_empty == 'true') ? 1 : 0);
        $depth = (($depth == 'true') ? 1 : 0);
        $value = array(
            'orderby' => 'name',
            'show_count' => $show_count,
            'hide_empty' => $hide_empty,
            'title_li' => '',
            'depth' => $depth,
            'number' => $number,
            'child_of' => $child_of,
        );
        echo "<div id='" . $classSuffix . "' class='" . $templates . "'><div class='main'>";
        echo "<div class='head'><h3>";
        if ($child_of && $child_of != 0) :
            $get_parent = get_category($child_of);
            if ($get_parent) :
                echo $get_parent->name;
            endif;
        else :
            echo $title;
        endif;
        echo "</h3></div>";
        if ($type && $type == 'true') :
            echo "<div class='content'>";
            echo wp_dropdown_categories($value);
            echo "</div>";
        else :
            echo "<div class='content'><ul class='st_cat_list_dropdown'>";
            $value['walker'] = new Custom_Walker_Category();
            wp_list_categories($value);
            echo "</ul></div>";
        endif;
        echo "</div></div>";
        if ($type && $type == 'true') :
            echo $this->dropdown_script();
        else :
            if ($templates == "style1") :
                echo $this->list_script();
            else :
                echo "";
            endif;
        endif;
    }

    function dropdown_script()
    {
        $output = "<script type='text/javascript'>
                    /* <![CDATA[ */
                    	var dropdown = document.getElementById('cat');
                    	function onCatChange() {
                    		if ( dropdown.options[dropdown.selectedIndex].value > 0 ) {
                    			location.href = '" . home_url() . "/?cat='+dropdown.options[dropdown.selectedIndex].value;
                    		}
                    	}
                    	dropdown.onchange = onCatChange;
                    /* ]]> */
                    </script>";
        return $output;
    }

    function list_script()
    {
        $output = "<script type='text/javascript'>
                    	jQuery(document).ready(function($) {
                            var menu_ul = $('#st-categories .st_cat_list_dropdown li ul')
                            menu_ul.prev().addClass('toogle');
                            
                            var menu_toogle  = $('#st-categories .st_cat_list_dropdown li span.toogle');
                    	    
                    	    menu_ul.show();
                            menu_toogle.addClass('active');
                    	    menu_toogle.click(function(e) {
                    	        e.preventDefault();
                    	        if(!$(this).hasClass('active')) {
             	                      $(this).prev().addClass('active');
                    	            $(this).addClass('active').next().stop(true,true).slideDown('normal');
                    	        } else {
                                    $(this).next().stop(true,true).slideUp('normal');
                    	            $(this).removeClass('active');
                                    $(this).prev().removeClass('active');
                    	        }
                    	    });
                    	
                    	});
                    </script>";
        return $output;
    }

    function get_shortcodes()
    {
        $categories = get_categories(array('hide_empty' => 0));
        $show = array();
        $show[] = (object)array('cat_ID' => '0', 'name' => 'All');
        $cats = array_merge($categories, $show);
        echo '<script type="text/javascript">
        var st_cat_id = new Array();
        var st_cat_name = new Array();';
        $count = 0;
        foreach ($cats as $cat) {
            echo "st_cat_id[{$count}] = '{$cat->cat_ID}';";
            echo "st_cat_name[{$count}] = '{$cat->name}';";
            $count++;
        }
        echo '</script>';
    }

    function add_button()
    {
        if (current_user_can('edit_posts') && current_user_can('edit_pages')) {
            add_filter('mce_external_plugins', array(&$this, 'add_plugin'));
            add_filter('mce_buttons', array(&$this, 'register_button'));
        }
    }

    function register_button($buttons)
    {
        array_push($buttons, "St_Categories_Shortcode");
        return $buttons;
    }

    function add_plugin($plugin_array)
    {
        $path = ST_CAT_JS_URL . 'button.js';
        $plugin_array['St_Categories_Shortcode'] = $path;
        return $plugin_array;
    }

    function add_style()
    {
        wp_enqueue_style('st-categories-style', ST_CAT_CSS_URL . 'style.css');
        wp_enqueue_style('st-font-awesome-ie7', ST_CAT_CSS_URL . 'font-awesome-ie7.min.css');
        wp_enqueue_style('st-font-awesome', ST_CAT_CSS_URL . 'font-awesome.min.css');
    }

    function admin_style()
    {
        wp_enqueue_style('st-cat-admin-style', ST_CAT_CSS_URL . 'admin.css');
    } // END COPPYRIGHT
} // END st_categories CLASS
$st_categories = new st_categories();
require_once(ST_CAT_ROOT . 'widget.php');

class Custom_Walker_Category extends Walker_Category
{

    function start_el(&$output, $category, $depth = 0, $args = array(), $id = 0)
    {
        extract($args);
        $cat_name = esc_attr($category->name);
        $cat_name = apply_filters('list_cats', $cat_name, $category);
        $link = '<a href="' . esc_url(get_term_link($category)) . '" ';
        if ($use_desc_for_title == 0 || empty($category->description))
            $link .= 'title="' . esc_attr(sprintf(__('View all posts filed under %s'), $cat_name)) . '"';
        else
            $link .= 'title="' . esc_attr(strip_tags(apply_filters('category_description', $category->description, $category))) . '"';
        $link .= '>';
        $link .= $cat_name;
        if (!empty($show_count))
            $link .= ' <span>' . intval($category->count) . '</span>';
        $link .= '</a>';
        $link .= '<span></span>';
        if (!empty($feed_image) || !empty($feed)) :
            $link .= ' ';
            if (empty($feed_image))
                $link .= '(';
            $link .= '<a href="' . esc_url(get_term_feed_link($category->term_id, $category->taxonomy, $feed_type)) . '"';
            if (empty($feed)) {
                $alt = ' alt="' . sprintf(__('Feed for all posts filed under %s'), $cat_name) . '"';
            } else {
                $title = ' title="' . $feed . '"';
                $alt = ' alt="' . $feed . '"';
                $name = $feed;
                $link .= $title;
            }
            $link .= '>';
            if (empty($feed_image))
                $link .= $name;
            else
                $link .= "<img src='$feed_image'$alt$title" . ' />';
            $link .= '</a>';
            if (empty($feed_image))
                $link .= ')';
        endif;
        if ('list' == $args['style']) {
            $output .= "\t<li";
            $class = 'cat-item cat-item-' . $category->term_id;


            // YOUR CUSTOM CLASS
            if ($depth)
                $class .= ' sub-' . sanitize_title_with_dashes($category->name);


            if (!empty($current_category)) {
                $_current_category = get_term($current_category, $category->taxonomy);
                if ($category->term_id == $current_category)
                    $class .= ' current-cat';
                elseif ($category->term_id == $_current_category->parent)
                    $class .= ' current-cat-parent';
            }
            $output .= ' class="' . $class . '"';
            $output .= ">$link\n";
        } else {
            $output .= "\t$link<br />\n";
        }
    } // function start_el

} // class Custom_Walker_Category