/**
 * @author donny
 *
 * required library:
 *   jQuery
 *   jQuery.form
 *   jQuery.blockUI
 *   json2
 * required var:
 *   form
 *   blockui_image
 */
jQuery(function($){
  //DOM loaded, code here
  //jquery.form options
  var options = {
    beforeSubmit: function(a,f,o){
      $.hide_all_messages();
      $("input[name='ajax']").val("ajax");
      form.block({
        message: '<img src="'+blockui_image+'" /> Sending...'
      });
    },
    success: function(resp) {
      form.unblock();
      try {
        var r = JSON.parse(resp);
        if (r.reload) {
          setTimeout(function(){
            window.top.location.assign(r.reload);
          }, 2000);
        }
        if (r.error_message) {
          form.display_message('error', r.error_message);
        }
        if (r.success_message) {
          form.display_message('success', r.success_message);
        }
      }
      catch (e) {}
    }
  };
  form.ajaxForm(options);
});