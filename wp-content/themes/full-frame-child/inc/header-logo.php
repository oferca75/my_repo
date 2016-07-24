<?php

/**
 * The template for displaying the Breadcrumb
 *
 * @package Catch Themes
 * @subpackage Full Frame
 * @since Full Frame 1.0
 */


/**
 * Add breadcrumb.
 *
 * @action fullframe_after_header
 *
 * @since Fullframe 1.0
 */
if (!function_exists('header_logo')) :

    function header_logo()
    {
        ?>

        <div class="bjj-header wrapper">
            <a href="/" style="float:left;margin-right:10%;margin-left: 10%;">
                <div class="img-container">
                    <img width="250" alt="Brazilian Jiu Jitsu Techniques" src="<?php echo get_stylesheet_directory_uri() ?>/img/logo4.jpe"/>
                </div>
            </a>

          
          <script type="text/javascript" src='http://adn.ebay.com/files/js/min/ebay_activeContent-min.js'></script>
          <div class="desktop-only-ad">
            <script charset="utf-8" type="text/javascript">
            document.write('\x3Cscript type="text/javascript" charset="utf-8" src="http://adn.ebay.com/cb?programId=1&campId=5337925220&toolId=10026&customId=22222&keyword=%28bjj%2Cgi%2Ckimono%29&width=728&height=90&font=1&textColor=333366&linkColor=333333&arrowColor=FFAF5E&color1=63769A&color2=[COLORTWO]&format=ImageLink&contentType=TEXT_AND_IMAGE&enableSearch=y&usePopularSearches=n&freeShipping=n&topRatedSeller=y&itemsWithPayPal=n&descriptionSearch=y&showKwCatLink=n&excludeCatId=&excludeKeyword=&catId=&disWithin=200&ctx=n&autoscroll=n&flashEnabled=' + isFlashEnabled + '&pageTitle=' + _epn__pageTitle + '&cachebuster=' + (Math.floor(Math.random() * 10000000 )) + '">\x3C/script>' );
            </script>
          </div>
<!--           
          <div class="mobile-only-ad" style="margin-left: 10%;margin-top: 10px !important;"> 
              <script charset="utf-8" type="text/javascript">
              document.write('\x3Cscript type="text/javascript" charset="utf-8" src="http://adn.ebay.com/cb?programId=1&campId=5337925220&toolId=10026&customId=2222&keyword=%28gi%2Ckimono%29&width=250&height=150&font=1&textColor=000000&linkColor=0000AA&arrowColor=8BBC01&color1=709AEE&color2=[COLORTWO]&format=ImageLink&contentType=TEXT_AND_IMAGE&enableSearch=y&usePopularSearches=n&freeShipping=n&topRatedSeller=n&itemsWithPayPal=n&descriptionSearch=n&showKwCatLink=n&excludeCatId=&excludeKeyword=&catId=179774&disWithin=200&ctx=n&autoscroll=n&flashEnabled=' + isFlashEnabled + '&pageTitle=' + _epn__pageTitle + '&cachebuster=' + (Math.floor(Math.random() * 10000000 )) + '">\x3C/script>' );
              </script>


          </div> -->


        </div>

        <?php

    }

endif;
add_action('fullframe_before_content', 'header_logo', 30);
