<?php
/*
Plugin Name: DD Last Viewed
Version: 2.7
Plugin URI: http://dijkstradesign.com
Description: Shows the users recently viewed/visited posts,pages and or custom posttypes in a widget.
Author: Wouter Dijkstra
Author URI: http://dijkstradesign.com
*/


/*  Copyright 2014  WOUTER DIJKSTRA  (email : info@dijkstradesign.nl)

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

require_once('inc/widget.php');
require_once('inc/shortcode.php');


function my_preview_js() {
    wp_enqueue_script( 'dd_js_admin-lastviewed', plugins_url( '/js/admin-lastviewed.js', __FILE__ ) , array( 'jquery' ), '' );
}
add_action( 'customize_preview_init', 'my_preview_js' );

function dd_lastviewed_add_front()
{
    wp_register_style( 'dd_lastviewed_css', plugins_url('/css/style.css', __FILE__) );
    wp_enqueue_style( 'dd_lastviewed_css' );

    wp_enqueue_script('jquery');
    wp_enqueue_script( 'dd_js_admin-lastviewed', plugins_url( '/js/admin-lastviewed.js', __FILE__ ) , array( 'jquery' ), '' );

}
add_action( 'wp_enqueue_scripts', 'dd_lastviewed_add_front' );

function dd_lastviewed_admin()
{
    wp_register_style( 'dd_lastviewed_admin_styles', plugins_url('/css/admin-style.css', __FILE__) );
    wp_enqueue_style( 'dd_lastviewed_admin_styles' );

    wp_enqueue_script('jquery');
    wp_enqueue_script( 'dd_js_admin-lastviewed', plugins_url( '/js/admin-lastviewed.js', __FILE__ ) , array( 'jquery' ), '' );

}
add_action( 'admin_init', 'dd_lastviewed_admin' );


function add_lastviewed_id() {

    if (is_singular()) {
        $post_type = get_post_type();
        $lastviewed_widgets =get_option('widget_lastviewed');
        global $post;
        $post_id = $post->ID;


        foreach($lastviewed_widgets as $id => $lastviewed_widget){

            if($id == '_multiwidget'){
                break;
            }
            $types = $lastviewed_widget["selected_posttypes"];
            $exclude_ids = explode(',', $lastviewed_widget["lastviewed_excl_ids"]);
            $exclude_post = in_array($post->ID,$exclude_ids ); //true/false

            if (is_array($types) && in_array($post_type, $types) && !$exclude_post){


                $cookieName = "cookie_data_lastviewed_widget_".$id;
                $cookieVal = $_COOKIE[$cookieName];
                $oldList = explode(',', $cookieVal);
                $newList = isset($cookieVal) ?  array_diff( $oldList, array($post_id) ) : array();
                //if (count($newList) == 0)
                array_push($newList, $post_id);
                $newList = implode(",", $newList);

                setcookie($cookieName, $newList, time() + (60 * 60 * 24 * 30), "/"); // 30 days
            }
        }
    }
}
add_action('get_header', 'add_lastviewed_id');
