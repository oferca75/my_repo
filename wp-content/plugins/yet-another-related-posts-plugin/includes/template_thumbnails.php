<?php
/*
 * YARPP's built-in thumbnails template
 * @since 4
 *
 * This template is used when you choose the built-in thumbnails option.
 * If you want to create a new template, look at yarpp-templates/yarpp-template-example.php as an example.
 * More information on the custom templates is available at http://mitcho.com/blog/projects/yarpp-3-templates/
 */

if (!$this->diagnostic_using_thumbnails())
    $this->set_option('manually_using_thumbnails', true);

$options = array('thumbnails_heading', 'thumbnails_default', 'no_results');
extract($this->parse_args($args, $options));

// a little easter egg: if the default image URL is left blank,
// default to the theme's header image. (hopefully it has one)
if (empty($thumbnails_default))
    $thumbnails_default = get_header_image();
$dimensions = $this->thumbnail_dimensions();
if (!function_exists("eliminateKeywords")) {
    function eliminateKeywords($str)
    {
        return $str;
    }
}


if (have_posts()) {
    $postTitle = get_the_title();
    if ($postTitle !== "Front") {
        global $arrowSVG;
        $dispStr = '<div class="next-arrow">'.$arrowSVG."</div>";
        if ((endsWith($postTitle, "sitions") || endsWith($postTitle, "sition"))) {
            $dispStr .= "Attacks And Defences From The " . eliminateKeywords($postTitle);
        } else {
            $dispStr .= 'What To Do From ' . eliminateKeywords($postTitle);

        };
        $output .= '<h3>' . $dispStr . ':</h3>' . "\n";
    }
    $output .= '<div class="yarpp-thumbnails-horizontal">' . "\n";
    while (have_posts()) {
        the_post();

        $output .= "<a class='yarpp-thumbnail' href='" . get_permalink() . "' title='" . the_title_attribute('echo=0') . "'><div class='next-overlay'></div>" . "\n";

//Ofer BEGIN
        if (preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', get_the_content(), $match)) {
            $video_id = $match[1];
        }
        global $post;
        //	$post_thumbnail_html =  '<a href="'.$post->guid.'">'.get_the_content().'</a>';

        $post_thumbnail_html = '<iframe id="_ytid_56590" src="https://www.youtube.com/embed/' . $video_id . '?enablejsapi=1&amp;loop=1&amp;playlist=fvmhXk95ZtA&amp;autoplay=0&amp;cc_load_policy=0&amp;iv_load_policy=1&amp;modestbranding=0&amp;rel=1&amp;showinfo=1&amp;playsinline=0&amp;controls=2&amp;autohide=2&amp;theme=dark&amp;color=red&amp;wmode=opaque&amp;vq=&amp;&amp;enablejsapi=1&amp;origin=http://bjjinteractive.ga" frameborder="0" class="__youtube_prefs__" data-vol="0" allowfullscreen=""></iframe>';
        // Ofer END
        //'<iframe width="420" height="315" src="'.get_the_content().'" frameborder="0" allowfullscreen></iframe>';
        if (has_post_thumbnail()) {
            if ($this->diagnostic_generate_thumbnails())
                $this->ensure_resized_post_thumbnail(get_the_ID(), $dimensions);
            $post_thumbnail_html = get_the_post_thumbnail(null, $dimensions['size']);
        }

        $output .= '<span class="yarpp-thumbnail-title">' . eliminateKeywords(get_the_title()) . '</span>';

        if (trim($post_thumbnail_html) != '')
            $output .= $post_thumbnail_html;
        else
            $output .= '<span class="yarpp-thumbnail-default"><img src="' . esc_url($thumbnails_default) . '"/></span>';

        $output .= '</a>' . "\n";

    }
    //Ofer Begin comment out following line - causes bug
      $output .= "</div>\n";
} else {
    $output .= $no_results;
}

$this->enqueue_thumbnails($dimensions);
