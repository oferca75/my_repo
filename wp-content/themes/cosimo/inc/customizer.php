<?php
/**
 * cosimo Theme Customizer
 *
 * @package cosimo
 */

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function cosimo_customize_register( $wp_customize ) {
	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
	$wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';
}
add_action( 'customize_register', 'cosimo_customize_register' );

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function cosimo_customize_preview_js() {
	wp_enqueue_script( 'cosimo_customizer', get_template_directory_uri() . '/js/customizer.js', array( 'customize-preview' ), '20130508', true );
}
add_action( 'customize_preview_init', 'cosimo_customize_preview_js' );

/*
Enqueue Script for top buttons
*/
if ( ! function_exists( 'cosimo_customizer_controls' ) ){
	function cosimo_customizer_controls(){

		wp_register_script( 'cosimo_customizer_top_buttons', get_template_directory_uri() . '/js/theme-customizer-top-buttons.js', array( 'jquery' ), true  );
		wp_enqueue_script( 'cosimo_customizer_top_buttons' );

		wp_localize_script( 'cosimo_customizer_top_buttons', 'customBtns', array(
			'prodemo' => esc_html__( 'Demo PRO version', 'cosimo' ),
            'proget' => esc_html__( 'Get PRO Version', 'cosimo' )
		) );
	}
}//end if function_exists
add_action( 'customize_controls_enqueue_scripts', 'cosimo_customizer_controls' );

 /**
 * Register Custom Settings
 */
function cosimo_custom_settings_register( $wp_customize ) {
	
	/*
	Start Cosimo Colors
	=====================================================
	*/
	
	$colors = array();
	
	$colors[] = array(
	'slug'=>'cosimo_header_title_color', 
	'default' => '#ffffff',
	'label' => __('Site Title and Description color', 'cosimo')
	);
	
	$colors[] = array(
	'slug'=>'cosimo_box_background_color', 
	'default' => '#ffffff',
	'label' => __('Box Background Color', 'cosimo')
	);
	
	$colors[] = array(
	'slug'=>'cosimo_box_text_color', 
	'default' => '#5b5b5b',
	'label' => __('Text Color', 'cosimo')
	);
	
	$colors[] = array(
	'slug'=>'cosimo_box_second_text_color', 
	'default' => '#b9b9b9',
	'label' => __('Secondary Text Color', 'cosimo')
	);
	
	$colors[] = array(
	'slug'=>'cosimo_special_color', 
	'default' => '#eb911c',
	'label' => __('Special Color', 'cosimo')
	);
	
	foreach( $colors as $cosimo_theme_options ) {
		// SETTINGS
		$wp_customize->add_setting( 'cosimo_theme_options[' . $cosimo_theme_options['slug'] . ']', array(
			'default' => $cosimo_theme_options['default'],
			'type' => 'option', 
			'sanitize_callback' => 'sanitize_hex_color',
			'capability' => 'edit_theme_options'
		)
		);
		// CONTROLS
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				$cosimo_theme_options['slug'], 
				array('label' => $cosimo_theme_options['label'], 
				'section' => 'colors',
				'settings' =>'cosimo_theme_options[' . $cosimo_theme_options['slug'] . ']',
				)
			)
		);
	}

	/*
	Start Cosimo Options
	=====================================================
	*/
	$wp_customize->add_section( 'cresta_cosimo_options', array(
	     'title'    => esc_attr__( 'Cosimo Theme Options', 'cosimo' ),
	     'priority' => 50,
	) );
	/*
	Social Icons
	=====================================================
	*/
	$socialmedia = array();
	
	$socialmedia[] = array(
	'slug'=>'facebookurl', 
	'default' => '#',
	'label' => __('Facebook URL', 'cosimo')
	);
	$socialmedia[] = array(
	'slug'=>'twitterurl', 
	'default' => '#',
	'label' => __('Twitter URL', 'cosimo')
	);
	$socialmedia[] = array(
	'slug'=>'googleplusurl', 
	'default' => '#',
	'label' => __('Google Plus URL', 'cosimo')
	);
	$socialmedia[] = array(
	'slug'=>'linkedinurl', 
	'default' => '#',
	'label' => __('Linkedin URL', 'cosimo')
	);
	$socialmedia[] = array(
	'slug'=>'instagramurl', 
	'default' => '#',
	'label' => __('Instagram URL', 'cosimo')
	);
	$socialmedia[] = array(
	'slug'=>'youtubeurl', 
	'default' => '#',
	'label' => __('YouTube URL', 'cosimo')
	);
	$socialmedia[] = array(
	'slug'=>'pinteresturl', 
	'default' => '#',
	'label' => __('Pinterest URL', 'cosimo')
	);
	$socialmedia[] = array(
	'slug'=>'tumblrurl', 
	'default' => '#',
	'label' => __('Tumblr URL', 'cosimo')
	);
	$socialmedia[] = array(
	'slug'=>'vkurl', 
	'default' => '#',
	'label' => __('VK URL', 'cosimo')
	);
	$socialmedia[] = array(
	'slug'=>'bloglovinurl', 
	'default' => '',
	'label' => __('Bloglovin URL', 'cosimo')
	);
	
	foreach( $socialmedia as $cosimo_theme_options ) {
		// SETTINGS
		$wp_customize->add_setting(
			'cosimo_theme_options_' . $cosimo_theme_options['slug'], array(
				'default' => $cosimo_theme_options['default'],
				'capability'     => 'edit_theme_options',
				'sanitize_callback' => 'esc_url_raw',
				'type'     => 'theme_mod',
			)
		);
		// CONTROLS
		$wp_customize->add_control(
			$cosimo_theme_options['slug'], 
			array('label' => $cosimo_theme_options['label'], 
			'section'    => 'cresta_cosimo_options',
			'settings' =>'cosimo_theme_options_' . $cosimo_theme_options['slug'],
			)
		);
	}
	
	/*
	Opacity on header
	=====================================================
	*/
	$wp_customize->add_setting('cosimo_theme_options_headeropacity', array(
        'default'    => '1',
        'type'       => 'theme_mod',
        'capability' => 'edit_theme_options',
		'sanitize_callback' => 'cosimo_sanitize_checkbox'
    ) );
	
	$wp_customize->add_control('cosimo_theme_options_headeropacity', array(
        'label'      => __( 'Show Opacity on Header', 'cosimo' ),
        'section'    => 'cresta_cosimo_options',
        'settings'   => 'cosimo_theme_options_headeropacity',
        'type'       => 'checkbox',
    ) );
	
	/*
	Search Button
	=====================================================
	*/
	$wp_customize->add_setting('cosimo_theme_options_hidesearch', array(
        'default'    => '1',
        'type'       => 'theme_mod',
        'capability' => 'edit_theme_options',
		'sanitize_callback' => 'cosimo_sanitize_checkbox'
    ) );
	
	$wp_customize->add_control('cosimo_theme_options_hidesearch', array(
        'label'      => __( 'Show Search Button in Main Menu', 'cosimo' ),
        'section'    => 'cresta_cosimo_options',
        'settings'   => 'cosimo_theme_options_hidesearch',
        'type'       => 'checkbox',
    ) );
	
	/*
	Masonry Style
	=====================================================
	*/
	$wp_customize->add_setting('cosimo_theme_options_masonrybig', array(
        'default'    => '',
        'type'       => 'theme_mod',
        'capability' => 'edit_theme_options',
		'sanitize_callback' => 'cosimo_sanitize_checkbox'
    ) );
	
	$wp_customize->add_control('cosimo_theme_options_masonrybig', array(
        'label'      => __( 'Last Post Blog Big?', 'cosimo' ),
        'section'    => 'cresta_cosimo_options',
        'settings'   => 'cosimo_theme_options_masonrybig',
        'type'       => 'checkbox',
    ) );
	
	/*
	Enlarge the featured images
	=====================================================
	*/
	$wp_customize->add_setting('cosimo_theme_options_enlargefeatured', array(
        'default'    => '1',
        'type'       => 'theme_mod',
        'capability' => 'edit_theme_options',
		'sanitize_callback' => 'cosimo_sanitize_checkbox'
    ) );
	
	$wp_customize->add_control('cosimo_theme_options_enlargefeatured', array(
        'label'      => __( 'Enlarge Featured Images', 'cosimo' ),
        'section'    => 'cresta_cosimo_options',
        'settings'   => 'cosimo_theme_options_enlargefeatured',
        'type'       => 'checkbox',
    ) );
	
	/*
	Upgrade to PRO
	=====================================================
	*/
    class Cosimo_Customize_Upgrade_Control extends WP_Customize_Control {
        public function render_content() {  ?>
        	<p class="cosimo-upgrade-title">
        		<span class="customize-control-title">
					<h3 style="text-align:center;"><div class="dashicons dashicons-megaphone"></div> <?php _e('Get Cosimo PRO WP Theme for only', 'cosimo'); ?> 34,90&euro;</h3>
        		</span>
        	</p>
			<p style="text-align:center;" class="cosimo-upgrade-button">
				<a style="margin: 10px;" target="_blank" href="http://crestaproject.com/demo/cosimo-pro/" class="button button-secondary">
					<?php _e('Watch the demo', 'cosimo'); ?>
				</a>
				<a style="margin: 10px;" target="_blank" href="http://crestaproject.com/downloads/cosimo/" class="button button-secondary">
					<?php _e('Get Cosimo PRO Theme', 'cosimo'); ?>
				</a>
			</p>
			<ul>
				<li><div class="dashicons dashicons-yes" style="color: #1fa67a;"></div><b><?php _e('Advanced Theme Options', 'cosimo'); ?></b></li>
				<li><div class="dashicons dashicons-yes" style="color: #1fa67a;"></div><b><?php _e('Logo Upload', 'cosimo'); ?></b></li>
				<li><div class="dashicons dashicons-yes" style="color: #1fa67a;"></div><b><?php _e('Font switcher', 'cosimo'); ?></b></li>
				<li><div class="dashicons dashicons-yes" style="color: #1fa67a;"></div><b><?php _e('Loading Page', 'cosimo'); ?></b></li>
				<li><div class="dashicons dashicons-yes" style="color: #1fa67a;"></div><b><?php _e('Unlimited Colors and Skin', 'cosimo'); ?></b></li>
				<li><div class="dashicons dashicons-yes" style="color: #1fa67a;"></div><b><?php _e('Exclusive Portfolio', 'cosimo'); ?></b></li>
				<li><div class="dashicons dashicons-yes" style="color: #1fa67a;"></div><b><?php _e('WooCommerce CSS Style', 'cosimo'); ?></b></li>
				<li><div class="dashicons dashicons-yes" style="color: #1fa67a;"></div><b><?php _e('Post views counter', 'cosimo'); ?></b></li>
				<li><div class="dashicons dashicons-yes" style="color: #1fa67a;"></div><b><?php _e('Posts with Infinite Scroll', 'cosimo'); ?></b></li>
				<li><div class="dashicons dashicons-yes" style="color: #1fa67a;"></div><b><?php _e('Post formats (Audio, Video, Gallery)', 'cosimo'); ?></b></li>
				<li><div class="dashicons dashicons-yes" style="color: #1fa67a;"></div><b><?php _e('8 Shortcodes', 'cosimo'); ?></b></li>
				<li><div class="dashicons dashicons-yes" style="color: #1fa67a;"></div><b><?php _e('11 Exclusive Widgets', 'cosimo'); ?></b></li>
				<li><div class="dashicons dashicons-yes" style="color: #1fa67a;"></div><b><?php _e('Related Posts Box', 'cosimo'); ?></b></li>
				<li><div class="dashicons dashicons-yes" style="color: #1fa67a;"></div><b><?php _e('Information About Author Box', 'cosimo'); ?></b></li>
				<li><div class="dashicons dashicons-yes" style="color: #1fa67a;"></div><b><?php _e('Parallax Header', 'cosimo'); ?></b></li>
				<li><div class="dashicons dashicons-yes" style="color: #1fa67a;"></div><b><?php _e('Header with YouTube video', 'cosimo'); ?></b></li>
				<li><div class="dashicons dashicons-yes" style="color: #1fa67a;"></div><b><?php _e('And much more...', 'cosimo'); ?></b></li>
			<ul><?php
        }
    }
	
	$wp_customize->add_section( 'cresta_upgrade_pro', array(
	     'title'    => __( 'More features? Upgrade to PRO', 'cosimo' ),
	     'priority' => 999,
	));
	
	$wp_customize->add_setting('cosimo_section_upgrade_pro', array(
		'default' => '',
		'type' => 'option',
		'sanitize_callback' => 'esc_attr'
	));
	
	$wp_customize->add_control(new Cosimo_Customize_Upgrade_Control($wp_customize, 'cosimo_section_upgrade_pro', array(
		'section' => 'cresta_upgrade_pro',
		'settings' => 'cosimo_section_upgrade_pro',
	)));
	
}
add_action( 'customize_register', 'cosimo_custom_settings_register' );

function cosimo_sanitize_checkbox( $input ) {
	if ( $input == 1 ) {
		return 1;
	} else {
		return '';
	}
}

/**
 * Add Custom CSS to Header 
 */
function cosimo_custom_css_styles() { 
	global $cosimo_theme_options;
	$se_options = get_option( 'cosimo_theme_options', $cosimo_theme_options );
	if( isset( $se_options[ 'cosimo_header_title_color' ] ) ) {
		$cosimo_header_title_color = $se_options['cosimo_header_title_color'];
	}
	if( isset( $se_options[ 'cosimo_box_background_color' ] ) ) {
		$cosimo_box_background_color = $se_options['cosimo_box_background_color'];
	}
	if( isset( $se_options[ 'cosimo_box_text_color' ] ) ) {
		$cosimo_box_text_color = $se_options['cosimo_box_text_color'];
	}
	if( isset( $se_options[ 'cosimo_box_second_text_color' ] ) ) {
		$cosimo_box_second_text_color = $se_options['cosimo_box_second_text_color'];
	}
	if( isset( $se_options[ 'cosimo_special_color' ] ) ) {
		$cosimo_special_color = $se_options['cosimo_special_color'];
	}
?>
<style type="text/css">
	<?php if (!empty($cosimo_header_title_color) && $cosimo_header_title_color != '#ffffff' ) : ?>
		.site-branding,
		.site-branding a,
		.site-branding a:hover,
		.site-branding a:focus,
		.site-description {
			color: <?php echo esc_attr($cosimo_header_title_color); ?>;
		}
	<?php endif; ?>
	<?php if (!empty($cosimo_box_background_color) && $cosimo_box_background_color != '#ffffff' ) : ?>
		button,
		input[type="button"],
		input[type="reset"],
		input[type="submit"],
		.main-navigation ul li:hover > a, 
		.main-navigation ul li.focus > a, 
		.main-navigation ul li.current-menu-item > a, 
		.main-navigation ul li.current-menu-parent > a, 
		.main-navigation ul li.current-page-ancestor > a,
		.main-navigation .current_page_item > a, 
		.main-navigation .current_page_parent > a,
		.site-main .navigation.pagination .nav-links span.current,
		#wp-calendar > caption,
		.tagcloud a,
		#toTop {
			color: <?php echo esc_attr($cosimo_box_background_color); ?>;
		}
		button:hover,
		input[type="button"]:hover,
		input[type="reset"]:hover,
		input[type="submit"]:hover,
		button:focus,
		input[type="button"]:focus,
		input[type="reset"]:focus,
		input[type="submit"]:focus,
		button:active,
		input[type="button"]:active,
		input[type="reset"]:active,
		input[type="submit"]:active,
		input[type="text"],
		input[type="email"],
		input[type="url"],
		input[type="password"],
		input[type="search"],
		input[type="number"],
		input[type="tel"],
		input[type="range"],
		input[type="date"],
		input[type="month"],
		input[type="week"],
		input[type="time"],
		input[type="datetime"],
		input[type="datetime-local"],
		input[type="color"],
		textarea,
		.main-navigation ul ul a,
		.site-main .navigation.pagination .nav-links a, .site-main .navigation.posts-navigation .nav-links a,
		.tagcloud a:hover,
		.whiteSpace,
		.cosimo-back,
		.openFeatImage,
		.single .content-area,
		.page .content-area,
		body.error404 .page-content,
		body.search-no-results .page-content,
		.widget-area,
		header.page-header,
		#search-full {
			background: <?php echo esc_attr($cosimo_box_background_color); ?>;
		}
		.main-navigation div > ul > li > ul::before,
		.main-navigation div > ul > li > ul::after {
			border-bottom-color: <?php echo esc_attr($cosimo_box_background_color); ?>;
		}
		.entry-featuredImg.cosimo-loader {
			background-color: <?php echo esc_attr($cosimo_box_background_color); ?>;
		}
		@media screen and (max-width: 768px) {
			.menu-toggle,
			.main-navigation.toggled .nav-menu {
				background: <?php echo esc_attr($cosimo_box_background_color); ?>;
			}
		}
	<?php endif; ?>
	<?php if (!empty($cosimo_box_text_color) && $cosimo_box_text_color != '#5b5b5b' ) : ?>
		<?php list($r, $g, $b) = sscanf($cosimo_box_text_color, '#%02x%02x%02x'); ?>
		body,
		button,
		input,
		select,
		textarea,
		input[type="text"]:focus,
		input[type="email"]:focus,
		input[type="url"]:focus,
		input[type="password"]:focus,
		input[type="search"]:focus,
		input[type="number"]:focus,
		input[type="tel"]:focus,
		input[type="range"]:focus,
		input[type="date"]:focus,
		input[type="month"]:focus,
		input[type="week"]:focus,
		input[type="time"]:focus,
		input[type="datetime"]:focus,
		input[type="datetime-local"]:focus,
		input[type="color"]:focus,
		textarea:focus,
		.whiteSpace a, h2.entry-title a,
		a:hover,
		a:focus,
		a:active,
		.main-navigation ul ul a,
		.main-search-box,
		.search-container input[type="search"],
		.closeSearch {
			color: <?php echo esc_attr($cosimo_box_text_color); ?>;
		}
		.main-navigation ul li:hover > a, 
		.main-navigation ul li.focus > a, 
		.main-navigation ul li.current-menu-item > a, 
		.main-navigation ul li.current-menu-parent > a, 
		.main-navigation ul li.current-page-ancestor > a,
		.main-navigation .current_page_item > a, 
		.main-navigation .current_page_parent > a,
		.site-main .navigation.pagination .nav-links span.current,
		.main-sidebar-box span,
		.main-sidebar-box span:before,
		.main-sidebar-box span:after,
		#toTop,
		.nano > .nano-pane > .nano-slider {
			background: <?php echo esc_attr($cosimo_box_text_color); ?>;
		}
		input[type="text"],
		input[type="email"],
		input[type="url"],
		input[type="password"],
		input[type="search"],
		input[type="number"],
		input[type="tel"],
		input[type="range"],
		input[type="date"],
		input[type="month"],
		input[type="week"],
		input[type="time"],
		input[type="datetime"],
		input[type="datetime-local"],
		input[type="color"],
		textarea,
		.content-cosimo:before {
			border: 4px double  rgba(<?php echo esc_attr($r).', '.esc_attr($g).', '.esc_attr($b); ?>, 0.2);
		}
		.post-navigation .nav-next {
			border-left: 4px double  rgba(<?php echo esc_attr($r).', '.esc_attr($g).', '.esc_attr($b); ?>, 0.2);
		}
		.comment-navigation .nav-links a, .cosimo-opacity {
			background: rgba(<?php echo esc_attr($r).', '.esc_attr($g).', '.esc_attr($b); ?>, 0.3);
		}
		#wp-calendar th, .site-main .post-navigation {
			background: rgba(<?php echo esc_attr($r).', '.esc_attr($g).', '.esc_attr($b); ?>, 0.05);
		}
		#wp-calendar tbody td {
			border: 1px solid rgba(<?php echo esc_attr($r).', '.esc_attr($g).', '.esc_attr($b); ?>, 0.05);
		}
		.intSeparator {
			border-top: 4px double rgba(<?php echo esc_attr($r).', '.esc_attr($g).', '.esc_attr($b); ?>, 0.2);
		}
		aside ul li {
			border-bottom: 1px solid rgba(<?php echo esc_attr($r).', '.esc_attr($g).', '.esc_attr($b); ?>, 0.05);
		}
		aside ul li ul.sub-menu, aside ul li ul.children {
			border-left: 1px solid rgba(<?php echo esc_attr($r).', '.esc_attr($g).', '.esc_attr($b); ?>, 0.05);
		}
		h3.widget-title {
			border-bottom: 4px double  rgba(<?php echo esc_attr($r).', '.esc_attr($g).', '.esc_attr($b); ?>, 0.2);
		}
		#comments ol .pingback, #comments ol article {
			border-bottom: 1px solid rgba(<?php echo esc_attr($r).', '.esc_attr($g).', '.esc_attr($b); ?>, 0.2);
		}
		.nano > .nano-pane {
			background: rgba(<?php echo esc_attr($r).', '.esc_attr($g).', '.esc_attr($b); ?>, 0.15);
		}
		.nano > .nano-pane > .nano-slider {
			background: rgba(<?php echo esc_attr($r).', '.esc_attr($g).', '.esc_attr($b); ?>, 0.3);
		}
		@media all and (max-width: 650px) {
			.post-navigation .nav-next {
				border-top: 4px double rgba(<?php echo esc_attr($r).', '.esc_attr($g).', '.esc_attr($b); ?>, 0.2);
			}
		}
		@media screen and (max-width: 768px) {
			.menu-toggle {
				color: <?php echo esc_attr($cosimo_box_text_color); ?>;
			}
		}
	<?php endif; ?>
	<?php if (!empty($cosimo_box_second_text_color) && $cosimo_box_second_text_color != '#b9b9b9' ) : ?>
		.smallPart,
		.tagcloud,
		input[type="text"],
		input[type="email"],
		input[type="url"],
		input[type="password"],
		input[type="search"],
		input[type="number"],
		input[type="tel"],
		input[type="range"],
		input[type="date"],
		input[type="month"],
		input[type="week"],
		input[type="time"],
		input[type="datetime"],
		input[type="datetime-local"],
		input[type="color"],
		textarea {
			color: <?php echo esc_attr($cosimo_box_second_text_color); ?>;
		}			
	<?php endif; ?>
	<?php if (!empty($cosimo_special_color) && $cosimo_special_color != '#eb911c' ) : ?>
		blockquote::before,
		button:hover,
		input[type="button"]:hover,
		input[type="reset"]:hover,
		input[type="submit"]:hover,
		button:focus,
		input[type="button"]:focus,
		input[type="reset"]:focus,
		input[type="submit"]:focus,
		button:active,
		input[type="button"]:active,
		input[type="reset"]:active,
		input[type="submit"]:active,
		a,
		.tagcloud a:hover {
			color: <?php echo esc_attr($cosimo_special_color); ?>;
		}
		button,
		input[type="button"],
		input[type="reset"],
		input[type="submit"],
		#wp-calendar > caption,
		.tagcloud a {
			background: <?php echo esc_attr($cosimo_special_color); ?>;
		}
		blockquote {
			border-left: 4px double <?php echo esc_attr($cosimo_special_color); ?>;
			border-right: 1px solid <?php echo esc_attr($cosimo_special_color); ?>;
		}
		input[type="text"]:focus,
		input[type="email"]:focus,
		input[type="url"]:focus,
		input[type="password"]:focus,
		input[type="search"]:focus,
		input[type="number"]:focus,
		input[type="tel"]:focus,
		input[type="range"]:focus,
		input[type="date"]:focus,
		input[type="month"]:focus,
		input[type="week"]:focus,
		input[type="time"]:focus,
		input[type="datetime"]:focus,
		input[type="datetime-local"]:focus,
		input[type="color"]:focus,
		textarea:focus {
			border: 4px double <?php echo esc_attr($cosimo_special_color); ?>;
		}
		#wp-calendar tbody td#today, .tagcloud a:hover {
			border: 1px solid <?php echo esc_attr($cosimo_special_color); ?>;
		}
		@media screen and (max-width: 768px) {
			.main-navigation.toggled .nav-menu {
				border: 2px solid <?php echo esc_attr($cosimo_special_color); ?>;
			}
			.menu-toggle:hover, .menu-toggle:focus,
			.main-navigation.toggled .menu-toggle,
			.main-navigation ul li .indicator {
				color: <?php echo esc_attr($cosimo_special_color); ?>;
			}
		}
	<?php endif; ?>
</style>
<?php 
}
add_action('wp_head', 'cosimo_custom_css_styles');
?>