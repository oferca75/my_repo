function clickPar($el) {
    $el.click();
    $el.find("i").removeClass("icon-plus-squared").addClass("icon-minus-squared");
}
String.prototype.replaceAll = function(search, replacement) {
    var target = this;
    return target.replace(new RegExp(search, 'g'), replacement);
};
function catTreeNreadcrmbHighlight(click) {

    var text = click ? jQuery(this).text() :jQuery(this).attr("class").split(" ")[1];
    var cand = text.replaceAll(" ","-");
    jQuery("li.cat-item a." + cand).each(function () {
        var $el = jQuery(this).prev();
            if(click) {
                while($el.length > 0 && $el.hasClass("collapse")){
                    clickPar.call(this,$el);
                    jQuery($el).next().css("font-weight", "bold");
                    jQuery($el).next().css("color", "black");
                    $el = $el.parent().parent().prev().prev();
                }
            }
        else{
                jQuery($el).next().css("font-weight", "bold");
                jQuery($el).next().css("color", "black");

            }

    })
}
jQuery(document).ready(function ($) {
$(".widget_lastviewed .widgettitle").click(function(){
    $(".fight-path .lastViewedList").toggle("slow");
})

    jQuery.fx.off = true;
    setTimeout(function(){jQuery.fx.off= false},0)
    jQuery(".icon-minus-squared").parent().click();
    jQuery(".lastViewedcontent .lastViewedTitle").each(function(){
            catTreeNreadcrmbHighlight.call(this,true);
    })
    jQuery("#content article .entry-header .entry-title").each(function(){
            catTreeNreadcrmbHighlight.call(this,false);
    }
    )
});
