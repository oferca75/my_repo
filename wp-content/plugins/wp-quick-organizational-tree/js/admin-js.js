function wm_edit_horizontal_map(refreshAfter) {
    refreshAfter = (typeof refreshAfter != 'undefined' && refreshAfter != null) ? refreshAfter : false;
    formChanged = false;
    showProcessStatusMessage(true, 'waiting', 'Editing map in progress..');
    jQuery.ajax({
        type: 'POST',
        url: wm_options.admin_url + 'admin-ajax.php',
        data: "action=wm_edit_horizontal_map&" + jQuery('#wm_horizontal_tree_form').serialize(),
        success: function (data) {
            if (Number(data) == 1) {
                showProcessResultMessage('success', 'Map updated successfully!');
                if (refreshAfter) {
                    window.location.href = wm_options.admin_url + 'admin.php?page=wm_edit_website_map&mpid=' + jQuery('#mp_id').val();
                }
            }
        }
    });
}
//-----------------------------------------------------
function wm_edit_vertical_map(refreshAfter) {
    refreshAfter = (typeof refreshAfter != 'undefined' && refreshAfter != null) ? refreshAfter : false;
    formChanged = false;
    showProcessStatusMessage(true, 'waiting', 'Editing map in progress..');
    jQuery.ajax({
        type: 'POST',
        url: wm_options.admin_url + 'admin-ajax.php',
        data: "action=wm_edit_vertical_map&" + jQuery('#wm_vertical_tree_form').serialize(),
        success: function (data) {
            if (Number(data) == 1) {
                showProcessResultMessage('success', 'Map updated successfully!');
                if (refreshAfter) {
                    window.location.href = wm_options.admin_url + 'admin.php?page=wm_edit_website_map&mpid=' + jQuery('#mp_id').val();
                }
            }
        }
    });
}
//-----------------------------------------------------
function wm_delVerTreeNodes() {
    var selec = jQuery('#wm_vertical_tree').jstree('get_checked', false);
    var regx = new RegExp("^archive[\+]+");
    var sel = [];
    for (var i = 0; i < selec.length; i++) {
        if (!regx.test(selec[i])) {
            sel.push(selec[i]);
        }
    }
    if (sel.length > 0 && confirm('Remove node(s) ?')) {
        showProcessStatusMessage(true, 'waiting', 'Deleting tree node in progress..');
        var nodes = [];
        for (var i = 0; i < sel.length; i++) {
            nodes[i] = {"id": sel[i], "parent_id": document.getElementById(sel[i]).parentNode.parentNode.id}
        }
        jQuery.ajax({
            type: 'POST',
            url: wm_options.admin_url + 'admin-ajax.php',
            data: {
                action: 'wm_del_vertical_tree_node',
                mp_id: jQuery('#mp_id').val(),
                nodes: nodes
            },
            success: function (data) {
                if (Number(data) == 1) {
                    showProcessResultMessage('success', 'Tree node is deleted successfully!');
                    for (var c = 0; c < sel.length; c++) {
                        var el = document.getElementById(sel[c]);
                        var children = el.childNodes;
                        for (var j = 0; j < children.length; j++) {
                            if (children[j].nodeType == 1 && children[j].tagName == 'A') {
                                var children2 = children[j].childNodes;
                                for (var i = 0; i < children2.length; i++) {
                                    if (children2[i].nodeType == 1 && children2[i].tagName == 'SPAN') {
                                        children2[i].className = 'excluded';
                                    }
                                }
                            }
                        }
                    }
                    jQuery('#wm_vertical_tree').jstree('uncheck_node', sel);
                }
            }
        });
    }
}
//-----------------------------------------------------
function wm_restoreVerTreeNodes() {
    var selec = jQuery('#wm_vertical_tree').jstree('get_checked', false);
    var regx = new RegExp("^archive[\+]+");
    var sel = [];
    for (var i = 0; i < selec.length; i++) {
        if (!regx.test(selec[i])) {
            sel.push(selec[i]);
        }
    }
    if (sel.length > 0 && confirm('Restore node(s) ?')) {
        showProcessStatusMessage(true, 'waiting', 'Restoring tree node in progress..');
        jQuery.ajax({
            type: 'POST',
            url: wm_options.admin_url + 'admin-ajax.php',
            data: {
                action: 'wm_restore_vertical_tree_node',
                mp_id: jQuery('#mp_id').val(),
                nodes: sel
            },
            success: function (data) {
                if (Number(data) == 1) {
                    showProcessResultMessage('success', 'Tree node is Restored successfully!');
                    for (var c = 0; c < sel.length; c++) {
                        var el = document.getElementById(sel[c]);
                        var children = el.childNodes;
                        for (var j = 0; j < children.length; j++) {
                            if (children[j].nodeType == 1 && children[j].tagName == 'A') {
                                var children2 = children[j].childNodes;
                                for (var i = 0; i < children2.length; i++) {
                                    if (children2[i].nodeType == 1 && children2[i].tagName == 'SPAN') {
                                        children2[i].className = '';
                                    }
                                }
                            }
                        }
                    }
                    jQuery('#wm_vertical_tree').jstree('uncheck_node', sel);
                }
            }
        });
    }
}
//-----------------------------------------------------
function wm_deleteMap(mp_id) {
    if (confirm('Delete This Map ?')) {
        showProcessStatusMessage(true, 'waiting', 'Deleting map in progress..');
        jQuery.ajax({
            type: 'POST',
            url: wm_options.admin_url + 'admin-ajax.php',
            data: {
                action: 'wm_delete_map',
                mp_id: mp_id
            },
            success: function (data) {
                if (Number(data) == 1) {
                    showProcessResultMessage('success', 'Map is deleted successfully!');
                    jQuery('#tr-mp_id-' + mp_id).remove();
                }
            }
        });
    }
}
//-----------------------------------------------------
function showProcessStatusMessage(show, processStatus, message) {
    message = (typeof message != 'undefined') ? message : '';
    processStatus = (typeof processStatus != 'undefined') ? processStatus : '';
    jQuery('#processMessage').removeClass('waitingStatus');
    jQuery('#processMessage').removeClass('successStatus');
    jQuery('#processMessage').removeClass('failureStatus');
    jQuery('#processMessage').removeClass('alertStatus');

    switch (processStatus) {
        case 'waiting':
            jQuery('#processMessage').addClass('waitingStatus');
            break;

        case 'success':
            jQuery('#processMessage').addClass('successStatus');
            break;

        case 'failure':
            jQuery('#processMessage').addClass('failureStatus');
            break;

        case 'alert':
            jQuery('#processMessage').addClass('alertStatus');
            break;

        default:
    }

    jQuery('#processMessage span').html(message);

    if (show) {
        jQuery('#processMessageContainer').css('display', 'inline-block');
    } else {
        jQuery('#processMessageContainer').css('display', 'none');
    }
}
//-----------------------------------------------------
function showProcessResultMessage(status, message, timeout) {
    timeout = (typeof timeout != 'undefined') ? timeout : 5000;

    showProcessStatusMessage(true, status, message);

    var t = setTimeout(function () {
        showProcessStatusMessage(false, null, '');
        clearTimeout(t);
    }, timeout);
}
//-----------------------------------------------------
function monitorFormChanges(fieldNames) {
    for (var i = 0; i < fieldNames.length; i++) {
        var name = fieldNames[i].replace(/(\[|\])/g, '\\$1');
        var element = jQuery('[name=' + name + ']');

        var tagName = element.prop('tagName');
        if (tagName == 'SELECT') {
            element.change(function () {
                formChanged = true;
            });
        }
        else if (tagName == 'INPUT') {
            var type = element.prop('type');
            switch (type) {
                case 'text':
                case 'password':
                    element.keypress(function () {
                        formChanged = true;
                    });
                    break;

                case 'radio':
                case 'checkbox':
                    element.click(function () {
                        formChanged = true;
                    });
                    break;

                default:
                    element.keypress(function () {
                        formChanged = true;
                    });
            }
        }
        else if (tagName == 'TEXTAREA') {
            element.keypress(function () {
                formChanged = true;
            });
        }
        else {
            element.keypress(function () {
                formChanged = true;
            });
        }
    }
}
//-----------------------------------------------------
var addImageButton = null;
window.original_send_to_editor = window.send_to_editor;

window.send_to_editor = function (html) {
    var fileurl = '';
    fileurl = jQuery('img', html).attr('src');
    if (!fileurl) {
        var regex = /src="(.+?)"/;
        var rslt = html.match(regex);
        fileurl = rslt[1];

        addImageButton.parentNode.thumbnail_id.value = encodeURIComponent(fileurl);
        jQuery('#' + addImageButton.id).after('<img src="' + wm_options.plug_images_url + 'check_16.png" width="16" height="16" style="margin: 5px;" />');
    }

    tb_remove();
    jQuery('html').removeClass('Image');
}
//-----------------------------------------------------
function wm_addImage(btn) {
    addImageButton = btn;

    jQuery('html').addClass('Image');

    var frame;
    if (WORDPRESS_VER >= "3.5") {
        if (frame) {
            frame.open();
            return;
        }
        frame = wp.media();
        frame.on("select", function () {
            var attachment = frame.state().get("selection").first();
            var fileurl = attachment.attributes.url;
            frame.close();

            addImageButton.parentNode.thumbnail_id.value = encodeURIComponent(fileurl);
            jQuery('#' + addImageButton.id).after('<img src="' + wm_options.plug_images_url + 'check_16.png" width="16" height="16" style="margin: 5px;" />');
        });
        frame.open();
    }
    else {
        tb_show("", "media-upload.php?type=image&amp;TB_iframe=true&amp;tab=library");
        return false;
    }

}
//-----------------------------------------------------
function disableElements(elementNames, disable, value, criticalValue) {
    disable = (value == criticalValue) ? disable : !disable;
    for (var i = 0; i < elementNames.length; i++) {
        var field = jQuery('[name="' + elementNames[i].replace(/(\[|\])/g, '\\$1') + '"]');
        field.prop('disabled', disable);
    }
}
//-----------------------------------------------------
function wm_changeButtonsStatus() {
    var arr = jQuery('#wm_vertical_tree').jstree('get_checked', false);
    var disabled = true;
    if (arr.length > 0) {
        disabled = false;
    }
    jQuery('#wm_deleteNodeBtn').prop('disabled', disabled);
    jQuery('#wm_restoreNodeBtn').prop('disabled', disabled);
}
//-----------------------------------------------------
function wm_setContainerDimentions() {
    jQuery('[name="mp_container_width"]').val(jQuery('#treeContainer').width());
    jQuery('[name="mp_container_height"]').val(jQuery('#treeContainer').height() + 200);
    showProcessResultMessage('alert', 'Container dimensions are changed', 4000);
    formChanged = true;
}
//-----------------------------------------------------

