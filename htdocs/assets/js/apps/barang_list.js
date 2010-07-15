/**
 * @author donny
 *
 * required library:
 *   jQuery
 *   jQuery.metadata
 *   jQuery.livequery
 * required var:
 *    blockui_image
 *    content_placeholder
 *    filter_form
 */
jQuery(function($){
  //DOM loaded, code here
  //handle delete
  $(".delete").livequery('click', function(e){
    e.preventDefault();
    var data = $(this).metadata();
    if (confirm('Apakah Anda yakin untuk menghapus barang ini?')) {
      //confirmed, delete barang
      $("input[name='barang_id']").val(data.barang_id);
      $("#delete_barang").submit();
    }
    return false;
  });
});