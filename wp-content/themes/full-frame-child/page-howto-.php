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
 




  <div style="text-align:center"> 

    <div class="start-with full-hierarchy">
      <h3>
        Full Hierarchy
      </h3>
      <?php  dynamic_sidebar('front-sidebar'); ?>

    </div>
    <div class="start-with ebay-scraper" >
       <h3>
          On eBay
      </h3>
        <script type="text/javascript" src='http://adn.ebay.com/files/js/min/ebay_activeContent-min.js'></script>
        <script charset="utf-8" type="text/javascript">
        document.write('\x3Cscript type="text/javascript" charset="utf-8" src="http://adn.ebay.com/cb?programId=1&campId=5337925220&toolId=10026&customId=22222&keyword=%28bjj%2Cgi%2Ckimono%29&width=160&height=600&font=1&textColor=333366&linkColor=333333&arrowColor=FFAF5E&color1=63769A&color2=[COLORTWO]&format=ImageLink&contentType=TEXT_AND_IMAGE&enableSearch=y&usePopularSearches=n&freeShipping=n&topRatedSeller=y&itemsWithPayPal=n&descriptionSearch=y&showKwCatLink=n&excludeCatId=&excludeKeyword=&catId=179774&disWithin=200&ctx=n&autoscroll=n&flashEnabled=' + isFlashEnabled + '&pageTitle=' + _epn__pageTitle + '&cachebuster=' + (Math.floor(Math.random() * 10000000 )) + '">\x3C/script>' );
        </script>
    </div>
    <div class="start-with" style="width:63%; padding-right:2%;">
       <h3>
        Categories
      </h3>
          <?php echo do_shortcode("[pt_view id=8f9de405bc]"); ?>

    </div>
    
    
  </div>
    <div class="start-with">

       <h3>
        Latest Videos
      </h3>
  <?php echo do_shortcode("[pt_view id=dbb00e0828]"); ?>
</div>
  <?php get_footer(); ?>