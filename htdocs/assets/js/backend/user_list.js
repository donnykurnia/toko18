/**
 * @author donny
 *
 * required library:
 *   jQuery
 *   jQuery.metadata
 *   jQuery.livequery
 * required var:
 */
jQuery(function($){
  //DOM loaded, code here
  //handle delete
  $(".delete").livequery('click', function(e){
    e.preventDefault();
    var data = $(this).metadata();
    if (confirm('Are you sure to delete this user?')) {
      //confirmed, delete user
      $("input[name='uid']").val(data.uid);
      $("#delete_user").submit();
    }
    return false;
  });
});