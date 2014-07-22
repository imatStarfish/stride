$(document).ready(function(){
	
//	prepareList();
	$('<span class="expand glyphicon glyphicon-plus"></span>').appendTo('.hasSecond');
	$('.expand').click(function(){
		$(this).toggleClass('glyphicon-plus glyphicon-minus')
		$(this).prev('.subNav').slideToggle();
		prepareList()
	});
	
	$('input[name="save"],.show_photo_chooser').click(function(){
		$('.modal-backdrop').addClass('in').removeClass('hide');
	});
});

function prepareList() {
	$('#nav_list').find('li:has(ul)').click( function(event) {
        if (this == event.target) {
        	$(this).toggleClass('collapsed');
        	$(this).toggleClass('expanded');
            $(this).children('ul').slideToggle('medium');
        }
        return false;
    })
    .addClass('collapsed')
    .children('ul').hide();
};