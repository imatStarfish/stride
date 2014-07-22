$(document).ready(function() {

	if($('#upload-image').length > 0){
		
			Dropzone.autoDiscover = false;
			
			var dz = new Dropzone("#upload-image", {
				init			: function() {
				
					this.on("addedfile", function(file) {
						$(".uploadingBtn").show();
						$(".cancelBtn").hide();
					});
					
					this.on("queuecomplete", function(file){
						$(".doneBtn").show();
						$(".uploadingBtn").hide();
					});
					
				},
				paramName     : "file",    // The name that will be used to transfer the file
				maxFilesize   : 1000000,   // MB
				acceptedFiles : "image/*", //accepted types
				fallback      : function(){
					$("form[name=photo]").attr("action", "/photo_library/images/uploadImageOldWay");
				},
				success		: function(fileObj, response){ //called after an image is uploaded(1 by 1)
					response = JSON.parse(response);
					
					if(response != undefined){
						if(response.success == true){
							fileObj.previewElement.classList.add("dz-success");
							
							if($(".imagesContainer[data-size_id="+response.size_id+"] #noImageContainer").length > 0)
								$("#noImageContainer").hide();
							
							var html = '\
								<div class="fleft mAm thumbWrap clearfix">\
								<div>\
								<a href="'+response.thumb_path+'" data-title="Default Title." data-lightbox="'+response.dimension+'" class="imageListingThumb lazy" style="background-image:url('+response.thumb_path+')">\
								</a>\
								</div>\
								<p class="imgName fleft">'+response.filename+'</p>\
								<div class="fright mVxs">\
								<span class="edit_image pointer glyphicon glyphicon-edit" data-image_id="'+response.image_id+'"></span>\
								<span class="delete_image  pointer glyphicon glyphicon-trash" data-image_id="'+response.image_id+'"></span>\
								</div>\
								</div>\
								';
							
							$(".imagesContainer[data-size_id="+response.size_id+"]").append(html);
							
						}
						else{
							fileObj.previewElement.classList.add("dz-error");
						}
					}
					else{
						fileObj.previewElement.classList.add("dz-error");
					}
				}
			});
			
			
			$(".doneBtn").click(function(){
				dz.removeAllFiles()
				$(".doneBtn").hide();
				$(".cancelBtn").show();
			});
			
	}
	
	
	
	
//	
//	Dropzone.options.uploadImage = {
//			  paramName: "file", // The name that will be used to transfer the file
//			  maxFilesize: 1000000, // MB
//			  acceptedFiles : "image/*", //accepted types
//			  fallback : function(){
//				  $("form[name=photo]").attr("action", "/photo_library/images/uploadImageOldWay");
//			  },
//			  success: function(fileObj, response){
//				  
//				  response = JSON.parse(response);
//				  
//				  if(response != undefined){
//					  if(response.success == true){
//						  fileObj.previewElement.classList.add("dz-success");
//						  
//						  var html = '\
//								<div class="fleft mAm thumbWrap clearfix">\
//									<div>\
//										<a href="'+response.thumb_path+'" data-title="Default Title." data-lightbox="'+response.dimension+'" class="imageListingThumb lazy" style="background-image:url('+response.thumb_path+')">\
//										 </a>\
//									</div>\
//									<p class="imgName fleft">'+response.filename+'</p>\
//									<div class="fright mVxs">\
//										<span class="edit_image pointer glyphicon glyphicon-edit" data-image_id="'+response.image_id+'"></span>\
//										<span class="delete_image  pointer glyphicon glyphicon-trash" data-image_id="'+response.image_id+'"></span>\
//									</div>\
//								</div>\
//							';
//						  
//						  $(".imagesContainer[data-size_id="+response.size_id+"]").append(html);
//						  
//					  }
//					  else{
//						  fileObj.previewElement.classList.add("dz-error");
//					  }
//				  }
//				  else{
//					  fileObj.previewElement.classList.add("dz-error");
//				  }
//			  }
//	};
	
});
	