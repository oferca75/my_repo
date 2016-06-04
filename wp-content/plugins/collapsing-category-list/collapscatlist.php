<?php
/*
Plugin Name: Collapsing category list
Plugin URI: http://www.interadictos.es/category/proyectos-personales-profesionales/
Description: Filter for collapsing the categories list
Version: 0.3.1
Author: José Miguel Gil Córdoba
Author URI: http://josemiguel.nom.es
License: GPLv2 or later
*/
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

define( 'PLUGIN_NAME', 'collapsing-category-list' );

$theme_name = wp_get_theme();
$img_path = plugin_dir_url( __FILE__ ) .'images/';

if ($theme_name->get ( 'Name' ) == 'Twenty Fourteen' || $theme_name->get ( 'Name' ) == 'Twenty Thirteen') {
  $img_collapse_global = $img_path . 'collapse_neg.gif';
  $img_expand_global = $img_path . 'expand_neg.gif';
  define( 'ALT_COLOR_GLOBAL', '-alt');
}
else {
  $img_collapse_global = $img_path . 'collapse.gif';
  $img_expand_global = $img_path . 'expand.gif';
  define( 'ALT_COLOR_GLOBAL', '');
}

/**
 * Class Walker_Category_Modify
 * Modify class from Walker_Category
 */
class Walker_Category_Modify extends Walker_Category{

  /**
   * @see Walker::start_el()
   * @since 2.1.0
   *
   * @param string $output Passed by reference. Used to append additional content.
   * @param object $category Category data object.
   * @param int $depth Depth of category in reference to parents.
   * @param array $args
   */
  function start_el( &$output, $category, $depth = 0, $args = array(), $id = 0 ) {
    global $post, $wp_version;
          
		/** This filter is documented in wp-includes/category-template.php */
		$cat_name = apply_filters(
			'list_cats',
			esc_attr( $category->name ),
			$category
		);

		// Don't generate an element if the category name is empty.
		if ( ! $cat_name ) {
			return;
		}

    extract( $args );
    $remove_link_for_categories_array = explode(',', $remove_link_for_categories);
    $hide_categories_array = explode(',', $hide_categories);
    $not_collapse_array = explode(',', $not_collapse);
    $current_categories = get_cat_ID($cat_name);
    $image_children = '';

    // Remove white space
    foreach ( $remove_link_for_categories_array as &$elem) {
      $elem = trim($elem);
    }
    
    foreach ( $hide_categories_array as &$elem) {
      $elem = trim($elem);
    }
    
    foreach ( $not_collapse_array as &$elem) {
      $elem = trim($elem);
    }

    if (!$has_children && !empty($category->parent)) {
      $has_children = 1;
    }

    if (is_array($current_categories)) {
      if (empty($current_category) && $wp_version < 4.4) {
        $current_category = $current_categories[0];
      }
      else {
        $current_category = $current_categories[0]->term_id;
      }
    }
    else {
      if (empty($current_category)) {
        $current_category = $current_categories;
      }
    }

    $cat_name = esc_attr( $category->name );
    $cat_name = apply_filters( 'list_cats', $cat_name, $category );

    $link = $this->make_link_hide_icon($category, $args, $cat_name, $not_collapse_array, $current_category, $remove_link_for_categories_array);


    if ( !empty($feed_image) || !empty($feed) ) {
      $link .= ' ';

      if ( empty($feed_image) )
        $link .= '(';

      $link .= '<a href="' . esc_url( get_term_feed_link( $category->term_id, $category->taxonomy, $feed_type ) ) . '"';

      if ( empty($feed) ) {
        $alt = ' alt="' . sprintf( __( 'Feed for all posts filed under %s' ), $cat_name ) . '"';
      } else {
        $title = ' title="' . $feed . '"';
        $alt   = ' alt="' . $feed . '"';
        $name  = $feed;
        $link .= $title;
      }

      $link .= '>';

      if ( empty($feed_image) )
        $link .= $name;
      else
        $link .= "<img src='$feed_image'$alt$title" . ' />';

      $link .= '</a>';

      if ( empty($feed_image) )
        $link .= ')';
    }

    if ( !empty($show_count) )
      $link .= ' (' . number_format_i18n( $category->count ) . ')';

    if (!$hide_icons) {
      if ( 1 != $args['has_children'] || !$collaps_categories || false !== array_search($cat_name, $not_collapse_array) ){
        if (!empty($img_collapse) && (bool) !$use_class) {
          $image_children = '<img src="'. plugin_dir_url( __FILE__ ) .'/images/nothing.gif" width="9px" height="9px" />';
        }
        else {
          $image_children = '<i class="collapscatlist-nothing icon-nothing-squared"></i>';
        }
      } 
      else {

        if ( !empty($current_category)) {
          if (is_object($current_category)) {
            $_current_category = $current_category;
          }
          else {
            $_current_category = get_term( $current_category, $category->taxonomy );
          }

          if ( ($category->term_id == $_current_category->term_id ||
                  $category->term_id == $_current_category->parent) && !is_front_page() ) {
            $image_children  = '<a href="#" class="collapse">';
            if (!empty($img_collapse) && (bool) !$use_class) {
              $image_children .= '<img src="'. $img_collapse .'" />';
            }
            else {
              $image_children .= '<i class="icon-minus-squared' . ALT_COLOR_GLOBAL . '"></i>';
            }
            $image_children .= '</a>';
          } else {
            $image_children  = '<a href="#" class="expand">';
            if (!empty($img_expand) && (bool) !$use_class) {
              $image_children .= '<img src="'. $img_expand .'" />';
            }
            else {
              $image_children .= '<i class="icon-plus-squared' . ALT_COLOR_GLOBAL . '"></i>';
            }
            $image_children .= '</a>';
          }
        } else {
          $image_children  = '<a href="#" class="expand">';
          if (!empty($img_expand) && (bool) !$use_class) {
            $image_children .= '<img src="'. $img_expand .'" />';
          }
          else {
            $image_children .= '<i class="icon-plus-squared' . ALT_COLOR_GLOBAL . '"></i>';
          }
          $image_children .= '</a>';
        }
      }
    }

    if ( 'list' == $args['style'] ) {
			$output .= "\t<li";
			$css_classes = array(
				'cat-item',
				'cat-item-' . $category->term_id,
			);
      if ( !empty($current_category) ) {
				// 'current_category' can be an array, so we use `get_terms()`.
				$_current_terms = get_terms( $category->taxonomy, array(
					'include' => $args['current_category'],
					'hide_empty' => false,
				) );
				foreach ( $_current_terms as $_current_term ) {
					if ( $category->term_id == $_current_term->term_id ) {
						$css_classes[] = 'current-cat';
					} elseif ( $category->term_id == $_current_term->parent ) {
						$css_classes[] = 'current-cat-parent';
					}
				}
      }
      
      if ( array_search($cat_name, $hide_categories_array) !== FALSE ) {
        $css_classes[] = 'hide';
      }
      
			/**
			 * Filter the list of CSS classes to include with each category in the list.
			 *
			 * @since 4.2.0
			 *
			 * @see wp_list_categories()
			 *
			 * @param array  $css_classes An array of CSS classes to be applied to each list item.
			 * @param object $category    Category data object.
			 * @param int    $depth       Depth of page, used for padding.
			 * @param array  $args        An array of wp_list_categories() arguments.
			 */
			$css_classes = implode( ' ', apply_filters( 'category_css_class', $css_classes, $category, $depth, $args ) );

      $output .= ' class="' . $css_classes . '"';
      $output .= ">$image_children $link\n";
		} elseif ( isset( $args['separator'] ) ) {
			$output .= "\t$link" . $args['separator'] . "\n";
    } else {
      $output .= "\t$link<br />\n";
    }

  }
  
  private function make_link_hide_icon($category, $args, $cat_name, $not_collapse_array, $current_category, $remove_link_for_categories_array) {
    if (1 != $args['has_children'] || !$args['remove_parent_link'] || $args['hide_icons']) {
      $link = $this->make_link($args, $cat_name, $not_collapse_array, $current_category, $category, $remove_link_for_categories_array, esc_url( get_term_link( $category ) ));
    }
    else {
      $link = $cat_name;
    }
    
    return $link;
  }
  
  private function make_link ($args, $cat_name, $not_collapse_array, $current_category, $category, $remove_link_for_categories_array, $url) {
    $link     = '<a href="';

    if ($args['hide_icons'] && array_search($cat_name, $remove_link_for_categories_array) !== FALSE) {
     $link .= '#" class="collapscatlist_hide_icon ';
    }
    else {
      $link .= $url . '" class="';
    }
    
    if ( 1 != $args['has_children'] || !$args['collaps_categories'] || false !== array_search($cat_name, $not_collapse_array) ){
      $link .= '"';
    }
    else {
      if ( !empty($current_category)) {
        if (is_object($current_category)) {
          $_current_category = $current_category;
        }
        else {
          $_current_category = get_term( $current_category, $category->taxonomy );
        }
        if ( ($category->term_id == $_current_category->term_id ||
                $category->term_id == $_current_category->parent) && !is_front_page() ) {
          $link .= ' collapse" ';
        } else {
          $link .= ' expand" ';
        }
      }
      else {
        $link .= ' expand" ';
      }
    }
    
    if ( $args['use_desc_for_title'] && ! empty( $category->description ) ) {
      /**
       * Filter the category description for display.
       *
       * @since 1.2.0
       *
       * @param string $description Category description.
       * @param object $category    Category object.
       */
       $link .= 'title="' . esc_attr( strip_tags( apply_filters( 'category_description', $category->description, $category ) ) ) . '"';
    }

    $link .= '>';
    $link .= $cat_name . '</a>';
    return $link;
  }
}

/**
 * Class WP_Widget_Collaps_Categories
 * Modify class WP_Widget_Categories
 */
class WP_Widget_Collaps_Categories extends WP_Widget {

	function __construct() {
		$widget_ops = array( 'classname' => 'widget_categories', 'description' => __( "A list or dropdown of categories" ) );
		parent::__construct('categories', __('Categories'), $widget_ops);
	}

	function widget( $args, $instance ) {
    global $img_collapse_global, $img_expand_global;
		extract( $args );

		$title = apply_filters('widget_title', empty( $instance['title'] ) ? __( 'Categories' ) : $instance['title'], $instance, $this->id_base);
		$c = ! empty( $instance['count'] ) ? '1' : '0';
		$h = ! empty( $instance['hierarchical'] ) ? '1' : '0';
		$d = ! empty( $instance['dropdown'] ) ? '1' : '0';
    $cc = ! empty ( $instance['collaps_categories'] ) ? '1' : '0';
    $remove_parent_link = ! empty ( $instance['remove_parent_link'] ) ? '1' : '0';
    $img_collapse = ! empty ( $instance['img_collapse'] ) ? $instance['img_collapse'] : $img_collapse_global;
    $img_expand = ! empty ( $instance['img_expand'] ) ? $instance['img_expand'] : $img_expand_global;
    $use_class = (! empty( $instance['use_class'] ) ) ? '1' : '';
    $remove_link_for_categories = ! empty ( $instance['remove_link_for_categories'] ) ? $instance['remove_link_for_categories'] : '';
    $hide_categories = ! empty ( $instance['hide_categories'] ) ? $instance['hide_categories'] : '';
    $not_collapse = ! empty ( $instance['not_collapse'] ) ? $instance['not_collapse'] : '';
    $hide_icons = ! empty ( $instance['hide_icons'] ) ? '1' : '0';

    if (array_key_exists('order_by', $instance)) {
      switch ($instance['order_by']) {
        case 0:
          $order_by = 'name';
          break;
        case 1:
          $order_by = 'slug';
          break;
        default:
          $order_by = 'name';
          break;
      }
    }
    else {
      $order_by = 'name';
    }

		echo $before_widget;
		if ( $title )
			echo $before_title . $title . $after_title;

		$cat_args = array(
        'orderby' => $order_by, 
        'show_count' => $c, 
        'hierarchical' => $h, 
        'collaps_categories' => $cc,
        'img_collapse' => $img_collapse,
        'img_expand' => $img_expand,
        'use_class' => $use_class,
        'remove_parent_link' => $remove_parent_link,
        'remove_link_for_categories' => $remove_link_for_categories,
        'hide_categories' => $hide_categories,
        'not_collapse' => $not_collapse,
        'hide_icons' => $hide_icons,
    );

		if ( $d ) {
			$cat_args['show_option_none'] = __('Select Category');
			wp_dropdown_categories(apply_filters('widget_categories_dropdown_args', $cat_args));
?>

<script type='text/javascript'>
/* <![CDATA[ */
	var dropdown = document.getElementById("cat");
	function onCatChange() {
		if ( dropdown.options[dropdown.selectedIndex].value > 0 ) {
			location.href = "<?php echo home_url(); ?>/?cat="+dropdown.options[dropdown.selectedIndex].value;
		}
	}
	dropdown.onchange = onCatChange;
/* ]]> */
</script>

<?php
		} else {
?>
		<ul>
<?php
		$cat_args['title_li'] = '';
		wp_list_categories(apply_filters('widget_categories_args', $cat_args));
?>
		</ul>
<?php
		}

		echo $after_widget;
	}

	function update( $new_instance, $old_instance ) {
    global $img_collapse_global, $img_expand_global;
    
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['count'] = !empty($new_instance['count']) ? 1 : 0;
		$instance['hierarchical'] = !empty($new_instance['hierarchical']) ? 1 : 0;
		$instance['dropdown'] = !empty($new_instance['dropdown']) ? 1 : 0;
    $instance['collaps_categories'] = !empty($new_instance['collaps_categories']) ? 1 : 0;
    $instance['img_collapse'] = strip_tags(!empty($new_instance['img_collapse']) ? $new_instance['img_collapse'] : $img_collapse_global);
    $instance['img_expand'] = strip_tags(!empty($new_instance['img_expand']) ? $new_instance['img_expand'] : $img_expand_global);
    $instance['use_class'] = !empty( $new_instance['use_class'] ) ? 1 : 0;
    $instance['remove_parent_link'] = !empty($new_instance['remove_parent_link']) ? 1 : 0;
    $instance['remove_link_for_categories'] = strip_tags(!empty($new_instance['remove_link_for_categories'])) ? $new_instance['remove_link_for_categories'] : '';
    $instance['hide_categories'] = strip_tags(!empty($new_instance['hide_categories'])) ? $new_instance['hide_categories'] : '';
    $instance['order_by'] = !empty($new_instance['order_by']) ? $new_instance['order_by'] : 0;
    $instance['not_collapse'] = !empty($new_instance['not_collapse']) ? $new_instance['not_collapse'] : '';
    $instance['hide_icons'] = !empty($new_instance['hide_icons']) ? 1 : 0;
            
		return $instance;
	}

	function form( $instance ) {
    global $img_collapse_global, $img_expand_global;
    
		//Defaults
		$instance = wp_parse_args( (array) $instance, array( 'title' => '') );
		$title = esc_attr( $instance['title'] );
		$count = isset($instance['count']) ? (bool) $instance['count'] :false;
		$hierarchical = isset( $instance['hierarchical'] ) ? (bool) $instance['hierarchical'] : false;
		$dropdown = isset( $instance['dropdown'] ) ? (bool) $instance['dropdown'] : false;
    $collaps_categories = isset( $instance['collaps_categories'] ) ? (bool) $instance['collaps_categories'] : false;
    $img_collapse = isset( $instance['img_collapse'] ) ? $instance['img_collapse'] : $img_collapse_global;
    $img_expand = isset( $instance['img_expand'] ) ? $instance['img_expand'] : $img_expand_global;
    $use_class = isset( $instance['use_class'] ) ? (bool) $instance['use_class'] : false;
    $remove_parent_link = isset( $instance['remove_parent_link'] ) ? (bool) $instance['remove_parent_link'] : false;
    $remove_link_for_categories = isset( $instance['remove_link_for_categories'] ) ? $instance['remove_link_for_categories'] : '';
    $hide_categories = isset( $instance['hide_categories'] ) ? $instance['hide_categories'] : '';
    $order_by = isset( $instance['order_by'] ) ? $instance['order_by'] : 0;
    $not_collapse = isset( $instance['not_collapse'] ) ? $instance['not_collapse'] : '';
    $hide_icons = isset( $instance['hide_icons']) ? (bool) $instance['hide_icons'] : '';
?>
    <p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e( 'Title:' ); ?></label>
    <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></p>

    <p><input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id('dropdown'); ?>" name="<?php echo $this->get_field_name('dropdown'); ?>"<?php checked( $dropdown ); ?> />
    <label for="<?php echo $this->get_field_id('dropdown'); ?>"><?php _e( 'Display as dropdown' ); ?></label><br />

    <input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id('count'); ?>" name="<?php echo $this->get_field_name('count'); ?>"<?php checked( $count ); ?> />
    <label for="<?php echo $this->get_field_id('count'); ?>"><?php _e( 'Show post counts' ); ?></label><br />

    <input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id('hierarchical'); ?>" name="<?php echo $this->get_field_name('hierarchical'); ?>"<?php checked( $hierarchical ); ?> />
    <label for="<?php echo $this->get_field_id('hierarchical'); ?>"><?php _e( 'Show hierarchy' ); ?></label><br />

    <input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id('collaps_categories'); ?>" name="<?php echo $this->get_field_name('collaps_categories'); ?>"<?php checked( $collaps_categories ); ?> />
    <label for="<?php echo $this->get_field_id('collaps_categories'); ?>"><?php _e( 'Collaps categories' ); ?></label><br />
    
    <input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id('remove_parent_link'); ?>" name="<?php echo $this->get_field_name('remove_parent_link'); ?>"<?php checked( $remove_parent_link ); ?> />
    <label for="<?php echo $this->get_field_id('remove_parent_link'); ?>"><?php _e( 'Remove all links' ); ?></label>
    
    <br />
    <input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id('hide_icons'); ?>" name="<?php echo $this->get_field_name('hide_icons'); ?>"<?php checked( $hide_icons ); ?> />
    <label for="<?php echo $this->get_field_id('hide_icons'); ?>"><?php _e( 'Hide icons to expand / collapse categories. (The link will be used to expand / collapse categories)' ); ?></label>
    
    </p>
    
    <p>
      <label for="<?php echo $this->get_field_id('order_by'); ?>"><?php _e( 'Order by:' ); ?></label>
      <select class="widefat" id="<?php echo $this->get_field_id('order_by'); ?>" name="<?php echo $this->get_field_name('order_by'); ?>">
        <option value="0" <?php if ( $order_by == 0 ): ?>selected<?php endif; ?>><?php _e( 'Name' ); ?></option>
        <option value="1" <?php if ( $order_by == 1 ): ?>selected<?php endif; ?>><?php _e( 'Slug' ); ?></option>
      </select>
    </p>
    
    <p><label for="<?php echo $this->get_field_id('img_collapse'); ?>"><?php _e( 'Image to collapse:' ); ?></label>
    <input class="widefat" id="<?php echo $this->get_field_id('img_collapse'); ?>" name="<?php echo $this->get_field_name('img_collapse'); ?>" type="text" value="<?php echo $img_collapse; ?>" /></p>
    
    <p><label for="<?php echo $this->get_field_id('img_expand'); ?>"><?php _e( 'Image to expand:' ); ?></label>
    <input class="widefat" id="<?php echo $this->get_field_id('img_expand'); ?>" name="<?php echo $this->get_field_name('img_expand'); ?>" type="text" value="<?php echo $img_expand; ?>" /></p>
    <input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id('use_class'); ?>" name="<?php echo $this->get_field_name('use_class'); ?>"<?php checked( $use_class ); ?> />
    <label for="<?php echo $this->get_field_id('use_class'); ?>"><?php _e( 'To display the icons use CSS classes instead of images' ); ?></label>
    
     <p><label for="<?php echo $this->get_field_id('remove_link_for_categories'); ?>"><?php _e( 'Remove link of categories by title (separeted with commas):' ); ?></label>
    <input class="widefat" id="<?php echo $this->get_field_id('remove_link_for_categories'); ?>" name="<?php echo $this->get_field_name('remove_link_for_categories'); ?>" type="text" value="<?php echo $remove_link_for_categories; ?>" /></p>
     
     <p><label for="<?php echo $this->get_field_id('hide_categories'); ?>"><?php _e( 'Hide categories by title (separeted with commas):' ); ?></label>
    <input class="widefat" id="<?php echo $this->get_field_id('hide_categories'); ?>" name="<?php echo $this->get_field_name('hide_categories'); ?>" type="text" value="<?php echo $hide_categories; ?>" /></p>
     
     <p><label for="<?php echo $this->get_field_id('not_collapse'); ?>"><?php _e( 'Not collapse the following categories by title (separated with commas):' ); ?></label>
    <input class="widefat" id="<?php echo $this->get_field_id('not_collapse'); ?>" name="<?php echo $this->get_field_name('not_collapse'); ?>" type="text" value="<?php echo $not_collapse; ?>" /></p>
<?php
	}
}

// Make a filter what modify the categories list
function my_filter_widget_categories( $args ) {
   $walker = new Walker_Category_Modify();
   $args   = array_merge( $args, array( 'walker' => $walker ) );
   return $args;
}
add_filter( 'widget_categories_args', 'my_filter_widget_categories' );

// Add the javascript file.
function my_init() {
  if ( !is_admin() ) {
    wp_enqueue_script( 'the_js', plugins_url( '/js/dropdown.js',__FILE__ ), array( 'jquery' ) );
    wp_enqueue_style( 'collapscatlist_css', plugins_url( 'collapsing-category-list/' ).'collapsing-category-list.css' );
  }
}
add_action( 'init', 'my_init' );

// Register the categories widget
function register_categories_widget() {
  unregister_widget('WP_Widget_Categories');
  register_widget('WP_Widget_Collaps_Categories');
}
add_action( 'widgets_init', 'register_categories_widget');

?>
