/**
 * @author donny
 *
 * require: jQuery
 * require: jQuery.livequery
 */
//display process message
;(function($){
$.fn.display_message = function(message_type, message_text){
  var message_parent;
  //check if message placeholder is exist in page or not
  if ($(".message_area", this).length == 0){
    message_parent = $('<div class="ui-widget message_area noprint"></div>');
    this.prepend(message_parent);
  }
  else {
    message_parent = $(".message_area", this);
  }
  //determine class and icon for message
  var state = 'ui-state-error';
  var icon = 'ui-icon-alert';
  if (message_type == 'success')
  {
    state = 'ui-state-highlight';
    icon = 'ui-icon-info';
  }
  //build the html
  var message_html = [
    '<div class="',state,' ui-corner-all">',
    '<span class="ui-icon ',icon,'"></span>',
    '<p>',
    message_text,
    '</p>',
    '</div>'
  ].join('');
  //append the message
  message_parent.append(message_html);
  return this;
};
$.extend($, {
  hide_all_messages: function(){
    var message_parent = $(".message_area");
    $('.ui-state-highlight,.ui-state-error').hide().remove();
    //remove parent if empty
    if (message_parent.children().length == 0){
      message_parent.hide().remove();
    }
    return this;
  }
});
})(jQuery);
//handle click in message
jQuery(function($){
  $('.ui-state-highlight,.ui-state-error', ".message_area").livequery('click', function(e){
    e.preventDefault();
    var message_parent = $(this).parent();
    $(this).hide().remove();
    //remove parent if empty
    if (message_parent.children().length == 0){
      message_parent.hide().remove();
    }
    return false;
  });
});