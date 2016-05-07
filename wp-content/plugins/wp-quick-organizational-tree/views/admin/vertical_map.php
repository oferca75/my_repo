<?php
wp_enqueue_style('wm-awesome-css', plugins_url('lib/font-awesome/css/font-awesome.min.css', WM_PLUGIN_MAIN_FILE));
wp_enqueue_style('wm-jstree-proton-theme-css', plugins_url('lib/jstree-bootstrap-theme/src/themes/proton/style.css', WM_PLUGIN_MAIN_FILE));
wp_enqueue_style('jstree-css', plugins_url('lib/jstree/dist/themes/default/style.css', WM_PLUGIN_MAIN_FILE));
wp_enqueue_script('jstree-js', plugins_url('lib/jstree/dist/jstree.min.js', WM_PLUGIN_MAIN_FILE));

if (empty($mp_id)) {
    $mp_id = wm_create_new_vertical_map();
    ?>
    <div
        style="background: #CCEBC9 url(<?php echo plugins_url('images/success2_24.png', WM_PLUGIN_MAIN_FILE) ?>) no-repeat 10px 10px; padding: 10px 10px 10px 40px; border: solid 1px #B0DEA9; color: #508232; margin: 20px 10px;">
        Vertical map is created successfully
    </div>
    <script type="text/javascript">
        window.location.href = "<?php echo get_admin_url() ?>admin.php?page=wm_edit_website_map&mpid=<?php echo $mp_id ?>";
    </script>
    <?php
}

$map = wm_read_map($mp_id);

?>
<!-- start process message -->
<div id="processMessageContainer" class="processMessageContainer">
    <div id="processMessage" class="processMessage successStatus">
        <span></span>
    </div>
</div>
<!-- end process message -->
<div class="container-fluid wm-form-container">
    <form method="post" action="" onsubmit="return false;" id="wm_vertical_tree_form">
        <input type="hidden" name="mp_id" id="mp_id" value="<?php echo $mp_id ?>"/>
        <div class="row">
            <div class="col-sm-4">
                <div class="row">
                    <div class="col-sm-4">
                        <a class="btn btn-primary" style="margin-bottom: 20px;"
                           href="<?php echo get_admin_url() ?>admin.php?page=wm_website_maps">
                            <i class="fa fa-arrow-left"></i> <?php _e('Go back', 'websitemap') ?></a>
                    </div>

                    <div class="col-sm-6">
                        <button type="button" class="btn btn-success" onclick="wm_edit_vertical_map(true);">
                            <i class="fa fa-floppy-o"></i> <?php _e('Save and refresh', 'websitemap') ?></button>
                    </div>
                </div>
            </div>
        </div>


        <div class="row">
            <div class="col-sm-3">
                <label><?php _e('Map Name', 'websitemap') ?></label>
                <input type="text" class="form-control" name="mp_name" value="<?php echo $map['mp_name'] ?>">
            </div>

            <div class="col-sm-2">
                <label><?php _e('Show feature image', 'websitemap') ?></label>
                <select class="form-control" name="mp_enable_thumbnail">
                    <option value="1" <?php selected($map['mp_enable_thumbnail'], 1) ?>>Yes</option>
                    <option value="0" <?php selected($map['mp_enable_thumbnail'], 0) ?>>No</option>
                </select>
            </div>

            <div class="col-sm-2" style="display:none">

                <input type="text" class="form-control" name="mp_enable_link" value="0">

            </div>

            <div class="col-sm-2">
                <label><?php _e('Number of posts in list', 'websitemap') ?></label>
                <input type="number" class="form-control" name="mp_list_posts"
                       value="<?php echo $map['mp_list_posts'] ?>"/>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-3">
                <label><?php _e('Root node title', 'websitemap') ?></label>
                <input type="text" class="form-control" name="mp_root_node" value="<?php echo $map['mp_root_node'] ?>">
            </div>

            <div class="col-sm-3">
                <label><?php _e('Root node url', 'websitemap') ?></label>
                <input type="text" class="form-control" name="mp_root_node_link"
                       value="<?php echo $map['mp_root_node_link'] ?>">
            </div>
        </div>


    </form>
    <div class="col-sm-12"
         style="color:#F00; padding:5px; font-size:14px; background:#FF9; margin-top:5px; margin-bottom:5px">
        Note : To exclude nodes and enable hyperlinks, please <a href="http://www.wp-buy.com/product/wp-tree-pro/"
                                                                 target="_blank">upgrade</a> to pro version
    </div>
    <p></p>

    <div class="container-fluid wm-tree-container" style="border: solid 2px #CCCCCC; border-radius: 5px;">
        <div class="row" style="padding: 10px 0; background-color: #CCCCCC;">
            &nbsp;Tree Preview
        </div>
        <div id="wm_vertical_tree" class="wm-vertical-map"></div>

        <p>
            <em>Shortcode : [wm_website_map id="<?php echo $mp_id ?>"]</em>
        </p>

    </div>
</div>
<script type="text/javascript">
    var wm_options = {
        admin_url: '<?php echo admin_url(); ?>',
        images_url: '',
        plug_images_url: '<?php echo plugins_url('images/', WM_PLUGIN_MAIN_FILE); ?>'
    };

    var formChanged = false;

    monitorFormChanges(['mp_name', 'mp_enable_thumbnail', 'mp_enable_link', 'mp_root_node', 'mp_root_node_link', 'mp_list_posts']);

    jQuery(document).ready(function () {

        setInterval(function () {
            if (formChanged) {
                wm_edit_vertical_map();
            }
        }, 10000);

        jQuery("#wm_vertical_tree").jstree({
            "core": {
                "themes": {
                    "name": "proton",
                    "responsive": true,
                    "icons": <?php echo ($map['mp_enable_thumbnail']) ? 'true' : 'false'; ?>,
                },
                "check_callback": true,
                "data": {
                    "url": function (node) {
                        return "<?php echo admin_url() ?>admin-ajax.php";
                    },
                    "data": function (node) {
                        return {
                            "action": "wm_get_vertical_tree_nodes",
                            "mp_id": jQuery('#mp_id').val(),
                            "parent": node.id,
                            "control_panel": true
                        };
                    }
                }
            },
            "types": {
                "default": {
                    "icon": "fa fa-folder icon-state-warning icon-lg"
                },
                "file": {
                    "icon": "fa fa-file icon-state-warning icon-lg"
                }
            },
            "checkbox": {
                "keep_selected_style": false,
                "tie_selection": false
            },
            "state": {"key": "demo3"},
            "plugins": ["dnd", "state", "types", "checkbox"]
        });

        jQuery("#wm_vertical_tree").bind('check_node.jstree', function (node, selected, evnt) {
            wm_changeButtonsStatus();
        });

        jQuery("#wm_vertical_tree").bind('uncheck_node.jstree', function (node, selected, evnt) {
            wm_changeButtonsStatus();
        });
    });

    window.onbeforeunload = function () {
        if (formChanged) {
            return false;
        }
    }
</script>