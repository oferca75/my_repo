<?php
$mp_id = (isset($_GET['mpid'])) ? $_GET['mpid'] : NULL;
$type = (isset($_GET['tp'])) ? $_GET['tp'] : NULL;

$mp_name = NULL;
if ($mp_id > 0) {
    $t = wm_read_column_value("{$table_prefix}wm_maps", 'mp_type', 'mp_id', $mp_id);
    $type = (is_array($t) && !empty($t)) ? $t[0]->mp_type : '';

    $n = wm_read_column_value("{$table_prefix}wm_maps", 'mp_name', 'mp_id', $mp_id);
    $mp_name = (is_array($n) && !empty($n)) ? $n[0]->mp_name : '';
}

if ('hor' == substr($type, 0, 3)) {
    //include( "horizontal_map.php" );
} else if ('ver' == substr($type, 0, 3)) {
    if ('POST' == $_SERVER['REQUEST_METHOD']) {
        wm_save_vertical_tree();
    }
    include("vertical_map.php");
}
?>