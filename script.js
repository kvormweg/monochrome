jQuery(document).ready(function(){
  if (jQuery(window).height() >= jQuery(document).height()) {
    jQuery("a.top").css("display", "none");
  } else {
    jQuery("a.top").css("display", "block");
  }
  jQuery("nav.mainmenu ul.idx > li.closed > div.li").each(function() {
    jQuery(this).prepend('<span class="inout closed">&nbsp;</span> ');
  });
  jQuery("nav.mainmenu ul.idx li div.li > span.inout").click(function() {
    if(jQuery(this).parent("div.li").parent("li").hasClass("closed")) {
      jQuery(this).parent("div.li").parent("li.closed").removeClass("closed").addClass("open");
      jQuery(this).removeClass("closed").addClass("open");
    } else {
      jQuery(this).parent("div.li").parent("li.open").removeClass("open").addClass("closed");
      jQuery(this).removeClass("open").addClass("closed");
    }
  });
});
