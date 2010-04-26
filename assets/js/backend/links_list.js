/**
 * @author donny
 *
 * required library:
 *   jQuery
 *   jQuery.metadata
 *   jQuery.livequery
 * required var:
 */
jQuery(function($){
  //DOM loaded, code here
  //jquery.form options
  var options = {
    beforeSubmit: function(a,f,o){
      $.hide_all_messages();
      $("input[name='ajax']").val("ajax");
      form.block({
        message: '<img src="'+blockui_image+'" /> Saving...'
      });
    },
    success: function(resp) {
      form.unblock();
      try {
        var r = JSON.parse(resp);
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
  //handling country change
  var change_country_options = {
    beforeSubmit: function(a,f,o){
      $.hide_all_messages();
      $("input[name='ajax']").val("ajax");
      $("#main_content").block({
        message: '<img src="'+blockui_image+'" /> Loading...'
      });
    },
    success: function(resp) {
      $("#main_content").unblock();
      $("#main_content").html(resp);
      if ($('select[name="country"]').val() > 0){
        $('input').removeAttr('disabled');
      }
      else{
        $('input').attr('disabled','disabled');
      }
    }
  };
  change_country_form.ajaxForm(change_country_options);
  $('select[name="country"]').change(function(e){
    change_country_form.submit();
  });
});