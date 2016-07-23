<?php

$postId = get_the_ID();

$video_id = get_metadata('post', $postId, "video_id", true);
$post_thumbnail_html = '<img src="http://img.youtube.com/vi/' . $video_id . '/0.jpg" />'

?>
<div style="background:black" class="tech-video-wrap">
    <img class="video-thumb" src="http://img.youtube.com/vi/<?php echo $video_id; ?>/hqdefault.jpg"/>
    <img class="play-button" height="500" id="<?php echo $video_id; ?>"
         src="<?php echo get_stylesheet_directory_uri() ?>/img/playbutton.png"
         onclick='jQuery(this).fadeToggle();
             jQuery(this).prev().remove();
             jQuery(this).parent().append("<iframe src=\"https://www.youtube.com/embed/<?php echo $video_id; ?>?autoplay=1\" frameborder=\"0\" allowfullscreen></iframe>");
             '
    />
</div>

<div class="next-move-title"><?php echo get_next_moves_title(); ?></div>