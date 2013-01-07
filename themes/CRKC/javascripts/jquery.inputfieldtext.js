jQuery.fn.inputFieldText = function(string, hintClass) {
  this.each(function() {
	  jQuery(this).addClass(hintClass).val(string);
	  jQuery(this).focus(function(){
        if ($(this).val() == string){
          $(this).removeClass(hintClass).val('');
        }
      });
	  jQuery(this).blur(function(){
        if ($(this).val() == '' ){
        	jQuery(this).addClass(hintClass).val(string);
        }
      }); 
  });
}