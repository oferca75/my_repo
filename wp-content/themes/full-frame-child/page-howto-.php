<?php
// Template Name: How 2 Use

/**
 * The template for displaying all pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
 *
 * @package Catch Themes
 * @subpackage Full Frame
 * @since Full Frame 1.0
 */

get_header(); ?>
 
<h1 class="entry-title"><br>
        Latest Videos
  <br>
      </h1>
<?php echo do_shortcode("[pt_view id=dbb00e0828]"); ?>
<?php //get_sidebar(); ?>
<?php get_footer(); ?>
