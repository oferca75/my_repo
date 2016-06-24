<div class="fight-path">
    <?php
    if (!is_archive()) { // lastviewed causes strange bug

        global $dd_lastviewed_id;
        echo do_shortcode('[dd_lastviewed widget_id="' . $dd_lastviewed_id . '"]');
    }
    
    ?>
</div>