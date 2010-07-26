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
  $('#report_table').dataTable({
    "aoColumns": [
      { "bSortable": false }, 
      null,
      null,
      null,
      null,
      null,
      null,
      null
    ],
    "aaSorting": [[ 1, "desc" ]],
    "bFilter": true,
    "bProcessing": true,
    "bServerSide": true,
    "iDisplayStart": start,
    "iDisplayLength": per_page,
    "sAjaxSource": dataTableSource,
    "sPaginationType": 'full_numbers',
    "fnServerData": function ( sSource, aoData, fnCallback ) {
      /* Add some extra data to the sender */
      aoData.push({
        "name": "more_data",
        "value": "my_value"
      });
      $.getJSON( sSource, aoData, function (json) { 
        /* Do whatever additional processing you want on the callback, then tell DataTables */
      });
    }
  });
  $("#paging_area").remove();
});