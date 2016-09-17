isMobile = isMobile();

function clickPar($el) {
  if ($el.find("i").hasClass("icon-plus-squared")) {
    $el.click();
    $el.find("i").removeClass("icon-plus-squared").addClass("icon-minus-squared");
  }
}
String.prototype.replaceAll = function(search, replacement) {
  var target = this;
  return target.replace(new RegExp(search, 'g'), replacement);
};
replaceAll = function(target, search, replacement) {
  if (target)
    return target.replace(new RegExp(search, 'g'), replacement);
};

function catTreeNreadcrmbHighlight(click) {

  var text = click ? jQuery(this).text() : jQuery(this).attr("class").split(" ")[1];
  var cand = replaceAll(text, " ", "-");
  jQuery("li.cat-item a." + cand).each(function() {
    var $el = jQuery(this).prev();
    if (jQuery($el).attr("visited")) {
      return;
    }

    var vertex = false;
    while ($el.length > 0 && $el.parent().hasClass("cat-item")) {
      jQuery($el).attr("visited", "1");
      $parent = $el.parent().parent().prev().prev();
      if (jQuery($parent).attr("visited")) {
        return
      }
      if (vertex || click) {
        clickPar.call(this, $el);
      }
      if (!vertex && !click)
          highlightElem(jQuery($el).next());

      vertex = true;

      jQuery($el).next().css("font-weight", "bold");
      jQuery($el).next().css("font-weight", "19px");
      jQuery($el).next().css("text-shadow", "0.51px 0.5px 0.5px #FF0000");


      jQuery($el).prev().click();
      $el = $parent;
    }

  })
}
jQuery(".featured-heading-wrap>p>b, b.search-word").click(function() {
  jQuery("#header-toggle").click()
})

function isMobile() {
  isMobile = false;
  if (/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|ipad|iris|kindle|Android|Silk|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i.test(navigator.userAgent) ||
    /1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(navigator.userAgent.substr(0, 4))) isMobile = true;
  return isMobile;
}

function setCookie(cname, cvalue, exdays) {
  var d = new Date();
  d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
  var expires = "expires=" + d.toUTCString();
  document.cookie = cname + "=" + cvalue + ";path=/;" + expires;
}

function getCookie(cname) {
  var name = cname + "=";
  var ca = document.cookie.split(';');
  for (var i = 0; i < ca.length; i++) {
    var c = ca[i];
    while (c.charAt(0) == ' ') {
      c = c.substring(1);
    }
    if (c.indexOf(name) == 0) {
      return c.substring(name.length, c.length);
    }
  }
  return "";
}

function createPlayButtonEvents() {
  jQuery.fx.off = true;
  setTimeout(function() {
    jQuery.fx.off = false
    jQuery(".entry-header").css("visibility", "visible");
    if (isMobile)
      jQuery(".play-button").each(function() {
        jQuery(this).hide();
        jQuery(this).prev().remove();
        jQuery(this).parent().append("<iframe src=\"https://www.youtube.com/embed/" + jQuery(this).attr("id") + "?autoplay=1\" frameborder=\"0\"></iframe>");
      });
    jQuery(".video-thumb").css("visibility", "visible");


  }, 0)
}

function closeOpenTreeNodes() {
//   setTimeout(function() {

    jQuery(".icon-minus-squared").each(function() {
      jQuery(this).parent().click();
    })
    setTimeout(function() {
      jQuery(".widget_categories a").css("opacity", "1");
    }, 0)
//   }, 0)

}

function doHilighting(lastVisited) {
  var lastHighlighted;
  var visitedPosition;
  var lastPositions = [];
  var counter = 0;
  while(counter < 5 && lastVisited.length > 0 ) {
    visitedPosition = lastVisited.pop();
    counter++;
    lastPositions.push(visitedPosition);
    var $visitedPosition = jQuery(".widget_categories ." + visitedPosition);
    if (!lastHighlighted || $visitedPosition.parent().find("." + lastHighlighted).length > 0) {

      if (!lastHighlighted) {
        highlightElem(jQuery($visitedPosition));
      }
      jQuery($visitedPosition).css("font-weight", "bold");
      jQuery($visitedPosition).css("font-weight", "19px");
      jQuery($visitedPosition).css("text-shadow", "0.51px 0.5px 0.5px #FF0000");
      jQuery($visitedPosition).prev().click();
    }
    lastHighlighted = visitedPosition;
  }
  return lastPositions;

}

function highlightElem($el){
  $el.css("background", "#7E1418").css("color", "white").css("line-height", "32px").css("padding", "3px 8px").css("font-weight", "bolder").css("font-size", "18px").css("border-radius", "3px")
}

function highlight(lastVisited) {
  var firstTime = true;
  var $visitedPosition = jQuery(".widget_categories ." + lastVisited);
  highlightElem(jQuery($visitedPosition));
  while($visitedPosition.parent().hasClass("cat-item")){
    jQuery($visitedPosition).css("font-weight", "bold");
    jQuery($visitedPosition).css("font-weight", "19px");
    jQuery($visitedPosition).css("text-shadow", "0.51px 0.5px 0.5px #FF0000"); 
    jQuery($visitedPosition).prev().click();
    $visitedPosition = jQuery($visitedPosition).parent().parent().prev();
  }

}
function delete_cookie( name ) {
  document.cookie = name + '=; expires=Thu, 01 Jan 1970 00:00:01 GMT;';
}
function highlightTreePath() {
  var cookieName = "last_visited_5";
  var currentPositionId = jQuery("#content article .entry-header .entry-title").attr("class").split(" ")[1];
  var lastVisitedCookie = getCookie(cookieName);
  try {
    lastVisited = jQuery.map(JSON.parse(lastVisitedCookie), function(value, index) {
      return [value];
    });
  } catch (err) {
    lastVisited = [];
  }
  lastVisited.push(currentPositionId);
  var lastPositions = highlight(lastVisited);
 
  setCookie(cookieName, JSON.stringify(lastPositions), 1000);
}

jQuery(document).ready(function($) {

  createPlayButtonEvents();

  closeOpenTreeNodes();

  if (location.pathname !== "/") {
    highlightTreePath();

    //     setTimeout(function() {
    //       var lastViewedArray = jQuery(".lastViewedcontent .lastViewedTitle");

    //       lastViewedArray.each(function() {
    //         //catTreeNreadcrmbHighlight.call(this, true);
    //       })

    //       jQuery("#content article .entry-header .entry-title").each(function() {
    //         //catTreeNreadcrmbHighlight.call(this, false);
    //       })
    //     }, 0)
  } else {
    jQuery(".Top-Positions").prev().click();
  }


});