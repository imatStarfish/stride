/*
 * Popup dialog javascript
 * by: starfish internet solutions
 * April 16, 2013
 * 
 * use 'class="openDialog"' for buttons intended to show the dialogs, also don't forget
 * that this buttons "id" attribute is used to determine which dialog to get.
 * 
 * dialogs must have or use the buttons "id" attribute partnerd with the word "Dialog" for
 * the dialog's "class" attribute
 * 
 * use 'class="closeDialog"' for buttons intended to hide the current dialog
 * 
 * 
 * */

;(function($){
	var methods = {
		init : function (options) {
			
			var elem	= $(this)
			var opts = $.extend({
				'background' 	: '#popUpBackground'
			}, options);
			
			return this.each(function(){
				elem.click(function(){
					dialogAction($(this),opts.background);
				});
			},function(){
				$('body').append('<div id="popUpBackground"></div>');
			});
		}
	
	}
	$.fn.popup = function(options){
		
		 // Method calling logic
	    if ( methods[options] ) {
	      return methods[ options ].apply( this, Array.prototype.slice.call( arguments, 1 ));
	    } else if ( typeof method === 'object' || ! options ) {
	      return methods.init.apply( this, arguments );
	    } else {
	      $.error( 'Method ' +  options + ' does not exist on popup dialogs' );
	    } 
	}
	
	function dialogAction(elem,background){
		if(elem.hasClass('openDialog')){
			var d = elem.attr('id')
			$('[class*="'+d+'Dialog"]').fadeIn();
			$(background).fadeIn();
		}
		else{
			elem.closest('div[class*="Dialog"],div[class*="popupDialog"]').fadeOut();
			$(background).fadeOut();
		}
	}
	
})(jQuery);