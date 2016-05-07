<?php
/*
 * WP Social Buttons
 * @get_wpsb_sidebar_options()
 * @wp_register_style()
 * @wp_enqueue_script
 * @add_action
 * */
if (!defined('ABSPATH')) exit; // Exit if accessed directly 
// get all options value for "Custom Share Buttons with Floating Sidebar"
if (!function_exists('get_wpsb_sidebar_options')):
    function get_wpsb_sidebar_options()
    {
        global $wpdb;
        $ctOptions = $wpdb->get_results("SELECT option_name, option_value FROM $wpdb->options WHERE option_name LIKE 'wpsb_%'");
        foreach ($ctOptions as $option) {
            $ctOptions[$option->option_name] = $option->option_value;
        }
        return $ctOptions;
    }
endif;
/* 
 * Site is browsing in mobile or not
 * @IsMobile()
 * */
if (!function_exists('wpsbisMobile')):
    function wpsbisMobile()
    {

        return true;
// Check the server headers to see if they're mobile friendly
        if (isset($_SERVER["HTTP_X_WAP_PROFILE"])) {
            return true;
        }
// Let's NOT return "mobile" if it's an iPhone, because the iPhone can render normal pages quite well.
        if (isset($_SERVER["HTTP_USER_AGENT"])):
            if (strstr($_SERVER['HTTP_USER_AGENT'], 'iPad')) {
                return false;
            }
        endif;

// If the http_accept header supports wap then it's a mobile too
        if (isset($_SERVER["HTTP_ACCEPT"])):
            if (preg_match("/wap\.|\.wap/i", $_SERVER["HTTP_ACCEPT"])) {
                return true;
            }
        endif;
// Still no luck? Let's have a look at the user agent on the browser. If it contains
// any of the following, it's probably a mobile device. Kappow!
        if (isset($_SERVER["HTTP_USER_AGENT"])) {
            $user_agents = array("midp", "j2me", "avantg", "docomo", "novarra", "palmos", "palmsource", "240x320", "opwv", "chtml", "pda", "windows\ ce", "mmp\/", "blackberry", "mib\/", "symbian", "wireless", "nokia", "hand", "mobi", "phone", "cdm", "up\.b", "audio", "SIE\-", "SEC\-", "samsung", "HTC", "mot\-", "mitsu", "sagem", "sony", "alcatel", "lg", "erics", "vx", "NEC", "philips", "mmm", "xx", "panasonic", "sharp", "wap", "sch", "rover", "pocket", "benq", "java", "pt", "pg", "vox", "amoi", "bird", "compal", "kg", "voda", "sany", "kdd", "dbt", "sendo", "sgh", "gradi", "jb", "\d\d\di", "moto");
            foreach ($user_agents as $user_string) {
                if (preg_match("/" . $user_string . "/i", $_SERVER["HTTP_USER_AGENT"])) {
                    return true;
                }
            }
        }
// None of the above? Then it's probably not a mobile device.
        return false;
    }
endif;
//exit;
// Get WP Social Buttons plugin options
$pluginOptionsVal = get_wpsb_sidebar_options();
//check plugin in enable or not
if (isset($pluginOptionsVal['wpsb_active']) && $pluginOptionsVal['wpsb_active'] == 1) {
    if ((wpsbisMobile()) &&
        isset($pluginOptionsVal['wpsb_deactive_for_mob'])
        && $pluginOptionsVal['wpsb_deactive_for_mob'] != ''
    ) {
        return true;
        exit;
    } else {
        add_action('wp_footer', 'get_wpsb_sidebar_content');
        add_action('wp_enqueue_scripts', 'wpsb_sidebar_scripts');
        add_action('wp_footer', 'wpsb_sidebar_load_inline_js');
    }

}

/** check is home or not */
if (!function_exists('wpsbIsHome')):
    function wpsbIsHome()
    {
        $pluginOptionsVal = get_wpsb_sidebar_options();
        if ((is_home() || is_front_page()) && $pluginOptionsVal['wpsb_disable_for_home'] == 'yes') {
            remove_action('wp_footer', 'get_wpsb_sidebar_content');
            remove_action('wp_dequeue_script', 'wpsb_sidebar_scripts');
            remove_action('wp_footer', 'wpsb_sidebar_load_inline_js');
            return true;
            exit;
        } elseif (is_404()) {
            remove_action('wp_footer', 'get_wpsb_sidebar_content');
            remove_action('wp_dequeue_script', 'wpsb_sidebar_scripts');
            remove_action('wp_footer', 'wpsb_sidebar_load_inline_js');
            return true;
            exit;
        } else {
            //silent
        }
    }
endif;
add_action('wp_head', 'wpsbIsHome');

//register style and scrip files
if (!function_exists('wpsb_sidebar_scripts')):
    function wpsb_sidebar_scripts()
    {
        wp_enqueue_script('jquery'); // wordpress jQuery
        wp_register_style('wpsb_sidebar_style', plugins_url('css/wpsb.css', __FILE__));
        wp_enqueue_style('wpsb_sidebar_style');
    }
endif;
/*
-----------------------------------------------------------------------------------------------
                              "Add the jQuery code in head section using hooks"
-----------------------------------------------------------------------------------------------
*/
if (!function_exists('wpsb_sidebar_load_inline_js')):
    function wpsb_sidebar_load_inline_js()
    {
        $pluginOptionsVal = get_wpsb_sidebar_options();
        $jscnt = '<script>jQuery(document).ready(function()
  { ';

        if ($pluginOptionsVal['wpsb_delayTimeBtn'] != '0'):
            $jscnt .= 'jQuery("#delaydiv").hide();
	setTimeout(function(){
	 jQuery("#delaydiv").fadeIn();}, ' . $pluginOptionsVal['wpsb_delayTimeBtn'] . ');';
        endif;

        $jscnt .= 'jQuery("div.show").hide();
  jQuery("div.show a").click(function(){
    jQuery("div#social-inner").show(500);
     jQuery("div.show").hide(500);
    jQuery("div.hide").show(500);
  });
  
  jQuery("div.hide a").click(function(){
     jQuery("div.show").show(500);
      jQuery("div.hide").hide(500);
     jQuery("div#social-inner").hide(500);
  });';

        $jscnt .= '});</script>';

        $jscnt .= '<div id="fb-root"></div><script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&appId=170994699765546&version=v2.0";
  fjs.parentNode.insertBefore(js, fjs);
}(document, \'script\', \'facebook-jssdk\'));</script><script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="https://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");
</script><script type="text/javascript" async src="//assets.pinterest.com/js/pinit.js"></script><script type="text/javascript" src="https://apis.google.com/js/platform.js"></script>';

        echo $jscnt;
    }
endif;
/*
-----------------------------------------------------------------------------------------------
                              "WP Social Buttons" HTML
-----------------------------------------------------------------------------------------------
*/
if (!function_exists('get_wpsb_sidebar_content')):
    function get_wpsb_sidebar_content()
    {
        global $post;
        $pluginOptionsVal = get_wpsb_sidebar_options();
        if (is_category()) {
            $category_id = get_query_var('cat');
            $shareurl = get_category_link($category_id);
            $cats = get_the_category();
            $ShareTitle = $cats[0]->name;
        } elseif (is_page() || is_single()) {
            $shareurl = get_permalink($post->ID);
            $ShareTitle = $post->post_title;
        } else {
            $shareurl = home_url('/');
            $ShareTitle = get_bloginfo('name');
        }

// Top Margin
        if ($pluginOptionsVal['wpsb_top_margin'] != '') {
            $margin = $pluginOptionsVal['wpsb_top_margin'];
        } else {
            $margin = '25%';
        }

//Sidebar Position
        if ($pluginOptionsVal['wpsb_position'] == 'right') {
            $style = ' style="top:' . $margin . ';right:-5px;"';
            $idName = ' id="wpsb-right"';
            $showImg = 'hide-r.png';
            $hideImg = 'show.png';

        } else {
            $idName = ' id="wpsb-left"';
            $style = ' style="top:' . $margin . ';left:0;"';
            $showImg = 'hide-l.png';
            $hideImg = 'hide.png';
        }
        $wpSocialShareContent = '';

        $wpSocialShareContent .= '<div id="delaydiv">';
        $wpSocialShareContent .= '<div class="social-widget" ' . $idName . ' title="Share This Site With Your Friends" ' . $style . ' >';
        $wpSocialShareContent .= '<div class="show"><a href="javascript:" title="Show Buttons" id="show"><img src="' . plugins_url('wp-social-buttons/images/' . $showImg) . '" alt="Show Buttons"></a></div>';
        $wpSocialShareContent .= '<div id="social-inner">';
        if ($pluginOptionsVal['wpsb_fpublishBtn'] != ''):
            $wpSocialShareContent .= '<div class="sbutton first"><div id="fb"><div class="fb-like" data-href="' . $shareurl . '" data-layout="box_count" data-action="like" data-show-faces="true" data-share="false"></div></div></div>';
        endif;

        if ($pluginOptionsVal['wpsb_tpublishBtn'] != ''):
            $wpSocialShareContent .= '<div class="sbutton"><div id="tw"><a href="https://twitter.com/share" class="twitter-share-button"  data-count="vertical" data-url="' . $shareurl . '">Tweet</a></div></div>';
        endif;

        if ($pluginOptionsVal['wpsb_gpublishBtn'] != ''):
            $wpSocialShareContent .= '<div class="sbutton"><div id="gp"><div class="g-plusone" data-size="tall" data-href="' . $shareurl . '"></div></div></div>';
        endif;

        if ($pluginOptionsVal['wpsb_lpublishBtn'] != ''):
            $wpSocialShareContent .= '<div class="sbutton"><div id="li"><script src="//platform.linkedin.com/in.js" type="text/javascript">lang: en_US</script><script type="IN/Share" data-url="' . $shareurl . '" data-counter="top"></script></div></div>';
        endif;

        if ($pluginOptionsVal['wpsb_ppublishBtn'] != ''):
            $wpSocialShareContent .= '<div class="sbutton"><div id="pin"><a href="//www.pinterest.com/pin/create/button/?url=' . $shareurl . '&media=http://farm8.staticflickr.com/7027/6851755809_df5b2051c9_z.jpg&description=' . $ShareTitle . '" data-pin-do="buttonPin" data-pin-config="above" data-pin-color="red" data-pin-height="28"><img src="//assets.pinterest.com/images/pidgets/pinit_fg_en_rect_red_28.png" /></a></div></div>';
        endif;
        $wpSocialShareContent .= '</div>';
        $wpSocialShareContent .= '<div class="hide"><a href="javascript:" title="Hide WP Social Buttons" id="hide"><img src="' . plugins_url('wp-social-buttons/images/' . $hideImg) . '" alt="Hide Buttons"></a></div></div></div>';

        print $wpSocialShareContent;
    }
endif;

?>
