<?php
/*
Plugin Name: WP Social Buttons
Plugin URI: http://www.mrwebsolution.in/
Description: "wp-social-buttons" is the very simple plugin for add to social buttons on your site!.
Author: Raghunath
Author URI: http://raghunathgurjar.wordpress.com
Version: 1.5
*/

/*  Copyright 2014  Raghunath Gurjar  (email : raghunath.0087@gmail.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

/*
 * WP Social Buttons
 * @add_action
 * @register_setting
 * 
 * */
//Admin "WP Social Buttons" Menu Item
if (!defined('ABSPATH')) {
    die('-1');
}

add_action('admin_menu', 'wpsb_sidebar_menu');
if (!function_exists('wpsb_sidebar_menu')):
    function wpsb_sidebar_menu()
    {
        add_options_page('WP Social Buttons', 'WP Social Buttons', 'manage_options', 'wpsb-settings', 'wpsb_sidebar_admin_option_page');
    }
endif;
//Define Action for register "WP Social Buttons" Options
add_action('admin_init', 'wpsb_sidebar_init');
//Register "WP Social Buttons" options
if (!function_exists('wpsb_sidebar_init')):
    function wpsb_sidebar_init()
    {
        register_setting('wpsb_sidebar_options', 'wpsb_active');
        register_setting('wpsb_sidebar_options', 'wpsb_position');
        register_setting('wpsb_sidebar_options', 'wpsb_top_margin');
        register_setting('wpsb_sidebar_options', 'wpsb_delayTimeBtn');
        register_setting('wpsb_sidebar_options', 'wpsb_page_hide_home');
        register_setting('wpsb_sidebar_options', 'wpsb_page_hide_post');
        register_setting('wpsb_sidebar_options', 'wpsb_page_hide_page');
        register_setting('wpsb_sidebar_options', 'wpsb_fpublishBtn');
        register_setting('wpsb_sidebar_options', 'wpsb_tpublishBtn');
        register_setting('wpsb_sidebar_options', 'wpsb_gpublishBtn');
        register_setting('wpsb_sidebar_options', 'wpsb_ppublishBtn');
        register_setting('wpsb_sidebar_options', 'wpsb_lpublishBtn');
        register_setting('wpsb_sidebar_options', 'wpsb_fb_url');
        register_setting('wpsb_sidebar_options', 'wpsb_tw_url');
        register_setting('wpsb_sidebar_options', 'wpsb_li_url');
        register_setting('wpsb_sidebar_options', 'wpsb_gp_url');
        register_setting('wpsb_sidebar_options', 'wpsb_pin_url');
        register_setting('wpsb_sidebar_options', 'wpsb_deactive_for_mob');
        register_setting('wpsb_sidebar_options', 'wpsb_disable_for_home');
    }
endif;
// Add settings link to plugin list page in admin
if (!function_exists('wpsb_add_settings_link')):
    function wpsb_add_settings_link($links)
    {
        $settings_link = '<a href="options-general.php?page=wpsb-settings">' . __('Settings', 'wpsb') . '</a>';
        array_unshift($links, $settings_link);
        return $links;
    }
endif;
$plugin = plugin_basename(__FILE__);
add_filter("plugin_action_links_$plugin", 'wpsb_add_settings_link');

/* 
*Display Options form for WP Social Buttons 
*/
if (!function_exists('wpsb_sidebar_admin_option_page')):
    function wpsb_sidebar_admin_option_page()
    { ?>

        <div id="wpsb-settings">

            <h1>WP Social Buttons Settings</h1>

            <!-- Start Options Form -->
            <form action="options.php" method="post" id="wpsb-sidebar-admin-form">
                <div id="wpsb-tab-menu"><a id="wpsb-general" class="wpsb-tab-links active">General</a> <a
                        id="wpsb-advance" class="wpsb-tab-links">Advance Settings</a> <a id="wpsb-support"
                                                                                         class="wpsb-tab-links">Support</a>
                    <a id="wpsb-gopro" class="wpsb-tab-links">GO Pro</a></div>
                <div class="wpsb-setting">
                    <!-- General Setting -->
                    <div class="first wpsb-tab" id="div-wpsb-general">
                        <h2>General Settings</h2>
                        <p><label><strong><?php _e('Enable:'); ?></strong></label>&nbsp;<input type="checkbox"
                                                                                               id="wpsb_active"
                                                                                               name="wpsb_active"
                                                                                               value='1' <?php if (get_option('wpsb_active') != '') {
                                echo ' checked="checked"';
                            } ?>/></p>

                        <p><label><strong><?php _e('Siderbar Position:'); ?></strong></label>&nbsp;<select
                                id="wpsb_position" name="wpsb_position">
                                <option value="left" <?php if (get_option('wpsb_position') == 'left') {
                                    echo 'selected="selected"';
                                } ?>>Left
                                </option>
                                <option value="right" <?php if (get_option('wpsb_position') == 'right') {
                                    echo 'selected="selected"';
                                } ?>>Right
                                </option>
                            </select></p>
                        <p><label><strong><?php _e('Publish Buttons:'); ?></strong></label>&nbsp;<input type="checkbox"
                                                                                                        id="publish1"
                                                                                                        value="yes"
                                                                                                        name="wpsb_fpublishBtn" <?php if (get_option('wpsb_fpublishBtn') == 'yes') {
                                echo 'checked="checked"';
                            } ?>/> Facebook&nbsp;<input type="checkbox" id="publish2" name="wpsb_tpublishBtn"
                                                        value="yes" <?php if (get_option('wpsb_tpublishBtn') == 'yes') {
                                echo 'checked="checked"';
                            } ?>/> Twitter&nbsp;<input type="checkbox" id="publish3" name="wpsb_gpublishBtn"
                                                       value="yes" <?php if (get_option('wpsb_gpublishBtn') == 'yes') {
                                echo 'checked="checked"';
                            } ?>/> Google Plus&nbsp;<input type="checkbox" id="publish4" name="wpsb_lpublishBtn"
                                                           value="yes" <?php if (get_option('wpsb_lpublishBtn') == 'yes') {
                                echo 'checked="checked"';
                            } ?>/>Linkdin&nbsp;<input type="checkbox" id="publish6" name="wpsb_ppublishBtn"
                                                      value="yes" <?php if (get_option('wpsb_ppublishBtn') == 'yes') {
                                echo 'checked="checked"';
                            } ?>/> Pinterest</p>

                        <p><label><strong><?php _e('Delay Time:'); ?></strong><label>&nbsp;<input type="text"
                                                                                                  name="wpsb_delayTimeBtn"
                                                                                                  id="wpsb_delayTimeBtn"
                                                                                                  value="<?php echo get_option('wpsb_delayTimeBtn') ? get_option('wpsb_delayTimeBtn') : 2000; ?>"
                                                                                                  size="15">[<i>Publish
                                        share buttons after given time(millisecond)</i>]</p>

                    </div>

                    <!-- Advance Setting -->
                    <div class="wpsb-tab" id="div-wpsb-advance">
                        <h2>Advance Settings</h2>
                        <p><label><strong><?php _e('Top Margin:'); ?></strong></label>&nbsp;<input type="text"
                                                                                                   id="wpsb_top_margin"
                                                                                                   name="wpsb_top_margin"
                                                                                                   value="<?php echo get_option('wpsb_top_margin'); ?>"
                                                                                                   placeholder="10% OR 10px"
                                                                                                   size="15"/></p>
                        <p><label><strong><?php _e('Disable For Mobile', 'wpsb'); ?></strong></label>&nbsp;<input
                                type="checkbox" id="wpsb_deactive_for_mob" name="wpsb_deactive_for_mob"
                                value="yes" <?php if (get_option('wpsb_deactive_for_mob') == 'yes') {
                                echo 'checked="checked"';
                            } ?>/></p>
                        <p><label><strong><?php _e('Disable On Home Page', 'wpsb'); ?></strong></label>&nbsp;<input
                                type="checkbox" id="wpsb_disable_for_home" name="wpsb_disable_for_home"
                                value="yes" <?php if (get_option('wpsb_disable_for_home') == 'yes') {
                                echo 'checked="checked"';
                            } ?>/></p>
                    </div>
                    <!-- Support -->
                    <div class=" author wpsb-tab" id="div-wpsb-support">
                        <h2>Plugin Support</h2>
                        <table>
                            <tr>
                                <td width="50%"><p><a
                                            href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=ZEMSYQUZRUK6A"
                                            target="_blank" style="font-size: 17px; font-weight: bold;"><img
                                                src="https://www.paypal.com/en_US/i/btn/btn_donate_LG.gif"
                                                title="Donate for this plugin"></a></p>
                                    <p><strong>Plugin Author:</strong><br><img
                                            src="<?php echo plugins_url('images/raghu.jpg', __FILE__); ?>" width="75"
                                            height="75" class="authorimg"><br><a
                                            href="http://raghunathgurjar.wordpress.com" target="_blank">Raghunath
                                            Gurjar</a></p>
                                    <p><a href="mailto:raghunath.0087@gmail.com" target="_blank" class="contact-author">Contact
                                            Author</a></p></td>
                                <td><p><strong>My Other Plugins:</strong><br>
                                    <ul>
                                        <li><a href="https://wordpress.org/plugins/protect-wp-admin/" target="_blank">Protect
                                                WP-Admin</a></li>
                                        <li>
                                            <a href="https://wordpress.org/plugins/custom-share-buttons-with-floating-sidebar"
                                               target="_blank">Custom Share Buttons with Floating Sidebar</a></li>
                                        <li><a href="https://wordpress.org/plugins/wp-testimonial" target="_blank">WP
                                                Testimonial</a></li>
                                        <li><a href="https://wordpress.org/plugins/wp-easy-recipe/" target="_blank">WP
                                                Easy Recipe</a></li>
                                        <li><a href="https://wordpress.org/plugins/wp-youtube-gallery/" target="_blank">WP
                                                Youtube Gallery</a></li>
                                        <li><a href="https://wordpress.org/plugins/cf7-advance-security/"
                                               target="_blank">CF7 Advance Security</a></li>
                                        <li><a href="https://wordpress.org/plugins/wc-sales-count-manager/"
                                               target="_blank">WC Sales Count Manager</a></li>
                                    </ul>
                                    </p></td>
                            </tr>
                        </table>
                    </div>
                    <!-- gopro -->
                    <div class="author wpsb-tab" id="div-wpsb-gopro">
                        <h2>Pro Verion</h2>
                        <p>Please find feature of addon given below :</p>
                        <ol>
                            <li>Floating Sidebar</li>
                            <li>Social Share Buttons</li>
                            <li>option for hide “Floating Sidebar” on page/post/product/categoyr/archive pages</li>
                            <li>Option for define distance of sidebar from left/right/bottom</li>
                            <li>option for hide “Social Share Buttons” on page/post/product/categoyr/archive pages</li>
                            <li>Option for define position of “Social Share Buttons” (bottom/above of content)</li>
                            <li>Option for set floating sidebar position to horizontal automaticaly for mobile users
                            </li>
                            <li>Option for define “Sidebar Backgroung Color”</li>
                            <li>Option for disable “Show/Hide” buttons of sidebar</li>
                            <li>Option for define your message (show/hide)</li>
                            <li>Show/Hide “Social Share Buttons” and “Floating Sidebar” on any specific pages</li>
                            <li>Options to show/hide the social buttons</li>
                            <li>Options to define the postion(Left/Right/Bottom) of floating sidebar</li>
                            <li>Option To Disabe For Mobile</li>
                            <li>Extra Buttons (Tumblr, Buffer, StumbleUpon, Print, Email, Youtube)</li>
                            <li>Option for set feature image as pinterest image</li>
                        </ol>

                        <p><a target="_blank" href="http://extensions.mrwebsolution.in/wp-social-buttons-pro"
                              class="pagelink">GO Pro</a> OR
                            <a target="_blank" href="http://extensions.mrwebsolution.in/wp-social-buttons-pro"
                               class="pagelink">Live Demo</a>
                        </p>

                    </div>

                </div>
                <p>&nbsp;</p>
                <span
                    class="submit-btn"><?php echo get_submit_button('Save Settings', 'button-primary', 'submit', '', ''); ?></span>
                <?php settings_fields('wpsb_sidebar_options'); ?>
            </form>
            <!-- End Options Form -->
        </div>
        <?php
    }
endif;
require dirname(__FILE__) . '/wpsb-class.php';
/** add js into admin footer */
add_action('admin_footer', 'init_wpsb_admin_scripts');
if (!function_exists('init_wpsb_admin_scripts')):
    function init_wpsb_admin_scripts()
    {
        wp_register_style('wpsb_admin_style', plugins_url('css/wpsb-admin.css', __FILE__));
        wp_enqueue_style('wpsb_admin_style');
        echo $script = '<script type="text/javascript">
	/* WP Social Buttons js for admin */
	jQuery(document).ready(function(){
		jQuery(".wpsb-tab").hide();
		jQuery("#div-wpsb-general").show();
	    jQuery(".wpsb-tab-links").click(function(){
		var divid=jQuery(this).attr("id");
		jQuery(".wpsb-tab-links").removeClass("active");
		jQuery(".wpsb-tab").hide();
		jQuery("#"+divid).addClass("active");
		jQuery("#div-"+divid).fadeIn();
		})
		})
	</script>';
    }
endif;
/* 
*Delete the options during disable the plugins 
*/
if (function_exists('register_uninstall_hook'))
    register_uninstall_hook(__FILE__, 'wpsb_sidebar_uninstall');
//Delete all Custom Tweets options after delete the plugin from admin
if (!function_exists('wpsb_sidebar_uninstall')):
    function wpsb_sidebar_uninstall()
    {
        delete_option('wpsb_active');
    }
endif;
?>
