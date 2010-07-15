/**
 * @author donny
 *
 * required library:
 *   jQuery
 *   jQuery.metadata
 *   jQuery.livequery
 * required var:
 *    form
 */
jQuery(function($){
  //DOM loaded, code here
  //handle delete
  $(".delete").livequery('click', function(e){
    e.preventDefault();
    var data = $(this).metadata();
    if (confirm('Apakah Anda yakin untuk menghapus transaksi pembelian ini?')) {
      //confirmed, delete pembelian
      $("input[name='pembelian_id']").val(data.pembelian_id);
      $(form).submit();
    }
    return false;
  });
});