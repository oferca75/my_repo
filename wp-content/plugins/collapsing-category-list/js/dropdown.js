var collapsCatList = jQuery.noConflict();

collapsCatList(document).ready(function(){
  //collapsCatList('.children').slideUp();
	
  collapsCatList('.cat-item a[class*="expand"]').closest('.cat-item').find('ul.children:first').slideUp();

  collapsCatList('.cat-item a[class*="expand"], .cat-item a[class*="collapse"]').click(function(){
		if (collapsCatList(this).attr('href') == '#') {
			var display_val = collapsCatList(this).closest('.cat-item').find('ul.children:first').css('display');
			var is_image = collapsCatList(this).find('img').length;

			if (display_val == 'none'){
				if (is_image > 0) {
					var src = collapsCatList(this).children('img').attr("src");
					src = src.replace("expand.gif", "collapse.gif");
					src = src.replace("expand_neg.gif", "collapse_neg.gif");
				}
				else {
					if (collapsCatList(this).find('i').hasClass('icon-plus-squared')) {
						collapsCatList(this).find('i').addClass('icon-minus-squared').removeClass('icon-plus-squared');
					}
					else if(collapsCatList(this).find('i').hasClass('icon-plus-squared-alt')) {
						collapsCatList(this).find('i').addClass('icon-minus-squared-alt').removeClass('icon-plus-squared-alt');
					}
				}
			}
			else {
				if (is_image > 0) {
					var src = collapsCatList(this).children('img').attr("src");
					src = src.replace("collapse.gif", "expand.gif");
					src = src.replace("collapse_neg.gif", "expand_neg.gif");
				}
				else {
					if (collapsCatList(this).find('i').hasClass('icon-minus-squared')) {
						collapsCatList(this).find('i').addClass('icon-plus-squared').removeClass('icon-minus-squared');
					}
					else if(collapsCatList(this).find('i').hasClass('icon-minus-squared-alt')) {
						collapsCatList(this).find('i').addClass('icon-plus-squared-alt').removeClass('icon-minus-squared-alt');
					}
				}
			}

			if (is_image > 0) { 
				collapsCatList(this).children('img').attr("src", src);
			}

			collapsCatList(this).closest('.cat-item').find('ul.children:first').slideToggle();

			return false;
		}
  });
	
	// With hide_icon = true;
});


