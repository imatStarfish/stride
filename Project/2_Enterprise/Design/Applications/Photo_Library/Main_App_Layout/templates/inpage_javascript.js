$(document).ready(function() {

	$("lazy").lazyload({
		effect : "fadeIn"
	});
	
	
	$(".delete_album").click(function(){
		album_id = $(this).attr("data-album_id");
		$("#delete_album_dialog input[name=album_id]").val(album_id);
		
		$("#delete_album_dialog").fadeIn();
	});

	//UPLOAD PHOTO
	$(".uploadPhoto").click(function(){
		dimension = $(this).attr("data-dimensions");
		size_id   = $(this).attr("data-size_id");
		
		$("#uploadImage input[name=dimension]").val(dimension);
		$("#uploadImage input[name=size_id]").val(size_id);
	});
	
	//edit albbum popup
	$(".edit_album").click(function(){
		album_id    = $(this).attr("data-album_id");
		album_title = $(this).attr("data-album_title");

		$("#edit_album_dialog input[name=album_id]").val(album_id);
		$("#edit_album_dialog input[name=album_title]").val(album_title);
		$("#edit_album_dialog").fadeIn();
	});
	
	
	//upload photo
	$("input[name=upload_photo]").click(function(e){
		e.preventDefault();
		if($("#fakeInputFile-text span.filename").text() != '')
		{
			image_sizes = $("select[name=image_sizes]").val();
			
			if(image_sizes == null)
				$("#upload-dialog #error").html("Please create an image size first to upload image");
			else
				$("#upload_image").submit();
		}
		else
		{
			alert("Please choose an image");
		}
	});
	
	//delete iamge
	$(document).on("click", ".delete_image", function(){
		image_id = $(this).attr("data-image_id");
		
		$.php("/ajax/photo_library/images/checkIfImageIsUsed", {image_id:image_id});
		
		//the pop ups is in the ajax controller
		php.complete = function(){
		}
	});
	
	
	$("#add_album_size").click(function(){
		$("#new_album_size_dialog").fadeIn();
	});
	
	$(".delete_size").click(function(){
		size_id = $(this).attr("data-size_id");
		
		$("#delete-album-size-dialog input[name=size_id]").val(size_id);
		$("#delete-album-size-dialog").fadeIn();
		
	});
	
	//----------------------------------------------------------------------------------------------------
	//FAKE INPUT TYPE FILE
	$('.trueInputFile').change(function(){
		var trueValue = $(this).attr('value');
		trueValue = trueValue.replace('C:\\fakepath\\','');
		var hasValue = $('#fakeInputFile-text').text();
		if(hasValue){
			$('#fakeInputFile-text .filename').text(' ');
		}
		$('#fakeInputFile-text .filename').text(trueValue);
		
	});
	
	//----------------------------------------------------------------------------------------------------
	//SHOWING OF IMAGE EDITOR
	$(".edit_image").live("click", (function(){
		image_id = $(this).attr("data-image_id");
		
		$.php("/ajax/photo_library/images/loadImageEditor", {image_id:image_id});
		
		php.complete = function(){
			//you will find this at main javascript
			$('#photo_details_holder').modal('toggle');
			reInitializedPlugins();
			
			//you will find this at cropper.js
			showThumbnailCropper();
			showCropper();
//			$("#photo_details_holder").fadeIn();
		}
		
	}));
	
	
	//----------------------------------------------------------------------------------------------------
	//CHANGING THE VALUE OF DROPDOWN FOR IMAGE SIZES
	$("#photo_details_holder select[name=album_id]").live("change", function(){
		album_id = $(this).val();
		$("#photo_details_holder select[name=image_sizes]").prop("disabled", true);
		
		$.php("/ajax/photo_library/images/getImageGroupSizesOnAlbum", {album_id:album_id});
		
		php.complete = function(){
			$("#photo_details_holder select[name=image_sizes]").prop("disabled", false);
		}
	});
	
	
	//----------------------------------------------------------------------------------------------------
	//CREATING AN ALBUM
	$("input[name=add_album]").live("click", (function(e){
		e.preventDefault();
		
		album_title  = $("form#add_album_form input[name=album_title]").val();
		
		//submitting of forms and displaying errors are done in ajax controllers
		$.php("/ajax/photo_library/images/checkIfFolderExist", {album_title:album_title});
		
	}));
	
	//-----------------------------------------------------------------------------------------------------------
	//CREATE ALBUM SIZE
	$("input[name=add_size]").live("click", (function(e){
		e.preventDefault();
		dimensions   = $("form#add_album_size_form input[name=width]").val() + "x" + $("form#add_album_size_form input[name=height]").val();
		album_id     = $("form#add_album_size_form input[name=album_id]").val();
		
		//submitting of forms and displaying errors are done in ajax controllers
		$.php("/ajax/photo_library/images/checkIfAlbumSizeExist", {dimensions:dimensions, album_id:album_id});
	}));
});


