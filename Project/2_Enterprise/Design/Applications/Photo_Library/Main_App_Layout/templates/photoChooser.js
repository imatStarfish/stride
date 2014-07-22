$(document).ready(function(){

	texteditor = false;
	divImageObject = "";
	
	//show photo chooser
	$('span.show_photo_chooser,img.show_photo_chooser, .cke_button__image').live('click',function(){
		
		$("html, body").animate({ scrollTop: 0 }, "fast");
		
		//remove the ckeditor dialog
		$('.cke_editor_page_content_editor_dialog').hide();
		
		//check if the command is from from ck editor
		if($(this).hasClass('cke_button__image'))
		{
			texteditor = true;
			editor = CKEDITOR.currentInstance;
		}
		else
		{
			texteditor = false;
			divImageObject = $(this).prev().prev();
		}
		
		
		//show dim
		php.beforeSend = function(){
			$('.modal-backdrop').removeClass('hide');
		}
		
		$.php("/ajax/photo_library/images/loadPhotoChooser");
		
		//hide dim
		php.complete = function(){
			$('.modal-backdrop').addClass('hide');
			$("#photo_chooser").show();
		}
		
	});
	
	//-------------------------------------------------------------------------------------------------	
	//cancel photo_chooser
	$("#cancel_photo_chooser").live("click", function(){
		//trigger the cancel button on editor
		$('.cke_editor_page_content_editor_dialog').hide();
		
		$("#photo_chooser").hide();
	});
	
	
	//-------------------------------------------------------------------------------------------------	
	//load photo chooser images
	$(".show_images").live("click", (function(){

		//show dim
		php.beforeSend = function(){
			$('.modal-backdrop').removeClass('hide');
		}
		
		album_id = $(this).attr("data-album_id");
		$.php("/ajax/photo_library/images/loadPhotoChooserImages", {album_id:album_id});

		//hide dim
		php.complete = function(){
			$('.modal-backdrop').addClass('hide');
		}
		
	}));
	
	
	//--------------------------------------------------------------------------------------------------
	//show use image popup
	$(".show_use_image_popup").live("click", function(){
		image_path  = $(this).attr("data-image_path");
		console.log(image_path);
		image_id 	= $(this).attr("data-image_id"); 
		
		$("#use_image_popup #image_path").val(image_path);
		$("#use_image_popup #image_id").val(image_id);
		$('#use_image_popup').modal('show');
	});
	
	
	//--------------------------------------------------------------------------------------------------
	//use the image
	$("#use_image").live("click", function(){
		
		image_path = $("#use_image_popup #image_path").val();
		image_id   = $("#use_image_popup #image_id").val();
		
		
		//do this if the command is from ck editor
		if(texteditor)
		{
			$('.cke_editor_page_content_editor_dialog').hide();
			$('#use_image_popup').modal('hide');
			$("#photo_chooser").hide();
			
			editor.insertHtml('<img src="'+image_path+'" />');
		}
		else
		{
			divImageObject.find("#img").css("background-image", "url(" + image_path + ")");  
			divImageObject.next().val(image_id);
			
			$('#use_image_popup').modal('hide');
			$("#photo_chooser").hide();
		}
	});
	
	
});