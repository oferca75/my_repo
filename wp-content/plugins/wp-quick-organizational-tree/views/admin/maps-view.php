<?php $admin_url = get_admin_url(); ?>
<?php $page = (isset($_GET['pg'])) ? (int)$_GET['pg'] : 1; ?>
<?php $page = (!empty($page)) ? $page : 1; ?>
<?php $title = (isset($_GET['title']) && !empty($_GET['title'])) ? $_GET['title'] : ''; ?>
<?php $type = (isset($_GET['type'])) ? $_GET['type'] : NULL; ?>
<?php $limit = 7; ?>
<?php $offset = ($page - 1) * $limit; ?>
<?php $website_maps = wm_get_all_maps($title, $type, $limit, $offset); ?>
<!-- start process message -->
<div id="processMessageContainer" class="processMessageContainer">
    <div id="processMessage" class="processMessage successStatus">
        <span></span>
    </div>
</div>
<!-- end process message -->

<div class="container-fluid wm-plugin-logo">
    <div class="row">
        <div class="col-md-8">
            <div class="row">

                <div class="col-md-12">
                    <div class="wpLogo">
                        <img src="<?php echo plugins_url('images/wp-tree.png', WM_PLUGIN_MAIN_FILE) ?>" border="0"
                             style="width:32px; height:32px"/>
                    </div>
                    <h1>&nbsp;Tree Website Map</h1>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="container-fluid wp-tree">
    <div class="row" style="margin-top: 20px;">
        <div class="col-md-8 col-sm-12">
            <div class="row">

                <div class="col-sm-3">
                    <a class="btn btn-info"
                       href="<?php echo $admin_url ?>admin.php?page=wm_edit_website_map&tp=ver"><?php _e('Add New Vertical Map', 'websitemap'); ?></a>
                </div>


            </div>
            <div style=" color:#090; padding:10px; -webkit-border-radius: 10px;
-moz-border-radius: 10px; border-radius: 10px; font-size:15px; margin-bottom:20px">&nbsp;<b style="color:#C00">limited
                    time offer!</b><br/><br/>If you want to add horizontal trees to create organizational tree or family
                tree please <a href="http://www.wp-buy.com/product/wp-tree-pro/" target="_blank">upgrade to the pro</a>
                version and save 40%, The premium version of Tree website map is completely different from the free
                version as there are a lot more features included<p></p></div>

        </div>
    </div>
    <?php
    if (!empty($website_maps['data'])) {
        ?>
        <div class="row wm-maps-filter">
            <form id="wm_site_maps" method="get" action="<?php echo $admin_url ?>admin.php">
                <input type="hidden" name="page" value="wm_website_maps"/>
                <div class="col-sm-3 wm-form">
                    <label><?php _e('Map Title', 'websitemap') ?></label>
                    <input type="text" class="form-control" name="title" value="<?php echo $title ?>">
                </div>

                <div class="col-sm-2 wm-form">
                    <button type="submit" class="btn btn-default"><?php _e('Filter', 'websitemap') ?></button>
                </div>
            </form>
        </div>
    <?php } ?>
    <div class="row">
        <div class="col-sm-12">
            <?php
            if (!empty($website_maps['data'])) {
                ?>
                <table class="table table-bordered table-striped table-hover wm-maps-table">
                    <thead>
                    <tr>
                        <th width="20"></th>
                        <th><?php _e('Map Title', 'websitemap') ?></th>
                        <th><?php _e('Map Type', 'websitemap') ?></th>
                        <th><?php _e('Shortcode', 'websitemap') ?></th>
                        <th>Edit</th>
                        <th>Delete</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($website_maps['data'] as $map): ?>
                        <tr id="tr-mp_id-<?php echo $map['mp_id'] ?>">
                            <td>
                                <img
                                    src="<?php echo plugins_url('images/' . strtolower($map['mp_type']) . '_16.png', WM_PLUGIN_MAIN_FILE) ?>"
                                    border="0" width="16" height="16"/>
                            </td>
                            <td>
                                <a href="<?php echo $admin_url ?>admin.php?page=wm_edit_website_map&mpid=<?php echo $map['mp_id'] ?>"><?php echo $map['mp_name'] ?></a>
                            </td>
                            <td><?php echo $map['mp_type'] ?></td>
                            <td>[wm_website_map id="<?php echo $map['mp_id'] ?>"]</td>
                            <td>
                                <a href="<?php echo $admin_url ?>admin.php?page=wm_edit_website_map&mpid=<?php echo $map['mp_id'] ?>">
                                    <img src="<?php echo plugins_url('images/edit_16.png', WM_PLUGIN_MAIN_FILE) ?>"
                                         border="0"
                                         title="<?php _e('Edit', 'websitemap') ?>" width="16" height="16"/>
                                </a>
                            </td>
                            <td>
                                <a href="javascript: void(0);" onclick="wm_deleteMap('<?php echo $map['mp_id'] ?>');">
                                    <img
                                        src="<?php echo plugins_url('images/delete-icon_16.png', WM_PLUGIN_MAIN_FILE) ?>"
                                        border="0"
                                        title="<?php _e('Delete', 'websitemap') ?>" width="16" height="16"/>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            <?php } else {
                echo '<br /><br /><p><em>Get started now and create your first tree !</em></p>';
            }

            if ($website_maps['total'] > $limit): ?>
                <?php echo wm_pagination($admin_url . 'admin.php?page=wm_website_maps&pg=', $website_maps['total'], $limit, $page); ?>
            <?php endif; ?>
        </div>
    </div>
</div>
</div>
<script type="text/javascript">
    var wm_options = {
        admin_url: '<?php echo admin_url(); ?>',
        images_url: ''
    };
</script>