/**
 * @author donny
 *
 * required library:
 *   jQuery
 *   jQuery.dataTable
 * required var:
 *    start
 *    per_page
 *    dataTableSource
 */
jQuery(function($){
  //DOM loaded, code here
  //dataTable
  $('#penjualan_table').dataTable({
    "aoColumns": [
      { "bSortable": false }, 
      null,
      null,
      null,
      null,
      null,
      null,
      null,
      { "bSortable": false }
    ],
    "aaSorting": [[ 1, "desc" ]],
    "bFilter": true,
    "bProcessing": true,
    "bServerSide": true,
    "iDisplayStart": start,
    "iDisplayLength": per_page,
    "sAjaxSource": dataTableSource,
    "sPaginationType": 'full_numbers'
  });
  $("#paging_area").remove();
});