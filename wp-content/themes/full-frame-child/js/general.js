function clickPar($el) {
    if ($el.find("i").hasClass("icon-plus-squared")) {
        $el.click();
        $el.find("i").removeClass("icon-plus-squared").addClass("icon-minus-squared");
    }
}
String.prototype.replaceAll = function (search, replacement) {
    var target = this;
    return target.replace(new RegExp(search, 'g'), replacement);
};
replaceAll = function (target, search, replacement) {
    if (target)
        return target.replace(new RegExp(search, 'g'), replacement);
};
function catTreeNreadcrmbHighlight(click) {

    var text = click ? jQuery(this).text() : jQuery(this).attr("class").split(" ")[1];
    var cand = replaceAll(text, " ", "-");
    jQuery("li.cat-item a." + cand).each(function () {
        var $el = jQuery(this).prev();
        var vertex = false;
        while ($el.length > 0 && $el.parent().hasClass("cat-item")) {
            if(vertex || click) {
                clickPar.call(this, $el);
            }
            vertex = true;

            jQuery($el).next().css("font-weight", "bold");
            jQuery($el).next().css("color", "#316b1c !important");
            $el = $el.parent().parent().prev().prev();
        }

    })
}
jQuery(document).ready(function ($) {
    $(".widget_lastviewed .widgettitle").click(function () {
        $(".fight-path .lastViewedList").toggle("slow");
        jQuery('.next-step.next-arrow').toggle("slow")
        $(".widgettitle").toggle();
    })

    jQuery.fx.off = true;
    setTimeout(function () {
        jQuery.fx.off = false
        jQuery(".entry-header").css("visibility","visible");
    }, 0)
    jQuery(".icon-minus-squared").parent().click();
    var lastViewedArray = jQuery(".lastViewedcontent .lastViewedTitle");
    if (lastViewedArray.length == 0){
        jQuery(jQuery(".icon-plus-squared")[0]).parent().click();
    }
    lastViewedArray.each(function () {
        catTreeNreadcrmbHighlight.call(this, true);
    })

    jQuery("#content article .entry-header .entry-title").each(function () {
            catTreeNreadcrmbHighlight.call(this, false);
        }
    )


    var thumbArray = jQuery("#jssor_1 .yarpp-thumbnail");
    if (thumbArray.length > 0)
        {
            var thumbWidth = jQuery(thumbArray[0]).css("width").replace(/\D/g, '');
            var nextArrow = jQuery('.next-arrow');
            var containerWidth = nextArrow.length > 0 ? nextArrow.offset().left * 2:jQuery("#jssor_1").css("width").replace("px","");
            var thumbsWidth = thumbWidth * thumbArray.length;
            if (thumbsWidth < containerWidth)
                {
                    var leftDistPx = Math.max(0,(containerWidth - thumbsWidth) / 2 - thumbWidth);
                    leftDistPx += "px";
                    thumbArray.each(function(){
                        jQuery(this).parent().css("left",leftDistPx)
                    })
                }

        }
});
