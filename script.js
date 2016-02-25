jQuery(document).ready(function(){
  if (jQuery(window).height() >= jQuery(document).height()) {
    jQuery("a.top").css("display", "none");
  } else {
    jQuery("a.top").css("display", "block");
  }
});
