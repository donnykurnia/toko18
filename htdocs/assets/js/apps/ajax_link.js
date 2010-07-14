/**
 * @author donny
 *
 * required library:
 *   jQuery
 *   jQuery.livequery
 *   jQuery.blockUI
 *   load_page
 * required var:
 *   blockui_image
 *   content_placeholder
 */
jQuery(function($){
  //DOM loaded, code here
  //pagination handler
  $(".ajax_link").livequery('click', function(e){
    e.preventDefault();
    var href = $(this).attr("href");
    $(content_placeholder).load_page('<img src="'+blockui_image+'" /> Loading...', href);
    return false;
  });
});