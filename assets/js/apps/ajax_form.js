/**
 * @author donny
 *
 * required library:
 *   jQuery
 *   jQuery.form
 *   jQuery.blockUI
 *   json2
 * required var:
 *   blockui_image
 *   content_placeholder
 *   form
 *   load_message
 */
jQuery(function($){
  //DOM loaded, code here
  $(form).bind('form-pre-serialize', function(e) {
    $("input[name='ajax']").val("ajax");
  });
  //jquery.form options
  var options = {
    beforeSubmit: function(a,f,o){
      $.hide_all_messages();
      $(form).block({
        message: '<img src="'+blockui_image+'" /> '+load_message
      });
    },
    success: function(resp) {
      $(form).unblock();
      try {
        var r = JSON.parse(resp);
        if (r.new_content) {
          $(content_placeholder).html(r.new_content);
        }
        if (r.reload) {
          $.get(r.reload,
            {'nbRandom': Math.random()},
            function(get_resp){
              $(content_placeholder).html(get_resp);
            });
        }
        if (r.error_message) {
          $(form).display_message('error', r.error_message);
        }
        if (r.success_message) {
          $(form).display_message('success', r.success_message);
        }
      }
      catch (e) {}
    }
  };
  $(form).ajaxForm(options);
});