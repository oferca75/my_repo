<?php if ('comments.php' == basename($_SERVER['SCRIPT_FILENAME'])) return; ?>
<section id="comments">
    <?php
    if (have_comments()) :
        global $comments_by_type;
        $comments_by_type = separate_comments($comments);
        if (!empty($comments_by_type['comment'])) :
            ?>
            <section id="comments-list" class="comments">
                <h3 class="comments-title"><?php comments_number(__('No comments about ', 'fluid-baseline-grid'), __('1 comment about ', 'fluid-baseline-grid'), __('% comments about ', 'fluid-baseline-grid'));
                    the_title(); ?></h3>
                <?php if (get_comment_pages_count() > 1) : ?>
                    <nav id="comments-nav-above" class="comments-navigation" role="navigation">
                        <p class="paginated-comments-links"><?php paginate_comments_links(); ?></p>
                    </nav>
                <?php endif; ?>
                <ul>
                    <?php wp_list_comments('type=all&format=html5&avatar_size=2em&short_ping=true'); ?>
                </ul>
                <?php if (get_comment_pages_count() > 1) : ?>
                    <nav id="comments-nav-below" class="comments-navigation" role="navigation">
                        <p class="paginated-comments-links"><?php paginate_comments_links(); ?></p>
                    </nav>
                <?php endif; ?>
            </section>
            <?php
        endif;
    endif;
    if (comments_open()) {
        $comment_args = array(
            'comment_field' => '<p class="comment-form-comment"><label for="comment">' . _x('Comment', 'noun', 'fluid-baseline-grid') . '</label><textarea id="comment" name="comment" cols="45" rows="5" aria-required="true"></textarea></p>',
        );
        comment_form($comment_args);
    }
    ?>
</section>