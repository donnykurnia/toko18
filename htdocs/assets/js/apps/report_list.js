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