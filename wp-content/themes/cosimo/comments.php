<?php
/**
 * The template for displaying comments.
 *
 * The area of the page that contains both current comments
 * and the comment form.
 *
 * @package cosimo
 */

/*
 * If the current post is protected by a password and
 * the visitor has not yet entered the password we will
 * return early without loading the comments.
 */
if (post_password_required()) {
    return;
}
?>

<div id="comments" class="comments-area">

    <?php // You can start editing here -- including this comment! ?>

    <?php if (have_comments()) : ?>
        <h2 class="comments-title">
            <?php
            printf(
                esc_html(_nx('One thought on &ldquo;%2$s&rdquo;', '%1$s thoughts on &ldquo;%2$s&rdquo;', get_comments_number(), 'comments title', 'cosimo')),
                number_format_i18n(get_comments_number()),
                '<span>' . get_the_title() . '</span>'
            );
            ?>
        </h2>

        <ol class="comment-list">
            <?php
            wp_list_comments(array(
                'style' => 'ol',
                'short_ping' => true,
                'avatar_size' => '70',
                'reply_text' => '<span>' . esc_html__('Reply', 'cosimo') . '<i class="fa fa-reply spaceLeft"></i></span>',
            ));
            ?>
        </ol><!-- .comment-list -->

        <?php if (get_comment_pages_count() > 1 && get_option('page_comments')) : // are there comments to navigate through ?>
            <nav id="comment-nav-below" class="navigation comment-navigation" role="navigation">
                <h2 class="screen-reader-text"><?php esc_html_e('Comment navigation', 'cosimo'); ?></h2>
                <div class="nav-links">

                    <div
                        class="nav-previous"><?php previous_comments_link('<i class="fa fa-lg fa-angle-double-left spaceRight"></i>' . esc_html__('Older Comments', 'cosimo')); ?></div>
                    <div
                        class="nav-next"><?php next_comments_link(esc_html__('Newer Comments', 'cosimo') . '<i class="fa fa-lg fa-angle-double-right spaceLeft"></i>'); ?></div>

                </div><!-- .nav-links -->
            </nav><!-- #comment-nav-below -->
        <?php endif; // check for comment navigation ?>

    <?php endif; // have_comments() ?>

    <?php
    // If comments are closed and there are comments, let's leave a little note, shall we?
    if (!comments_open() && '0' != get_comments_number() && post_type_supports(get_post_type(), 'comments')) :
        ?>
        <p class="no-comments"><?php esc_html_e('Comments are closed.', 'cosimo'); ?></p>
    <?php endif; ?>

    <?php
    $commenter = wp_get_current_commenter();
    $req = get_option('require_name_email');
    $aria_req = ($req ? " aria-required='true'" : '');

    $fields = array(
        'author' => '<p class="comment-form-author"><label for="author"><span class="screen-reader-text">' . __('Name *', 'cosimo') . '</span></label><input id="author" name="author" type="text" value="' . esc_attr($commenter['comment_author']) . '" ' . $aria_req . ' placeholder="' . __('Name *', 'cosimo') . '"/></p>',
        'email' => '<p class="comment-form-email"><label for="email"><span class="screen-reader-text">' . __('Email *', 'cosimo') . '</span></label><input id="email" name="email" type="text" value="' . esc_attr($commenter['comment_author_email']) . '" ' . $aria_req . ' placeholder="' . __('Email *', 'cosimo') . '"/></p>',
        'url' => '<p class="comment-form-url"><label for="url"><span class="screen-reader-text">' . __('Website *', 'cosimo') . '</span></label><input id="url" name="url" type="text" value="' . esc_attr($commenter['comment_author_url']) . '" placeholder="' . __('Website', 'cosimo') . '"/></p>',
    );
    $required_text = esc_html__(' Required fields are marked ', 'cosimo') . ' <span class="required">*</span>';
    ?>
    <?php comment_form(array(
        'fields' => apply_filters('comment_form_default_fields', $fields),
        'must_log_in' => '<p class="must-log-in">' . sprintf(__('You must be <a href="%s">logged in</a> to post a comment.', 'cosimo'), wp_login_url(apply_filters('the_permalink', get_permalink()))) . '</p>',
        'logged_in_as' => '<p class="logged-in-as smallPart">' . sprintf(__('Logged in as <a href="%1$s">%2$s</a>. <a href="%3$s" title="Log out of this account">Log out?</a>', 'cosimo'), admin_url('profile.php'), $user_identity, wp_logout_url(apply_filters('the_permalink', get_permalink()))) . '</p>',
        'comment_notes_before' => '<p class="comment-notes smallPart">' . __('Your email address will not be published.', 'cosimo') . ($req ? $required_text : '') . '</p>',
        'title_reply' => __('Leave a Reply', 'cosimo'),
        'title_reply_to' => __('Leave a Reply to %s', 'cosimo'),
        'cancel_reply_link' => __('Cancel reply', 'cosimo') . '<i class="fa fa-times spaceLeft"></i>',
        'label_submit' => __('Post Comment', 'cosimo'),
        'comment_field' => '<p class="comment-form-comment"><label for="comment"><span class="screen-reader-text">' . __('Comment *', 'cosimo') . '</span></label><textarea id="comment" name="comment" rows="8" aria-required="true" placeholder="' . __('Comment *', 'cosimo') . '"></textarea></p>',
    ));
    ?>

</div><!-- #comments -->
