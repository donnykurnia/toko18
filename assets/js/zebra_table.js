;
jQuery.fn.zebra_table = function(){
  this.removeClass("odd");
  this.filter(":nth-child(even)").addClass("odd");
  return this;
};
