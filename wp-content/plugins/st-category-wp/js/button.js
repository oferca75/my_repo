(function () {
    'use strict';

    tinymce.create('tinymce.plugins.St_Categories_Shortcode', {
        init: function (editor, url) {
            editor.addButton('St_Categories_Shortcode', {
                title: 'ST Categories',
                cmd: 'cat',
                image: url + '/../images/categories-icon.png'
            });
            var shortcodeValues = [];
            jQuery.each(st_cat_name, function (i) {
                shortcodeValues.push({text: st_cat_name[i], value: st_cat_id[i]});
            });
            editor.addCommand('cat', function () {
                editor.windowManager.open({
                    title: 'ST Categories Setting',
                    body: [
                        {
                            type: 'textbox',
                            name: 'title',
                            label: 'Title',
                            minWidth: 200
                        },
                        {
                            type: 'listbox',
                            name: 'categories',
                            label: 'Choose category',
                            'values': shortcodeValues
                        },
                        {
                            type: 'listbox',
                            name: 'templates',
                            label: 'Choose Templates',
                            values: [
                                {text: 'Template 1', value: 'style1'},
                                {text: 'Template 2', value: 'style2'},
                                {text: 'Default', value: 'default'}
                            ]
                        },
                        {
                            type: 'textbox',
                            name: 'limit',
                            label: 'Limit',
                            minWidth: 200
                        },
                        {
                            type: 'checkbox',
                            name: 'type',
                            label: 'Display as dropdown',
                            checked: false
                        },
                        {
                            type: 'checkbox',
                            name: 'postcount',
                            label: 'Show post counts',
                            checked: true
                        },
                        {
                            type: 'checkbox',
                            name: 'hideempty',
                            label: 'Hide Empty Categories',
                            checked: false
                        },
                        {
                            type: 'checkbox',
                            name: 'hierarchy',
                            label: 'Hide Hierarchy',
                            checked: false
                        }
                    ],
                    onsubmit: function (e) {

                        var shortcode = '[st-categories';

                        if (e.data.title) {
                            shortcode += ' title="' + e.data.title;
                            shortcode += '"';
                        }
                        if (e.data.categories) {
                            shortcode += ' child_of="' + e.data.categories;
                            shortcode += '"';
                        }
                        if (e.data.templates) {
                            shortcode += ' templates="' + e.data.templates;
                            shortcode += '"';
                        }
                        if (e.data.limit) {
                            shortcode += ' number="' + e.data.limit;
                            shortcode += '"';
                        }
                        shortcode += ' type="' + e.data.type;
                        shortcode += '"';

                        shortcode += ' show_count="' + e.data.postcount;
                        shortcode += '"';

                        shortcode += ' hide_empty="' + e.data.hideempty;
                        shortcode += '"';


                        shortcode += ' depth="' + e.data.hierarchy;
                        shortcode += '"';

                        shortcode += editor.selection.getContent() + ']';

                        editor.execCommand('mceInsertContent', 0, shortcode);

                    }
                });
            });
        }
    });

    tinymce.PluginManager.add('St_Categories_Shortcode', tinymce.plugins.St_Categories_Shortcode);
}());