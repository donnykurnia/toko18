/**
 * @author donny
 *
 * required library:
 *   jQuery
 *   jQuery.metadata
 *   jQuery.livequery
 * required var:
 *    form
 *    blockui_image
 *    content_placeholder
 */
jQuery(function($){
  //DOM loaded, code here
  //date picker
  var dates = $('#start_date, #end_date').datepicker({
    changeMonth: true,
    changeYear: true,
    dateFormat: 'yy-mm-dd',
    onSelect: function(selectedDate) {
      var option = this.id == "start_date" ? "minDate" : "maxDate";
      var instance = $(this).data("datepicker");
      var date = $.datepicker.parseDate(instance.settings.dateFormat || $.datepicker._defaults.dateFormat, selectedDate, instance.settings);
      dates.not(this).datepicker("option", option, date);
    }
  });
  //jquery.form options
  $(form).ajaxForm({
    beforeSubmit: function(a,$form,o){
      $.hide_all_messages();
      $form.block({
        message: '<img src="'+blockui_image+'" /> '+load_message
      });
    },
    success: function(resp, statusText, $form) {
      $form.unblock();
      $(content_placeholder).html(resp);
    }
  });
});