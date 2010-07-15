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
    if (confirm('Apakah Anda yakin untuk menghapus transaksi penjualan ini?')) {
      //confirmed, delete penjualan
      $("input[name='penjualan_id']").val(data.penjualan_id);
      $(form).submit();
    }
    return false;
  });
});