(function ($) {
    "use strict";

    $(document).ready(function () {

        /*-----------------------------------------------------------------------------------*/
        /*  Set height of header
         /*-----------------------------------------------------------------------------------*/
        function setHeight() {
            var windowHeight = $(window).innerHeight() / 1.5;
            $('.site-header').css('height', windowHeight);
        };
        setHeight();

        function setNano() {
            if ($(window).width() > 767) {
                $(".nano").nanoScroller({preventPageScrolling: true});
            }
        };
        setNano();

        $(window).resize(function () {
            setHeight();
            setNano();
        });

        /*-----------------------------------------------------------------------------------*/
        /*  Home icon in main menu
         /*-----------------------------------------------------------------------------------*/
        if ($('body').hasClass('rtl')) {
            $('.main-navigation .menu-item-home > a').append('<i class="fa fa-home spaceRight"></i>');
        } else {
            $('.main-navigation .menu-item-home > a').prepend('<i class="fa fa-home spaceRight"></i>');
        }

        /*-----------------------------------------------------------------------------------*/
        /*  If Comment Metadata exist or Edit Comments Link exist
         /*-----------------------------------------------------------------------------------*/
        if ($('.comment-metadata').length) {
            $('.comment-metadata').addClass('smallPart');
        }
        if ($('.reply').length) {
            $('.reply').addClass('smallPart');
        }

        /*-----------------------------------------------------------------------------------*/
        /*  Masonry & ImagesLoaded
         /*-----------------------------------------------------------------------------------*/
        if ($('#mainCosimo').length) {
            var whatText = $('body').hasClass('rtl') ? true : false;
            var $container = $('#mainCosimo').masonry();
            $container.imagesLoaded(function () {
                $container.masonry({
                    columnWidth: '.grid-sizer',
                    itemSelector: '.cosimomas',
                    transitionDuration: '0.3s',
                    isRTL: whatText
                });
            });
        }
        var $featImage = $('.entry-featuredImg');
        $featImage.imagesLoaded(function () {
            $('.entry-featuredImg img').css({
                opacity: 1
            });
            setTimeout(function () {
                $featImage.removeClass('cosimo-loader');
            }, 1500);
        });

        /*-----------------------------------------------------------------------------------*/
        /*  Manage Sidebar
         /*-----------------------------------------------------------------------------------*/
        $('.main-sidebar-box').click(function () {
            $('.widget-area, .main-sidebar-box, body').toggleClass('sidebar-open');
            $('body').toggleClass('menu-opened');
            setTimeout(function () {
                $('body').removeClass('menu-opened');
            }, 500);
        });

        /*-----------------------------------------------------------------------------------*/
        /*  Open Featured Images
         /*-----------------------------------------------------------------------------------*/
        $('.openFeatImage').click(function () {
            var $ddelay = 0;
            if ($('.main-sidebar-box').hasClass('sidebar-open')) {
                $('.widget-area, .main-sidebar-box, body').removeClass('sidebar-open');
                $('body').addClass('menu-opened');
                setTimeout(function () {
                    $('body').removeClass('menu-opened');
                }, 500);
                var $ddelay = 500;
            }
            setTimeout(function () {
                $('.openFeatImage').toggleClass('featOpen');
                $('body').toggleClass('featOpenS');
                $('body').addClass('featOpen');
                setTimeout(function () {
                    $('body').removeClass('featOpen');
                }, 500);
                if ($('.openFeatImage').hasClass('featOpen')) {
                    $('.openFeatImage').html('<i class="fa fa-lg fa-angle-double-up"></i>');
                    var windowHeight = $(window).innerHeight();
                    $('.site-branding').fadeTo(400, 0.0, function () {
                        $('.site-branding').hide();
                    });
                } else {
                    $('.openFeatImage').html('<i class="fa fa-lg fa-angle-double-down"></i>');
                    var windowHeight = $(window).innerHeight() / 1.5;
                    $('.site-branding').fadeTo(400, 1);
                }
                $('.site-header').css('height', windowHeight);
            }, $ddelay);
        });

        /*-----------------------------------------------------------------------------------*/
        /*  Search button
         /*-----------------------------------------------------------------------------------*/
        $('.main-search-box').click(function () {
            var $ddelay = 0;
            if ($('.main-sidebar-box').hasClass('sidebar-open')) {
                $('.widget-area, .main-sidebar-box, body').removeClass('sidebar-open');
                $('body').addClass('menu-opened');
                setTimeout(function () {
                    $('body').removeClass('menu-opened');
                }, 500);
                var $ddelay = 500;
            }
            setTimeout(function () {
                $('#search-full').fadeIn(300);
                if (/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)) {
                } else {
                    $("#search-full #search-field").focus();
                }
            }, $ddelay);
        });

        $('.closeSearch').click(function () {
            $('#search-full').fadeOut(300);
        });

        /*-----------------------------------------------------------------------------------*/
        /*  Mobile Menu
         /*-----------------------------------------------------------------------------------*/
        if ($(window).width() < 769) {
            $('.main-navigation').find("li").each(function () {
                if ($(this).children("ul").length > 0) {
                    $(this).append("<span class='indicator'></span>");
                }
            });
            $('.main-navigation ul > li.menu-item-has-children .indicator, .main-navigation ul > li.page_item_has_children .indicator').click(function () {
                $(this).parent().find('> ul.sub-menu, > ul.children').toggleClass('yesOpen');
                $(this).toggleClass('yesOpen');
                var $self = $(this).parent();
                if ($self.find('> ul.sub-menu, > ul.children').hasClass('yesOpen')) {
                    $self.find('> ul.sub-menu, > ul.children').slideDown(300);
                } else {
                    $self.find('> ul.sub-menu, > ul.children').slideUp(200);
                }
            });
        }
        $(window).resize(function () {
            if ($(window).width() > 769) {
                $('.main-navigation ul > li.menu-item-has-children, .main-navigation ul > li.page_item_has_children').find('> ul.sub-menu, > ul.children').slideDown(300);
            }
        });

        /*-----------------------------------------------------------------------------------*/
        /*  Detect Mobile Browser
         /*-----------------------------------------------------------------------------------*/
        if (/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)) {
        } else {

            /*-----------------------------------------------------------------------------------*/
            /*  Scroll To Top
             /*-----------------------------------------------------------------------------------*/
            $(window).scroll(function () {
                if ($(this).scrollTop() > 700) {
                    $('#toTop').fadeIn(300);
                }
                else {
                    $('#toTop').fadeOut(300);
                }
            });
            $('#toTop').click(function () {
                $("html, body").animate({scrollTop: 0}, 1000);
                return false;
            });

        }


    });

})(jQuery);