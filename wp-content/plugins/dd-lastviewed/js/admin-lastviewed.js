( function( $ ) {
    $(document).on('click','.dd-switch', function(){
        $(this).toggleClass('on');
        var value = $(this).is('.on') ? true : false;
        $(this).next('input').attr('checked', value);

        var this_lv_link = $(this).next().next('.lv_link');

        var lv_link_val = this_lv_link.is('.button-primary'); //? true : false;

        if(!value && lv_link_val ){
            this_lv_link.trigger("click");

        }

    });

    $(document).on('click','.lv_link', function(){
        $(this).toggleClass('button-primary');
        var value = $(this).is('.button-primary') ? true : false;
        $(this).next('input').attr('checked', value);
    })
} )( jQuery );