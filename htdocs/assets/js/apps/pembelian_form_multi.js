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
  //handle datepicker
  //date picker
  $('input[name^="tanggal_transaksi"]').datepicker({
    changeMonth: true,
    changeYear: true,
    dateFormat: 'yy-mm-dd'
  });
});