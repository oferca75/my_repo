<?php

class st_Category extends WP_Widget
{

    function st_Category()
    {
        parent::WP_Widget($id = 'st_Category', $name = 'ST Category Widget', $options = array('description' => 'A list or dropdown of categories.'));
    }

    function update($new_instance, $old_instance)
    {
        $instance = $old_instance;
        $instance['st_cat_title'] = strip_tags($new_instance['st_cat_title']);
        $instance['st_cat_categories'] = $new_instance['st_cat_categories'];
        $instance['st_cat_templates'] = $new_instance['st_cat_templates'];
        $instance['st_cat_limit'] = $new_instance['st_cat_limit'];
        $instance['st_cat_dropdown'] = !empty($new_instance['st_cat_dropdown']) ? 1 : 0;
        $instance['st_cat_count'] = !empty($new_instance['st_cat_count']) ? 1 : 0;
        $instance['st_cat_hideempty'] = !empty($new_instance['st_cat_hideempty']) ? 1 : 0;
        $instance['st_cat_hierarchy'] = !empty($new_instance['st_cat_hierarchy']) ? 1 : 0;

        return $instance;
    } // END UPDATE

    function form($instance)
    {
        $instance = wp_parse_args((array)$instance, array('st_cat_title' => ''));
        $instance['st_cat_title'] = esc_attr($instance['st_cat_title']);
        $instance['st_cat_categories'] = ((isset($instance['st_cat_categories'])) ? $instance['st_cat_categories'] : '');
        $instance['st_cat_templates'] = ((isset($instance['st_cat_templates'])) ? $instance['st_cat_templates'] : '');
        $instance['st_cat_limit'] = ((isset($instance['st_cat_limit'])) ? $instance['st_cat_limit'] : '');
        $instance['st_cat_dropdown'] = ((isset($instance['st_cat_dropdown'])) ? (bool)$instance['st_cat_dropdown'] : false);
        $instance['st_cat_count'] = ((isset($instance['st_cat_count'])) ? (bool)$instance['st_cat_count'] : false);
        $instance['st_cat_hideempty'] = ((isset($instance['st_cat_hideempty'])) ? (bool)$instance['st_cat_hideempty'] : false);
        $instance['st_cat_hierarchy'] = ((isset($instance['st_cat_hierarchy'])) ? (bool)$instance['st_cat_hierarchy'] : false);
        ?>
        <div>
            <p>
                <label for="<?php echo $this->get_field_id('st_cat_title'); ?>"><?php _e('Title:'); ?></label>
                <input class="widefat" type="text" name="<?php echo $this->get_field_name('st_cat_title') ?>"
                       value="<?php echo esc_attr($instance['st_cat_title']); ?>"/>
            </p>
            <p>
                <label
                    for="<?php echo $this->get_field_id('st_cat_categories'); ?>"><?php _e('Choose category :'); ?></label>
                <select id="<?php echo $this->get_field_id('st_cat_categories'); ?>" class="widefat"
                        name="<?php echo $this->get_field_name('st_cat_categories'); ?>">
                    <?php echo implode('', $this->get_cat($instance['st_cat_categories'])); ?>
                </select>
            </p>
            <p>
                <label
                    for="<?php echo $this->get_field_id('st_cat_templates'); ?>"><?php _e('Choose Template :'); ?></label>
                <select id="<?php echo $this->get_field_id('st_cat_templates'); ?>" class="widefat"
                        name="<?php echo $this->get_field_name('st_cat_templates'); ?>">
                    <?php echo implode('', $this->get_templates($instance['st_cat_templates'])); ?>
                </select>
            </p>
            <p>
                <?php echo '<a href="' . site_url() . '/wp-admin/admin.php?page=st_categories_wp">Custom Css</a>'; ?>
            </p>
            <p>
                <label for="<?php echo $this->get_field_id('st_cat_limit'); ?>"><?php _e('Limit:'); ?></label>
                <input class="widefat" size="5" type="text"
                       name="<?php echo $this->get_field_name('st_cat_limit') ?>"
                       value="<?php echo esc_attr($instance['st_cat_limit']); ?>"/>
            </p>
            <p>
                <input type="checkbox" class="checkbox"
                       name="<?php echo $this->get_field_name('st_cat_dropdown') ?>"<?php checked($instance['st_cat_dropdown']); ?> />
                <label
                    for="<?php echo $this->get_field_id('st_cat_dropdown'); ?>"><?php _e('Display as dropdown'); ?></label>
            </p>
            <p>
                <input type="checkbox" class="checkbox"
                       name="<?php echo $this->get_field_name('st_cat_count') ?>"<?php checked($instance['st_cat_count']); ?> />
                <label for="<?php echo $this->get_field_id('st_cat_count'); ?>"><?php _e('Show post counts'); ?></label>
            </p>
            <p>
                <input type="checkbox" class="checkbox"
                       name="<?php echo $this->get_field_name('st_cat_hideempty') ?>"<?php checked($instance['st_cat_hideempty']); ?> />
                <label
                    for="<?php echo $this->get_field_id('st_cat_hideempty'); ?>"><?php _e('Hide Empty Categories'); ?></label>
            </p>
            <p>
                <input type="checkbox" class="checkbox"
                       name="<?php echo $this->get_field_name('st_cat_hierarchy') ?>"<?php checked($instance['st_cat_hierarchy']); ?> />
                <label
                    for="<?php echo $this->get_field_id('st_cat_hierarchy'); ?>"><?php _e('Hide Hierarchy'); ?></label>
            </p>
        </div>
        <?php
    }

    function get_cat($category)
    {
        $categories = get_categories(array('hide_empty' => 0));

        $show_cats = array();
        $show_cats[] = '<option value="0">Select one...</option>';
        foreach ($categories as $cat) :
            $selected = $category === $cat->cat_ID ? ' selected="selected"' : '';
            $show_cats[] = '<option value="' . $cat->cat_ID . '"' . $selected . '>' . $cat->name . '</option>';
        endforeach;

        return $show_cats;
    } // END WIDGET

    // Get cat

    function get_templates($val)
    {
        $temps = array(
            'style1' => "templates 01",
            'style2' => "templates 02",
            'default' => "Default"
        );
        $show_temps = array();
        $show_temps[] = '<option value="style2">Select one...</option>';
        foreach ($temps as $key => $values) :
            $selected = $val === $key ? ' selected="selected"' : '';
            $show_temps[] = '<option value="' . $key . '"' . $selected . '>' . $values . '</option>';
        endforeach;
        return $show_temps;
    }

    function widget($args, $instance)
    {
        global $st_categories;
        extract($args, EXTR_SKIP);

        $title = empty($instance['st_cat_title']) ? __('categories') : apply_filters('widget_title', $instance['st_cat_title']);
        $categories = $instance['st_cat_categories'];
        $limit = $instance['st_cat_limit'];
        $dropdown = !empty($instance['st_cat_dropdown']) ? 1 : 0;
        $count = !empty($instance['st_cat_count']) ? 1 : 0;
        $classSuffix = (($dropdown == 1) ? "st-dropdown-categories" : "st-categories");
        $template = $instance['st_cat_templates'];
        $hide_empty = !empty($instance['st_cat_hideempty']) ? 1 : 0;
        $hierarchy = !empty($instance['st_cat_hierarchy']) ? 1 : 0;

        echo $before_widget . "<div id='" . $classSuffix . "' class='" . $template . "'><div class='main'>";
        echo "<div class='head'>";
        if ($categories && $categories != 0) :
            $get_parent = get_category($categories);
            if ($get_parent) :
                echo $before_title . $get_parent->name . $after_title;
            endif;
        else :
            echo $before_title . $title . $after_title;
        endif;
        echo "</div>";
        // Test List categories
        if ($dropdown == 0) :
            echo "<div class='content'><ul class='st_cat_list_dropdown'>";
            $args = array(
                'orderby' => 'name',
                'show_count' => $count,
                'hide_empty' => $hide_empty,
                'title_li' => '',
                'depth' => $hierarchy,
                'number' => $limit,
                'child_of' => $categories,
                'walker' => new Custom_Walker_Category(),
            );
            wp_list_categories($args);

            echo "</ul></div>";
        else :
            echo "<div class='content'>";
            $args = array(
                'orderby' => 'name',
                'show_count' => $count,
                'hide_empty' => $hide_empty,
                'title_li' => '',
                'depth' => $hierarchy,
                'number' => $limit,
                'child_of' => $categories
            );
            wp_dropdown_categories($args);
            echo "</div>";
        endif; // DISPLAY
        echo "</div></div>";
        if ($dropdown == 0) :
            if ($template == 'style1') :
                echo $st_categories->list_script();
            else :
                echo "";
            endif;
        else :
            echo $st_categories->dropdown_script();
        endif;
        echo $after_widget;
    }
} // END CLASS
add_action('widgets_init', create_function('', 'return register_widget("st_Category");'));