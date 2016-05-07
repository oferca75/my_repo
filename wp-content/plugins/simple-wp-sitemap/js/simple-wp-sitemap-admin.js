(function ($) {
    var SitemapAdmin = function () {
        this.run = function (config) {
            var that = this;
            this.c = config;

            this.c.btns.on('click', function () {
                that.changeState($(this));
            });
            this.c.ul.on('click', function (e) {
                that.changeOrder($(e.target));
            });
            this.c.defaults.on('click', function () {
                that.restoreDefaults();
            });
            this.c.form.on('submit', function () {
                that.submitForm();
            });
            this.c.theBtn.on('click', function () {
                that.upgrade(that.c.theField);
            });
            this.c.theField.on('keypress', function (e) {
                if (e.which === 13) {
                    e.preventDefault();
                    that.upgrade(that.c.theField);
                }
            }).on('click', function () {
                if (that.c.theBtn.next().text()) {
                    that.c.theBtn.next().text('');
                }
            });

            if (this.c.theField.val()) {
                this.c.btns.eq(2).click();
            }
        };

        this.changeState = function (btn) {
            this.c.btns.attr('class', '');
            btn.attr('class', 'sitemap-active');
            this.c.tables.attr('id', '').parent().find('table[data-id="' + btn.attr('id') + '"]').attr('id', 'sitemap-table-show');
        };

        this.changeOrder = function (node) {
            var li = node.parent();

            if (node.attr('class') === 'sitemap-up' && li.prev()[0]) {
                li.prev().before(li);
            }
            else if (node.attr('class') === 'sitemap-down' && li.next()[0]) {
                li.next().after(li);
            }
        };

        this.submitForm = function () {
            var inputs = this.c.ul.find('input');

            $.each(inputs, function (i) {
                inputs.eq(i).val(i + 1);
            });
        };

        this.upgrade = function (input) {
            var form = $('#simpleWpHiddenForm');

            form.find('input[type=hidden]').attr({name: 'upgrade_to_premium', value: input.val()});
            form.submit();
        }

        this.restoreDefaults = function () {
            var sections = ['Home', 'Posts', 'Pages', 'Other', 'Categories', 'Tags', 'Authors'],
                html = '';

            $.each(sections, function (i) {
                html += '<li>' + sections[i] + '<span class="sitemap-down" title="move down"></span><span class="sitemap-up" title="move up"></span><input type="hidden" name="simple_wp_' + sections[i].toLowerCase() + '_n" value="' + (i + 1) + '"></li>';
            });

            this.c.ul.empty().append(html);
        };
    };

    var sitemap = new SitemapAdmin();
    sitemap.run({
        ul: $('#sitemap-display-order'),
        defaults: $('#sitemap-defaults'),
        form: $('#simple-wp-sitemap-form'),
        btns: $('#sitemap-settings li'),
        tables: $('#simple-wp-sitemap-form table'),
        theBtn: $('#upgradeToPremium'),
        theField: $('#upgradeField')
    });
})(jQuery);