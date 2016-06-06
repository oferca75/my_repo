jQuery(document).ready(function ($) {
$(".widget_lastviewed .widgettitle").click(function(){
    $(".fight-path .lastViewedList").toggle("slow");
})

    jQuery.fx.off = true;
    setTimeout(function(){jQuery.fx.off= false},0);
    jQuery(".icon-minus-squared").parent().click();
    jQuery(".lastViewedcontent h3, .entry-header .entry-title").each(function(){
        var cand = jQuery(this).text();
        jQuery("li.cat-item a:contains('"+cand+"')").each(function() {
            if(jQuery(this).text() == cand)
            {
                jQuery(this).prev().click();
                jQuery(this).prev().find("i").removeClass("icon-plus-squared").addClass("icon-minus-squared");
                jQuery(this).css("font-weight","bold");
                jQuery(this).css("color","black");}})})
});
