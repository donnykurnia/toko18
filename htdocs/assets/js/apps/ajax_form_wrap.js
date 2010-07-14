/**
 * @author donny
 *
 * required library:
 *   jQuery
 *   jQuery.form
 *   jQuery.blockUI
 *   json2
 *   message_handler
 * required var:
 *   form
 *   blockui_image
 *   message_area
 *   content_placeholder
 *   load_message
 */

jQuery(function($){
  //DOM loaded, code here
  //jquery.form options
  $(form).ajaxForm({
    beforeSerialize: function($form, options) { 
      $("input[name='ajax']", $form).val("ajax");
    },
    beforeSubmit: function(a,$form,o){
      $.hide_all_messages();
      $form.block({
        message: '<img src="'+blockui_image+'" /> '+load_message
      });
    },
    success: function(resp, statusText, $form) {
      $form.unblock();
      try {
        var resp_val = $(resp).get(0).value;
        var r = JSON.parse(resp_val);
        if (r.new_content) {
          $(content_placeholder).html(r.new_content);
        }
        if (r.reload) {
          setTimeout(function(){
            window.top.location.assign(r.reload);
          }, 2000);
        }
        if (r.error_message) {
          $(message_area).display_message('error', r.error_message);
        }
        if (r.success_message) {
          $(message_area).display_message('success', r.success_message);
        }
        if (typeof(callback) == 'function') {
          callback(resp);
        }
      }
      catch (e) {}
    }
  });
});