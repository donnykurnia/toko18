/*
 * Simple function to load a page using AJAX GET
 * then inject it into existing DOM, replacing old content
 *
 * Depends:
 *  jquery
 *  jquery.blockUI.js
 *  jquery.scrollTo.js (optional)
 *
 * Author: Donny Kurnia <donnykurnia@gmail.com>
 */

;(function($){
$.fn.load_page = function(message_text, href, callback, scroll){
  $(this).block({
    message: message_text
  });
  var elmt = $(this);
  $.get(href,
    { nbRandom: Math.random() },
    function(resp){
      elmt.unblock();
      elmt.html(resp);
      if (callback) {
        callback($, resp);
      }
      if (scroll) {
        $.scrollTo(elmt);
      }
    });
  return this;
};
})(jQuery);