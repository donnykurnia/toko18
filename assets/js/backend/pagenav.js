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
 */
jQuery(function($){
  //DOM loaded, code here
  //pagination handler
  $(".pagenav").livequery('click', function(e){
    e.preventDefault();
    var href = $(this).attr("href");
    $("#main_content").load_page('<img src="'+blockui_image+'" /> Loading...', href);
    return false;
  });
});