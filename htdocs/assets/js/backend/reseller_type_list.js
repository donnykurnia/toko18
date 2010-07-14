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
    if (confirm('Are you sure to delete this reseller type?')) {
      //confirmed, delete reseller type
      $("input[name='id']").val(data.id);
      $("#delete_reseller_type").submit();
    }
    return false;
  });
});