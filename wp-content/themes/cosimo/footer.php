<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after
 *
 * @package cosimo
 */
?>

<?php
$facebookURL = get_theme_mod('cosimo_theme_options_facebookurl', '#');
$twitterURL = get_theme_mod('cosimo_theme_options_twitterurl', '#');
$googleplusURL = get_theme_mod('cosimo_theme_options_googleplusurl', '#');
$linkedinURL = get_theme_mod('cosimo_theme_options_linkedinurl', '#');
$instagramURL = get_theme_mod('cosimo_theme_options_instagramurl', '#');
$youtubeURL = get_theme_mod('cosimo_theme_options_youtubeurl', '#');
$pinterestURL = get_theme_mod('cosimo_theme_options_pinteresturl', '#');
$tumblrURL = get_theme_mod('cosimo_theme_options_tumblrurl', '#');
$vkURL = get_theme_mod('cosimo_theme_options_vkurl', '#');
$bloglovinURL = get_theme_mod('cosimo_theme_options_bloglovinurl', '');
?>

<footer id="colophon" class="site-footer" role="contentinfo">
    <div class="site-info smallPart">
        <div class="infoFoo">
            <a href="<?php echo esc_url(__('http://wordpress.org/', 'cosimo')); ?>"><?php printf(esc_html__('Proudly powered by %s', 'cosimo'), 'WordPress'); ?></a>
            <span class="sep"> | </span>
            <?php printf(esc_html__('Theme: %1$s by %2$s.', 'cosimo'), 'Cosimo Free', '<a target="_blank" href="http://crestaproject.com/downloads/cosimo/" rel="designer" title="CrestaProject">CrestaProject</a>'); ?>
        </div>
        <div class="infoFoo right">
            <div class="socialLine">
                <?php if (!empty($facebookURL)) : ?>
                    <a href="<?php echo esc_url($facebookURL); ?>" title="<?php esc_attr_e('Facebook', 'cosimo'); ?>"><i
                            class="fa fa-facebook spaceLeftRight"><span
                                class="screen-reader-text"><?php esc_attr_e('Facebook', 'cosimo'); ?></span></i></a>
                <?php endif; ?>
                <?php if (!empty($twitterURL)) : ?>
                    <a href="<?php echo esc_url($twitterURL); ?>" title="<?php esc_attr_e('Twitter', 'cosimo'); ?>"><i
                            class="fa fa-twitter spaceLeftRight"><span
                                class="screen-reader-text"><?php esc_attr_e('Twitter', 'cosimo'); ?></span></i></a>
                <?php endif; ?>
                <?php if (!empty($googleplusURL)) : ?>
                    <a href="<?php echo esc_url($googleplusURL); ?>"
                       title="<?php esc_attr_e('Google Plus', 'cosimo'); ?>"><i
                            class="fa fa-google-plus spaceLeftRight"><span
                                class="screen-reader-text"><?php esc_attr_e('Google Plus', 'cosimo'); ?></span></i></a>
                <?php endif; ?>
                <?php if (!empty($linkedinURL)) : ?>
                    <a href="<?php echo esc_url($linkedinURL); ?>" title="<?php esc_attr_e('Linkedin', 'cosimo'); ?>"><i
                            class="fa fa-linkedin spaceLeftRight"><span
                                class="screen-reader-text"><?php esc_attr_e('Linkedin', 'cosimo'); ?></span></i></a>
                <?php endif; ?>
                <?php if (!empty($instagramURL)) : ?>
                    <a href="<?php echo esc_url($instagramURL); ?>" title="<?php esc_attr_e('Instagram', 'cosimo'); ?>"><i
                            class="fa fa-instagram spaceLeftRight"><span
                                class="screen-reader-text"><?php esc_attr_e('Instagram', 'cosimo'); ?></span></i></a>
                <?php endif; ?>
                <?php if (!empty($youtubeURL)) : ?>
                    <a href="<?php echo esc_url($youtubeURL); ?>" title="<?php esc_attr_e('YouTube', 'cosimo'); ?>"><i
                            class="fa fa-youtube spaceLeftRight"><span
                                class="screen-reader-text"><?php esc_attr_e('YouTube', 'cosimo'); ?></span></i></a>
                <?php endif; ?>
                <?php if (!empty($pinterestURL)) : ?>
                    <a href="<?php echo esc_url($pinterestURL); ?>" title="<?php esc_attr_e('Pinterest', 'cosimo'); ?>"><i
                            class="fa fa-pinterest spaceLeftRight"><span
                                class="screen-reader-text"><?php esc_attr_e('Pinterest', 'cosimo'); ?></span></i></a>
                <?php endif; ?>
                <?php if (!empty($tumblrURL)) : ?>
                    <a href="<?php echo esc_url($tumblrURL); ?>" title="<?php esc_attr_e('Tumblr', 'cosimo'); ?>"><i
                            class="fa fa-tumblr spaceLeftRight"><span
                                class="screen-reader-text"><?php esc_attr_e('Tumblr', 'cosimo'); ?></span></i></a>
                <?php endif; ?>
                <?php if (!empty($vkURL)) : ?>
                    <a href="<?php echo esc_url($vkURL); ?>" title="<?php esc_attr_e('VK', 'cosimo'); ?>"><i
                            class="fa fa-vk spaceLeftRight"><span
                                class="screen-reader-text"><?php esc_attr_e('VK', 'cosimo'); ?></span></i></a>
                <?php endif; ?>
                <?php if (!empty($bloglovinURL)) : ?>
                    <a href="<?php echo esc_url($bloglovinURL); ?>" title="<?php esc_attr_e('Bloglovin', 'cosimo'); ?>"><i
                            class="fa fa-heart spaceLeftRight"><span
                                class="screen-reader-text"><?php esc_attr_e('Bloglovin', 'cosimo'); ?></span></i></a>
                <?php endif; ?>
            </div>
        </div>
    </div><!-- .site-info -->
</footer><!-- #colophon -->
</div><!-- #content -->
</div><!-- #page -->
<div id="toTop"><i class="fa fa-angle-up fa-lg"></i></div>
<?php wp_footer(); ?>
</body>
</html>
