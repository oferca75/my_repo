<?php
/**
 * Custom template tags for this theme.
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package cosimo
 */

if (!function_exists('the_posts_navigation')) :
    /**
     * Display navigation to next/previous set of posts when applicable.
     *
     * @todo Remove this function when WordPress 4.3 is released.
     */
    function the_posts_navigation()
    {
        // Don't print empty markup if there's only one page.
        if ($GLOBALS['wp_query']->max_num_pages < 2) {
            return;
        }
        ?>
        <nav class="navigation posts-navigation" role="navigation">
            <h2 class="screen-reader-text"><?php esc_html_e('Posts navigation', 'cosimo'); ?></h2>
            <div class="nav-links">

                <?php if (get_next_posts_link()) : ?>
                    <div class="nav-previous"><?php next_posts_link(esc_html__('Older posts', 'cosimo')); ?></div>
                <?php endif; ?>

                <?php if (get_previous_posts_link()) : ?>
                    <div class="nav-next"><?php previous_posts_link(esc_html__('Newer posts', 'cosimo')); ?></div>
                <?php endif; ?>

            </div><!-- .nav-links -->
        </nav><!-- .navigation -->
        <?php
    }
endif;

if (!function_exists('the_post_navigation')) :
    /**
     * Display navigation to next/previous post when applicable.
     *
     * @todo Remove this function when WordPress 4.3 is released.
     */
    function the_post_navigation()
    {
        // Don't print empty markup if there's nowhere to navigate.
        $previous = (is_attachment()) ? get_post(get_post()->post_parent) : get_adjacent_post(false, '', true);
        $next = get_adjacent_post(false, '', false);

        if (!$next && !$previous) {
            return;
        }
        ?>
        <nav class="navigation post-navigation" role="navigation">
            <h2 class="screen-reader-text"><?php esc_html_e('Post navigation', 'cosimo'); ?></h2>
            <div class="nav-links">
                <?php
                previous_post_link('<div class="nav-previous">%link</div>', '<div class="theNavigationArrow"><i class="fa prevNext fa-2x fa-angle-left"></i></div><div class="meta-nav" aria-hidden="true"><span class="smallPart">' . esc_html__('Previous Post', 'cosimo') . '</span> ' . '<span class="screen-reader-text">' . esc_html__('Previous post:', 'cosimo') . '</span> ' . '<div class="nextPrevName">%title</div></div>');
                next_post_link('<div class="nav-next">%link</div>', '<div class="meta-nav" aria-hidden="true"><span class="smallPart">' . esc_html__('Next Post', 'cosimo') . '</span><div class="nextPrevName">%title</div></div><div class="theNavigationArrow"><i class="fa prevNext fa-2x fa-angle-right"></i></div> ' . '<span class="screen-reader-text">' . esc_html__('Next Post:', 'cosimo') . '</span> ');
                ?>
            </div><!-- .nav-links -->
        </nav><!-- .navigation -->
        <?php
    }
endif;

if (!function_exists('cosimo_posted_on')) :
    /**
     * Prints HTML with meta information for the current post-date/time and author.
     */
    function cosimo_posted_on()
    {
        $time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
        if (get_the_time('U') !== get_the_modified_time('U')) {
            $time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>';
        }

        $time_string = sprintf($time_string,
            esc_attr(get_the_date('c')),
            esc_html(get_the_date()),
            esc_attr(get_the_modified_date('c')),
            esc_html(get_the_modified_date())
        );

        $posted_on = sprintf(
            _x('<i class="fa fa-clock-o spaceRight"></i>%s', 'post date', 'cosimo'),
            '<a href="' . esc_url(get_permalink()) . '" rel="bookmark">' . $time_string . '</a>'
        );

        $byline = sprintf(
            _x('<i class="fa fa-user spaceLeftRight"></i>%s', 'post author', 'cosimo'),
            '<span class="author vcard"><a class="url fn n" href="' . esc_url(get_author_posts_url(get_the_author_meta('ID'))) . '">' . esc_html(get_the_author()) . '</a></span>'
        );

        echo '<span class="posted-on">' . $posted_on . '</span><span class="byline"> ' . $byline . '</span>';

        if (!post_password_required() && (comments_open() || get_comments_number())) {
            echo ' <span class="comments-link"><i class="fa fa-comments-o spaceLeftRight"></i>';
            comments_popup_link(esc_html__('Leave a comment', 'cosimo'), esc_html__('1 Comment', 'cosimo'), esc_html__('% Comments', 'cosimo'));
            echo '</span>';
        }

    }
endif;

if (!function_exists('cosimo_entry_footer')) :
    /**
     * Prints HTML with meta information for tags and edit link.
     */
    function cosimo_entry_footer()
    {
        if ('post' == get_post_type()) {
            $tags_list = get_the_tag_list('', ', ');
            if ($tags_list) {
                printf('<span class="tags-links"><i class="fa fa-tags spaceRight"></i>' . esc_html__('%1$s', 'cosimo') . '</span>', $tags_list);
            }
        }

        edit_post_link(esc_html__('Edit', 'cosimo'), '<span class="edit-link floatLeft"><i class="fa fa-wrench spaceLeftRight"></i>', '</span>');
    }
endif;

if (!function_exists('cosimo_entry_categories')) :
    /**
     * Prints HTML with meta information for the categories.
     */
    function cosimo_entry_categories()
    {
        $categories_list = get_the_category_list(' / ');
        if ($categories_list && cosimo_categorized_blog()) {
            printf('<span class="cat-links">%1$s</span>', $categories_list);
        }
    }
endif;

if (!function_exists('the_archive_title')) :
    /**
     * Shim for `the_archive_title()`.
     *
     * Display the archive title based on the queried object.
     *
     * @todo Remove this function when WordPress 4.3 is released.
     *
     * @param string $before Optional. Content to prepend to the title. Default empty.
     * @param string $after Optional. Content to append to the title. Default empty.
     */
    function the_archive_title($before = '', $after = '')
    {
        if (is_category()) {
            $title = sprintf(esc_html__('Category: %s', 'cosimo'), single_cat_title('', false));
        } elseif (is_tag()) {
            $title = sprintf(esc_html__('Tag: %s', 'cosimo'), single_tag_title('', false));
        } elseif (is_author()) {
            $title = sprintf(esc_html__('Author: %s', 'cosimo'), '<span class="vcard">' . get_the_author() . '</span>');
        } elseif (is_year()) {
            $title = sprintf(esc_html__('Year: %s', 'cosimo'), get_the_date(esc_html_x('Y', 'yearly archives date format', 'cosimo')));
        } elseif (is_month()) {
            $title = sprintf(esc_html__('Month: %s', 'cosimo'), get_the_date(esc_html_x('F Y', 'monthly archives date format', 'cosimo')));
        } elseif (is_day()) {
            $title = sprintf(esc_html__('Day: %s', 'cosimo'), get_the_date(esc_html_x('F j, Y', 'daily archives date format', 'cosimo')));
        } elseif (is_tax('post_format')) {
            if (is_tax('post_format', 'post-format-aside')) {
                $title = esc_html_x('Asides', 'post format archive title', 'cosimo');
            } elseif (is_tax('post_format', 'post-format-gallery')) {
                $title = esc_html_x('Galleries', 'post format archive title', 'cosimo');
            } elseif (is_tax('post_format', 'post-format-image')) {
                $title = esc_html_x('Images', 'post format archive title', 'cosimo');
            } elseif (is_tax('post_format', 'post-format-video')) {
                $title = esc_html_x('Videos', 'post format archive title', 'cosimo');
            } elseif (is_tax('post_format', 'post-format-quote')) {
                $title = esc_html_x('Quotes', 'post format archive title', 'cosimo');
            } elseif (is_tax('post_format', 'post-format-link')) {
                $title = esc_html_x('Links', 'post format archive title', 'cosimo');
            } elseif (is_tax('post_format', 'post-format-status')) {
                $title = esc_html_x('Statuses', 'post format archive title', 'cosimo');
            } elseif (is_tax('post_format', 'post-format-audio')) {
                $title = esc_html_x('Audio', 'post format archive title', 'cosimo');
            } elseif (is_tax('post_format', 'post-format-chat')) {
                $title = esc_html_x('Chats', 'post format archive title', 'cosimo');
            }
        } elseif (is_post_type_archive()) {
            $title = sprintf(esc_html__('Archives: %s', 'cosimo'), post_type_archive_title('', false));
        } elseif (is_tax()) {
            $tax = get_taxonomy(get_queried_object()->taxonomy);
            /* translators: 1: Taxonomy singular name, 2: Current taxonomy term */
            $title = sprintf(esc_html__('%1$s: %2$s', 'cosimo'), $tax->labels->singular_name, single_term_title('', false));
        } else {
            $title = esc_html__('Archives', 'cosimo');
        }

        /**
         * Filter the archive title.
         *
         * @param string $title Archive title to be displayed.
         */
        $title = apply_filters('get_the_archive_title', $title);

        if (!empty($title)) {
            echo $before . $title . $after;
        }
    }
endif;

if (!function_exists('the_archive_description')) :
    /**
     * Shim for `the_archive_description()`.
     *
     * Display category, tag, or term description.
     *
     * @todo Remove this function when WordPress 4.3 is released.
     *
     * @param string $before Optional. Content to prepend to the description. Default empty.
     * @param string $after Optional. Content to append to the description. Default empty.
     */
    function the_archive_description($before = '', $after = '')
    {
        $description = apply_filters('get_the_archive_description', term_description());

        if (!empty($description)) {
            /**
             * Filter the archive description.
             *
             * @see term_description()
             *
             * @param string $description Archive description to be displayed.
             */
            echo $before . $description . $after;
        }
    }
endif;

/**
 * Returns true if a blog has more than 1 category.
 *
 * @return bool
 */
function cosimo_categorized_blog()
{
    if (false === ($all_the_cool_cats = get_transient('cosimo_categories'))) {
        // Create an array of all the categories that are attached to posts.
        $all_the_cool_cats = get_categories(array(
            'fields' => 'ids',
            'hide_empty' => 1,

            // We only need to know if there is more than one category.
            'number' => 2,
        ));

        // Count the number of categories that are attached to the posts.
        $all_the_cool_cats = count($all_the_cool_cats);

        set_transient('cosimo_categories', $all_the_cool_cats);
    }

    if ($all_the_cool_cats > 1) {
        // This blog has more than 1 category so cosimo_categorized_blog should return true.
        return true;
    } else {
        // This blog has only 1 category so cosimo_categorized_blog should return false.
        return false;
    }
}

/**
 * Flush out the transients used in cosimo_categorized_blog.
 */
function cosimo_category_transient_flusher()
{
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    // Like, beat it. Dig?
    delete_transient('cosimo_categories');
}

add_action('edit_category', 'cosimo_category_transient_flusher');
add_action('save_post', 'cosimo_category_transient_flusher');
